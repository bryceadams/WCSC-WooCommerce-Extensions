<?php
/**
Plugin Name: Ship to Woo
Plugin URI: https://woocommerce.com/
Description: Our fifth WooCommerce extension! Getting tired now...
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Ship_To_Woo {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_package_rates', array( $this, 'change_shipping_price' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function change_shipping_price( $rates ) {
		foreach ( $rates as $rate ) {
			$cost = $rate->cost;
			$rate->cost = $cost - 5;
		}

		return $rates;
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Ship to Woo requires %s to be installed and active.', 'ship-to-woo' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Ship_To_Woo();