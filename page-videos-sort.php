<?php
/**
 * Template Name: Videos Sort Grid
 */
get_header();

$current_sort = isset($_GET['filter']['sort']) ? sanitize_text_field($_GET['filter']['sort']) : 'update';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Trích xuất thủ công $paged nếu đang dùng Rewrite tĩnh 
$req_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if (preg_match('/page\/([0-9]+)\/?$/', $req_uri, $matches)) {
    $paged = (int)$matches[1];
}

$args = array(
    'post_type'      => 'ophim',
    'posts_per_page' => 24,
    'paged'          => $paged
);

if ($current_sort == 'view') {
    $page_title = 'Most Popular Videos';
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
    $page_title = 'Top Rated Videos';
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
} else {
    $page_title = 'Latest Updates';
    $args['orderby'] = 'modified';
    $args['order'] = 'DESC';
}

$wp_query = new WP_Query($args);
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="block-thumbs">
                <div class="headline">
                    <h1 class="title"><span></span> <?php echo esc_html($page_title); ?></h1>
                </div>
                
                <div class="thumbs">
                    <?php
                    if ($wp_query->have_posts()) {
                        while ($wp_query->have_posts()) {
                            $wp_query->the_post();
                            include THEME_URL . '/templates/section/section_thumb_item.php';
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p style="text-align:center;width:100%;color:#888;">No videos found.</p>';
                    }
                    ?>
                </div>

                <?php 
                ophim_pagination($wp_query); 
                ?>
            </div>
        </div>
    </div>
</div>

<?php 
get_footer(); 
?>
