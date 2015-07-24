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

add_filter( 'the_content', 'simplelisting_singlecontent' );
function simplelisting_singlecontent( $content ) {

	$location = get_post_meta( get_the_ID(), '_cmb_listing-location', true );
	$price    = get_post_meta( get_the_ID(), '_cmb_listing-price', true );
	$mls      = get_post_meta( get_the_ID(), '_cmb_mls-link', true );
	$image    = get_the_post_thumbnail( get_the_ID(), 'listing-photo', array( 'class' => 'alignright', 'alt' => the_title_attribute( 'echo=0' ) ) );
	if ( $location ) {
		$content .= sprintf( '<strong>%s</strong> %s<br />', esc_attr__( 'Location: ', 'simple-listings-genesis' ), esc_attr( $location ) );
	}
	if ( $price ) {
		$content .= sprintf( '<strong>%s</strong> %s<br />', esc_attr__( 'Transaction Amount: ', 'simple-listings-genesis' ), esc_attr( $price ) );
	}
	if ( $mls ) {
		$content .= sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $mls ), esc_attr__( 'Listing Details', 'simple-listings-genesis' ) );
	}

	return $image . $content;

}

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

genesis();
