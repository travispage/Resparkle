<?php
if (!defined('ABSPATH')) exit;

if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'woothemes_add_javascript');
}

if (!function_exists('woothemes_add_javascript')) {
    function woothemes_add_javascript()
    {
        global $woo_options;

        wp_register_script('prettyPhoto', get_template_directory_uri() . '/includes/js/jquery.prettyPhoto.js', array('jquery'));
        wp_register_script('enable-lightbox', get_template_directory_uri() . '/includes/js/enable-lightbox.js', array('jquery', 'prettyPhoto'));
        wp_register_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=false');
        wp_register_script('google-maps-markers', get_template_directory_uri() . '/includes/js/markers.js');
        wp_register_script('infinite-scroll', get_template_directory_uri() . '/includes/js/jquery.infinitescroll.min.js', array('jquery'));
        wp_register_script('woo-masonry', get_template_directory_uri() . '/includes/js/jquery.masonry.min.js', array('jquery'));
        wp_register_script('slick', get_template_directory_uri() . '/assets/js/slick/slick.min.js', array('jquery'));
        wp_register_script('waypoints', get_template_directory_uri() . '/assets/js/waypoints/jquery.waypoints.min.js', array('jquery'));
        wp_register_script('waypoints-sticky', get_template_directory_uri() . '/assets/js/waypoints/sticky.min.js', array('waypoints'));
        wp_register_script('scrollbar', get_template_directory_uri() . '/assets/js/scrollbar/perfect-scrollbar.with-mousewheel.min.js', array('jquery'));

        wp_enqueue_script('third party', get_template_directory_uri() . '/includes/js/third-party.js', array('jquery'));
        wp_enqueue_script('tiptip', get_template_directory_uri() . '/includes/js/jquery.tiptip.min.js', array('jquery'));

        wp_register_style('slick-css', get_template_directory_uri() . '/assets/js/slick/slick.css');
        wp_register_style('slick-theme', get_template_directory_uri() . '/assets/js/slick/slick-theme.css', array('slick-css'));
        wp_register_style('scrollbar', get_template_directory_uri() . '/assets/js/scrollbar/perfect-scrollbar.min.css');

        wp_register_script('select', get_template_directory_uri() . '/assets/js/select2/select2.min.js', array('jquery'));
        wp_register_style('select2', get_template_directory_uri() . '/assets/js/select2/select2.min.css');

        wp_register_script('icheck', get_template_directory_uri() . '/assets/js/icheck/icheck.min.js', array('jquery'));
        wp_register_style('icheck', get_template_directory_uri() . '/assets/js/icheck/minimal/orange.css');

        wp_register_script('fancybox', get_template_directory_uri() . '/assets/js/fancybox/jquery.fancybox.pack.js', array('jquery'));
        wp_register_style('fancybox', get_template_directory_uri() . '/assets/js/fancybox/jquery.fancybox.css');


        wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js');


        global $woocommerce;
        $terms = get_the_terms($post->ID, 'product_cat');
        if(!empty($terms)){
        foreach ($terms as $term) {
            if ($term->slug != 'subscription-bundles') {
                wp_enqueue_script('scrollbar');
                wp_enqueue_style('scrollbar');
            }
        }
        }
        wp_enqueue_script('waypoints-sticky');

        // Load scripts for home page
        if (is_home()) {
            wp_enqueue_script('slick');
            wp_enqueue_style('slick-theme');
            add_action('wp_head', 'resparkle_home_slick');
        } // End If Statement

        if (is_page(3080) || is_page(3083)) {
            wp_enqueue_script('slick');
            wp_enqueue_style('slick-theme');
            add_action('wp_head', 'resparkle_home_slick');
        }

        wp_enqueue_script('icheck');
        wp_enqueue_style('icheck');

        wp_enqueue_script('select');
        wp_enqueue_style('select2');

        wp_enqueue_script('modernizr');

        // Load Google Script on Contact Form Page Template
        if (is_page_template('template-contact.php')) {
            wp_enqueue_script('google-maps');
            wp_enqueue_script('google-maps-markers');
        } // End If Statement

        // Load infinite scroll on shop page / product cats
        if (is_woocommerce_activated()) {
            if (($woo_options['woocommerce_archives_infinite_scroll'] == 'true') && (is_woocommerce())) {
                wp_enqueue_script('infinite-scroll');
            }
        }

        // Load Masonry on the blog grid layout
        if (is_page_template('template-blog-grid.php')) {
            wp_enqueue_script('woo-masonry');
            add_action('wp_head', 'woo_fire_masonry');
        }

        // LOAD IMPACT SCRIPTS
        if (is_page_template('page-our-impact.php')) {
            wp_enqueue_script('fancybox');
            wp_enqueue_style('fancybox');
            add_action('wp_head', 'impact_js');
        }

        do_action('woothemes_add_javascript');
    } // End woothemes_add_javascript()
}

if (!is_admin()) {
    add_action('wp_print_styles', 'woothemes_add_css');
}

if (!function_exists('woothemes_add_css')) {
    function woothemes_add_css()
    {
        wp_register_style('prettyPhoto', get_template_directory_uri() . '/includes/css/prettyPhoto.css');

        do_action('woothemes_add_css');
    } // End woothemes_add_css()
}

if (!function_exists('woo_fire_masonry')) {
    function woo_fire_masonry()
    { ?>
        <script>
            jQuery(window).load(function ($) {
                if (jQuery(window).width() > 767) {
                    jQuery('.blog-grid').masonry({
                        itemSelector: '.post',
                        // set columnWidth a fraction of the container width
                        columnWidth: function (containerWidth) {
                            return containerWidth / 2;
                        }
                    });
                }
            });
        </script>
    <?php }
}

/* IMPACT PAGE */
if (!function_exists('impact_js')) {
    function impact_js()
    { ?>

        <script>
            jQuery(function ($) {

                jQuery('a.cover').fancybox({
                    type: 'ajax',
                    topRatio: 0,
                    helpers: {
                        overlay: {
                            locked: false
                        }
                    }
                });

            });

            jQuery(window).load(function ($) {
                var imp_id = location.hash.replace('#', '');

                if (imp_id) {
                    jQuery('a[data-id=' + imp_id + ']').click();
                }
            });
        </script>

    <?php }
}

if (!function_exists('resparkle_home_slick')) {
    function resparkle_home_slick()
    { ?>
        <script>
            jQuery(function ($) {
                jQuery('.billboard .slides').slick({
                    dots: true,
                    infintite: true,
                    speed: 500,
                    fade: true,
                    cssEase: 'linear',
                    autoplay: true,
                    arrows: false
                });
                jQuery('.testimonials .slider').slick({
                    infintite: true,
                    autoplay: true,
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    autoplaySpeed: 2500,
                    arrows: true,
                    prevArrow: '<button class="arr-left"><span class="icon-left-open-big"></span></button>',
                    nextArrow: '<button class="arr-right"><span class="icon-right-open-big"></span></button>',
                    responsive: [
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                arrows: false
                            }
                        }
                    ]
                });
                var sticky = new Waypoint.Sticky({
                    element: jQuery('#main-nav')[0]
                });
            });
        </script>
    <?php }
}

// Add an HTML5 Shim

add_action('wp_head', 'html5_shim');

if (!function_exists('html5_shim')) {
    function html5_shim()
    {
        ?>
        <!--[if lt IE 9]>
        <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php
    } // End html5_shim()
}

?>