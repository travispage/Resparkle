<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/public
 * @author     Your Name <email@example.com>
 */
class Gens_RAF_Public {

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
	 * @var      string    $gens_raf       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $gens_raf, $version ) {

		$this->gens_raf = $gens_raf;
		$this->version = $version;

	}

	/**
	 * Show or call to generate new referal ID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function get_referral_id($user_id) {

		if ( !$user_id ) {
			return false;
		}
		$referralID = get_user_meta($user_id, "gens_referral_id", true);
		if($referralID && $referralID != "") {
			return $referralID;
		} else {
			do{
			    $referralID = $this->generate_referral_id();
			} while ($this->exists_ref_id($referralID));
			update_user_meta( $user_id, 'gens_referral_id', $referralID );
			return $referralID;
		}

	}

	/**
	 * Check if ID already exists
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function exists_ref_id($referralID) {

		$args = array('meta_key' => "gens_referral_id", 'meta_value' => $referralID );
		if (get_users($args)) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Generate a new Referral ID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	function generate_referral_id($randomString="ref")
	{

	    $characters = "0123456789";
	    for ($i = 0; $i < 7; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	/**
	 * Get number of referrals for a user
	 *
	 * @since    1.1.0
	 * @return   string
	 */
	public function get_number_of_referrals($user_id) {
		$number = get_user_meta($user_id, "gens_num_friends", true);
		return $number;
	}

	/**
	 * Save RAF(User) ID in Order Meta after Order is Complete
	 * woocommerce_checkout_update_order_meta hook
	 *
	 * @since    1.0.0
	 * @since    1.1.0  Added filter
	 * @return   string
	 */
	public function save_raf_id( $order_id ) {
		$not_active = get_option( 'gens_raf_disable' );
		$allow_existing   = get_option( 'gens_raf_allow_existing' );
		$order = new WC_Order( $order_id );
	    $_user_id = $order->get_user_id();
	    $num_friends_refered = $this->get_number_of_referrals($_user_id);
	    $user_id = 0;
		if(isset($_COOKIE["gens_raf"])) {
			$gens_users = get_users( array(
				"meta_key" => "gens_referral_id",
				"meta_value" => $_COOKIE["gens_raf"],
				"number" => 1,
				"fields" => "ID"
			) );
			$user_id = (int)$gens_users[0];
		}
	    /*
	    $user_info = get_userdata($myuser_id);
	    $items = $order->get_items();
		*/
		// If filter returns "yes", order wont be saved as referal.
		$not_active = apply_filters('gens_raf_not_active', $not_active, $num_friends_refered, $order);

		if ( isset($_COOKIE["gens_raf"]) && $not_active != "yes" && get_current_user_id() !== $user_id && ($this->has_enough_orders() < 1 || $allow_existing == "yes") ) {
			$rafID = $_COOKIE["gens_raf"];
			update_post_meta( $order_id, '_raf_id', esc_attr($rafID));
		}
    	return $order_id;
	}

	/**
	 * Generate coupon and email it after order status has been changed to complete
	 * woocommerce_order_status_completed hook
	 *
	 * @since    1.0.0
	 * @since    1.1.0  Added filter
	 */
	public function gens_create_send_coupon($order_id) {
		$rafID = esc_attr(get_post_meta( $order_id, '_raf_id', true));
		$order = new WC_Order( $order_id );
		$order_user_id = $order->get_user_id();
		$order_total = $order->get_total();
		if(method_exists($order, "get_billing_email")) { // support for older version of woo
			$order_email = $order->get_billing_email();
		} else {
			$order_email = $order->billing_email;
		}
		$minimum_amount = get_option( 'gens_raf_min_ref_order' );

		$gens_users = get_users( array(
			"meta_key" => "gens_referral_id",
			"meta_value" => $rafID,
			"number" => 1, 
			"fields" => "ID"
		) );
		$user_id = $gens_users[0];

		// If rafID returns empty, coupon wont be generated
		$rafID = apply_filters('gens_rafid_to_send_coupon', $rafID, $user_id);

		if ( $gens_users && !empty($rafID) && ($user_id != $order_user_id) ) {

			if($minimum_amount && $minimum_amount > $order_total) {
				return $order_id; //exit, dont generate
			}

			// Generate Coupon and returns it
			$coupon_code = $this->generate_coupons( $user_id  ); 
			// Send coupon to buyer as well?
			$buyer = get_option( 'gens_raf_friend_enable' );
			if($buyer === "yes") {
				$friend_coupon_code = $this->generate_buyer_coupon( $order_email  );
				$this->gens_send_buyer_email( $order_email, $friend_coupon_code );
			}
			// Increase number of referrals
			$num_friends_refered = $this->get_number_of_referrals($user_id);
			update_user_meta( $user_id, 'gens_num_friends', (int)$num_friends_refered + 1 );
			// Send via Email
			$this->gens_send_email( $user_id, $coupon_code );
		}
		return $order_id;
	}

	/**
	 * Send Email to user
	 *
	 * @since    1.0.0
	 */
	public function gens_send_email($user_id,$coupon_code) {

		if ( !$user_id || !$coupon_code) {
			return false;
		}

		global $woocommerce;
		$mailer = $woocommerce->mailer();

		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;
		$user_message = get_option( 'gens_raf_email_message' );
		$subject = get_option( 'gens_raf_email_subject' );
		ob_start();
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $subject ) );
		echo str_replace( '{{code}}', $coupon_code, $user_message );
		wc_get_template( 'emails/email-footer.php' );
		$message = ob_get_clean();
		// Debug wp_die($user_email);
		$mailer->send( $user_email, $subject, $message);
	}

	/**
	 * Send Email to buyer
	 *
	 * @since    1.0.0
	 */
	public function gens_send_buyer_email($order_email,$coupon_code) {

		if ( !$order_email || !$coupon_code) {
			return false;
		}

		global $woocommerce;
		$mailer = $woocommerce->mailer();
		
		$user_message = get_option( 'gens_raf_buyer_email_message' );
		$subject = get_option( 'gens_raf_buyer_email_subject' );
		ob_start();
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $subject ) );
		echo str_replace( '{{code}}', $coupon_code, $user_message );
		wc_get_template( 'emails/email-footer.php' );
		$message = ob_get_clean();
		// Debug wp_die($user_email);
		$mailer->send( $order_email, $subject, $message);
	}


	/**
	 * Generate a coupon for userID
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function generate_coupons( $user_id ) {
		$user_info = get_userdata($user_id);
		$user_email = $user_info->user_email;
		$coupon_code = "RAF-".substr( md5( time() ), 22); // Code
		$amount = get_option( 'gens_raf_coupon_amount' );
		$duration = get_option( 'gens_raf_coupon_duration' );
		$individual = get_option( 'gens_raf_individual_use' );
		$discount_type = get_option( 'gens_raf_coupon_type' );
		$minimum_amount = get_option( 'gens_raf_min_order' );
		$product_ids = get_option( 'gens_raf_product_ids' );
		$exclude_product_categories = get_option( 'gens_raf_exclude_product_categories' );
		$exclude_product_categories = array_map('intval', explode(',', $exclude_product_categories));
		$product_categories = get_option( 'gens_raf_product_categories' );
		$product_categories = array_map('intval', explode(',', $product_categories));

		$coupon = array(
			'post_title' => $coupon_code,
			'post_excerpt' => 'Referral coupon for: '.$user_email,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'		=> 'shop_coupon'
		);
							
		$new_coupon_id = wp_insert_post( $coupon );

		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual );
		update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_product_categories );
		update_post_meta( $new_coupon_id, 'product_categories', $product_categories );
		update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
		update_post_meta( $new_coupon_id, 'customer_email', strtolower($user_email));
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' ); // Only one coupon
		if($duration) {
			update_post_meta( $new_coupon_id, 'expiry_date', date('Y-m-d', strtotime('+'.$duration.' days')));			
		}
		update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

		do_action( 'gens_generate_user_coupon', $new_coupon_id);

		if($new_coupon_id) {
			return $coupon_code;			
		} else {
			return "Error creating coupon";
		}

	}

	/**
	 * Generate a coupon for buyer if checked
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function generate_buyer_coupon( $order_email ) {

		$coupon_code = "RAF-".substr( md5( time() ), 22); // Code
		$amount = get_option( 'gens_raf_friend_coupon_amount' );
		$duration = get_option( 'gens_raf_friend_coupon_duration' );
		$individual = get_option( 'gens_raf_friend_individual_use' );
		$discount_type = get_option( 'gens_raf_friend_coupon_type' );
		$minimum_amount = get_option( 'gens_raf_friend_min_order' );
		$product_ids = get_option( 'gens_raf_friend_product_ids' );
		$exclude_product_categories = get_option( 'gens_raf_friend_exclude_product_categories' );
		$exclude_product_categories = array_map('intval', explode(',', $exclude_product_categories));
		$product_categories = get_option( 'gens_raf_friend_product_categories' );
		$product_categories = array_map('intval', explode(',', $product_categories));

		$coupon = array(
			'post_title' => $coupon_code,
			'post_excerpt' => 'Referral coupon for: '.$order_email,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'		=> 'shop_coupon'
		);
							
		$new_coupon_id = wp_insert_post( $coupon );

		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual );
		update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_product_categories );
		update_post_meta( $new_coupon_id, 'product_categories', $product_categories );
		update_post_meta( $new_coupon_id, 'product_ids', $product_ids );
		update_post_meta( $new_coupon_id, 'customer_email', strtolower($order_email) );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' ); // Only one coupon
		if($duration) {
			update_post_meta( $new_coupon_id, 'expiry_date', date('Y-m-d', strtotime('+'.$duration.' days')));			
		}
		update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );

		do_action( 'gens_generate_buyer_coupon', $new_coupon_id);

		if($new_coupon_id) {
			return $coupon_code;			
		} else {
			return "Error creating coupon";
		}

	}

	/**
	 * Remove Cookie after checkout if Setting is set
	 * woocommerce_thankyou hook
	 *
	 * @since    1.0.0
	 */
	public function remove_cookie_after( $order_id ) {
		$remove = get_option( 'gens_raf_cookie_remove' );
		if (isset($_COOKIE['gens_raf']) && $remove == "yes") {
		    unset($_COOKIE['gens_raf']);
		    setcookie('gens_raf', '', time() - 3600, '/'); // empty value and old timestamp
		}
	}

	/**
	 * Returning number of orders customer has.
	 *
	 * @since    1.2.0
	 */
	public function has_enough_orders() {

		$user_id = get_current_user_id();
		if($user_id == 0) {
			return 0;
		}
		$customer_orders = get_posts( array(
		    'numberposts' => -1,
		    'meta_query' => array(
				array(
					'key'    => '_customer_user',
		    		'value'  => $user_id,
				),
			),
		    'post_type'   => wc_get_order_types(),
		    'post_status' => 'wc-completed',
		) );

		return count($customer_orders);
	}

	public function show_admin_raf_notes($order) { 

		if(method_exists($order, "get_id")) {
			$referralID = esc_attr(get_post_meta( $order->get_id(), '_raf_id', true ));				
		} else {
			$referralID = esc_attr(get_post_meta( $order->id, '_raf_id', true ));
		}
		

		if (!empty($referralID)) {
		
			$args = array('meta_key' => "gens_referral_id", 'meta_value' => $referralID );
			$user = get_users($args);
			?>
		    <div class="form-field form-field-wide">
		        <h4><?php _e( 'Referral details:', 'gens-raf' ); ?></h4>
		        <p><strong><?php _e( 'Referred by user:','gens-raf' ); ?></strong> <a href="<?php echo get_edit_user_link($user[0]->id); ?>"><?php echo $user[0]->user_email; ?></a></p>
		    </div>
    <?php
    	}
	}

	/**
	 * Woocommerce tools button to add missing referrals.
	 *
	 * @since    1.1.0
	 */
	public function gens_tools($old) {
		$new = array(
			'gens_add_missing_referrals' => array(
				'name'    => __( 'Refer a friend - Create missing referral links.', 'gens-raf' ),
				'button'  => __( 'Create referrals', 'gens-raf' ),
				'desc'    => __( 'This tool will add missing referrals to your site prior to users clicking on my account page. Useful if you want to inform users about their referral link, before it was autogenerated by them visiting page with referral link. ', 'gens-raf' ),
				'callback' => array($this,'gens_add_missing_referrals')
			),
			'gens_reset_referral_data' => array(
				'name'    => __( 'Refer a friend - Reset referral data.', 'gens-raf' ),
				'button'  => __( 'Reset referrals', 'gens-raf' ),
				'desc'    => __( 'This tool will remove all referral data from orders and will reset number of referrals for each user. Use with caution! Coupons will be kept by users, but if some referrals are pending, they wont award coupons after this. Backup your store before clicking!', 'gens-raf' ),
				'callback' => array($this,'gens_reset_referral_data')
			),
		);
		$tools = array_merge( $old, $new );
		return $tools;
	}
	
	/**
	 * Callback function for Woocommerce tools button to reset referral data. 
	 * This will remove all metas on orders.
	 *
	 * @since    1.1.2
	 */
	function gens_reset_referral_data() {

		$return = delete_post_meta_by_key('_raf_id');
		$users = get_users();
	    foreach ($users as $user) {
	        delete_user_meta($user->ID, 'gens_num_friends');
	    }
		if($return === true) {
			echo '<div class="updated inline"><p>Removed all referral data for orders.</p></div>';
		} else {
			echo '<div class="updated inline"><p>Nothing to remove.</p></div>';
		}

	}

	/**
	 * Callback function for Woocommerce tools button to add missing referrals.
	 *
	 * @since    1.1.0
	 */
	function gens_add_missing_referrals() {
		$gens_user_query = new WP_User_Query(array( 'meta_key' => 'gens_referral_id', 'meta_compare' => 'NOT EXISTS' ));
		$users = $gens_user_query->get_results();
		foreach ($users as $user) {
			$this->get_referral_id($user->ID);
		}
		echo '<div class="updated inline"><p>' . count($users) . __( ' missing referral codes have been created', 'gens-raf' ) . '</p></div>';
	}

	/**
	 * Create referral code on new user registration, in case someone needs meta fields for mailchimp and such.
	 *
	 * @since    1.1.0
	 */
	function gens_new_user_registration_hook( $user_id ) {
		$this->get_referral_id($user_id);
	}


	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->gens_raf.'_cookieJS', plugin_dir_url( __FILE__ ) . 'js/cookie.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->gens_raf, plugin_dir_url( __FILE__ ) . 'js/gens-raf-public.js', array( 'jquery' ), $this->version, false );
		$time = get_option( 'gens_raf_cookie_time' );
		$cookies = array( 'timee' => $time, 'ajax_url' => admin_url( 'admin-ajax.php' ) );
		wp_localize_script( $this->gens_raf, 'gens_raf', $cookies );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->gens_raf, plugin_dir_url( __FILE__ ) . 'css/gens-raf.css', array(), $this->version, 'all' );

	}

	/**
	 * Main function for displaying front end share buttons for Product Tab, Shortcode and My Account Page.
	 *
	 * @since    1.0.0
	 */
	public function share_front_end($type) {
			global $wp;
			$title = get_option( 'gens_raf_twitter_title' );
			$twitter_via = get_option( 'gens_raf_twitter_via' );
			$referral_id = $this->get_referral_id( get_current_user_id() );

			if($type === "tab") {
				$refLink = esc_url(home_url(add_query_arg(array('raf' => $referral_id),$wp->request)));
			} else if($type === "my-account") {
				$link = get_home_url();
				$my_account_options_url = get_option( 'gens_raf_my_account_url' );
				if($my_account_options_url != "") {
					$link = $my_account_options_url;
				}
				$refLink = esc_url(add_query_arg( 'raf', $referral_id, $link ));
			} else {
				$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() ));
			}
			$refLink = apply_filters('gens_raf_link', $refLink);
			do_action('gens_before_referral_url');
		?>
			<div class="gens-refer-a-friend">
				<div id="gens-raf-message" class="woocommerce-message"><?php _e( 'Your Referral URL:','gens-raf'); ?> <strong><?php echo $refLink; ?></strong></div>
			<?php 
				do_action('gens_after_referral_url');
			?>
				<div class="gens-referral_share">
					<a href="<?php echo $refLink; ?>" class="gens-referral_share__fb"><i class="gens_raf_icn-facebook"></i> <?php _e("Share via Facebook","gens-raf"); ?></a>
					<a href="<?php echo $refLink; ?>" class="gens-referral_share__tw" data-via="<?php echo $twitter_via; ?>" data-title="<?php echo $title; ?>" ><i class="gens_raf_icn-twitter"></i> <?php _e("Share via Twitter","gens-raf"); ?></a>
					<a href="" class="gens-referral_share__gp"><i class="gens_raf_icn-gplus"></i> <?php _e("Share via Google+","gens-raf"); ?></a>
					<a href="<?php echo $refLink; ?>" class="gens-referral_share__wa" data-title="<?php echo $title; ?>"><i class="gens_raf_icn-whatsapp"></i> <?php _e("Share via Whatsapp","gens-raf"); ?></a>
				</div>
				<div class="gens-referral_share__email">
					<span class="gens-referral_share__email__title"><?php _e( 'or share via email','gens-raf'); ?></span>
					<form id="gens-referral_share__email" action="" type="post">
						<div class="gens-referral_share__email__inputs">
							<input type="email" placeholder="<?php _e( 'Enter email','gens-raf'); ?>">
							<input type="email" class="gens-referral_share__email__clone" placeholder="<?php _e( 'Enter email','gens-raf'); ?>">
						</div>
						<input type="submit" value="<?php _e( 'Send Emails','gens-raf'); ?>">
					</form>
				</div>
			</div>
		<?php
	}

	/**
	 * When share via email is clicked.
	 *
	 * @since    1.0.0
	 */
	public function gens_share_email_function() {
		global $woocommerce;
		$mailer = $woocommerce->mailer();

		$link = get_home_url();
		$referral_id = $this->get_referral_id( get_current_user_id() );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, $link ));
		$user_message = str_replace( '{{code}}', $refLink, get_option( 'gens_raf_email_body' ) );
		$subject = get_option( 'gens_raf_email_subject_share' );
		$user_email = $_POST['emails'];
		ob_start();
		wc_get_template( 'emails/email-header.php', array( 'email_heading' => $subject ) );
		echo $user_message;
		wc_get_template( 'emails/email-footer.php' );
		$message = ob_get_clean();
		$mailer->send( $user_email, $subject, $message);
	}

	/**
	 * Auto apply coupons at the cart page for referred person, if chosen.
	 *
	 * @since    1.1.0
	 */
	public function apply_matched_coupons() {
		global $woocommerce;

		$not_active         = get_option( 'gens_raf_disable' );
		$guest_coupon_stats = get_option( 'gens_raf_guest_enable' );
		$guest_coupon_code  = get_option( 'gens_raf_guest_coupon_code' );
		$guest_coupon_msg   = get_option( 'gens_raf_guest_coupon_msg' );
		$allow_existing   = get_option( 'gens_raf_allow_existing' );
		// disallow users buying for them self !!!
		$user_id = 0;
		if(isset($_COOKIE["gens_raf"])) {
			$gens_users = get_users( array(
				"meta_key" => "gens_referral_id",
				"meta_value" => $_COOKIE["gens_raf"],
				"number" => 1,
				"fields" => "ID"
			) );
			$user_id = (int)$gens_users[0];
		}

		// if some coupon is already applied, dont do anything.
		if ( get_current_user_id() !== $user_id && empty($woocommerce->cart->applied_coupons) && !empty($guest_coupon_code) && isset($_COOKIE["gens_raf"]) && $not_active != "yes" && $woocommerce->cart->cart_contents_count >= 1 && $guest_coupon_stats == "yes" && ($this->has_enough_orders() < 1 || $allow_existing == "yes") ) {
			$woocommerce->cart->add_discount( $guest_coupon_code );
			wc_add_notice($guest_coupon_msg);
			wc_print_notices();
	    }
	}

	public function hide_coupon_code($text,$coupon) {
		$guest_coupon_code  = get_option( 'gens_raf_guest_coupon_code' );
		if($coupon->get_code() == strtolower($guest_coupon_code)) {
		  _e("Coupon Applied!","gens-raf");
		} else {
		  echo $text;
		}

	}

	/**
	 * Remove coupon if user wants to abuse it by adding it as a guest then logging in at the checkout.
	 *
	 * @since    1.1.0
	 */
	public function checkout_form_check() {
		global $woocommerce;
		$user_id = 0;
		$guest_coupon_code  = get_option( 'gens_raf_guest_coupon_code' );
		$allow_existing   = get_option( 'gens_raf_allow_existing' );
		if(isset($_COOKIE["gens_raf"])) {
			$gens_users = get_users( array(
				"meta_key" => "gens_referral_id",
				"meta_value" => $_COOKIE["gens_raf"],
				"number" => 1,
				"fields" => "ID"
			) );
			$user_id = (int)$gens_users[0];
		}
		if ( (get_current_user_id() === $user_id || ($this->has_enough_orders() > 0 && $allow_existing != "yes")) && $woocommerce->cart->has_discount($guest_coupon_code) ) {
			$woocommerce->cart->remove_coupon( $guest_coupon_code );
			wc_print_notices();
		}
	}

}
