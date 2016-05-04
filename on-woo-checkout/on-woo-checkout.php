<?php
/**
Plugin Name: On Woo Checkout
Plugin URI: https://woocommerce.com/
Description: Our seventh WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class On_Woo_Checkout {
	public function __construct() {
		define( 'TWITTER_CONSUMER_KEY', '' );
		define( 'TWITTER_CONSUMER_SECRET', '' );
		define( 'TWITTER_ACCESS_TOKEN', '' );
		define( 'TWITTER_ACCESS_TOKEN_SECRET', '' );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_order_status_processing', array( $this, 'tweet' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function tweet( $order_id ) {
		require_once( plugin_dir_path( __FILE__ ) . 'TwitterAPI.php' );

		$settings = array(
		    'oauth_access_token' => TWITTER_ACCESS_TOKEN,
		    'oauth_access_token_secret' => TWITTER_ACCESS_TOKEN_SECRET,
		    'consumer_key' => TWITTER_CONSUMER_KEY,
		    'consumer_secret' => TWITTER_CONSUMER_SECRET,
		);

		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';

		$order = wc_get_order( $order_id );
		$postfields = array(
		    'status' => 'I just got a new order (#' . $order_id . ') for $' . number_format( $order->get_total(), 2 ) . ' - woooo! 😍',
		);

		$twitter = new TwitterAPIExchange($settings);
		$twitter->buildOauth($url, $requestMethod)
		    ->setPostfields($postfields)
		    ->performRequest();
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'On Woo Checkout requires %s to be installed and active.', 'add-to-woo' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new On_Woo_Checkout();