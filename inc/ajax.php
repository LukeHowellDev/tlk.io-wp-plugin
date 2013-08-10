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
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, array(
			'ison'        => WP_TLKIO_DEFAULT_CHANNEL_STATE,
			'channel'     => WP_TLKIO_DEFAULT_CHANNEL,
			'width'       => WP_TLKIO_DEFAULT_WIDTH,
			'height'      => WP_TLKIO_DEFAULT_HEIGHT,
			'stylesheet'  => WP_TLKIO_DEFAULT_STYLESHEET,
			'off_content' => WP_TLKIO_DEFAULT_OFF_CONTENT
		));
		$channel_options[ 'ison' ] = !$channel_options[ 'ison' ];
		update_option( $channel_option_name, $channel_options );
		$result[ 'shortcode' ] = do_shortcode( '[tlkio channel="' . $channel_options[ 'channel' ]  . '" 
			                         width="' . $channel_options[ 'width' ] . '" 
			                         height="' . $channel_options[ 'height' ] . '" 
			                         stylesheet="' . $channel_options[ 'stylesheet' ] .'"]' . $channel_options[ 'off_content' ] . '[/tlkio]' );
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		$result[ 'channel' ] = $_POST[ 'channel' ];
		echo json_encode( $result );
		die();
	}

	/**
	 * Get state of channel
	 */
	function channel_state() {
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, array(
			'ison'        => WP_TLKIO_DEFAULT_CHANNEL_STATE,
			'channel'     => WP_TLKIO_DEFAULT_CHANNEL,
			'width'       => WP_TLKIO_DEFAULT_WIDTH,
			'height'      => WP_TLKIO_DEFAULT_HEIGHT,
			'stylesheet'  => WP_TLKIO_DEFAULT_STYLESHEET,
			'off_content' => WP_TLKIO_DEFAULT_OFF_CONTENT
		));
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		$result[ 'channel' ] = $_POST[ 'channel' ];
		echo json_encode( $result );
		die();
	}

	/**
	 * Get refresh of the shortcode
	 */
	function refresh_channel() {
		$channel_option_name = WP_TLKIO_SLUG . '_' . $_POST[ 'channel' ];
		$channel_options = get_option( $channel_option_name, array(
			'ison'        => WP_TLKIO_DEFAULT_CHANNEL_STATE,
			'channel'     => WP_TLKIO_DEFAULT_CHANNEL,
			'width'       => WP_TLKIO_DEFAULT_WIDTH,
			'height'      => WP_TLKIO_DEFAULT_HEIGHT,
			'stylesheet'  => WP_TLKIO_DEFAULT_STYLESHEET,
			'off_content' => WP_TLKIO_DEFAULT_OFF_CONTENT
		));
		$result[ 'shortcode' ] = do_shortcode( '[tlkio channel="' . $channel_options[ 'channel' ]  . '" 
			                         width="' . $channel_options[ 'width' ] . '" 
			                         height="' . $channel_options[ 'height' ] . '" 
			                         stylesheet="' . $channel_options[ 'stylesheet' ] .'"]' . $channel_options[ 'off_content' ] . '[/tlkio]' );
		$result[ 'channel' ] = $_POST[ 'channel' ];
		$result[ 'state' ] = $channel_options[ 'ison' ] ? 'on' : 'off';
		echo json_encode( $result );
		die();
	}
}