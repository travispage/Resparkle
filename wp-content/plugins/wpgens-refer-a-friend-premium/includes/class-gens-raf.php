<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Gens_RAF
 * @subpackage Gens_RAF/includes
 */
class Gens_RAF {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Gens_RAF_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $gens_raf    The string used to uniquely identify this plugin.
	 */
	protected $gens_raf;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->gens_raf = 'gens-raf';
		$this->version = '1.2.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->edd_update_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gens_RAF_Loader. Orchestrates the hooks of the plugin.
	 * - Gens_RAF_i18n. Defines internationalization functionality.
	 * - Gens_RAF_Admin. Defines all hooks for the dashboard.
	 * - Gens_RAF_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gens-raf-loader.php';

		/**
		 * EDD Auto Update
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/edd_licence/class.wpgens-raf-licence.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gens-raf-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gens-raf-admin.php';

		/**
		 * The class is responsible for page that shows all referrals.
		 */
		if(!class_exists('WP_List_Table')){
		   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gens-list-refs.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-gens-raf-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-my-account.php';
		$this->loader = new Gens_RAF_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gens_RAF_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Gens_RAF_i18n();
		$plugin_i18n->set_domain( $this->get_gens_raf() );

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Automatic update of plugin via EDD Licence plugin
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function edd_update_hooks() {

		$edd_update = new EDD_WPGens_RAF_Licence();
		$this->loader->add_action( 'admin_init', $edd_update, 'gens_raf_auto_update', 0 );
		$this->loader->add_action( 'admin_init', $edd_update, 'gens_raf_activate_licence' );
		$this->loader->add_action( 'admin_init', $edd_update, 'gens_raf_deactivate_licence' );
		$this->loader->add_action( 'admin_init', $edd_update, 'gens_raf_licence_options');

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Gens_RAF_Admin( $this->get_gens_raf(), $this->get_version() );

		//Run if Woo Is Not Activated
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'no_woo_admin_notice' );
		} else {
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_stats_page' );
			$this->loader->add_filter( 'woocommerce_get_settings_pages', $plugin_admin, 'add_settings_page' );
		}

		// Add user referral code to backend user profile.
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'raf_user_profile_field' );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'raf_user_profile_field' );
		// Add links under plugin page.
		$this->loader->add_filter( 'plugin_action_links_wpgens-refer-a-friend-premium/gens-raf.php', $plugin_admin, 'add_settings_link' );
		$this->loader->add_filter( 'plugin_action_links_wpgens-refer-a-friend-premium/gens-raf.php', $plugin_admin, 'docs_link' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public     = new Gens_RAF_Public( $this->get_gens_raf(), $this->get_version() );
		$plugin_front      = new Gens_RAF_Front( $this->get_gens_raf(), $this->get_version() );
		$plugin_my_account = new Gens_RAF_MyAccount( $this->get_gens_raf(), $this->get_version() );
		// Simple script calling
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		
		// Create coupon and send it after order has been changed to complete - main function
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_public, 'gens_create_send_coupon' );
		// Save RAF ID in Order Meta after Order is Complete
		$this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'save_raf_id');
		//Remove Cookie after checkout if Setting is set
		$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'remove_cookie_after' );
		// Add Admin notes in orders regarding referral user
		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_public, 'show_admin_raf_notes' );
		// Hide auto applied coupon codes from showing.
		$this->loader->add_filter( 'woocommerce_cart_totals_coupon_label', $plugin_public, 'hide_coupon_code', 10, 2 );

		// Add referral code to every user afer registration - in case someone needs it before user hits my account page
		$this->loader->add_action( 'user_register', $plugin_public, 'gens_new_user_registration_hook' );
		// Add Tab to every product with shortcode
		$this->loader->add_filter( 'woocommerce_product_tabs', $plugin_front, 'raf_product_tab' );
		// Create button to create missing referral ID's for every user.
		$this->loader->add_filter( 'woocommerce_debug_tools', $plugin_public, 'gens_tools' );
		// Auto apply RAF Coupons on cart for referrals. Also apply on checkout if cart is skipped.
		$this->loader->add_action( 'woocommerce_before_cart', $plugin_public, 'apply_matched_coupons' ); // woocommerce_before_checkout_form
		$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'apply_matched_coupons' ); // woocommerce_before_checkout_form
		$this->loader->add_action( 'woocommerce_checkout_update_order_review', $plugin_public , 'checkout_form_check' );

		// Refer a Friend TAB
		$this->loader->add_action( 'wp_ajax_gens_share_email_function', $plugin_my_account, 'gens_share_email_function' );
		$this->loader->add_action( 'init', $plugin_my_account, 'gens_myreferral_tab' );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_my_account, 'gens_account_menu_item', 10, 1 );
		$this->loader->add_action( 'woocommerce_account_myreferrals_endpoint', $plugin_my_account, 'gens_account_referral_content' );
	}

	/**
	 * Register all of the shortcodes
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		$plugin_public = new Gens_RAF_Front( $this->get_gens_raf(), $this->get_version() );

		$this->loader->wpcf7_add_form_tag( 'gens_raf', $plugin_public, 'wpcf7_gens_shortcode_handler' );

		$this->loader->add_shortcode( 'WOO_GENS_RAF', $plugin_public, 'main_raf_shortcode_handler' );

		$this->loader->add_shortcode( 'WOO_GENS_RAF_ADVANCE', $plugin_public, 'advance_raf_shortcode_handler' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_gens_raf() {
		return $this->gens_raf;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Gens_RAF_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
