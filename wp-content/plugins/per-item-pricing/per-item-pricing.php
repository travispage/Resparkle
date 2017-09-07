<?php
/**
 * Plugin Name: WooCommerce Product Bundles - Hide Bundled Item Prices
 * Plugin URI: http://www.woothemes.com/products/composite-products/
 * Description: Use this snippet to hide bundled item prices in all templates when Per-Item Pricing is checked.
 * Version: 1.0
 * Author: SomewhereWarm
 * Author URI: http://www.somewherewarm.net/
 * Developer: Manos Psychogyiopoulos
 *
 * Requires at least: 3.8
 * Tested up to: 4.1
 *
 * Copyright: © 2015 Manos Psychogyiopoulos (psyx@somewherewarm.net).
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// To use this snippet, download this file into your plugins directory and activate it, or copy the code under this line into the functions.php file of your (child) theme.

add_filter( 'woocommerce_cart_item_price', 'wc_pb_empty_bundled_item_cart_order_price', 11, 2 );
add_filter( 'woocommerce_cart_item_subtotal', 'wc_pb_empty_bundled_item_cart_order_price', 11, 2 );
add_filter( 'woocommerce_checkout_item_subtotal', 'wc_pb_empty_bundled_item_cart_order_price', 11, 2 );
add_filter( 'woocommerce_order_formatted_line_subtotal', 'wc_pb_empty_bundled_item_cart_order_price', 11, 2 );
add_filter( 'woocommerce_bundled_item_price_html', 'wc_pb_empty_bundled_item_price_html', 100, 3 );
function wc_pb_empty_bundled_item_price_html( $price, $original_price, $item ) {
    if ( ! $item->is_optional() ) {
        $price = '';
    }
    return $price;
}
function wc_pb_empty_bundled_item_cart_order_price( $price, $values ) {
    if ( isset( $values[ 'bundled_by' ] ) ) {
        $price = '';
    }
    return $price;
}