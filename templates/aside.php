<?php
// Prepare dynamic data
$aside_genres = get_terms(['taxonomy' => 'ophim_genres', 'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 12]);
$aside_tags   = get_terms(['taxonomy' => 'ophim_tags',   'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 12]);

$current_url = home_url(add_query_arg([], $wp->request));
?>
<aside class="sidebar">
    <div class="box mobile">
        <div class="mob-nav">
            <ul class="mob-nav awn-ignore">
                <li><a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link<?php echo is_front_page() ? ' active' : ''; ?>">Home</a></li>
                
                <?php
                $pm_locations  = get_nav_menu_locations();
                $pm_menu_id    = isset($pm_locations['primary-menu']) ? (int) $pm_locations['primary-menu'] : 0;
                $primary_items = $pm_menu_id > 0 ? wp_get_nav_menu_items($pm_menu_id) : [];

                if (!empty($primary_items) && !is_wp_error($primary_items)) :
                    foreach ($primary_items as $item) :
                        if ($item->menu_item_parent != 0) continue; // Only top level for mobile simple list
                        $is_active = in_array('current-menu-item', (array)$item->classes);
                ?>
                    <li><a href="<?php echo esc_url($item->url); ?>" class="nav-link<?php echo $is_active ? ' active' : ''; ?>"><?php echo esc_html($item->title); ?></a></li>
                <?php 
                    endforeach;
                endif; 
                ?>
                
                <li><a href="<?php echo esc_url(home_url('/' . get_option('ophim_slug_genres', 'genres') . '/')); ?>" class="nav-link">Categories</a></li>
                <li><a href="<?php echo esc_url(home_url('/' . get_option('ophim_slug_tags', 'tags') . '/')); ?>" class="nav-link">Tags</a></li>
                <li><a href="<?php echo esc_url(home_url('/' . (get_option('ophim_slug_actors') ?: 'actors') . '/')); ?>" class="nav-link">Models</a></li>
            </ul>
        </div>
    </div>

    <div class="box">
        <h3 class="title">Sort by</h3>
        <div class="sort-wrap">
            <button type="button" class="btn-dropdown">
                <span class="text">Videos</span>
                <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
            </button>
            <ul class="dropdown">
                <li class="item"><a href="<?php echo esc_url(home_url('/latest-updates/')); ?>">Latest</a></li>
                <li class="item"><a href="<?php echo esc_url(home_url('/most-popular/')); ?>">Most Viewed</a></li>
                <li class="item"><a href="<?php echo esc_url(home_url('/top-rated/')); ?>">Top Rated</a></li>
            </ul>
        </div>
    </div>

    <div class="box">
        <h3 class="title">Video Quality</h3>
        <ul class="tags-list list-hd">
            <li class="item"><button type="button" class="btn all active">All</button></li>
            <li class="item"><button type="button" class="btn btn-hd">HD</button></li>
        </ul>
    </div>

    <?php if (!is_wp_error($aside_tags) && !empty($aside_tags)): ?>
    <div class="box">
        <h3 class="title">Popular Tags</h3>
        <ul class="tags-list two-per-row">
            <?php foreach ($aside_tags as $tag): ?>
            <li class="item">
                <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="btn">
                    <span class="name"><?php echo esc_html($tag->name); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (!is_wp_error($aside_genres) && !empty($aside_genres)): ?>
    <div class="box 2">
        <h3 class="title">Popular Categories</h3>
        <ul class="tags-list two-per-row">
            <?php foreach ($aside_genres as $genre): ?>
            <li class="item">
                <a href="<?php echo esc_url(get_term_link($genre)); ?>" class="btn">
                    <span class="name"><?php echo esc_html($genre->name); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="box">
        <div id="search_results_aside_trend_searches" class="search-results">
            <h2 class="title">Trending Tags</h2>
            <ul class="tags-list second two-per-row">
                <?php 
                // Using some tags as "Trending" for visual filler if no real search data exists
                $trending_tags = array_slice((array)$aside_tags, 0, 15);
                foreach ($trending_tags as $t): 
                ?>
                <li class="item">
                    <a class="item btn" title="<?php echo esc_attr($t->name); ?>" href="<?php echo esc_url(get_term_link($t)); ?>">
                        <span class="name"><?php echo esc_html($t->name); ?></span>
                        <span class="value"><?php echo number_format($t->count); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</aside>