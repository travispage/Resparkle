<?php

/**
 * Front End Part of the Plugin (My Account Page, soon Tabs as well)
 *
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/public
 */
class Gens_RAF_MyAccount extends Gens_RAF_Public {

	public function gens_myreferral_tab(){
	    add_rewrite_endpoint( 'myreferrals', EP_PAGES );
	}

	public function gens_account_menu_item( $items ) {
	    $items['myreferrals'] = __( 'Refer a Friend', 'gens-raf' );
	    return $items;
	}

	public function gens_account_referral_content() {
		$this->account_page_start();
		$this->share_front_end("my-account");
		$this->account_page_show_coupons();
		$this->account_page_show_referral_stats();
	}

	public function account_page_start() { 
		echo get_option('gens_raf_myaccount_text');
	}

	/**
	 * Account page - list unused referral coupons
	 *
	 * @since    1.0.0
	 */
	public function account_page_show_coupons() {
		$user_info = get_userdata(get_current_user_id());
		$user_email = $user_info->user_email;
		$date_format = get_option( 'date_format' );
		$args = array(
		    'posts_per_page'   => -1,
		    'post_type'        => 'shop_coupon',
		    'post_status'      => 'publish',
			'meta_query' => array (
			    array (
				  'key' => 'customer_email',
				  'value' => $user_email,
	              'compare' => 'LIKE'
			    )
			),
		);
		    
		$coupons = get_posts( $args );

		if($coupons) { ?>

			<h3><?php echo apply_filters( 'wpgens_raf_title', __( 'Unused Refer a Friend Coupons', 'gens-raf' ) ); ?></h3>
			<table class="shop_table shop_table_responsive shop_table_wpgens">
				<tr>
					<th><?php _e('Coupon code','gens-raf'); ?></th>
					<th><?php _e('Coupon discount','gens-raf'); ?></th>
					<th><?php _e('Expiry date','gens-raf'); ?></th>
				</tr>
		<?php
			foreach ( $coupons as $coupon ) {
				$discount = esc_attr(get_post_meta($coupon->ID, "coupon_amount" ,true));
				$discount_type = esc_attr(get_post_meta($coupon->ID, "discount_type" ,true));
				$usage_count = esc_attr(get_post_meta($coupon->ID, "usage_count" ,true));
				$expiry_date = esc_attr(get_post_meta($coupon->ID,"expiry_date",true));
				if($expiry_date == "") {
					$expiry_date = __('No expiry date','gens-raf');
				}
				if($discount_type == "percent_product" || $discount_type == "percent") {
					$discount = $discount."%";
				}
				
				if($usage_count == 0) { // If coupon isnt used yet.
					echo '<tr>';
					echo '<td>'.$coupon->post_title.'</td>';
					echo '<td>'.$discount.'</td>';
					echo '<td>'.$expiry_date.'</td>';
					echo '</tr>';
				} 

			}
			echo '</table>';
		}
	}

	/**
	 * Account page - List all referrals made by user referral code.
	 *
	 * @since    1.2.0
	 */
	public function account_page_show_referral_stats() {
		$referral_id = $this->get_referral_id( get_current_user_id() );
		$num_friends_refered = $this->get_number_of_referrals(get_current_user_id());

		$args = array(
		    'meta_query'  => array(
		    	array(
		    		'key' => '_raf_id',
		    		'value' => $referral_id,
		    		'compare' => '='
		    	)
		    ),
		    'post_type'   => wc_get_order_types(),
		    'post_status' => array_keys( wc_get_order_statuses() ),
		);
		$orders = get_posts( $args );

		$args_potential = array(
		    'meta_query'  => array(
		    	array(
		    		'key' => '_raf_id',
		    		'value' => $referral_id,
		    		'compare' => '='
		    	)
		    ),
		    'post_type'   => wc_get_order_types(),
		    'post_status' => array( 'wc-pending', 'wc-processing', 'wc-on-hold' ),
		);
		$potential_orders = get_posts( $args_potential );
		?>

			<h3><?php echo apply_filters( 'wpgens_raf_title', __( 'Track your invites', 'gens-raf' ) ); ?></h3>
			<div class="gens-referral_stats">
				<div><?php _e('Earned Coupons:','gens-raf'); ?> <span><?php echo $num_friends_refered; ?></span></div>
				<div><?php _e('Potential Coupons:','gens-raf'); ?> <span><?php echo count($potential_orders); ?></span></div>
			</div>
			<table class="shop_table shop_table_responsive">
				<tr>
					<th><?php _e('Friend','gens-raf'); ?></th>
					<th><?php _e('Referred On','gens-raf'); ?></th>
					<th><?php _e('Status','gens-raf'); ?></th>
				</tr>
		<?php
		if($orders) {
			foreach ( $orders as $order ) {
				$order = new WC_Order($order->ID);
				// Order ID, support 2.6
				if(method_exists($order, "get_id")) {
					$order_id = $order->get_id();			
				} else {
					$order_id = $order->id;
				}
				// User
				if ( $order->get_user_id() ) {
					$user = $order->get_user();
					$user = $user->display_name;
				} else {
					$user = __( 'Guest', 'gens-raf' );
				}
				// Date, support 2.6
				if(method_exists($order, "get_date_created")) {
					$date = date_i18n(wc_date_format(), strtotime($order->get_date_created()));
				} else {
					$date = $order->order_date;
				}

				echo '<tr>';
				echo '<td>'.$user.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td>'.$order->get_status().'</td>';
				echo '</tr>';

			}
		}
			echo '</table>';
	}

}
