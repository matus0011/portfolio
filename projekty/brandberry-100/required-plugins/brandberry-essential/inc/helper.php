<?php

use Elementor\Core\Files\File_Types\Svg;

/* return the specific value from theme options  */
if (! function_exists('brandberry_option')) {
	function brandberry_option($option = '', $default = '', $parent = false)
	{

		static $cached_options = [];		
		if(!$parent){
			$parent = BRANDBERRY_ESSENTIAL_OPTION_KEY;
		}
		if (empty($option)) {
			return $default;
		}
		// Load and cache options
		if (! isset($cached_options[$parent])) {
			$cached_options[$parent] = get_option($parent, []);
		}

		$options = $cached_options[$parent];
		$value = isset($options[$option]) ? $options[$option] : $default;
		// Allow filtering
		return apply_filters('brandberry_option_value', $value, $option, $parent);
	}
}

if (! function_exists('brandberry_theme_allowed_features')) { 
	function brandberry_theme_allowed_features() {
	    global $wpdb; 	
		static $memo      = null;
		static $run_count = 0;
		// If already memoized, return without extra SQL
		if ( $memo !== null ) {
			return $memo;
		}
		$fld= '_key_status'; 
		 // Use SQL to fetch license key status directly
		 $sql = $wpdb->prepare(
				"SELECT option_value 
				FROM {$wpdb->options} 
				WHERE option_name LIKE %s 
				AND option_value = %s 
				LIMIT 1",
				'%' . $wpdb->esc_like($fld) . '%', // LIKE match
				'valid' // exact value match
			);
		$license_status = $wpdb->get_var($sql);
		$run_count++;	
		$memo = ( $license_status === 'valid' );		
		return $memo;
	 }
}


if( !function_exists('brandberry_get_image_sizes')) {

	function brandberry_get_image_sizes( $size = '' ) {
		global $_wp_additional_image_sizes;		
	
		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
	
		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $_size ]  = $_size;
			
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = $_size;
			}
		}
	
		// Get only 1 size if found
		if ( $size ) {
			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}	
		return $sizes;
	}

}

if ( ! function_exists( 'wcf_elementor_widget_concat_prefix' ) ) {

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 */
	function wcf_elementor_widget_concat_prefix( $widget_name ) {
		return __( 'WCF ' . $widget_name, 'brandberry-essential' );
	}
}
