# Simple Listings for Genesis

WordPress plugin that adds a custom post type and taxonomy for real estate listings.

## Description

This plugin registers a simple custom post type for real estate listings.  It also registers a separate listing taxonomy. The plugin supports a featured image, general description, MLS link, location, and price. It does not support detailed input such as square footage, number of bedrooms, etc. If you need that, use AgentPress Listings (http://wordpress.org/plugins/agentpress-listings/) instead.

If your site is running a Genesis Framework child theme, this plugin includes a template for the archive, taxonomy, and single listing page. If you're not running the Genesis Framework, you can create your own templates for these in your theme. If you don't like my templates, comment out line 160 in the class-listing-type.php file. If you don't like my styles, override them in your child theme or comment out line 49 in simple-listings-genesis.php.

Demo: http://robin.works/listings/

## Requirements
* WordPress 3.4, tested up to 3.9.1
* Genesis Framework (templates and widget will not work with other themes)
* an HTML5 theme because I was too lazy to add XHTML support back in.

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:robincornett/simple-listings-genesis.git`

Then go to your Plugins screen and click __Activate__.

## Frequently Asked Questions

### How can I display listing items differently than regular posts?

If you're using an HTML5 Genesis Framework theme, it's done. If you're not, use the included templates as a reference and have fun.

### Why did you make this?

The AgentPress Listings plugin has a lot more features and functionality than I needed for a couple of projects, so I thought I'd make a simple version which would allow my client to post their own listings on their own site, but optionally link to the outside MLS service listing. They can feature their own listings without having to re-enter all of the data.

## Credits

Built by [Robin Cornett](http://www.robincornett.com/)