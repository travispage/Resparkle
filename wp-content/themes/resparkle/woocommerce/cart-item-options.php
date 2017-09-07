<?php
/**
 * Cart Item Subscription Options Template.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/cart-item-options.php'.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><ul class="wcsatt-convert"><?php

	foreach ( $options as $option ) {
		?><li>
			<label>
				<input type="radio" name="cart[<?php echo $cart_item_key; ?>][convert_to_sub]" value="<?php echo $option[ 'id' ] ?>" <?php checked( $option[ 'selected' ], true, true ); ?> />
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

?></ul>
