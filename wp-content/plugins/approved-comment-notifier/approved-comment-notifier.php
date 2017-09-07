<?php
/*
Plugin Name: Approved Comment Notifier
Plugin URL: http://remicorson.com/approved-comment-notifier
Description: This plugins sends an email to comment author when his comment is approved
Version: 1.0
Author: Remi Corson
Author URI: http://remicorson.com
Contributors: corsonr
Text Domain: rc_acn
Domain Path: languages
*/

/**
 * Uses the Settings_API to register the plugin options.
 * Options are saved in the wp_options table under the Option Name.
 *
 * Option group is rc_acn_plugin_options
 * Option name is rc_acn_options
 *
 * Initialized with admin_init hook
 */
function rc_acn_init(){
    register_setting( 'rc_acn_plugin_options', 'rc_acn_options');
    add_action( 'init', 'rc_acn_textdomain');
}
add_action('admin_init', 'rc_acn_init' );


/**
 * Load the plugin's text domain
 *
 */
function rc_acn_textdomain() {
	load_plugin_textdomain( 'rc_acn_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Enqueue scripts for rich editor
 *
 * Initialized with admin_init hook
 */ 
function rc_acn_editor_admin_init( $hook ) {
    // bail early if this is not the plugin option screen
    if( 'settings_page_approved-comment-notifier/approved-comment-notifier' !== $hook ) {
        return;
    }
	wp_enqueue_script('word-count');
	wp_enqueue_script('post');
	wp_enqueue_script('editor');
	wp_enqueue_script('media-upload');
}
add_action('admin_init', 'rc_acn_editor_admin_init');

/**
 * Load TinyMCE
 *
 * Initialized with admin_head hook
 */
function rc_acn_editor_admin_head( $hook ) {
    // bail early if this is not the plugin option screen
    if( 'settings_page_approved-comment-notifier/approved-comment-notifier' !== $hook ) {
        return;
    }
	wp_tiny_mce();
}
add_action('admin_head', 'rc_acn_editor_admin_head');

/**
 * Adds the plugin's option page to the Settings menu section.
 * Defines plugin options page parameters.
 *
 * Initialized with admin_menu hook
 */
function rc_acn_add_options_page() {
    add_options_page( __('Approved Comment Notifier', 'rc_acn'), __('Approved Comment Notifier', 'rc_acn'), 'manage_options', __FILE__, 'rc_acn_display_options');
}

add_action('admin_menu', 'rc_acn_add_options_page');

/**
 * Load the built-in 'wp-color-picker' script and style.
 * Load the plugin specific 'cp-demo-custom' script that handles
 * the use of the wp-color-picker script on our plugin options page.
 *
 * @param  string   $hook   current admin screen suffix
 *
 * Initialized with admin_enqueue_scripts hook
 */
function rc_acn_admin_enqueue_scripts($hook) {
    // bail early if this is not the plugin option screen
    if( 'settings_page_approved-comment-notifier/approved-comment-notifier' !== $hook ) {
        return;
    }
    // load admin style
    wp_enqueue_style( 'rc_acn_admin_style', plugins_url( 'includes/css/rc_acn_style.css', __FILE__ ) );

}

add_action( 'admin_enqueue_scripts', 'rc_acn_admin_enqueue_scripts' );



/**
 * Displays plugin options
 *
 * Initialized in rc_acn_add_options_page()
 */
function rc_acn_display_options() {
    include "includes/admin-options.php";
}

/**
 * Sends the email to comment author once comment is approved
 *
 * Initialized with comment_unapproved_to_approved hook
 */
function rc_acn_approved_comment_notifier( $comment ) {

	// Get options
	$options = get_option( 'rc_acn_options' );

	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

	$comment_id           = $comment->comment_post_ID;
	$comment_post_ID      = $comment->comment_post_ID;
	$comment_author       = $comment->comment_author;
	$comment_author_email = $comment->comment_author_email;
	$comment_date         = $comment->comment_date;
	$comment_id           = $comment->comment_post_ID;

	$email_content = nl2br($options['email']);
	$email_content = str_replace( '{comment_author}', $comment_author, $email_content );
	$email_content = str_replace( '{comment_date}', date_i18n( get_option('date_format'), strtotime( $comment_date ) ), $email_content );
	$email_content = str_replace( '{commented_post_name}', get_the_title($comment_post_ID), $email_content );
	$email_content = str_replace( '{commented_post_url}', get_permalink($comment_post_ID), $email_content );
	$email_content = str_replace( '{site_name}', get_bloginfo('name'), $email_content );
	$email_content = str_replace( '{site_url}', get_bloginfo('url'), $email_content );
	$email_content = apply_filters( 'rc_acn_email_content', $email_content );
	
	$to      = $comment_author_email;
	$subject = $options['subject'];

	$headers[] = 'From: '.$options['from_name'].' <'.$options['from_email'].'>';

	wp_mail( $to, $subject, $email_content, $headers );

}

add_action('comment_unapproved_to_approved', 'rc_acn_approved_comment_notifier');
