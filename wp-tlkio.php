<?php
/*
Plugin Name: WP tlk.io
Plugin URI: http://truemediaconcepts.com
Description: A plugin to integrate <a href="http://tlk.io">tlk.io chat</a> on any page or post on your website using a shortcode. Insert a shortcode with the shortcode generator located in the WYSIWYG editor. There is currently no options page for this plugin.
Version: 0.3
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

// Defines
define( 'WP_TLKIO_SLUG', 'wp_tlkio' );
define( 'WP_TLKIO_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_TLKIO_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Base class for operating the plugin
 *
 * @package WordPress
 * @subpackage WP_TlkIo
 */
class WP_TlkIo {

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
		// Require necessary files
		require_once( plugin_dir_path( __FILE__ ) . 'inc/tinymce.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'inc/ajax.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'inc/shortcode.php' );

		// Objects for functioning
		$tinymce   = new WP_TlkIo_TinyMce_Plugin;
		$ajax      = new WP_TlkIo_AJAX;
		$shortcode = new WP_TlkIo_Shortcode;

		// Setup localization
		load_plugin_textdomain( WP_TLKIO_SLUG, false, plugin_dir_path( __FILE__ ) . 'lang' );

		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [tlkio]
		add_shortcode( 'tlkio', array( &$shortcode, 'render_tlkio_shortcode' ) );

		// Add code to the admin footer
		add_action( 'in_admin_footer', array( &$shortcode, 'add_shortcode_form' ) );

		// Load the tinymce extras if the user can edit things and has rich editing enabled
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins',   array( &$tinymce, 'register_plugin' ) );
			add_filter( 'mce_buttons',            array( &$tinymce, 'register_button' ) );
		}
	}	

	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	function register_scripts_and_styles() {
		if ( is_admin() )
		{
			wp_register_style( WP_TLKIO_SLUG . '-admin-style', plugins_url( '/css/admin.css', __FILE__ ) );
			wp_enqueue_style( WP_TLKIO_SLUG . '-admin-style' );
		}
	}
}
new WP_TlkIo;