<?php
/**
 * Template Name: Danh sách diễn viên
 * Trang danh sách diễn viên (domain/actors) - 50/trang, 10 cột desktop, 3 cột mobile.
 */
get_header();

if (!function_exists('ophim_get_actor_total_views')) {
    function ophim_get_actor_total_views($term_taxonomy_id) {
        global $wpdb;
        $query = $wpdb->prepare("
            SELECT SUM(CAST(pm.meta_value AS UNSIGNED))
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->term_relationships} tr ON pm.post_id = tr.object_id
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
            WHERE pm.meta_key = 'ophim_view'
            AND tr.term_taxonomy_id = %d
            AND p.post_type = 'ophim'
            AND p.post_status = 'publish'
        ", $term_taxonomy_id);
        $total = $wpdb->get_var($query);
        return $total ? number_format_i18n($total) : 0;
    }
}

$actors_slug = get_option('ophim_slug_actors') ?: 'actors';
$per_page = 50;
$paged = max(1, (int) get_query_var('paged'));

$total_actors = wp_count_terms(array(
    'taxonomy'   => 'ophim_actors',
    'hide_empty' => true,
));
if (is_wp_error($total_actors)) $total_actors = 0;
$total_pages = max(1, (int) ceil($total_actors / $per_page));
$paged = min($paged, $total_pages);
$offset = ($paged - 1) * $per_page;

$actors = get_terms(array(
    'taxonomy'   => 'ophim_actors',
    'hide_empty' => true,
    'number'     => $per_page,
    'offset'     => $offset,
    'orderby'    => 'name',
    'order'      => 'ASC',
));
if (is_wp_error($actors)) $actors = array();
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="headline">
                <h1 class="title">
                    <span>Top</span> Rated Models
                </h1>
            </div>

            <div class="thumbs vertical">
                <?php
                $default_avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
                if (!empty($actors)) :
                    foreach ($actors as $actor) :
                        $actor_link = get_term_link($actor);
                        if (is_wp_error($actor_link)) {
                            continue;
                        }
                        $avatar = get_term_meta($actor->term_id, 'actor_avatar', true);
                        if (empty($avatar)) {
                            $avatar = $default_avatar;
                        }
                        ?>
                        <div class="thumb">
                            <div class="box">
                                <a class="item" href="<?php echo esc_url($actor_link); ?>" title="<?php echo esc_attr($actor->name); ?>">
                                    <span class="thumb-img">
                                        <img class="img lazyloaded" src="<?php echo esc_url($avatar); ?>" data-src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($actor->name); ?>">
                                    </span>
                                    <span class="col">
                                        <span class="description">
                                            <b><?php echo esc_html($actor->name); ?></b>
                                        </span>
                                    </span>
                                    <ul class="info">
                                        <li class="item">
                                            <svg class="svg-icon">
                                                <use xlink:href="#icon-video"></use>
                                            </svg>
                                            <span class="text"><?= (int)$actor->count ?></span>
                                        </li>
                                        <li class="item">
                                            <svg class="svg-icon view">
                                                <use xlink:href="#icon-view"></use>
                                            </svg>
                                            <span class="text"><?= ophim_get_actor_total_views($actor->term_taxonomy_id) ?></span>
                                        </li>
                                    </ul>
                                </a>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else :
                    echo '<p>Không có diễn viên nào.</p>';
                endif;
                ?>
            </div>

            <?php
        $paging_query = (object) array('max_num_pages' => $total_pages);
        ophim_pagination1($paging_query);
        ?>
        </div>
    </div>
</div>
<?php
get_footer();
