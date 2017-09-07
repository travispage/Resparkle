<?php
if (!defined('ABSPATH')) exit;
/**
 * Page Template
 *
 * Template Name: Bundles Template
 *
 * @package WooFramework
 * @subpackage Template
 */
get_header();
global $woo_options;
$title = get_the_title();
?>

    <script type="text/javascript" src="/wp-content/themes/resparkle/js/jssor.slider.mini.js"></script>
    <!-- use jssor.slider.debug.js instead for debug -->
    <script>
        jQuery(document).ready(function ($) {

            var jssor_1_SlideoTransitions = [
                [{b: 5500, d: 3000, o: -1, r: 240, e: {r: 2}}],
                [{b: -1, d: 1, o: -1, c: {x: 51.0, t: -51.0}}, {
                    b: 0,
                    d: 1000,
                    o: 1,
                    c: {x: -51.0, t: 51.0},
                    e: {o: 7, c: {x: 7, t: 7}}
                }],
                [{b: -1, d: 1, o: -1, sX: 9, sY: 9}, {b: 1000, d: 1000, o: 1, sX: -9, sY: -9, e: {sX: 2, sY: 2}}],
                [{b: -1, d: 1, o: -1, r: -180, sX: 9, sY: 9}, {
                    b: 2000,
                    d: 1000,
                    o: 1,
                    r: 180,
                    sX: -9,
                    sY: -9,
                    e: {r: 2, sX: 2, sY: 2}
                }],
                [{b: -1, d: 1, o: -1}, {b: 3000, d: 2000, y: 180, o: 1, e: {y: 16}}],
                [{b: -1, d: 1, o: -1, r: -150}, {b: 7500, d: 1600, o: 1, r: 150, e: {r: 3}}],
                [{b: 10000, d: 2000, x: -379, e: {x: 7}}],
                [{b: 10000, d: 2000, x: -379, e: {x: 7}}],
                [{b: -1, d: 1, o: -1, r: 288, sX: 9, sY: 9}, {
                    b: 9100,
                    d: 900,
                    x: -1400,
                    y: -660,
                    o: 1,
                    r: -288,
                    sX: -9,
                    sY: -9,
                    e: {r: 6}
                }, {b: 10000, d: 1600, x: -200, o: -1, e: {x: 16}}]
            ];

            var jssor_1_options = {
                $AutoPlay: true,
                $SlideDuration: 800,
                $SlideEasing: $Jease$.$OutQuint,
                $CaptionSliderOptions: {
                    $Class: $JssorCaptionSlideo$,
                    $Transitions: jssor_1_SlideoTransitions
                },
                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$
                },
                $BulletNavigatorOptions: {
                    $Class: $JssorBulletNavigator$
                }
            };

            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1920);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }

            ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
    </script>

    <style>

        /* jssor slider bullet navigator skin 05 css */
        /*
        .jssorb05 div           (normal)
        .jssorb05 div:hover     (normal mouseover)
        .jssorb05 .av           (active)
        .jssorb05 .av:hover     (active mouseover)
        .jssorb05 .dn           (mousedown)
        */
        .jssorb05 {
            position: absolute;
        }

        .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
            position: absolute;
            /* size of bullet elment */
            width: 16px;
            height: 16px;
            background: url('img/b05.png') no-repeat;
            overflow: hidden;
            cursor: pointer;
        }

        .jssorb05 div {
            background-position: -7px -7px;
        }

        .jssorb05 div:hover, .jssorb05 .av:hover {
            background-position: -37px -7px;
        }

        .jssorb05 .av {
            background-position: -67px -7px;
        }

        .jssorb05 .dn, .jssorb05 .dn:hover {
            background-position: -97px -7px;
        }

        /* jssor slider arrow navigator skin 22 css */
        /*
        .jssora22l                  (normal)
        .jssora22r                  (normal)
        .jssora22l:hover            (normal mouseover)
        .jssora22r:hover            (normal mouseover)
        .jssora22l.jssora22ldn      (mousedown)
        .jssora22r.jssora22rdn      (mousedown)
        */
        .jssora22l, .jssora22r {
            display: block;
            position: absolute;
            /* size of arrow element */
            width: 40px;
            height: 58px;
            cursor: pointer;
            background: url('img/a22.png') center center no-repeat;
            overflow: hidden;
        }

        .jssora22l {
            background-position: -10px -31px;
        }

        .jssora22r {
            background-position: -70px -31px;
        }

        .jssora22l:hover {
            background-position: -130px -31px;
        }

        .jssora22r:hover {
            background-position: -190px -31px;
        }

        .jssora22l.jssora22ldn {
            background-position: -250px -31px;
        }

        .jssora22r.jssora22rdn {
            background-position: -310px -31px;
        }
    </style>


    <div class="billboard">
        <div class="slides">
            <?php
            $args = array('post_type' => 'slide', 'posts_per_page' => 5, 'orderby' => 'date', 'order' => 'ASC', 's' => 'bundles');
            $loop = new WP_Query($args);
            while ($loop->have_posts()) : $loop->the_post();

                echo '<div><a href="' . get_post_meta($post->ID, 'url', TRUE) . '" target="_blank">' . get_the_post_thumbnail($page->ID, 'full') . '</a></div>';

            endwhile;
            ?>
        </div>
    </div>
    <div class="content">
        <div class="cat-nav">
            <?php
            $prod_cats = get_terms('product_cat', array('hide_empty' => 0, 'orderby' => 'id', 'order' => 'asc', 'parent' => 0));
            $current_cat = get_query_var('product_cat');
            ?>
            <h1 class="sec-title">SHOP</h1>
            <ul>
                <li class="toggleBtn"><span class="icon-down-open"></span></li>
                <?php foreach ($prod_cats as $cat): ?>
                    <li <?php echo($current_cat == $cat->slug ? 'class="active"' : ''); ?>><a
                            href="<?php echo get_bloginfo('url') . '/product-category/' . $cat->slug; ?>"><?php echo $cat->name; ?></a>
                    </li>
                <?php endforeach; ?>
                <li><a href="<?php echo get_bloginfo('url') . '/online-shops/'; ?>">Stockists</a></li>
            </ul>
        </div>

        <!--products in bundles category-->
        <?php echo do_shortcode('[product_category category="subscription-bundles" column="4" orderby="menu_order" order="asc" perpage="4"]'); //The product ID's to show?>
        <a name="title"></a>
        <section id="main">
            <div class="cat-nav red nav-blp">
                <ul>
                    <li class="toggleBtn"><span class="icon-down-open"></span></li>
                    <li <?php echo($title == 'Bundles' ? 'class="active"' : ''); ?>><a
                            href="<?php echo get_bloginfo('url') . '/bundles/#title'; ?>">Why Bundle</a></li>
                    <li <?php echo($title == 'How It Works' ? 'class="active"' : ''); ?>><a
                            href="<?php echo get_bloginfo('url') . '/bundles/how-it-works/#title'; ?>">How it works</a>
                    </li>
                </ul>
                <div class="ruler-red"></div>
            </div>
            <div id="content" class="page col-full">


                <?php
                if (have_posts()) {
                    $count = 0;
                    while (have_posts()) {
                        the_post();
                        $count++;
                        ?>
                        <article <?php post_class(); ?>>

                            <section class="entry">

                                <?php the_content(); ?>

                                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'woothemes'))); ?>
                            </section><!-- /.entry -->

                        </article><!-- /.post -->

                        <?php
                        // Determine wether or not to display comments here, based on "Theme Options".
                        if (isset($woo_options['woo_comments']) && in_array($woo_options['woo_comments'], array('page', 'both'))) {
                            comments_template();
                        }

                    } // End WHILE Loop
                } else {
                    ?>
                    <article <?php post_class(); ?>>
                        <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes'); ?></p>
                    </article><!-- /.post -->
                <?php } // End IF Statement ?>

        </section><!-- /#main -->

        <?php woo_main_after(); ?>

        <br class="clear"/>
        <div class="testimonials">
            <h1 class="sec-title">TESTIMONIALS</h1>
            <div class="intro fullw">
                <div class="wrapper">
                    Don't take it from us - hear what our customers have to say about Resparkle!
                </div>
            </div>

            <div class="wrapper">

                <div class="slider">
                    <!--<div class="slider slick-initialized slick-slider">
                        <div class="slick-track">-->
                    <?php
                    $args = array('post_type' => 'testimonial', 'posts_per_page' => 10);
                    $loop = new WP_Query($args);
                    while ($loop->have_posts()) : $loop->the_post();
                        $test_id = get_the_ID();
                        ?>
                        <a href="<?php echo $blog_url; ?>about-us/customer-testimonials" class="test">
                            <?php the_content(); ?>
                            <div class="rating">
                                <div class="star-rating">
                                    <?php
                                    $rating = get_post_meta($test_id, 'test_rating', TRUE);
                                    $percentage = ($rating / 5) * 100;
                                    ?>
                                    <span style="width: <?php echo $percentage; ?>%">
    								<strong class="rating"><?php echo $rating; ?></strong>
    								out of 5
    							</span>
                                </div>
                                <span class="author"><?php echo get_post_meta($test_id, 'test_author', TRUE); ?></span>
                                <span class="from"><?php echo get_post_meta($test_id, 'test_from', TRUE); ?></span>
                            </div>
                        </a>
                        <?php
                    endwhile;
                    ?>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
    </div><!-- /#content -->

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

        });
    </script>

    </div><!-- /#content -->
<?php get_footer(); ?>