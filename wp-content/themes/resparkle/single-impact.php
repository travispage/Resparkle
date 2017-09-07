<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: Our Impact Article Template
 *
 * @package WooFramework
 * @subpackage Template
 */

	global $woo_options;
    $title = get_the_title();
?>    
    
    
    <div id="content" class="page col-full our-impact">
        
        <section id="main">            
           <?php
            if ( have_posts() ) { 
                while ( have_posts() ) { the_post(); 
           ?>                                                           
               <div <?php post_class(); ?>>
                   <div class="impact-cover">
                   <?php if (class_exists('MultiPostThumbnails')) :
                       MultiPostThumbnails::the_post_thumbnail(
                           'Impact',
                           'post-image'                           
                       );
                   endif; ?>
                   <?php $post_id = get_the_id();?>
                   <div class="et_social_inline et_social_mobile_on et_social_inline_bottom">
                      <div class="et_social_networks et_social_autowidth et_social_slide et_social_rounded et_social_left et_social_no_animation et_social_withcounts et_social_withnetworknames et_social_outer_dark">                            
                        <ul class="et_social_icons_container">
                          <li class="et_social_facebook"><a target="_blank" href="http://www.facebook.com/sharer.php?u=http://resparkle.com.au/resparkle-movement/our-impact/%23<?php echo $post_id;?>" class="et_social_share et_social_display_count" rel="nofollow" data-social_name="facebook" data-post_id="<?php echo $post_id;?>" data-social_type="share" data-min_count="0"><i class="et_social_icon et_social_icon_facebook"></i><div class="et_social_network_label"><div class="et_social_networkname">Facebook</div></div><span class="et_social_overlay"></span></a></li>
                          <li class="et_social_twitter"><a target="_blank" href="http://twitter.com/share?text=Resparkle&amp;url=http://resparkle.com.au/resparkle-movement/our-impact/%23<?php echo $post_id;?>&amp;via=resparkle" class="et_social_share et_social_display_count" rel="nofollow" data-social_name="twitter" data-post_id="<?php echo $post_id;?>" data-social_type="share" data-min_count="0"><i class="et_social_icon et_social_icon_twitter"></i><div class="et_social_network_label"><div class="et_social_networkname">Twitter</div></div><span class="et_social_overlay"></span></a></li>
                          <?php /* <li class="et_social_pinterest"><a href="#" class="et_social_share_pinterest et_social_display_count" rel="nofollow" data-social_name="pinterest" data-post_id="<?php echo $post_id;?>" data-social_type="share" data-min_count="0"><i class="et_social_icon et_social_icon_pinterest"></i><div class="et_social_network_label"><div class="et_social_networkname">Pinterest</div></div><span class="et_social_overlay"></span></a></li> */ ?>
                        </ul>
                      </div>
                        </div>
                   </div>
                   <div class="impact-body">
                    <h1><?php echo the_title();?></h1>
                    <strong class="date"><?php echo the_date();?></strong>
                    <?php the_content(); ?>
                    </div>
                    <div class="impact-supp">
                   <?php if (class_exists('MultiPostThumbnails')) :
                       MultiPostThumbnails::the_post_thumbnail(
                           'Impact',
                           'supp-image'                           
                       );
                   endif; ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>                    
                   
               </div><!-- /.post -->
               
               <?php              

                } // End WHILE Loop
            } else {
        ?>
            <div <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
               </div><!-- /.post -->
           <?php } // End IF Statement ?> 
        </section><!-- /#main -->

        <?php woo_main_after(); ?>

    </div><!-- /#content -->
		