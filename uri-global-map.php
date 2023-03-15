<?php
/**
 * Plugin Name: URI Global Map
 * Plugin URI: https://www.uri.edu
 * Description: A Mapbox global map
 * Version: 0.1.0
 * Author: URI Web Communications
 * Author URI: https://today.uri.edu/
 *
 * @author: Brandon Fuller <bjcfuller@uri.edu>
 * @author: Alexandra Gauss <alexandra_gauss@uri.edu>
 * @package uri-global-map
 */

// Block direct requests
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


define( 'URI_GLOBAL_MAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'URI_GLOBAL_MAP_URL', str_replace( '/assets', '/', plugins_url( 'assets', __FILE__ ) ) );

// Include the admin settings screen
include_once( URI_GLOBAL_MAP_PATH . 'uri-global-map-settings.php' );

// Include map
//include_once( URI_GLOBAL_MAP_PATH . 'inc/display-map.php' );

/**
 * Include css and js
 */
function uri_global_map_enqueues() {

	wp_register_style( 'uri-global-map-css', plugins_url( '/css/style.built.css', __FILE__ ) );
	wp_enqueue_style( 'uri-global-map-css' );

	wp_register_script( 'uri-global-map-js', plugins_url( '/js/script.built.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'uri-global-map-js' );

	$spreadsheet = get_option( 'uri_global_map_spreadsheet' );
	$token = get_option( 'uri_global_map_token' );
	wp_localize_script(
		 'uri-global-map-js',
		'spreadsheet',
		array(
			'text' => $spreadsheet,
		)
		);
		wp_localize_script(
			'uri-global-map-js',
		   'token',
		   array(
			   'text' => $token,
		   )
		   );

		   wp_register_script( 'csv2geojson-js', plugins_url( '/inc/csv2geojson.js', __FILE__ ) );
		   wp_enqueue_script( 'csv2geojson-js' );

		   wp_register_script( 'mapbox-gl-js', plugins_url( '/inc/mapbox-gl.js', __FILE__ ) );
		   wp_enqueue_script( 'mapbox-gl-js' );

		   wp_register_style( 'mapbox-gl-css', plugins_url( '/inc/mapbox-gl.css', __FILE__ ) );
		   wp_enqueue_style( 'mapbox-gl-css' );

}
add_action( 'wp_enqueue_scripts', 'uri_global_map_enqueues' );


/**  Default attributes */
function uri_global_map_shortcode( $attributes ) {

	$attributes = shortcode_atts(
		 array(
			 'width' => '',
			 'height' => '',
		 ),
		$attributes,
		$shortcode
		);

	echo '
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>';
	ob_start();
		include 'inc/display-map.php';
		ob_end_flush();
}
	add_shortcode( 'uri-global-map', 'uri_global_map_shortcode' );
