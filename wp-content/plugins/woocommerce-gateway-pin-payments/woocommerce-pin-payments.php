<?php
/*
Plugin Name: WooCommerce Pin Payments Gateway
Plugin URI: http://woothemes.com/woocommerce
Description: Use Pin Payments (www.pin.net.au) as a credit card processor for WooCommerce.
Version: 1.7.4
Author: Tyson Armstrong
Author URI: http://tysonarmstrong.com/

Copyright: © 2013 Tyson Armstrong

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '449e42d8cc1acab9c87be66b259d0b1e', '201974' );

add_action('plugins_loaded', 'woocommerce_pin_payments_init', 0);

function woocommerce_pin_payments_init() {

	if (!class_exists('WC_Payment_Gateway_CC'))  return;

	class WC_Gateway_Pin_Payments extends WC_Payment_Gateway_CC {

		private static $log;

		public function __construct() {
			global $woocommerce;

		    $this->id 					= 'pin_payments';
		    $this->method_title 		= __('Pin Payments', 'woo_pin_payments');
			$this->method_description 	= __('Use Pin Payments as a credit card processor for WooCommerce.', 'woo_pin_payments');
			$this->icon 				= plugins_url() . "/" . plugin_basename( dirname(__FILE__)) . '/images/pin.png';
			$this->supports 			= array('subscriptions', 'default_credit_card_form', 'products', 'subscription_cancellation', 'subscription_reactivation', 'subscription_suspension', 'subscription_date_changes','subscription_amount_changes','subscription_payment_method_change', 'subscription_payment_method_change_customer','subscription_payment_method_change_admin', 'multiple_subscriptions', 'refunds' );
			$this->view_transaction_url = 'https://dashboard.pin.net.au/charges/%s';

		    // Load the form fields.
		    $this->init_form_fields();

		    // Load the settings.
		    $this->init_settings();

		    // Define user set variables
		    $this->title = $this->settings['title'];
		    $this->description = __('Pay using credit card','woo_pin_payments');
		    $this->pin_url = ($this->settings['testmode'] == 'yes') ? 'https://test-api.pin.net.au/1/' : 'https://api.pin.net.au/1/';

			// Save admin options
			add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) ); // 1.6.6
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) ); // 2.0.0

		    add_action('admin_notices', array(&$this, 'ssl_check'));
			add_action('woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );

			if ( ! $this->is_valid_for_use() ) {
				$this->enabled = 'no';
			} elseif ($this->enabled == 'yes') {
				add_action('wp_enqueue_scripts', array(&$this,'load_checkout_script'));

				// Add Card Name field to credit card form
				add_filter('woocommerce_credit_card_form_fields', array($this,'add_card_name_field'));
				add_action( 'wp_enqueue_scripts', array($this,'add_card_name_field_styles'));
			}

		}

		/**
		 * Check if this gateway is enabled and available in the user's country.
		 * @return bool
		 */
		public function is_valid_for_use() {
			return in_array( get_woocommerce_currency(), apply_filters( 'woo_pin_payments', array( 'AUD', 'USD', 'NZD', 'SGD', 'EUR', 'GBP', 'CAD', 'HKD', 'JPY', 'MYR', 'THB', 'PHP', 'ZAR', 'IDR' ) ) );
		}


		/**
		 * Load checkout script to determine which credit card type is being entered
		 */
		function load_checkout_script() {
			if (is_checkout()) {
				$script_url = ($this->settings['testmode'] == 'yes') ? 'https://test-api.pin.net.au/pin.js' : 'https://api.pin.net.au/pin.js';
				wp_enqueue_script('woo_pin_payments_script',$script_url,array('jquery','woocommerce','wc-checkout'),false,true);


				$publishable_key = ($this->settings['testmode'] == 'yes') ? $this->settings['publishable-key-test'] : $this->settings['publishable-key-live'];
				wp_localize_script('woo_pin_payments_script', 'WooPinPayments', array( 'publishable_key' => $publishable_key,'plugin_url'=>plugins_url()."/" . plugin_basename( dirname(__FILE__))));

				wp_enqueue_script('woo_pin_payments_script_local',plugins_url() . "/" . plugin_basename( dirname(__FILE__)) . '/woocommerce-pin-payments.js',array('jquery','woocommerce','wc-checkout','woo_pin_payments_script'),false,true);
			}
		}


        /**
         * Check if SSL is enabled and notify the user
         * */
        function ssl_check() {
            if (get_option('woocommerce_force_ssl_checkout') == 'no' && $this->enabled == 'yes') :
                echo '<div class="error"><p>' . sprintf(__('Pin Payments is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'woo_pin_payments'), admin_url('admin.php?page=woocommerce')) . '</p></div>';
            endif;
        }


		/**
	     * Initialise Gateway Settings Form Fields
		 *
		 * @since 1.0.0
	     */
		function init_form_fields() {
			$this->form_fields = array(
			    'enabled' => array(
			        'title' => __( 'Enable/Disable', 'woo_pin_payments' ),
			        'type' => 'checkbox',
			        'label' => __( 'Enable this payment method', 'woo_pin_payments' ),
			        'default' => 'yes'
			    ),
			    'title' => array(
			        'title' => __( 'Title', 'woo_pin_payments' ),
			        'type' => 'text',
			        'description' => __( 'This controls the title which the user sees during checkout.', 'woo_pin_payments' ),
			        'default' => __( 'Pin Payments', 'woo_pin_payments' )
			    ),
				'testmode' => array(
					'title' => __( 'Test mode', 'woo_pin_payments' ),
					'label' => __( 'Enable Test mode', 'woo_pin_payments' ),
					'type' => 'checkbox',
					'description' => __( 'Process transactions in Test mode. No transactions will actually take place.', 'woo_pin_payments' ),
					'default' => 'yes'
				),
				'disable-stored-cards' => array(
					'title' => __( 'Disable Saved Cards', 'woo_pin_payments' ),
					'label' => __( 'Disable Saved Cards', 'woo_pin_payments' ),
					'type' => 'checkbox',
					'description' => __( 'Saved Cards allows logged in customers to use previously entered credit cards (details stored at Pin Payments).', 'woo_pin_payments' ),
					'default' => 'no'
				),
				'publishable-key-live' => array(
					'title' => __( 'Publishable API Key (Live)', 'woo_pin_payments' ),
					'type' => 'text',
					'description' => __( 'Your <strong>live</strong> environment publishable API key. This can be obtained at <a href="https://pin.net.au">Pin Payments</a>.', 'woo_pin_payments' ),
					'default' => ''
				),
				'secret-key-live' => array(
					'title' => __( 'Secret API Key (Live)', 'woo_pin_payments' ),
					'type' => 'password',
					'description' => __( 'Your <strong>live</strong> environment secret API key. This can be obtained at <a href="https://pin.net.au">Pin Payments</a>.', 'woo_pin_payments' ),
					'default' => ''
				),
				'publishable-key-test' => array(
					'title' => __( 'Publishable API Key (Test)', 'woo_pin_payments' ),
					'type' => 'text',
					'description' => __( 'Your <strong>test</strong> environment publishable API key. This can be obtained at <a href="https://pin.net.au">Pin Payments</a>.', 'woo_pin_payments' ),
					'default' => ''
				),
				'secret-key-test' => array(
					'title' => __( 'Secret API Key (Test)', 'woo_pin_payments' ),
					'type' => 'password',
					'description' => __( 'Your <strong>test</strong> environment secret API key. This can be obtained at <a href="https://pin.net.au">Pin Payments</a>.', 'woo_pin_payments' ),
					'default' => ''
				)
			);
		} // End init_form_fields()


		/**
		 * Admin Panel Options
		 *
		 * @since 1.0.0
		 */
		public function admin_options() { 
			if ( $this->is_valid_for_use() ) { ?>
	    	<h3><?php _e('Pin Payments gateway', 'woo_pin_payments'); ?></h3>
	    	<table class="form-table">
	    	<?php
	    		// Generate the HTML For the settings form.
	    		$this->generate_settings_html();
	    	?>
			</table><!--/.form-table-->
	    	<?php
	    	} else {
	    		echo '<div class="inline error"><p><strong>'.__( 'Gateway Disabled', 'woo_pin_payments' ).'</strong>: '.__( 'Pin Payments does not support your store currency. See <a href="https://pin.net.au/docs/currency-support" target="_blank">Currency Support</a> for a full list.', 'woo_pin_payments' ).'</p></div>';
	    	}
	    } // End admin_options()


		/**
         * Payment Form
         */
        function payment_fields() {

			global $woocommerce;

			$showbillingfields = false;

			if (is_wc_endpoint_url( 'order-pay' )) {
				 global $wp;
			    $order_id = $wp->query_vars['order-pay'];
			    $order = new WC_Order( $order_id );
				$showbillingfields = true;
				if (version_compare( WC_VERSION, '2.7', '<' )) {
					//$order = new WC_Order((int) $_GET['order_id']);
					$addressline1 = $order->billing_address_1;
					$addressline2 = $order->billing_address_2;
					$city = $order->billing_city;
					$state = $order->billing_state;
					$postcode = $order->billing_postcode;
					$country = $order->billing_country;
				} else {
					//$order = new WC_Order((int) $_GET['order_id']);
					$addressline1 = $order->get_billing_address_1();
					$addressline2 = $order->get_billing_address_2();
					$city = $order->get_billing_city();
					$state = $order->get_billing_state();
					$postcode = $order->get_billing_postcode();
					$country = $order->get_billing_country();	
				}
				
			}

			// Payment form
			if ($this->settings['testmode']=='yes') echo '<p>'._e('TEST MODE ENABLED', 'woo_pin_payments').'</p>';
			if ($this->description) { echo '<p>'.$this->description.'</p>'; }

			$timestamp = gmdate('YmdHis');



			 /* These fields are required by the JS to retreive the Pin Payments card token.
			They don't need to be submitted to the browser, so don't need name attributes. */
			if ($showbillingfields) { ?>
				<input id='billing_address_1' type="hidden" value="<?php echo $addressline1; ?>" />
	    		<input id='billing_address_2' type="hidden" value="<?php echo $addressline2; ?>" />
				<input id='billing_city' type="hidden" value="<?php echo $city; ?>" />
	   			<input id='billing_state' type="hidden" value="<?php echo $state; ?>" />
	    		<input id='billing_postcode' type="hidden" value="<?php echo $postcode; ?>" />
	   			<input id='billing_country' type="hidden" value="<?php echo $country; ?>" />
   			<?php } ?>
   			<div class='errors' style='display:none'>
    			<h3></h3>
    			<ul></ul>
  			</div>

			<fieldset>
				<?php if ( $this->settings['disable-stored-cards'] != "yes" && is_user_logged_in() && ( $credit_cards = get_user_meta( get_current_user_id(), '_pin_customer_token', false ) ) ) : ?>
					<p class="form-row form-row-wide">

						<a class="button" style="float:right;" href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>#saved-cards"><?php _e( 'Manage cards', 'woo_pin_payments' ); ?></a>

						<?php foreach ( $credit_cards as $i => $credit_card ) : ?>
							<input type="radio" id="pin_card_<?php echo $i; ?>" name="pin_customer_token" style="width:auto;" value="<?php echo $i; ?>" />
							<label style="display:inline;" for="pin_card_<?php echo $i; ?>"><?php _e( 'Card ending with', 'woo_pin_payments' ); ?> <?php echo substr($credit_card['display_number'],-4,4); ?> (<?php echo get_full_card_scheme_name($credit_card['scheme']) ?>)</label><br />
						<?php endforeach; ?>

						<input type="radio" id="new" name="pin_customer_token" style="width:auto;" <?php checked( 1, 1 ) ?> value="new" /> <label style="display:inline;" for="new"><?php _e( 'Use a new credit card', 'woo_pin_payments' ); ?></label>

					</p>
					<div class="clear"></div>
				<?php $has_cards = true;
				endif; ?>
				<div class="pin_new_card <?php if (isset($has_cards) && $has_cards == true) echo 'has_cards'; ?>">
					<?php $this->form(); ?>
				</div>
				<div class="clear"></div>
			</fieldset>

		<?php
        }


        /**
         * Add a Card Name field to the default WooCommerce checkout form (and make it first)
         */
        function add_card_name_field($default_fields) {
        	$fields = array_merge(array('card-name-field' => '<p class="form-row form-row-wide">
				<label for="' . esc_attr( $this->id ) . '-card-name">' . __( 'Name on card', 'woo_pin_payments' ) . ' <span class="required">*</span></label>
				<input id="' . esc_attr( $this->id ) . '-card-name" class="input-text wc-credit-card-form-card-name" type="text" autocomplete="off" placeholder="" name="' . $this->id . '-card-name' . '" />
			</p>'),$default_fields);
        	return $fields;
        }

        function add_card_name_field_styles() {
        	if (is_checkout()) {
				wp_register_style( 'woocommerce-pin-payments', plugin_dir_url(__FILE__) . 'woocommerce-pin-payments.css' );
				wp_enqueue_style( 'woocommerce-pin-payments' );
			}
        }

		/**
		 * Process the payment and return the result
		 *
		 * @since 1.0.0
		 */
		function process_payment( $order_id ) {
			global $woocommerce;
			$order = new WC_Order( $order_id );

			$card_token = isset( $_POST['card_token'] ) ? woocommerce_clean( $_POST['card_token'] ) : '';
			$ip_address = isset( $_POST['ip_address'] ) ? woocommerce_clean( $_POST['ip_address'] ) : '';
			$secret_key = ($this->settings['testmode'] == 'yes') ? $this->settings['secret-key-test'] : $this->settings['secret-key-live'];

			// Are we paying by customer token?
			if ( isset( $_POST['pin_customer_token'] ) && $_POST['pin_customer_token'] !== 'new' && is_user_logged_in() ) {
				$customer_tokens = get_user_meta( get_current_user_id(), '_pin_customer_token', false );

				if ( isset( $customer_tokens[ $_POST['pin_customer_token'] ]['customer_token'] ) )
					$customer_token = $customer_tokens[ $_POST['pin_customer_token'] ]['customer_token'];
				else
					wc_add_notice(__( 'Invalid card.', 'woo_pin_payments' ),'error');
					$this->log(sprintf(__('Order %s: Pin payment error - invalid saved card token.', 'woo_pin_payments'),$order->get_order_number()));
			} elseif (empty($card_token)) {
				wc_add_notice(__('Please make sure your card details have been entered correctly and that your browser supports JavaScript.', 'woo_pin_payments' ),'error');
				$this->log(sprintf(__('Order %s: Pin payment error - card token was empty. Unlikely the Pin payments JS executed correctly.', 'woo_pin_payments'),$order->get_order_number()));
			}

			// Save token if logged in
			if ( is_user_logged_in() && !isset($customer_token) && isset($card_token) ) {
				// We need to turn the card token into a Customer token for later use.
				$customer_token = $this->create_pin_customer( $order, $card_token );
			}

			$order_description = sprintf( __( '%s - Order #%s', 'woocommerce' ), esc_html( get_bloginfo( 'name', 'display' ) ), $order->get_order_number() );

			if (version_compare( WC_VERSION, '2.7', '<' )) {
				$billing_email = $order->billing_email;
				$amount = $order->order_total;
			} else {
				$billing_email = $order->get_billing_email();
				$amount = $order->get_total();
			}
			if (in_array(get_woocommerce_currency(),array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF'))) {
				$damount = $amount;
			} else {
				$damount = number_format( (float)$amount * 100, 0, '.', '' );
			}
		    $post_data = array(
		    	'email'=>$billing_email,
		    	'description'=>$order_description,
		    	'amount'=>$damount,
		    	'currency'=>get_woocommerce_currency(),
		    	'ip_address'=>$ip_address
		    	);
		    if (isset($customer_token)) {
		    	$post_data['customer_token'] = $customer_token;
		    } else {
		    	$post_data['card_token'] = $card_token;
		    }

		    $result = $this->call_pin($post_data,'charges', $order);

		    if (isset($result->response->success) && $result->response->success == 1) {
		    	// Success
	    		$order->add_order_note(sprintf(__('Pin Payments payment on card %s approved at %s. Reference ID: %s','woo_pin_payments'),$result->response->card->display_number,$result->response->created_at,$result->response->token));
	    		$order->payment_complete($result->response->token);
	    		$woocommerce->cart->empty_cart();

	    		if (is_woocommerce_pre_2_1()) {
	    			$redirect = add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(get_option('woocommerce_thanks_page_id'))));
	    		} else { // WC 2.1+
	    			$redirect = $this->get_return_url($order);
	    		}
                return array(
                    'result' => 'success',
                    'redirect' => $redirect
                );

	        } elseif (isset($result->response->success) && $result->response->success != 1) {
	        	// Failed
            	$order->add_order_note(sprintf(__('Pin Payments payment failed: %s. (ref ID: %s)', 'woo_pin_payments'), $result->response->error_message, $result->response->token));
            	wc_add_notice(sprintf(__('Payment error: %s', 'woo_pin_payments'), $result->response->error_message),'error');
                $this->log(sprintf(__('Order %s: Pin payment failed - %s. Reference ID: %s', 'woo_pin_payments'),$order->get_order_number(),$result->response->error_message, $result->response->token));

            } else {
            	// Errored
            	$order->add_order_note(sprintf(__('Pin Payments error: %s', 'woo_pin_payments'), $result->error_description));
                wc_add_notice(sprintf(__('Payment error: %s', 'woo_pin_payments'), $result->error_description),'error');
                $this->log(sprintf(__('Order %s: Pin payment error - %s.', 'woo_pin_payments'),$order->get_order_number(),$result->error_description));
            }
	        return array(
	            'result' => 'failure',
	            'redirect' => $order->get_checkout_payment_url(true)
      		);
		}

		function create_pin_customer($order,$card_token) {
			global $woocommerce;

			if (is_user_logged_in() && $card_token) {

				if (version_compare( WC_VERSION, '2.7', '<' )) {
					$billing_email = $order->billing_email;
				} else {
					$billing_email = $order->get_billing_email();
				}

				$post_data = array(
					'email'=>$billing_email,
					'card_token'=>$card_token);

				$result = $this->call_pin($post_data,'customers', $order);

				if (isset($result->response->token) && !empty($result->response->token)) {
					$customer_token = array(
						'customer_token'=>$result->response->token,
						'display_number'=>$result->response->card->display_number,
						'scheme'=>$result->response->card->scheme,
						'email'=>$result->response->email
					);
					if ($this->settings['disable-stored-cards'] != "yes") {
						add_user_meta(get_current_user_id(),'_pin_customer_token',$customer_token);
					}
					return $result->response->token;
				}
			}
		}

		function call_pin($post_data,$action='charges', $order) {
			global $woocommerce;

			$secret_key = ($this->settings['testmode'] == 'yes') ? $this->settings['secret-key-test'] : $this->settings['secret-key-live'];

			$response = wp_remote_post( $this->pin_url.$action , array(
   				'method'		=> 'POST',
   				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $secret_key . ':' )
				),
    			'body' 			=> $post_data,
    			'timeout' 		=> 70,
    			'user-agent' 	=> 'WooCommerce ' . $woocommerce->version
			));
			if (is_wp_error($response)) {
				$result = $response;
				$this->log(sprintf(__('Order %s: Pin payment error - %s.', 'woo_pin_payments'),$order->get_order_number(),$response->get_error_message()));
			} else {
				$result = json_decode($response['body']);
			}
			return $result;
		}

		public function process_refund($order_id, $amount = null, $reason = '') {

			$order = new WC_Order($order_id);

			$charge_token = get_post_meta( $order_id, '_transaction_id', true );
			if (!$charge_token) return new WP_Error('no_charge_token','Sorry, this order does not have a charge token saved. It may have been processed by an earlier version of the Pin Payments plugin and cannot process a refund.');
			if (in_array(get_woocommerce_currency(),array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF'))) {
				$damount = $amount;
			} else {
				$damount = number_format( (float)$amount * 100, 0, '.', '' );
			}
			$post_data = array('amount'=>$damount);
		    $result = $this->call_pin($post_data,'charges/'.$charge_token.'/refunds', $order);

		    

		    if (isset($result->response)) {
		    	// Refund was successful
		    	if (in_array(get_woocommerce_currency(),array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF'))) {
					$damount = $result->response->amount;
				} else {
					$damount = $result->response->amount/100;
				}
			    
		    	$order->add_order_note(sprintf(__('Pin Payments refund was processed for %s %s at %s. Reference ID: %s','woo_pin_payments'),$damount,get_woocommerce_currency(),$result->response->created_at,$result->response->token));
		    	return 1;
		    } elseif (isset($result->error)) {
		    	// Refund was not successful
		    	$errors = '';
		    	foreach ($result->messages as $i => $msg) {
		    		if ($i > 0) $errors .= ', ';
		    		$errors .= $msg->message;
		    	}
		    	$order->add_order_note(sprintf(__('Pin Payments refund was NOT processed due to: %s.','woo_pin_payments'),$errors));
		    	$this->log(sprintf(__('Order %s: Pin payment refund errored - %s.', 'woo_pin_payments'),$order->get_order_number(),$errors));
		    	return 0;
		    }

		    $order->add_order_note(sprintf(__('Pin Payments refund response: %s','woo_pin_payments'),print_r($result,true)));
		    $this->log(sprintf(__('Order %s: Pin payment refund response - %s.', 'woo_pin_payments'),$order->get_order_number(),print_r($result,true)));
		    return new WP_Error('invalid_refund_response','Sorry, we couldn\'t understand the refund response from Pin Payment. It has been saved as an order note.');

		}

		public static function log( $message ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}

			self::$log->add( 'woo_pin_payments', $message );

		}

	}

	function get_full_card_scheme_name($scheme) {
		switch ($scheme) {
			case 'visa':
				return 'VISA';
			case 'master':
				return 'Mastercard';
			case 'american_express':
				return 'American Express';
			default :
				return '';
		}
	}

	/**
	 * account_cc function.
	 *
	 * @access public
	 * @return void
	 */
	function woocommerce_pin_saved_cards() {
		$credit_cards = get_user_meta( get_current_user_id(), '_pin_customer_token', false );

		$gateway = new WC_Gateway_Pin_Payments();
		if ( ! $credit_cards || $gateway->settings['disable-stored-cards'] == "yes")
			return;

        if ( isset( $_POST['delete_card'] ) && wp_verify_nonce( $_POST['_wpnonce'], "pin_del_card" ) ) {
			$credit_card = $credit_cards[ (int) $_POST['delete_card'] ];
			delete_user_meta( get_current_user_id(), '_pin_customer_token', $credit_card );
		}

		$credit_cards = get_user_meta( get_current_user_id(), '_pin_customer_token', false );

		if ( ! $credit_cards )
			return;
		?>
			<h2 id="saved-cards" style="margin-top:40px;"><?php _e('Saved cards', 'woo_pin_payments' ); ?></h2>
			<table class="shop_table">
				<thead>
					<tr>
						<th><?php _e('Card ending in...','woo_pin_payments'); ?></th>
						<th><?php _e('Card type','woo_pin_payments'); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $credit_cards as $i => $credit_card ) : ?>
					<tr>
                        <td><?php esc_html_e(substr($credit_card['display_number'],-4,4)); ?></td>
                        <td><?php echo get_full_card_scheme_name(esc_html($credit_card['scheme'])); ?></td>
						<td>
                            <form action="#saved-cards" method="POST">
                                <?php wp_nonce_field ( 'pin_del_card' ); ?>
                                <input type="hidden" name="delete_card" value="<?php echo (int) $i; ?>">
                                <input type="submit" class="button" value="<?php _e( 'Delete card', 'woo_pin_payments' ); ?>">
                            </form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php
	}

	add_action( 'woocommerce_after_my_account', 'woocommerce_pin_saved_cards' );

	if (!function_exists('is_woocommerce_pre_2_1')) {
		function is_woocommerce_pre_2_1() {
	        if ( ! defined( 'WC_VERSION' ) ) {
	            $woocommerce_is_pre_2_1 = true;
	        } else {
	            $woocommerce_is_pre_2_1 = false;
	        }
	        return $woocommerce_is_pre_2_1;
	    }
	}


	if ( class_exists( 'WC_Subscriptions_Order' ) ) {
		include_once( 'woocommerce-pin-payments-subscriptions.php' );

		// Support for WooCommerce Subscriptions 1.n
		if ( ! function_exists( 'wcs_create_renewal_order' ) ) {
			include_once( 'woocommerce-pin-payments-subscriptions-deprecated.php' );
		}
	}

	/**
	 * Add the Pin Payments gateway to WooCommerce
	 *
	 * @since 1.0.0
	 **/
	function add_pin_payments_gateway( $methods ) {
		if ( class_exists( 'WC_Subscriptions_Order' ) ) {
			if ( class_exists( 'WC_Subscriptions_Order' ) && !function_exists( 'wcs_create_renewal_order' ) ) {
				$methods[] = 'WC_Gateway_Pin_Payments_Subscriptions_Deprecated';
			} else {
				$methods[] = 'WC_Gateway_Pin_Payments_Subscriptions';
			}
		} else {
			$methods[] = 'WC_Gateway_Pin_Payments';
		}
		return $methods;
	}
	add_filter('woocommerce_payment_gateways', 'add_pin_payments_gateway' );

}