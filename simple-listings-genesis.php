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
 * Version:           1.4.1
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

//* Include required files
define( 'SIMPLELISTING_PATH', plugin_dir_path( __FILE__ ) );

require( SIMPLELISTING_PATH . 'includes/class-listing-registration.php' ); // listing custom post type registration
require( SIMPLELISTING_PATH . 'includes/class-listing-type.php' );
include( SIMPLELISTING_PATH . 'includes/featured-listing-widget.php' ); // featured listing widget

add_image_size( 'listing-photo', 340, 227, TRUE);

/**
 * set up metaboxes
 * @since  1.4.0
 */
if ( file_exists( SIMPLELISTING_PATH . '/cmb2/init.php' ) ) {
	require_once SIMPLELISTING_PATH . '/cmb2/init.php';
}
elseif ( file_exists( SIMPLELISTING_PATH . '/CMB2/init.php' ) ) {
	require_once SIMPLELISTING_PATH . '/CMB2/init.php';
}

add_action( 'wp_enqueue_scripts', 'simplelisting_style' );
/**
 * Load the stylesheet for Simple Genesis Listings
 * Comment out this function if you do not want to use my styles.
 * @since 1.0.0
 */
function simplelisting_style() {
	wp_enqueue_style( 'simplelisting-style', plugins_url( 'includes/simple-listing.css', __FILE__ ), array(), 1.0 );
}

// Register the Featured Listing Widget. Requires Genesis Framework.
add_action( 'genesis_setup', 'simplelisting_register_genesis_widget' );
function simplelisting_register_genesis_widget() {
	add_action( 'widgets_init', 'simplelisting_register_widget' );
}

function simplelisting_register_widget() {
	register_widget( 'Genesis_Featured_Listing' );
}

add_action( 'plugins_loaded', 'simplelisting_load_textdomain' );
/**
 * Set up text domain for translations
 *
 * @since 1.2.0
 */
function simplelisting_load_textdomain() {
	load_plugin_textdomain( 'simple-listings-genesis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$listing_post_type_registrations = new Simple_Listing_Post_Type_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$listing_post_type = new Simple_Listing_Post_Type( $listing_post_type_registrations );

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $listing_post_type, 'activate' ) );

// Initialise registrations for post-activation requests.
$listing_post_type_registrations->init();
