=== Lex's Last Update Widget ===
Contributors: Lex--
Donate link: 
Tags: widget, last update, update
Requires at least: 3.3
Tested up to: 3.4
Stable tag: trunk

A widget to display the date of the last blog update.


== Description ==

Although blog posts can display the date on which they were created, it is sometimes useless to display this information. At least, this can be a way to make index pages lighter. In this case though, it can be useful to display the date of the last blog update on the index. It allows the visitor to see the last update time at a glance.

This plugins provides a widget to display the date of the last blog update.  
The widget has 4 options:

* The *title* of the widget.
* The *format* of the date.
* The *watched elements*: posts, pages, revisions or bookmarks (several ones can be selected at once).
* The *criteria* for posts and pages (do we look at creation time or modification time?).

== Installation ==

1. Unzip the plugin archive on your local disk
1. Upload the `lex-last-update-widget` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the widget in your sidebar using the 'Appearance' menu in the administration panel

== Frequently Asked Questions ==

= I have just installed this plugin and bookmark modifications seem to be ignored. Why? = 

Wordpress does not store creation and modification time for bookmarks. So, they can only be tracked once this plugin has been installed. Previous modifications in the bookmarks cannot be known and thus displayed by the widget.

== Screenshots ==

1. An overview of the widget.
2. The configuration of the widget in the administration panel.

== Changelog ==

= 1.2.0 =

Ignore sticky posts when searching the most recent post.
Tested with a more recent version of Wordpress that originally.

= 1.1.0 =
* Last modifications were originally the creation of new posts.
* It can now be the creation of a new post, page, revision or bookmark modification (creation, edition, deletion).
* The widget options were updated in consequence.
* Internationalization is on its way (French and English in a first time).

= 1.0 =
* Initial version.

== Upgrade Notice ==

* Deactivate the older plugin version in the administration panel.
* Delete the old directory of the plugin.
* Follow the installation procedure to install the new version.
