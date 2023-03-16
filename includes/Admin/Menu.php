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
        add_action( 'init', [ $this, 'register' ] );
        add_action( 'init', [ $this, 'register_translations' ] );
    }

    /**
     * Register our menu page
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'wpfeather';
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
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 5 );
    }

	/**
     * Register app assets.
     *
     * @since 1.0.0
     *
	 * @return void
	 */
	public function register() {
		$assets       = require WPFEATHER_PATH . '/assets/js/settings.asset.php';
		$dependencies = $assets['dependencies'];

		wp_register_script(
			'wpfeather-settings',
			plugins_url( 'assets/js/settings.js', WPFEATHER_FILE ),
			$dependencies,
			$assets['version'],
			true
		);
		wp_register_style(
			'wpfeather-settings',
			plugins_url( 'assets/js/settings.css', WPFEATHER_FILE ),
			[],
			$assets['version']
		);
    }

    /**
     * Load scripts and styles for the app
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wpfeather-settings' );
        wp_enqueue_script( 'wpfeather-settings' );
    }

	/**
	 * Handles the Workspaces page
	 *
	 * @return void
	 */
	public function wp_feather_settings_page() {
		?>
		<div id="wpfeather-settings"></div>
		<?php
	}

	/**
	 * Register script translations
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_translations() {
		wp_set_script_translations(
			'wpfeather-js',
			'wpfeather',
			plugin_dir_path( WPFEATHER_FILE ) . 'languages'
		);
	}
}
