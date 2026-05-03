<?php

add_action('wp_ajax_ratemovie', 'ratemovie_init');
add_action('wp_ajax_nopriv_ratemovie', 'ratemovie_init');
function ratemovie_init()
{
    $rate = $_POST['rating'];
    $key = 'ophim_votes';
    $post_id = $_POST['postid'];
    $count = (int)get_post_meta($post_id, $key, true);
    $count++;
    update_post_meta($post_id, $key, $count);

    $rate = $_POST['rating'];
    $key = 'ophim_rating';
    $post_id = $_POST['postid'];
    // ophim_rating lưu tỷ lệ (float). Đừng cast int vì sẽ mất phần thập phân.
    $rating = (float) get_post_meta($post_id, $key, true);
    if ($rating < 0) {
        $rating = 0.0;
    }

    $updaterate = $rating + ((int)$rate - $rating) / ($count);
    update_post_meta($post_id, $key, $updaterate);


    // Tách like/dislike theo giả định:
    // - ophim_rating là tỷ lệ like (0..1)
    // - ophim_votes là tổng lượt vote
    $votes = (int) $count;
    $ratio = (float) $updaterate;
    $likeCount = (int) round($votes * $ratio);
    $likeCount = max(0, min($votes, $likeCount));
    $dislikeCount = max(0, $votes - $likeCount);

    $format_count = function ($c) {
        $c = (int) $c;
        if ($c >= 1000000000) {
            $formatted = round($c / 1000000000, 1);
            return ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'B';
        } elseif ($c >= 1000000) {
            $formatted = round($c / 1000000, 1);
            return ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'M';
        } elseif ($c >= 1000) {
            $formatted = round($c / 1000, 1);
            return ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'K';
        }
        return $c;
    };

    $result = array(
        'status' => 'success',
        'rating_star' => number_format($rate, 1),
        'rating_count' => $votes,
        'like_count' => $format_count($likeCount),
        'dislike_count' => $format_count($dislikeCount),
    );
    header('Content-Type: application/json');
    echo json_encode($result);
    die();

}

add_action('wp_ajax_reportbug', 'reportbug_init');
add_action('wp_ajax_nopriv_reportbug', 'reportbug_init');
function reportbug_init()
{
    $result = array('status' => true);
    header('Content-Type: application/json');
    echo json_encode($result);
    die();
}

add_action('wp_ajax_load_actors', 'load_actors_init');
add_action('wp_ajax_nopriv_load_actors', 'load_actors_init');
function load_actors_init() {
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 30;
    
    $actors = get_terms(array(
        'taxonomy'   => 'ophim_actors',
        'hide_empty' => true,
        'number'     => $limit,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ));
    
    $result = array();
    
    if (!is_wp_error($actors) && !empty($actors)) {
        foreach ($actors as $actor) {
            $avatar = get_term_meta($actor->term_id, 'actor_avatar', true);
            
            if (empty($avatar)) {
                $avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
            }

            $getslug = get_option('ophim_slug_actors');
            if($getslug){
                $slug = $getslug;
            }else{
                $slug = 'actors';
            }
            
            $result[] = array(
                'id'     => $actor->term_id,
                'name'   => $actor->name,
                'slug'   => $actor->slug,
                'count'  => $actor->count,
                'avatar' => $avatar,
                'link'   => home_url($slug . "/" . $actor->slug),
            );
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(array(
        'status' => 'success',
        'actors' => $result,
        'total'  => count($result),
    ));
    die();
}

/**
 * Gợi ý tìm kiếm AJAX giống theme-fullvideosporn.
 *
 * Endpoint: admin-ajax.php?action=search_suggestions
 * Trả về JSON: { suggestions: [], channels: [], pornstars: [] }
 */
add_action('wp_ajax_search_suggestions', 'twf_search_suggestions');
add_action('wp_ajax_nopriv_search_suggestions', 'twf_search_suggestions');
function twf_search_suggestions() {
    check_ajax_referer('twf_search_suggestions', 'nonce');

    global $wpdb;

    $term = isset($_POST['term']) ? sanitize_text_field(wp_unslash($_POST['term'])) : '';
    // Cho phép tìm với keyword chỉ 1 ký tự (vd: "s")
    if (mb_strlen($term) < 1) {
        wp_send_json_success(array(
            'suggestions' => array(),
            'channels'    => array(),
            'pornstars'   => array(),
        ));
    }

    $like = '%' . $wpdb->esc_like($term) . '%';

    // Gợi ý theo tiêu đề phim (post_type: ophim)
    $post_ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'ophim' AND post_status = 'publish'
            AND post_title LIKE %s
            ORDER BY post_date DESC
            LIMIT 12",
            $like
        )
    );

    $suggestions = array();
    $seen_titles = array();
    foreach ((array) $post_ids as $pid) {
        $title = get_the_title($pid);
        if ($title === '' || isset($seen_titles[$title])) {
            continue;
        }
        $seen_titles[$title] = true;

        $suggestions[] = array(
            'label' => $title,
            // Link xem phim
            'url'   => get_permalink($pid),
        );
        if (count($suggestions) >= 10) {
            break;
        }
    }

    // Danh mục (channels)
    $channels = array();
    $terms_cat = get_terms(array(
        'taxonomy'   => 'ophim_categories',
        'hide_empty' => true,
        'number'     => 8,
        'search'     => $term,
    ));

    if (!is_wp_error($terms_cat) && !empty($terms_cat)) {
        foreach ($terms_cat as $t) {
            $link = get_term_link($t);
            if (is_wp_error($link)) {
                continue;
            }
            $channels[] = array(
                'label' => $t->name,
                'url'   => $link,
            );
        }
    }

    // Diễn viên (pornstars)
    $pornstars = array();
    $terms_act = get_terms(array(
        'taxonomy'   => 'ophim_actors',
        'hide_empty' => true,
        'number'     => 8,
        'search'     => $term,
    ));

    if (!is_wp_error($terms_act) && !empty($terms_act)) {
        $defaultAvatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
        foreach ($terms_act as $t) {
            $link = get_term_link($t);
            if (is_wp_error($link)) {
                continue;
            }
            $face = get_term_meta($t->term_id, 'actor_avatar', true);
            if (empty($face)) {
                $face = $defaultAvatar;
            }
            $pornstars[] = array(
                'label' => $t->name,
                'url'   => $link,
                'face'  => $face,
            );
        }
    }

    wp_send_json_success(array(
        'suggestions' => $suggestions,
        'channels'    => $channels,
        'pornstars'   => $pornstars,
    ));
}