<?php
/**
 * Single-Product Subscription Options Template.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/product-options.php'.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
echo '<div class="freq">';
if ($prompt) {
    echo $prompt;
} else {
    ?><h3><?php
    echo 'Select a delivery frequency:';
    ?></h3><?php
}

?>
<ul class="wcsatt-convert-product"><?php

    foreach ($options as $option) {
        ?>
        <li>
        <label>
            <input type="radio" name="convert_to_sub_<?php echo $product->id; ?>"
                   value="<?php echo $option['id']; ?>" <?php checked($option['selected'], true, true); ?> />
             <?php

            $opt_desc = $option['description'];
            $opt_desc = strtolower($opt_desc);

            $opt_desc = str_replace('every month', 'Monthly', $opt_desc);
            $opt_desc = str_replace('every 2 months', 'Bi-Monthly', $opt_desc);

            echo $opt_desc;
            ?>
        </label>
        </li><?php
    }

    ?></ul></div>
