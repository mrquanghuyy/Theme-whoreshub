<?php
// Prepare nav data from WordPress taxonomies
$nav_genres  = get_terms(['taxonomy' => 'ophim_genres',  'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 12]);
$nav_tags    = get_terms(['taxonomy' => 'ophim_tags',    'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 40]);
$nav_actors  = get_terms(['taxonomy' => 'ophim_actors',  'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8]);

$genres_url  = get_post_type_archive_link('ophim') ?: home_url('/');
$tags_url    = home_url('/');
$actors_url  = home_url('/');

$actor_slug  = get_option('ophim_slug_actors') ?: 'actors';

$current_queried = is_tax() ? get_queried_object() : null;
$current_tax     = $current_queried ? $current_queried->taxonomy : '';
$current_term_id = $current_queried ? (int) $current_queried->term_id : 0;

$default_avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
?>
<header class="header">
    <div class="header-top">
        <div class="col">
            <button aria-label="menu" type="button" class="burger">
                <span class="burger-brick"></span>
                <span class="burger-brick"></span>
                <span class="burger-brick"></span>
            </button>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="logo">
                <?php
                $custom_logo_id = get_theme_mod('custom_logo');
                if ($custom_logo_id) {
                    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                    echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" style="height:60px;width:auto;">';
                } elseif (function_exists('op_the_logo')) {
                    op_the_logo('height:50px');
                } else {
                    $site_name = get_bloginfo('name');
                    $half = max(1, (int)(strlen($site_name) / 2));
                    echo '<span style="color:#fff">' . esc_html(substr($site_name, 0, $half)) . '</span>';
                    echo '<span style="color:#fa0052">' . esc_html(substr($site_name, $half)) . '</span>';
                }
                ?>
            </a>
        </div>

        <div class="col-search">
            <form id="search_form" action="<?= esc_url(home_url('/')) ?>" method="get">
                <div class="form-search">
                    <div class="box-search">
                        <input type="text" class="search" name="q" placeholder="Search..." value="<?= esc_attr(get_search_query()) ?>" autocomplete="off">
                        <button aria-label="search" type="submit" class="btn-search">
                            <svg class="svg-icon"><use xlink:href="#icon-search"></use></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col second">
            <ul class="list">
                <li class="item">
                    <button aria-label="toggle dark" type="button" class="toggle-dark">
                        <svg class="svg-icon"><use xlink:href="#icon-moon"></use></svg>
                    </button>
                </li>
            </ul>
            <ul class="member-links"></ul>
            <button aria-label="search" type="button" class="btnSearch">
                <svg class="svg-icon"><use xlink:href="#icon-search"></use></svg>
            </button>
        </div>
    </div>

    <div class="header-bottom">
        <div class="container">
            <nav class="nav">

                <!-- Home -->
                <?php 
                // Sử dụng nhận diện qua URL gốc (Request URI) vì hàm is_home() của WP bị plugin Ophim ghi đè gây sai lệch.
                $req_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                $is_actual_home = ($req_uri === '') ? true : false;
                
                $actor_slug_chk = get_option('ophim_slug_actors') ?: 'actors';
                $genre_slug_chk = get_option('ophim_slug_genres') ?: 'genres';
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"
                   class="nav-link<?php echo $is_actual_home ? ' active' : ''; ?>">
                    Home
                </a>

                <!-- Videos dropdown (simple list) -->
                <div class="is-relative">
                    <button type="button" class="btn-dropdown nav-link<?php echo (in_array(explode('/', $req_uri)[0], ['latest-updates', 'most-popular', 'top-rated'])) ? ' active' : ''; ?>">
                        <span class="text">Videos</span>
                        <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                    </button>
                    <ul class="dropdown">
                        <li class="item"><a href="<?php echo esc_url(home_url('/latest-updates/')); ?>">Latest</a></li>
                        <li class="item"><a href="<?php echo esc_url(home_url('/most-popular/')); ?>">Most Viewed</a></li>
                        <li class="item"><a href="<?php echo esc_url(home_url('/top-rated/')); ?>">Top Rated</a></li>
                    </ul>
                </div>

                <!-- Categories (genres) - full dropdown panel with images -->
                <?php if (!is_wp_error($nav_genres) && !empty($nav_genres)): ?>
                <div class="wrap">
                    <button type="button" class="btn-dropdown nav-link<?php echo ($current_tax === 'ophim_genres' || strpos($req_uri, $genre_slug_chk) === 0) ? ' active' : ''; ?>">
                        <span class="text">Categories</span>
                        <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                    </button>
                    <div class="dropdown">
                        <div class="dropdown-inner">
                            <div class="container">
                                <div class="inner">
                                    <div class="thumbs">
                                        <?php foreach ($nav_genres as $genre):
                                            $getslug = get_option('ophim_slug_genres');
                                            if($getslug){
                                                $slug = $getslug;
                                            }else{
                                                $slug = 'genres';
                                            }
                                            $link = home_url('/' . $slug . '/' . $genre->slug);
                                            if (is_wp_error($link)) continue;
                                            $is_active_genre = ($current_term_id && (int)$genre->term_id === $current_term_id);
                                            $g_img = get_term_meta($genre->term_id, 'genre_thumbnail', true);
                                            if (empty($g_img)) $g_img = get_term_meta($genre->term_id, 'ophim_genre_thumbnail', true);
                                            if (empty($g_img)) $g_img = $default_avatar;
                                        ?>
                                        <div class="thumb<?php echo $is_active_genre ? ' active' : ''; ?>">
                                            <div class="box">
                                                <a href="<?php echo esc_url($link); ?>" class="item">
                                                    <span class="thumb-img">
                                                        <img class="img" src="<?php echo esc_url($g_img); ?>" alt="<?php echo esc_attr($genre->name); ?>">
                                                    </span>
                                                    <span class="col second">
                                                        <span class="description"><?php echo esc_html($genre->name); ?></span>
                                                        <span class="val">
                                                            <svg class="svg-icon"><use xlink:href="#icon-video"></use></svg>
                                                            <span class="text"><?php echo (int)$genre->count; ?></span>
                                                        </span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <a class="btn" href="<?php echo esc_url(home_url('/' . (get_option('ophim_slug_genres') ?: 'genres') . '/')); ?>">All Categories</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tags dropdown panel -->
                <?php if (!is_wp_error($nav_tags) && !empty($nav_tags)): ?>
                <div class="wrap">
                    <button type="button" class="btn-dropdown nav-link<?php echo ($current_tax === 'ophim_tags') ? ' active' : ''; ?>">
                        <span class="text">Tags</span>
                        <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                    </button>
                    <div class="dropdown">
                        <div class="dropdown-inner">
                            <div class="container">
                                <div class="inner">
                                    <div class="section">
                                        <ul class="tags-list second">
                                            <?php foreach ($nav_tags as $tag):
                                                $link = get_term_link($tag);
                                                if (is_wp_error($link)) continue;
                                            ?>
                                            <li class="item">
                                                <a href="<?php echo esc_url($link); ?>" class="btn">
                                                    <span class="name"><?php echo esc_html($tag->name); ?></span>
                                                    <span class="value"><?php echo (int)$tag->count; ?></span>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <a href="<?php echo esc_url(home_url('/' . (get_option('ophim_slug_tags') ?: 'tags') . '/')); ?>" class="btn">All Tags</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Models (actors) dropdown panel -->
                <?php if (!is_wp_error($nav_actors) && !empty($nav_actors)): ?>
                <div class="wrap">
                    <button type="button" class="btn-dropdown nav-link<?php echo ($current_tax === 'ophim_actors' || strpos($req_uri, $actor_slug_chk) === 0) ? ' active' : ''; ?>">
                        <span class="text">Models</span>
                        <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                    </button>
                    <div class="dropdown">
                        <div class="dropdown-inner">
                            <div class="container">
                                <div class="inner">
                                    <div class="thumbs vertical">
                                        <?php foreach ($nav_actors as $actor):
                                            $link = get_term_link($actor);
                                            if (is_wp_error($link)) continue;
                                            $avatar = get_term_meta($actor->term_id, 'actor_avatar', true);
                                            if (empty($avatar)) $avatar = get_term_meta($actor->term_id, 'actor_image', true);
                                            if (empty($avatar)) $avatar = $default_avatar;
                                        ?>
                                        <div class="thumb">
                                            <div class="box">
                                                <a href="<?php echo esc_url($link); ?>" class="item">
                                                    <span class="thumb-img">
                                                        <img class="img" src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($actor->name); ?>">
                                                    </span>
                                                    <span class="col second">
                                                        <span class="description"><?php echo esc_html($actor->name); ?></span>
                                                        <span class="val">
                                                            <svg class="svg-icon"><use xlink:href="#icon-video"></use></svg>
                                                            <span class="text"><?php echo (int)$actor->count; ?></span>
                                                        </span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <a href="<?php echo esc_url(home_url('/' . $actor_slug . '/')); ?>" class="btn">All Models</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Primary Menu logic -->
                <?php
                $pm_locations  = get_nav_menu_locations();
                $pm_menu_id    = isset($pm_locations['primary-menu']) ? (int) $pm_locations['primary-menu'] : 0;
                $primary_items = $pm_menu_id > 0 ? wp_get_nav_menu_items($pm_menu_id) : [];

                if ( !empty($primary_items) && !is_wp_error($primary_items) ) :
                    $pm_tree      = [];
                    $pm_top_level = [];
                    foreach ( $primary_items as $p_item ) {
                        $p_parent = (int) $p_item->menu_item_parent;
                        if ( $p_parent === 0 ) {
                            $pm_top_level[] = $p_item;
                        } else {
                            $pm_tree[$p_parent][] = $p_item;
                        }
                    }

                    foreach ( $pm_top_level as $p_item ):
                        $p_children = $pm_tree[(int)$p_item->ID] ?? [];
                        $p_active   = in_array('current-menu-item', (array)$p_item->classes) || in_array('current-menu-ancestor', (array)$p_item->classes);
                        $p_target   = !empty($p_item->target) ? 'target="' . esc_attr($p_item->target) . '"' : '';
                ?>
                    <?php if ( !empty($p_children) ): ?>
                        <div class="is-relative">
                            <button type="button" class="btn-dropdown nav-link<?php echo $p_active ? ' active' : ''; ?>">
                                <span class="text"><?php echo esc_html($p_item->title); ?></span>
                                <svg class="svg-icon arrow"><use xlink:href="#icon-arrow-down"></use></svg>
                            </button>
                            <ul class="dropdown">
                                <?php foreach ($p_children as $p_child): 
                                    $pc_target = !empty($p_child->target) ? 'target="' . esc_attr($p_child->target) . '"' : '';
                                ?>
                                <li class="item">
                                    <a href="<?php echo esc_url($p_child->url); ?>" <?php echo $pc_target; ?>>
                                        <?php echo esc_html($p_child->title); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo esc_url($p_item->url); ?>" class="nav-link<?php echo $p_active ? ' active' : ''; ?>" <?php echo $p_target; ?>>
                            <?php echo esc_html($p_item->title); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; endif; ?>

            </nav>
        </div>
    </div>
</header>