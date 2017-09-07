<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 *
 * Template Name: Customer Testimonials Template
 *
 * @package WooFramework
 * @subpackage Template
 */
get_header();
global $woo_options;
?>
<?php
if ( has_post_thumbnail() ) { ?>
    <div class="masthead">
        <?php echo the_post_thumbnail();?>
    </div>
    <?php
}
?>
    <div class="cat-nav">
        <h1 class="sec-title">ABOUT US</h1>
        <ul>
            <li class="toggleBtn"><span class="icon-down-open"></span></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/';?>">Mission</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/story/';?>">Story</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/innovations/packaging';?>">Innovations</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/certifications/';?>">certifications</a></li>
            <li class="active"><a href="<?php echo get_bloginfo('url').'/customer-testimonials/';?>">customer testimonials</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/in-the-press/';?>">In The Press</a></li>
            <li><a href="http://livehealthyeveryday.org/">Blog</a></li>
        </ul>
    </div>
    <div id="content" class="page col-full customer-testimonials">

        <section id="main">

            <?php
            $args = array (
                'post_type' => 'testimonial',
                'post_status' => 'publish',
                'paged' => $paged,
                'posts_per_page' => 10,
                'ignore_sticky_posts'=> 1
            );
            $temp = $wp_query; // assign ordinal query to temp variable for later use  
            $wp_query = null;
            $wp_query = new WP_Query($args);
            if ( $wp_query->have_posts() ) :
                while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    $test_id = get_the_id();
                    ?>
                    <article>
                        <h2 class="title"><?php echo the_title();?></h2>
                        <div class="rating">
                            <div class="star-rating">
                                <?php
                                $rating = get_post_meta( $test_id, 'test_rating', TRUE );
                                $percentage = ($rating / 5)*100;
                                ?>
                                <span style="width: <?php echo $percentage;?>%">
                                <strong class="rating"><?php echo $rating;?></strong>
                                out of 5
                            </span>
                            </div>
                            <?php
                            $author = get_post_meta( $test_id, 'test_author', TRUE );
                            $from = get_post_meta( $test_id, 'test_from', TRUE );
                            ?>
                            <span class="author"><?php echo $author;?></span>
                            <span class="from"><?php echo ($from ? '('.$from.')' : '');?></span>
                            <span class="date"><?php echo get_the_date('d/m/y');?></span>
                        </div>
                        <div class="test-content">
                            <?php the_content();?>
                        </div>
                    </article>
                    <?php
                endwhile; ?>

                <div class="pagination">
                    <div class="nav-next"><?php previous_posts_link( '<span class="icon-left-open-big"></span>' ); ?></div>
                    <div class="nav-previous"><?php next_posts_link( '<span class="icon-right-open-big"></span>' ); ?></div>
                </div>

                <?php
            else :
                echo '<h2>Not Found</h2>';
                get_search_form();
            endif;
            $wp_query = $temp;
            ?>

        </section><!-- /#main -->

        <?php woo_main_after(); ?>

    </div><!-- /#content -->

<?php get_footer(); ?>