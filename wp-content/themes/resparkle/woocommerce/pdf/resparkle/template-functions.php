<?php
/**
 * Use this file for all your template filters and actions.
 * Requires WooCommerce PDF Invoices & Packing Slips 1.4.13 or higher
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function mytestfunction()
{
    global $woocommerce;
    $oid = $wpo_wcpdf->order_number();
    echo 'oid: '.$oid.'<br />';
    if (WC_Subscriptions_Order::order_contains_subscription($oid)) {
        echo 'Order contains subscription<br />';
        $subkey = WC_Subscriptions_Manager::get_subscription_key($oid,$item_id);
        echo 'Subscription Key = '.$subkey.'<br />';
        echo $oid.$subkey;
        $subdata = WC_Subscriptions_Manager::get_subscription('2967'.$subkey);
        print_r($subdata);
        global $woocommerce;

    }
};

add_filter('get_extra_1','mytestfunction',10,1);