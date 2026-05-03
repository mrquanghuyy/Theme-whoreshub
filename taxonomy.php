<?php
get_header();

// Extract the current sort parameter from the Ophim filter array if available
$current_sort = isset($_GET['filter']['sort']) ? sanitize_text_field($_GET['filter']['sort']) : 'update';

// Apply sorting to the current taxonomy query
global $wp_query;
$args = $wp_query->query_vars;

if ($current_sort == 'view') {
    $args['meta_query'] = array(
        'relation' => 'OR',
        'has_view' => array(
            'key'     => 'ophim_view',
            'type'    => 'NUMERIC',
            'compare' => 'EXISTS'
        ),
        'no_view'  => array(
            'key'     => 'ophim_view',
            'compare' => 'NOT EXISTS'
        )
    );
    $args['orderby'] = array(
        'has_view' => 'DESC',
        'modified' => 'DESC'
    );
} elseif ($current_sort == 'year') {
    $args['orderby']  = 'date';
    $args['order']    = 'DESC';
} else {
    $args['orderby']  = 'modified';
    $args['order']    = 'DESC';
}

// Override the main query with our applied sorting
query_posts($args);
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="block-thumbs">
                <div class="headline">
                    <h1 class="title"><span><?= single_term_title('', false); ?></span></h1>
                    
                    <div class="sort-wrap">
                        <button type="button" class="btn-dropdown">
                            <span class="text">
                                <?php 
                                    if ($current_sort == 'view') {
                                        echo 'Most Viewed';
                                    } elseif ($current_sort == 'year') {
                                        echo 'Latest';
                                    } else {
                                        echo 'Recently Updated';
                                    }
                                ?>
                            </span>
                            <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                        </button>
                        <ul id="list_videos_common_videos_list_sort_list" class="dropdown">
                            <li class="item <?= $current_sort == 'update' ? 'active' : '' ?>">
                                <a href="<?= esc_url(add_query_arg(['filter' => ['sort' => 'update']])) ?>">Recently Updated</a>
                            </li>
                            <li class="item <?= $current_sort == 'year' ? 'active' : '' ?>">
                                <a href="<?= esc_url(add_query_arg(['filter' => ['sort' => 'year']])) ?>">Latest</a>
                            </li>
                            <li class="item <?= $current_sort == 'view' ? 'active' : '' ?>">
                                <a href="<?= esc_url(add_query_arg(['filter' => ['sort' => 'view']])) ?>">Most Viewed</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="thumbs">
                    <?php 
                    $key = 0; 
                    if (have_posts()) : 
                        while (have_posts()) : the_post(); 
                            $key++;
                            get_template_part('templates/section/section_thumb_item');
                        endwhile; 
                    else: 
                        echo '<p style="text-align:center;width:100%;color:#888;">No videos found in this sequence.</p>';
                    endif; 
                    ?>
                </div>

                <?php 
                if (function_exists('ophim_pagination1')) {
                    ophim_pagination1();
                } elseif (function_exists('ophim_pagination')) {
                    ophim_pagination();
                } else {
                    the_posts_pagination();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>
