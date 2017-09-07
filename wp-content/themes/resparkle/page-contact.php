<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Contact Page Template
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
    <div class="cat-nav red">
        <h1 class="sec-title">CONTACT US</h1>
        <ul>
            <li class="toggleBtn"><span class="icon-down-open"></span></li>
            <li <?php echo ($title == 'Contact' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/contact/';?>">Customers</a></li>            
            <li <?php echo ($title == 'Wholesalers / Distributors' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/contact/wholesalers-distributors';?>">Wholesalers / Distributors</a></li>            
            <li <?php echo ($title == 'Bloggers / Affiliates' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/contact/bloggers-affiliates';?>">Bloggers / Affiliates</a></li>
            <li <?php echo ($title == 'Media' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/contact/media';?>">Media</a></li>
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