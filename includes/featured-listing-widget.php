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
			'title'        => '',
			'tax_term'     => '',
			'posts_num'    => 1,
			'posts_offset' => 0,
			'orderby'      => 'rand',
			'order'        => 'DESC',
			'show_image'   => 0,
			'image_size'   => 'listing-photo',
			'post_type'    => 'listing',
			'show_title'   => 0,
			'show_status'  => 0,
			'show_content' => '',
			'archive_link' => 0,
			'archive_text' => '',
		);

		$widget_ops = array(
			'classname'                   => 'featured-content featuredlisting',
			'description'                 => __( 'Displays featured listings with thumbnails', 'simple-listings-genesis' ),
			'customize_selective_refresh' => true,
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

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
		}

		$query_args = array(
			'post_type' => 'listing',
			'showposts' => $instance['posts_num'],
			'offset'    => $instance['posts_offset'],
			'orderby'   => $instance['orderby'],
			'order'     => $instance['order'],
		);

		if ( '0' !== $instance['tax_term'] ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'status',
					'terms'    => $instance['tax_term'],
				),
			);
		}

		$widget_query = new WP_Query( $query_args );

		if ( $widget_query->have_posts() ) : while ( $widget_query->have_posts() ) : $widget_query->the_post();

			genesis_markup( array(
				'html5'   => '<article %s><div class="listing-wrap">',
				'xhtml'   => sprintf( '<div class="%s"><div class="listing-wrap">', implode( ' ', get_post_class() ) ),
				'context' => 'entry',
			) );

			$image = genesis_get_image( array(
				'format'  => 'html',
				'size'    => $instance['image_size'],
				'context' => 'featured-post-widget',
				'attr'    => genesis_parse_attr( 'entry-image-widget' ),
			) );

			if ( $instance['show_image'] ) {
				if ( ! $image ) {
					$fallback = plugins_url( 'includes/sample-images/simple-listings.png' , dirname( __FILE__ ) );
					$image    = sprintf( '<img src="%s" alt="%s" />', esc_url( $fallback ), esc_attr( the_title_attribute( 'echo=0' ) ) );
				}
				if ( $image ) {
					printf(
						'<a href="%s" alt="%s">%s</a>',
						esc_url( get_permalink() ),
						esc_attr( the_title_attribute( 'echo=0' ) ),
						wp_kses_post( $image )
					);
				}
			}

			if ( $instance['show_title'] ) {
				echo genesis_html5() ? '<header class="entry-header">' : '';
			}

			if ( ! empty( $instance['show_title'] ) ) {
				printf( '<h2 class="entry-title">%s</h2>', the_title_attribute( 'echo=0' ), get_the_title() );
			}

			if ( ! empty( $instance['show_status'] ) ) {
				echo '<span class="listing-status">' . strip_tags( get_the_term_list( get_the_ID(), 'status', '', ', ', '' ) ) . '</span>';
			}

			if ( $instance['show_title'] ) {
				echo genesis_html5() ? '</header>' : '';
			}

			if ( ! empty( $instance['show_content'] ) ) {

				echo genesis_html5() ? '<div class="entry-content">' : '';
					the_content();
				echo genesis_html5() ? '</div>' : '';

			}

		genesis_markup( array(
			'html5' => '</div></article>',
			'xhtml' => '</div></div>',
		) );

		endwhile; endif;

		if ( ! empty( $instance['archive_link'] ) && ! empty( $instance['archive_text'] ) ) {
			printf(
				'<p class="more-from-category"><a href="%1$s">%2$s</a></p>',
				esc_url( get_post_type_archive_link( $instance['post_type'] ) ),
				esc_html( $instance['archive_text'] )
			);
		}

		// Restore original query
		wp_reset_query();

		echo $args['after_widget'];

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

		$new_instance['title'] = strip_tags( $new_instance['title'] );
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

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'simple-listings-genesis' ); ?>:</label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<div class="genesis-widget-column-box genesis-widget-column-box-top">

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tax_term' ) ); ?>"><?php esc_attr_e( 'Listing Status', 'simple-listings-genesis' ); ?>:</label>
				<?php
				$categories_args = array(
					'name'             => $this->get_field_name( 'tax_term' ),
					'selected'         => $instance['tax_term'],
					'orderby'          => 'Name',
					'hierarchical'     => 1,
					'show_option_all'  => __( 'Any Status', 'simple-listings-genesis' ),
					'show_option_none' => 0,
					'hide_empty'       => 1,
					'taxonomy'         => 'status',
				);
				wp_dropdown_categories( $categories_args ); ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'posts_num' ) ); ?>"><?php esc_attr_e( 'Number of Listings to Show', 'simple-listings-genesis' ); ?>:</label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'posts_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_num' ) ); ?>" value="<?php echo esc_attr( $instance['posts_num'] ); ?>" size="2" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'posts_offset' ) ); ?>"><?php esc_attr_e( 'Number of Listings to Offset/Hide', 'simple-listings-genesis' ); ?>:</label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'posts_offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_offset' ) ); ?>" value="<?php echo esc_attr( $instance['posts_offset'] ); ?>" size="2" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_attr_e( 'Order By', 'simple-listings-genesis' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
					<option value="date" <?php selected( 'date', $instance['orderby'] ); ?>><?php esc_attr_e( 'Date', 'simple-listings-genesis' ); ?></option>
					<option value="title" <?php selected( 'title', $instance['orderby'] ); ?>><?php esc_attr_e( 'Title', 'simple-listings-genesis' ); ?></option>
					<option value="ID" <?php selected( 'ID', $instance['orderby'] ); ?>><?php esc_attr_e( 'ID', 'simple-listings-genesis' ); ?></option>
					<option value="rand" <?php selected( 'rand', $instance['orderby'] ); ?>><?php esc_attr_e( 'Random', 'simple-listings-genesis' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'Sort Order', 'simple-listings-genesis' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
					<option value="DESC" <?php selected( 'DESC', $instance['order'] ); ?>><?php esc_attr_e( 'Descending (3, 2, 1)', 'simple-listings-genesis' ); ?></option>
					<option value="ASC" <?php selected( 'ASC', $instance['order'] ); ?>><?php esc_attr_e( 'Ascending (1, 2, 3)', 'simple-listings-genesis' ); ?></option>
				</select>
			</p>

		</div>

		<div class="genesis-widget-column-box">

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" value="1" <?php checked( $instance['show_image'] ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_attr_e( 'Show Featured Image', 'simple-listings-genesis' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_status' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_status' ) ); ?>" value="1" <?php checked( $instance['show_status'] ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_status' ) ); ?>"><?php esc_attr_e( 'Show Status', 'simple-listings-genesis' ); ?></label>
			</p>

			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_title' ) ); ?>" value="1" <?php checked( $instance['show_title'] ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>"><?php esc_attr_e( 'Show Listing Title', 'simple-listings-genesis' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_content' ) ); ?>"><?php esc_attr_e( 'Show the content?', 'simple-listings-genesis' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'show_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_content' ) ); ?>">
					<option value="" <?php selected( '', $instance['show_content'] ); ?>><?php esc_attr_e( 'Do Not Show Content', 'simple-listings-genesis' ); ?></option>
					<option value="content" <?php selected( 'content', $instance['show_content'] ); ?>><?php esc_attr_e( 'Show Content', 'simple-listings-genesis' ); ?></option>
				</select>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'archive_link' ) ); ?>" type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'archive_link' ) ); ?>" value="1" <?php checked( $instance['archive_link'] ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'archive_link' ) ); ?>"><?php esc_attr_e( 'Show Archive Link (this will show all listings)', 'simple-listings-genesis' ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'archive_text' ) ); ?>"><?php esc_attr_e( 'Link Text', 'simple-listings-genesis' ); ?>:</label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'archive_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'archive_text' ) ); ?>" value="<?php echo esc_attr( $instance['archive_text'] ); ?>" class="widefat" />
			</p>

		</div>
		<?php
	}
}
