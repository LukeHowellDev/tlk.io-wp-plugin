<?php
/*
Plugin Name: wp_tlkio
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
		register_activation_hook( __FILE__, array( &$this, 'install_wp_tlkio' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_wp_tlkio' ) );
	}

	/**
	 * Runs when the plugin is activated
	 */
	function install_wp_tlkio() {
		// do not generate any output here
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

		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
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

	function wp_tlkio_plugin_menu() {
		add_options_page( 'WP TlkIo Options', 'WP TlkIo', 'manage_options', 'wp_tlkio', array( &$this, 'wp_tlkio_plugin_options' ) );
	}

	function wp_tlkio_plugin_options() {
		if( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap">';
		echo '<p>Here is where the page will go.</p>';
		echo '</div>';
	}

	function render_tlkio_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'channel'    => 'lobby',
			'width'      => '400px',
			'height'     => '400px',
			'stylesheet' => ''
			), $atts));
		
		echo '<div id="tlkio"';
		echo ' data-channel="' . $channel . '"';
		echo ' style="overflow: hidden;width:' . $width . ';height:' . $height . ';"';
		echo ! empty( $stylesheet ) ? ' stylesheet="' . $stylesheet . '"' : '';
		echo '></div>';
		echo '<script async src="//tlk.io/embed.js" type="text/javascript"></script>';
	}

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
		} else {
			$this->load_file( self::slug . '-script', '/js/widget.js', true );
			$this->load_file( self::slug . '-style', '/css/widget.css' );
		} // end if/else
	} // end register_scripts_and_styles

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file

} // end class
new wp_tlkio();