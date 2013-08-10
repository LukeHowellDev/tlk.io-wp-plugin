function tlkio_refresh() {
	var target_element  = document.getElementById('tlkio'),
	    channel_name    = target_element.getAttribute('data-channel'),
	    custom_css_path = target_element.getAttribute('data-theme'),
	    iframe          = document.createElement('iframe');

	var iframe_src = 'http://embed.tlk.io/' + channel_name;

	if (custom_css_path && custom_css_path.length > 0) {
	  iframe_src += ('?custom_css_path=' + custom_css_path);
	}

	iframe.setAttribute('src', iframe_src);
	iframe.setAttribute('width', '100%');
	iframe.setAttribute('height', '100%');
	iframe.setAttribute('frameborder', '0');
	iframe.setAttribute('style', 'margin-bottom: -6px;');

	var current_style = target_element.getAttribute('style');
	target_element.setAttribute('style', 'overflow: auto; -webkit-overflow-scrolling: touch;' + current_style);

	target_element.appendChild(iframe);	
}

jQuery(function($) {
	setInterval(function() {
		$( '.tlkio-channel' ).each(function() {
			var channel = $( this ).attr( 'id' ).split( 'wp-tlkio-channel-' )[1];
			$.post(
				WP_TlkIo.ajaxurl,
				{
					'action': 'wp_tlkio_check_state',
					'channel': channel
				},
				function( response ) {
					result = $.parseJSON( response );
					if( !$( "#wp-tlkio-channel-" + result.channel ).hasClass( result.state ) ) {
						$.post(
							WP_TlkIo.ajaxurl,
							{
								'action': 'wp_tlkio_update_channel',
								'channel': result.channel
							},
							function( response ) {
								result = $.parseJSON( response );
								$( '#wp-tlkio-channel-' + result.channel ).replaceWith( result.shortcode );
								if( 'off' == result.state )
									alert( WP_TlkIo.channel_off_message );
								else
									tlkio_refresh();
							}
						);
					}
				}
			);
		})
	}, 5000);
});