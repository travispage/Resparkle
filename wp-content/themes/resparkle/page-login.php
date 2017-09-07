<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
 *
 * @package WooFramework
 * @subpackage Template
 */

    global $wpdb,$user_ID;   


    /* login */
    /*
    if($_POST['action'] == 'login'){
       
        $username = $wpdb->escape($_REQUEST['username']);
        $password = $wpdb->escape($_REQUEST['password']);

        $login_data = array();
        $login_data['user_login'] = $username;
        $login_data['user_password'] = $password;

        //wp_signon 是wordpress自带的函数，通过用户信息来授权用户(登陆)，可记住用户名
        $user_verify = wp_signon( $login_data, false );

        if ( is_wp_error($user_verify) ) {
            // wp_safe_redirect( esc_url( home_url( '/login' ) ) );
            $login_errors = $user_verify;

        } else { //登陆成功则跳转到首页(ajax提交所以需要用js来跳转)

            echo "<script type='text/javascript'>window.location='".wp_safe_redirect( home_url('login') )."'</script>";
            exit();
        }

    }
    */ 

    if($_POST['action'] == 'login'){

        $match_user = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";

        $username = $wpdb->escape($_REQUEST['username']);
        $password = $wpdb->escape($_REQUEST['password']);

        if( preg_match($match_user, $username) ) {
            $user = get_user_by('email', $username);
        } else {
            $user = get_user_by('login', $username);
        }

        if( !$user ) {
            $login_message = 'No valid name or email / matching password found.';
        } else {
            $data = array();
            $data['user_login'] = $user->data->user_login;
            $data['user_password'] = $password;
            $user =  wp_signon($data, false );
            if( is_wp_error($user) ) {
                $login_errors = $user;
            } else {
                $return_url = $wpdb->escape($_REQUEST['return_url']);
                if($return_url != '') {
                    $return_url = base64_decode($return_url);
                    wp_safe_redirect( $return_url );
                    exit;
                }

                wp_safe_redirect( home_url('login') );
                exit();
            }
        }
    }

    if($_POST['action'] == 'register') {
        $register_status = false;

        $first_name = $wpdb->escape($_POST['first_name']);
        $sur_name = $wpdb->escape($_POST['sur_name']);
        $user_email = $wpdb->escape($_POST['user_email']);
        $re_user_email = $wpdb->escape($_POST['re_user_email']);
        $user_pass = $wpdb->escape($_POST['user_password']);
        $mobile_number = $wpdb->escape($_POST['mobile_number']);

        $user_year = $wpdb->escape($_POST['user_year']);
        $user_month = $wpdb->escape($_POST['user_month']);
        $user_day = $wpdb->escape($_POST['user_day']);

        $birthday = $user_year.'-'.$user_month.'-'.$user_day;

        // Edit by Fred Lin, 26 Sep 2016
        // To change default username to email
        // $array = array(
        //     'user_login' => $first_name,
        //     'user_pass' => md5($user_pass),
        //     'user_email' => $user_email,
        //     'user_nicename' => $first_name,
        //     'display_name' => $first_name,
        //     'user_registered' => date('Y-m-d h:m:s', time())
        //     );        
        $array = array(
            'user_login' => $user_email,
            'user_pass' => md5($user_pass),
            'user_email' => $user_email,
            'user_nicename' => $first_name.'_'.$sur_name,
            'display_name' => $first_name.' '.$sur_name,
            'user_registered' => date('Y-m-d h:m:s', time())
            );

        $errors = new WP_Error();

        if ( ! empty( $first_name ) && ! empty( $user_email )) {

            // Edit by Fred Lin, 26 Sep 2016
            // Change sanitized user login to email instead of first_name
            // $sanitized_user_login = sanitize_user( $first_name );
            $sanitized_user_login = sanitize_user( $user_email );

            /**
             * Filter the email address of a user being registered.
             *
             * @since 2.1.0
             *
             * @param string $user_email The email address of the new user.
             */
            $user_email = apply_filters( 'user_registration_email', $user_email );

            // Check the username
            if ( $sanitized_user_login == '' ) {
                $errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
            } elseif ( ! validate_username( $first_name ) ) {
                $errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
                $sanitized_user_login = '';
            } 
            // Edit by  Fred, 27 Sep 2016
            // Removed check for existing username because it is the same as existing email address.
            // elseif ( username_exists( $sanitized_user_login ) ) {
            //     $errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
            // }

            // Check the e-mail address
            if ( $user_email == '' ) {
                $errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.' ) );
            } elseif ( ! is_email( $user_email ) ) {
                $errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address you\'ve entered is invalid.' ) );
                $user_email = '';
            } elseif ( email_exists( $user_email ) ) {
                $errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email adress is already registered, please choose another one.' ) );
            }

            if($user_email != $re_user_email) {
                $errors->add('emails', __( "<strong>ERROR</strong>: The email addresses you've entered are different." ) );
            }

           
            do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

            $errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );
            if ( $errors->get_error_code() ) {
                // $redirect_to = esc_url( home_url('/login') );
                // print_r($errors);exit;
                // wp_safe_redirect( $redirect_to );
                $registere_errors =  $errors;

            } else {
                $wpdb->insert($wpdb->users, $array);

                $user_id = $wpdb->insert_id;

                if( $user_id) {
                    $user = new WP_User( $user_id );

                    $user->set_role(get_option('default_role'));

                    // duc-nh remove below line to test on localhost and dev server, need to revert it back after upload to live server:
                    // do_action( 'user_register', $user_id );

                    // Updated by Fred 3rd October 2016 to include first and last names to be saved to user meta.
                    update_user_meta( $user_id, 'mobile_number', trim( $mobile_number ) );
                    update_user_meta( $user_id, 'first_name', trim( $first_name ) );
                    update_user_meta( $user_id, 'last_name', trim( $sur_name ) );
                    update_user_meta( $user_id, 'birthday', trim( $birthday ) );

                    if(isset($_POST['sub']) && ! empty($_POST['sub'])) {
                        $user_sub = implode(',', $_POST['sub']);
                        update_user_meta( $user_id, 'user_sub', $user_sub);
                    }
                    /* sent email to the user */
                    update_user_option( $user_id, 'default_password_nag', false, true ); //Set up the Password change nag.

                    wp_send_new_user_notifications($user_id,'user');

                    // duc-nh add to auto log user in
                    wp_set_auth_cookie( $user_id );

                    // $wc_emails = new WC_Email_Customer_New_Account();
                    // $wc_emails->trigger($user_id, '', false);
                    $register_status = true;
                }


                $return_url = $wpdb->escape($_REQUEST['return_url']);
                if($return_url != '') {
                    $return_url = base64_decode($return_url);
                    wp_safe_redirect( $return_url );
                    exit;
                }
            }

        }

    }


	get_header();
	global $woo_options;
?>    
    <?php 
        if ( has_post_thumbnail() ) {
            echo '<div class="masthead">'.the_post_thumbnail().'</div>';
        }
    ?>   
    <div class="title-head orange">
    	<h1 class="sec-title">ABOUT US</h1>
    </div>
    <div id="content" class="page col-full login">

        
            <?php if($user_ID) {?>
                <div class="usered" style="width:100%;text-align:center;font-size:1.6em">
                <h2>Welcome back! You have successfully logged in.</h2>
                </div>
            <?php } ?>

            <?php if(isset($login_message) && !empty($login_message)) {?>
                <div class="usered" style="width:100%;text-align:center;font-size:1.6em">
                <h2><?php echo $login_message; ?></h2>
                </div>
            <?php } ?>

            <?php if(isset($register_status) && ($register_status == true) ) {?>
                <div class="usered" style="width:100%;text-align:center;font-size:1.6em">
                <h2>Congratulations, you have successfully registered.</h2>
                </div>
            <?php } ?>

    
    	<div class="left">
    		<h1>WELCOME</h1>
                        
    		<p>
    			Sign up and log in to shop, share and review Resparkle's organic products. We hope you have a wonderful time with us!
    		</p>

    	</div>

    	<div class="right">
    		<form action="" class="login" method="post" id="wp_login_form">
                <?php if(isset($login_errors)) { ?>
                    <div class="errors"> 
                <?php foreach ($login_errors->get_error_messages() as $error):?>
                    <strong><?php echo $error.'<br>'; ?></strong>
                <?php endforeach; ?>
                </div>
                <?php } ?>
    			<div class="fi">
    				<input type="text" name="username" id="" placeholder="YOUR USERNAME OR EMAIL"/>
    			</div>
    			<div class="fi">
    				<input type="password" name="password" id="" placeholder="PASSWORD"/>
    			</div>
    			<div class="fi">
    				<button name="sub" id="login_sub">LOGIN</button>
    			</div>
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="return_url" value="<?php echo $wpdb->escape($_REQUEST['return_url'])?>">
    		</form>

    		<form action="" class="signup" id="wp_register_form" method="post">
    			<h1>New here? Sign up to save!</h1>
                <?php 
                    if(isset($registere_errors)) { ?>
                    <div class="errors"> <?php
                       foreach ( $errors->get_error_messages() as $error ) {
                            echo $error . '<br/>';
                        } 
                    ?>
                    </div>
                    <?php
                    }
                ?>
    			<div class="two-col">
    				<div class="fi">
    					<input type="text" name="first_name" id="first_name" placeholder="FIRST NAME">
    				</div>
    				<div class="fi">
    					<input type="text" name="sur_name" id="sur_name" placeholder="LAST NAME">
    				</div>
    			</div>
    			<div class="fi">
    				<input type="email" name="user_email" id="user_email" placeholder="EMAIL">
    			</div>
    			<div class="fi">
    				<input type="email" name="re_user_email" id="re_user_email" placeholder="RE-ENTER EMAIL">
    			</div>
    			<div class="fi">
    				<input type="password" name="user_password" id="user_password" placeholder="PASSWORD">
    			</div>
    			<div class="fi bday">
    				<strong>Birthday</strong>
    				<select name="user_day" class="select2 day" data-placeholder="DAY">
                        <?php
                            for($x = 1 ; $x <= 31 ; $x++) { ?>
                                <option value="<?php echo $x;?>"><?php echo $x;?></option>
                        <?php } ?>    					
    				</select>
    				<select name="user_month" class="select2 month" data-placeholder="MONTH">
                        <?php for($y = 1; $y <=12; $y++) { ?>
    					<option value="<?php echo $y;?>"><?php echo $y; ?></option>
                        <?php } ?>
    				</select>
    				<select name="user_year" class="select2 year" data-placeholder="YEAR">
                        <?php for($ye = 1900; $ye < date('Y', time()); $ye++) {?>
    					<option value="<?php echo $ye; ?>"><?php echo $ye; ?></option>
                        <?php } ?>
    				</select>
                    
                    <em class="optional">Optional</em>
    			</div>
    			<div class="fi">
    				<input type="text" name="user_mobile_number" id="user_mobile_number" placeholder="MOBILE NUMBER"> <em class="optional">Optional</em>
    			</div>
    			<div class="fi subscribe icheck">
    				<strong>Subscribe</strong>
                    <input checked type="checkbox" name="_mc4wp_lists[]" id="ss1" value="bf40178674" /> <label for="ss1">Newsletter</label>
                    <input checked type="checkbox" name="_mc4wp_lists[]" id="ss2" value="8359ea4b62" /> <label for="ss2">Blog</label>
    			</div>

    			<span class="terms">By clicking Proceed, you agree to our Terms & Conditions</span>
    			
    			<br class="clear"/>
                <input type="hidden" name="action" value="register">
    			<button id="register_sub" name="re_sub">PROCEED</button>
                <input type="hidden" name="return_url" value="<?php echo $wpdb->escape($_REQUEST['return_url'])?>">
               
    		</form>

    	</div>

    </div><!-- /#content -->
	
    <?php get_footer(); ?>

    <script>
	jQuery(function(){

        jQuery("#login_sub").click(function() {
            
            var input_data = jQuery('#wp_login_form').serialize();   
            
            jQuery.ajax({
                type: "POST",   
                url:  '<?php echo "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>',
                data: input_data,
                success: function(msg){   
                    // alert('ssss');
                },
                error:function(msg) {

                }   
            }); 
            // return false;
        });


        jQuery('#register_sub').on('click', function(e) {
            e.preventDefault();

            var sub = '';

            var first_name = jQuery.trim(jQuery('#first_name').val());
            var sur_name = jQuery.trim(jQuery('#sur_name').val());
            var user_email = jQuery.trim(jQuery('#user_email').val());
            var re_user_email = jQuery.trim(jQuery('#re_user_email').val());
            var mobile_number = jQuery.trim(jQuery('#user_mobile_number').val());
          
            if(user_email == re_user_email) {

                if( first_name.length == 0 )
                    sub += 'First Name, ';

                if( sur_name.length == 0 )
                    sub += 'Last Name, ';

                if( user_email.length == 0 )
                    sub += 'and Email';

                if(sub.length > 0) {
                    alert('Your '+sub+' are required.');
                } else {
                    jQuery('#wp_register_form').submit();
                }

            } else {
                alert('The 2 emails are different!');
            }

        });

	});
    </script>

<?php 
