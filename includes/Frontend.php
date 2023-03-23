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
		wp_register_script( 'cloudflare-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js' );

		$wpFeatherForm = [
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'actionKey' => 'wpfeather_' . WPFEATHER_AJAX_KEY,
			'nonce'     => wp_create_nonce( 'wpfeather_form' ),
		];

		wp_localize_script( 'wpfeather-form', 'wpFeatherForm', $wpFeatherForm );

		wp_enqueue_script( 'wpfeather-form' );
		wp_enqueue_style( 'wpfeather-form' );
	}

	/**
	 * Adding the frontend floating form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_floating_form() {
		$floating_form = WPFEATHER_INCLUDES . '/Frontend/views/floating-form.php';

		$turnstile_site_key = wpfeather_get_option( 'sitekey', 'wpfeather_settings' );
		$thank_you_message  = wpfeather_get_option( 'thank_you_msg', 'wpfeather_settings' );
		// checking with isset instead of empty because user might save the title or body empty
		$msg_title = isset( $thank_you_message['title'] ) ? esc_html( $thank_you_message['title'] ) : 'We received your message';
		$msg_body  = isset( $thank_you_message['body'] ) ? esc_html( $thank_you_message['body'] ) : 'We will reach you with your email address soon. Thank you for the time!';

		if ( ! empty( $turnstile_site_key ) ) {
			wp_enqueue_script( 'cloudflare-turnstile' );
		}

		if ( file_exists( $floating_form ) ) {
			include $floating_form;
		}
	}
}
