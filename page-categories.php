<?php
/**
 * Template Name: Danh sách Thể loại (Genres)
 */
get_header();

$per_page = 25;
$paged = max(1, (int) get_query_var('paged'));

$total_cats = wp_count_terms(array(
    'taxonomy'   => 'ophim_genres',
    'hide_empty' => true,
));
if (is_wp_error($total_cats)) $total_cats = 0;
$total_pages = max(1, (int) ceil($total_cats / $per_page));
$paged = min($paged, max(1, $total_pages));
$offset = ($paged - 1) * $per_page;

$categories = get_terms(array(
    'taxonomy'   => 'ophim_genres',
    'hide_empty' => true,
    'number'     => $per_page,
    'offset'     => $offset,
    'orderby'    => 'count',
    'order'      => 'DESC',
));
?>
<div class="content">
    <div id="list_categories_categories_list" class="section">
        <div class="container">
            <div class="block-thumbs">
                <div class="headline">
                    <h1 class="title"><span>All</span> Categories</h1>
                </div>

                <div class="thumbs">
                    <?php
                    if (!is_wp_error($categories) && !empty($categories)) :
                        $default_avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
                        foreach ($categories as $cat) :
                            $link = get_term_link($cat);
                            if (is_wp_error($link)) continue;

                            $thumb = get_term_meta($cat->term_id, 'genre_thumbnail', true);
                            if (empty($thumb)) {
                                $thumb = get_term_meta($cat->term_id, 'ophim_genre_thumbnail', true);
                            }
                            if (empty($thumb)) {
                                $thumb = $default_avatar;
                            }
                            ?>
                            <div class="thumb">
                                <div class="box">
                                    <a href="<?php echo esc_url($link); ?>" class="item cat-item">
                                        <span class="thumb-img">
                                            <img class="img lazyloaded" src="<?php echo esc_url($thumb); ?>" data-src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($cat->name); ?>">
                                        </span>
                                        <span class="col second">
                                            <span class="description"><?php echo esc_html($cat->name); ?></span>
                                            <span class="val">
                                                <svg class="svg-icon"><use xlink:href="#icon-video"></use></svg>
                                                <span class="text"><?php echo (int)$cat->count; ?></span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    else :
                        echo '<p style="text-align:center;width:100%;color:#888;">No categories found.</p>';
                    endif;
                    ?>
                </div>

                <?php 
                $paging_query = (object) array('max_num_pages' => $total_pages);
                ophim_pagination($paging_query);
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
