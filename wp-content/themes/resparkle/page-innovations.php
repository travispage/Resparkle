<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Innovations Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
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
    <div class="cat-nav">        
        <h1 class="sec-title">INNOVATIONS</h1>   
        <ul>
            <li class="toggleBtn"><span class="icon-down-open"></span></li>          
            <li <?php echo ($title == 'Packaging' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/about-us/innovations/packaging/';?>">Packaging</a></li>
            <li <?php echo ($title == 'Concentrates' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/about-us/innovations/concentrates/';?>">Concentrates</a></li>
            <li <?php echo ($title == 'Performance' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/about-us/innovations/performance/';?>">Performance</a></li>
            <li <?php echo ($title == 'Awards' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/about-us/innovations/awards/';?>">Awards</a></li>
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