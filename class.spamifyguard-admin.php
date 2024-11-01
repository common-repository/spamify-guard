<?php

class SpamifyGuard_Admin {
	const NONCE = 'spamifyguard-update-key';

	private static $initiated = false;
	private static $notices   = array();

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
			self::enter_api_key();
		}
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-url' ) {
			self::enter_redirect_url();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_init', array( 'SpamifyGuard_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'SpamifyGuard_Admin', 'admin_menu' ), 5 ); # Priority 5, so it's called before Jetpack's admin_menu.
		add_filter( 'plugin_action_links_'.plugin_basename( plugin_dir_path( __FILE__ ) . 'spamify-guard.php'), array( 'SpamifyGuard_Admin', 'admin_plugin_settings_link' ) );
		add_action( 'admin_enqueue_scripts', array( 'SpamifyGuard_Admin', 'load_resources' ) );
	}

	public static function admin_init() {
		load_plugin_textdomain( 'spamifyguard' );
	}

	public static function load_resources() {
		wp_register_style( 'style.css', plugin_dir_url( __FILE__ ) . '_inc/style.css', array(), SPAMIFYGUARD_VERSION);
		wp_enqueue_style( 'style.css');
	}

	public static function admin_menu() {
		add_options_page( __('Spamify Guard', 'spamifyguard'), __('Spamify Guard', 'spamifyguard'), 'manage_options', 'spamifyguard-key-config', array( 'SpamifyGuard_Admin', 'display_page' ) );
	}

	public static function admin_head() {
		if ( !current_user_can( 'manage_options' ) )
			return;
	}
	
	public static function admin_plugin_settings_link( $links ) { 
  		$settings_link = '<a href="'.esc_url( self::get_page_url() ).'">'.__('Settings', 'spamifyguard').'</a>';
  		array_unshift( $links, $settings_link );
  		return $links;
	}

	public static function enter_api_key() {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?', 'spamifyguard'));

		if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
			return false;

		$new_key = preg_replace( '/[^a-f0-9]/i', '', $_POST['key'] );
		$old_key = SpamifyGuard::get_api_key();

		if ( empty( $new_key ) ) {
			if ( !empty( $old_key ) ) {
				delete_option( 'spamifyguard_api_key' );
				self::$notices[] = 'new-key-empty';
			}
		}
		elseif ( $new_key != $old_key ) {
			update_option( 'spamifyguard_api_key', $new_key );
			self::$notices['status'] = 'new-key-valid';
		}

		return true;
	}

	public static function enter_redirect_url() {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?', 'spamifyguard'));

		if ( !wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) )
			return false;

		$new_url = $_POST['url'];
		$old_url = SpamifyGuard::get_redirect_url();

		if ( empty( $new_url ) ) {
			update_option( 'spamifyguard_redirect_url', 'http://app.spamifyguard.com/block' );
			self::$notices['status'] = 'default-url-valid';
		}
		elseif ( $new_url != $old_url ) {
			update_option( 'spamifyguard_redirect_url', $new_url );
			self::$notices['status'] = 'new-url-valid';
		}

		return true;
	}

	public static function get_page_url() {

		$args = array( 'page' => 'spamifyguard-key-config' );
		$url = add_query_arg( $args, class_exists( 'Jetpack' ) ? admin_url( 'admin.php' ) : admin_url( 'options-general.php' ) );

		return $url;
	}

	public static function display_page() {
		self::display_start_page();
	}

	public static function display_start_page() {
		if ( isset( $_GET['action'] ) ) {
			if ( $_GET['action'] == 'delete-key' ) {
				if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], self::NONCE ) )
					delete_option( 'spamifyguard_api_key' );
			}
		}
		echo '<h2 class="spamify-header">'.esc_html__('Spamify Guard', 'spamifyguard').'</h2>';
		self::display_status();
		SpamifyGuard::view( 'start', array() );
	}

	public static function display_status() {
		foreach ( self::$notices as $type ) {
			SpamifyGuard::view( 'notice', compact( 'type' ) );
		}
	}
}