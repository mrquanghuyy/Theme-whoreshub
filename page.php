<?php
get_header();
?>
<?php if (wp_is_mobile()): ?>
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/assets/css/app-mobile.css" />
<?php else: ?>
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/assets/css/app.css" />
<?php endif; ?>
<div class="container<?= wp_is_mobile() ? '' : ' show-sidebar' ?>">
    <div class="content">
        <div class="policy-wrapper">
            <div class="policy-content">
                <?php
                $page_id = get_queried_object_id();
                if ($page_id) :
                    $page_post = get_post($page_id);
                    if ($page_post instanceof WP_Post) :
                        ?>
                        <h1><?php echo esc_html(get_the_title($page_post)); ?></h1>
                        <?php echo apply_filters('the_content', $page_post->post_content); ?>
                        <?php
                    endif;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
