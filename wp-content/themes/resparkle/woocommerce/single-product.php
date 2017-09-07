<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
	<h1 class="sec-title">PRODUCT DETAILS</h1>
		<?php do_action('woo_display_breadcrumbs');?>

		<?php while ( have_posts() ) : the_post(); ?>


            <?php
            // Add custom css that applies to buindles pages (with the name 'box' in the title to allow the full description to be shown

            $terms = get_the_terms( $post->ID, 'product_cat' );
            foreach ($terms as $term) {
                if ($term->slug == 'subscription-bundles') {
                    echo '<style>
                .single-product #main #tab-description {
                    /*overflow: inherit !important;*/
                    height: inherit !important;
                }
                .ps-scrollbar-x, .ps-scrollbar-y {
                    display: none;
                }
            </style>';
                }
            }

                wc_get_template_part('content', 'single-product');
             ?>


		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>
