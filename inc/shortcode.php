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
	function render_tlkio_shortcode( $atts, $content = '' ) {
		global $wp_tlkio_shortcode_defaults, $wp_tlkio_options_default ;

		// Extract the shortcode attributes to variables
		extract(shortcode_atts( $wp_tlkio_shortcode_defaults, $atts) );

		// Chat room option name
		$channel_option_name = WP_TLKIO_SLUG . '_' . $channel;

		// Get the channel specific options array
		$channel_options = get_option( $channel_option_name, $wp_tlkio_options_default);

		$channel_options[ 'channel' ]         = $channel;
		$channel_options[ 'width' ]           = $width;
		$channel_options[ 'height' ]          = $height;
		$channel_options[ 'stylesheet' ]      = $stylesheet;
		$channel_options[ 'offclass' ]        = $offclass;
		$channel_options[ 'activated' ]       = $activated;
		$channel_options[ 'deactivated' ]     = $deactivated;
		$channel_options[ 'default_content' ] = $content;
		
		$output = '';

		$channel_status = $channel_options[ 'ison' ] ? 'on' : 'off';

		$admin = current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ? ' admin' : '';

		$output .= '<div class="tlkio-channel ' . $channel_status . $admin . '" id="wp-tlkio-channel-' . $channel . '">';

		// Display the on/off button if the user is an able to edit posts or pages.
		if( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ) {

			// Image to use for the switch
			$switch_image   = $channel_options[ 'ison' ] ?
		                  WP_TLKIO_URL . 'img/chat-on.png' :
		                  WP_TLKIO_URL . 'img/chat-off.png';

      // Determine the switch state to turn to
			$switch_function  = $channel_options[ 'ison' ] ? 'off' : 'on';

			$offchecked = $channel_options[ 'ison' ] ? '' : ' checked';
			$onchecked = $channel_options[ 'ison' ] ? ' checked' : '';

			$output .=
			'
			<div class="tlkio-admin" style="width:' . $channel_options[ 'width' ] . ';">
				<form method="post" class="tlkio-switch">
					<div class="container">
						<div class="switch">
							<input type="radio" name="' . $channel_options[ 'channel' ] . '" value="off" id="switch-off"' . $offchecked . '>
							<input type="radio" name="' . $channel_options[ 'channel' ] . '" value="on"  id="switch-on"'  . $onchecked  . '>
							<label for="switch-off">Off</label>
							<label for="switch-on">On</label>
							<span class="toggle"></span>
						</div> 
					</div> 
				</form>
			</div>
			';
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
			$class = empty( $channel_options[ 'offclass' ] ) ? '' : ' class="' . $channel_options[ 'offclass' ] . '"';
			$output .= '<div' . $class . '>';
			$output .= empty( $channel_options[ 'default_content' ] ) ? $wp_tlkio_options_default[ 'default_content' ] : $channel_options[ 'default_content' ];;
			$output .= '</div>';
		}

		$output .= '</div>';

		update_option( $channel_option_name, $channel_options );
		
		return $output;
	}

	/**
	 * Adds the code for the shortcode form to the footer
	 */
	function add_shortcode_form() {
		echo '
		<div id="wp-tlkio-popup" class="no_preview" style="display:none;">
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
	                          <td class="label">' . sprintf( __( 'Off Class', 'wp-tlkio' ) ) . '</td>
	                          <td class="field">
	                              <input name="css" id="wp-tlkio-offclass" class="wp-tlkio-input">
	                              <span class="wp-tlkio-form-desc">' . sprintf( __( 'Class that will wrap the message displayed when the chat is off.  Defaults to class="offmessage"', 'wp-tlkio' ) ) . '</span>
	                          </td>
	                      </tr>
	                  </tbody>
	                  <tbody>
	                      <tr class="form-row">
	                          <td class="label">' . sprintf( __( 'Activated Message', 'wp-tlkio' ) ) . '</td>
	                          <td class="field">
	                              <input name="activated" id="wp-tlkio-activated" class="wp-tlkio-input">
	                              <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify a custom message to display when the chat is activated while a user is on the page.', 'wp-tlkio' ) ) . '</span>
	                          </td>
	                      </tr>
	                  </tbody>
	                  <tbody>
	                      <tr class="form-row">
	                          <td class="label">' . sprintf( __( 'Deactivated Message', 'wp-tlkio' ) ) . '</td>
	                          <td class="field">
	                              <input name="deactivated" id="wp-tlkio-deactivated" class="wp-tlkio-input">
	                              <span class="wp-tlkio-form-desc">' . sprintf( __( 'Specify a custom message to display when the chat is deactivated while a user is on the page.', 'wp-tlkio' ) ) . '</span>
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
		';
	}
}