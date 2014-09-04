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
add_action( 'genesis_loop', 'simplelisting_archive_loops' );

function simplelisting_archive_loops() {
	if ( genesis_html5() ) {
		simplelisting_loop_html5();
	}
	else {
		simplelisting_loop_xhtml();
	}
}

function simplelisting_loop_html5() {

	global $post;

	echo '<div class="listings">';
	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

		$status = get_the_term_list( $post->ID, 'status', '', ', ', '' ); ?>

			<article <?php post_class(); ?>><div class="listing-wrap"> <?php
				$image    = get_the_post_thumbnail( $post->ID, 'listing-photo', array( 'class' => 'aligncenter', 'alt' => get_the_title(), 'title' => get_the_title() ) );
				$fallback = plugins_url( 'includes/sample-images/simple-listings.png' , dirname( __FILE__ ) );

				if ( $image ) {
					echo '<a href="' . get_permalink() . '">' . $image;
				}
				else {
					printf(
						'<a href="%s"><img src="%s" class="aligncenter" alt="%s" title="%s" />',
						get_permalink(),
						$fallback,
						the_title_attribute( 'echo=0' ),
						the_title_attribute( 'echo=0' )
					);
				}

				echo genesis_html5() ? '<header class="entry-header">' : '';
				printf( '<h2 class="entry-title">%s</h2>', the_title_attribute( 'echo=0' ) );

				if ( $status ) {
					echo '<span class="listing-status">' . strip_tags( $status ) . '</span>';
				}
				echo genesis_html5() ? '</header>' : '';

			echo '</a></div></article>';

		endwhile;
		do_action( 'genesis_after_endwhile' );
	endif;
	echo '</div>';
}

function simplelisting_loop_xhtml() {

	global $post;

	echo '<div class="listings">';
	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

		$status = get_the_term_list( $post->ID, 'status', '', ', ', '' );

			printf( '<div class="%s">', join( ' ', get_post_class() ) );
			echo '<div class="listing-wrap">';
				$image    = get_the_post_thumbnail( $post->ID, 'listing-photo', array( 'class' => 'aligncenter', 'alt' => get_the_title(), 'title' => get_the_title() ) );
				$fallback = plugins_url( 'includes/sample-images/simple-listings.png' , dirname( __FILE__ ) );

				if ( $image ) {
					echo '<a href="' . get_permalink() . '">' . $image;
				}
				else {
					printf(
						'<a href="%s"><img src="%s" class="aligncenter" alt="%s" title="%s" />',
						get_permalink(),
						$fallback,
						the_title_attribute( 'echo=0' ),
						the_title_attribute( 'echo=0' )
					);
				}

				printf( '<h2 class="entry-title">%s</h2>', the_title_attribute( 'echo=0' ) );

				if ( $status ) {
					echo '<span class="listing-status">' . strip_tags( $status ) . '</span>';
				}

			echo '</a></div></div>';

		endwhile;
		do_action( 'genesis_after_endwhile' );
	endif;
	echo '</div>';
}

genesis();
