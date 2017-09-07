<?php
// File Security Check
if ( ! function_exists( 'wp' ) && ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?><?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
    
	$settings = array(
					'homepage_enable_product_categories' => 'true',
					'homepage_enable_featured_products' => 'true',
					'homepage_enable_recent_products' => 'true',
					'homepage_enable_testimonials' => 'true',
					'homepage_enable_content' => 'true',
					'homepage_product_categories_title' => '',
					'homepage_product_categories_limit' => 4,
					'homepage_featured_products_title' => '',
					'homepage_featured_products_limit' => 4,
					'homepage_recent_products_title' => '',
					'homepage_recent_products_limit' => 4,
					'homepage_number_of_testimonials' => 4,
					'homepage_testimonials_area_title' => '',
					'homepage_content_type' => 'posts',
					'homepage_page_id' => '',
					'homepage_posts_sidebar' => 'true'
					);
	$settings = woo_get_dynamic_values( $settings );

	$layout_class = 'full-width';
?>
    <div class="content">

    	<?php // woo_main_before(); ?>
    	<div class="billboard">
    		<div class="slides">
    			<?php
	    			$args = array( 'post_type' => 'slide', 'posts_per_page' => 6, 'orderby' => 'date', 'order' => 'ASC' );
	    			$loop = new WP_Query( $args );
	    			while ( $loop->have_posts() ) : $loop->the_post();
	    		
	    			echo '<div><a href="'.get_post_meta( $post->ID, 'url', TRUE ).'" target="_blank">'.get_the_post_thumbnail( $page->ID, 'full' ).'</a></div>';
	    		
	    			endwhile;
	    		?>
    		</div>
    	</div>
    	<?php 
    		$blog_url = get_bloginfo('url').'/';
    	?>

        <?php get_template_part('page', 'nav'); ?>
    	
    	<br class="clear"/>
    	
    	<div class="promo">
    		<?php
    			$args = array( 'post_type' => 'promo', 'posts_per_page' => 1 );
    			$loop = new WP_Query( $args );
    			while ( $loop->have_posts() ) : $loop->the_post();
    		
    			echo the_content();
    		
    			endwhile;
    		?>
    	</div>
    	
    	<div class="healthy">
    		
    		<h1 class="sec-title">Live Healthy Every Day</h1>
    		<div class="intro fullw">
    			<div class="wrapper">
    				Brighten your everyday with tips and inspiration from our blog!
    			</div>
    		</div>
    		
    		<div class="tabs">    			
    			
    			<?php echo do_shortcode('[mc4wp_form id="1919"]');?>
    			
    		</div>
    		
    		<br class="clear"/>
    		
            <?php
                include_once(ABSPATH . WPINC . '/feed.php'); 
                $feed = fetch_feed('http://livehealthyeveryday.org/feed/'); 
                $limit = $feed->get_item_quantity(4);
                $items = $feed->get_items(0, $limit);
            ?>
    		<div class="tab-content" id="latest">
    			<ul>
                    <?php
                    foreach($items as $item): ?>
                    <li>                        
                    <?php
                        
                        $html = $item->get_content();
                        preg_match( '@src="([^"]+)"@' , $html, $match );
                        $src = array_pop($match);
                    ?>
                        <a href="<?php echo $item->get_permalink();?>" class="cover" target="_blank"><img src="<?php echo $src;?>"/></a>
                        <div class="prod_body">
                            <a href="<?php echo $item->get_permalink();?>" class="title" target="_blank"><?php echo $item->get_title();?></a>
                            <em>POSTED <?php echo $item->get_date('j F Y');?></em>
                        </div>
                    </li>                    
    				<?php endforeach; ?>
    			</ul>
    		</div>
    		
    		<br class="clear"/>
    		
    	</div> <!--./ health -->
    	
    	<div class="bestsellers">
    		<div class="wrapper">
    			<h1 class="sec-title">BESTSELLERS</h1>
    			<?php                    
    				if ( 'true' == $settings['homepage_enable_featured_products'] && is_woocommerce_activated() ) {
    					the_widget( 'Woo_Featured_Products', array( 'title' => stripslashes( $settings['homepage_featured_products_title'] ), 'products_per_page' => intval( $settings['homepage_featured_products_limit'] ) ) );
    				}
    			?>
    			<a href="<?php echo $blog_url;?>/shop" class="view-all">
    				<span class="icon-circle-right"></span>
    				<strong>VIEW ALL PRODUCTS</strong>
    			</a>
    		</div>
    	</div>
    	
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
    				<?php
    					$args = array( 'post_type' => 'testimonial', 'posts_per_page' => 10 );
    					$loop = new WP_Query( $args );
    					while ( $loop->have_posts() ) : $loop->the_post();
    					$test_id = get_the_ID();
    				?>
    				<a href="<?php echo $blog_url;?>about-us/customer-testimonials" class="test">
    					<?php the_content();?>
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
    						<span class="author"><?php echo get_post_meta( $test_id, 'test_author', TRUE );?></span>
    						<span class="from"><?php echo get_post_meta( $test_id, 'test_from', TRUE );?></span>
    					</div>
    				</a>
    				<?php
    					endwhile;
    				?>    				
    			</div>
    		</div>
    	</div>
    	
    	<br class="clear"/>
    	
    	<div class="social-feeds">
    		
    		<h1 class="sec-title">SOCIAL FEEDS</h1>
    		<div class="instagram-wall">
    		  <?php echo do_shortcode('[instagram-feed]');?>
    		</div>
    		<div class="feature-wall">
                <?php
                    $args = array( 'post_type' => 'feature-wall-item', 'posts_per_page' => 3 );
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post();
                    $feat_id = get_the_ID();
                ?>
    			<div class="feat">
    				<a href="<?php echo get_post_meta( $feat_id, 'feat_link', TRUE );?>"><?php echo get_the_post_thumbnail($feat_id,'full');?></a>
    			</div>
                <?php
                    endwhile;
                ?>      			
    		</div>
    	</div>		

		<?php woo_main_after(); ?>

        <?php if ( 'true' == $settings['homepage_posts_sidebar'] ) { get_sidebar(); } ?>

    </div><!-- /#content -->

<?php get_footer(); ?>