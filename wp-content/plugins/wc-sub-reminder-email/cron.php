<?php
# Created by HostGeek - hostgeek.com.au
# Last Modified: 26/06/2016

# Purpose: Checks for any subscriptions that are due in 7 days, and sends reminder email to the customer
# using the template.php file as the HTML email template.
# This script is designed to be run on a daily basis via cron

# Connect to WordPress
require_once('/home/resparkl/public_html/wp-load.php');
date_default_timezone_set('Australia/Melbourne');

function productIDtoName($id)
{
    // Resolve Product ID to a name
    global $wpdb;
    $productNames = $wpdb->get_results("SELECT post_title FROM $wpdb->posts	WHERE ID = '" . $id . "'");

    foreach ($productNames as $productName) {
        return $productName->post_title;
    }
}

#Check last run time. Read date from file
$lastrun = file_get_contents('lastrun.txt', true);

if ($lastrun < date('Y-m-d')) {
    #Script was last run yesterday or longer ago, so lets execute
    #Firstly update the script to identify that it was run today
    file_put_contents('lastrun.txt', date('Y-m-d'));

    $notifications = array();
    $errors = array();

    $allSubs = WC_Subscriptions_Manager::get_all_users_subscriptions();
    foreach ($allSubs as $sub_id => $sub_data) {
        if ($sub_data['status'] === 'active') {

            // Get the next payment date (e.g. renewal date) and see if it matches today's date

            if (date('Y-m-d', strtotime("+7 day")) == date('Y-m-d', strtotime(WC_Subscriptions_Order::get_next_payment_date($sub_data['order_id'], $sub_data['product_id'])))) {
                //This line below can be used (with modification) for testing
                //if ('2016-04-01' == date('Y-m-d', strtotime(WC_Subscriptions_Order::get_next_payment_date($sub_data['order_id'], $sub_data['product_id'])))) {

                # This subscription is due in 7 days
                # Get customer details and store as variables
                $results = $wpdb->get_results("SELECT wp_users.display_name, wp_users.user_email FROM wp_postmeta
            LEFT JOIN wp_users on wp_postmeta.meta_value=wp_users.id
            WHERE wp_postmeta.post_id = '" . $sub_data['order_id'] . "' and wp_postmeta.meta_key = '_customer_user'");

                foreach ($results as $result) {
                    $display_name = $result->display_name;
                    $email = $result->user_email;
                }

                #Get product details and store as variables
                $order_child_products = array();
                //Get products listed on the order
                $orderItems = $wpdb->get_results("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id = '" . $sub_data['order_id'] . "'");
                foreach ($orderItems as $orderItem) {

                    //These are the products listed on the order. Get meta for the products
                    $bundleChildProduct = $wpdb->get_results("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '" . $orderItem->order_item_id . "' AND meta_key = '_bundled_item_id'");
                    if ($bundleChildProduct) {
                        // we have a bundled child product, now to get the qty for it
                        $bundleChildQty = $wpdb->get_results("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '" . $orderItem->order_item_id . "' AND meta_key = '_qty'");

                        $order_child_products[productIDtoName($bundleChildProduct[0]->meta_value)] = $bundleChildQty[0]->meta_value;
                    } else {
                        # We are looking at the master bundle product
                        $masterProductName = $wpdb->get_results("SELECT wp_posts.post_title FROM wp_woocommerce_order_itemmeta LEFT JOIN wp_posts on wp_posts.id = wp_woocommerce_order_itemmeta.meta_value WHERE wp_woocommerce_order_itemmeta.order_item_id = '" . $orderItem->order_item_id . "' and wp_woocommerce_order_itemmeta.meta_key = '_product_id'");

                        $bundle_qty = $bundleChildQty[0]->meta_value;

                        # Don't know why, but this variable need to be double set. It works.
                        $bundle_name = $masterProductName[0]->post_title;
                        if (isset($bundle_name)) {
                            $my_bundle_name = $bundle_name;
                        }

                    }
                }

                $to = $email;
                $subject = "Your Resparkle Subscription Renews in 7 Days!";

                $html = file_get_contents('template.php', true);
                $html = str_replace('%%NAME%%', $display_name, $html);
                $html = str_replace('%%EMAIL%%', $email, $html);
                $html = str_replace('%%BUNDLEPRODUCT%%', $my_bundle_name, $html);
                $html = str_replace('%%BUNDLEQTY%%', $bundle_qty, $html);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <no-reply@resparkle.com.au>' . "\r\n";

                if (mail($to, $subject, $html, $headers)) {
                    //echo 'mail sent';
                } else {
                    $client_err = 'There was an error sending the email to ' . $email;
                    array_push($errors, $client_err);
                }

                $notifications[$display_name] = $my_bundle_name;

            }
        }
    }

} else {
    #Script was last run today, exit
    $errmsg = '<html>
    <head>
        <title>Bundle/Subscription Cron Report for ' . date('Y-m-d') . '</title>
    </head>
    <h1>Error - Script run too frequently</h1>
    <p>This cron script was last run on ' . $lastrun . '. This script is designed to be run once daily only.</p>
    </html>';
}

# Send admin email
if (isset($errmsg)) {
    $adminHTML = $errmsg;
} else {
    foreach ($notifications as $name => $product) {
        $notification_table .= '<tr><td>' . $name . '</td><td>' . $product . '</td></tr>';
    }

    $adminHTML = '<html>
    <head>
        <title>Bundle/Subscription Cron Report for ' . date('Y-m-d') . '</title>
    </head>
    <h1>Bundle/Subscription Cron Report</h1>';

    if (empty($notifications)) {
        $adminHTML .= '<p><strong>No notifications to send today</strong></p>';
    } else {
        $adminHTML .= '<p>' . count($notifications) . ' notifications were sent today to:</p><table><thead><tr><td><strong>Customer Name</strong></td><td><strong>Bundle Product</strong></td></tr></thead>';
        $adminHTML .= $notification_table;
        $adminHTML .= '</table>';
    }

    if (!empty($client_err)) {
        $adminHTML .= '<p style="color: red">Errors were encountered:</p><ul></ul>';
        foreach ($client_err as $err) {
            $adminHTML .= '<li>' . $err . '</li>';
        }
        $adminHTML .= '</ul>';
    }

    $adminHTML .= '</html>';
}

$to = get_option(admin_email).', daniel@hostgeek.com.au';
$subject = "Bundle/Subscription Cron Report for " . date('Y-m-d');
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <no-reply@resparkle.com.au>' . "\r\n";

if (mail($to, $subject, $adminHTML, $headers)) {
    echo 'Admin email sent successfully';
} else {
    echo 'There was an error sending the Admin email';
}

