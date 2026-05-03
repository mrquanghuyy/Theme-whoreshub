<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <link rel="profile" href="http://gmgp.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
    <script>
        var url = '<?= get_template_directory_uri() ?>';
        var siteUrl = '<?= esc_url( home_url( '/' ) ); ?>';
        var ajaxUrl = '<?= admin_url( 'admin-ajax.php' ); ?>';
        var actorsSlug = '<?= esc_js( get_option('ophim_slug_actors') ?: 'actors' ) ?>';
        var twfSearchNonce = '<?= esc_js( wp_create_nonce( 'twf_search_suggestions' ) ); ?>';
    </script>
    <style>
        @font-face {
            font-family: 'Quicksand';
            src: url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.eot');
            src: url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.eot?#iefix') format('embedded-opentype'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.woff2') format('woff2'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.woff') format('woff'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.ttf') format('truetype'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Medium.svg#Quicksand-Medium') format('svg');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Quicksand';
            src: url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.eot');
            src: url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.eot?#iefix') format('embedded-opentype'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.woff2') format('woff2'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.woff') format('woff'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.ttf') format('truetype'),url('<?= get_template_directory_uri() ?>/assets/fonts/Quicksand-Regular.svg#Quicksand-Regular') format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?= get_template_directory_uri() ?>/assets/css/app.css" />
    <style>.kt-player {position: absolute !important;left: 0;top: 0;background: transparent !important;z-index: 1;}.player-wrap {position: relative;}</style>
    <style type="text/css">.fancybox-margin{margin-right:0px;}</style>
    <style>#overlay {position: fixed;top: 0; left: 0;width: 100%;height: 100%;background-color: rgba(29, 27, 40, 0.85);backdrop-filter: blur(12px);display: flex;flex-direction: column;justify-content: center;align-items: center;z-index: 2000000;padding: 0 20px;}.overlay-box {background: #FFF;padding: 30px 50px;text-align: center;max-width: 700px;width: 100%;overflow: auto;position: relative;}.overlay-box svg {margin-bottom: 30px;}.overlay-box h2 {color: #E21725;font-size: 30px;font-style: normal;font-weight: 700;margin-bottom: 30px;float: none;text-transform: none;}.overlay-box p {color: #333;text-align: center;font-size: 14px;font-style: normal;font-weight: 400;line-height: 24px;margin-bottom: 30px;}.overlay-box span {display: block;color: #333;font-size: 14px;font-style: normal;font-weight: 400;margin: 30px 0;}.overlay-box span a {color: #E21725;}.overlay-box #okButton {color: #fff;font-size: 16px;font-style: normal;font-weight: 500;text-transform: uppercase;border-radius: 40px;background: #E21725;width: 50%;height: 40px;cursor: pointer;font-size: 16px;cursor: pointer;font-weight: 500;transition: transform .3s ease;}.overlay-box #exitButton {color: #333333;font-size: 16px;font-style: normal;font-weight: 500;text-transform: uppercase;border-radius: 40px;background: #F5F5F5;width: 50%;height: 40px;cursor: pointer;font-size: 16px;cursor: pointer;font-weight: 500;transition: transform .3s ease;}.overlay-box div {display: flex;align-items: center;justify-content: center;gap: 10px;margin-top: 20px;}.overlay-box a {display: flex;align-items: center;justify-content: center;gap: 10px;margin-top: 20px;}@media screen and (hover:hover) {.overlay-box #exitButton:hover, .overlay-box #okButton:hover {transform: scale(1.05);}.overlay-box span a:hover {color: #333;}}@media screen and (max-width: 1024px) {.overlay-box {padding: 30px;}.overlay-box svg, .overlay-box h2, .overlay-box p {margin-bottom: 20px;}}</style>

    <script type="text/javascript" src="<?= get_template_directory_uri() ?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?= get_template_directory_uri() ?>/assets/js/index.js"></script>
    <script type="text/javascript" src="<?= get_template_directory_uri() ?>/assets/js/global.min.js"></script>
    <script type="text/javascript" src="<?= get_template_directory_uri() ?>/assets/js/custom.js"></script>
    
</head>
<?php 
    $theme_class = isset($_COOKIE['kt_rt_theme']) && $_COOKIE['kt_rt_theme'] === 'dark' ? 'dark' : 'white';
?>
<body class="<?= $theme_class ?> age_false">
    <div class="wrapper">
        <?php get_template_part('templates/header'); ?>
        <main class="main">
            <?php get_template_part('templates/aside'); ?>
