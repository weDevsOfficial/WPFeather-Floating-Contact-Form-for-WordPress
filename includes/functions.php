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

/**
 * Get the value of a settings field
 *
 * @param string $option  settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 *
 * @return mixed
 */
function wpfeather_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	}

	return $default;
}