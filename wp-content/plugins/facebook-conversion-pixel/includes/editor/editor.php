<?php

////////////////////////////
// SETTINGS PAGE 
////////////////////////////

function fca_pc_plugin_menu() {
	
	add_menu_page(
		__( 'Pixel Cat', 'pixel-cat' ),
		__( 'Facebook Pixel', 'pixel-cat' ),
		'manage_options',
		'fca_pc_settings_page',
		'fca_pc_settings_page',
		FCA_PC_PLUGINS_URL . '/assets/icon.png',
		119
	);
	
}
add_action( 'admin_menu', 'fca_pc_plugin_menu' );

//ENQUEUE ANY SCRIPTS OR CSS FOR OUR ADMIN PAGE EDITOR
function fca_pc_admin_enqueue() {

	wp_enqueue_style('dashicons');
	wp_enqueue_script('jquery');

	wp_enqueue_script( 'fca_pc_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array(), FCA_PC_PLUGIN_VER, true );
	wp_enqueue_style( 'fca_pc_select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', array(), FCA_PC_PLUGIN_VER );
	
	wp_enqueue_style( 'fca_pc_tooltipster_stylesheet', FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.css', array(), FCA_PC_PLUGIN_VER );
	wp_enqueue_style( 'fca_pc_tooltipster_borderless_css', FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster-borderless.min.css', array(), FCA_PC_PLUGIN_VER );
	wp_enqueue_script( 'fca_pc_tooltipster_js',FCA_PC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.js', array('jquery'), FCA_PC_PLUGIN_VER, true );
	
	$admin_dependencies = array('jquery', 'fca_pc_select2', 'fca_pc_tooltipster_js' );
	
	if ( FCA_PC_DEBUG ) {
		wp_enqueue_script('fca_pc_admin_js', FCA_PC_PLUGINS_URL . '/includes/editor/admin.js', $admin_dependencies, FCA_PC_PLUGIN_VER, true );		
		wp_enqueue_style( 'fca_pc_admin_stylesheet', FCA_PC_PLUGINS_URL . '/includes/editor/admin.css', array(), FCA_PC_PLUGIN_VER );
	} else {
		wp_enqueue_script('fca_pc_admin_js', FCA_PC_PLUGINS_URL . '/includes/editor/admin.min.js', $admin_dependencies, FCA_PC_PLUGIN_VER, true );		
		wp_enqueue_style( 'fca_pc_admin_stylesheet', FCA_PC_PLUGINS_URL . '/includes/editor/admin.min.css', array(), FCA_PC_PLUGIN_VER );
	}
	$options = get_option( 'fca_pc', array() );
	$events = empty( $options['event_json'] ) ? json_encode( array() ) : stripslashes_deep( $options['event_json'] );
	
	$admin_data = array (
		'ajaxurl' => admin_url ( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'fca_pc_admin_nonce' ),
		'eventTemplate' => fca_pc_event_row_html(),
	);
	wp_localize_script( 'fca_pc_admin_js', 'fcaPcDebug', array( 'debug' => FCA_PC_DEBUG ) );
	wp_localize_script( 'fca_pc_admin_js', 'fcaPcAdminData', $admin_data );
	
}

function fca_pc_settings_page() {
	
	$options = get_option( 'fca_pc', array() );
	$form_class = FCA_PC_PLUGIN_PACKAGE === 'Lite' ? 'fca-pc-free' : 'fca-pc-premium';
	if ( isSet( $_POST['fca_pc_save'] ) ) {
		$options = fca_pc_settings_save();
	}
	
	if ( isSet( $_GET['fca_pc_downgrade'] ) ) {
		update_option( 'fca_pc_upgrade_complete', false );
		echo '<script>window.location="' . admin_url('options-general.php?page=fb_pxl_options') . '"</script>';
		exit;
	}
		
	$id = empty ( $options['id'] ) ? '' : $options['id'];
	$options['events'] = empty ( $options['events'] ) ? array() : $options['events'];

	fca_pc_admin_enqueue();
	
	$exclude = empty ( $options['exclude'] ) ? array() : $options['exclude'];
	//DEFAULT EXCLUDE TO ADMIN & EDITOR
	$exclude = empty ( $options['has_save'] ) ? array( 'Administrator', 'Editor' ) : $exclude;
	
	$html = "<div id='fca-pc-overlay' style='display:none'></div>";
	
	$html .= "<form novalidate style='display: none' action='' method='post' id='fca_pc_main_form' class='$form_class'>";
		
		$html .= '<a class="fca-pc-top-icon" target="_blank" href="https://fatcatapps.com/pixelcat/"><img height="120" width="120" src="' . FCA_PC_PLUGINS_URL . '/assets/pixelcat_icon_128_128_360.png' . '"></a>';

		$html .= '<h1>' .  __('Pixel Cat', 'pixel-cat') . '</h1>';
		
		$html .= '<p>' . __('Help: ', 'pixel-cat');
			$html .= '<a href="https://fatcatapps.com/facebook-pixel/#Option_2_Install_a_Facebook_Pixel_WordPress_plugin_recommended" target="_blank">' . __('Setup Instructions', 'pixel-cat') . '</a> | ';
			$html .= '<a href="https://fatcatapps.com/facebook-pixel/#How_To_Migrate_To_The_New_Facebook_Pixel_From_The_Old_Conversion_Pixel" target="_blank">' . __('Migration from old Conversion Pixel', 'pixel-cat') . '</a> | ';
			$html .= '<a href="https://fatcatapps.com/facebook-pixel/" target="_blank">' . __('FB Pixel: The Definitive Guide', 'pixel-cat') . '</a> | ';
			$html .= '<a href="https://wordpress.org/support/plugin/facebook-conversion-pixel" target="_blank">' . __('Support Forum', 'pixel-cat') . '</a>';
		$html .= '</p>';
		
		$html .= "<h1 class='nav-tab-wrapper fca-pc-nav $form_class'>";
			$html .= '<a href="#" data-target=".fca_pc_setting_table" class="nav-tab">' . __('Main', 'pixel-cat') . '</a>';
			$html .= '<a href="#" data-target="#fca-pc-events-table" class="nav-tab">' . __('Events', 'pixel-cat') . '</a>';
			if ( FCA_PC_PLUGIN_PACKAGE !== 'Personal' ) {
				$html .= '<a href="#" data-target="#fca-pc-woo-table" class="nav-tab">' . __('WooCommerce', 'pixel-cat') . '</a>';
			}
		$html .= '</h1>';
		
		//ADD A HIDDEN INPUT TO DETERMINE IF WE HAVE AN EMPTY SAVE OR NOT
		$html .= fca_pc_input ( 'has_save', '', true, 'hidden' );
		
		$html .= '<table class="fca_pc_setting_table" >';
			$html .= "<tr>";
				$html .= '<th>' . __('Facebook Pixel ID', 'pixel-cat') . '</th>';
				$html .= '<td id="fca-pc-helptext" title="' . __('Your Facebook Pixel ID should only contain numbers', 'pixel-cat') . '" >' . fca_pc_input ( 'id', 'e.g. 123456789123456', $id, 'text' );
				$html .= '<a class="fca_pc_hint" href="https://fatcatapps.com/facebook-pixel-wordpress-plugin/#pixel-id" target="_blank">' . __( 'What is my Facebook Pixel ID?', 'pixel-cat' ) . '</a>';
				$html .= '</td>';
			$html .= "</tr>";
			$html .= "<tr>";
				$html .= '<th>' . __('Exclude Users', 'pixel-cat') . '</th>';
				$html .= '<td>' . fca_pc_input ( 'exclude', '', $exclude, 'roles' );
				$html .= '<p class="fca_pc_hint">' . __( 'Logged in users selected above will not trigger your pixel.', 'pixel-cat' ) . '</p>';
				$html .= '</td>';
			$html .= "</tr>";
			
		$html .= '</table>';
		
		$html .= fca_pc_event_panel( $options['events'] );
		
		$html .= fca_pc_add_premium_integrations( $options );
		
		$html .= fca_pc_add_woo_integrations( $options );
				
		$html .= '<button id="fca_pc_save" type="submit" style="margin-top: 20px;" name="fca_pc_save" class="button button-primary">' . __('Save', 'pixel-cat') . '</button>';
	
	if ( function_exists ('fca_pc_add_premium_event_form') ) {
		$html .= fca_pc_add_premium_event_form();
	} else {
		$html .= fca_pc_add_event_form();
	}
		
	$html .= '</form>';
	
	if ( FCA_PC_PLUGIN_PACKAGE === 'Lite' ) {
		$html .= fca_pc_marketing_metabox();
	}
		
	echo $html;
}

function fca_pc_add_event_form() {
	
	$events = array(
		'ViewContent' => 'ViewContent',
		'Lead' => 'Lead',
		'AddToCart' => 'AddToCart' . __(' - Pro Only', 'pixel-cat' ),
		'AddToWishlist' => 'AddToWishlist' . __(' - Pro Only', 'pixel-cat' ),
		'InitiateCheckout' => 'InitiateCheckout' . __(' - Pro Only', 'pixel-cat' ),
		'AddPaymentInfo' => 'AddPaymentInfo' . __(' - Pro Only', 'pixel-cat' ),
		'Purchase' => 'Purchase' . __(' - Pro Only', 'pixel-cat' ),
		'CompleteRegistration' => 'CompleteRegistration' . __(' - Pro Only', 'pixel-cat' )
	);
	
	$disabled_events = array (
		'AddToCart',
		'AddToWishlist',
		'InitiateCheckout',
		'AddPaymentInfo',
		'Purchase',
		'CompleteRegistration'
	);
	
	$triggers = array(
		'all' => __('All Pages', 'pixel-cat'),
		'front' => __('Front Page', 'pixel-cat'),
		'blog' => __('Blog Page', 'pixel-cat')
	);

	forEach ( get_pages( array( 'posts_per_page' => -1 ) ) as $page ) {
		$triggers[$page->ID] = 'Page ' . $page->ID . ' - ' . $page->post_title;
	}
	forEach ( get_posts( array( 'posts_per_page' => -1 ) ) as $post ) {
		$triggers[$post->ID] = 'Post ' . $post->ID . ' - ' . $post->post_title;
	}
	
	forEach ( get_categories() as $cat ) {
		$triggers['cat' . $cat->cat_ID] = 'Category ' . $cat->cat_ID . ' - ' . $cat->category_nicename;
	}
	
	forEach ( get_tags() as $tag ) {
		$triggers['tag' . $tag->term_id] = 'Tag ' . $tag->term_id  . ' - ' . $tag->name;
	}
	
	//REMOVE BLOG PAGE FROM OPTIONS - USE BLOG SETTING INSTEAD
	$blog_id = get_option('page_for_posts');
	if ( $blog_id !== 0 ) {
		unset ( $triggers[$blog_id] );
	}
	
	$modes = array (
		'post' => __( 'Post, Page, Tag or Category', 'pixel-cat' ),
		'css' => __( 'CSS Selector Click - Pro Only', 'pixel-cat' ),
		'url' => __( 'URL Click - Pro Only', 'pixel-cat' ),
	);
	
	$disabled_modes = array(
		'css',
		'url',
	);
	
	ob_start(); ?>
	<div id='fca-pc-event-modal' style='display: none;'>
		<h3><?php _e('Edit Event', 'pixel-cat') ?></h3>
		<table class="fca_pc_modal_table">
			<tr>
				<th><?php _e('Trigger', 'pixel-cat') ?></th>
				<td>
					<select id='fca-pc-modal-trigger-type-input' class='fca_pc_select' name='fca[trigger_type]' style='width: 100%' >
						<?php 
						forEach ( $modes as $key => $value ) {
							if ( !in_array( $key, $disabled_modes ) ) {
								echo "<option value='$key'>$value</option>";
							} else {
								echo "<option value='$key' disabled>$value</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr id='fca-pc-post-input-tr'>
				<th><?php _e('Pages', 'pixel-cat'); echo fca_pc_tooltip( __('Choose where on your site to trigger this event. You can choose any posts, pages, or categories.', 'pixel-cat') ) ?></th>
				<td>
					<select id='fca-pc-modal-post-trigger-input' class='fca_pc_multiselect' multiple='multiple' style='width: 100%' >
						
						<?php 
						forEach ( $triggers as $key => $value ) {
							echo "<option value='$key'>$value</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e('Event', 'pixel-cat'); ?></th>
				<td>
					<select id='fca-pc-modal-event-input' class='fca_pc_select' style='width: 100%' >
						<optgroup label='<?php _e( 'Standard Events', 'pixel-cat' ) ?>'>
						<?php 
						forEach ( $events as $key => $value ) {
							if ( !in_array( $key, $disabled_events ) ) {
								echo "<option value='$key'>$value</option>";
							} else {
								echo "<option value='$key' disabled>$value</option>";
							}
						}?>
						</optgroup>
						<option value='custom' class='fca-bold' disabled><?php _e( 'Custom Event - Pro Only', 'pixel-cat' ) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e('Parameters', 'pixel-cat')?></th>
				<td><span style='font-style: italic; position: relative; top: 7px;'><?php _e('Parameters, time delay, all events and dynamic event triggers<br>are available in', 'pixel-cat') ?> <a href='https://fatcatapps.com/pixelcat' target='_blank'><?php _e('Pixel Cat Premium', 'pixel-cat') ?></a>.</span></td>
			</tr>
		</table>

		<button type='button' id='fca-pc-event-save' class='button button-primary' style='margin-right: 8px'><?php _e('Save', 'pixel-cat') ?></button>
		<button type='button' id='fca-pc-event-cancel' class='button button-secondary'><?php _e('Cancel', 'pixel-cat') ?></button>
	
	</div>

	<?php
	return ob_get_clean();
}

function fca_pc_event_tooltips(){
	
	$viewcontent_hover_text =  htmlentities ( __("We'll automatically send the following event parameters to Facebook:<br>content_name: Post/Page title (eg. \"My first blogpost\")<br>content_type: Post type (eg. \"Post\", \"Page\", \"Product\")<br>content_ids: The WordPress post id (eg. \"47\")", 'pixel-cat'), ENT_QUOTES );
	$lead_hover_text = htmlentities ( __("We'll automatically send the following event parameters to Facebook:<br>content_name: Post/Page title (eg. \"My first blogpost\")<br>content_category: The post's category, if any (eg. \"News\")", 'pixel-cat'), ENT_QUOTES );
	
	$html = "<p class='fca_pc_hint' id='fca_pc_tooltip_viewcontent'>";
		$html .= sprintf( __("Send the %1sViewContent%2s standard event to Facebook.<br>(%3sWhich Parameters will be sent?%4s)", 'pixel-cat'), '<strong>', '</strong>', "<span class='fca_pc_event_tooltip' title='$viewcontent_hover_text'>", '</span>' );
	$html .= '</p>';
	
	$html .= "<p class='fca_pc_hint' id='fca_pc_tooltip_lead' style='display: none'>";
		$html .= sprintf( __("Send the %1sLead%2s standard event to Facebook.<br>(%1sWhich Parameters will be sent?%2s)", 'pixel-cat'), '<strong>', '</strong>', "<span class='fca_pc_event_tooltip' title='$lead_hover_text'>", '</span>' );
	$html .= '</p>';
	return $html;
}
function fca_pc_event_panel( $events = array() ) {
	$html = '<div id="fca-pc-events-table">';
		$html .= "<h3>" . __('Events', 'pixel-cat') . "</h3>";
		$html .= "<p>" . __('Add events based on user behavior.', 'pixel-cat') . "</p>";
		$html .= '<table id="fca-pc-events" class="widefat">';
			$html .= '<tr id="fca-pc-event-table-heading">';
				//HIDDEN COLUMN FOR JSON
				$html .= '<th style="display:none;"></th>';
				$html .= '<th style="width: 30%;">' . __('Event','pixel-cat') . '</th>';
				$html .= '<th style="width: calc( 70% - 75px );">' . __('Trigger','pixel-cat') . '</th>';
				$html .= '<th style="text-align: right; width: 67px;"></th>';
			$html .= '</tr>';
			forEach ( $events as $event ) {
				$html .= fca_pc_event_row_html( $event );
			}
		$html .= '</table>';
		$html .= '<button type="button" id="fca_pc_new_event" class="button button-secondary"><span class="dashicons dashicons-plus" style="vertical-align: middle;"></span>' . __('Add New', 'pixel-cat') . '</button><br>';
	$html .= '</div>';
	
	return $html;
}

//EVENT TABLE ROW TEMPLATE
function fca_pc_event_row_html( $event = array() ) {
	ob_start(); ?>
	<tr id='{{ID}}' class='fca_pc_event_row fca_deletable_item'>
		<td class='fca-pc-json-td' style='display:none;'><input type='hidden' class='fca-pc-input-hidden fca-pc-json' name='fca_pc[event_json][]' value='<?php echo stripslashes_deep( $event ) ?>' /></td>
		<td class='fca-pc-event-td'>{{EVENT}}</td>
		<td class='fca-pc-trigger-td'>{{TRIGGER}}</td>
		<td class='fca-pc-delete-td'><?php echo fca_pc_delete_icons() ?></td>
	</tr>
	<?php
	return ob_get_clean();
}

function fca_pc_sanitize_text_array( $value ) {
	return sanitize_text_field( $value );
}

function fca_pc_settings_save() {
	$data = array();
	
	
	echo '<div id="fca-pc-notice-save" class="notice notice-success is-dismissible">';
		echo '<p><strong>' . __( "Settings saved.", 'pixel-cat' ) . '</strong></p>';
		//echo '<a class="button button-primary" target="_blank" href="https://fatcatapps.com/facebook-pixel-wordpress-plugin/#pixel-helper">' . __( "Verify my pixel", 'pixel-cat' ) . '</a>';
	echo '</div>';	
	
	$data['has_save'] = intval ( $_POST['fca_pc']['has_save'] );
	$data['id'] = fca_pc_bigintval ( $_POST['fca_pc']['id'] );
	$data['events'] = empty( $_POST['fca_pc']['event_json'] ) ? array() : array_map( 'fca_pc_sanitize_text_array', $_POST['fca_pc']['event_json'] );
	
	$data['exclude'] = empty( $_POST['fca_pc']['exclude'] ) ? array() : array_map( 'fca_pc_sanitize_text_array', $_POST['fca_pc']['exclude'] );
	
	
	$data['search_integration'] = empty( $_POST['fca_pc']['search_integration'] ) ? '' : 'on';
	$data['quizcat_integration'] = empty( $_POST['fca_pc']['quizcat_integration'] ) ? '' : 'on';
		
	$data['woo_excluded_categories'] = empty( $_POST['fca_pc']['woo_excluded_categories'] ) ? array() : array_map( 'fca_pc_sanitize_text_array', $_POST['fca_pc']['woo_excluded_categories'] );
	$data['woo_integration'] = empty( $_POST['fca_pc']['woo_integration'] ) ? '' : 'on';
	$data['woo_feed'] = empty( $_POST['fca_pc']['woo_feed'] ) ? '' : 'on';
	
	$data['woo_product_id'] = empty( $_POST['fca_pc']['woo_product_id'] ) ? 'post_id' : $_POST['fca_pc']['woo_product_id'];
	$data['woo_desc_mode'] = empty( $_POST['fca_pc']['woo_desc_mode'] ) ? 'description' : $_POST['fca_pc']['woo_desc_mode'];
	$data['google_product_category'] = empty( $_POST['fca_pc']['google_product_category'] ) ? '' : $_POST['fca_pc']['google_product_category'];
	
	update_option( 'fca_pc', $data );
	
	if ( FCA_PC_DEBUG ) {
		wp_php_log ( $_POST, 'save $_POST' );
		wp_php_log( $data, 'pixel save data' );
	}
	
	return $data;
}


function fca_pc_add_premium_integrations( $options ) {
	
	$search_integration_on = empty( $options['search_integration'] ) ? '' : 'on';
	$quizcat_integration_on = empty( $options['quizcat_integration'] ) ? '' : 'on';
	
	ob_start(); ?>
	<table class='fca_pc_setting_table fca_pc_integrations_table'>
		<?php if ( FCA_PC_PLUGIN_PACKAGE === 'Lite' OR FCA_PC_PLUGIN_PACKAGE === 'Personal' ) { ?>
			<tr>
				<th><?php _e('Track Search Event', 'pixel-cat') ?></th>
				<td><?php echo fca_pc_input( 'search_integration', '', $search_integration_on, 'checkbox' ) ?>
				<span class='fca_pc_hint'><?php _e("Trigger Search event when a search is performed using WordPress' built-in search feature.", 'pixel-cat') ?></span></td>
			</tr>
		<?php } ?>		
		<?php if ( defined('FCA_QC_PLUGIN_PACKAGE') && FCA_QC_PLUGIN_PACKAGE === 'Elite' ) { ?>
		<tr>
			<th><?php _e('Track Quiz Cat Events', 'pixel-cat') ?></th>
			<td><?php echo fca_pc_input( 'quizcat_integration', '', $quizcat_integration_on, 'checkbox' ) ?>
			<span class='fca_pc_hint'><?php _e("Send Lead event, plus custom events related to Quiz engagement to Facebook.", 'pixel-cat') ?> <a target='_blank' href='https://fatcatapps.com/pixelcat/'><?php _e('Learn More...', 'pixel-cat') ?></a></span></td>
		</tr>
		<?php } ?>
	</table>
	<?php
	return ob_get_clean();
}

function fca_pc_woo_product_cat_and_tags() {
	
	$return = array();
	
	$products = get_posts( array( 'post_type' => 'product' ) );
	$tags = get_terms( 'product_tag' );
	$cats = get_terms( 'product_cat' );
		
	forEach ( array_merge( $cats, $tags ) as $obj ) {
		$return[$obj->term_id] = $obj->name;
	}
	return $return;
}

function fca_pc_add_woo_integrations( $options ) {
	
	$version_ok = false;
	$woo_is_active = is_plugin_active( 'woocommerce/woocommerce.php' );
	if ( $woo_is_active ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
			$version_ok = true;
		}
	}
	
	ob_start();
	
	if ( $woo_is_active && $version_ok && FCA_PC_PLUGIN_PACKAGE !== 'Lite' && FCA_PC_PLUGIN_PACKAGE !== 'Personal' ) {
		
		$woo_integration_on = empty( $options['woo_integration'] ) ? '' : 'on';
		$woo_feed_on = empty( $options['woo_feed'] ) ? '' : 'on';
		
		$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
		$id_options = array(
			'post_id' => 'WordPress Post ID (recommended)',
			'sku' => 'WooCommerce SKU',
		);
		
		$woo_desc_mode = empty( $options['woo_desc_mode'] ) ? 'description' : $options['woo_desc_mode'];
		$description_options = array(
			'description' => 'Product Content',
			'short' => 'Product Short Description',
		);
		
		$excluded_categories = empty( $options['woo_excluded_categories'] ) ? array() : $options['woo_excluded_categories'];
		$categories = fca_pc_woo_product_cat_and_tags();
		
		$google_product_category = empty( $options['google_product_category'] ) ? '' : $options['google_product_category'];

		?>
		<div id='fca-pc-woo-table'>
			<h3><?php _e('WooCommerce Integration', 'pixel-cat') ?></h3>
			<table class='fca_pc_integrations_table'>
				<tr>
					<th><?php _e('Track Shopping Events', 'pixel-cat') ?></th>
						<td><?php echo fca_pc_input( 'woo_integration', '', $woo_integration_on, 'checkbox' ) ?>
					<span class='fca_pc_hint'><?php _e("Automatically send the following events to Facebook: Add&nbsp;To&nbsp;Cart, Add&nbsp;Payment&nbsp;Info, Purchase, View&nbsp;Content, Search, Add&nbsp;To&nbsp;Wishlist, and add Advanced Matching for logged in shoppers.", 'pixel-cat') ?></span></td>
				</tr>
				<tr>
					<th><?php _e('Product Feed', 'pixel-cat') ?></th>
						<td><?php echo fca_pc_input( 'woo_feed', '', $woo_feed_on, 'checkbox' ) ?>
					<span class='fca_pc_hint'><?php _e("A Product Feed is required to use Facebook Dynamic Product Ads.", 'pixel-cat') ?></span></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings'>
					<th><?php _e('Feed URL', 'pixel-cat') ?></th>
						<td><input style='height: 35px;' type='text' onclick='this.select()' readonly value='<?php echo get_site_url() . '?feed=pixelcat' ?>' >
					<span class='fca_pc_hint'><?php _e("You'll need above URL when setting up your Facebook Product Catalog.", 'pixel-cat') ?></span></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings'>
					<th><?php _e('Exclude Categories/Tags', 'pixel-cat') ?></th>
						<td><select id='fca-pc-exclude-woo-categories' name='fca_pc[woo_excluded_categories][]' class='fca_pc_multiselect' multiple='multiple' style='width: 100%' >
						<?php			
						forEach ( $categories as $key => $value ) {
							if ( in_array( $key, $excluded_categories ) ) {
								echo "<option value='$key' selected='selected'>$value</option>";
							} else {
								echo "<option value='$key'>$value</option>";
							}
						}?>
						</select>
					<span class='fca_pc_hint'><?php _e("Selected product categories and tags will not be included in your feed.", 'pixel-cat') ?></span></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings'>
					<th><?php _e('Advanced Feed Settings', 'pixel-cat') ?></th>
						<td><?php echo '<span id="fca-pc-show-feed-settings" class="fca-pc-feed-toggle">' . __('(show)', 'pixel-cat') . '</span><span style="display: none;" id="fca-pc-hide-feed-settings" class="fca-pc-feed-toggle">' . __('(hide)', 'pixel-cat') . '</span>' ?></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings fca-pc-woo-advanced-feed-settings' style='display:none;'>
					<th><?php _e('Product Identifier', 'pixel-cat') ?></th>
						<td><select name='fca_pc[woo_product_id]' style='width: 100%' >
						<?php

						forEach ( $id_options as $key => $value ) {
							if ( $woo_id_mode == $key ) {
								echo "<option value='$key' selected='selected'>$value</option>";
							} else {
								echo "<option value='$key'>$value</option>";
							}
						}?>
						</select>
					<span class='fca_pc_hint'><?php _e("Set how to identify your product using the Facebook Pixel (content_id) and the feed (g:id)", 'pixel-cat') ?></span></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings fca-pc-woo-advanced-feed-settings' style='display:none;'>
					<th><?php _e('Description Field', 'pixel-cat') ?></th>
						<td><select name='fca_pc[woo_desc_mode]' style='width: 100%' >
						<?php

						forEach ( $description_options as $key => $value ) {
							if ( $woo_desc_mode == $key ) {
								echo "<option value='$key' selected='selected'>$value</option>";
							} else {
								echo "<option value='$key'>$value</option>";
							}
						}?>
						</select>
					<span class='fca_pc_hint'><?php _e("Set the field to use as your product description for the Facebook product catalog", 'pixel-cat') ?></span></td>
				</tr>
				<tr class='fca-pc-woo-feed-settings fca-pc-woo-advanced-feed-settings' style='display:none;'>
					<th><?php _e('Google Product Category', 'pixel-cat') ?></th>
						<td><?php echo fca_pc_input( 'google_product_category', 'e.g. 2271', $google_product_category, 'text' ) ?>
					<span class='fca_pc_hint'><?php echo __("Enter your numeric Google Product Category ID here.  If your category is \"Apparel & Accessories > Clothing > Dresses\", enter 2271.  ", 'pixel-cat') . '<a href="http://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.xls" target="_blank">' . __("Click here", 'pixel-cat') . '</a> ' . __("for a current spreadsheet of all Categories & IDs.", 'pixel-cat') ?></span></td>
				</tr>
			</table>
		</div>
		<?php return ob_get_clean();
	} else if ( FCA_PC_PLUGIN_PACKAGE === 'Lite' OR ( FCA_PC_PLUGIN_PACKAGE !== 'Personal' && ( !$woo_is_active OR !$version_ok ) ) ) {
		?>
		<div id='fca-pc-woo-table'>
			<h3><?php _e('WooCommerce Integration', 'pixel-cat') ?></h3>
			<h4><?php _e('Requires WooCommerce 3.0.0+ and ', 'pixel-cat') ?><a href='https://fatcatapps.com/pixelcat/woocommerce' target='_blank'><?php _e( 'Pixel Cat Premium', 'pixel-cat' ) ?></a>.</h4>
			<table class='fca_pc_integrations_table fca-pc-integration-disabled'>
				<tr>
					<th><?php _e('Track Shopping Events', 'pixel-cat') ?></th>
						<td><?php echo fca_pc_input( 'woo_integration', '', '', 'checkbox', 'disabled' ) ?>
					<span class='fca_pc_hint'><?php _e("Automatically send the following events to Facebook: Add&nbsp;To&nbsp;Cart, Add&nbsp;Payment&nbsp;Info, Purchase, View&nbsp;Content, Search, Add&nbsp;To&nbsp;Wishlist, and add Advanced Matching for logged in shoppers.", 'pixel-cat') ?></span></td>
				</tr>
				<tr>
					<th><?php _e('Product Feed', 'pixel-cat') ?></th>
						<td><?php echo fca_pc_input( 'woo_feed', '', '', 'checkbox', 'disabled' ) ?>
					<span class='fca_pc_hint'><?php _e("A Product Feed is required to use Facebook Dynamic Product Ads.", 'pixel-cat') ?></span></td>
				</tr>
			</table>
		</div>		
	<?php
		return ob_get_clean();
	}
}
function fca_pc_marketing_metabox() {
	ob_start(); ?>
	<div id='fca-pc-marketing-metabox' style='display: none;'>
		<h3><?php _e( 'Get Pixel Cat Premium', 'pixel-cat' ); ?></h3>

		<ul>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Create Powerful Custom Audiences', 'pixel-cat' ); ?></li>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Track Conversions To The Penny', 'pixel-cat' ); ?></li>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( '1-Click WooCommerce Pixel Setup', 'pixel-cat' ); ?></li>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( 'WooCommerce Dynamic Ads & Product Catalog', 'pixel-cat' ); ?></li>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Full Event Builder', 'pixel-cat' ); ?></li>
			<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Priority Email Support', 'pixel-cat' ); ?></li>
		</ul>
		<div style='text-align: center'>
			<a href="https://fatcatapps.com/pixelcat" target="_blank" class="button button-primary button-large"><?php _e('Upgrade & Boost My Conversions', 'pixel-cat'); ?></a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

function fca_pc_bigintval( $value ) {
	$value = trim($value);

	if ( ctype_digit($value) ) {
		return $value;
	}

	$value = preg_replace("/[^0-9](.*)$/", '', $value);

	if ( ctype_digit($value ) ) {
		return $value;
	}
		return 0;
}