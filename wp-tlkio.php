<?php
/*
Plugin Name: WP tlk.io
Plugin URI: http://truemediaconcepts.com
Description: A plugin to integrate <a href="http://tlk.io">tlk.io chat</a> on any page of your website.
Version: 0.1
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
	const name = 'wp_tlkio';
	const slug = 'wp_tlkio';

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


		// Load the tinymce extras if the user can edit things and has rich editing enabled
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( &$this, 'register_tinymce_plugin' ) );
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
		
		// Display the on/off button if the user is an able to edit posts or pages.
		if( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages') ) {
			// Chat room option name
			$option_name = 'tlkio_chat_' . $channel;
			
			// The current chat room query string to be used
			$onoff = $option_name . '_switch';

			// Get the chat room options
			$chat_room_options = get_option( $option_name, array(
				'ison' => false
			));

			// The chat room is being turned on or off
			if( isset( $_GET[ $onoff ] ) ) {
				if( 'on' == $_GET[ $onoff ] )
					$chat_room_options[ 'ison' ] = true;
				elseif( 'off' == $_GET[ $onoff ] )
					$chat_room_options[ 'ison' ] = false;
			}

			$is_chat_on = get_option( $chat_option, false );

			$switch_link =  $is_chat_on ? 
				'<a href="' . add_query_arg( 'tlkio_chat', 'off', remove_query_arg( 'tlkio_chat' ) ) . '"><img style="width:30px;padding-left:10px;" src="' . plugins_url( 'img/chat-on.png', __FILE__ ) . '"></a>' : 
				'<a href="' . add_query_arg( 'tlkio_chat', 'on', remove_query_arg( 'tlkio_chat' ) ) . '"><img style="width:30px;padding-left:10px;" src="' . plugins_url( 'img/chat-off.png', __FILE__ ) . '"></a>';

			echo '<div id="tlkio-switch" style="margin-bottom:5px;text-align:right;background: rgba(0,0,0,0.5);border-radius: 5px;padding: 2px 7px 2px 2px;font-family: sans-serif;color: #fff;font-size: 0.8em;">This bar is only visible to the admin. Turn chat on / off &raquo;' . $switch_link . '</div>';

			// Variable to hold is the chat room on or off
			$is_chat_on = $chat_room_options[ 'ison' ];

			// Link for the switch detmined based on whether the channel is on or off
			$switch_link =  $is_chat_on ?
				'<a href="' . add_query_arg( $onoff, 'off', remove_query_arg( $onoff ) ) . '"><img style="width:50px;" src="' . plugins_url( 'img/chat-on.png',  __FILE__ ) . '"></a>' : 
				'<a href="' . add_query_arg( $onoff, 'on',  remove_query_arg( $onoff ) ) . '"><img style="width:50px;" src="' . plugins_url( 'img/chat-off.png', __FILE__ ) . '"></a>';
			echo '<div id="tlkio-switch" style="margin-bottom:5px;text-align:right;">' . $switch_link . '</div>';

			update_option( $option_name, $chat_room_options );

		}

		// If the chat room is on diplay is, otherwise display the custom message
		if( $is_chat_on ) {
			echo '<div id="tlkio"';
			echo ' data-channel="' . $channel . '"';
			echo ' style="overflow: hidden;width:' . $width . ';height:' . $height . ';max-width:100%;"';
			echo ! empty( $stylesheet ) ? ' stylesheet="' . $stylesheet . '"' : '';
			echo '></div>';
			echo '<script async src="//tlk.io/embed.js" type="text/javascript"></script>';
		} else {
			echo '<div id="chat_is_off">';
			if( !empty( $content ) )
				echo $content;
			else
				_e( 'This chat is currently disabled.', self::slug );
			echo '</div>';
		}
	}

	/**
	 * Registers the tinymce plugin for the shortcode form
	 */
	function register_tinymce_plugin( $plugin_array ) {
		$plugin_array[ self::slug ] = plugins_url( 'js/tinymce-plugin.js', __FILE__ );
		return $plugin_array;
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
	private function register_scripts_and_styles() {
		if ( is_admin() )
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
	}

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name				The ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') );
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			}
		}
	}
}
new WP_TlkIo;