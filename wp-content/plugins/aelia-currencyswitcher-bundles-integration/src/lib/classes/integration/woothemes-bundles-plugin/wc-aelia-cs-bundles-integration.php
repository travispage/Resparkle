<?php
namespace Aelia\WC\CurrencySwitcher\Bundles;
if(!defined('ABSPATH')) exit; // Exit if accessed directly

use \WC_Aelia_CurrencySwitcher;
use \WC_Aelia_CurrencyPrices_Manager;
use \WC_Bundles_Product;
use \WC_Product;
use \WC_Product_Bundle;

/**
 * Implements support for WooThemes Bundles plugin.
 *
 * @since 1.0.0.151213
 */
class Bundles_Integration {
	const FIELD_BASE_REGULAR_CURRENCY_PRICES = '_bundle_base_currency_prices';
	const FIELD_BASE_SALE_CURRENCY_PRICES = '_bundle_base_sale_currency_prices';

	// @var WC_Aelia_CurrencyPrices_Manager The object that handles Currency Prices for the Products.
	protected static $_currencyprices_manager;

	// @var WC_Aelia_CurrencySwitcher The Currency Switcher instance .
	protected static $_currency_switcher;

	// @var string The shop's base currency
	protected static $_base_currency;
	// @var string The active currency
	protected static $_selected_currency;

	/**
	 * Returns the instance of the Currency Switcher plugin.
	 *
	 * @return WC_Aelia_CurrencySwitcher
	 */
	protected static function cs() {
		if(empty(self::$_currency_switcher)) {
			self::$_currency_switcher = WC_Aelia_CurrencySwitcher::instance();
		}
		return self::$_currency_switcher;
	}

	/**
	 * Returns the instance of the currency prices manager class.
	 *
	 * @return WC_Aelia_CurrencyPrices_Manager
	 */
	protected static function currencyprices_manager() {
		if(empty(self::$_currencyprices_manager)) {
			self::$_currencyprices_manager = \WC_Aelia_CurrencyPrices_Manager::instance();
		}
		return self::$_currencyprices_manager;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->set_hooks();
	}

	/**
	 * Convenience method. Returns an array of the Enabled Currencies.
	 *
	 * @return array
	 */
	protected function enabled_currencies() {
		return WC_Aelia_CurrencySwitcher::settings()->get_enabled_currencies();
	}

	/**
	 * Set the hooks required by the class.
	 */
	protected function set_hooks() {
		add_filter('wc_aelia_currencyswitcher_product_convert_callback', array($this, 'wc_aelia_currencyswitcher_product_convert_callback'), 10, 2);
		add_action('woocommerce_process_product_meta_bundle', array($this, 'woocommerce_process_product_meta_bundle'));
		add_filter('woocommerce_bundle_price_html', array($this, 'woocommerce_bundle_price_html'), 10, 2);
		add_filter('woocommerce_bundle_sale_price_html', array($this, 'woocommerce_bundle_sale_price_html'), 10, 2);

		add_filter('woocommerce_bundle_get_base_price', array($this, 'woocommerce_bundle_get_base_price'), 10, 2);
		add_filter('woocommerce_bundle_get_base_regular_price', array($this, 'woocommerce_bundle_get_base_regular_price'), 10, 2);
		add_filter('woocommerce_bundle_get_base_sale_price', array($this, 'woocommerce_bundle_get_base_sale_price'), 10, 2);

		// Admin UI
		// TODO Implement Admin UI
		//add_action('woocommerce_product_options_general_product_data', array($this, 'woocommerce_product_options_general_product_data'), 20);
	}

	/**
	 * Returns the shop's base currency.
	 *
	 * @return string
	 */
	public static function base_currency() {
		if(empty(self::$_base_currency)) {
			self::$_base_currency = WC_Aelia_CurrencySwitcher::settings()->base_currency();
		}
		return self::$_base_currency;
	}

	/**
	 * Returns the active currency.
	 *
	 * @return string
	 */
	public function selected_currency() {
		if(empty(self::$_selected_currency)) {
			self::$_selected_currency = self::cs()->get_selected_currency();
		}
		return self::$_selected_currency;
	}

	/**
	 * Converts all the prices of a given product in the currently selected
	 * currency.
	 *
	 * @param WC_Product product The product whose prices should be converted.
	 * @return WC_Product
	 */
	protected function convert_product_prices($product) {
		$selected_currency = self::selected_currency();
		$base_currency = self::base_currency();

		if(empty($product->currency) || ($product->currency != $selected_currency)) {
			$product = self::currencyprices_manager()->convert_product_prices($product, $selected_currency);
			$product->currency = $selected_currency;
		}

		return $product;
	}

	/**
	 * Converts the price for a bundled product. With bundled products, price
	 * is passed "as-is" and it doesn't get converted into currency.
	 *
	 * @param string bundle_price_html The HTML snippet containing a
	 * bundle's regular price in base currency.
	 * @param WC_Product product The product being displayed.
	 * @return string The HTML snippet with the price converted into currently
	 * selected currency.
	 */
	public function woocommerce_bundle_price_html($bundle_price_html, $product) {
		$product = $this->convert_product_prices($product);

		$bundle_price_html = $product->get_price_html_from_text();
		$bundle_price_html .= woocommerce_price($product->min_bundle_price);
		return $bundle_price_html;
	}

	/**
	 * Converts the price for a bundled Products on sale. With sales, the regular
	 * price is passed "as-is" and it doesn't get converted into currency.
	 *
	 * @param string bundle_sale_price_html The HTML snippet containing a
	 * Product's regular price and sale price.
	 * @param WC_Product product The product being displayed.
	 * @return string The HTML snippet with the sale price converted into
	 * currently selected currency.
	 */
	public function woocommerce_bundle_sale_price_html($bundle_sale_price_html, $product) {
		$product = $this->convert_product_prices($product);

		$min_bundle_regular_price_in_currency = self::cs()->format_price($product->min_bundle_regular_price);
		$min_bundle_sale_price_in_currency = $product->min_bundle_price;
		if($min_bundle_sale_price_in_currency <= 0) {
			$min_bundle_sale_price_in_currency = __('Free!', 'woocommerce');
		} else{
			$min_bundle_sale_price_in_currency = self::cs()->format_price($min_bundle_sale_price_in_currency);
		}

		$bundle_sale_price_html = $product->get_price_html_from_text();
		return '<del>' . $min_bundle_regular_price_in_currency . '</del> <ins>' . $min_bundle_sale_price_in_currency . '</ins>';
	}

	/**
	 * Callback to perform the conversion of bundle prices into selected currencu.
	 *
	 * @param callable $convert_callback A callable, or null.
	 * @param WC_Product The product to examine.
	 * @return callable
	 */
	public function wc_aelia_currencyswitcher_product_convert_callback($convert_callback, $product) {
		$method_keys = array(
			'WC_Product_Bundle' => 'bundle',
		);

		// Determine the conversion method to use
		$method_key = get_value(get_class($product), $method_keys, '');
		$convert_method = 'convert_' . $method_key . '_product_prices';

		if(!method_exists($this, $convert_method)) {
			return $convert_callback;
		}
		return array($this, $convert_method);
	}

	/**
	 * Indicates if the product is on sale. A product is considered on sale if:
	 * - Its "sale end date" is empty, or later than today.
	 * - Its sale price in the active currency is lower than its regular price.
	 *
	 * @param WC_Product product The product to check.
	 * @return bool
	 */
	protected function bundle_is_on_sale(WC_Product $product) {
		$today = date('Ymd');
		if((empty($product->base_sale_price_dates_from) ||
				$today >= date('Ymd', $product->base_sale_price_dates_from)) &&
			 (empty($product->base_sale_price_dates_to) ||
				date('Ymd', $product->base_sale_price_dates_to) > $today)) {
			$sale_price = $product->get_base_sale_price();
			return is_numeric($sale_price) && ($sale_price < $product->get_base_regular_price());
		}
		return false;
	}

	/**
	 * Recalculates bundle's prices, based on selected currency.
	 *
	 * @param WC_Product_Bundle product The bundle whose prices will be converted.
	 */
	protected function convert_bundle_base_prices(WC_Product_Bundle $product, $currency) {
		$shop_base_currency = self::base_currency();
		$product_base_currency = self::currencyprices_manager()->get_product_base_currency($product->id);

		// TODO Load product's base prices in each currency
		$bundle_base_regular_prices_in_currency = array();
		$bundle_base_sale_prices_in_currency = array();

		// Take regular price in the specific product base currency
		$product_base_regular_price = get_value($product_base_currency, $bundle_base_regular_prices_in_currency);
		// If a regular price was not entered for the selected product base currency,
		// take the one in shop base currency
		if(!is_numeric($product_base_regular_price)) {
			$product_base_regular_price = get_value($shop_base_currency, $bundle_base_regular_prices_in_currency, $product->base_regular_price);
		}

		// Take sale price in the specific product base currency
		$product_base_sale_price = get_value($product_base_currency, $bundle_base_sale_prices_in_currency);
		// If a sale price was not entered for the selected product base currency,
		// take the one in shop base currency
		if(!is_numeric($product_base_sale_price)) {
			$product_base_sale_price = get_value($shop_base_currency, $bundle_base_sale_prices_in_currency, $product->base_sale_price);
		}

		if(($currency != $product_base_currency) && !is_numeric($product->base_regular_price)) {
			$product->base_regular_price = self::currencyprices_manager()->convert_product_price_from_base($product_base_regular_price, $currency, $product_base_currency, $product);
		}
																				;
		if(($currency != $product_base_currency) && !is_numeric($product->base_sale_price)) {
			$product->base_sale_price = self::currencyprices_manager()->convert_product_price_from_base($product_base_sale_price, $currency, $product_base_currency, $product);
		}

		// Debug
		//var_dump(
		//	"PRODUCT CLASS: " . get_class($product),
		//	"PRODUCT ID: {$product->id}",
		//	"BASE CURRENCY $product_base_currency",
		//	$bundle_base_regular_prices_in_currency,
		//	$product->regular_price,
		//	$product->sale_price
		//);

		if(!is_numeric($product->base_regular_price) ||
			 $this->bundle_is_on_sale($product)) {
			$product->base_price = $product->base_sale_price;
		}
		else {
			$product->base_price = $product->base_regular_price;
		}
		return $product;
	}

	/**
	 * Converts the prices of a bundle product to the specified currency.
	 *
	 * @param WC_Product_Bundle product A variable product.
	 * @param string currency A currency code.
	 * @return WC_Product_Bundle The product with converted prices.
	 */
	public function convert_bundle_product_prices(WC_Product_Bundle $product, $currency) {
		$bundled_products = get_value('bundled_products', $product, array());

		if($product->is_priced_per_product()) {
			$this->convert_bundle_base_prices($product, $currency);
		}
		else {
			$product = self::currencyprices_manager()->convert_simple_product_prices($product, $currency);
		}

		return $product;
	}

	/**
	 * Converts a bundle's base price.
	 *
	 * @return float The converted price.
	 */
	public function woocommerce_bundle_get_base_price($price, $product) {
		$product = $this->convert_product_prices($product);
		return $product->base_price;
	}

	/**
	 * Converts a bundle's base regular price.
	 *
	 * @return float The converted price.
	 */
	public function woocommerce_bundle_get_base_regular_price($price, $product) {
		$product = $this->convert_product_prices($product);
		return $product->base_regular_price;
	}

	/**
	 * Converts a bundle's base sale price.
	 *
	 * @return float The converted price.
	 */
	public function woocommerce_bundle_get_base_sale_price($price, $product) {
		$product = $this->convert_product_prices($product);
		return $product->base_sale_price;
	}

	/*** Manual pricing of bundles ***/
	/**
	 * Returns the path where the Admin Views can be found.
	 *
	 * @return string
	 */
	protected function admin_views_path() {
		return WC_Aelia_CS_Bundles_Plugin::plugin_path() . '/views/admin';
	}

	/**
	 * Loads (includes) a View file.
	 *
	 * @param string view_file_name The name of the view file to include.
	 */
	private function load_view($view_file_name) {
		$file_to_load = $this->admin_views_path() . '/' . $view_file_name;

		if(!empty($file_to_load) && is_readable($file_to_load)) {
			include($file_to_load);
		}
	}

	/**
	 * Event handler fired when a bundle is being saved. It processes and
	 * saves the Currency Prices associated with the bundle.
	 *
	 * @param int post_id The ID of the Post (bundle) being saved.
	 */
	public function woocommerce_process_product_meta_bundle($post_id) {

		//// Copy the currency prices from the fields dedicated to the variation inside the standard product fields
		//$_POST[WC_Aelia_CurrencyPrices_Manager::FIELD_REGULAR_CURRENCY_PRICES] = $_POST[self::FIELD_REGULAR_CURRENCY_PRICES];
		//$_POST[WC_Aelia_CurrencyPrices_Manager::FIELD_SALE_CURRENCY_PRICES] = $_POST[self::FIELD_SALE_CURRENCY_PRICES];


		self::currencyprices_manager()->process_product_meta($post_id);
	}

	/**
	 * Alters the view used to allow entering prices manually, in each currency.
	 *
	 * @param string file_to_load The view/template file that should be loaded.
	 * @return string
	 */
	public function woocommerce_product_options_general_product_data() {
		//$this->load_view('simplebundle_currencyprices_view.php');
	}
}
