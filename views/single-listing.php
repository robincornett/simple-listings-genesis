<?php
/**
 * Simple Listing Post Type: Single Listing View
 *
 * @package    Simple_Listing_Post_Type
 * @author     Robin Cornett <hello@robincornett.com>
 * @copyright  2014 Robin Cornett
 *
 */

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'simplelisting_single_content' );
add_action( 'genesis_post_content', 'simplelisting_single_content' );

function simplelisting_single_content() {

	$location    = get_post_meta( get_the_ID(), '_cmb_listing-location', true );
	$price       = get_post_meta( get_the_ID(), '_cmb_listing-price', true );
	$description = get_the_content();
	$mls         = get_post_meta( get_the_ID(), '_cmb_mls-link', true );
	$image       = get_the_post_thumbnail( get_the_ID(), 'listing-photo', array( 'class' => 'alignright', 'alt' => the_title_attribute( 'echo=0' ), 'title' => the_title_attribute( 'echo=0' ) ) );
	if ( $description ) {
		$description = sprintf( '<strong>%s</strong>%s', __( 'Description: ', 'simple-listings-genesis' ), $description );
		echo wpautop( wp_kses_post( $image . $description ) );
	}
	if ( $location ) {
		printf( '<strong>%s</strong> %s', esc_attr__( 'Location: ', 'simple-listings-genesis' ), esc_attr( $location ) );
	}
	if ( $price ) {
		echo '<br />';
		printf( '<strong>%s</strong> %s', esc_attr__( 'Transaction Amount: ', 'simple-listings-genesis' ), esc_attr( $price ) );
	}
	if ( $mls ) {
		echo '<br />';
		printf( '<a href="%s" target="_blank">%s</a>', esc_url( $mls ), esc_attr__( 'Listing Details', 'simple-listings-genesis' ) );
	}

}

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

genesis();
