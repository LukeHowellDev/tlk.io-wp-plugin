<?php
/**
 * Base class for operating the plugin
 *
 * @package WordPress
 * @subpackage WP_TlkIo
 */
class WP_TlkIo_Shortcode {
	/**
	 * Render the shortcode and output the results
	 */
	function render_tlkio_shortcode( $atts, $content = null ) {
		// Extract the shortcode attributes to variables
		extract(shortcode_atts( array(
			'channel'    => WP_TLKIO_DEFAULT_CHANNEL,
			'width'      => WP_TLKIO_DEFAULT_WIDTH,
			'height'     => WP_TLKIO_DEFAULT_HEIGHT,
			'stylesheet' => WP_TLKIO_DEFAULT_STYLESHEET
			), $atts) );

		$off_content = !empty( $content ) ? $content : WP_TLKIO_DEFAULT_OFF_CONTENT;
		
		$output = '';

		// Chat room option name
		$channel_option_name = WP_TLKIO_SLUG . '_' . $channel;

		// Get the channel specific options array
		$channel_options = get_option( $channel_option_name, array(
			'ison' => WP_TLKIO_DEFAULT_CHANNEL_STATE
		));

		$channel_options[ 'channel' ]     = $channel;
		$channel_options[ 'width' ]       = $width;
		$channel_options[ 'height' ]      = $height;
		$channel_options[ 'stylesheet' ]  = $stylesheet;
		$channel_options[ 'off_content' ] = $off_content;

		// The current chat room query string to be used
		$onoff_query = $channel_option_name . '_switch';

		if( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ) {
			// The chat room is being turned on or off
			if( isset( $_POST[ $onoff_query ] ) ) {
				if( 'on' == $_POST[ $onoff_query ] )
					$channel_options[ 'ison' ] = true;
				elseif( 'off' == $_POST[ $onoff_query ] )
					$channel_options[ 'ison' ] = false;
			}
		}

		$channel_status = $channel_options[ 'ison' ] ? 'on' : 'off';

		$output .= '<div class="tlkio-channel ' . $channel_status . '" id="wp-tlkio-channel-' . $channel . '">';

		// Display the on/off button if the user is an able to edit posts or pages.
		if( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ) {

			// Image to use for the switch
			$switch_image   = $channel_options[ 'ison' ] ?
		                  WP_TLKIO_URL . 'img/chat-on.png' :
		                  WP_TLKIO_URL . 'img/chat-off.png';

      // Determine the switch state to turn to
			$switch_function  = $channel_options[ 'ison' ] ? 'off' : 'on';

			$output .= '<div class="tlkio-admin">';
			$output .= __( 'This bar is only visible to the admin. Turn chat on / off', WP_TLKIO_SLUG ) . ' &raquo;';
			$output .= '<form method="post"><input id="' . $channel . '" class="tlkio-switch ' . $switch_function . '" type="image" src="' . $switch_image . '" name="' . $onoff_query . '" value="' . $switch_function . '"></form>';
			$output .= '</div>';

			update_option( $channel_option_name, $channel_options );

		}

		// If the chat room is on diplay is, otherwise display the custom message
		if( $channel_options[ 'ison' ] ) {
			$output .= '<div id="tlkio"';
			$output .= ' data-channel="' . $channel . '"';
			$output .= ' style="overflow: hidden;width:' . $width . ';height:' . $height . ';max-width:100%;"';
			$output .= ! empty( $stylesheet ) ? ' stylesheet="' . $stylesheet . '"' : '';
			$output .= '></div>';
			$output .= '<script async src="//tlk.io/embed.js" type="text/javascript"></script>';
		} else {
			$output .= '<div id="chat_is_off">';
			$output .= $off_content;
			$output .= '</div>';
		}

		$output .= '</div>';
		return $output;
	}

	/**
	 * Adds the code for the shortcode form to the footer
	 */
	function add_shortcode_form() {
		echo '
		<div id="wp-tlkio-popup" class="no_preview" style="display:none;">
		    <div id="wp-tlkio-shortcode-wrap">
		        <div id="wp-tlkio-sc-form-wrap">
		            <div id="wp-tlkio-sc-form-head">' . sprintf( __( 'Insert %1$s Shortcode', 'wp-tlkio' ), 'tlk.io' ) . '</div>
		            <form method="post" id="wp-tlkio-sc-form">
		                <table id="wp-tlkio-sc-form-table">
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label">' . sprintf( __( 'Channel', 'wp-tlkio' ) ) . '</td>
		                            <td class="field">
		                                <input name="channel" id="wp-tlkio-channel" class="wp-tlkio-input">
		                                <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify the channel name for the chat room. Leave blank for default channel of %1$s.', 'wp-tlkio' ), '"Lobby"' ) . '</span>
		                            </td>
		                        </tr>
		                    </tbody>
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label">' . sprintf( __( 'Width', 'wp-tlkio' ) ) . '</td>
		                            <td class="field">
		                                <input name="width" id="wp-tlkio-width" class="wp-tlkio-input">
		                                <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify the width of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) . '</span>
		                            </td>
		                        </tr>
		                    </tbody>
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label">' . sprintf( __( 'Height', 'wp-tlkio' ) ) . '</td>
		                            <td class="field">
		                                <input name="height" id="wp-tlkio-height" class="wp-tlkio-input">
		                                <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify the height of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) . '</span>
		                            </td>
		                        </tr>
		                    </tbody>
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label">' . sprintf( __( 'Custom CSS File', 'wp-tlkio' ) ) . '</td>
		                            <td class="field">
		                                <input name="css" id="wp-tlkio-css" class="wp-tlkio-input">
		                                <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify a custom CSS file to use. Leave blank for no custom CSS.', 'wp-tlkio' ) ) . '</span>
		                            </td>
		                        </tr>
		                    </tbody>
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label">' . sprintf( __( 'Chat Is Off Message', 'wp-tlkio' ) ) . '</td>
		                            <td class="field">
		                                <textarea name="offmessage" id="wp-tlkio-off-message" class="wp-tlkio-input wp-tlkio-textarea"></textarea>
		                                <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify the message you want to see when the chat is off.', 'wp-tlkio' ) ) . '</span>
		                            </td>
		                        </tr>
		                    </tbody>
		                    <tbody>
		                        <tr class="form-row">
		                            <td class="label"></td>
		                            <td class="field"><a id="wp-tlkio-submit" href="#" class="button-primary wp-tlkio-insert">' . sprintf( __( 'Insert %1$s Shortcode', 'wp-tlkio' ), 'tlk.io' ) . '</a></td>
		                        </tr>
		                    </tbody>
		                </table>
		            </form>
		        </div>
		        <div class="clear"></div>
		    </div>
		</div>
		';
	}
}