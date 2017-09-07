<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: WooCommerce Subscription & Bundle Reporting
 * Plugin URI: http://www.hostgeek.com.au
 * Description: Provides product requirement forecasting for subscriptions of bundled products
 * Version: 1.0.0
 * Author: HostGeek
 * Author URI: http://www.hostgeek.com.au/
 * Developer: Daniel Cole
 * Developer URI: http://www.hostgeek.com.au/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * Copyright: © 2016 HostGeek.
 */

//Check to ensure WooCommerce is installed and active
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // Create Menu Page under WooCommerce
    function register_wc_sub_bundle_report_page()
    {
        add_submenu_page('woocommerce', 'Stock Projection Reports', 'Stock Projection Reports', 'manage_options', 'wc_sub_bundle_report', 'wc_sub_bundle_report_callback');
    }

    function wc_sub_bundle_report_css_and_js()
    {
        wp_enqueue_style('stylesheet', plugins_url('/style.css', __FILE__), false, '1.0.0', 'all');
    }

    add_action('admin_enqueue_scripts', 'wc_sub_bundle_report_css_and_js');


    //Our page goes here
    function wc_sub_bundle_report_callback()
    {
        echo '<h1>Stock Projection Report</h1>';

        //Work out the month to display
        if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = sanitize_text_field($_GET['month']);
            $year = sanitize_text_field($_GET['year']);
        } else {
            $month = date('m');
            $year = date('Y');
        }

        global $wpdb;
        // Create temp table to store subscription details
        $wpdb->query("CREATE TEMPORARY TABLE stock_projections_summary (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, bundle_title varchar(100), customer_name varchar(20), order_id varchar(10), date varchar(10))");

        // Create temp table to be used for storing this months products
        $wpdb->query("CREATE TEMPORARY TABLE stock_projections (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id varchar(10), qty varchar(10), date varchar(10))");

        $allSubs = WC_Subscriptions_Manager::get_all_users_subscriptions();
        foreach ($allSubs as $sub_id => $sub_data) {
            if ($sub_data['status'] != 'Cancelled' && $sub_data['status'] != 'Refunded') {

                ############################
                # Code for Bundle Title
                ############################
                // Get Customers Name
                $name = $wpdb->get_results("SELECT wp_users.display_name, wp_users.user_email FROM wp_postmeta
                LEFT JOIN wp_users on wp_postmeta.meta_value=wp_users.id
                WHERE wp_postmeta.post_id = '" . $sub_data['order_id'] . "' and wp_postmeta.meta_key = '_customer_user'");

                $custName = $name['0']->display_name;

                // Get products on Order
                //echo 'Order ID: ' . $sub_data['order_id'] . '<br />';
                $products = $wpdb->get_results("SELECT order_item_id, order_item_name FROM wp_woocommerce_order_items WHERE order_id = '" . $sub_data['order_id'] . "'");
                //print_r($products);
                echo '<br />';

                foreach ($products as $product) {
                    # Test to see if meta key '_bundled_item_id' exists. If no, we have the master product
                    $bundleChildProduct = $wpdb->get_results("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '" . $product->order_item_id . "' AND meta_key = '_bundled_item_id'");

                    //echo 'Product Name: '.$product->order_item_name.'<br />';
                    //echo 'BundleChildProduct :'.print_r($bundleChildProduct).'<br />';

                    //if (count($bundleChildProduct) === 0) {
                    # We have the master
                    # check to see if $bundleName is set. If so, we may be looking at the shipping lien item - so ignore it
                    if (!isset($bundleName)) {
                        $bundleName = $product->order_item_name;
                    }
                    //}
                }

                // Test to see how many (if any) times each product appears in our given time frame
                $start_timestamp = mktime(0, 0, 0, $month, 1, $year);
                $end_timestamp = mktime(23, 59, 59, $month, date('t', $start_timestamp), $year);
                $interval = '+' . $sub_data['interval'] . ' ' . $sub_data['period'];

                $i = strtotime($sub_data['start_date']);
                $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));

                // First check to see if we are before the end time of the current period
                while ($i <= $end_timestamp) {
                    // We are, so see if we are past the start time of the period
                    if ($i >= $start_timestamp) {
                        # We are in the zone
                        // Convert unix time to date
                        $thisDate = date('Y-m-d', $i);

                        $wpdb->insert('stock_projections_summary', array(
                            'bundle_title' => base64_encode($bundleName),
                            'customer_name' => $custName,
                            'order_id' => $sub_data['order_id'],
                            'date' => $thisDate));

                        unset($bundleName);

                        $i = strtotime($interval, $i);
                        $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));
                    } else {
                        //We are not jet in the zone, add an interval and go around again
                        $i = strtotime($interval, $i);
                        $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));
                    }
                }
                ############################
                # Ends code for Bundle Title
                ############################

                //Get products listed on the order
                $orderItems = $wpdb->get_results("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id = '" . $sub_data['order_id'] . "'");
                foreach ($orderItems as $orderItem) {

                    //These are the products listed on the order. Get meta for the products
                    $bundleChildProduct = $wpdb->get_results("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '" . $orderItem->order_item_id . "' AND meta_key = '_bundled_item_id'");
                    if ($bundleChildProduct) {
                        // we have a bundled child product, now to get the qty for it
                        $bundleChildQty = $wpdb->get_results("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '" . $orderItem->order_item_id . "' AND meta_key = '_qty'");

                        // Test to see how many (if any) times each product appears in our given time frame
                        $start_timestamp = mktime(0, 0, 0, $month, 1, $year);
                        $end_timestamp = mktime(23, 59, 59, $month, date('t', $start_timestamp), $year);
                        $interval = '+' . $sub_data['interval'] . ' ' . $sub_data['period'];

                        $i = strtotime($sub_data['start_date']);
                        $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));

                        // First check to see if we are before the end time of the current period
                        while ($i <= $end_timestamp) {
                            // We are, so see if we are past the start time of the period
                            if ($i >= $start_timestamp) {
                                # We are in the zone
                                // Convert unix time to date
                                $thisDate = date('Y-m-d', $i);

                                $wpdb->insert('stock_projections', array(
                                    'product_id' => $bundleChildProduct[0]->meta_value,
                                    'qty' => $bundleChildQty[0]->meta_value,
                                    'date' => $thisDate
                                ));

                                $i = strtotime($interval, $i);
                                $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));
                            } else {
                                //We are not jet in the zone, add an interval and go around again
                                $i = strtotime($interval, $i);
                                $i = mktime(date('G', $i), date('i', $i), date('s', $i), date('n', $i), date('j', $i), date('Y', $i));
                            }
                        }
                    }
                }
            }
        }

        // Page Navigation
        $month_last = date('m', mktime(12, 0, 0, $month - 1, 1, $year));
        $year_last = date('y', mktime(12, 0, 0, $month - 1, 1, $year));
        $month_next = date('m', mktime(12, 0, 0, $month + 1, 1, $year));
        $year_next = date('y', mktime(12, 0, 0, $month + 1, 1, $year));
        echo '<div class="bun-rep-nav">
<div id="bun-rep-prev">
<a href="admin.php?page=wc_sub_bundle_report&month=' . $month_last . '&year=' . $year_last . '">Last Month</a>
</div>
<div id="bun-rep-next">
<a href="admin.php?page=wc_sub_bundle_report&month=' . $month_next . '&year=' . $year_next . '">Next Month</a>
</div>
</div>
<div class="clear"></div>';

        //Render the calendar
        $date = mktime(12, 0, 0, $month, 1, $year);
        $daysInMonth = date("t", $date);
        // calculate the position of the first day in the calendar (sunday = 1st column, etc)
        $offset = date("w", $date);
        $rows = 1;

        echo "<h2>Stock Required for " . date("F Y", $date) . "</h2>";

        echo '<table id="wc_sub_rep_table">';
        echo "<tr><th>Sun</th><th>Mon</th><th>Tues</th><th>Wed</th><th>Thur</th><th>Fri</th><th>Sat</th></tr>";
        echo "<tr>";

        for ($i = 1; $i <= $offset; $i++) {
            echo "<td></td>";
        }
        for ($day = 1; $day <= $daysInMonth; $day++) {
            if (($day + $offset - 1) % 7 == 0 && $day != 1) {
                echo "</tr><tr>";
                $rows++;
            }
            echo "<td>";
            echo '<h1>' . $day . '</h1>';

            // Date string for the current date
            $today = strtotime($year . '-' . $month . '-' . $day);
            $today = date('Y-m-d', $today);

            # Bundle Results
            $bundleResults = $wpdb->get_results("SELECT bundle_title, customer_name, order_id FROM stock_projections_summary WHERE date = '" . $today . "'");
            if (count($bundleResults) > 0) {
            echo '<h2>Customers with bundles:</h2>';
            }
            foreach ($bundleResults as $bundleResult) {
                echo base64_decode($bundleResult->bundle_title) . ' for customer: ' . $bundleResult->customer_name . ' on Order #: <a href="' . site_url() . '/wp-admin/post.php?post=' . $bundleResult->order_id . '&action=edit">' . $bundleResult->order_id . '</a><br />';
            }

            # Item Results
            $itemResults = $wpdb->get_results("SELECT product_id, SUM(qty) as quantity, date FROM stock_projections WHERE date = '" . $today . "' GROUP BY product_id");

            if (count($itemResults) > 0) {
                echo '<h2>Items to be shipped:</h2>';
            }
            foreach ($itemResults as $itemResult) {
                // Resolve Product ID to a name
                $productNames = $wpdb->get_results("SELECT post_title FROM $wpdb->posts	WHERE ID = '" . $itemResult->product_id . "'");

                foreach ($productNames as $productName) {
                    $productTitle = $productName->post_title;
                }

                echo $itemResult->quantity . 'x <a href="' . site_url() . '/wp-admin/post.php?post=' . $itemResult->product_id . '&action=edit">' . $productTitle . '</a><br />';
            }
            echo "</td>";
        }
        while (($day + $offset) <= $rows * 7) {
            echo "<td></td>";
            $day++;
        }
        echo "</tr>";
        echo "</table>";

    }

//Register the page
    add_action('admin_menu', 'register_wc_sub_bundle_report_page', 99);
}
