<?php
/**
 * Simple Listing Post Type: Archive/Taxonomy Listing View
 *
 * @package    Simple_Listing_Post_Type
 * @author     Robin Cornett <hello@robincornett.com>
 * @copyright  2014 Robin Cornett
 *
 */

add_filter( 'body_class', 'simplelisting_body_class' );
function simplelisting_body_class( $classes ) {
	$classes[] = 'simple-listing';

	return $classes;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'simplelistinggenesis_archive_loop' );

/**
 * Custom archive loop
 * @since 1.5.0
 */
function simplelistinggenesis_archive_loop() {

	echo '<div class="listings">';
	if ( have_posts() ) :

		while ( have_posts() ) : the_post();
			$article = genesis_html5() ? 'article' : 'div';
			printf( '<%s class="%s">', esc_attr( $article ), esc_attr( join( ' ', get_post_class() ) ) );
				simplelistingsgenesis_archive_photo();
			printf( '</%s>', esc_attr( $article ) );

		endwhile;
		do_action( 'genesis_after_endwhile' );
	endif;
	echo '</div>';
}

/**
 * Photo for archive
 * @return image with link, title, and status
 *
 * @since 1.5.0
 */
function simplelistingsgenesis_archive_photo() {
	echo '<div class="listing-wrap">';
	$status   = get_the_term_list( get_the_ID(), 'status', '', ', ', '' );
	$image    = get_the_post_thumbnail( get_the_ID(), 'listing-photo', array( 'class' => 'aligncenter', 'alt' => get_the_title() ) );
	$fallback = plugins_url( 'includes/sample-images/simple-listings.png' , dirname( __FILE__ ) );

	if ( $image ) {
		echo '<a href="' . esc_url( get_permalink() ) . '">' . wp_kses_post( $image );
	} else {
		printf(
			'<a href="%s"><img src="%s" class="aligncenter" alt="%s" />',
			esc_url( get_permalink() ),
			esc_url( $fallback ),
			the_title_attribute( 'echo=0' )
		);
	}

	echo genesis_html5() ? '<header class="entry-header">' : '';
	printf( '<h2 class="entry-title">%s</h2>', the_title_attribute( 'echo=0' ) );

	if ( $status ) {
		echo '<span class="listing-status">' . esc_attr( strip_tags( $status ) ) . '</span>';
	}
	echo genesis_html5() ? '</header>' : '';

	echo '</a></div>';
}

genesis();
