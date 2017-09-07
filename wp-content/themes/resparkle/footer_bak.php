<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
?>
	<?php // woo_display_breadcrumbs(); ?>
	
	
</div>	
	<br class="clear"/>
	<div class="footer-wrap">

	<?php
	global $woo_options;

	$total = 4;
	if ( isset( $woo_options['woo_footer_sidebars'] ) && ( $woo_options['woo_footer_sidebars'] != '' ) ) {
		$total = $woo_options['woo_footer_sidebars'];
	}

	if ( ( woo_active_sidebar( 'footer-1' ) ||
		   woo_active_sidebar( 'footer-2' ) ||
		   woo_active_sidebar( 'footer-3' ) ||
		   woo_active_sidebar( 'footer-4' ) ) && $total > 0 ) {

?>

	<?php woo_footer_before(); ?>

		<section id="footer-widgets" class="col-full col-<?php echo $total; ?> fix">

			<?php $i = 0; while ( $i < $total ) { $i++; ?>
				<?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>

			<div class="block footer-widget-<?php echo $i; ?>">
	        	<?php woo_sidebar( 'footer-' . $i ); ?>
			</div>

		        <?php } ?>
			<?php } // End WHILE Loop ?>

		</section><!-- /#footer-widgets  -->
	<?php } // End IF Statement ?>
		<br class="clear"/>
		<footer id="footer" class="footer">

			<div class="wrapper">
				<div class="social">
					<div class="img">
						<a href="https://www.facebook.com/resparkleaustralia"><img src="<?php echo get_template_directory_uri();?>/assets/img/fb.png" alt=""></a>
						<a href="https://twitter.com/reSPARKLEOz"><img src="<?php echo get_template_directory_uri();?>/assets/img/twitter.png" alt=""></a>
						<a href="https://www.pinterest.com/resparkle/"><img src="<?php echo get_template_directory_uri();?>/assets/img/pinterest.png" alt=""></a>
					</div>

					<?php echo do_shortcode('[mc4wp_form id="1918"]');?>
				</div>

				<div class="bottom">
					<a href="<?php echo get_bloginfo('url').'/privacy-policy';?>">PRIVACY POLICY</a> <span>|</span>
					<a href="<?php echo get_bloginfo('url').'/terms-conditions';?>">TERMS &amp; CONDITIONS</a>
					<span class="right">&copy; 2015 RESPARKLE ALL RIGHTS RESERVED.</span>
				</div>

				<div class="back">
					<a href="#">BACK<br/>TO TOP</a>
				</div>
			</div>

		</footer><!-- /#footer  -->

	</div><!--/.footer-wrap-->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
<script>
	jQuery(function(){						

		jQuery('.ham').on('click',function(e){
			e.preventDefault();
			btn = jQuery(this);
			btn.toggleClass('active');
			jQuery('html,body').animate({scrollTop: 0 }, 500);
			jQuery('#mobile-nav').toggleClass('show');
		});
		jQuery('#tab-description').perfectScrollbar();
		jQuery('#comments').perfectScrollbar();
		jQuery('#store_container').perfectScrollbar();
		
		jQuery('.select2').select2();
		
        jQuery('.icheck input').iCheck({
			checkboxClass: 'icheckbox_minimal-orange',
			radioClass: 'iradio_minimal-orange',
			increaseArea: '20%'
		});
		
		jQuery('.back a').on('click',function(e){
			e.preventDefault();
			jQuery('html,body').animate({scrollTop: 0 }, 1000);
		});
		
		if(jQuery('.thumbnails img').length > 0) {
			jQuery('.thumbnails img').on('click',function(e){
				e.preventDefault();
				var img = jQuery(this).attr('src');
				jQuery(this).parent().addClass('active').siblings().removeClass('active');
				jQuery('.woocommerce-main-image img').attr('src',img);
			});
		}


		jQuery('a.add_to_cart_button').click(function(){

			var cart_count = jQuery('.cart_count').html();
			jQuery('.cart_count').html( parseInt(cart_count) + 1);

			/* this is about cart */ 
			var cart_amount = jQuery(this).prev().prev();

			if(cart_amount.find('del').length) {
				cart_amount = cart_amount.find('ins span.amount').html().substring(1);
			} else {
				cart_amount = cart_amount.find('span.amount').html().substring(1);
			}

			var total_amount = jQuery('.links .amount');
			var total = parseFloat(cart_amount) + parseFloat(total_amount.html().substring(1));
			jQuery(total_amount.html('$'+ total));

		});

		if(jQuery('body').hasClass('home') ) {
			jQuery('#main-nav').removeClass('stuck');
		}
		
		if(jQuery('.checkout-login').length > 0) {
			var sticky = new Waypoint.Sticky({
			  element: jQuery('.checkout-login')[0]
			});
		}
		
		jQuery('.cat-nav .toggleBtn').on('click',function(e){
			e.preventDefault();
			jQuery('.cat-nav ul').toggleClass('show');
		});
		
		jQuery('.closeBtn').on('click',function(e){
			e.preventDefault();
			jQuery(this).parent().fadeOut();	
		})

	});
</script>
</body>
</html>