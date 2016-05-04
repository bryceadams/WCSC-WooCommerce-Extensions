<?php
/**
Plugin Name: Add To Woo
Plugin URI: https://woocommerce.com/
Description: Our third WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Add_To_Woo {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_add_to_cart_text' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function change_add_to_cart_text( $text ) {
		return 'Add to WooCart';
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Add To Woo requires %s to be installed and active.', 'add-to-woo' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Add_To_Woo();