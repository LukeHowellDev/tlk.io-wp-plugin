<?php
/*
Plugin Name: WP tlk.io
Plugin URI: http://truemediaconcepts.com
Description: A plugin to integrate <a href="http://tlk.io">tlk.io chat</a> on any page or post on your website using a shortcode. Insert a shortcode with the shortcode generator located in the WYSIWYG editor. There is currently no options page for this plugin.
Version: 0.2
Author URI: http://truemediaconcepts.com/
Author: True Media Concepts
Author Email: support@truemediaconcepts.com
License: GPL2

  Copyright 2013 Brad Bodine, Luke Howell (support@truemediaconcepts.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * Base class for operating the plugin
 *
 * @package WordPress
 * @subpackage WP_TlkIo
 * @author Luke Howell <luke@truemediaconcepts.com>
 */
class WP_TlkIo {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const slug        = 'wp_tlkio';
	const option_base = 'wp_tlkio';

	/**
	 * Constructor
	 */
	function __construct() {
		// Hook to the init action in WordPress
		add_action( 'init', array( &$this, 'init_wp_tlkio' ) );
	}

	/**
	 * Runs when the plugin is initialized
	 */
	function init_wp_tlkio() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [tlkio]
		add_shortcode( 'tlkio', array( &$this, 'render_tlkio_shortcode' ) );

		// Add code to the admin footer
		add_action( 'in_admin_footer', array( &$this, 'add_shortcode_form' ) );

		// Load the tinymce extras if the user can edit things and has rich editing enabled
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( &$this, 'register_tinymce_plugin' ) );
			add_filter( 'mce_external_languages', array( &$this, 'localize_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( &$this, 'register_tinymce_button' ) );
		}
	}

	/**
	 * Render the shortcode and output the results
	 */
	function render_tlkio_shortcode( $atts, $content = null ) {
		// Extract the shortcode attributes to variables
		extract(shortcode_atts( array(
			'channel'    => 'lobby',
			'width'      => '400px',
			'height'     => '400px',
			'stylesheet' => ''
			), $atts) );

		$output = '';

		// Chat room option name
		$channel_option_name = self::option_base . '_' . $channel;

		// Get the channel specific options array
		$channel_options = get_option( $channel_option_name, array(
			'ison' => false
		));
		
		// Display the on/off button if the user is an able to edit posts or pages.
		if( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ) {
			
			// The current chat room query string to be used
			$onoff_query = $channel_option_name . '_switch';

			// The chat room is being turned on or off
			if( isset( $_POST[ $onoff_query ] ) ) {
				if( 'on' == $_POST[ $onoff_query ] )
					$channel_options[ 'ison' ] = true;
				elseif( 'off' == $_POST[ $onoff_query ] )
					$channel_options[ 'ison' ] = false;
			}

     	// Image to use for the switch
			$switch_image   = $channel_options[ 'ison' ] ?
			                  plugins_url( 'img/chat-on.png',  __FILE__ ) :
			                  plugins_url( 'img/chat-off.png', __FILE__ );

			// Determine the switch state to turn to
			$switch_function  = $channel_options[ 'ison' ] ? 'off' : 'on';

			$output .= '<div id="tlkio-switch" style="margin-bottom:5px;text-align:right;background: rgba(0,0,0,0.5);border-radius:5px;padding:2px 7px 2px 2px;font-family:sans-serif;color:#fff;font-size:0.8em;">';
			$output .= __( 'This bar is only visible to the admin. Turn chat on / off', self::slug ) . ' &raquo;';
			$output .= '<form method="post" style="float:right;"><input type="image" src="' . $switch_image . '" name="' . $onoff_query . '" value="' . $switch_function . '" style="border:none;width:20px;padding:0;"></form>';
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
			if( !empty( $content ) )
				$output .= $content;
			else
				_e( 'This chat is currently disabled.', self::slug );
			$output .= '</div>';
		}
		return $output;
	}

	/**
	 * Registers the tinymce plugin for the shortcode form
	 */
	function register_tinymce_plugin( $plugin_array ) {
		$plugin_array[ self::slug ] = plugins_url( 'js/tinymce-plugin.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Registers the tinymce plugin for the shortcode form
	 */
	function localize_tinymce_plugin( $lang_array ) {
		$lang[ self::slug ] = plugin_dir_path( __FILE__ ) . 'inc/tinymce-lang.php';
		return $lang_array;
	}

	/**
	 * Adds the tinymce button for the shortcode form
	 */
	function register_tinymce_button( $buttons ) {
		array_push( $buttons, self::slug );
		return $buttons;
	}

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	function register_scripts_and_styles() {
		if ( is_admin() )
		{
			wp_register_style( self::slug . '-admin-style', plugins_url( '/css/admin.css', __FILE__ ) );
			wp_enqueue_style( self::slug . '-admin-style' );
		}
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
new WP_TlkIo;