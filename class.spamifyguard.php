<?php

class SpamifyGuard {
	const API_HOST = 'app.spamifyguard.com';
	const API_PORT = 80;

	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function plugin_activation() {
		if (!self::get_redirect_url()) {
			update_option('spamifyguard_redirect_url', 'http://app.spamifyguard.com/block');
		}
	}

	public static function plugin_deactivation( ) {
	}

	private static function init_hooks() {
		self::$initiated = true;
		add_action('wp_head', array( 'SpamifyGuard', 'validate' ), 1);
	}

	public static function get_api_key() {
		return apply_filters( 'spamifyguard_get_api_key', get_option('spamifyguard_api_key') );
	}

	public static function get_redirect_url() {
		return apply_filters( 'spamifyguard_get_redirect_url', get_option('spamifyguard_redirect_url') );
	}

	public static function get_ip_address() {
		return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;
	}

	private static function get_user_agent() {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
	}

	private static function get_referer() {
		return isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null;
	}

	public static function validate() {
		try {
			$referer = self::get_referer();
			$ip = self::get_ip_address();
			$agent = self::get_user_agent();

			$token = self::get_api_key();

			$response = self::http_post("token=" . $token . "&referer=" . $referer . "&ip=" . $ip . "&agent=" . $agent);
			$response = json_decode($response, true);

			if (isset($response['error'])) {
				return;
			}

			if (isset($response['success']) && $response['success']) {
				return;
			}

			if (isset($response['success']) && !$response['success']) {
				header('Location: ' . self::get_redirect_url());
			}
		} catch(Exception $e) {}
	}

	public static function http_post( $request  ) {

		$spamify_ua = sprintf( 'WordPress/%s | Spamify Guard/%s', $GLOBALS['wp_version'], constant( 'SPAMIFYGUARD_VERSION' ) );
		$spamify_ua = apply_filters( 'spamify_ua', $spamify_ua );

		$http_args = array(
			'body' => $request,
			'headers' => array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' ),
				'Host' => self::API_HOST,
				'User-Agent' => $spamify_ua,
			),
			'httpversion' => '1.0',
			'timeout' => 15
		);

		$spamify_url = "https://" . self::API_HOST . '/v1/verify';
		$response = wp_remote_post( $spamify_url, $http_args );

		return $response['body'];
	}


	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'spamifyguard_view_arguments', $args, $name );

		foreach ( $args AS $key => $val ) {
			$$key = $val;
		}

		load_plugin_textdomain( 'spamifyguard' );

		$file = SPAMIFYGUARD_PLUGIN_DIR . 'views/'. $name . '.php';

		include( $file );
	}

}
