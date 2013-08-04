<?php # -*- coding: utf-8 -*-

$strings = '
tinyMCE.addI18n(
	{' . _WP_Editors::$mce_locale . ':
    { wp_tlkio:
      {
        insert_shortcode: "' . esc_js( sprintf( __( 'Insert %1$s Shortcode', 'wp-tlkio' ), 'tlk.io' ) ) . '",
        channel_title: "'    . esc_js( sprintf( __( 'Channel', 'wp-tlkio' ) ) ) . '",
        channel_desc: "'     . esc_js( sprintf( __( 'Specify the channel name for the chat room. Leave blank for default channel of %1$s.', 'wp-tlkio' ), '"Lobby"' ) ) . '",
        width_title: "'      . esc_js( sprintf( __( 'Width', 'wp-tlkio' ) ) ) . '",
        width_desc: "'       . esc_js( sprintf( __( 'Specify the width of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) ) . '",
        height_title: "'     . esc_js( sprintf( __( 'Height', 'wp-tlkio' ) ) ) . '",
        height_desc: "'      . esc_js( sprintf( __( 'Specify the height of the chat. Leave blank for the default of %1$s.', 'wp-tlkio' ), '400px' ) ) . '",
        css_title: "'        . esc_js( sprintf( __( 'Custom CSS File', 'wp-tlkio' ) ) ) . '",
        css_desc: "'         . esc_js( sprintf( __( 'Specify a custom CSS file to use. Leave blank for no custom CSS.', 'wp-tlkio' ) ) ) . '",
        offmessage_title: "' . esc_js( sprintf( __( 'Chat Is Off Message', 'wp-tlkio' ) ) ) . '",
        offmessage_desc: "'  . esc_js( sprintf( __( 'Specify the message you want to see when the chat is off.', 'wp-tlkio' ) ) ) . '"
      }
    }
  }
)';