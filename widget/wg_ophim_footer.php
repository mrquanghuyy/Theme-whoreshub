<?php
class WG_oPhim_Footer extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wg_footer', // Base ID
            __( 'Ophim Footer', 'ophim' ), // Name
            array( 'description' => __( 'Mẫu footer', 'ophim' ), ) // Args
        );
    }

    private function get_default_footer_html() {
        ob_start();
        ?>
<div class="footer-index">
    <div class="section-spot">
        <div class="container">
            <h2 class="title">Welcome To WhoresHub</h2>
            <div class="spot-text">
                <p>WhoresHub.com is a large collection of the newest high-quality porn videos in high resolution. Enjoy our user-friendly platform to find and enjoy the best sex videos across different categories on any of your devices. We constantly update each category with the latest HD porn videos to meet the diverse taste of our visitors.</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer-wrap">
            <div class="col second"><a href="<?php echo home_url(); ?>" class="logo" aria-label="logo">
                <?php
                if (function_exists('op_the_logo')) {
                    op_the_logo('height:50px');
                } else {
                    $site_name = get_bloginfo('name');
                    $half = max(1, (int)(strlen($site_name) / 2));
                    echo '<span style="color:#fff">' . esc_html(substr($site_name, 0, $half)) . '</span>';
                    echo '<span style="color:#fa0052">' . esc_html(substr($site_name, $half)) . '</span>';
                }
                ?>
            </a>
                <p class="copy">2025 <?php echo get_bloginfo('name'); ?> All rights reserved.</p>
            </div>
            <div class="col">
                <h3 class="title"><span>Information</span></h3>
                <ul class="footer-list">
                    <li class="item"><a href="<?php echo home_url(); ?>/terms/">Terms &amp; Conditions</a></li>
                    <li class="item"><a href="<?php echo home_url(); ?>/privacy-policy/">Privacy Policy</a></li>
                    <li class="item"><a href="<?php echo home_url(); ?>/dmca/">DMCA</a></li>
                    <li class="item"><a href="<?php echo home_url(); ?>/2257/">18 USC 2257</a></li>
                </ul>
            </div>
            <div class="col">
                <h3 class="title"><span>Work</span> With Us</h3>
                <ul class="footer-list">
                    
                </ul>
            </div>
            <div class="col">
                <h3 class="title"><span>Support</span> and Help</h3>
                <ul class="footer-list">
                    <li class="item"><a href="<?php echo home_url(); ?>/contact/" target="_blank">Contact Support</a></li>
                    <li class="item"><a href="<?php echo home_url(); ?>/sitemap/">Sitemap</a></li>
                </ul>
            </div>
            <div class="col">
                <h3 class="title"><span>Friends</span></h3>
                <ul class="footer-list">

                </ul>
            </div>
        </div>
    </div>
</div>
        <?php
        return ob_get_clean();
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract($args);
        ob_start();
        $footer_content = !empty($instance['footer']) ? $instance['footer'] : $this->get_default_footer_html();
        echo $footer_content;
        echo $after_widget;
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array(
            'footer' => '',
        ) );
        extract($instance);

        $footer_content = !empty($instance['footer']) ? $instance['footer'] : $this->get_default_footer_html();
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('footer'); ?>"><?php _e('Footer Content', 'ophim') ?></label>
            <br />
            <textarea class="widefat" rows="15" id="<?php echo $this->get_field_id('footer'); ?>" name="<?php echo $this->get_field_name('footer'); ?>"><?php echo esc_textarea($footer_content); ?></textarea>
        </p>

        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['footer'] = !empty($new_instance['footer']) ? $new_instance['footer'] : '';
        return $instance;
    }

}
function register_new_widget_Footer() {
    register_widget( 'WG_oPhim_Footer' );
}
add_action( 'widgets_init', 'register_new_widget_Footer' );
