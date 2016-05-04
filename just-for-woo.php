<?php
/**
Plugin Name: Just For Woo
Plugin URI: https://woocommerce.com/
Description: Our second WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Just_For_Woo {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_get_price', array( $this, 'change_price' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function change_price( $price ) {
		/**
		 * If the current user is an author (or higher) on our site, give them a 20% discount.
		 */
		if ( current_user_can( 'publish_posts' ) ) {
			return $price * 0.8;
		}
		return $price;
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Just For Woo requires %s to be installed and active.', 'just-for-woo' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Just_For_Woo();