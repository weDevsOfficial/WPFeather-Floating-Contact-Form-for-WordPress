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
		add_action( 'wp_head', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'add_floating_form' ] );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'wpfeather-floating-form', WPFEATHER_ASSETS . '/js/floating-form.js', [], WPFEATHER_VERSION, true );
		wp_enqueue_style( 'wpfeather-floating-form', WPFEATHER_ASSETS . '/css/index.css', [], WPFEATHER_VERSION );
	}

	public function add_floating_form() {
		$floating_form = WPFEATHER_INCLUDES . '/Frontend/views/floating-form.php';
		if ( file_exists( $floating_form ) ) {
			include $floating_form;
		}
	}
}
