<?php

if (!defined('ABSPATH')) exit;


/*-----------------------------------------------------------------------------------*/

/* Start WooThemes Functions - Please refrain from editing this section */

/*-----------------------------------------------------------------------------------*/


// WooFramework init

require_once(get_template_directory() . '/functions/admin-init.php');


/*-----------------------------------------------------------------------------------*/

/* Load the theme-specific files, with support for overriding via a child theme.

/*-----------------------------------------------------------------------------------*/


$includes = array(

    'includes/theme-options.php',            // Options panel settings and custom settings

    'includes/theme-functions.php',        // Custom theme functions

    'includes/theme-actions.php',            // Theme actions & user defined hooks

    'includes/theme-comments.php',            // Custom comments/pingback loop

    'includes/theme-js.php',                // Load JavaScript via wp_enqueue_script

    'includes/sidebar-init.php',            // Initialize widgetized areas

    'includes/theme-widgets.php',            // Theme widgets

    'includes/theme-plugin-integrations.php'// Plugin integrations

);


// Allow child themes/plugins to add widgets to be loaded.

$includes = apply_filters('woo_includes', $includes);


foreach ($includes as $i) {

    locate_template($i, true);

}


/*-----------------------------------------------------------------------------------*/

/* You can add custom functions below */

/*-----------------------------------------------------------------------------------*/


add_theme_support('post-thumbnails');


add_action('init', 'promo_register');

add_action('init', 'feat_register');

add_action('init', 'testimonial_register');

add_action('init', 'ingredient_register');

//add_action('init', 'faq_register');

add_action('init', 'impact_register');

add_action('admin_init', 'admin_init');


function admin_init()

{

    add_meta_box("test_author", "Author", "test_author", "testimonial", "normal", "low");

    add_meta_box("test_from", "From", "test_from", "testimonial", "normal", "low");

    add_meta_box("test_rating", "Rating", "test_rating", "testimonial", "normal", "low");

    add_meta_box("feat_link", "URL", "feat_link", "feature-wall-item", "normal", "low");

}


function feat_link()
{

    global $post;

    $custom = get_post_custom($post->ID);

    $url = $custom["feat_link"][0];

    ?>

    <input style="width: 100%;" name="url" value="<?php echo $url; ?>"/>

    <?php

}


function test_author()
{

    global $post;

    $custom = get_post_custom($post->ID);

    $test_author = $custom["test_author"][0];

    ?>

    <input style="width: 100%;" name="test_author" value="<?php echo $test_author; ?>"/>

    <?php

}


function test_from()
{

    global $post;

    $custom = get_post_custom($post->ID);

    $test_from = $custom["test_from"][0];

    ?>

    <input style="width: 40%;" name="test_from" value="<?php echo $test_from; ?>"/>

    <em>This shows as the nationality/origin of the testimonial's author</em>

    <?php

}


function test_rating()
{

    global $post;

    $custom = get_post_custom($post->ID);

    $test_rating = $custom["test_rating"][0];

    ?>

    <select name="test_rating" style="width: 100px;">

        <option value="5" <?php echo($test_rating == 5 ? 'selected' : ''); ?>>5</option>

        <option value="4" <?php echo($test_rating == 4 ? 'selected' : ''); ?>>4</option>

        <option value="3" <?php echo($test_rating == 3 ? 'selected' : ''); ?>>3</option>

        <option value="2" <?php echo($test_rating == 2 ? 'selected' : ''); ?>>2</option>

        <option value="1" <?php echo($test_rating == 1 ? 'selected' : ''); ?>>1</option>

    </select>

    <em>Rating of this testimonial</em>

    <?php

}


add_action('save_post', 'save_testimonial');


add_action('save_post', 'save_feature_link');


function save_feature_link()
{

    global $post;

    update_post_meta($post->ID, "feat_link", $_POST["url"]);

}


function save_testimonial()
{

    global $post;


    update_post_meta($post->ID, "test_author", $_POST["test_author"]);

    update_post_meta($post->ID, "test_from", $_POST["test_from"]);

    update_post_meta($post->ID, "test_rating", $_POST["test_rating"]);

}


function promo_register()
{


    $labels = array(

        'name' => _x('Promotions', 'post type general name'),

        'singular_name' => _x('Promotion', 'post type singular name'),

        'add_new' => _x('Add New', 'promo item'),

        'add_new_item' => __('Add New Promo Item'),

        'edit_item' => __('Edit Promo Item'),

        'new_item' => __('New Promo Item'),

        'view_item' => __('View Promo Item'),

        'search_items' => __('Search Promotion'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('promo', $args);

}


function feat_register()
{


    $labels = array(

        'name' => _x('Feature Wall', 'post type general name'),

        'singular_name' => _x('Feature Wall Item', 'post type singular name'),

        'add_new' => _x('Add New', 'feature-wall-item'),

        'add_new_item' => __('Add New Feature Wall Item'),

        'edit_item' => __('Edit Feature Wall Item'),

        'new_item' => __('New Feature Wall Item'),

        'view_item' => __('View Feature Wall Item'),

        'search_items' => __('Search Feature Wall Items'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('feature-wall-item', $args);

}


function ingredient_register()
{


    $labels = array(

        'name' => _x('Ingredients', 'post type general name'),

        'singular_name' => _x('Ingredient', 'post type singular name'),

        'add_new' => _x('Add New', 'ingredient item'),

        'add_new_item' => __('Add New Ingredient'),

        'edit_item' => __('Edit Ingredient'),

        'new_item' => __('New Ingredient'),

        'view_item' => __('View Ingredient'),

        'search_items' => __('Search Ingredients'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('ingredient', $args);

}


function faq_register()
{


    $labels = array(

        'name' => _x('FAQs', 'post type general name'),

        'singular_name' => _x('FAQ', 'post type singular name'),

        'add_new' => _x('Add New', 'faq item'),

        'add_new_item' => __('Add New FAQ'),

        'edit_item' => __('Edit FAQ'),

        'new_item' => __('New FAQ'),

        'view_item' => __('View FAQ'),

        'search_items' => __('Search FAQs'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('faq', $args);

}


function testimonial_register()
{


    $labels = array(

        'name' => _x('Testimonials', 'post type general name'),

        'singular_name' => _x('Testimonial', 'post type singular name'),

        'add_new' => _x('Add New', 'Testimonial'),

        'add_new_item' => __('Add New Testimonial'),

        'edit_item' => __('Edit Testimonial'),

        'new_item' => __('New Testimonial'),

        'view_item' => __('View Testimonial'),

        'search_items' => __('Search Testimonial'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('testimonial', $args);

}


function impact_register()
{


    $labels = array(

        'name' => _x('Impacts', 'post type general name'),

        'singular_name' => _x('Impact', 'post type singular name'),

        'add_new' => _x('Add New', 'Impact item'),

        'add_new_item' => __('Add New Impact Item'),

        'edit_item' => __('Edit Impact Item'),

        'new_item' => __('New Impact Item'),

        'view_item' => __('View Impact Item'),

        'search_items' => __('Search Impact'),

        'not_found' => __('Nothing found'),

        'not_found_in_trash' => __('Nothing found in Trash'),

        'parent_item_colon' => ''

    );


    $args = array(

        'labels' => $labels,

        'public' => true,

        'publicly_queryable' => true,

        'show_ui' => true,

        'query_var' => true,

        'menu_icon' => '',

        'rewrite' => true,

        'capability_type' => 'post',

        'hierarchical' => false,

        'menu_position' => null,

        'supports' => array('title', 'editor', 'thumbnail')

    );


    register_post_type('Impact', $args);

}


if (class_exists('MultiPostThumbnails')) {

    new MultiPostThumbnails(

        array(

            'label' => 'Post Image',

            'id' => 'post-image',

            'post_type' => 'Impact'

        )

    );

}


if (class_exists('MultiPostThumbnails')) {

    new MultiPostThumbnails(

        array(

            'label' => 'Supplementary Image',

            'id' => 'supp-image',

            'post_type' => 'Impact'

        )

    );

}


include_once('extendcomment.php');


function new_excerpt_more($more)
{

    return ' <a class="read-more" href="' . get_permalink(get_the_ID()) . '">' . __('Read More', 'your-text-domain') . '</a>';

}

add_filter('excerpt_more', 'new_excerpt_more');


function extend_order_no()

{

    global $post;

    $entend = '';

    $old_id = get_post_meta($post->ID, 'OID', TRUE);

    if (!empty($old_id)) {

        $entend = ' (OID: ' . $old_id . ')';

    }

    return $post->ID . $entend;

}

//add_filter('woocommerce_order_number','extend_order_no');


add_filter('show_admin_bar', '__return_false');


// Sets the time for rss feeds to be cached. This applies to the blog feed pull on the home page.

function feed_expiry_time($seconds)
{

    // change the default feed cache recreation period to 2 hours

    return 60;

}


add_filter('wp_feed_cache_transient_lifetime', 'feed_expiry_time');


// Detects if a currency has been selected with Aelia currency selector plugin. If not, pop up the currency pop up for user to choose.

function currency_selector_popup()

{

    global $woocommerce;


    $curr = $woocommerce->session->get('aelia_cs_selected_currency');


    if (!$curr) {

        ?>

        <div class="curr-overlay">

            <div id="currency-selector">

                <h1>Please select one:</h1>

                <div class="buttons">

                    <?php echo do_shortcode('[aelia_currency_selector_widget widget_type="buttons"]'); ?>

                </div>

            </div>

        </div>

    <?php }

}

//add_action('wp_after_body', 'currency_selector_popup');


// Detects if shipping/billing country is same as selected currency. If not, synchronize and change currency programatically.

function set_currency_programmatically()
{


    if ($_POST['country'] == 'AU') {

        $_POST['aelia_cs_currency'] = 'AUD';

    }

    if ($_POST['country'] == 'CN') {

        $_POST['aelia_cs_currency'] = 'CNY';

    }

}

add_action('woocommerce_init', 'set_currency_programmatically', 0);


function my_currency_labels($currencies, $widget_type, $widget_title, $widget_template_name)
{

    $currencies['AUD'] = '<img class="cntry-icon au" src="' . get_site_url() . '/wp-content/themes/resparkle/images/au.png"> $ AUD';

    $currencies['CNY'] = '<img class="cntry-icon cn" src="' . get_site_url() . '/wp-content/themes/resparkle/images/cn.png"> &yen; RMB';


    return $currencies;

}

add_filter('wc_aelia_currencyswitcher_widget_currency_options', 'my_currency_labels', 10, 4);


// cron for subscription reminders
add_action('wp', 'sub_reminder_email_activation');
function sub_reminder_email_activation()
{
    if (!wp_next_scheduled('sub_reminder_email_cache')) {
        wp_schedule_event(time(), 'daily', 'sub_reminder_email_cache');
    }
}

add_action('sub_reminder_email_cache', 'sub_reminder_email_data');
function sub_reminder_email_data()
{
    //Create empty variable for admin email contents
    $admin_email_contents = '';

    // Get list of subscriptions
    $user_subscriptions = WC_Subscriptions_Manager::get_all_users_subscriptions();

    foreach ($user_subscriptions as $subscription_id => $user_subscription_data) {

        $admin_email_contents .= 'Subscription ID = ' . $subscription_id . '<br />';

        $order_id = $user_subscription_data['order_id'];
        $product_id = $user_subscription_data['product_id'];

        $admin_email_contents .= 'Order ID: ' . $order_id . '<br /> Product ID: ' . $product_id . '<br />';

        // Get the orders User ID
        $order = new WC_Order($order_id);
        $sub_user_id = $order->user_id;

        $admin_email_contents .= 'User ID: ' . $sub_user_id . '<br />';

        $next_date = WC_Subscriptions_Order::get_next_payment_date($order_id, $product_id);
        $next_date = date('Y-m-d', strtotime($next_date));
        $user_info = get_userdata($sub_user_id);

        $admin_email_contents .= 'Next Due Date for subscription is: ' . $next_date . '<br />';

        $now_plus_7 = Date('Y-m-d', strtotime("+31 days"));

        // Check to see if it is exactly 7 days out
        if ($next_date == $now_plus_7) {

            $admin_email_contents .= 'Date Match. Sending email to: ' . $user_info->user_email . '<br />';

            // Set the content type to HTML
            add_filter('wp_mail_content_type', 'set_html_content_type');
            function set_html_content_type()
            {
                return 'text/html';
            }

            $to = $user_info->user_email;
            $subj = 'The email subject';
            $body = 'This is the <strong>body</strong> of the email';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

            $admin_email_contents .= '<strong>Email Details</strong><br />To: ' . $to . '<br />Subject: ' . $subj . '<br />Body: ' . $body . '<br />Headers: ' . $headers . '<br /><br />';

            $sent_message = wp_mail($to, $subj, $body);

            //display message based on the result.
            if ($sent_message) {
                // The message was sent.
                $admin_email_contents .= 'The  message was sent.';
            } else {
                // The message was not sent.
                $admin_email_contents .= 'The message was not sent!';
            }

            // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
            remove_filter('wp_mail_content_type', 'set_html_content_type');
        } else {
            $admin_email_contents .= 'Subscription not due to send email today';
        }
        $admin_email_contents .= '<br /><br />';

    }
    //Display Comments on page
    echo $admin_email_contents;
    add_filter('wp_mail_content_type', 'set_html_content_type');
    function set_html_content_type()
    {
        return 'text/html';
    }

    //wp_mail('daniel@hostgeek.com.au', 'Resparkle Cron', $admin_email_contents);
    remove_filter('wp_mail_content_type', 'set_html_content_type');
}

// END Cron for subscription reminders


// Filter for line breaks in product titles with brackets
add_filter('the_title', 'filter_woocommerce_product_title', 10, 2);
function filter_woocommerce_product_title($title)
{
    // Checks to see if there is a open bracket '(' and if so inserts a line break
    $pos = strpos($title, '(');
    if ($pos != false) {
        $title = substr_replace($title, '<br />', $pos, 0);
    }
    return $title;
}

// Filter to show free shipping for customers with active sub due within next 7 days
function sub_due_next_7_days()
{
    $allSubs = WC_Subscriptions_Manager::get_users_subscriptions(get_current_user_id());
    foreach ($allSubs as $sub_id => $sub_data) {
        if ($sub_data['status'] === 'active') {
            $subNextDue = strtotime(WC_Subscriptions_Order::get_next_payment_date($sub_data['order_id'], $sub_data['product_id']));
            $subDateLess7 = strtotime('-7 day', $subNextDue);
            if (date('Y-m-d') >= date('Y-m-d', $subDateLess7)) {
                return true;
            }
        } else {
            return false;
        }
    }
}

function free_ship_if_bundle($rates)
{
    // Check for active subscription duew within next 7 days
    if (sub_due_next_7_days()) {

        // Set shipping rate to $0
        foreach ($rates as $rate) {
            $rate->cost = 0;
        }
    }
    return $rates;
}

add_filter('woocommerce_package_rates', 'free_ship_if_bundle', 10);

//Modify 'What is PayPal' HTML on checkout page
function newPayment_gateway_icon($icon, $id)
{
    if ($id === 'paypal' && WC_Subscriptions_Cart::cart_contains_subscription() == true) {
        return $icon . '<p class="paypal-bundle-rec"><a href="' . get_site_url() . '/customer-service/faq" target="_blank"><em>Recommended for bundle subscriptions</em></a></p>';
    } else {
        return $icon;
    }
}

add_filter('woocommerce_gateway_icon', 'newPayment_gateway_icon', 10, 2);

// Over ride the shipping rates for bundles.
// Flat fee for 'regional' areas, free for all others
function sub_bundle_ship_rates($rates)
{
    // Check for active subscription due within next 7 days
    if (WC_Subscriptions_Cart::cart_contains_subscription() == true) {

        foreach ($rates as $rate) {
            if (strpos($rate->label, 'Regional') !== false) {
                // Result is a regional location. Over-ride shipping rate to be $9.00
                $rate->cost = 9;
            } else {
                // Result is not a regional location. Over-ride shipping rate to be free
                $rate->cost = 0;
            }
        }
    }
    return $rates;
}

add_filter('woocommerce_package_rates', 'sub_bundle_ship_rates', 10);


$ship_to_different_address = apply_filters('woocommerce_ship_to_different_address_checked', $ship_to_different_address);

// define the woocommerce_ship_to_different_address_checked callback
function filter_woocommerce_ship_to_different_address_checked($ship_to_different_address)
{
    return 0;
}


// add the filter
add_filter('woocommerce_ship_to_different_address_checked', 'filter_woocommerce_ship_to_different_address_checked', 10, 1);

// Change 'month' to 'delivery' for subscription products
function my_subs_price_string($pricestring)
{
    //Force text to lower case to ensure our pattern matching works
    $pricestring = strtolower($pricestring);

    //Check to see if word 'every' is in string
    $pos = strpos($pricestring, 'every');

    if ($pos == false) {
        // Our price string isnt a subscription
        return $pricestring;
    } else {
        // We have a subscription

        $pricestring = str_replace('every month', 'monthly', $pricestring);
        $pricestring = str_replace('every 2 months', 'bi-monthly', $pricestring);

    }
    return $pricestring;
}

add_filter('woocommerce_subscriptions_product_price_string', 'my_subs_price_string');
add_filter('woocommerce_subscription_price_string', 'my_subs_price_string');

// Change Subscription Status Terms
function woo_subscriptions_change_statuses($subscription_statuses)
{
    $subscription_statuses['wc-pending-cancel'] = _x('Cancelled', 'Subscription status', 'woocommerce-subscriptions'); // Was Pending Cancellation
    return $subscription_statuses;
}

add_action('wcs_subscription_statuses', 'woo_subscriptions_change_statuses', 1);

// Action for sending email to customer and admin on subscription cancelled
add_action('cancelled_subscription', 'resparkle_sub_cancelled', 10, 2);

function resparkle_sub_cancelled($user_id, $subscription_key)
{
// Get the customers information
    $custdata = get_userdata($user_id);

// Get subscription info
    $sub = WC_Subscriptions_Manager::get_subscription($subscription_key);

    $order = new WC_Order($sub['order_id']);
    $items = $order->get_items();
    foreach ($items as $item) {
        $r2u_name = $item['name'];
        break;
    }

// Send email to customer
    $to = $custdata->user_email;
    $subject = "Resparkle2U Subscription Cancellation Confirmation";

    $message = "
<html>
<head>
    <title>Resparkle2U Subscription Cancellation Confirmation</title>
</head>
<body>
<p>Hi " . $custdata->display_name . ",</p>
<p>This email confirms that your subscription for <em>" . $r2u_name . "</em> has been <strong>Cancelled</strong><p>
<p>If you feel you have received this email in error, please contact us at <a href='mailto:info@resparkle.com.au'>info@resparkle.com.au</a>.</p>
<p>Regards,<br />The Resparkle Team</p>
</body>
</html>
";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $custEmail = mail($to, $subject, $message, $headers);

    if ($custEmail) {
// Sent email to customer successfully
        $failed = 0;
    } else {
// Failed to send email
        $failed = 1;

    }

// Send email to admin
    $to = 'info@resparkle.com.au';
    $subject = "Resparkle2U Customer Subscription Cancellation";

    $message = "
<html>
<head>
    <title>Resparkle2U Customer Subscription Cancellation</title>
</head>
<body>
<p>Customer <em>" . $custdata->display_name . "</em> has <strong>Cancelled</strong> their subscription to: " . $r2u_name . " (Order: " . $sub['order_id'] . ")</p>";

    if ($failed == 1) {
        $message .= '<p style="color: red;">Note: Sending email confirmation to customer failed!</p>';
        $message .= '<p style="color: red;">Error: ' . print_r(error_get_last(), 1) . '</p>';
    }

    $message .= "</body></html>";

// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $adminEmail = mail($to, $subject, $message, $headers);

    if ($adminEmail) {
        // Sent email to customer successfully
    } else {
        // Failed to send email
    }
}

// Action for sending email to customer and admin on subscription cancelled
add_action('woocommerce_customer_changed_subscription_to_on-hold', 'resparkle_sub_hold', 10, 2);

function resparkle_sub_hold($user_id, $subscription_key)
{

    file_put_contents('/home/resparklecom/public_html/debug.txt', print_r(get_defined_vars(), 1) . PHP_EOL, FILE_APPEND);


// Get the customers information
    $custdata = get_userdata($user_id);

// Get subscription info
    $sub = WC_Subscriptions_Manager::get_subscription($subscription_key);

    $order = new WC_Order($sub['order_id']);
    $items = $order->get_items();
    foreach ($items as $item) {
        $r2u_name = $item['name'];
        break;
    }

// Send email to customer
    $to = $custdata->user_email;
    $subject = "Resparkle2U Subscription Pause Confirmation";

    $message = "
<html>
<head>
    <title>Resparkle2U Subscription Pause Confirmation</title>
</head>
<body>
<p>Hi " . $custdata->display_name . ",</p>
<p>This email confirms that your subscription for <em>" . $r2u_name . "</em> has been <strong>Paused</strong><p>
<p>If you feel you have received this email in error, please contact us at <a href='mailto:info@resparkle.com.au'>info@resparkle.com.au</a>.</p>
<p>Regards,<br />The Resparkle Team</p>
</body>
</html>
";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $custEmail = mail($to, $subject, $message, $headers);

    if ($custEmail) {
// Sent email to customer successfully
        $failed = 0;
    } else {
// Failed to send email
        $failed = 1;
    }

// Send email to admin
    $to = 'info@resparkle.com.au';
    $subject = "Resparkle2U Customer Subscription Paused";

    $message = "
<html>
<head>
    <title>Resparkle2U Customer Subscription Paused</title>
</head>
<body>
<p>Customer <em>" . $custdata->display_name . "</em> has <strong>Paused</strong> their subscription to: " . $r2u_name . " (Order: " . $sub['order_id'] . ")</p>";

    if ($failed == 1) {
        $message .= '<p style="color: red;">Note: Sending email confirmation to customer failed!</p>';
        $message .= '<p style="color: red;">Error: ' . print_r(error_get_last(), 1) . '</p>';
    }

    $message .= "</body></html>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $adminEmail = mail($to, $subject, $message, $headers);

    if ($adminEmail) {
        // Sent email to customer successfully
    } else {
        // Failed to send email
    }
}

// Action for sending email to customer and admin on subscription active from paused
add_action('woocommerce_customer_changed_subscription_to_active', 'resparkle_sub_hold_to_active', 10, 1);

//file_put_contents('/home/resparklecom/public_html/debug.txt', 'Vars in functions.php:'.PHP_EOL.print_r(get_defined_vars(),1).PHP_EOL, FILE_APPEND);

function resparkle_sub_hold_to_active($vars)
{
    file_put_contents('/home/resparklecom/public_html/debug.txt', print_r(get_defined_vars(), 1) . PHP_EOL, FILE_APPEND);
    // Get subscription info
    $sub = WC_Subscriptions_Manager::get_subscription($vars->id);

    $order = new WC_Order($vars->id);
    $items = $order->get_items();
    foreach ($items as $item) {
        $r2u_name = $item['name'];
        break;
    }


// Send email to customer
    $to = $order->billing_email;
    $subject = "Resparkle2U Subscription Resume Confirmation";

    $message = "
<html>
<head>
    <title>Resparkle2U Subscription Resume Confirmation</title>
</head>
<body>
<p>Hi " . $order->display_name . ",</p>
<p>This email confirms that your subscription for <em>" . $r2u_name . "</em> has been <strong>Resumed</strong><p>
<p>If you feel you have received this email in error, please contact us at <a href='mailto:info@resparkle.com.au'>info@resparkle.com.au</a>.</p>
<p>Regards,<br />The Resparkle Team</p>
</body>
</html>
";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $custEmail = mail($to, $subject, $message, $headers);

    if ($custEmail) {
// Sent email to customer successfully
        $failed = 0;
    } else {
// Failed to send email
        $failed = 1;
    }

// Send email to admin
    $to = 'info@resparkle.com.au';
    $subject = "Resparkle2U Customer Subscription Resumed";

    $message = "
<html>
<head>
    <title>Resparkle2U Customer Subscription Paused</title>
</head>
<body>
<p>Customer <em>" . $order->display_name . "</em> has <strong>Resumed</strong> their subscription to: " . $r2u_name . " (Order: " . $sub['order_id'] . ")</p>";

    if ($failed == 1) {
        $message .= '<p style="color: red;">Note: Sending email confirmation to customer failed!</p>';
        $message .= '<p style="color: red;">Error: ' . print_r(error_get_last(), 1) . '</p>';
    }

    $message .= "</body></html>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <info@resparkle.com.au>' . "\r\n";

    $adminEmail = mail($to, $subject, $message, $headers);

    if ($adminEmail) {
        // Sent email to customer successfully
    } else {
        // Failed to send email
    }
}

//Over-ride the checkout.min.js file. Need scroll on error in checkout to be higher
add_action('wp_enqueue_scripts', 'override_woo_frontend_scripts');
function override_woo_frontend_scripts()
{
    wp_deregister_script('wc-checkout');
    wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/woocommerce/js/checkout.min.js', array('jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n'), null, true);
}


function resparkle_override_default_address_fields($address_fields)
{
    $address_fields['address_1']['placeholder'] = "Kindly note that we are unable to ship to PO Boxes or Parcel Lockers";
    $address_fields['address_2']['placeholder'] = "";
    return $address_fields;
}

//Checks to see if a product is a bundle and a subscription, adds text to price string
add_filter('woocommerce_get_price_html', 'r2u_sub_price_message', 20000, 3);
function r2u_sub_price_message($price, $product)
{
    $is_sub = get_post_meta($product->id, '_wcsatt_force_subscription', true);
    if ($product->is_type('bundle') && $is_sub == 'yes') {
        return $price . ' + Free Metro Shipping!';
    } else {
        return $price;
    }
}

function resparkle_update_date($sub_id)
{
    $subscription = wcs_get_subscription($sub_id);

    if ($subscription->can_date_be_updated('next_payment')) {
        $dates = array();
        $current_status = $subscription->get_status();

        $dates['next_payment'] = date('Y-m-d H:i:s', strtotime("+121 minutes"));

        try {
            // $subscription->update_dates($dates, 'site');
            $subscription->update_dates($dates);
            wp_cache_delete($sub_id, 'posts');
        } catch (Exception $e) {
            wcs_add_admin_notice($e->getMessage(), 'error');
        }
    }
}

add_action('resparkle_do_update', 'resparkle_update_date', 10, 1);


// Action for resuming subscription, with modifying next due date to be in 10 mins time.
add_action('woocommerce_subscription_status_on-hold_to_active', 'resparkle_sub_resume');
function resparkle_sub_resume($vars)
{
    // Check to see if our meta key is set on the order. If not, ignore (as the order may be going from on-hold to active
    // when the first payment is received.
    if (get_post_meta($vars->id, 'resparkle-hold-key', true) == $vars->id) {
        // error_log('shadi - found key');
        $args = array('id' => $vars->id);
        wp_schedule_single_event(time() + 60, 'resparkle_do_update', $args);

        delete_post_meta($vars->id, 'resparkle-hold-key');
    }
}

add_action('woocommerce_customer_changed_subscription_to_on-hold', 'resparkle_sub_pause', 10, 2);
function resparkle_sub_pause($vars)
{
    // Create our key for putting the sub on hold
    add_post_meta($vars->id, 'resparkle-hold-key', $vars->id);
}


# Disable strong password requirements
function wc_ninja_remove_password_strength()
{
    if (wp_script_is('wc-password-strength-meter', 'enqueued')) {
        wp_dequeue_script('wc-password-strength-meter');
    }
}

add_action('wp_print_scripts', 'wc_ninja_remove_password_strength', 100);
?>
<?php
// Ecentura Start Adjust Code:
function lmc_it_custom_init()
{
    register_post_type('in-the-press', array('labels' => array('name' => 'In The Press', 'add_new_item' => 'Add new item', 'add_new' => 'Add new item', 'all_items' => 'All items'), 'public' => true, 'public_queryable' => true, 'menu_position' => 5, 'supports' => array('title', 'thumbnail', 'editor')));

    register_taxonomy('in-the-press-cat', 'in-the-press', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Category'
        )
    ));

}

add_action('init', 'lmc_it_custom_init', 0);

if (!function_exists('get_current_page_url')) {
    function get_current_page_url()
    {
        global $wp;
        return add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request));
    }
}
?>