<?php get_header(); ?>
<?php global $batv_options; ?>
<div id="containerper">
<?php require_once LMCIT_THEME_BLOCK_DIR . '/header.php';?>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-570b1584bde44239"></script> 
<div class="container">
    <div class="row">
      <div class="sidebar col-sm-3">
          <div class="sidebar-content">
            <div id="nav_menu-2" class="widget widget_nav_menu ">
                <div class="widgettitle-all ttl-sidebarchildx"><i class="fa fa-bars" aria-hidden="true"></i>  Danh mục sản phẩm</div>
                <div class="menu-menunew-container">
                  <?php 
                      $defaults = array(
                          'theme_location'  => 'product-menu',
                          'menu'            => '',
                          'menu_class'      => 'menu',
                          'menu_id'         => 'menu-menunew',
                          'echo'            => true,
                          'fallback_cb'     => 'wp_page_menu',
                          'before'          => '',
                          'after'           => '',
                          'link_before'     => '',
                          'link_after'      => '',
                          'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                          'depth'           => 0,
                          'walker'          => '',
                      );
                      wp_nav_menu($defaults); 
                      ?>
                </div>
            </div>
          </div>
      </div>
      <div class="col-sm-12 col-md-9">
          <?php
            if(have_posts()){ 
              while (have_posts()){
                the_post();
          ?>
                <div class="breadcrumb">
                  <?php echo (function_exists('taxonomy_breadcrumbs')?taxonomy_breadcrumbs():''); ?>
                </div>   
                <div class="box_detail_products">
                    <h1 class="title"><?php the_title(); ?></h1>
                    <div class="network_product">
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-570b1584bde44239"></script> 
                        <div class="addthis_native_toolbox clearfix"></div>
                    </div>
                    <div class="detail">
                      <?php the_content(); ?>
                    </div>  
                </div>    
          <?php 
              }
            }
          ?> 
      </div>

    </div>
</div>

<?php get_footer(); ?>









