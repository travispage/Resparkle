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
	get_header();
	global $woo_options;
?>    
    <?php 
        if ( has_post_thumbnail() ) {
            echo '<div class="masthead">'.the_post_thumbnail().'</div>';
        }
    ?>   
    <div class="title-head blue">
    	<h1 class="sec-title">SUBSCRIBE</h1>
    </div>
    <div id="content" class="page col-full subscribe-page">
    
    	<div class="left">
    		<h1>STAY CONNECTED</h1>

    		<p>
    			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.
    		</p>

    		<p>
    			Duis vulputate commodo lectus, ac blandit elit tincidunt id. 
    			Sed rhoncus, tortor sed eleifend tristique, tortor mauris molestie elit, et lacinia ipsum quam nec dui.
    		</p>
    	</div>

    	<div class="right">
    		<form action="" class="subscribe-form">
                <div class="fi subscribe icheck">
                    <strong>Subscribe</strong>
                    <input type="checkbox" name="sub" id="ss1" class="checkbox"><label for="ss1">Newsletter</label>
                    <input type="checkbox" name="sub" id="ss2" class="checkbox"><label for="ss2">Blog</label>
                </div>
    			<div class="fi">
    				<input type="email" name="email" id="" placeholder="EMAIL"/>
    			</div>
    			<div class="fi">
                    <input type="email" name="email2" id="" placeholder="RE-ENTER EMAIL"/>
                </div>
    			<div class="fi">
    				<button>CONFIRM</button>
    			</div>
    		</form>

    	</div>

    </div><!-- /#content -->
		
<?php get_footer(); ?>

<script>
	$(function(){
		
		jQuery('.icheck input').iCheck({
			checkboxClass: 'icheckbox_minimal-orange',
			radioClass: 'iradio_minimal-orange',
			increaseArea: '20%'
		});
		
	})
</script>