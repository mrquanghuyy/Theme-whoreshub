<?php

define('THEME_URL', get_stylesheet_directory());
define('CORE', THEME_URL . '/core');
define('WIDGET', THEME_URL . '/widget');
define('SIDEBARTEMPLADE', THEME_URL . '/templates/rightbar');
define('THEMETEMPLADE', THEME_URL . '/templates');


add_action('init', function() {
    register_nav_menus([
        'primary-menu' => __('Primary Menu', 'ophim'),
    ]);
});

require_once(CORE . '/init.php');
require_once(THEME_URL . '/inc/demo.php');
require_once(THEME_URL . '/inc/register_sidebar.php');
require_once(THEME_URL . '/inc/ajax.php');
require_once(THEME_URL . '/inc/front.php');
require_once(THEME_URL . '/inc/age-verify.php');
require_once(WIDGET . '/wg_ophim_categories.php');
require_once(WIDGET . '/wg_ophim_footer.php');

function custom_pre_get_posts($query) {
    if (!is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_archive() || $query->is_search())) {
        $query->set('post_type', array('post', 'ophim'));
    }
}
add_action('pre_get_posts', 'custom_pre_get_posts');

function taxonomy_orderby_modified($query) {
    if (!is_admin() && $query->is_main_query() && is_tax()) {
        $query->set('orderby', 'modified');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'taxonomy_orderby_modified');

function flush_rules_on_activation() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rules_on_activation');
add_filter('query_vars', function($vars) {
    $vars[] = 'actors_list';
    return $vars;
});
add_action('init', function() {
    $slug = get_option('ophim_slug_actors') ?: 'actors';
    $slug = trim($slug, '/');
    add_rewrite_rule('^' . preg_quote($slug, '/') . '/?$', 'index.php?actors_list=1', 'top');
    add_rewrite_rule('^' . preg_quote($slug, '/') . '/page/([0-9]+)/?$', 'index.php?actors_list=1&paged=$matches[1]', 'top');
    add_rewrite_rule('^page/([0-9]+)/?$', 'index.php?paged=$matches[1]', 'top');
}, 1);

add_action('init', function() {
    if (is_admin() || get_option('ophim_whoreshub_paged_rewrite_flushed')) {
        return;
    }
    $rules = get_option('rewrite_rules');
    if (empty($rules) || !isset($rules['^page/([0-9]+)/?$']) || $rules['^page/([0-9]+)/?$'] !== 'index.php?paged=$matches[1]') {
        flush_rewrite_rules(false);
        update_option('ophim_whoreshub_paged_rewrite_flushed', 1);
    }
}, 99);

add_action('template_redirect', function() {
    if (!is_404()) {
        return;
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $uri = preg_replace('#\?.*$#', '', $uri);
    $uri = untrailingslashit($uri);
    if (!preg_match('#^/page/([1-9][0-9]*)$#', $uri, $m)) {
        return;
    }
    $paged = (int) $m[1];
    global $wp_query;
    $wp_query->set('paged', $paged);
    $wp_query->set('post_type', array('post', 'ophim'));
    $wp_query->is_404 = false;
    $wp_query->is_home = true;
    $wp_query->is_archive = false;
    $wp_query->is_singular = false;
    status_header(200);
    nocache_headers();
    include get_stylesheet_directory() . '/index.php';
    exit;
}, 1);

add_filter('template_include', function($template) {
    if ((int) get_query_var('actors_list')) {
        $t = get_stylesheet_directory() . '/page-actors.php';
        if (file_exists($t)) return $t;
    }

    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $uri = preg_replace('#\?.*$#', '', $uri);
    $uri = trim(untrailingslashit($uri), '/');
    if ($uri === 'categories') {
        $t = get_stylesheet_directory() . '/page-categories.php';
        if (file_exists($t)) return $t;
    }

    return $template;
});

add_filter('document_title_parts', function($title) {
    if (is_admin()) {
        return $title;
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? preg_replace('#\?.*$#', '', $_SERVER['REQUEST_URI']) : '';
    $uri = trim(untrailingslashit($uri), '/');
    if (preg_match('#^page/([1-9][0-9]*)$#', $uri, $m)) {
        $title['title'] = get_bloginfo('name', 'display');
        $title['page'] = '';
        return $title;
    }
    if (is_home() && (int) get_query_var('paged') > 0) {
        $title['title'] = get_bloginfo('name', 'display');
        $title['page'] = '';
    }
    return $title;
}, 20);

add_filter('document_title', function($title) {
    if (is_admin()) {
        return $title;
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? preg_replace('#\?.*$#', '', $_SERVER['REQUEST_URI']) : '';
    $uri = trim(untrailingslashit($uri), '/');
    if (preg_match('#^page/([1-9][0-9]*)$#', $uri, $m)) {
        return get_bloginfo('name', 'display');
    }
    return $title;
}, 20);

add_filter('pre_handle_404', function($preempt, $wp_query) {
    if ($preempt || !$wp_query->is_main_query()) {
        return $preempt;
    }
    $uri = isset($_SERVER['REQUEST_URI']) ? preg_replace('#\?.*$#', '', $_SERVER['REQUEST_URI']) : '';
    $uri = untrailingslashit($uri);
    if (!preg_match('#^/page/([1-9][0-9]*)$#', $uri, $m)) {
        return $preempt;
    }
    return true;
}, 1, 2);

/**
 * Tự tạo trang + chèn link sẵn vào menu primary-menu.
 */
function ophim_whoreshub_seed_pages_and_menu() {
    if (get_option('ophim_whoreshub_seed_pages_v1')) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/post.php';

    $ensure_page = function (string $slug, string $title, string $content) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page && !empty($page->ID)) {
            return (int) $page->ID;
        }

        $insert_id = wp_insert_post([
            'post_type'    => 'page',
            'post_name'    => $slug,
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
        ], true);

        if (is_wp_error($insert_id)) {
            return 0;
        }

        return (int) $insert_id;
    };

    $page_about      = $ensure_page('about-us', 'About Us', '<p>About Us</p>');
    $page_privacy    = $ensure_page('privacy', 'Privacy', '<p>Privacy</p>');
    $page_contact    = $ensure_page('contact-us', 'Contact Us', '<p>Contact Us</p>');
    $page_categories = $ensure_page('categories', 'Categories', '<p>Categories</p>');

    $locations = get_nav_menu_locations();
    $menu_id = isset($locations['primary-menu']) ? (int) $locations['primary-menu'] : 0;

    if ($menu_id <= 0) {
        $menu_id = wp_create_nav_menu('Primary Menu');
        if (!is_wp_error($menu_id) && $menu_id > 0) {
            $theme_locations = get_theme_mod('nav_menu_locations');
            if (!is_array($theme_locations)) {
                $theme_locations = [];
            }
            $theme_locations['primary-menu'] = $menu_id;
            set_theme_mod('nav_menu_locations', $theme_locations);
        }
    }

    if ($menu_id <= 0) {
        update_option('ophim_whoreshub_seed_pages_v1', 1);
        return;
    }

    $existing_items = wp_get_nav_menu_items($menu_id);
    $existing_by_url = [];
    $existing_by_obj = [];

    foreach ((array) $existing_items as $it) {
        if (!empty($it->url)) {
            $existing_by_url[$it->url] = (int) $it->ID;
        }
        if (!empty($it->object_id)) {
            $existing_by_obj[(int) $it->object_id] = (int) $it->ID;
        }
    }

    $upsert_menu_item = function (array $args) use ($menu_id, $existing_by_url, $existing_by_obj) {
        $type = $args['type'] ?? '';
        $title = $args['title'] ?? '';
        $url = $args['url'] ?? '';
        $object_id = isset($args['object_id']) ? (int) $args['object_id'] : 0;
        $existing_id = 0;

        if ($type === 'custom' && $url && isset($existing_by_url[$url])) {
            $existing_id = (int) $existing_by_url[$url];
        } elseif ($object_id > 0 && isset($existing_by_obj[$object_id])) {
            $existing_id = (int) $existing_by_obj[$object_id];
        }

        if ($existing_id > 0) {
            return;
        }

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'     => $title,
            'menu-item-url'       => $url,
            'menu-item-status'    => 'publish',
            'menu-item-type'      => $type,
            'menu-item-object'    => $type === 'custom' ? 'custom' : 'page',
            'menu-item-object-id' => $object_id,
        ]);
    };

    if ($page_about > 0) {
        $upsert_menu_item([
            'type' => 'post_type',
            'title' => 'About Us',
            'url' => get_permalink($page_about),
            'object_id' => $page_about,
        ]);
    }
    if ($page_privacy > 0) {
        $upsert_menu_item([
            'type' => 'post_type',
            'title' => 'Privacy',
            'url' => get_permalink($page_privacy),
            'object_id' => $page_privacy,
        ]);
    }
    if ($page_contact > 0) {
        $upsert_menu_item([
            'type' => 'post_type',
            'title' => 'Contact Us',
            'url' => get_permalink($page_contact),
            'object_id' => $page_contact,
        ]);
    }

    if (!get_option('ophim_whoreshub_seed_pages_rewrite_flushed')) {
        flush_rewrite_rules(false);
        update_option('ophim_whoreshub_seed_pages_rewrite_flushed', 1);
    }

    update_option('ophim_whoreshub_seed_pages_v1', 1);
}

add_action('init', 'ophim_whoreshub_seed_pages_and_menu', 20);
add_action('admin_init', 'ophim_whoreshub_seed_pages_and_menu', 20);

/**
 * AJAX: nhận report video từ frontend.
 */
function ophim_whoreshub_handle_report_ajax() {
    $video_id    = isset($_POST['videoId']) ? sanitize_text_field($_POST['videoId']) : '';
    $report_value = isset($_POST['reportValue']) ? sanitize_text_field($_POST['reportValue']) : '';
    $section     = isset($_POST['section']) ? sanitize_text_field($_POST['section']) : '';
    $note        = isset($_POST['note']) ? wp_kses_post($_POST['note']) : '';

    if (empty($video_id) || empty($report_value)) {
        wp_send_json_error(['message' => 'Thiếu dữ liệu report.'], 400);
    }

    $comment_content  = "Report cho video ID: {$video_id}\n";
    $comment_content .= "Loại: {$report_value}\n";
    if ($section) {
        $comment_content .= "Section: {$section}\n";
    }
    if ($note) {
        $comment_content .= "Ghi chú:\n{$note}\n";
    }

    wp_insert_comment([
        'comment_post_ID'      => intval($video_id),
        'comment_content'      => $comment_content,
        'comment_type'         => 'ophim_report',
        'comment_approved'     => 0,
        'comment_author'       => 'Report form',
        'comment_author_email' => '',
    ]);

    wp_send_json_success(['message' => 'Đã gửi report, cảm ơn bạn.']);
}
add_action('wp_ajax_ophim_report', 'ophim_whoreshub_handle_report_ajax');
add_action('wp_ajax_nopriv_ophim_report', 'ophim_whoreshub_handle_report_ajax');

function op_get_actor_image($actor_id) {
    $default_avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
    $actor = get_term($actor_id);
    if ($actor) {
        $avatar = get_term_meta($actor_id, 'actor_image', true);
        if (empty($avatar)) {
            $avatar = $default_avatar;
        }
        return $avatar;
    }
    return $default_avatar;
}