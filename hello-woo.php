<?php
/**
Plugin Name: Hello Woo
Plugin URI: https://woocommerce.com/
Description: Our first WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Hello_Woo {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_product_is_on_sale', '__return_true' );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Hello Woo requires %s to be installed and active.', 'hello-woo' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Hello_Woo();