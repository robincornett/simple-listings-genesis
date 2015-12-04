<?php
/**
 * Simple real estate listings plugin. Requires the Genesis Framework.
 *
 * @package   Simple_Listing_Post_Type
 * @author    Robin Cornett <hello@robincornett.com>
 * @license   GPL-2.0+
 * @link      http://robincornett.com
 * @copyright 2014 Robin Cornett Creative, LLC
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Listings for Genesis
 * Plugin URI:        http://github.com/robincornett/simple-listings-genesis/
 * Description:       This sets up a simple real estate listings custom post type/taxonomy. It pretty much requires the Genesis Framework although it will work without it--just reduced functionality.
 * Version:           1.6.0
 * Author:            Robin Cornett
 * Author URI:        http://robincornett.com
 * Text Domain:       simple-listings-genesis
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Include required files
function simple_listings_require() {
	$files = array(
		'class-simplelisting',
		'class-simplelisting-posttype',
		'class-tgm-plugin-activation',
	);

	foreach ( $files as $file ) {
		require plugin_dir_path( __FILE__ ) . 'includes/' . $file . '.php';
	}
}
simple_listings_require();

$simplelisting_post_type = new SimpleListing_Post_Type_Registrations;
$simplelistingsgenesis   = new Simple_Listings_Genesis( $simplelisting_post_type );

$simplelistingsgenesis->run();
