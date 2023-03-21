<?php
/**
 * Plugin Name: WPFeather - Floating Contact Form for WordPress
 * Plugin URI: https://wordpress.org/plugins/wpfeather/
 * Description: A very lightweight floating contact form for WordPress with built-in spam protection & Cloudflare Turnstile integration
 * Version: 1.0.0
 * Author: weDevs
 * Author URI: https://wedevs.com/
 * Text Domain: wpfeather
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/*
 * Copyright (c) 2019 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

// include background process file
require_once __DIR__  . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
/**
 * WPfeather class
 *
 * @class WPfeather The class that holds the entire plugin
 */
final class WPfeather {

	/**
	 * Plugin version
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Holds various class instances.
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private $container = [];

	/**
	 * Constructor for the class.
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

		add_action( 'wpfeather_mail_frontend_form_submission', [ $this, 'send_mail' ], 10, 3 );
	}

	/**
	 * Initializes the WPfeather() class.
	 *
	 * Checks for an existing WPfeather() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @since 0.1
	 *
	 * @return WPfeather|bool
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new WPfeather();
		}

		return $instance;
	}

	/**
	 * Magic getter to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Magic isset to bypass referencing plugin.
	 *
	 * @param $prop
	 *
	 * @since 0.1
	 *
	 * @return mixed
	 */
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 * Define the constants.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'WPFEATHER_VERSION', self::VERSION );
		$this->define( 'WPFEATHER_FILE', __FILE__ );
		$this->define( 'WPFEATHER_PATH', dirname( WPFEATHER_FILE ) );
		$this->define( 'WPFEATHER_INCLUDES', WPFEATHER_PATH . '/includes' );
		$this->define( 'WPFEATHER_URL', plugins_url( '', WPFEATHER_FILE ) );
		$this->define( 'WPFEATHER_ASSETS', WPFEATHER_URL . '/assets' );
		$this->define( 'WPFEATHER_BASE_NAME', plugin_basename( __FILE__ ) );

		$options = get_option( 'wpfeather_options' );

		// define the ajax key suffix. it will append after the ajax action name
		if ( ! empty( $options['secret_keys'] ) && ! empty( $options['secret_keys'][0] ) ) {
			$this->define( 'WPFEATHER_AJAX_KEY', sanitize_key( $options['secret_keys'][0] ) );
		} else {
			$this->define( 'WPFEATHER_AJAX_KEY', '' );
		}
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin after all plugis are loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->init_hooks();
	}

	/**
	 * Placeholder for activation function.
	 *
	 * Nothing being called here yet.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new WeDevs\WpFeather\Installer();
		$installer->run();
	}

	/**
	 * Placeholder for deactivation function.
	 *
	 * Nothing being called here yet.
	 *
	 * @since 0.1
	 */
	public function deactivate() {}

	/**
	 * Initialize the hooks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'after_setup_theme', [ $this, 'init_classes' ] );

		// Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );
	}

	/**
	 * Instantiate the required classes.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function init_classes() {
		if ( $this->is_request( 'admin' ) ) {
			$this->container['admin'] = new WeDevs\WpFeather\Admin();
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->container['frontend'] = new WeDevs\WpFeather\Frontend();
		}

		if ( $this->is_request( 'ajax' ) ) {
			$this->container['ajax'] = new WeDevs\WpFeather\Ajax();
		}
	}

	/**
	 * Initialize plugin for localization.
	 *
	 * @uses load_plugin_textdomain()
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'wpfeather', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, cron or frontend
	 *
	 * @since 0.1
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();

			case 'ajax':
				return defined( 'DOING_AJAX' );

			case 'rest':
				return defined( 'REST_REQUEST' );

			case 'cron':
				return defined( 'DOING_CRON' );

			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Send frontend form submitted data to a mail
	 *
	 * @param $name
	 * @param $email
	 * @param $message
	 *
	 * @return void
	 */
	public function send_mail( $name, $email, $message ) {
		$email_from    = get_bloginfo( 'admin_email' );
		$email_to      = get_bloginfo( 'admin_email' );
		$email_subject = sprintf( esc_html__( 'Message from %s', 'wpfeather' ), get_bloginfo( 'name' ) );

		/* translators: Do not translate USERNAME, URL_DELETE, SITENAME, SITEURL: those are placeholders. */
		$mail_body = __(
			"Howdy,

Someone submitted a form through WPFeather from ###SITEURL###. The form details below:\n
Name: $name\n
e-mail: $email\n
Message: $message\n

Thanks for using WPFeather"
		);
		/**
		 * Filters the text for the email sent when WPFeather frontend form is submitted.
		 *
		 * @since 1.0.0
		 *
		 * @param string $mail_body The email text.
		 */
		$mail_body = apply_filters( 'wpfeather_frontend_form_email_content', $mail_body );
		$mail_body = str_replace( '###SITEURL###', network_home_url(), $mail_body );

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: "' . esc_attr( get_bloginfo( 'name' ) ) . '" <' . sanitize_email( $email_from ) . '>',
			'Reply-To: <'.sanitize_email( $email ).'>'
		];

		$result = wp_mail( $email_to, $email_subject, $mail_body, $headers );

		if ( ! $result ) {
			error_log( '[WPFeather]: cannot send mail to admin.' );
		}
	}
}

/**
 * Initialize the main plugin.
 *
 * @since 0.1
 *
 * @return \WPfeather
 */
function wpfeather() {
	return WPfeather::init();
}

/*
 * Kick-off the plugin.
 *
 * @since 0.1
 */
wpfeather();

