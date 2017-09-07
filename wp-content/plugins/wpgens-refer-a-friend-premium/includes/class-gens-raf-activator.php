<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Gens_RAF
 * @subpackage Gens_RAF/includes
 * @author     Your Name <email@example.com>
 */
class Gens_RAF_Activator {

	/**
	 * Adding myreferrals new subpage for my account page. Flushing rules.
	 *
	 * @since    1.2.0
	 */
	public static function activate() {
		add_rewrite_endpoint( 'myreferrals', EP_PAGES );
		flush_rewrite_rules();
		if(!get_option('gens_raf_myaccount_text')) {
	        $op = "<h2>Referral Program</h2><p>For each friend you invite, we will send you a coupon code worth $20 that you can use to purchase or get a discount on any product on our site. Get started now, by sharing your referral link with your friends.</p>";
	        add_option('gens_raf_myaccount_text', $op);
	        add_option('gens_raf_share_text', $op);
	    }
	    if(!get_option('gens_raf_email_body')) {
	        $op = "Check out this site!  I gave their products a try and I love them!
Here is the code that will give you a discount: {{code}}.";
	        add_option('gens_raf_email_body', $op);
	        add_option('gens_raf_email_subject_share','Check out this site!');
	    }

	    
	}

}
