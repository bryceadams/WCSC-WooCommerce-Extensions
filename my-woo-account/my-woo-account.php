<?php
/**
Plugin Name: My Woo Account
Plugin URI: https://woocommerce.com/
Description: Our sixth WooCommerce extension!
Version: 1.0
Author URI: https://woocommerce.com/
*/

class My_Woo_Account {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_before_my_account', array( $this, 'before_account' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'no_wc' ) );
		}
	}

	public function before_account() {
		$user = wp_get_current_user() ?>
		<h2>My favourite GIFs!</h2>
		<p>Hi there <strong><?php echo $user->display_name; ?></strong>! I wanted to share with you some GIFs I like.</p>
		<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/gifs/two.gif" width="355" style="display:inline-block;" />
		<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/gifs/three.gif" width="200" style="display:inline-block;" />
		<hr />
	<?php }

	public function no_wc() {
		echo '<div class="error"><p>' . sprintf( __( 'My Woo Account requires %s to be installed and active.', 'my-woo-account' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}
}

new My_Woo_Account();