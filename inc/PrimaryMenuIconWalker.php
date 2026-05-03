<?php

/**
 * Walker render menu sidebar theo cấu trúc:
 * - Item cấp 0 không có child: <a class="menu-item">...</a>
 * - Item cấp 0 có child: render khối "collapse-menu" (Straight/Gay/Shemale)
 * - Child cấp 1: <a class="collapse-menu-item">...</a>
 *
 * Cách dùng:
 * - Vào WP Admin -> Appearance -> Menus
 * - Chọn menu location "primary-menu"
 * - Dán SVG icon (hoặc HTML icon) vào trường "Description" của từng item
 *   (icon sẽ xuất hiện ở đầu item tương ứng)
 */
class Pornslash_PrimaryMenuIconWalker extends Walker_Nav_Menu {
    private function icon_from_item(WP_Post $item) {
        if (empty($item->description)) {
            return '';
        }

        // Cho phép một số tag SVG cơ bản để không bị wp_kses cắt hết.
        $allowed = array(
            'svg' => array(
                'xmlns' => true,
                'xmlns:xlink' => true,
                'width' => true,
                'height' => true,
                'viewBox' => true,
                'fill' => true,
                'version' => true,
                'style' => true,
                'class' => true,
            ),
            'g' => array('fill' => true, 'class' => true),
            'path' => array('d' => true, 'fill' => true, 'class' => true),
            'polygon' => array('points' => true, 'fill' => true, 'class' => true),
            'rect' => array('x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'class' => true),
            'circle' => array('cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'class' => true),
            'line' => array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true, 'class' => true),
        );

        return wp_kses($item->description, $allowed);
    }

    public function start_lvl(&$output, $depth = 0, $args = null) {
        // Nếu đang vào "level con" của item cấp 0, tạo wrapper danh sách collapse.
        if ($depth === 0) {
            $output .= '<div class="collapse-menu-list left-menu-option" style="height: 0px;">';
        }
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        // Đóng danh sách + đóng collapse-menu.
        if ($depth === 0) {
            $output .= '</div></div>';
        }
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $url = !empty($item->url) ? esc_url($item->url) : '#';
        $title = !empty($item->title) ? esc_html($item->title) : '';
        $icon_html = $this->icon_from_item($item);

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);

        if ($depth === 0 && $has_children) {
            // Parent có child => hiển thị header collapse-menu.
            $output .= '<div class="collapse-menu orientation">';
            $output .= '<div class="collapse-menu-header">';
            $output .= '<div class="collapse-menu-label">';
            // Parent chưa dán icon (Description rỗng) => bỏ khối icon header.
            if ($icon_html !== '') {
                $output .= '<div class="collapse-menu-header-icon">' . $icon_html . '</div>';
            }
            $output .= '<div class="menu-label-text">' . $title . '</div>';
            $output .= '</div>';

            // Chevron icon (giống bản hardcode trong sidebar).
            $output .= '<div class="collapse-menu-icon"><svg width="100%" height="100%" style="fill:white" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="396.6,160 416,180.7 256,352 96,180.7 115.3,160 256,310.5 "></polygon></svg></div>';

            $output .= '</div>'; // đóng collapse-menu-header
            return;
        }

        if ($depth === 0) {
            // Item cấp 0 không có child.
            $output .= '<a href="' . $url . '" class="menu-item">';
            // Item chưa dán icon => bỏ khối icon để khỏi lệch layout.
            if ($icon_html !== '') {
                $output .= '<div class="menu-icon">' . $icon_html . '</div>';
            }
            $output .= '<div class="menu-title">' . $title . '</div>';
            $output .= '</a>';
            return;
        }

        if ($depth === 1) {
            // Child cấp 1 nằm trong collapse-menu-list.
            $output .= '<a href="' . $url . '" class="collapse-menu-item">';
            // Child chưa dán icon => bỏ khối icon.
            if ($icon_html !== '') {
                $output .= '<div class="collapse-menu-item-icon">' . $icon_html . '</div>';
            }
            $output .= '<div class="collapse-menu-item-text">' . $title . '</div>';
            $output .= '</a>';
            return;
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        // Không cần vì thẻ được đóng trong start_el.
    }
}

