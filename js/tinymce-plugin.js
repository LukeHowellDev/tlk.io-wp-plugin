(function() {
    tinymce.create('tinymce.plugins.wp_tlkio', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addButton('wp_tlkio', {
                title : 'Insert tlk.io Shortcode',
                cmd : 'wp_tlkio',
                image : url + '/../img/tinymce-button.png'
            });
 
            ed.addCommand('wp_tlkio', function() {
                // triggers the thickbox
                var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                W = W - 80;
                H = H - 84;
                tb_show( 'WP tlk.io Plugin', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=wp-tlkio-popup' );
            });
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'TlkIo Chat Room Button',
                author : 'Luke Howell & Brad Bodine',
                authorurl : 'http://www.truemediaconcepts.com',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'wp_tlkio', tinymce.plugins.wp_tlkio );

    // executes this when the DOM is ready
    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this
        var form = jQuery('<div id="wp-tlkio-popup" class="no_preview">\
                            <div id="wp-tlkio-shortcode-wrap">\
                                <div id="wp-tlkio-sc-form-wrap">\
                                    <div id="wp-tlkio-sc-form-head">Insert tlk.io Shortcode</div>\
                                    <form method="post" id="wp-tlkio-sc-form">\
                                        <div id="_zilla_shortcode" class="hidden">[zilla_alert style="{{style}}"] {{content}} [/zilla_alert]</div>\
                                        <div id="_zilla_popup" class="hidden">alert</div>\
                                        <table id="wp-tlkio-sc-form-table">\
                                            <div id="_zilla_ushortcode" class="hidden">[zilla_alert style="white"] Your Alert! [/zilla_alert]</div>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label">Channel</td>\
                                                    <td class="field">\
                                                        <input name="channel" id="wp-tlkio-channel" class="wp-tlkio-input">\
                                                        <span class="wp-tlkio-form-desc">Specify the channel name for the chat room. Leave blank for default channel of "Lobby".</span>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label">Width</td>\
                                                    <td class="field">\
                                                        <input name="width" id="wp-tlkio-width" class="wp-tlkio-input">\
                                                        <span class="wp-tlkio-form-desc">Specify the width of the chat. Leave blank for the default of 400px.</span>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label">Height</td>\
                                                    <td class="field">\
                                                        <input name="height" id="wp-tlkio-height" class="wp-tlkio-input">\
                                                        <span class="wp-tlkio-form-desc">Specify the height of the chat. Leave blank for the default of 400px.</span>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label">Custom CSS File</td>\
                                                    <td class="field">\
                                                        <input name="css" id="wp-tlkio-css" class="wp-tlkio-input">\
                                                        <span class="wp-tlkio-form-desc">Specify a custom CSS file to use. Leave blank for no custom CSS.</span>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label">Chat Off Message</td>\
                                                    <td class="field">\
                                                        <textarea name="offmessage" id="wp-tlkio-off-message" class="wp-tlkio-input wp-tlkio-textarea"></textarea>\
                                                        <span class="wp-tlkio-form-desc">Specify the message you want to see when the chat is off.</span>\
                                                    </td>\
                                                </tr>\
                                            </tbody>\
                                            <tbody>\
                                                <tr class="form-row">\
                                                    <td class="label"></td>\
                                                    <td class="field"><a id="wp-tlkio-submit" href="#" class="button-primary wp-tlkio-insert">Insert Shortcode</a></td>\
                                                </tr>\
                                            </tbody>\
                                        </table>\
                                    </form>\
                                </div>\
                                <div class="clear"></div>\
                            </div>\
                        </div>');
        
        var table = form.find('table');
        form.appendTo('body').hide();
        
        // handles the click event of the submit button
        form.find('#wp-tlkio-submit').click(function(){
            // defines the options and their default values
            // again, this is not the most elegant way to do this
            // but well, this gets the job done nonetheless
            var options = { 
                'channel'    : '',
                'width'      : '',
                'height'     : '',
                'css'        : ''
                };
            var shortcode = '[tlkio';
            
            for( var index in options) {
                var value = table.find('#wp-tlkio-' + index).val();
                
                // attaches the attribute to the shortcode only if it's different from the default value
                if ( value !== options[index] )
                    shortcode += ' ' + index + '="' + value + '"';
            }
            
            var value = jQuery( '#wp-tlkio-off-message' ).val();
            shortcode += ']' + value + '[/tlkio]';
            
            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
            
            // closes Thickbox
            tb_remove();
        });
    });
})();