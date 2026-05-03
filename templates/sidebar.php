<div class="side-bar">
    <div class="side-menu">
        <!-- Home -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="menu-item<?php echo is_home() ? ' active' : ''; ?>">
            <div class="menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </div>
            <div class="menu-title">Home</div>
        </a>

        <!-- Trending -->
        <a href="<?php echo esc_url(home_url('/')); ?>?orderby=views" class="menu-item">
            <div class="menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.2 3.56a1 1 0 0 0-1.82.88L17 8H7a1 1 0 0 0-.93 1.36l3 8A1 1 0 0 0 10 18h4a1 1 0 0 0 .95-.68l1.39-4.18 1.82 3.46a1 1 0 0 0 1.78-.94zM13.38 16h-3l-2.25-6H17.3z"/>
                </svg>
            </div>
            <div class="menu-title">Trending</div>
        </a>

        <!-- New Videos -->
        <a href="<?php echo esc_url(home_url('/')); ?>?orderby=date" class="menu-item">
            <div class="menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                </svg>
            </div>
            <div class="menu-title">New Videos</div>
        </a>

        <!-- Pornstars/Actors -->
        <?php
        $actors_slug = get_option('ophim_slug_actors') ?: 'actors';
        ?>
        <a href="<?php echo esc_url(home_url('/' . $actors_slug . '/')); ?>" class="menu-item<?php echo get_query_var('actors_list') ? ' active' : ''; ?>">
            <div class="menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
            </div>
            <div class="menu-title">Pornstars</div>
        </a>

        <!-- Categories -->
        <a href="<?php echo esc_url(home_url('/categories/')); ?>" class="menu-item">
            <div class="menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/>
                </svg>
            </div>
            <div class="menu-title">Categories</div>
        </a>
    </div>

    <?php
    // Genres collapse menu
    $genres = get_terms(array(
        'taxonomy'   => 'ophim_genres',
        'hide_empty' => false,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 20,
    ));
    if (!is_wp_error($genres) && !empty($genres)):
    ?>
    <div class="collapse-menu">
        <div class="collapse-menu-header" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block'">
            <div class="collapse-menu-label">
                <div class="collapse-menu-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span class="menu-label-text">Genres</span>
            </div>
            <div class="collapse-menu-icon">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
            </div>
        </div>
        <div class="collapse-menu-list" style="display:none;">
            <?php foreach ($genres as $g):
                $link = get_term_link($g);
                if (is_wp_error($link)) continue;
            ?>
            <a href="<?php echo esc_url($link); ?>" class="collapse-menu-item">
                <span class="collapse-menu-item-text"><?php echo esc_html($g->name); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="sidebar-links">
        <?php
        // Primary nav menu
        $menu_locations = get_nav_menu_locations();
        if (isset($menu_locations['primary-menu'])) {
            $menu_id = $menu_locations['primary-menu'];
            $menu_items = wp_get_nav_menu_items($menu_id);
            if ($menu_items) {
                foreach ($menu_items as $item) {
                    echo '<a href="' . esc_url($item->url) . '" class="menu-item"><div class="menu-title">' . esc_html($item->title) . '</div></a>';
                }
            }
        }
        ?>
    </div>

    <div class="footer-links">
        <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="footer-link">About</a>
        <a href="<?php echo esc_url(home_url('/privacy/')); ?>" class="footer-link">Privacy</a>
        <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="footer-link">Contact</a>
        <span class="footer-link">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span>
    </div>
</div>