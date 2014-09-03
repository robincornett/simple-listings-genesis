<?php
/**
 * Simple Listing Post Type: Single Listing View
 *
 * @package    Simple_Listing_Post_Type
 * @author     Robin Cornett <hello@robincornett.com>
 * @copyright  2014 Robin Cornett
 *
 */

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'simplelisting_single_content' );

function simplelisting_single_content() {
	global $post;

	$location    = get_post_meta( $post->ID, '_cmb_listing-location', true );
	$price       = get_post_meta( $post->ID, '_cmb_listing-price', true );
	$description = get_the_content();
	$mls         = get_post_meta( $post->ID, '_cmb_mls-link', true );

	echo get_the_post_thumbnail( $post->ID, 'listing-photo', array( 'class' => 'alignright', 'alt' => the_title_attribute( 'echo=0' ), 'title' => the_title_attribute( 'echo=0' ) ) );
	if ( $description ) {
		echo wpautop( '<strong>' . __( 'Description: ', 'simple-listings-genesis' ) . '</strong>' . $description );
	}
	if ( $location ) {
		echo '<strong>' . __( 'Location: ', 'simple-listings-genesis' ) . '</strong> ' . $location;
	}
	if ( $price ) {
		echo '<br />';
		echo '<strong>' . __( 'Transaction Amount: ', 'simple-listings-genesis' ) . '</strong> ' . $price;
	}
	if ( $mls ) {
		echo '<br />';
		echo '<a href="' . esc_url( $mls ) . '" target="_blank">' . __( 'Listing Details', 'simple-listings-genesis' ) . '</a>';
	}
}

genesis();
