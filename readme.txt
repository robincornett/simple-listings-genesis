=== Simple Listings for Genesis ===

Contributors: littler.chicken
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FEFGGUG88LBKQ
Tags: Genesis, StudioPress, real estate
Requires at least: 3.8
Tested up to: 4.0RC1
Stable tag: 1.2.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

WordPress plugin that adds a custom post type and taxonomy for real estate listings.

== Description ==

This plugin registers a simple custom post type for real estate listings. It also registers a separate listing taxonomy. The plugin supports a featured image, general description, MLS link, location, and price. It does not support detailed input such as square footage, number of bedrooms, etc. If you need that, use AgentPress Listings (http://wordpress.org/plugins/agentpress-listings/) instead.

If your site is running a Genesis Framework child theme, this plugin includes a template for the archive, taxonomy, and single listing page. If you're not running the Genesis Framework, you can create your own templates for these in your theme. If you don't like my templates, comment out line 160 in the class-listing-type.php file. If you don't like my styles, override them in your child theme or comment out line 49 in simple-listings-genesis.php.

Demo: http://robin.works/listings/

== Installation ==

1. Upload the entire `simple-listings-genesis` folder to your `/wp-content/plugins` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==

= How can I display listing items differently than regular posts? =

If you're using an HTML5 Genesis Framework theme, it's done. If you're not, use the included templates as a reference and have fun.

= Why did you make this? =

The AgentPress Listings plugin has a lot more features and functionality than I needed for a couple of projects, so I thought I'd make a simple version which would allow my client to post their own listings on their own site, but optionally link to the outside MLS service listing. They can feature their own listings without having to re-enter all of the data.

== Changelog ==

= 1.2.0 =
* Reorganized file structure
* Reconfigured how templates are loaded
* Body class function for archive/taxonomy moved to template file

= 1.0.0 =
* Initial Release
