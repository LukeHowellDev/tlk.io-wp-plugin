=== WP tlk.io ===
Contributors: snumb130, bbodine1
Donate link: 
Tags: chat, tlk.io, tlkio
Requires at least: 2.8
Tested up to: 3.6
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to integrate tlk.io chat on any page of your website. tlk.io [talk·eeo] is a web chat that is open to anyone and channels are owner-free.

== Description ==

A plugin to integrate [tlk.io chat](http://tlk.io) on any page of your website.  The plugin will allow users to insert a tlk.io chatroom in a post or page with a shortcode. There is currently no options page to adjust settings. All customs settings are done through the shortcode generator located in the WYSIWYG editor of all pages and posts. Look for the blue tlk.io logo icon (blue cloud) in the editor.

For more information about tlk.io chat visit the [tlk.io](http://tlk.io) website.

== Installation ==

1. Upload `wp-tlkio` folder to the `/wp-content/plugins/` directory
1. Activate the plugin 'WP tlk.io' through the 'Plugins' menu in WordPress
1. Place `[tlkio]` in your pages or posts

== Frequently asked questions ==

= What short code do I use? =

`[tlkio]`
or
`[tlkio channel="lobby" width="100%" height="500px" css="http://yourdomain.com/pathtoyour.css" activated="The chat has been activated." deactivated="The chat has been deactivated."]Chat is currently off. Check back later.[/tlkio]`

= What short code options do I have? =

* _channel_         - The name of the channel that you want to use. ex. 'lobby' or 'somethingrandom21'
* _width_           - How wide will the chat window be? use percentage or pixel width. ex. '100%' or '500px'
* _height_          - How high will the chat window be? use percentage or pixel width. ex. '100%' or '500px'
* _float_           - Where with the channel be floated when less than 100% width. ex. 'right' or 'left'. Default is none.
* _css_             - Link to an external stylesheet to easily add custom style to the embedded tlk.io chat. ex. 'http://yourdomain.com/custom.css'
* _offclass_        - Class to use for the message displayed when chat is off.  To get the default style set this to 'offmessage'. `offclass="offmessage"`
* _activated_       - Message to show if the chat is activated while users are on the page
* _deactivated_     - Message to show if the chat is deactivated while users are on the page
* _Offline Message_ - This can tell the users of your webpage that you currently have the on page chat turned off. ex. 'Plain text message of what you want to say'

== Screenshots ==

1. Chat demo

== Changelog ==

= 0.4 =
* Adding AJAX to turn the chats on and off.
* Adding AJAX to refresh users page if the chat is turned off during session.
* Reorganized the files and variables.
* Added option to specify message to show if the chat is activated while users are on the page
* Added option to specify message to show if the chat is deactivated while users are on the page

= 0.3 =
* Fixed shortcode error that was echoing output instead of returning.
* Changed the on/off switch from link to a form(POST method).

= 0.2 =
* Update to the readme.txt with better instructions for use.

= 0.1 =
* Initial version

== Upgrade notice ==

= 0.4 =
* Styling has been added to the message displayed when chat is off.  If you want to remove the styling add a shortcode option of `offclass=""`.  You can alternatively add a custom class to that option and style it how you want.
* AJAX has been added to the plugin for controlling the chat room state.
* Users currently on the page will have chat autorefresh after admin changes the state.
* New shortcode option (activated) to show a message to the users if the chat is activated while they are on the page.
* New shortcode option (deactivated) to show a message to the users if the chat is deactivated while they are on the page.

= 0.3 =
* Fixes possible error in the output of shortcodes.

= 0.2 =
* Minor update. No functionality changes. Only instructional changes.

= 0.1 =
* Initial version