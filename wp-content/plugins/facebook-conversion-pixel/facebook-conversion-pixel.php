<?php
/*
	Plugin Name: Pixel Cat Lite
	Plugin URI: https://fatcatapps.com/pixel-cat
	Description: Provides an easy way to embed Facebook pixels
	Text Domain: pixel-cat
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 2.0.1
*/


// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



$has_legacy_save = get_option( 'fb_pxl_options', false ) != false;
$upgraded = get_option( 'fca_pc_upgrade_complete' );

if ( !$upgraded && $has_legacy_save ) {
	
	include_once( plugin_dir_path( __FILE__ ) . '/deprecated/facebook-conversion-pixel.php' );
		
	//ADD NAG
	function fca_pc_admin_deprecated_notice() {
		$dismissed_deprecated_notice = get_option( 'fca_pc_deprecated_dismissed', false );
		
		if ( isSet( $_GET['fca_pc_upgrade'] ) && current_user_can('manage_options') ) {
			update_option( 'fca_pc_upgrade_complete', true );
			echo '<script>window.location="' . admin_url('admin.php?page=fca_pc_settings_page') . '"</script>';
			exit;
		}
		if ( isSet( $_GET['fca_pc_dismiss_upgrade'] ) && current_user_can('manage_options') ) {
			update_option( 'fca_pc_deprecated_dismissed', true );
		} else if ( $dismissed_deprecated_notice != true && current_user_can('manage_options') ) {
			$upgrade_url = admin_url( 'options-general.php?page=fb_pxl_options&fca_pc_upgrade=true' );
			$dismiss_url = admin_url( 'options-general.php?page=fb_pxl_options&fca_pc_dismiss_upgrade=true' );
			$read_more_url = 'https://fatcatapps.com/facebook-pixel/';
			
			echo '<div id="fca-pc-setup-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
				echo '<img style="float:left; margin-right: 16px;" height="120" width="120" src="' . plugins_url( '', __FILE__ ) . '/assets/pixelcat_icon_128_128_360.png' . '">';
				echo '<p style="margin-top: 0;"><strong>' .  __( "Pixel Cat: We now support the new universal Facebook Pixel!", 'pixel-cat' ) . '</strong></p>';
				echo '<p>' . __( "Not sure about the new features? No problem!", 'pixel-cat' ) . " <a href='$read_more_url' target='_blank'>" .  __( "Learn more", 'pixel-cat' ) . '</a></p>';
				echo '<p>' . __( "Don't worry you can revert back with one click, and we'll keep your current settings. Click upgrade to get started.", 'pixel-cat' ) . '</p>';
				echo "<a href='$upgrade_url' type='button' class='button button-primary' >" . __( 'Upgrade', 'pixel-cat') . "</a> ";
				echo "<a style='position: relative;	left: 25px; top: 4px;' href='$dismiss_url' type='button'>" . __( 'Not right now', 'pixel-cat') . "</a> ";
				echo '<br style="clear:both">';
			echo '</div>';
		}
	}
	add_action( 'admin_notices', 'fca_pc_admin_deprecated_notice' );
	
} else if ( !defined('FCA_PC_PLUGIN_DIR') ) {
	
	//DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_PC_DEBUG', FALSE );
	define( 'FCA_PC_PLUGIN_VER', '2.0.1' );
	define( 'FCA_PC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_PC_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_PC_PLUGINS_BASENAME', plugin_basename(__FILE__) );
	define( 'FCA_PC_PLUGIN_FILE', __FILE__ );
	define( 'FCA_PC_PLUGIN_PACKAGE', 'Lite' ); //DONT CHANGE THIS - BREAKS AUTO UPDATER
	define( 'FCA_PC_PLUGIN_NAME', 'Pixel Cat Premium: ' . FCA_PC_PLUGIN_PACKAGE );
	
	//LOAD CORE
	include_once( FCA_PC_PLUGIN_DIR . '/includes/api.php' );
	
	$options = get_option( 'fca_pc', array() );
	
	if ( !empty( $options['search_integration'] ) && file_exists ( FCA_PC_PLUGIN_DIR . '/includes/integrations/search.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/integrations/search.php' );
	}
	if ( !empty( $options['woo_integration'] ) && file_exists ( FCA_PC_PLUGIN_DIR . '/includes/integrations/woo-events.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/integrations/woo-events.php' );
	}
	if ( !empty( $options['woo_feed'] ) && file_exists ( FCA_PC_PLUGIN_DIR . '/includes/integrations/woo-feed.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/integrations/woo-feed.php' );
	}	
	if ( !empty( $options['quizcat_integration'] ) && file_exists ( FCA_PC_PLUGIN_DIR . '/includes/integrations/quizcat.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/integrations/quizcat.php' );
	}

	//LOAD MODULES
	include_once( FCA_PC_PLUGIN_DIR . '/includes/editor/editor.php' );
	if ( file_exists ( FCA_PC_PLUGIN_DIR . '/includes/editor/editor-premium.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/editor/editor-premium.php' );
	}
	
	if ( file_exists ( FCA_PC_PLUGIN_DIR . '/includes/splash/splash.php' ) ) {
		//include_once( FCA_PC_PLUGIN_DIR . '/includes/splash/splash.php' );
	}
	
	if ( file_exists ( FCA_PC_PLUGIN_DIR . '/includes/licensing/licensing.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/licensing/licensing.php' );
	}
	
	if ( file_exists ( FCA_PC_PLUGIN_DIR . '/includes/upgrade.php' ) ) {
		include_once( FCA_PC_PLUGIN_DIR . '/includes/upgrade.php' );
	}

	if ( FCA_PC_PLUGIN_PACKAGE === 'Lite' ) {
		//ACTIVATION HOOK
		function fca_pc_activation() {
			fca_pc_api_action( 'Activated Pixel Cat Free' );
		}
		register_activation_hook( FCA_PC_PLUGIN_FILE, 'fca_pc_activation' );
		
		//DEACTIVATION HOOK
		function fca_pc_deactivation() {
			fca_pc_api_action( 'Deactivated Pixel Cat Free' );
		}
		register_deactivation_hook( FCA_PC_PLUGIN_FILE, 'fca_pc_deactivation' );
	}
	
	//HELPER FILTERS
	function fca_pc_cat_id_fiter ( $cat_id ) {
		return 'cat' . $cat_id;
	}	
	function fca_pc_tag_id_fiter ( $tag_id ) {
		return 'tag' . $tag_id->term_id;
	}
	//INSERT PIXEL
	function fca_pc_maybe_add_pixel() {

		$roles = wp_get_current_user()->roles;
		
		$options = get_option( 'fca_pc', array() );
		$pixel = empty ( $options['id'] ) ? '' : $options['id'];
		$exclude = empty ( $options['exclude'] ) ? array() : $options['exclude'];
		$roles_check = count( array_intersect( array_map( 'strtolower', $roles), array_map( 'strtolower', $exclude ) ) ) == 0;
		global $post;
		
		if ( !empty( $pixel ) && $roles_check ) {
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('fca_pc_client_js');
			
			//INTEGRATIONS
			if ( function_exists('fca_pc_woocommerce_events') ) {
				fca_pc_woocommerce_events();
			}
			if ( function_exists('fca_pc_quizcat_events') ) {
				fca_pc_quizcat_events();
			}
			if ( function_exists('fca_pc_woocommerce_search') ) {
				fca_pc_woocommerce_search();
			} else if ( function_exists('fca_pc_search_integration') ) {
				fca_pc_search_integration();
			}		
			//CHECK FOR EVENTS AND SEND TO JS
			$events = empty( $options['events'] ) ? array() : stripslashes_deep( $options['events'] );
			
			$id = get_the_ID();
			$categories = wp_get_post_categories( $id );
			$tags = wp_get_post_tags( $id );
			
			$active_events = array();		
			if ( !empty ( $events ) ) {
				forEach ( $events as $event ) {
					$event = json_decode( $event );
					if ( is_array( $event->trigger ) ) {
						$post_id_match = in_array( $id, $event->trigger );
						//CHECK CATEGORIES & TAGS
						$category_match = count( array_intersect( array_map( 'fca_pc_cat_id_fiter', $categories ), $event->trigger ) ) > 0;
						$tag_match = count( array_intersect( array_map( 'fca_pc_tag_id_fiter', $tags ), $event->trigger ) ) > 0;
						$front_page_match = is_front_page() && in_array( 'front', $event->trigger );
						$blog_page_match = is_home() && in_array( 'blog', $event->trigger );
						if ( in_array( 'all', $event->trigger ) OR $post_id_match OR $category_match OR $front_page_match OR $blog_page_match OR $tag_match ) {
							$active_events[] = $event;
						} 	
					} else {
						//CSS TRIGGERS
						$active_events[] = $event;
					}

				}
			}
					
			wp_localize_script( 'fca_pc_client_js', 'fcaPcEvents', $active_events );
			wp_localize_script( 'fca_pc_client_js', 'fcaPcDebug', array( 'debug' => FCA_PC_DEBUG ) );
			
			//GET CATEGORY NAMES
			$category_names = array();
			
			forEach ( $categories as $cat_id ) {
				$category_names[] = get_cat_name( $cat_id );
			}
			
			
			$post_data = array(
				'title' => empty ( $post->post_title ) ? '' : $post->post_title,
				'type' => empty ( $post->post_type ) ? '' : $post->post_type,
				'id' => empty ( $post->ID ) ? '' : $post->ID,
				'categories' => empty ( $category_names ) ? array() : $category_names,
			);
				
			wp_localize_script( 'fca_pc_client_js', 'fcaPcPost', $post_data );
			
			ob_start(); ?>
			
			<!-- Facebook Pixel Code -->
			<script>
			!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
			<?php 
				if ( function_exists( 'fca_pc_woo_advanced_matching' ) && is_user_logged_in() ){ ?>
					fbq('init', '<?php echo $pixel ?>', <?php echo fca_pc_woo_advanced_matching() ?> );
				<?php } else { ?>
					fbq('init', '<?php echo $pixel ?>');
			<?php }	?>
			
			fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=<?php echo $pixel ?>&ev=PageView&noscript=1"
			/></noscript>
			<!-- DO NOT MODIFY -->
			<!-- End Facebook Pixel Code -->
			
			<?php
			echo ob_get_clean();
		}
	}
	add_action('wp_head', 'fca_pc_maybe_add_pixel', 1);
	
	function fca_pc_register_scripts() {
		if ( FCA_PC_DEBUG ) {
			wp_register_script('fca_pc_client_js', FCA_PC_PLUGINS_URL . '/pixel-cat.js', array('jquery'), FCA_PC_PLUGIN_VER, true );
		} else {
			wp_register_script('fca_pc_client_js', FCA_PC_PLUGINS_URL . '/pixel-cat.min.js', array('jquery'), FCA_PC_PLUGIN_VER, true );
		}	
	}	
	add_action('init', 'fca_pc_register_scripts' );
	
	////////////////////////////
	// LOCALIZATION
	////////////////////////////
	
	function fca_pc_load_localization() {
		load_plugin_textdomain( 'pixel-cat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'fca_pc_load_localization' );
	
	////////////////////////////
	// FUNCTIONS
	////////////////////////////
		
	//RETURN GENERIC INPUT HTML
	function fca_pc_input ( $name, $placeholder = '', $value = '', $type = 'text', $atts = '' ) {
	
		$html = "<div class='fca-pc-field fca-pc-field-$type'>";
		
			switch ( $type ) {
				
				case 'checkbox':
					$checked = !empty( $value ) ? "checked='checked'" : '';
					
					$html .= "<div class='onoffswitch'>";
						$html .= "<input $atts style='display:none;' type='checkbox' id='fca_pc[$name]' class='onoffswitch-checkbox fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' $checked>"; 
						$html .= "<label class='onoffswitch-label' for='fca_pc[$name]'><span class='onoffswitch-inner' data-content-on='ON' data-content-off='OFF'><span class='onoffswitch-switch'></span></span></label>";
					$html .= "</div>";
					break;
				
				case 'textarea':
					$html .= "<textarea $atts placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]'>$value</textarea>";
					break;
					
				case 'image':
					$html .= "<input type='hidden' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					$html .= "<button type='button' class='button-secondary fca_pc_image_upload_btn'>" . __('Add Image', 'pixel-cat') . "</button>";
					$html .= "<img class='fca_pc_image' style='max-width: 252px' src='$value'>";
			
					$html .= "<div class='fca_pc_image_hover_controls'>";
						$html .= "<button type='button' class='button-secondary fca_pc_image_change_btn'>" . __('Change', 'pixel-cat') . "</button>";
						$html .= "<button type='button' class='button-secondary fca_pc_image_revert_btn'>" . __('Remove', 'pixel-cat') . "</button>";
					$html .=  '</div>';
					break;
				case 'color':
					$html .= "<input $atts type='hidden' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					break;
				case 'editor':
					ob_start();
					wp_editor( $value, $name, array() );
					$html .= ob_get_clean();
					break;
				case 'datepicker':
					$html .= "<input $atts type='text' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					break;
				case 'roles':
					$roles = get_editable_roles();
					forEach ( $roles as $role ) {
						$options[] = $role['name'];
					}
					$html = "<select $atts name='fca_pc[$name][]' data-placeholder='$placeholder' multiple='multiple' style='width: 100%; border: 1px solid #ddd; border-radius: 0;' class='fca_pc_multiselect'>";
						forEach ( $options as $role ) {
							if ( in_array($role, $value) ) {
								$html .= "<option value='$role' selected='selected'>$role</option>";
							} else {
								$html .= "<option value='$role'>$role</option>";
							}
						}
					
					$html .= "</select>";
					break;
				case 'hidden':
					$html .= "<input $atts type='hidden' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
					break;
						
				default: 
					$html .= "<input $atts type='$type' placeholder='$placeholder' class='fca-pc-input-$type fca-pc-$name' name='fca_pc[$name]' value='$value'>";
			}
		
		$html .= '</div>';
		
		return $html;
	}
	
	//SINGLE-SELECT
	function fca_pc_select( $name, $selected = '', $options = array() ) {
		$html = "<div class='fca-pc-field fca-pc-field-select'>";
			$html .= "<select name='fca_pc[$name]' class='fca-pc-input-select fca-pc-$name'>";
				if ( empty( $options ) && !empty ( $selected ) ) {
					$html .= "<option selected='selected' value='$selected'>" . __('Loading...', 'pixel-cat') . "</option>";
				} else {
					forEach ( $options as $key => $text ) {
						$sel = $selected === $key ? 'selected="selected"' : '';
						$html .= "<option $sel value='$key'>$text</option>";
					}
				}
			$html .= '</select>';
		$html .= '</div>';
		
		return $html;
	}
	
	function fca_pc_delete_icons() {
		ob_start(); ?>
			<span class='dashicons dashicons-trash fca_delete_icon fca_delete_button'></span>
			<span class='dashicons dashicons-yes fca_delete_icon fca_delete_icon_confirm' style='display:none;'></span>
			<span class='dashicons dashicons-no fca_delete_icon fca_delete_icon_cancel' style='display:none;'></span>
		<?php
		return ob_get_clean();
	}
	
	function fca_pc_tooltip( $text = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
		return "<span class='$icon fca_pc_tooltip' title='" . htmlentities( $text ) . "'></span>";
	}
	
	function fca_pc_convert_entities ( $array ) {
		$array = is_array($array) ? array_map('fca_pc_convert_entities', $array) : html_entity_decode( $array, ENT_QUOTES );
		return $array;
	}
	
	function fca_pc_add_plugin_action_links( $links ) {
		
		$url = admin_url('admin.php?page=fca_pc_settings_page');
		
		$new_links = array(
			'configure' => "<a href='$url' >" . __('Configure Pixel', 'pixel-cat' ) . '</a>'
		);
		
		$links = array_merge( $new_links, $links );
	
		return $links;
		
	}
	add_filter( 'plugin_action_links_' . FCA_PC_PLUGINS_BASENAME, 'fca_pc_add_plugin_action_links' );
	
	//ADD NAG IF NO PIXEL IS SET
	function fca_pc_admin_notice() {
		$options = get_option( 'fca_pc', array() );
		$screen = get_current_screen();
		if ( empty( $options['id'] ) && $screen->id != 'toplevel_page_fca_pc_settings_page'  ) {
			$url = admin_url( 'admin.php?page=fca_pc_settings_page' );
		
			echo '<div id="fca-pc-setup-notice" class="notice notice-success is-dismissible" style="padding-bottom: 8px; padding-top: 8px;">';
				echo '<img style="float:left; margin-right: 16px;" height="120" width="120" src="' . FCA_PC_PLUGINS_URL . '/assets/pixelcat_icon_128_128_360.png' . '">';
				echo '<p><strong>' . __( "Thank you for installing Pixel Cat.", 'pixel-cat' ) . '</strong></p>';
				echo '<p>' . __( "It looks like you haven't configured your Facebook Pixel yet. Ready to get started?", 'pixel-cat' ) . '</p>';
				echo "<a href='$url' type='button' class='button button-primary' style='margin-top: 25px;'>" . __( 'Set up my Pixel', 'pixel-cat') . "</a> ";
				echo '<br style="clear:both">';
			echo '</div>';
		}
	
	}
	add_action( 'admin_notices', 'fca_pc_admin_notice' );
	
	//ADD DOWNGRADE LINK
	function fca_pc_admin_footer( $text ) {
		$screen = get_current_screen();
		$has_legacy_save = get_option( 'fb_pxl_options', false ) != false;
		if ( $has_legacy_save && $screen->id == 'toplevel_page_fca_pc_settings_page' && FCA_PC_PLUGIN_PACKAGE === 'Lite' ) {
			$downgrade_url = admin_url( 'admin.php?page=fca_pc_settings_page&fca_pc_downgrade=true' );
			$text = __('Looking for the old Facebook Conversion Pixel?', 'pixel-cat') . " <a href='$downgrade_url'>" . __('Click here to downgrade', 'pixel-cat') . '</a>';
		}
		return $text;
	}
	add_filter( 'admin_footer_text', 'fca_pc_admin_footer' ); 

	
	//DEACTIVATION SURVEY
	if ( FCA_PC_PLUGIN_PACKAGE === 'Lite' ) {
		function fca_pc_admin_deactivation_survey( $hook ) {
			if ( $hook === 'plugins.php' ) {
				
				ob_start(); ?>
				
				<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
					<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php _e( 'Sorry to see you go', 'pixel-cat' ) ?></h3>
					<p><?php _e( 'Hi, this is David, the creator of Pixel Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'pixel-cat' ) ?>
					</p>
					<p><?php _e( 'I have a quick question that I hope you’ll answer to help us make Pixel Cat better: what made you deactivate?', 'pixel-cat' ) ?>
					</p>
					<p><?php _e( 'You can leave me a message below. I’d really appreciate it.', 'pixel-cat' ) ?>
					</p>
					
					<p><textarea style='width: 100%;' id='fca-pc-deactivate-textarea' placeholder='<?php _e( 'What made you deactivate?', 'pixel-cat' ) ?>'></textarea></p>
					
					<div style='float: right;' id='fca-deactivate-nav'>
						<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-pc-deactivate-skip'><?php _e( 'Skip', 'pixel-cat' ) ?></button>
						<button type='button' class='button button-primary' id='fca-pc-deactivate-send'><?php _e( 'Send Feedback', 'pixel-cat' ) ?></button>
					</div>
				
				</div>
				
				<?php
					
				$html = ob_get_clean();
				
				$data = array(
					'html' => $html,
					'nonce' => wp_create_nonce( 'fca_pc_uninstall_nonce' ),
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				);
							
				wp_enqueue_script('fca_pc_deactivation_js', FCA_PC_PLUGINS_URL . '/includes/deactivation.min.js', false, FCA_PC_PLUGIN_VER, true );
				wp_localize_script( 'fca_pc_deactivation_js', 'fca_pc', $data );
			}
			
			
		}	
		add_action( 'admin_enqueue_scripts', 'fca_pc_admin_deactivation_survey' );
	}
}