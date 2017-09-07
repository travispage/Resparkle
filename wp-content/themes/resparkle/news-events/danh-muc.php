<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Page Template
 * 
 * Template Name: About Page Template
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
    $title = get_the_title();
?>    
   <?php 
        if ( has_post_thumbnail() ) { ?>
            <div class="masthead">
                <img src="<?php echo get_template_directory_uri(); ?>/images/Maskhead_AboutUs.jpg">
            </div>
        <?php
        }
    ?>
    <div class="cat-nav orange">        
        <h1 class="sec-title">ABOUT US</h1>     
        <ul>            
            <li class="toggleBtn"><span class="icon-down-open"></span></li>
            <li <?php echo ($title == 'About Us' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/about-us/';?>">Mission</a></li>
            <li <?php echo ($title == 'Story' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/story/';?>">Story</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/innovations/packaging';?>">Innovations</a></li>
            <li <?php echo ($title == 'Certifications' ? 'class="active"' : '');?>><a href="<?php echo get_bloginfo('url').'/certifications/';?>">certifications</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/customer-testimonials/';?>">customer testimonials</a></li>
            <li><a href="<?php echo get_bloginfo('url').'/about-us/in-the-press/';?>">In The Press</a></li>
            <li><a target="_blank" href="http://livehealthyeveryday.org/">Blog</a></li>
        </ul>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/masonry.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/app.js"></script>

    <div class="_news-and-press _all batv_news">
        <div id="container">
            <div class="container">
                <div class="section" id="main">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row" id="masonry">
                                <div class="col-xs-12 col-sm-4" id="grid-sizer"></div>
                                    <?php
                                        $queried_object = get_queried_object();
                                        $term_slug = $queried_object->slug;
                                        $term_name= $queried_object->name;
                                        $term_ids =$queried_object->term_id;
                                        $description=$queried_object->description;
                                        $taxonomy=$queried_object->taxonomy;
                                    ?>
                                    <?php    
                                        $query = new WP_Query(array('post_type' => 'news-events',
                                                                     'tax_query' => array(
                                                                        array(
                                                                            'taxonomy' => $taxonomy,
                                                                            'field' => 'slug',
                                                                            'terms' => $term_slug,
                                                                        )),
                                                                    'paged' => (get_query_var('paged') ? get_query_var('paged') : 1)
                                                                    )
                                                            );
                                        $k=1;
                                        while($query->have_posts()) {
                                            $query->the_post();
                                            $imgUrl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                                            $image_1 = get_field('image_popup');
                                            $image_2 = get_field('image_popup_2');

                                    ?>
                                            <div class="col-xs-12 col-sm-4">
                                                <div class="item press masonry">
                                                    <a href="javascript: void(0);" data-toggle="modal" data-target=".pop-up-<?php echo $k; ?>"><img src="<?php echo  $imgUrl; ?>" class="img-responsive" /></a>
                                                    <div><?php the_title(); ?></div>
                                                </div>
                                            </div>
                                            <div class="modal fade pop-up-<?php echo $k; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-<?php echo $k; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                        
                                                         <div class="close" data-dismiss="modal">
                                                             <img src="<?php echo get_template_directory_uri()."/images/modal.png" ?>">
                                                       
                                                         </div>
                                                          <h4 class="modal-title align-center" id="myLargeModalLabel-<?php echo $k; ?>"><?php the_title(); ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="<?php echo $image_1['url']; ?>" alt="<?php echo $image_1['alt']; ?>"  class="img-responsive"/>
                                                        <img src="<?php echo $image_2['url']; ?>" alt="<?php echo $image_2['alt']; ?>"  class="img-responsive"/>
                                                    </div>
                                                  </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal mixer image -->
                                    <?php
                                        $k++;
                                        } 

                                    ?>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        <style type="text/css">
            .batv_news .img-responsive{
                display: initial  !important;
            }
            .batv_news .modal-content{ 
                width: 76.5%;margin: 0 auto;
             }
             .batv_news .modal{ z-index: 99999; }
             .batv_news .modal-title{ text-align: center;margin-top: 10px; }
            .batv_news .close {
                position: absolute;
                margin-top: -2px;
                right: -15px;
                top: -15px;
            }
            .batv_news .modal-content{ background: #f2f2f2; }
            .batv_news .modal-title{ color: #6c6c6c; font-size: 20px; text-transform: uppercase;}
        </style>




<?php get_footer();  ?>