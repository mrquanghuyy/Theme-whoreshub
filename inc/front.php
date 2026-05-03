<?php

// Ảnh preview khi hover vào item phim (chạy tuần tự các ảnh từ API)
add_action('wp_footer', 'nqt_beegcom_hover_preview_script', 20);
function nqt_beegcom_hover_preview_script() {
    if (!get_theme_mod('ophim_hover_preview', true)) {
        return;
    }
    ?>
    <script>
    (function() {
        function initHoverPreview() {
            document.querySelectorAll('.js-hover-preview').forEach(function(link) {
                if (link._previewInit) return;
                link._previewInit = true;
                var img = link.querySelector('.js-preview-img');
                if (!img) return;
                var raw = link.getAttribute('data-preview-images');
                if (!raw) return;
                var urls = [];
                try { urls = JSON.parse(raw); } catch (e) { return; }
                if (urls.length < 2) return;
                var posterUrl = img.src;
                var idx = 0;
                var t = null;
                link.addEventListener('mouseenter', function() {
                    idx = 0;
                    if (urls[0]) img.src = urls[0];
                    t = setInterval(function() {
                        idx = (idx + 1) % urls.length;
                        if (urls[idx]) img.src = urls[idx];
                    }, 500);
                });
                link.addEventListener('mouseleave', function() {
                    if (t) clearInterval(t);
                    t = null;
                    img.src = posterUrl;
                });
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHoverPreview);
        } else {
            initHoverPreview();
        }
    })();
    </script>
    <?php
}

function mySearchFilter($query) {
    if ($query->is_search && $query->is_main_query() && !is_admin()) {
        $query->set('post_type', 'ophim');

        $filter = isset($_GET['filter']) ? $_GET['filter'] : array();
        $categories = isset($filter['categories']) ? $filter['categories'] : '';
        $years      = isset($filter['years']) ? $filter['years'] : '';
        $genres     = isset($filter['genres']) ? $filter['genres'] : '';
        $regions    = isset($filter['regions']) ? $filter['regions'] : '';

        $tax_args = array();
        if ($categories) {
            $tax_args[] = array(
                'taxonomy' => 'ophim_categories',
                'field'    => 'slug',
                'terms'    => $categories,
            );
        }
        if ($years) {
            $tax_args[] = array(
                'taxonomy' => 'ophim_years',
                'field'    => 'slug',
                'terms'    => $years,
            );
        }
        if ($genres) {
            $tax_args[] = array(
                'taxonomy' => 'ophim_genres',
                'field'    => 'slug',
                'terms'    => $genres,
            );
        }
        if ($regions) {
            $tax_args[] = array(
                'taxonomy' => 'ophim_regions',
                'field'    => 'slug',
                'terms'    => $regions,
            );
        }
        if (!empty($tax_args)) {
            $query->set('tax_query', $tax_args);
        }

        $query->set('_ophim_tax_search', true);
    }
    return $query;
}
add_filter('pre_get_posts', 'mySearchFilter');

add_filter('posts_join', 'ophim_search_posts_join', 10, 2);
function ophim_search_posts_join($join, $query) {
    global $wpdb;
    if (!is_admin() && $query->is_main_query() && $query->get('_ophim_tax_search')) {
        $join .= " LEFT JOIN {$wpdb->postmeta} AS opm_title ON ({$wpdb->posts}.ID = opm_title.post_id AND opm_title.meta_key = 'ophim_original_title')";
        $join .= " LEFT JOIN {$wpdb->term_relationships} AS otr ON ({$wpdb->posts}.ID = otr.object_id)";
        $join .= " LEFT JOIN {$wpdb->term_taxonomy} AS ott ON (otr.term_taxonomy_id = ott.term_taxonomy_id AND ott.taxonomy IN ('ophim_tags','ophim_categories','ophim_genres','ophim_directors','ophim_actors','ophim_regions','ophim_years'))";
        $join .= " LEFT JOIN {$wpdb->terms} AS ot ON (ott.term_id = ot.term_id)";
    }
    return $join;
}

add_filter('posts_search', 'ophim_search_posts_search', 10, 2);
function ophim_search_posts_search($search, $query) {
    global $wpdb;
    if (!is_admin() && $query->is_main_query() && $query->get('_ophim_tax_search')) {
        $s = $query->get('s');
        if (empty($s)) return $search;

        $keywords = array_filter(array_map('trim', explode(' ', $s)));
        if (empty($keywords)) return $search;

        $conditions = [];
        foreach ($keywords as $kw) {
            // Nới điều kiện LIKE: trước đây bắt buộc có khoảng trắng 2 bên (% suzu %),
            // nên nhiều title có dấu ':' '-' '.' ngay kề từ khoá sẽ không match.
            // Giờ cho phép match bất kỳ vị trí (%suzu%).
            $word = '%' . $wpdb->esc_like(mb_strtolower($kw, 'UTF-8')) . '%';
            $conditions[] = $wpdb->prepare(
                "(LOWER(CONCAT(' ', {$wpdb->posts}.post_title, ' ')) COLLATE utf8mb4_bin LIKE %s OR LOWER(CONCAT(' ', {$wpdb->posts}.post_content, ' ')) COLLATE utf8mb4_bin LIKE %s OR LOWER(CONCAT(' ', COALESCE(opm_title.meta_value,''), ' ')) COLLATE utf8mb4_bin LIKE %s OR LOWER(CONCAT(' ', COALESCE(ot.name,''), ' ')) COLLATE utf8mb4_bin LIKE %s)",
                $word, $word, $word, $word
            );
        }

        $search = ' AND (' . implode(' AND ', $conditions) . ') ';
    }
    return $search;
}

add_filter('posts_distinct', 'ophim_search_posts_distinct', 10, 2);
function ophim_search_posts_distinct($distinct, $query) {
    if (!is_admin() && $query->is_main_query() && $query->get('_ophim_tax_search')) {
        return 'DISTINCT';
    }
    return $distinct;
}

