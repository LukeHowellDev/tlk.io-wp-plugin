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
			if( isset( $_GET[ $onoff_query ] ) ) {
				if( 'on' == $_GET[ $onoff_query ] )
					$channel_options[ 'ison' ] = true;
				elseif( 'off' == $_GET[ $onoff_query ] )
					$channel_options[ 'ison' ] = false;
			}

			// Link for the switch detmined based on whether the channel is on or off
			$switch_link  = $channel_options[ 'ison' ] ?
			                add_query_arg( $onoff_query, 'off', remove_query_arg( $onoff_query ) ) :
			                add_query_arg( $onoff_query, 'on',  remove_query_arg( $onoff_query ) );

     	// Image to use for the switch
			$switch_image = $channel_options[ 'ison' ] ?
			                plugins_url( 'img/chat-on.png',  __FILE__ ) :
			                plugins_url( 'img/chat-off.png', __FILE__ );

			echo '<div id="tlkio-switch" style="margin-bottom:5px;text-align:right;background: rgba(0,0,0,0.5);border-radius:5px;padding:2px 7px 2px 2px;font-family:sans-serif;color:#fff;font-size:0.8em;">';
			echo __( 'This bar is only visible to the admin. Turn chat on / off', self::slug ) . ' &raquo;';
			echo '<a href="' . $switch_link . '"><img src="' . $switch_image . '" alt="tlkio-switch" style="width:20px;"></a>';
			echo '</div>';

			update_option( $channel_option_name, $channel_options );

		}

		// If the chat room is on diplay is, otherwise display the custom message
		if( $channel_options[ 'ison' ] ) {
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
	private function register_scripts_and_styles() {
		if ( is_admin() )
		{
			wp_register_style( self::slug . '-admin-style', plugins_url( '/css/admin.css', __FILE__ ) );
			wp_enqueue_style( self::slug . '-admin-style' );
		}
	}
}
new WP_TlkIo;