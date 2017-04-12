=== GD Custom Posts And Taxonomies Tools ===
Contributors: GDragoN
Version: 4.3.9
Tags: gdragon, tools, taxonomy, custom post types, post type, custom post, custom taxonomies, taxonomies, management, widget, cloud, meta box, custom fields
Requires at least: 3.6
Tested up to: 4.4
Stable tag: trunk

GD Custom Posts And Taxonomies Tools is plugin for management and tools collection for working with custom post types and taxonomies. Additional features support: custom meta boxes, additional widgets and more.

== Description ==
GD Custom Posts And Taxonomies Tools is plugin for management and tools collection for working with custom post types and taxonomies. Additional features support: custom meta boxes, additional widgets and more.

[Plugin Home](http://www.dev4press.com/plugins/gd-taxonomies-tools/) |
[Twitter](http://twitter.com/milangd)

== Installation ==
= Requirements =
* PHP: 5.2.4 or newer
* WordPress: 3.6 or newer
* jQuery: 1.7.1 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-taxonomies-tools`.
* Upload folder `gd-taxonomies-tools` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Does plugin works with WordPress MultiSite installations? =
Yes.

= After upgrade, my custom post types or taxonomies are not accessible for edit or creating new ones? =
Most likely problem is that definition of post type or taxonomy is incomplete and some capabilities are not set. Review the settings for them and fill all capabilities.

= Some of the options for the editing of post type (add new, edit...) are missing. =
This usually happens if the capabilities for the post type are not set as they should. Open post type to edit with GD CPT Tools Pro, and on Advanced tab you will find basic capabilities and list of capabilities. If you don’t want custom capabilities, make sure that Capabilities are set to ‘Use Capability Base’, Capability base set to ‘post’ and click Auto Fill Defaults in the list of capabilities to have them all inserted.

= PHP reports fatal errors with registration of post types or meta boxes? =
Only one type of plugins are known to cause such issues. This is caused by W3 Total Cache or similar caching plugins when configured to use database and/or object cache. Both these features can prevent real time access to actual data in the database, and W3TC is serving cached data, and preventing plugin to add info to database or to get data when needed for proper unserialization.
