<?php
$taxonomyName = 'ophim_genres';
$taxonomy = get_the_terms(get_the_id(), $taxonomyName);

$actors = op_get_actor();

$getslug = get_option('ophim_slug_actors');
if($getslug){
    $slug = $getslug;
}else{
    $slug = 'actors';
}
$preview_enabled = get_theme_mod('ophim_hover_preview', true);
$preview_images = function_exists('op_get_preview_images') ? op_get_preview_images() : array();
$use_preview = $preview_enabled && count($preview_images) >= 2;
$preview_class = $use_preview ? ' js-hover-preview' : '';
?>

<div class="thumb">
    <div class="box">
        <a class="item <?= $preview_class ?>" href="<?= the_permalink(); ?>" title="<?= the_title_attribute(); ?>" <?= $use_preview ? 'data-preview-images="' . esc_attr(json_encode($preview_images)) . '"' : '' ?>>
            <span class="thumb-img">
                <img class="img js-preview-img lazyload" 
                     src="<?= op_get_poster_url() ?>" 
                     data-src="<?= op_get_poster_url() ?>" 
                     alt="<?= the_title_attribute(); ?>" 
                     data-p="<?= op_get_poster_url() ?>"
                     data-preview="<?= op_get_poster_url() ?>" 
                     width="320" height="180">
                <span class="duration"><?= op_get_runtime() ?></span>
                <span class="is-hd"><?= op_get_quality() ?></span>
                <span class="ico-fav-0 " title="Favourites" data-fav-video-id="<?= get_the_id() ?>" data-fav-type="0"></span>
                <span class="ico-fav-1 " title="Watch Later" data-fav-video-id="<?= get_the_id() ?>" data-fav-type="1"></span>
            </span>
            <span class="col">
                <span class="description"><?= the_title() ?></span>
            </span>
        </a>
        <ul class="info">
            <li class="item">
                <svg class="svg-icon view"><use xlink:href="#icon-view"></use></svg>
                <span class="text"><?= op_get_post_view() ?></span>
                <span class="rating positive">
                    <span class="voters">
                        <svg class="svg-icon"><use xlink:href="#icon-like"></use></svg>
                        <span class="text">0%</span>
                    </span>
                </span>
            </li>
            <li class="item">
                <svg class="svg-icon"><use xlink:href="#icon-calendar"></use></svg>
                <span class="text"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('ago'); ?></span>
            </li>
        </ul>
    </div>
</div>