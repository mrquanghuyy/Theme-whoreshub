<?php

// ─── Admin Settings ───

add_action('admin_menu', 'ophim_ageverify_admin_menu');
function ophim_ageverify_admin_menu() {
    add_theme_page(
        'Cài đặt thông báo 18+',
        'Thông báo 18+',
        'manage_options',
        'ophim-age-verify',
        'ophim_ageverify_settings_page'
    );
}

add_action('admin_init', 'ophim_ageverify_register_settings');
function ophim_ageverify_register_settings() {
    register_setting('ophim_ageverify_group', 'ophim_ageverify_enable');
    register_setting('ophim_ageverify_group', 'ophim_ageverify_title');
    register_setting('ophim_ageverify_group', 'ophim_ageverify_notice', array(
        'sanitize_callback' => 'wp_kses_post',
    ));
    register_setting('ophim_ageverify_group', 'ophim_ageverify_btn_accept');
    register_setting('ophim_ageverify_group', 'ophim_ageverify_btn_decline');
    register_setting('ophim_ageverify_group', 'ophim_ageverify_exit_url');
    register_setting('ophim_ageverify_group', 'ophim_ageverify_logo');
}

function ophim_ageverify_settings_page() {
    ?>
    <div class="wrap">
        <h1>Cài đặt thông báo xác minh tuổi 18+</h1>
        <form method="post" action="options.php">
            <?php settings_fields('ophim_ageverify_group'); ?>
            <table class="form-table">
                <tr>
                    <th>Bật thông báo</th>
                    <td>
                        <label>
                            <input type="checkbox" name="ophim_ageverify_enable" value="1" <?php checked(get_option('ophim_ageverify_enable'), '1'); ?>>
                            Hiển thị popup xác minh tuổi khi truy cập lần đầu
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>Logo URL</th>
                    <td>
                        <input type="url" name="ophim_ageverify_logo" class="regular-text" value="<?php echo esc_attr(get_option('ophim_ageverify_logo', '')); ?>" placeholder="https://example.com/logo.png">
                        <p class="description">URL hình logo hiển thị trên popup (để trống nếu không cần)</p>
                    </td>
                </tr>
                <tr>
                    <th>Tiêu đề</th>
                    <td>
                        <input type="text" name="ophim_ageverify_title" class="regular-text" value="<?php echo esc_attr(get_option('ophim_ageverify_title', 'Website dành cho người lớn')); ?>">
                    </td>
                </tr>
                <tr>
                    <th>Nội dung thông báo</th>
                    <td>
                        <?php
                        $default_notice = 'Website này chứa nội dung dành cho người lớn, bao gồm hình ảnh và video chỉ phù hợp với người trên 18 tuổi. Khi truy cập, bạn xác nhận rằng bạn đã đủ 18 tuổi trở lên và đồng ý xem các nội dung này.';
                        wp_editor(
                            get_option('ophim_ageverify_notice', $default_notice),
                            'ophim_ageverify_notice',
                            array(
                                'textarea_name' => 'ophim_ageverify_notice',
                                'textarea_rows' => 6,
                                'media_buttons' => false,
                                'teeny'         => true,
                            )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Nút "Đồng ý"</th>
                    <td>
                        <input type="text" name="ophim_ageverify_btn_accept" class="regular-text" value="<?php echo esc_attr(get_option('ophim_ageverify_btn_accept', 'Tôi đủ 18 tuổi - Truy cập')); ?>">
                    </td>
                </tr>
                <tr>
                    <th>Nút "Từ chối"</th>
                    <td>
                        <input type="text" name="ophim_ageverify_btn_decline" class="regular-text" value="<?php echo esc_attr(get_option('ophim_ageverify_btn_decline', 'Tôi chưa đủ 18 tuổi - Thoát')); ?>">
                    </td>
                </tr>
                <tr>
                    <th>URL thoát</th>
                    <td>
                        <input type="url" name="ophim_ageverify_exit_url" class="regular-text" value="<?php echo esc_attr(get_option('ophim_ageverify_exit_url', 'https://google.com')); ?>">
                        <p class="description">Chuyển hướng đến URL này khi người dùng chọn "chưa đủ 18 tuổi"</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Lưu cài đặt'); ?>
        </form>
    </div>
    <?php
}

// ─── Frontend Popup ───

add_action('wp_footer', 'ophim_ageverify_popup', 5);
function ophim_ageverify_popup() {
    if (is_admin()) return;
    if (get_option('ophim_ageverify_enable') !== '1') return;

    $logo        = esc_url(get_option('ophim_ageverify_logo', ''));
    $title       = esc_html(get_option('ophim_ageverify_title', 'Website dành cho người lớn'));
    $notice      = wp_kses_post(get_option('ophim_ageverify_notice', 'Website này chứa nội dung dành cho người lớn. Bạn xác nhận rằng bạn đã đủ 18 tuổi trở lên.'));
    $btn_accept  = esc_html(get_option('ophim_ageverify_btn_accept', 'Tôi đủ 18 tuổi - Truy cập'));
    $btn_decline = esc_html(get_option('ophim_ageverify_btn_decline', 'Tôi chưa đủ 18 tuổi - Thoát'));
    $exit_url    = esc_url(get_option('ophim_ageverify_exit_url', 'https://google.com'));
    ?>

    <div id="age-verify-overlay">
        <div id="age-verify-box">
            <?php if ($logo): ?>
                <img src="<?php echo $logo; ?>" alt="Logo" id="age-verify-logo">
            <?php endif; ?>
            <h2 id="age-verify-title"><?php echo $title; ?></h2>
            <div id="age-verify-notice"><?php echo $notice; ?></div>
            <div id="age-verify-buttons">
                <button id="age-verify-accept"><?php echo $btn_accept; ?></button>
                <button id="age-verify-decline"><?php echo $btn_decline; ?></button>
            </div>
        </div>
    </div>

    <style>
        #age-verify-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            align-items: center;
            justify-content: center;
            pointer-events: auto;
        }
        #age-verify-overlay.active {
            display: flex;
        }
        #age-verify-box {
            position: relative;
            z-index: 1;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            max-width: 560px;
            width: 92%;
            padding: 40px 36px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            pointer-events: auto;
        }
        #age-verify-logo {
            max-height: 48px;
            margin-bottom: 20px;
        }
        #age-verify-title {
            color: #fff;
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 16px;
            line-height: 1.3;
        }
        #age-verify-notice {
            color: #bbb;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        #age-verify-notice p {
            margin: 0 0 8px;
        }
        #age-verify-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }
        #age-verify-buttons button {
            width: 100%;
            max-width: 380px;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 600;
            border: 2px solid #d40000;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        #age-verify-accept {
            background: #d40000;
            color: #fff;
        }
        #age-verify-accept:hover {
            background: #e61a1a;
        }
        #age-verify-decline {
            background: transparent;
            color: #d40000;
        }
        #age-verify-decline:hover {
            background: #d40000;
            color: #fff;
        }
        body.age-verify-blocked > *:not(#age-verify-overlay) {
            filter: blur(20px);
            pointer-events: none;
            user-select: none;
        }
        body.age-verify-blocked {
            overflow: hidden;
        }
    </style>

    <script>
    (function(){
        var KEY = 'ophim_age_verified';
        var overlay = document.getElementById('age-verify-overlay');
        if (!overlay) return;

        if (localStorage.getItem(KEY) === '1') return;

        // Đưa overlay thành direct child của body để không bị kẹt dưới layer pointer-events: none
        document.body.appendChild(overlay);

        document.body.classList.add('age-verify-blocked');
        overlay.classList.add('active');

        document.getElementById('age-verify-accept').addEventListener('click', function(){
            localStorage.setItem(KEY, '1');
            overlay.classList.remove('active');
            document.body.classList.remove('age-verify-blocked');
        });

        document.getElementById('age-verify-decline').addEventListener('click', function(){
            window.location.href = <?php echo json_encode($exit_url); ?>;
        });
    })();
    </script>
    <?php
}
