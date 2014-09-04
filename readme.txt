=== Simple Listings for Genesis ===

Contributors: littler.chicken
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FEFGGUG88LBKQ
Tags: Genesis, StudioPress, real estate, realty, realtor, listing
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 1.3.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

WordPress plugin that adds a custom post type and taxonomy for real estate listings.

== Description ==

This plugin registers a simple custom post type for real estate listings. It also registers a separate listing taxonomy. The plugin supports a featured image, general description, MLS link, location, and price. It does not support detailed input such as square footage, number of bedrooms, etc. If you need that, use [AgentPress Listings](http://wordpress.org/plugins/agentpress-listings/) instead.

If your site is running a Genesis Framework child theme, this plugin includes a template for the archive, taxonomy, and single listing page. If you're not running the Genesis Framework, you can create your own templates for these in your theme. You can also override these with your own templates/styles in your theme.

*Note: although this plugin was written with the [Genesis Framework by StudioPress](http://studiopress.com/) in mind, it is not an official plugin for this framework and is neither endorsed nor supported by StudioPress.*

Demo: http://robin.works/listings/

== Installation ==

1. Upload the entire `simple-listings-genesis` folder to your `/wp-content/plugins` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==

= How can I display listing items differently than regular posts? =

If you're using a Genesis Framework theme, it's done. If you're not, use the included templates as a reference and have fun.

= Why did you make this? =

The AgentPress Listings plugin has a lot more features and functionality than I needed for a couple of projects, so I thought I'd make a simple version which would allow my client to post their own listings on their own site, but optionally link to the outside MLS service listing. They can feature their own listings without having to re-enter all of the data.

= How do I start? =

Simple Listings for Genesis works much like creating any other post or page in WordPress. One important thing is that when you are creating a listing, you must set a featured image. If you don't, there is a fallback image, but I'm pretty sure you would rather use your own. Everything else is more or less optional--whether you want to use a description, an outside MLS link, transaction amount, or location.

== Screenshots ==

1. Admin: show all listings.
2. Admin: create/update a new listing. Note metaboxes and featured image.

== Upgrade Notice ==

= 1.3.0 =
Updated to include styling and hooks for XHTML compatibility. HTML5 theme users won't see a difference.

= 1.2.1 =
Templates cleaned up and translation strings corrected.

= 1.2.0 =
This version reorganizes and tightens up all the code. Hoping for wordpress.org release. No new functionality.

== Changelog ==

= 1.3.0 =
* added XHTML actions/styling for backwards compatibility.

= 1.2.1 =
* Translation strings corrected
* Single listing template cleaned up (primarily for translation)
* Fallback image added/set for archive template and widget, if no featured image is set in listing. You really should set a featured image.
* CSS corrected so archive listing-wrap doesn't have a background
* Added xml folder/import file so you could import sample listings

= 1.2.0 =
* Reorganized file structure
* Reconfigured how templates are loaded
* Body class function for archive/taxonomy moved to template file

= 1.1.0 =
* Revised conditional to take user offsite

= 1.0.0 =
* Initial Release
