<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Our Impact Template
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
    $title = get_the_title();
?>    
    <?php 
        if ( has_post_thumbnail() ) { ?>
            <div class="masthead">
            <?php echo the_post_thumbnail();?>
            </div>
        <?php
        }
    ?>
    <div class="cat-nav blue">
        <h1 class="sec-title">RESPARKLE MOVEMENT</h1>     
        <ul>        
            <li class="toggleBtn"><span class="icon-down-open"></span></li>    
            <li><a href="<?php echo get_bloginfo('url').'/resparkle-movement/';?>">What is it</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/resparkle-movement/how-it-works/';?>">How it works</a></li>
            <li class="active"><a href="<?php echo get_bloginfo('url').'/resparkle-movement/our-impact';?>">Our Impact</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/resparkle-movement/get-involved/';?>">Get Involved</a></li>
        </ul>
    </div>
    <div id="content" class="page col-full our-impact">        
        <section id="main">
            <div class="impact-container">
                <?php
                    $args = array( 'post_type' => 'Impact', 'posts_per_page' => 10, 'orderby' => 'date', 'order' => 'DESC' );
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>
                    <article class="impact">
                        <strong class="date"><?php echo the_date('M Y');?></strong>
                        <a href="<?php echo get_the_permalink();?>" class="cover" data-id="<?php echo get_the_id();?>">
                            <?php echo get_the_post_thumbnail( $page->ID, 'full' );?>
                        </a>
                    </article>
                <?php
                    endwhile;
                    
                    wp_reset_postdata();
                ?>
            </div>
        
        </section><!-- /#main -->

        <?php woo_main_after(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>