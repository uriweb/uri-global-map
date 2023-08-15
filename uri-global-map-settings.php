<?php
/**
 * Create admin settings menu for the Global Map Plugin
 *
 * @package uri-global-map
 */


/**
 * Register settings
 */
function uri_global_map_register_settings() {

	register_setting(
	   'uri_global_map',
	   'uri_global_map_spreadsheet',
	   'uri_global_map_sanitize_url'
	);

	register_setting(
	   'uri_global_map',
	   'uri_global_map_token',
	   'sanitize_text_field'
	);

	add_settings_section(
	   'uri_global_map_settings',
	   __( 'URI Global Map Settings', 'uri' ),
	   'uri_global_map_settings_section',
	   'uri_global_map'
	);

	// register field
	add_settings_field(
	   'uri_global_map_spreadsheet', // id: as of WP 4.6 this value is used only internally
	   __( 'URL of Spreadsheet', 'uri' ), // title
	   'uri_global_map_spreadsheet_field', // callback
	   'uri_global_map', // page
	   'uri_global_map_settings', // section
	   array( // args
		   'label_for' => 'uri-global-map-field-spreadsheet',
		   'class' => 'uri_global_map_row',
	   )
	);

	add_settings_field(
	   'uri_global_map_token', // id: as of WP 4.6 this value is used only internally
	   __( 'Token', 'uri' ), // title
	   'uri_global_map_token_field', // callback
	   'uri_global_map', // page
	   'uri_global_map_settings', // section
	   array( // args
		   'label_for' => 'uri-global-map-field-token',
		   'class' => 'uri_global_map_row',
	   )
	);

}
 add_action( 'admin_init', 'uri_global_map_register_settings' );

/**
 * Callback for a settings section
 *
 * @param arr $args has the following keys defined: title, id, callback.
 * @see add_settings_section()
 */
function uri_global_map_settings_section( $args ) {
	$intro = 'URI Global Map displays a map with data points.';
	echo '<p id="' . esc_attr( $args['id'] ) . '">' . esc_html_e( $intro, 'uri' ) . '</p>';
}

/**
 * Add the settings page to the settings menu
 *
 * @see https://developer.wordpress.org/reference/functions/add_options_page/
 */
function uri_global_map_settings_page() {
	add_options_page(
		__( 'URI Global Map Settings', 'uri' ),
		__( 'URI Global Map', 'uri' ),
		'manage_options',
		'uri-global-map-settings',
		'uri_global_map_settings_page_html'
	);
}
add_action( 'admin_menu', 'uri_global_map_settings_page' );


/**
 * Callback to render the HTML of the settings page.
 * Renders the HTML on the settings page
 */
function uri_global_map_settings_page_html() {
	// check user capabilities
	// on web.uri, we have to leave this pretty loose
	// because web com doesn't have admin privileges.
	if ( ! current_user_can( 'manage_options' ) ) {
		echo '<div id="setting-message-denied" class="updated settings-error notice is-dismissible"> 
<p><strong>You do not have permission to save this form.</strong></p>
<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		return;
	}
	?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
					// output security fields for the registered setting "uri_global_map"
					settings_fields( 'uri_global_map' );
					// output setting sections and their fields
					// (sections are registered for "uri_global_map", each field is registered to a specific section)
					do_settings_sections( 'uri_global_map' );
					// output save settings button
					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
	<?php
}

/**
 * Field callback
 * outputs the field
 *
 * @see add_settings_field()
 * @see uri_today_field_domain_callback()
 */
function uri_global_map_spreadsheet_field( $args ) {
	// get the value of the setting we've registered with register_setting()
	$setting = get_option( 'uri_global_map_spreadsheet' );
	// output the field
	?>
		<input type="text" class="regular-text" aria-describedby="uri-global-map-field-spreadsheet" name="uri_global_map_spreadsheet" id="uri-global-map-field-spreadsheet" value="<?php print ( $setting !== false ) ? esc_attr( $setting ) : ''; ?>">
		<p class="uri-global-map-field-spreadsheet">
			<?php
				esc_html_e( 'Provide the URL for the spreadsheet.', 'uri' );
				echo '<br />';
				esc_html_e( 'For example: https://docs.google.com/spreadsheets/d/[spreadsheetID]/gviz/tq?tqx=out:csv&sheet=Sheet1', 'uri' );
			?>
		</p>
	<?php
}

/**
 * Field callback
 * outputs the field
 *
 * @see add_settings_field()
 * @see uri_today_field_domain_callback()
 */
function uri_global_map_token_field( $args ) {
	// get the value of the setting we've registered with register_setting()
	$setting = get_option( 'uri_global_map_token' );
	// output the field
	?>
		<input type="text" class="regular-text" aria-describedby="uri-global-map-field-token" name="uri_global_map_token" id="uri-global-map-field-token" value="<?php print ( $setting !== false ) ? esc_attr( $setting ) : ''; ?>">
		<p class="uri-global-map-field-token">
			<?php
				esc_html_e( 'Provide the token for the map.', 'uri' );
			?>
		</p>
	<?php
}
