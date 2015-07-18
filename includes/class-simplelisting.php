<?php

/**
 * Simple real estate listings plugin. Requires the Genesis Framework.
 *
 * @package   Simple_Listing_Post_Type
 * @author    Robin Cornett <hello@robincornett.com>
 * @license   GPL-2.0+
 * @link      http://robincornett.com
 * @copyright 2014 Robin Cornett Creative, LLC
*/

class Simple_Listings_Genesis {

	protected $post_type;

	public function __construct( $post_type ) {
		$this->post_type = $post_type;
	}

	public function run() {
		$this->post_type->init();

		add_image_size( 'listing-photo', 340, 227, true );
		add_action( 'wp_enqueue_scripts', array( $this, 'simplelisting_style' ) );
		add_action( 'genesis_setup', array( $this, 'register_widget' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'tgmpa_register', array( $this, 'require_plugins' ) );
	}

	/**
	 * Load the stylesheet for Simple Genesis Listings
	 * Comment out this function if you do not want to use my styles.
	 * @since 1.0.0
	 */
	function simplelisting_style() {
		$css_file = apply_filters( 'simplelistingsgenesis_css_file', plugin_dir_url( __FILE__ ) . 'simple-listing.css' );
		if ( 'listing' === get_post_type() || is_active_widget( false, false, 'featured-listing', true ) ) {
			wp_enqueue_style( 'simplelisting-style', $css_file, array(), '1.5.0' );
		}
	}

	function register_widget() {
		add_action( 'widgets_init', array( $this, 'simplelisting_register_widget' ) );
	}

	function simplelisting_register_widget() {
		register_widget( 'Genesis_Featured_Listing' );
	}

	/**
	 * Set up text domain for translations
	 *
	 * @since 1.2.0
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'simple-listings-genesis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	function require_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(

			array(
				'name'      => 'CMB2',
				'slug'      => 'cmb2',
				'required'  => true,
			),

		);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'tgmpa-simplelistings',  // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

			'strings'      => array(
				'notice_can_install_required'  => _n_noop(
					'Simple Listings for Genesis requires the following plugin: %1$s.',
					'Simple Listings for Genesis requires the following plugin: %1$s.',
					'theme-slug'
				), // %1$s = plugin name(s).
				'notice_can_activate_required' => _n_noop(
					'Simple Listings for Genesis requires the following plugin, which is currently inactive: %1$s.',
					'Simple Listings for Genesis requires the following plugin, which are currently inactive: %1$s.',
					'theme-slug'
				), // %1$s = plugin name(s).
			),

		);

		tgmpa( $plugins, $config );
	}

}
