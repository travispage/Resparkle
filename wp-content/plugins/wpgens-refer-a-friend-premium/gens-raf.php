<?php

/**
 * Plugin Name: Refer a Friend for WooCommerce PREMIUM
 * Plugin URI: https://wpgens.com/downloads/refer-a-friend-for-woocommerce-premium/
 * Description: PREMIUM Refer a friend by WPGENS. Go to WooCommerce -> Settings -> Refer a friend tab to set it up.
 * Version: 1.2.1
 * Author: WPGens
 * Author URI: https://wpgens.com
 * Text Domain: gens-raf
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define Globals for EDD Licence Manager
define( 'GENS_RAF_STORE_URL', 'https://wpgens.com' );
define( 'GENS_RAF_ITEM_NAME', 'Refer a Friend for WooCommerce PREMIUM' );
define( 'GENS_RAF_PLUGIN_LICENSE_PAGE', 'gens-raf' );
define( 'GENS_RAF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_gens_raf() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gens-raf-activator.php';
	Gens_RAF_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_gens_raf' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gens-raf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_premium_gens_raf() {

	$plugin = new Gens_RAF();
	$plugin->run();

}

// Need to run after Woo has been loaded
add_action( 'plugins_loaded', 'run_premium_gens_raf' );
