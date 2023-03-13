<?php
/**
 * Admin Pages Handler
 * Class Menu
 */

namespace WeDevs\WpFeather\Admin;

/**
 * Class Menu
 */
class Menu {

    /**
     * Menu constructor.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register our menu page
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'workplace';
        $capability  = 'manage_options';

        $hook = add_menu_page( __( 'WP Feather', 'wpfeather' ), __( 'WP Feather', 'wpfeather' ), $capability, $parent_slug, [ $this, 'wp_feather_settings_page' ], 'dashicons-feedback' );

        add_action( 'load-' . $hook, [ $this, 'init_hooks' ] );
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Load scripts and styles for the app
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_scripts() {
        // wp_enqueue_style( 'admin' );
        // wp_enqueue_script( 'admin' );
    }

	/**
	 * Handles the Workspaces page
	 *
	 * @return void
	 */
	public function wp_feather_settings_page() {
		?>
		<div id="wpfeather-settings">
			<h1>Loading...</h1>
		</div>
		<?php
	}
}
