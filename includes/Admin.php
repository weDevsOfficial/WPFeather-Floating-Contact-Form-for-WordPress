<?php
/**
 * The admin class
 */

namespace WeDevs\WpFeather;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->init_classes();
    }

    public function init_classes() {
        new Admin\Menu();
    }
}
