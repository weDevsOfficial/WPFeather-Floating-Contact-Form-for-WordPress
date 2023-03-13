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
            update_option( 'wpfeather_installed', time() );
        }

        update_option( 'wpfeather_version', WPFEATHER_VERSION );
    }
}
