=== WP tlk.io ===
Contributors: snumb130, bbodine1
Donate link: 
Tags: chat, tlk.io
Requires at least: 2.8
Tested up to: 3.6
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to integrate tlk.io chat on any page of your website.

== Description ==

A plugin to integrate [tlk.io chat](http://tlk.io) on any page of your website.  The plugin will allow users to insert a tlk.io chatroom in a post with a shortcode. There is currently no options page to adjust settings. All customs settings are done through the shortcode generator located in the WYSIWYG editor of all pages and posts. Look for the blue tlk.io logo icon (blue cloud) in the editor.

== Installation ==

1. Upload `wp-tlkio` folder to the `/wp-content/plugins/` directory
1. Activate the plugin 'WP tlk.io' through the 'Plugins' menu in WordPress
1. Place `[tlkio]` in your pages or posts

== Frequently asked questions ==

= What short code do I use? =

`[tlkio]` or `[tlkio channel="lobby" width="100%" height="500px" css="http://yourdomain.com/pathtoyour.css"]Chat is currently off. Check back later.[/tlkio]`

= What short code options do I have? =

* channel - the name of the channel that you want to use. ex. 'lobby' or 'somethingrandom21'
* width - how wide will the chat window be? use percentage or pixel width. ex. '100%' or '500px'
* height - how high will the chat window be? use percentage or pixel width. ex. '100%' or '500px'
* css - link to an external stylesheet to easily add custom style to the embedded tlk.io chat. ex. 'http://yourdomain.com/custom.css'
* Offline Message - this can tell the users of your webpage that you currently have the on page chat turned off. ex. 'Plain text message of what you want to say'

== Screenshots ==

1. Chat demo

== Changelog ==

= 0.3 =
* Fixed shortcode error that was echoing output instead of returning.
* Changed the on/off switch from link to a form(POST method).

= 0.2 =
* Update to the readme.txt with better instructions for use.

= 0.1 =
* Initial version

== Upgrade notice ==

= 0.3 =
* Fixes possible error in the output of shortcodes.

= 0.2 =
* Minor update. No functionality changes. Only instructional changes.

= 0.1 =
* Initial version