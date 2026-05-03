<?php
/**
 * Template Name: Danh sách Thẻ (Tags)
 */
get_header();

$tags_slug = get_option('ophim_slug_tags') ?: 'tags';
$per_page = 100;
$paged = max(1, (int) get_query_var('paged'));

$total_tags = wp_count_terms(array(
    'taxonomy'   => 'ophim_tags',
    'hide_empty' => true,
));
if (is_wp_error($total_tags)) $total_tags = 0;
$total_pages = max(1, (int) ceil($total_tags / $per_page));
$paged = min($paged, $total_pages);
$offset = ($paged - 1) * $per_page;

$tags = get_terms(array(
    'taxonomy'   => 'ophim_tags',
    'hide_empty' => true,
    'number'     => $per_page,
    'offset'     => $offset,
    'orderby'    => 'count',
    'order'      => 'DESC',
));
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="headline">
                <h1 class="title"><span>All</span> Tags</h1>
            </div>

            <div class="section">
                <ul class="tags-list second" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php
                    if (!is_wp_error($tags) && !empty($tags)) :
                        foreach ($tags as $tag) :
                            $link = get_term_link($tag);
                            if (is_wp_error($link)) continue;
                            ?>
                            <li class="item">
                                <a href="<?php echo esc_url($link); ?>" class="btn">
                                    <span class="name"><?php echo esc_html($tag->name); ?></span>
                                    <span class="value"><?php echo (int)$tag->count; ?></span>
                                </a>
                            </li>
                            <?php
                        endforeach;
                    else :
                        echo '<li>No tags found.</li>';
                    endif;
                    ?>
                </ul>
            </div>
            
            <?php
            $paging_query = (object) array('max_num_pages' => $total_pages);
            ophim_pagination($paging_query);
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
