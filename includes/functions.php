<?php
/**
 * Update options table data depending on single site or multisite
 *
 * @param $option
 * @param $value
 * @param $autoload
 *
 * @return bool
 */
function wpfeather_update_option( $option, $value, $autoload = false ) {
	if ( ! is_multisite() ) {
		return update_option( $option, $value, $autoload );
	} else {
		return update_blog_option( get_current_blog_id(), $option, $value );
	}
}