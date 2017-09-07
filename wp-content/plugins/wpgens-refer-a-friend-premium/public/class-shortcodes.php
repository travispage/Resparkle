<?php

/**
 * Front End Part of the Plugin (My Account Page, soon Tabs as well)
 *
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/public
 */
class Gens_RAF_Front extends Gens_RAF_Public {

	public function raf_product_tab($tabs) {

		$tabs_hide = get_option( 'gens_raf_tabs_disable' );

		if($tabs_hide != "yes") {
			$tabs['refer_tab'] = array(
		        'title'     => __( 'Refer to a friend', 'gens-raf' ),
		        'priority'  => 40,
		        'callback'  => array($this,'raf_product_tab_content')
		    );			
		}
	    return $tabs;
	}

	public function raf_product_tab_content() {

		$share_text = __(get_option( 'gens_raf_share_text' ),'gens-raf');
	    $guest_text = __(get_option( 'gens_raf_guest_text' ),'gens-raf');
	    
		if(is_user_logged_in()) {
		    echo $share_text;
			$this->share_front_end("tab");
		} else {
			echo $guest_text;
		}

	}

	/**
	 * Register Advance shortcode.
	 *
	 * @since    1.0.0
	 */
	public function advance_raf_shortcode_handler($atts, $content = null ) {
	   extract(shortcode_atts(array(
	      'share_text' => 'Share your referral URL with friends:',
	      'guest_text' => 'Please register to get your referral link.'
	   ), $atts));
		
		if(is_user_logged_in()) {
			ob_start();
		    echo $share_text;
			$this->share_front_end("shortcode");
			return ob_get_clean();
		} else {
			$return = '<div class="gens-refer-a-friend">';
			$return .= $guest_text;
			$return .= '</div>';
			return $return;
		}

	}

	/**
	 * Register RAF link as ContactForm7 shortcode.
	 *
	 * @since    1.0.0
	 */
	public function wpcf7_gens_shortcode_handler() {

		$referral_id = $this->get_referral_id( get_current_user_id() );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() )); 
		return '<input type="hidden" name="gens_raf" value="'.$refLink.'" />';
	}

	/**
	 * Register RAF link as simple shortcode.
	 *
	 * @since    1.0.0
	 */
	public function main_raf_shortcode_handler($atts, $content = null) {
		extract(shortcode_atts(array(
	      'id' => get_current_user_id(),
	    ), $atts));

		$referral_id = $this->get_referral_id( $id );
		$refLink = esc_url(add_query_arg( 'raf', $referral_id, get_home_url() )); 
		$refLink = "<a href='".$refLink."'>".$refLink."</a>";
		$refLink = apply_filters('gens_raf_link', $refLink);

		return $refLink;
	}

}
