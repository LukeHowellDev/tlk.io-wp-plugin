<?php
/**
 * Base class for operating the plugin
 *
 * @package WordPress
 * @subpackage WP_TlkIo
 */
class WP_TlkIo_AJAX {
	/**
	 * Turn chat room on or off
	 */
	function update_channel_state() {
		global $wp_tlkio_options_default;
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, $wp_tlkio_options_default );
		$channel_options[ 'ison' ] = !$channel_options[ 'ison' ];
		update_option( $channel_option_name, $channel_options );
		$result[ 'shortcode' ] = do_shortcode( '[tlkio channel="' . $channel_options[ 'channel' ]  . '" 
			                         width="' . $channel_options[ 'width' ] . '" 
			                         height="' . $channel_options[ 'height' ] . '" 
			                         stylesheet="' . $channel_options[ 'stylesheet' ] .'"]' . $channel_options[ 'default_content' ] . '[/tlkio]' );
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		$result[ 'channel' ] = $_POST[ 'channel' ];
		echo json_encode( $result );
		die();
	}

	/**
	 * Get state of channel
	 */
	function channel_state() {
		global $wp_tlkio_options_default;
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, $wp_tlkio_options_default );
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		$result[ 'channel' ] = $_POST[ 'channel' ];
		echo json_encode( $result );
		die();
	}

	/**
	 * Get refresh of the shortcode
	 */
	function refresh_channel() {
		global $wp_tlkio_options_default;
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, $wp_tlkio_options_default );
		$result[ 'shortcode' ] = do_shortcode( '[tlkio channel="' . $channel_options[ 'channel' ]  . '" 
			                         width="' . $channel_options[ 'width' ] . '" 
			                         height="' . $channel_options[ 'height' ] . '" 
			                         stylesheet="' . $channel_options[ 'stylesheet' ] .'"]' . $channel_options[ 'default_content' ] . '[/tlkio]' );
		$result[ 'channel' ] = $_POST[ 'channel' ];
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		echo json_encode( $result );
		die();
	}
}