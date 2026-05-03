<?php
get_header();
?>
<div class="content">
    <?php if ( is_active_sidebar('widget-area') ) {
        dynamic_sidebar( 'widget-area' );
    } else {
        _e(' Go to Appearance -> Widgets to add some widgets.', 'ophim');
    }
    ?>

    <?php
    $categories = get_terms([
        'taxonomy' => 'ophim_genres',
        'hide_empty' => false,
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 10
    ]);

    if (!is_wp_error($categories) && !empty($categories)) :
        $default_avatar = get_template_directory_uri() . '/assets/images/category-default.webp';
    ?>
    <div id="list_categories_categories_list" class="section">
        <div class="container">
            <div class="block-thumbs">
                <div class="headline">
                    <h2 class="title"><span>Most</span> Viewed Categories</h2>
                    <div class="sort-wrap">
                        <button type="button" class="btn-dropdown">
                            <span class="text">Most Viewed</span>
                            <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                        </button>
                        <!-- <ul id="list_categories_categories_list_sort_list" class="dropdown">
                            <li class="item"><span>Alphabetically</span></li>
                            <li class="item"><span>Top Rated</span></li>
                            <li class="item"><span>Most Videos</span></li>
                        </ul> -->
                    </div>
                </div>
                <div class="thumbs" id="list_categories_categories_list_items">
                    <?php foreach ($categories as $cat) : 
                        $link = get_term_link($cat);
                        $image = get_term_meta($cat->term_id, 'genre_thumbnail', true);
                        if (empty($image)) $image = $default_avatar;
                    ?>
                    <div class="thumb">
                        <div class="box">
                            <a class="item" href="<?= esc_url($link) ?>" title="<?= esc_attr($cat->name) ?>">
                                <span class="thumb-img">
                                    <img class="img lazyload" src="<?= esc_url($image) ?>" data-src="<?= esc_url($image) ?>" alt="<?= esc_attr($cat->name) ?>">
                                </span>
                                <span class="col second">
                                    <span class="description"><b><?= esc_html($cat->name) ?></b></span>
                                    <span class="val">
                                        <svg class="svg-icon svg-video"><use xlink:href="#icon-video"></use></svg>
                                        <span class="text"><?= (int)$cat->count ?></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="container">
            <div class="section">
                <div class="container">
                    <ul class="tags-list">
                        <?php 
                        $nav_tags    = get_terms(['taxonomy' => 'ophim_tags',    'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 40]);
                        
                        foreach ($nav_tags as $tag) : 
                            $slugTag = get_option('ophim_slug_tags') ?: 'tags';
                            $link = home_url('/' . $slugTag . '/' . $tag->slug);
                        ?>
                        <li class="item"><a href="<?= esc_url($link) ?>" class="btn"><span class="name"><?= esc_html($tag->name) ?></span><span class="value"><?= (int)$tag->count ?></span></a></li>
                        <?php endforeach; ?>
                        <li class="item">
                            <a href="<?php echo esc_url(home_url('/' . (get_option('ophim_slug_tags') ?: 'tags') . '/')); ?>" class="all btn">
                                Show All Tags
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>