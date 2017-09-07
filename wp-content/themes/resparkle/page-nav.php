<?php
$blog_url = get_bloginfo('url') . '/';
?>
<div class="nav stuck" id="main-nav">
    <ul>
        <li>
            <a href="<?php echo $blog_url; ?>">
                <span class="icon-home"></span>
                <strong>HOME</strong>
            </a>
        </li>
        <li>
            <a href="<?php echo $blog_url; ?>product-category/bestsellers">
                <span class="icon-shop"></span>
                <strong>SHOP</strong>
            </a>
            <div class="sub-menu">
                <ul>
                    <li><a href="<?php echo $blog_url; ?>product-category/bestsellers">SHOP ONLINE</a></li>
                    <li><a href="<?php echo $blog_url; ?>product-category/specials">SPECIALS</a></li>
                    <li><a href="<?php echo $blog_url; ?>online-shops">ONLINE SHOPS</a></li>
                    <li><a href="<?php echo $blog_url; ?>retailers">RETAILERS</a></li>
                </ul>
            </div>
        </li>
        <li>
            <a href="<?php echo $blog_url; ?>about-us">
                <span class="icon-about"></span>
                <strong>ABOUT US</strong>
            </a>
            <ul class="sub-menu">
                <li><a href="<?php echo $blog_url . 'about-us/'; ?>">Mission</a></li>
                <li><a href="<?php echo $blog_url . 'about-us/story/'; ?>">Story</a></li>
                <li><a href="<?php echo $blog_url . '/about-us/innovations/packaging'; ?>">Innovations</a></li>
                <li><a href="<?php echo $blog_url . 'about-us/certifications/'; ?>">certifications</a></li>
                <li><a href="<?php echo $blog_url . 'about-us/customer-testimonials/'; ?>">Testimonials</a></li>
                <li><a href="<?php echo $blog_url . 'about-us/in-the-press/'; ?>">In The Press</a></li>
                <li><a href="<?php echo $blog_url . 'about-us/blog/'; ?>" target="_blank">Blog</a></li>
                <!--<li><a href="http://livehealthyeveryday.org" target="_blank">Blog</a></li>-->
            </ul>
        </li>
        <li>
            <a href="<?php echo $blog_url; ?>resparkle-movement/">
                <span class="icon-movement"></span>
                <strong>RESPARKLE MOVEMENT</strong>
            </a>
            <ul class="sub-menu">
                <li><a href="<?php echo get_bloginfo('url') . '/resparkle-movement/'; ?>">What is it</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/resparkle-movement/how-it-works/'; ?>">How it works</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/resparkle-movement/our-impact'; ?>">Our Impact</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/resparkle-movement/get-involved/'; ?>">Get Involved</a></li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $blog_url; ?>customer-service/faq">
                <span class="icon-service"></span>
                <strong>CUSTOMER SERVICE</strong>
            </a>
            <ul class="sub-menu">
                <li><a href="<?php echo get_bloginfo('url') . '/customer-service/faq'; ?>">FAQ</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/customer-service/shipping'; ?>">SHIPPING</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/customer-service/return-policy'; ?>">RETURN POLICY</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/customer-service/ingredients'; ?>">INGREDIENTS</a></li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $blog_url; ?>contact">
                <span class="icon-contact"></span>
                <strong>CONTACT US</strong>
            </a>
            <ul class="sub-menu">
                <li><a href="<?php echo get_bloginfo('url') . '/contact/'; ?>">Customers</a></li>
                <li><a href="<?php echo get_bloginfo('url') . '/contact/wholesalers-distributors'; ?>">Wholesalers / &nbsp;&nbsp;&nbsp;&nbsp;Distributors</a>
                </li>
                <li><a href="<?php echo get_bloginfo('url') . '/contact/bloggers-affiliates'; ?>">Bloggers / &nbsp;&nbsp;&nbsp;&nbsp;Affiliates</a>
                </li>
                <li><a href="<?php echo get_bloginfo('url') . '/contact/media'; ?>">Media</a></li>
            </ul>
        </li>
    </ul>
</div>