<?php
/**
Plugin Name: Add To Woo #2
Plugin URI: https://woocommerce.com/
Description: Our fourth WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Add_To_Woo_2 {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'change_add_to_cart_text' ), 10, 2 );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function change_add_to_cart_text( $text, $product ) {
		if ( $product->get_price() > 20 ) {
			$text = 'Add EXPENSIVE $$$ product!';
		}
		return $text;
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Add To Woo #2 requires %s to be installed and active.', 'add-to-woo-2' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Add_To_Woo_2();