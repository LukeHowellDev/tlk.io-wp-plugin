<?php
/*
Plugin Name: WP tlk.io
Plugin URI: http://www.truemediaconcepts.com
Description: A plugin to integrate tlk.io chat on any page of your website.
Version: 0.1
Author: Brad Bodine, Luke Howell
Author Email: support@truemediaconcepts.com
License:

  Copyright 2011 Brad Bodine, Luke Howell (support@truemediaconcepts.com)

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
		//register an activation hook for the plugin
		// register_activation_hook( __FILE__, array( &$this, 'install_wp_tlkio' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_wp_tlkio' ) );
	}

	/**
	 * Runs when the plugin is activated
	 */
	// function install_wp_tlkio() {
	// 	// do not generate any output here
	// }

	/**
	 * Runs when the plugin is initialized
	 */
	function init_wp_tlkio() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		// Load JavaScript and stylesheets
		// $this->register_scripts_and_styles();

		// Register the shortcode [tlkio]
		add_shortcode( 'tlkio', array( &$this, 'render_tlkio_shortcode' ) );

		// if ( is_admin() ) {
			//this will run when in the WordPress admin
		// } else {
			//this will run when on the frontend
		// }

		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( &$this, 'register_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( &$this, 'register_tinymce_button' ) );
		}

		/*
		 * TODO: Define custom functionality for your plugin here
		 *
		 * For more information:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// add_action( 'your_action_here', array( &$this, 'action_callback_method_name' ) );
		// add_filter( 'your_filter_here', array( &$this, 'filter_callback_method_name' ) );
		// add_action( 'admin_menu', array( &$this, 'wp_tlkio_plugin_menu' ) );
	}

	function render_tlkio_shortcode( $atts ) {
		// Extract the attributes
		extract(shortcode_atts( array(
			'channel'    => 'lobby',
			'width'      => '400px',
			'height'     => 'auto',
			'stylesheet' => ''
			), $atts) );
		
		echo '<div id="tlkio"';
		echo ' data-channel="' . $channel . '"';
		echo ' style="overflow: hidden;width:' . $width . ';height:' . $height . ';"';
		echo ! empty( $stylesheet ) ? ' stylesheet="' . $stylesheet . '"' : '';
		echo '></div>';
		echo '<script async src="//tlk.io/embed.js" type="text/javascript"></script>';
	}

	function register_tinymce_plugin( $plugin_array ) {
		// $a = plugin_url(__FILE__).'wp-tlkio.js';
		// echo $a;
		// die();
		$plugin_array[ 'wp_tlkio' ] = plugins_url( 'wp-tlkio.js', __FILE__ );
		return $plugin_array;
	}

	function register_tinymce_button( $buttons ) {
		array_push( $buttons, 'wp_tlkio' );
		return $buttons;
	}

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	// private function register_scripts_and_styles() {
	// 	if ( is_admin() ) {
	// 		$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
	// 		$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
	// 	} else {
	// 		$this->load_file( self::slug . '-script', '/js/widget.js', true );
	// 		$this->load_file( self::slug . '-style', '/css/widget.css' );
		// } // end if/else
	// } // end register_scripts_and_styles

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	// private function load_file( $name, $file_path, $is_script = false ) {

	// 	$url = plugins_url($file_path, __FILE__);
	// 	$file = plugin_dir_path(__FILE__) . $file_path;

	// 	if( file_exists( $file ) ) {
	// 		if( $is_script ) {
	// 			wp_register_script( $name, $url, array('jquery') ); //depends on jquery
	// 			wp_enqueue_script( $name );
	// 		} else {
	// 			wp_register_style( $name, $url );
	// 			wp_enqueue_style( $name );
	// 		} // end if
	// 	} // end if

	// } // end load_file

} // end class
new WP_TlkIo;