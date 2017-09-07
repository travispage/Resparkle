<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Resparkle Movement -- Get Involved Template
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
        <h1 class="sec-title">GET INVOLVED</h1>     
        <ul>
            <li class="toggleBtn"><span class="icon-down-open"></span></li>       
            <li <?php echo ($title == 'Resparkle the World' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/resparkle-movement/get-involved/resparkle-the-world';?>">Reparkle the World</a></li>
            <li <?php echo ($title == 'Resparkle our Homes' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/resparkle-movement/get-involved/resparkle-our-homes';?>">Reparkle our Homes</a></li>
            <li <?php echo ($title == 'Resparkle our Community' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/resparkle-movement/get-involved/resparkle-our-community';?>">Reparkle our Community</a></li>
            <li <?php echo ($title == 'Resparkle our Workspaces' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/resparkle-movement/get-involved/resparkle-our-workspaces';?>">Reparkle our Workspaces</a></li>
        </ul>
    </div>
    <div id="content" class="page col-full">
        
        <section id="main">             
        <?php woo_display_breadcrumbs();?>
        <?php
            if ( have_posts() ) { $count = 0;
                while ( have_posts() ) { the_post(); $count++;
        ?>                                                           
            <article <?php post_class(); ?>>
                
                <section class="entry">
    
                    <?php the_content(); ?>

                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
                </section><!-- /.entry -->
                
            </article><!-- /.post -->
            
            <?php
                // Determine wether or not to display comments here, based on "Theme Options".
                if ( isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'page', 'both' ) ) ) {
                    comments_template();
                }

                } // End WHILE Loop
            } else {
        ?>
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php } // End IF Statement ?>  
        
        </section><!-- /#main -->

        <?php woo_main_after(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>