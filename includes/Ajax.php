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
		$email_from    = get_bloginfo( 'admin_email' );
		$email_to      = get_bloginfo( 'admin_email' );
		$email_subject = sprintf( esc_html__( 'Message from %s', 'WPFeather' ), get_bloginfo( 'name' ) );

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

		wp_mail( $email_to, $email_subject, $mail_body, $headers );

		// validation success
		wp_send_json_success( [
			'message' => __( 'Form submitted successfully', 'wpfeather' ),
		] );
	}
}
