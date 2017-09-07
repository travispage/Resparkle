<?php
/*
Plugin Name: Embed Google AdWords Codes on WooCommerce
Plugin URI: http://www.storeya.com/
Description: The ultimate Woocommerce plugin for Google AdWords advertising - embedding Conversion Tracking and Remarketing codes for you! 
Version: 1.2
Author: StoreYa
Author URI: http://www.storeya.com/

=== VERSION HISTORY ===
01.11.13 - v1.0 - The first version

=== LEGAL INFORMATION ===
Copyright © 2013 StoreYa Feed LTD - http://www.storeya.com/

License: GPLv2 
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

add_action('wp_footer', 'adw_rem_tag_insert');

add_action('wp_head', 'goog_search_console_ver_insert');

add_action( 'woocommerce_thankyou', 'adw_conv_tag_insert' );

function adw_rem_tag_insert()
{
    global $current_user;
    if (get_option('adw_rem_tag')) {            
        $adw_rem_tag_script = get_option('adw_rem_tag');
        echo $adw_rem_tag_script; 
    }
}

function goog_search_console_ver_insert()
{
    global $current_user;
    if (get_option('goog_search_console_ver')) {            
        $goog_search_console_ver_script = get_option('goog_search_console_ver');
        echo $goog_search_console_ver_script; 
    }
}

function get_adw_conv_id(){
	    global $current_user;
		if (get_option('adw_conv_id')) {            
			$adw_conv_id = get_option('adw_conv_id');
			return $adw_conv_id ; 
		}	
       return null;		
	}
	
	function get_adw_conv_label(){
	    global $current_user;
		if (get_option('adw_conv_label')) {            
			$adw_conv_label = get_option('adw_conv_label');
			return $adw_conv_label ; 
		}	
       return null;		
	}

function adw_conv_tag_insert($order_id) {
	
		$adw_conv_id  = get_adw_conv_id();	
	    $adw_conv_label  = get_adw_conv_label();
		
		$order = new WC_Order( $order_id );
		$order_total = $order->get_total();			
		
    if ( !$order->has_status( 'failed' ) && isset($adw_conv_id) && isset($adw_conv_label)){    
		
		$currency = $order->get_order_currency();
		
		
?>
	<!-- Start Google AdWords Conversion Code -->
	<script type="text/javascript">	
	var google_conversion_id = <?php echo $adw_conv_id; ?>;
	var google_conversion_language = 'en';
	var google_conversion_format = '3';
	var google_conversion_color = 'ffffff';
	var google_conversion_label = '<?php echo $adw_conv_label; ?>';
	var google_conversion_value = <?php echo $order_total; ?>;
	var google_conversion_currency = '<?php echo $currency; ?>';
	var google_remarketing_only = false;	
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/<?php echo $adw_conv_id; ?>/?value=<?php echo $order_total; ?>&currency_code=<?php echo $currency; ?>&label=<?php echo $adw_conv_label; ?>&guid=ON&script=0"/>
	</div>
	</noscript>
	</script>
	<!-- End Google AdWords Conversion Code -->

<?php	
	} 
}


if ( is_admin() ) {	


$plugurldir = get_option('siteurl') . '/' . PLUGINDIR . '/embed_google_adwords_codes/';
$igac_domain = 'embedGoogleAdWordsCodes';
load_plugin_textdomain($igac_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/embed_google_adwords_codes/');
add_action('init', 'igac_init');

add_action('admin_notices', 'igac_admin_notice');
add_filter('plugin_action_links', 'igac_plugin_actions', 10, 2);



function igac_init()
{
    if (function_exists('current_user_can') && current_user_can('manage_options'))
        add_action('admin_menu', 'igac_add_settings_page');
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $options = get_option('igacDisable');
}
function igac_settings()
{
    register_setting('embed_google_adwords_codes-group', 'adw_rem_tag');
    
     register_setting('embed_google_adwords_codes-group', 'goog_search_console_ver');
    
    register_setting('embed_google_adwords_codes-group', 'adw_conv_tag');
    
    register_setting('embed_google_adwords_codes-group', 'adw_conv_id');
    register_setting('embed_google_adwords_codes-group', 'adw_conv_label');
    
    register_setting('embed_google_adwords_codes-group', 'igacDisable');
    add_settings_section('embed_google_adwords_codes', "Embed Google AdWords Codes", "", 'embed_google_adwords_codes-group');

}
function igac_plugin_get_version()
{
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
    $plugin_file   = basename((__FILE__));
    return $plugin_folder[$plugin_file]['Version'];
}


function igac_admin_notice()
{
    if (!get_option('adw_conv_tag') && !get_option('adw_rem_tag'))
        echo ('<div class="error"><p><strong>' . sprintf(__('Embed Google AdWords Codes plugin is not set. Please go to the <a href="%s">plugin page</a> and save a valid data to enable it.'), admin_url('options-general.php?page=embed_google_adwords_codes')) . '</strong></p></div>');
}
function igac_plugin_actions($links, $file)
{
    $igac_domain = 'embedGoogleAdWordsCodes';
    static $this_plugin;
    if (!$this_plugin)
        $this_plugin = plugin_basename(__FILE__);
    if ($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=embed_google_adwords_codes') . '">' . __('Settings', $igac_domain) . '</a>';
        array_unshift($links, $settings_link);
    }
    return ($links);
}

        
    function igac_add_settings_page()
    {
        $igac_domain = 'EmbedGoogleAdWordsCodes';
		function igac_settings_page()
        {
            global $plugurldir, $storeya_options;
			$igac_domain = 'EmbedGoogleAdWordsCodes';
?>
      <div class="wrap">
        <?php
            screen_icon();
?>
        <h2><?php
            _e('StoreYa - Embed Google AdWords Codes ', $igac_domain);
?> <small><?
            echo igac_plugin_get_version();
?></small></h2>
        <div class="metabox-holder meta-box-sortables ui-sortable pointer">
          <div class="postbox" style="float:left;width: 81.5em;margin-right:20px">

            <div class="inside" style="padding: 0 10px">
              <p style="text-align:center">
		      </p>
              <form onSubmit="FillInfo();" method="post" action="options.php">
                <?php
            settings_fields('embed_google_adwords_codes-group');
?>
                <h3>How remarketing works</h3>
		<p>Implementing Google AdWords Remarketing Tag, so that you can mark your site's visitors and show them more ads in the future until they are convinced to purchase your products / services.</p>
                <table class="form-table"><tbody><tr><th scope="row">Remarketing Code</th><td><textarea rows="10" cols="20" style="width:100%;" name="adw_rem_tag" ><?php echo get_option('adw_rem_tag');?></textarea></td></tr></tbody></table>
                
                  <h3>Google Search Console Verification</h3>
           	<p>Implementing Google Search Console Verification Code helps you monitor and maintain your site's presence in Google Search results. Connecting and verifying your website can help you understand how Google views your site and optimize its performance in search results.</p>
                <table class="form-table"><tbody><tr><th scope="row">Verification Code</th><td><textarea rows="2" cols="20" style="width:100%;" name="goog_search_console_ver" ><?php echo get_option('goog_search_console_ver');?></textarea>                             
                </td></tr></tbody></table>
                
                 <h3>Adwords conversion tracking code</h3>
           	<p>Implementing Google AdWords conversion tracking so you would know how effective your ads are; how many of the clicks you are paying for are actually gaining for you sales, installs or whatever you are trying to to reach.</p>
                <table class="form-table"><tbody><tr><th scope="row">Conversion Tracking Code</th><td><textarea rows="10" cols="20" style="width:100%;" name="adw_conv_tag" ><?php echo get_option('adw_conv_tag');?></textarea>                
         
                 
                 <input type="hidden" name="adw_conv_id" value="<?php echo get_option('adw_conv_id');?>">
                 <input type="hidden" name="adw_conv_label" value="<?php echo get_option('adw_conv_label');?>">
                
                </td></tr></tbody></table>
                
                    <p class="submit">
                      <input type="submit" class="button-primary" value="<?php
            _e('Save Changes');
?>" />
                    </p>
                  </form>
</p>   				  <a href="http://www.storeya.com/public/trafficbooster?utm_source=WP&utm_medium=TBPlugin&utm_campaign=TBReg" target="_blank"><img src="<?php echo (plugins_url( 'TB.jpg', __FILE__ )); ?>"  /></a>
</div>
                </div>

                </div>
              </div>
			  <img src="http://www.storeya.com/widgets/admin?p=WpEmbedGoogleAdWordsCodes"/>
	<script type="text/javascript">
	
	function FillInfo()
	{
	    <?php 
	
	    if (get_option('adw_conv_tag')) { 
	               
                $adw_conv_tag_script = get_option('adw_conv_tag');              
                     
                if (preg_match("/var google_conversion_id = (.*);/", $adw_conv_tag_script)) {
                
		   preg_match("/var google_conversion_id = (.*);/", $adw_conv_tag_script, $id_matches);
                   $conversion_id = $id_matches[1];                 
                   update_option( 'adw_conv_id', $conversion_id );
                   	  
		}              
                        
                if (preg_match("/var google_conversion_label = \"(.*)\";/", $adw_conv_tag_script )) {
                
		   preg_match("/var google_conversion_label = \"(.*)\";/", $adw_conv_tag_script , $label_matches); 
                   $conversion_label = $label_matches[1];                   
                   update_option( 'adw_conv_label', $conversion_label  );
                   	  
		}      
            } 
            else
            {
               update_option( 'adw_conv_id', null);
               update_option( 'adw_conv_label', null);
            }   
	   
	    ?>	   

	}

        </script>
        <?php
        }
        add_action('admin_init', 'igac_settings');
        add_submenu_page('options-general.php', __('StoreYa - Embed Google AdWords Codes', $igac_domain), __('StoreYa - Embed Google AdWords Codes', $igac_domain), 'manage_options', 'embed_google_adwords_codes', 'igac_settings_page');
    }
    
    
}



?>