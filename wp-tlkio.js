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
                title : 'Chat Room',
                cmd : 'wp_tlkio',
                image : url + '/wp-tlkio.png'
            });
 
            ed.addCommand('wp_tlkio', function() {
                // triggers the thickbox
                var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                W = W - 80;
                H = H - 84;
                tb_show( 'WP tlk.io Plugin', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=wp-tlkio-form' );
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
        var form = jQuery('<div id="wp-tlkio-form"><table id="wp-tlkio-table" class="form-table">\
            <tr>\
                <th><label for="wp-tlkio-channel">Channel</label></th>\
                <td><input type="text" id="wp-tlkio-channel" name="channel" value="" /><br />\
                <small>specify the channel name for the chat room.  Leave blank for default channel of "Lobby".</small></td>\
            </tr>\
            <tr>\
                <th><label for="wp-tlkio-width">Width</label></th>\
                <td><input type="text" name="width" id="wp-tlkio-width" value="" /><br />\
                <small>specify the width of the chat.  Leave blank for the default of 400px.</small>\
            </tr>\
            <tr>\
                <th><label for="wp-tlkio-height">Height</label></th>\
                <td><input type="text" name="height" id="wp-tlkio-height" value="" /><br />\
                <small>specify the height of the chat.  Leave blank for the default of 400px.</small>\
            </tr>\
            <tr>\
                <th><label for="wp-tlkio-css">Custom CSS File</label></th>\
                <td><input type="text" name="css" id="wp-tlkio-css" value="" /><br />\
                <small>specify a custom CSS file to use.  Leave blank for no custom CSS.</small>\
            </tr>\
        </table>\
        <p class="submit">\
            <input type="button" id="wp-tlkio-submit" class="button-primary" value="Insert Chat Room" name="submit" />\
        </p>\
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
            
            shortcode += ']';
            
            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
            
            // closes Thickbox
            tb_remove();
        });
    });
})();