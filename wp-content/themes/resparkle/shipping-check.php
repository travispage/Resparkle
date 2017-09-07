<?php
if (!defined('ABSPATH')) exit;
/**
 * Page Template
 *
 * Template Name: Shipping Check Template
 *
 * @package WooFramework
 * @subpackage Template
 */
get_header();
global $woo_options;
$title = get_the_title();
?>
    <div style="height: 30px"></div>

    <a name="title"></a>
    <section id="main">
        <div id="content" class="page col-full">

            <?php
            if (have_posts()) {
                $count = 0;
                while (have_posts()) {
                    the_post();
                    $count++;
                    ?>
                    <article <?php post_class(); ?>>

                        <section class="entry">

                            <?php the_content(); ?>

                            <!-- Postage Check Calculator Start -->
                            <form method="get">
                                <label for="postcode">Enter your Australian Post Code:</label> <input type="number"
                                                                                                      id="postcode"
                                                                                                      name="postcode">
                                <input type="submit" class="postage-btn" value="Go!">
                            </form>

                            <?php
                            if (isset($_GET['postcode'])) {

                                $postcode = sanitize_text_field($_GET['postcode']);

                                echo '<div id="postcoderesult" style="padding-top: 25px">';

                                if (strlen($postcode) == 3 || strlen($postcode) == 4) {
                                    echo '<h2>Postage Costs for Postcode ' . $postcode . '</h2>';


                                    $zone_query = get_option('be_woocommerce_shipping_zones');
                                    $zone_orders = array();
                                    if (count($zone_query)) {
                                        foreach ($zone_query as $value) {

                                            // Reset our check variables
                                            $excluded = 0;
                                            $match = 0;
                                            $sub_bun = 0;

                                            // Match Country
                                            if (strpos($value['zone_country'], 'AU') !== false) {
                                                // Result is in Australia

                                                //Check to see if our postcode is in the exclusion list
                                                $excluded_postcodes = explode(',', $value['zone_except']);
                                                foreach ($excluded_postcodes as $excluded_postcode) {
                                                    //Check to see if we have a '-' or not
                                                    if (strpos($excluded_postcode, '-') !== false) {
                                                        // We have a range, see if we are in it
                                                        $range = explode('-', $excluded_postcode);
                                                        if ($postcode >= $range[0] && $postcode <= $range[1]) {
                                                            $excluded = 1;
                                                        }

                                                    } else {
                                                        // We have an individual number, match directly against it
                                                        if ($postcode == $excluded_postcode) {
                                                            $excluded = 1;
                                                        }
                                                    }
                                                }

                                                if ($excluded == 0) {
                                                    // We are not in the excluded ranges, see if we match the ranges
                                                    // Check to see if our postcode is in the zone_postal list

                                                    $allowed_postcodes = explode(',', $value['zone_postal']);
                                                    foreach ($allowed_postcodes as $allowed_postcode) {

                                                        //echo 'Allowed Postcodes: '.$allowed_postcode.'<br />';
                                                        //Check to see if we have a '-' or not
                                                        if (strpos($allowed_postcode, '-') !== false) {
                                                            // We have a range, see if we are in it
                                                            $range = explode('-', $allowed_postcode);
                                                            if ($postcode >= $range[0] && $postcode <= $range[1]) {
                                                                // Match
                                                                $match = 1;
                                                            }

                                                        } else {
                                                            // We have an individual number, match directly against it
                                                            if ($postcode == $allowed_postcode) {
                                                                // Match
                                                                $match = 1;
                                                            }
                                                        }
                                                    }


                                                    if ($match == 1) {
                                                        //print_r($value);

                                                        $rate_query = get_option('woocommerce_table_rates');
                                                        $rate_orders = array();
                                                        if (count($rate_query)) {
                                                            foreach ($rate_query as $value_rate) {
                                                                if ($value['zone_id'] == $value_rate['zone']) {
                                                                    if ($sub_bun == 0) {
                                                                        if (strpos($value_rate['title'], 'Regional') !== false) {
                                                                            // Result is a remote location
                                                                            echo 'Shipping for Subscription Bundles is $9 (regional surcharge).<br />';
                                                                        } else {
                                                                            echo 'Shipping for Subscription Bundles is FREE. <br />';
                                                                        }
                                                                        $sub_bun = 1;
                                                                    }


                                                                    if (strpos($value_rate['title'], 'Regional') !== false) {
                                                                        // Regional
                                                                        if ($value_rate['min'] === '0') {
                                                                            echo 'Shipping for Non-Subscription orders up to $' . $value_rate['max'] . ' is a flat fee of $' . $value_rate['cost'] . ' ($9.95 plus $9 regional surcharge).<br />';
                                                                        }
                                                                        if ($value_rate['max'] === '*') {
                                                                            if (empty($value_rate['cost'])) {
                                                                                echo 'Shipping for Non-Subscription orders over $' . round($value_rate['min']) . ' is FREE.<br />';
                                                                            } else
                                                                                echo 'Shipping for Non-Subscription orders over $' . round($value_rate['min']) . ' is a flat fee of $' . $value_rate['cost'] . '(regional surcharge).<br />';
                                                                        }
                                                                    } else {
                                                                        //Metro
                                                                        if ($value_rate['min'] === '0') {
                                                                            echo 'Shipping for Non-Subscription orders up to $' . $value_rate['max'] . ' is a flat fee of $' . $value_rate['cost'] . '.<br />';
                                                                        }
                                                                        if ($value_rate['max'] === '*') {
                                                                            if (empty($value_rate['cost'])) {
                                                                                echo 'Shipping for Non-Subscription orders over $' . round($value_rate['min']) . ' is FREE.<br />';
                                                                            } else
                                                                                echo 'Shipping for Non-Subscription orders over $' . round($value_rate['min']) . ' is a flat fee of $' . $value_rate['cost'] . '.<br />';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $zone_orders[$value['zone_id']] = $n;
                                            $n++;

                                            // Reset our check variables
                                            $excluded = 0;
                                            $match = 0;
                                            $remote = 0;
                                        }
                                    }

                                } else {
                                    echo '<h2 style="color: red">Error - invalid postcode supplied</h2>';
                                }
                            }
                            echo '</div>';
                            ?>

                            <!-- Postage Check Calculator End -->

                            <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'woothemes'))); ?>
                        </section><!-- /.entry -->

                    </article><!-- /.post -->

                    <?php
                    // Determine wether or not to display comments here, based on "Theme Options".
                    if (isset($woo_options['woo_comments']) && in_array($woo_options['woo_comments'], array('page', 'both'))) {
                        comments_template();
                    }

                } // End WHILE Loop
            } else {
                ?>
                <article <?php post_class(); ?>>
                    <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes'); ?></p>
                </article><!-- /.post -->
            <?php } // End IF Statement ?>

    </section><!-- /#main -->

<?php woo_main_after(); ?>

    <br class="clear"/>

    </div>
    </div><!-- /#content -->


<?php get_footer(); ?>