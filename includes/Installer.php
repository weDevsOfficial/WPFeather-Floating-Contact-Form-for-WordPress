<?php

namespace WeDevs\WpFeather;

/**
 * Class Installer
 */
class Installer {

    /**
     * Run the installer.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->add_keys();
    }

    /**
     * Add time and version on DB.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'wpfeather_installed' );

        if ( ! $installed ) {
	        wpfeather_update_option( 'wpfeather_installed', time() );
        }

	    wpfeather_update_option( 'wpfeather_version', WPFEATHER_VERSION );
    }

	public function add_keys() {
		$wpfeather_options = get_site_option( 'wpfeather_options' );
		if ( empty( $wpfeather_options ) ) {
			$wpfeather_options = [];
		}

		// already have secret_keys. no need to generate again
		if ( ! empty( $wpfeather_options['secret_keys'] ) ) {
			return;
		}

		$keys = [];
		for ( $n = 0; $n < 21; ++ $n ) {
			$keys[] = uniqid();
		}
		$wpfeather_options['secret_keys'] = $keys;

		// save the newly generated keys
		essential_form_update_option( 'wpfeather_options', $wpfeather_options );
	}
}
