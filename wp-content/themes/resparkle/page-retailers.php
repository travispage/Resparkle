<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Retailers Page Template
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
        <h1 class="sec-title">STOCKISTS</h1>     
        <ul>
            <li class="toggleBtn"><span class="icon-down-open"></span></li> 
            <li <?php echo ($title == 'Online Shops' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/online-shops/';?>">Online Shops</a></li>
            <li <?php echo ($title == 'Retailers' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/retailers/';?>">Retailers</a></li>
        </ul>
    </div>
    <div id="content" class="page col-full retailer">
    	
		<section id="main"> 	        
        <?php woo_display_breadcrumbs();?>
        <?php
        	if ( have_posts() ) { $count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>                                        
            <?php the_content(); ?>
            <em class="none">
            None in your local? <a href="<?php echo get_bloginfo('url');?>/contact">Email us!</a><br/>
            For International Orders, please visit <a href="http://www.resparkle.com.hk">www.resparkle.com.hk</a> and <a href="http://www.resparkle.com.cn">resparkle.com.cn</a></em>
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