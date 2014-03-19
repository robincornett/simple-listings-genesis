<?php
/**
 * Featured Listing Widget. Shows a list of the latest listings with thumbnails. Based on Genesis Featured Posts Widget.
 *
 * @package Simple_Listing_Post_Type
 * @author  StudioPress
 * @author  Robin Cornett
 * @license GPL-2.0+
 * @link    http://www.robincornett.com/
 */

class Genesis_Featured_Listing extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor. Set the default widget options and create widget.
	 *
	 * @since 0.1.8
	 */
	function __construct() {

		$this->defaults = array(
			'title'           => '',
			'taxonomy'        => '',
			'posts_num'       => 1,
			'posts_offset'    => 0,
			'orderby'         => 'rand',
			'order'           => 'DESC',
			'show_image'      => 0,
			'image_alignment' => '',
			'image_size'      => 'listing-photo',
			'post_type'       => 'listing',
			'listing_date'     => 0,
			'show_title'      => 1,
			'show_status'     => 0,
			'show_content'    => '',
			'archive_link'    => 0,
			'archive_text'    => '',
		);

		$widget_ops = array(
			'classname'   => 'featured-content featuredlisting',
			'description' => __( 'Displays featured listings with thumbnails', 'simple-listings-genesis' ),
		);

		$control_ops = array(
			'id_base' => 'featured-listing',
			'width'   => 505,
			'height'  => 350,
		);

		parent::__construct( 'featured-listing', __( 'Featured Listing', 'simple-listings-genesis' ), $widget_ops, $control_ops );

	}

	/**
	 * Echo the widget content.
	 *
	 * @since 0.1.8
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		global $wp_query, $_genesis_displayed_ids;

		extract( $args );

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

		//* Set up the author bio
		if ( ! empty( $instance['title'] ) )
			echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

		$query_args = array(
			'post_type'    => 'listing',
			'taxonomy'     => $instance['taxonomy'],
			'showposts'    => $instance['posts_num'],
			'offset'       => $instance['posts_offset'],
			'orderby'      => $instance['orderby'],
			'order'        => $instance['order'],
		);

		//* Exclude displayed IDs from this loop?
		global $post;
		$wp_query = new WP_Query( $query_args );

		if ( have_posts() ) : while ( have_posts() ) : the_post();

			$_genesis_displayed_ids[] = get_the_ID();

			genesis_markup( array(
				'html5'   => '<article %s><div class="listing-wrap">',
				'xhtml'   => sprintf( '<div class="%s">', implode( ' ', get_post_class() ) ),
				'context' => 'entry',
			) );

			$image = genesis_get_image( array(
				'format'  => 'html',
				'size'    => $instance['image_size'],
				'context' => 'featured-post-widget',
				'attr'    => genesis_parse_attr( 'entry-image-widget' ),
			) );

			$mls = get_post_meta( $post->ID, '_cmb_mls-link', true );

			if ( $instance['show_image'] && $image )
				if ( $mls )
					printf( '<a href="%s" title="%s" class="%s">%s</a>', esc_url( $mls ), the_title_attribute( 'echo=0' ), esc_attr( $instance['image_alignment'] ), $image );
				else
					printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), esc_attr( $instance['image_alignment'] ), $image );


			if ( $instance['show_title'] )
				echo genesis_html5() ? '<header class="entry-header">' : '';

				if ( ! empty( $instance['show_title'] ) ) {
					printf( '<h2 class="entry-title">%s</h2>', the_title_attribute( 'echo=0' ), get_the_title() );
				}

				if ( ! empty( $instance['show_status'] ) ) {
					echo '<span class="listing-status">' . strip_tags( get_the_term_list( $post->ID, 'status', '', ', ', '' ) ) . '</span>';
				}

			if ( $instance['show_title'] )
				echo genesis_html5() ? '</header>' : '';

			if ( ! empty( $instance['show_content'] ) ) {

				echo genesis_html5() ? '<div class="entry-content">' : '';

					global $more;

					$orig_more = $more;
					$more = 0;

					the_content( esc_html( $instance['more_text'] ) );

					$more = $orig_more;

				echo genesis_html5() ? '</div>' : '';

			}

		genesis_markup( array(
		'html5' => '</div></article>',
		'xhtml' => '</div>',
		) );

		endwhile; endif;

		if ( ! empty( $instance['archive_link'] ) && ! empty( $instance['archive_text'] ) )
		printf(
			__( '<p class="more-from-category"><a href="%1$s">%2$s</a></p>', 'genesis' ),
			get_post_type_archive_link( $instance['post_type'] ),
			esc_html( $instance['archive_text'] )
		);

		//* Restore original query
		wp_reset_query();

		echo $after_widget;

	}

	/**
	 * Update a particular instance.
	 *
	 * This function should check that $new_instance is set correctly.
	 * The newly calculated value of $instance should be returned.
	 * If "false" is returned, the instance won't be saved/updated.
	 *
	 * @since 0.1.8
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {

		$new_instance['title']     = strip_tags( $new_instance['title'] );
		$new_instance['listing_date'] = wp_kses_post( $new_instance['listing_date'] );
		return $new_instance;

	}

	/**
	 * Echo the settings update form.
	 *
	 * @since 0.1.8
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {

		//* Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$args = array(
			'public' => true
		);
		$output = 'names';
		$operator = 'and';
		$post_type_list = get_post_types( $args, $output, $operator );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'simple-listings-genesis' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<div class="genesis-widget-column" style="width:100%;">

			<div class="genesis-widget-column-box genesis-widget-column-box-top">

				<p>
					<label for="<?php echo $this->get_field_id( 'status' ); ?>"><?php _e( 'Listing Status', 'simple-listings-genesis' ); ?>:</label>
					<?php
					$categories_args = array(
						'name'             => $this->get_field_name( 'status' ),
						'selected'         => $instance['taxonomy'],
						'orderby'          => 'Name',
						'hierarchical'     => 1,
						'show_option_all'  => 'Any Status',
						'show_option_none' => __( 'No Status', 'simple-listings-genesis' ),
						'hide_empty'       => '0',
					);
					wp_dropdown_categories( 'show_option_all=Any Status&taxonomy=status' ); ?>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'posts_num' ); ?>"><?php _e( 'Number of Listings to Show', 'simple-listings-genesis' ); ?>:</label>
					<input type="text" id="<?php echo $this->get_field_id( 'posts_num' ); ?>" name="<?php echo $this->get_field_name( 'posts_num' ); ?>" value="<?php echo esc_attr( $instance['posts_num'] ); ?>" size="2" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'posts_offset' ); ?>"><?php _e( 'Number of Listings to Offset/Hide', 'simple-listings-genesis' ); ?>:</label>
					<input type="text" id="<?php echo $this->get_field_id( 'posts_offset' ); ?>" name="<?php echo $this->get_field_name( 'posts_offset' ); ?>" value="<?php echo esc_attr( $instance['posts_offset'] ); ?>" size="2" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By', 'simple-listings-genesis' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
						<option value="date" <?php selected( 'date', $instance['orderby'] ); ?>><?php _e( 'Date', 'simple-listings-genesis' ); ?></option>
						<option value="title" <?php selected( 'title', $instance['orderby'] ); ?>><?php _e( 'Title', 'simple-listings-genesis' ); ?></option>
						<option value="ID" <?php selected( 'ID', $instance['orderby'] ); ?>><?php _e( 'ID', 'simple-listings-genesis' ); ?></option>
						<option value="rand" <?php selected( 'rand', $instance['orderby'] ); ?>><?php _e( 'Random', 'simple-listings-genesis' ); ?></option>
					</select>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sort Order', 'simple-listings-genesis' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
						<option value="DESC" <?php selected( 'DESC', $instance['order'] ); ?>><?php _e( 'Descending (3, 2, 1)', 'simple-listings-genesis' ); ?></option>
						<option value="ASC" <?php selected( 'ASC', $instance['order'] ); ?>><?php _e( 'Ascending (1, 2, 3)', 'simple-listings-genesis' ); ?></option>
					</select>
				</p>

			</div>

			<div class="genesis-widget-column-box">

				<p>
					<input id="<?php echo $this->get_field_id( 'show_image' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_image' ); ?>" value="1" <?php checked( $instance['show_image'] ); ?>/>
					<label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Show Featured Image', 'simple-listings-genesis' ); ?></label>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'image_alignment' ); ?>"><?php _e( 'Image Alignment', 'simple-listings-genesis' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'image_alignment' ); ?>" name="<?php echo $this->get_field_name( 'image_alignment' ); ?>">
						<option value="alignnone">- <?php _e( 'None', 'simple-listings-genesis' ); ?> -</option>
						<option value="alignleft" <?php selected( 'alignleft', $instance['image_alignment'] ); ?>><?php _e( 'Left', 'simple-listings-genesis' ); ?></option>
						<option value="alignright" <?php selected( 'alignright', $instance['image_alignment'] ); ?>><?php _e( 'Right', 'simple-listings-genesis' ); ?></option>
					</select>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'show_status' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_status' ); ?>" value="1" <?php checked( $instance['show_status'] ); ?>/>
					<label for="<?php echo $this->get_field_id( 'show_status' ); ?>"><?php _e( 'Show Status', 'simple-listings-genesis' ); ?></label>
				</p>

				<p>
					<input id="<?php echo $this->get_field_id( 'show_title' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" value="1" <?php checked( $instance['show_title'] ); ?>/>
					<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Listing Title', 'simple-listings-genesis' ); ?></label>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id( 'show_content' ); ?>"><?php _e( 'Show the content?', 'simple-listings-genesis' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'show_content' ); ?>" name="<?php echo $this->get_field_name( 'show_content' ); ?>">
						<option value="" <?php selected( '', $instance['show_content'] ); ?>><?php _e( 'Do Not Show Content', 'simple-listings-genesis' ); ?></option>
						<option value="content" <?php selected( 'content', $instance['show_content'] ); ?>><?php _e( 'Show Content', 'simple-listings-genesis' ); ?></option>
					</select>
				</p>
				<p>
					<input id="<?php echo $this->get_field_id( 'archive_link' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'archive_link' ); ?>" value="1" <?php checked( $instance['archive_link'] ); ?>/>
					<label for="<?php echo $this->get_field_id( 'archive_link' ); ?>"><?php _e( 'Show Archive Link (this will show all listings)', 'simple-listings-genesis' ); ?></label>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'archive_text' ); ?>"><?php _e( 'Link Text', 'simple-listings-genesis' ); ?>:</label>
					<input type="text" id="<?php echo $this->get_field_id( 'archive_text' ); ?>" name="<?php echo $this->get_field_name( 'archive_text' ); ?>" value="<?php echo esc_attr( $instance['archive_text'] ); ?>" class="widefat" />
				</p>

			</div>

		</div>
		<?php

	}

}
