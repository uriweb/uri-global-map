<?php
/**
 * Plugin Name: URI Global Map
 * Plugin URI: http://www.uri.edu
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

/**
 * Include css and js
 */
//function uri_plugin_template_enqueues() {

	//wp_register_style( 'uri-global-map-css', plugins_url( '/css/style.built.css', __FILE__ ) );
	//wp_enqueue_style( 'uri-global-map-css' );

	//wp_register_script( 'uri-global-map-js', plugins_url( '/js/script.built.js', __FILE__ ) );
	//wp_enqueue_script( 'uri-global-map-js' );

//}
//add_action( 'wp_enqueue_scripts', 'uri_global_map_enqueues' );

// Include shortcodes
//include( URI_GLOBAL_MAP_DIR_PATH . 'inc/uri-global-map-shortcodes.php' );


function uri_global_map_shortcode( $attributes) {

    // default attributes
    $attributes = shortcode_atts( array(
        'spreadsheet' => '',
        'token' => '',
        'width' => '',
        'height' => '',
    ), $attributes, $shortcode );

echo '
<link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
<script src="https://npmcdn.com/csv2geojson@latest/csv2geojson.js"></script>
<script src="https://npmcdn.com/@turf/turf/turf.min.js"></script>
<link href="'.plugins_url(). '/uri-global-map/css/style.built.css" rel="stylesheet" />


<div class="container">
<div id="map">
<button id="btn-spin">Pause rotation</button>
<div id="menu">
    <input id="globe" type="radio" name="rtoggle" value="globe" checked="checked">
    <label for="globe">Globe</label>
    <input id="flat" type="radio" name="rtoggle" value="mercator">
    <label for="flat">Flat Map</label>
  </div>
</div>
</div>
<script src="' .plugins_url(). '/uri-global-map/js/script.built.js"></script>


';

  }
    add_shortcode( 'uri-global-map', 'uri_global_map_shortcode' );
