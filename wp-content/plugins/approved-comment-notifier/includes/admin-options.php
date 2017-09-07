<?php
require_once './admin.php';
if ( !current_user_can('edit_posts') )
    wp_die(__('Cheatin&#8217; uh?'));
?>
<div class="wrap">
    <div id="icon-tools" class="icon32 icon32-posts-deprecated_log"><br></div><?php echo "<h2>".__('Approved Comment Notifier')."</h2>";?>
    <form method="post" action="options.php" class="rc_acn_options_form">
        <?php settings_fields( 'rc_acn_plugin_options' ); ?>
        <?php $options = get_option( 'rc_acn_options' );?>

       <h3><?php echo __('Email Content', 'rc_acn'); ?></h3>
       
       <p>
       <?php
       $email_description = __('Indicate here the content of the email to send to comments authors. Use these variables to construct your message.', 'rc_acn').'<br />';
       $email_description .= __('They will be replaced by the corresponding values in the email.', 'rc_acn');
       $email_description .= '<p><strong>{comment_author}</strong> : '.__('will output comment author name.', 'rc_acn').'</p>';
       $email_description .= '<p><strong>{comment_date}</strong> : '.__('will output comment date.', 'rc_acn').'</p>';
       $email_description .= '<p><strong>{commented_post_name}</strong> : '.__('will output commented post name.', 'rc_acn').'</p>';
       $email_description .= '<p><strong>{commented_post_url}</strong> : '.__('will output commented post url.', 'rc_acn').'</p>';
       $email_description .= '<p><strong>{site_name}</strong> : '.__('will output your site name.', 'rc_acn').'</p>';
       $email_description .= '<p><strong>{site_url}</strong> : '.__('will output your site url.', 'rc_acn').'</p>';
       
       
       echo $email_description;
       ?>
       </p>
       
       <div id="rc_acn_editor">
       <?php 
			if( function_exists('wp_editor') ) {
				$html = wp_editor( $options['email'], 'rc_acn_options_[email]', array( 'textarea_name' => 'rc_acn_options[email]' ) );
			} else {
				$html = '<textarea class="large-text" rows="10" id="rc_acn_email" name="rc_acn_options[email]">' . esc_textarea( $options['email'] ) . '</textarea>';
			}
		
			echo $html;
       	?>
       </div>

        <h3><?php echo __('Email Configuration', 'rc_acn'); ?></h3>
        <ul>
            <li><label for="rc_acn_from_name"><?php echo __('From Name', 'rc_acn'); ?>: </label>
                <input name="rc_acn_options[from_name]" id="rc_acn_from_name" type="text" value="<?php if ( isset( $options['from_name'] ) ) echo $options['from_name']; ?>" />
            </li>
            <li><label for="rc_acn_from_email"><?php echo __('From Email', 'rc_acn'); ?>: </label>
                <input name="rc_acn_options[from_email]" id="rc_acn_from_email" type="text" value="<?php if ( isset( $options['from_email'] ) ) echo $options['from_email']; ?>" />
            </li>
            <li><label for="rc_acn_subject"><?php echo __('Email Subject', 'rc_acn'); ?>: </label>
                <input name="rc_acn_options[subject]" id="rc_acn_subject" type="text" value="<?php if ( isset( $options['subject'] ) ) echo $options['subject']; ?>" />
            </li>
       </ul>
       


        <?php submit_button();?>
    </form>
</div>
