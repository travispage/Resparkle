<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 *
 * Template Name: News & Events Template
 *
 * @package WooFramework
 * @subpackage Template
 */
get_header();
global $woo_options;
?>
<?php
if ( has_post_thumbnail() ) {
    echo '<div class="masthead">'.get_the_post_thumbnail().'</div>';
}
?>
    <div class="cat-nav">
        <h1 class="sec-title">ABOUT US</h1>
        <ul>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/';?>">Mission</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/story/';?>">Story</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/innovations/packaging';?>">Innovations</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/certifications/';?>">certifications</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/customer-testimonials/';?>">customer testimonials</a></li>
            <li class="active"><a href="<?php echo get_bloginfo('url').'/about-us/in-the-press/';?>">In The Press</a></li>
            <li><a href="http://livehealthyeveryday.org/">Blog</a></li>
        </ul>
    </div>
    <div id="content" class="page col-full news-events">

        <section id="main">
            <?php woo_display_breadcrumbs();?>
            <?php
            $args = array (
                'category_name=news-events'
            );
            $temp = $wp_query; // assign ordinal query to temp variable for later use  
            $wp_query = null;
            $wp_query = new WP_Query($args);
            if ( $wp_query->have_posts() ) :
                while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    $post_id = get_the_id();
                    ?>
                    <article>
                        <div class="post-thumb">
                            <?php echo get_the_post_thumbnail( $page->ID, 'medium' );?>
                        </div>
                        <div class="news-content">
                            <h1 class="title"><a href="<?php echo the_permalink();?>"><?php echo the_title();?></a></h1>
                            <strong class="date"><?php echo get_the_date();?></strong> | <a href="<?php echo the_permalink();?>" class="comment-count"> <?php comments_number( '0 comments' , '1 comment' , '% comments' ); ?> </a>
                            <div class="excerpt"><?php the_content();?></div>
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