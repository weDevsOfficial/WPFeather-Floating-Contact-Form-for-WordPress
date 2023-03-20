<?php
/**
 * The frontend class
 */

namespace WeDevs\WpFeather;

/**
 * The frontend class
 */
class Frontend {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'register_scripts' ] );
		add_action( 'wp_footer', [ $this, 'add_floating_form' ] );
	}

	public function register_scripts() {
		wp_register_script( 'wpfeather-form', WPFEATHER_ASSETS . '/js/floating-form.js', [ 'jquery' ], WPFEATHER_VERSION, true );
		wp_register_style( 'wpfeather-form', WPFEATHER_ASSETS . '/js/form.css', [], WPFEATHER_VERSION );

		$options = get_option( 'wpfeather_options' );

		$wpFeatherForm = [
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'actionKey' => 'wpfeather_' . WPFEATHER_AJAX_KEY,
			'nonce'     => wp_create_nonce( 'wpfeather_form' ),
		];

		wp_localize_script( 'wpfeather-form', 'wpFeatherForm', $wpFeatherForm );

		wp_enqueue_script( 'wpfeather-form' );
		wp_enqueue_style( 'wpfeather-form' );
	}

	public function add_floating_form() {
		$floating_form = WPFEATHER_INCLUDES . '/Frontend/views/floating-form.php';
		// @TODO:replace with site key from settings
		$turnstile_site_key = '0x4AAAAAAADQdgiADHUielUbUBtzJ4raQXk';
		if ( file_exists( $floating_form ) ) {
			include $floating_form;
		}
	}
}
