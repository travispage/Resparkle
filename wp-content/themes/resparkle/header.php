<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $woocommerce;
 
 $assets_uri = get_template_directory_uri().'/';

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if(is_home()) { ?>
<meta property="og:image" content="<?php echo $assets_uri;?>assets/img/resparkle-hero.jpg"/>
<?php } ?>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<script>
	if(navigator.userAgent.match(/Android/i)
  || navigator.userAgent.match(/webOS/i)
  || navigator.userAgent.match(/iPhone/i)
  || navigator.userAgent.match(/iPod/i)
  || navigator.userAgent.match(/BlackBerry/i)
  || navigator.userAgent.match(/Windows Phone/i)) {
    document.write('<meta name="viewport" content="width=device-width, user-scalable=no" />');
}
</script>
<title><?php woo_title( '' ); ?></title>
<?php woo_meta(); ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php
wp_head();
woo_head();
$blog_url = get_bloginfo('url').'/';
?>
<? /* Museo Sans font from Typekit */ ?>
<script src="//use.typekit.net/vlb0qua.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
</head>
<body <?php body_class(); ?>>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KK3ZC9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KK3ZC9');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P7N6DX');</script>
<!-- End Google Tag Manager -->

	<?php do_action('wp_after_body');?>
	<div class="top-nav">
		<div class="wrapper">
			<a href="<?php echo get_bloginfo('url');?>" class="logo">
				<img src="<?php echo $assets_uri;?>/assets/img/logo.png">
			</a>					
			<a href="#" class="ham"><span class="icon-menu"></span></a>			
			<ul class="links">
				<?php
					$title = strtolower( get_the_title() );
				?>
				<?php if(!is_user_logged_in()) {?>
				<li <?php echo ($title == 'login' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'login' ) ) ); ?>" >LOGIN</a>
					</div>
				</li>
				<?php } else { ?>
				<li <?php echo ($title == 'logout' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo wp_logout_url( home_url() );  ?>" >LOGOUT</a>
					</div>
				</li>
				<li <?php echo ($title == 'my_accout' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo home_url( '/my-account' ); ?>" >MY ACCOUNT</a>
					</div>
				</li>
				<?php } ?>


                <li <?php echo ($title == 'bundles' || $title == 'how it works' ? 'class="active"' : '');?>>
                    <div class="curr-menu-3">
                        <a href="<?php echo $blog_url;?>bundles">SUBSCRIBE &amp; SAVE</a>
                    </div>
                </li>


				<li <?php echo ($title == 'specials' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo $blog_url;?>product-category/specials">SPECIALS</a>
					</div>
				</li>
				<li <?php echo ($title == 'help' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo $blog_url;?>customer-service/faq/">HELP</a>
					</div>
				</li>
				<li <?php echo ($title == 'cart' ? 'class="active"' : '');?>>
					<div class="curr-menu-3">
						<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'cart' ) ) ); ?>">MY CART
							<?php echo '(<span class="cart_count">'.$woocommerce->cart->cart_contents_count.'</span>)';?>
						 	<?php /*('.$woocommerce->cart->get_cart_total().')';?> */?></a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	
	<div id="mobile-nav">
		<ul>			
			<li>
				<a href="<?php echo $blog_url;?>">
					<span class="icon-home"></span>	<strong>HOME</strong>
				</a>
			</li>
			<li>						
				<a href="<?php echo $blog_url;?>product-category/bestsellers">
					<span class="icon-shop"></span> <strong>SHOP</strong>
				</a>
			</li>
			<li>						
				<a href="<?php echo $blog_url;?>about-us">
					<span class="icon-about"></span> <strong>ABOUT US</strong>
				</a>
			</li>
			<li>
				<a href="<?php echo $blog_url;?>resparkle-movement/">
					<span class="icon-movement"></span> <strong>RESPARKLE MOVEMENT</strong>
				</a>
			</li>
			<li>						
				<a href="<?php echo $blog_url;?>customer-service/faq">
					<span class="icon-service"></span> <strong>CUSTOMER SERVICE</strong>
				</a>
			</li>
			<li>
				<a href="<?php echo $blog_url;?>contact">
					<span class="icon-contact"></span> <strong>CONTACT US</strong>
				</a>		        
			</li>
			<?php if(!is_user_logged_in()) {?>
			<li class="red" <?php echo ($title == 'login' ? 'class="active"' : '');?>><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'login' ) ) ); ?>" >LOGIN</a></li>
			<?php } else { ?>
			<li class="red" <?php echo ($title == 'logout' ? 'class="active"' : '');?>><a href="<?php echo wp_logout_url( home_url() );  ?>" >LOGOUT</a></li>
			<li class="red" <?php echo ($title == 'my_accout' ? 'class="active"' : '');?>><a href="<?php echo home_url( '/my-account' ); ?>" >MY ACCOUNT</a></li>
			<?php } ?>
<li class="red" <?php echo ($title == 'bundles' || $title == 'how it works' ? 'class="active"' : '');?>><a href="<?php echo $blog_url;?>bundles">SUBSCRIBE &amp; SAVE</a></li>
			<li class="red" <?php echo ($title == 'specials' ? 'class="active"' : '');?>><a href="<?php echo $blog_url;?>product-category/specials">SPECIALS</a></li>
			<li class="red" <?php echo ($title == 'help' ? 'class="active"' : '');?>><a href="<?php echo $blog_url;?>customer-service/faq/">HELP</a></li>
			<li class="red" <?php echo ($title == 'cart' ? 'class="active"' : '');?>><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'cart' ) ) ); ?>">MY CART
				<?php echo '(<span class="cart_count">'.$woocommerce->cart->cart_contents_count.'</span>)';?>
				 <?php /*('.$woocommerce->cart->get_cart_total().')';?> */?></a>
			</li>
		</ul>		
	</div>
	
	<div class="main">
	
	<?php if(! is_home()) { ?>
	
		<?php get_template_part('page', 'nav'); ?>

	<?php } ?>