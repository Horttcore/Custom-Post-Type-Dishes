<?php
/**
 * Widget
 *
 * @author Ralf Hortt
 */
if ( !class_exists( 'Custom_Post_Type_Dishes_Widget' ) ) :
class Custom_Post_Type_Dishes_Widget extends WP_Widget
{



	/**
	 * Constructor
	 *
	 * @since v1.0
	 * @author Ralf Hortt
	 */
	function __construct()
	{

		$widget_ops = array(
			'classname' => 'widget-dishes',
			'description' => __( 'Lists dishes', 'custom-post-type-dishes' ),
		);
		$control_ops = array( 'id_base' => 'widget-dishes' );
		parent::__construct( 'widget-dishes', __( 'Dishes', 'custom-post-type-dishes' ), $widget_ops, $control_ops );

		add_action( 'custom-post-type-dishes-widget-output', 'Custom_Post_Type_Dishes_Widget::widget_output', 10, 3 );
		add_action( 'custom-post-type-dishes-widget-loop-output', 'Custom_Post_Type_Dishes_Widget::widget_loop_output', 10, 3 );

	} // end __construct



	/**
	 * Output
	 *
	 * @param array $args Arguments
	 * @param array $instance Widget instance
	 * @since v1.0.12
	 * @author Ralf Hortt
	 */
	function widget( $args, $instance ) {

		$query = array(
			'post_type' => 'dish',
			'showposts' => $instance['limit'],
			'orderby' => $instance['orderby'],
			'order' => $instance['order'],
		);

		if ( 0 != $instance['dish-category'] )
			$query['tax_query'] = array(
				array(
					'taxonomy' => 'dish-category',
					'field' => 'term_id',
					'terms' => $instance['dish-category'],
				)
			);

		$query = new WP_Query( apply_filters( 'custom-post-type-dishes-widget-query', $query ) );

		if ( $query->have_posts() ) :

			/**
			 * Widget output
			 *
			 * @param array $args Arguments
			 * @param array $instance Widget instance
			 * @param obj $query WP_Query object
			 * @hooked Custom_Post_Type_Widget::widget_output - 10
			 */
			do_action( 'custom-post-type-dishes-widget-output', $args, $instance, $query );

		endif;

		wp_reset_query();

	} // end widget



	/**
	 * Save widget settings
	 *
	 * @param array $new_instance New widget instance
	 * @param array $old_instance Old widget instance
	 * @author Ralf Hortt
	 */
	function update( $new_instance, $old_instance )
	{

		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['limit'] = $new_instance['limit'];
		$instance['dish-category'] = $new_instance['dish-category'];

		return apply_filters( 'custom-post-type-dishes-widget-form-save', $instance );

	} // end update



	/**
	 * Widget settings
	 *
	 * @param array $instance Widget instance
	 * @author Ralf Hortt
	 * @since v1.0.0
	 */
	public function form( $instance )
	{


		do_action( 'custom-post-type-dishes-widget-form-before', $instance );

		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label><br>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'orderby' ); ?>"><?php _e( 'Order By:', 'custom-post-type-dishes' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<option <?php selected( $instance['orderby'], '' ) ?> value=""><?php _e( 'None' ); ?></option>
				<option <?php selected( $instance['orderby'], 'ID' ) ?> value="ID"><?php _e( 'ID', 'custom-post-type-dishes' ); ?></option>
				<option <?php selected( $instance['orderby'], 'title' ) ?> value="title"><?php _e( 'Title' ); ?></option>
				<option <?php selected( $instance['orderby'], 'date' ) ?> value="date"><?php _e( 'Date' ); ?></option>
				<option <?php selected( $instance['orderby'], 'rand' ) ?> value="rand"><?php _e( 'Random' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'order' ); ?>"><?php _e( 'Order:' ); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_name( 'order' ); ?>">
				<option value="ASC" <?php selected( $instance['order'], 'ASC' ) ?>><?php _e( 'Ascending', 'custom-post-type-dishes' ); ?></option>
				<option value="DESC" <?php selected( $instance['order'], 'DESC' ) ?>><?php _e( 'Descending', 'custom-post-type-dishes' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'limit' ); ?>"><?php _e( 'Count:', 'custom-post-type-dishes' ); ?></label><br>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo esc_attr( $instance['limit'] ) ?>">
		</p>

		<?php
		$category_dropdown = wp_dropdown_categories(array(
			'show_option_all' => __( 'All', 'custom-post-type-dishes' ),
			'taxonomy' => 'dish-category',
			'name' => $this->get_field_name( 'dish-category' ),
			'selected' => $instance['dish-category'],
			'hide_if_empty' => TRUE,
			'hide_empty' => FALSE,
			'hierarchical' => TRUE,
			'echo' => FALSE
		));

		if ( $category_dropdown ) :

			?>

			<p>
				<label for="<?php echo $this->get_field_name( 'dish-category' ); ?>"><?php _e( 'Category' ); ?></label><br>
				<?php echo $category_dropdown ?>
			</p>

			<?php

		endif;

		do_action( 'custom-post-type-dishes-widget-form-after', $instance );

	} // end form



	/**
	 * Widget loop output
	 *
	 * @static
	 * @access public
	 * @param array $args Arguments
	 * @param array $instance Widget instance
	 * @param obj $query WP_Query object
	 * @author Ralf Hortt
	 * @since v1.0.0
	 **/
	static public function widget_loop_output( $args, $instance, $query )
	{

		$meta = get_post_meta( get_the_ID(), '_meta', TRUE );
		?>
		<article class="dish">
			<header><h4 class="dish-title"><?php the_title() ?> <?php if ( $meta['price'] ) : ?><span class="dish-price"><?php echo $meta['price']; ?></span><?php endif; ?></h4>
			<div class="dish-description"><?php the_excerpt() ?></div>
			<?php if ( $meta['quantity'] ) : ?><span class="dish-quantity"><?php echo $meta['quantity']; ?></span><?php endif; ?>
		</article>
		<?php

	}



	/**
	 * Widget output
	 *
	 * @static
	 * @access public
	 * @param array $args Arguments
	 * @param array $instance Widget instance
	 * @param obj $query WP_Query object
	 * @author Ralf Hortt
	 * @since v1.0.0
	 **/
	static public function widget_output( $args, $instance, $query )
	{

		echo $args['before_widget'];

		echo $args['before_title'] . $instance['title'] . $args['after_title'];

		?>

		<section class="dish-list">

			<?php

			while ( $query->have_posts() ) : $query->the_post();

				/**
				 * Loop output
				 *
				 * @param array $args Arguments
				 * @param array $instance Widget instance
				 * @param obj $query WP_Query object
				 * @hooked Custom_Post_Type::widget_loop_output - 10
				 */
				do_action( 'custom-post-type-dishes-widget-loop-output', $args, $instance, $query );

			endwhile;

			?>

		</section>

		<?php

		echo $args['after_widget'];

	}



} // end Custom_Post_Type_Dishes_Widget

endif;
