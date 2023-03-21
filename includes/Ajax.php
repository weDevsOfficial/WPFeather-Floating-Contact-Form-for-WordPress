<?php

namespace WeDevs\WpFeather;

/**
 * The frontend class
 */
class Ajax {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_wpfeather_' . WPFEATHER_AJAX_KEY, [ $this, 'handle_frontend_form' ] );
		add_action( 'wp_ajax_nopriv_wpfeather_' . WPFEATHER_AJAX_KEY, [ $this, 'handle_frontend_form' ] );
	}

	/**
	 * Handle the frontend form submission. Sanitize form inputs and submits the formdata to a mail
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_frontend_form() {
		$nonce = ! empty( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		// checking the nonce
		if ( ! wp_verify_nonce( $nonce, 'wpfeather_form' ) ) {
			wp_send_json_error( [
				'type'    => 'error',
				'message' => __( 'Not authorized', 'wpfeather' ),
			] );
		}
		$name    = ! empty( $_POST['fullName'] ) ? sanitize_text_field( $_POST['fullName'] ) : '';
		$email   = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$message = ! empty( $_POST['message'] ) ? wp_kses_post( $_POST['message'] ) : '';

		if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
			wp_send_json_error( [
                'type'    => 'error',
                'message' => __( 'Name, email and message is required', 'wpfeather' ),
            ] );
		}

		// define hook name beforehand
		$mailing_hook = 'wpfeather_mail_frontend_form_submission';

		if ( false === as_next_scheduled_action( $mailing_hook ) ) {
			// now schedule to send an email containing the frontend result
			as_enqueue_async_action(
				$mailing_hook,
				[
					'name'    => $name,
					'email'   => $email,
					'message' => $message,
				]
			);
		}

		// validation success
		wp_send_json_success( [
			'message' => __( 'Form submitted successfully', 'wpfeather' ),
		] );
	}
}
