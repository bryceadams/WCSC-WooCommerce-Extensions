<?php
/**
Plugin Name: Woo SMS
Plugin URI: https://woocommerce.com/
Description: Our eight WooCommerce extension! Eight, right?
Version: 1.0
Author URI: https://woocommerce.com/
*/

class Woo_SMS {
	public function __construct() {
		define( 'TWILIO_ACCOUNT_SID', '' );
		define( 'TWILIO_TOKEN', '' );
		define( 'TWILIO_FROM_NUMBER', '' );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_order_status_processing', array( $this, 'processing' ) );
			add_action( 'woocommerce_order_status_completed', array( $this, 'completed' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function send_sms( $order, $message ) {
		/**
		 * Form URL for Twilio.
		 */
		$url = 'https://api.twilio.com/2010-04-01/Accounts/' . TWILIO_ACCOUNT_SID . '/Messages.json';
		
		/**
		 * Format 'To' number.
		 */
		$to = '+' . $order->billing_phone;

		/**
		 * Make the POST request to Twilio.
		 */
		$response = wp_remote_post( $url, [
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode( TWILIO_ACCOUNT_SID . ':' . TWILIO_TOKEN ),
			],
			'body' =>  [
				'To' => $to,
				'From' => TWILIO_FROM_NUMBER,
				'Body' => $message,
			],
		] );

		/**
		 * Check no error POSTing.
		 */
		if ( is_wp_error( $response ) ) {
			return false;
		}

		return $response;
	}

	public function processing( $order_id ) {
		/**
		 * Message to send.
		 */
		$order = wc_get_order( $order_id );
		$message = 'Hi ' . $order->billing_first_name . '! We just received your $' . number_format( $order->get_total(), 2 ) . ' order - #' . $order->get_order_number() . '. We will message you when it has been processed! Love, the WordCamp Sunshine Coast store... ☀️';

		$sms = $this->send_sms( $order, $message );

		if ( ! $sms ) {
			$order->add_order_note( 'Failed to send \'processing\' SMS' );
		}

		/**
		 * All good. Add an order note to say we sent an SMS.
		 */
		$sid = json_decode( $sms['body'] )->sid;
		$order->add_order_note( 'Processing Order SMS sent - ' . $sid );

		return true;
	}

	public function completed( $order_id ) {
		/**
		 * Message to send.
		 */
		$order = wc_get_order( $order_id );
		$message = 'Hi there ' . $order->billing_first_name . '! We just processed your order #' . $order->get_order_number() . '.  It should arrive any day now. Thanks! 💌';

		$sms = $this->send_sms( $order, $message );

		if ( ! $sms ) {
			$order->add_order_note( 'Failed to send \'completed\' SMS' );
		}

		/**
		 * All good. Add an order note to say we sent an SMS.
		 */
		$sid = json_decode( $sms['body'] )->sid;
		$order->add_order_note( 'Completed Order SMS sent - ' . $sid );

		return true;
	}

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'Woo SMS requires %s to be installed and active.', 'woo-sms' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new Woo_SMS();