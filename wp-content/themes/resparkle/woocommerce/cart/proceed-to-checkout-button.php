<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="checkout-button button alt wc-backward" >
	<?php _e( 'Continue Shopping', 'woocommerce' ) ?>
</a>

<?php if(is_user_logged_in()): ?>
	<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="checkout-button button alt wc-forward">
		<?php echo __( 'Proceed to Checkout', 'woocommerce' ); ?>
	</a>
<?php else: ?>
	<?php
	// ID of Login page is 971
	$currentUrl = get_current_page_url();
	$loginUrl = get_permalink(get_page(971));

	$loginUrl2 = add_query_arg( array(
		'return_url' => base64_encode($currentUrl),
	), $loginUrl );
	?>

	<a href="<?php echo $loginUrl2 ?>" class="checkout-button button alt wc-forward">
		<?php echo __( 'Proceed to Checkout', 'woocommerce' ); ?>
	</a>
<?php endif; ?>
