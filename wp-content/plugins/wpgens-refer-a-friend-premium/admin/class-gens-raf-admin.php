<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/admin
 * @author     Your Name <email@example.com>
 */
class Gens_RAF_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $gens_raf    The ID of this plugin.
	 */
	private $gens_raf;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $gens_raf       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $gens_raf, $version ) {

		$this->gens_raf = $gens_raf;
		$this->version = $version;

	}

	/**
	 * Add memberships settings page - The Way to Register a Settings Page
	 *
	 * @since 1.0
	 * @param array $settings
	 * @return array
	 */
	public function add_settings_page( $settings ) {

		$settings[] = require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/admin/class-gens-raf-woo-integration.php' );
		return $settings;
	}

	/**
	 * Print an admin notice if woocommerce is deactivated
	 */
	public function no_woo_admin_notice() { ?>
        <div class="error">
            <p><?php _e( 'Refer A Friend Plugin is enabled but not effective. It requires WooCommerce in order to work.', 'gens-raf' ); ?></p>
        </div>
	<?php }

	/**
	 * Add statistics subpage
	 *
	 * @since 1.0
	 * @param array $settings
	 * @return array
	 */
	public function add_stats_page() {
		add_submenu_page( 'woocommerce', __('Refers Stats', $this->gens_raf), __('Refer a Friend Data', $this->gens_raf), 'manage_options', $this->gens_raf, array($this, 'display_plugin_admin_page'));
	}

	 /**
	 * Statistics subpage view
	 *
	 * @since 1.0
	 * @param array $settings
	 * @return array
	 */
	public function display_plugin_admin_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/view/gens-raf-stats-display.php';
	}

	/**
	 * Plugin Settings Link on plugin page
	 *
	 * @since 		1.0.0
	 */
	function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=gens_raf' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Plugin Documentation Link on plugin page
	 *
	 * @since 		1.0.0
	 */
	function docs_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=gens_raf&section=howto' ) . '">Docs</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Add user referral code to backend user profile
	 *
	 * @since 		1.1.0
	 */
	function raf_user_profile_field($user) {
		?>
		<table class="form-table">
	        <tr>
	            <th>
	                <label for="code"><?php _e( 'Refer a friend Link' ); ?></label>
				</th>
				<td>
					<?php if(get_user_meta($user->ID, 'gens_referral_id', true ) != "") {
						echo get_home_url() .'/?raf='. esc_attr( get_user_meta($user->ID, 'gens_referral_id', true ) );
					} else {
						echo "Go to Woocommerce -> System Status -> Tools -> click on 'Create referrals' and then come back here";
					} ?>
				</td>
			</tr>
		</table>
	<?php
	}
	
}
