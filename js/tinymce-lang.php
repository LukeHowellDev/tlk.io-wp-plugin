<?php # -*- coding: utf-8 -*-

$strings = '
tinyMCE.addI18n(
	{' . _WP_Editors::$mce_locale . '.extrastrings:
    {
      shortcode_form_title: "' . esc_js( sprintf( __( 'Insert %1$s Shortcode', 'wp-tlkio' ), 'tlk.io' ) ) . '",
      shortcode_channel_title: "' . esc_js( sprintf( __( 'Channel', 'wp-tlkio' ) ) ) . '",
      shortcode_channel_desc: "' . esc_js( sprintf( __( 'Specify the channel name for the chat room. Leave blank for default channel of %1$s.', 'wp-tlkio' ), '"Lobby"' ) ) . '",
      shortcode_width_title: "' . esc_js( sprintf( __( 'Width', 'wp-tlkio' ) ) ) . '",
      shortcode_width_desc: "' . esc_js( sprintf( __( 'Specify the width of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) ) . '",
      shortcode_height_title: "' . esc_js( sprintf( __( 'Height', 'wp-tlkio' ) ) ) . '",
      shortcode_height_desc: "' . esc_js( sprintf( __( 'Specify the height of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) ) . '",
      shortcode_css_title: "' . esc_js( sprintf( __( 'Custom CSS File', 'wp-tlkio' ) ) ) . '",
      shortcode_css_desc: "' . esc_js( sprintf( __( 'Specify a custom CSS file to use. Leave blank for no custom CSS.', 'wp-tlkio' ) ) ) . '",
      shortcode_offmessage_title: "' . esc_js( sprintf( __( 'Chat Is Off Message', 'wp-tlkio' ) ) ) . '",
      shortcode_offmessage_desc: "' . esc_js( sprintf( __( 'Specify the message you want to see when the chat is off.', 'wp-tlkio' ) ) ) . '"
    }
  }
)';