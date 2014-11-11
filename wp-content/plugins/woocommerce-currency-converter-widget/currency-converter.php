<?php
/*
Plugin Name: WooCommerce Currency Converter
Plugin URI: http://woothemes.com/woocommerce
Description: Adds a currency selection widget - when the user chooses a currency, the stores prices are displayed in the chosen currency dynamically. This does not affect the currency in which you take payment. Conversions are estimated based on data from the Open Source Exchange Rates API with no guarantee whatsoever of accuracy.
Version: 1.2.9
Author: WooThemes
Author URI: http://woothemes.com
Requires at least: 3.1
Tested up to: 3.3

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '0b2ec7cb103c9c102d37f8183924b271', '18651' );

/**
 * Check if WooCommerce is active
 **/
if ( is_woocommerce_active() ) {

	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_currency_converter', false, dirname( plugin_basename( __FILE__ ) ) . '/');

	/**
	 * Widget
	 * */
	include_once( 'currency-converter-widget.php' );

	/**
	 * woocommerce_currency_converter class
	 **/
	if (!class_exists('WC_Currency_Converter')) {

		class WC_Currency_Converter {

			var $base;
			var $currency;
			var $rates;
			var $settings;

			public function __construct() {

				// Init settings
				$this->settings = array(
					array( 'name' => __( 'Open Exchange Rate API', 'woothemes' ), 'type' => 'title', 'desc' => '', 'id' => 'product_enquiry' ),
					array(
						'name' => __('App Key', 'wc_enquiry_form'),
						'desc' 		=> sprintf( __('(optional) If you have an <a href="%s">Open Exchange Rate API app ID</a>, enter it here.', 'wc_enquiry_form'), 'https://openexchangerates.org/signup' ),
						'id' 		=> 'wc_currency_converter_app_id',
						'type' 		=> 'text',
						'std'		=> ''
					),
					array( 'type' => 'sectionend', 'id' => 'product_enquiry'),
				);

				if ( false === ( $rates = get_transient( 'woocommerce_currency_converter_rates' ) ) ) {

					$app_id = get_option( 'wc_currency_converter_app_id' ) ? get_option( 'wc_currency_converter_app_id' ) : 'e65018798d4a4585a8e2c41359cc7f3c';

					$rates = wp_remote_retrieve_body( wp_remote_get( 'http://openexchangerates.org/api/latest.json?app_id=' . $app_id ) );

					// Cache for 12 hours
				    if ( $rates )
				    	set_transient( 'woocommerce_currency_converter_rates', $rates, 60*60*12 );
				}

				$rates = json_decode( $rates );

				if ( $rates ) {
					$this->base		= $rates->base;
					$this->rates 	= $rates->rates;
				}

				// Actions
				add_action('wp_enqueue_scripts', array(&$this, 'currency_conversion_js'));
				add_action('woocommerce_checkout_update_order_meta', array(&$this, 'update_order_meta'));
				add_action('widgets_init', array(&$this, 'widgets'));
				add_action('wp_enqueue_scripts', array(&$this, 'styles'));

				// Settings
				add_action('woocommerce_settings_general_options_after', array(&$this, 'admin_settings'));
				add_action('woocommerce_update_options_general', array(&$this, 'save_admin_settings'));
		    }

	        /*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			function admin_settings() {
				woocommerce_admin_fields( $this->settings );
			}

			function save_admin_settings() {
				woocommerce_update_options( $this->settings );
			}

			function widgets() {
				register_widget('WooCommerce_Widget_Currency_Converter');
			}

			function styles() {
				wp_enqueue_style( 'currency_converter_styles', plugins_url( '/assets/css/converter.css', __FILE__ ) );
			}

			function currency_conversion_js() {
				if ( is_admin() )
					return;

				// Scripts
				wp_register_script( 'moneyjs', plugins_url('/assets/js/money.min.js', __FILE__), 'jquery', '0.1.2', true );
				wp_register_script( 'accountingjs', plugins_url('/assets/js/accounting.min.js', __FILE__), 'jquery', '0.3.2', true );
				wp_register_script( 'jquery-cookie', plugins_url('/assets/js/jquery-cookie/jquery.cookie.min.js', __FILE__), 'jquery', '1.3.1', true );
				wp_enqueue_script( 'wc_currency_converter', plugins_url('/assets/js/conversion.min.js', __FILE__), array( 'jquery', 'moneyjs', 'accountingjs', 'jquery-cookie' ), '1.2.3', true );

				$symbols = array();

				if ( function_exists( 'get_woocommerce_currencies' ) ) {
					$codes   = get_woocommerce_currencies();
					foreach ( $codes as $code => $name )
						$symbols[ $code ]         = get_woocommerce_currency_symbol( $code );
				}

				$zero_replace = '.';
				for ( $i = 0; $i < absint( get_option( 'woocommerce_price_num_decimals' ) ); $i++ )
					$zero_replace .= '0';

				$wc_currency_converter_params = array(
					'current_currency' => isset( $_COOKIE['woocommerce_current_currency'] ) ? $_COOKIE['woocommerce_current_currency'] : '',
					'currencies'       => json_encode( $symbols ),
					'rates'            => $this->rates,
					'base'             => $this->base,
					'currency'         => get_option( 'woocommerce_currency' ),
					'currency_pos'     => get_option( 'woocommerce_currency_pos' ),
					'num_decimals'     => absint( get_option( 'woocommerce_price_num_decimals' ) ),
					'trim_zeros'       => get_option( 'woocommerce_price_trim_zeros' ) == 'yes' ? true : false,
					'thousand_sep'     => get_option( 'woocommerce_price_thousand_sep' ),
					'decimal_sep'      => get_option( 'woocommerce_price_decimal_sep' ),
					'i18n_oprice'      => __( 'Original price:', 'wc_currency_converter'),
					'zero_replace'     => $zero_replace
				);

				wp_localize_script( 'wc_currency_converter', 'wc_currency_converter_params', apply_filters( 'wc_currency_converter_params', $wc_currency_converter_params ) );
			}

			function update_order_meta( $order_id ) {
				global $woocommerce;

				if (isset($_COOKIE['woocommerce_current_currency']) && $_COOKIE['woocommerce_current_currency']) {

					update_post_meta( $order_id, 'Viewed Currency', $_COOKIE['woocommerce_current_currency'] );

					$order_total = number_format($woocommerce->cart->total, 2, '.', '');

					$store_currency = get_option('woocommerce_currency');
					$target_currency = $_COOKIE['woocommerce_current_currency'];

					if ($store_currency && $target_currency && $this->rates->$target_currency && $this->rates->$store_currency) {

						$new_order_total = ( $order_total / $this->rates->$store_currency ) * $this->rates->$target_currency;

						$new_order_total = round($new_order_total, 2) . ' ' . $target_currency;

						update_post_meta( $order_id, 'Converted Order Total', $new_order_total );

					}

				}
			}

		}

		$woocommerce_currency_converter = new WC_Currency_Converter();
	}
}