<?php
/**
 * Widget
 *
 * @author Ralf Hortt
 */
if ( !class_exists( 'Custom_Post_Type_Dishes_Cart_Widget' ) ) :
class Custom_Post_Type_Dishes_Cart_Widget extends WP_Widget
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
			'classname' => 'widget-dishes-cart',
			'description' => __( 'Displays a dishes cart', 'custom-post-type-dishes' ),
		);
		$control_ops = array( 'id_base' => 'widget-dishes-cart' );
		parent::__construct( 'widget-dishes-cart', __( 'Dishes Cart', 'custom-post-type-dishes' ), $widget_ops, $control_ops );

	} // END __construct



	/**
	 * Widget settings
	 *
	 * @param array $instance Widget instance
	 * @author Ralf Hortt
	 * @since v1.0.0
	 */
	public function form( $instance )
	{

		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label><br>
			<input class="regular-text" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ) ?>">
		</p>

		<?php

		$terms = get_terms( 'dish-category' );
		if ( $terms ) :
			?>
			<p>
				<?php _e( 'Categories:' ) ?><br>
				<?php
				foreach ( $terms as $term ) :
					$checked = ( in_array( $term->term_id, $instance['terms'] ) ) ? TRUE : FALSE;
					?>
					<label><input <?php checked($checked, TRUE) ?> type="checkbox" name="<?php echo $this->get_field_name( 'terms' ); ?>[]" value="<?php echo $term->term_id ?>"> <?php echo $term->name ?></label><br>
					<?php

				endforeach;
				?>
			</p>

			<?php

		endif;

	} // END form



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
		$instance['terms'] = $new_instance['terms'];

		return apply_filters( 'custom-post-type-dishes-widget-form-save', $instance );

	} // END update



	/**
	 * Output
	 *
	 * @param array $args Arguments
	 * @param array $instance Widget instance
	 * @since v1.0.12
	 * @author Ralf Hortt
	 */
	function widget( $args, $instance )
	{

		echo $args['before_widget'];

		if ( $instance['title'] )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$params = array(
			'orderby' => 'term_id',
			'order' => 'ASC'
		);
		if ( !empty( $instance['terms'] ) )
			$params['include'] = $instance['terms'];

		$terms = get_terms( 'dish-category', $params );

		if ( $terms ) :

			?>
			<nav class="tablist" role="tablist">

				<?php
				$i = 1;
				foreach ( $terms as $term ) :

					$current = ( 1 == $i ) ? TRUE : FALSE;
					$selected = ( TRUE === $current ) ? 'true' : 'false';

					?>
					<button id="term-<?php $term->term_id ?>" aria-controls="panel-<?php echo $term->term_id ?>" class="tab" role="tab" aria-selected="<?php echo $selected ?>"><?php echo $term->name ?></button>
					<?php

					$i++;

				endforeach;

				?>

			</nav><!-- .tablist -->

			<?php
			$i = 1;
			foreach ( $terms as $term ) :

				$current = ( 1 == $i ) ? TRUE : FALSE;
				$hidden = ( TRUE === $current ) ? 'false' : 'true';
				$selected = ( TRUE === $current ) ? 'true' : 'false';

				?>
				<div id="panel-<?php echo $term->term_id ?>" class="panel" aria-labelledby="term-<?php echo $term->term_id ?>" role="tabpanel" aria-hidden="<?php echo $hidden ?>">

					<h3 class="dish-category-title" aria-controls="panel-<?php echo $term->term_id ?>" role="tab" aria-selected="<?php echo $selected ?>" data-scrollto="1"><?php echo $term->name ?></h3>

					<div class="dishes">

						<?php
						$query = new WP_Query(array(
							'post_type' => 'dish',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'dish-category' => $term->slug
						));

						if ( $query->have_posts() ) :

							while ( $query->have_posts() ) : $query->the_post();

								$meta = get_post_meta( get_the_ID(), '_meta', TRUE );
								?>
								<article class="dish">
									<header><h4 class="dish-title"><?php the_title() ?> <?php if ( $meta['price'] ) : ?><span class="dish-price"><?php echo $meta['price']; ?></span><?php endif; ?></h4>
									<div class="dish-description"><?php the_excerpt() ?></div>
									<?php if ( $meta['quantity'] ) : ?><span class="dish-quantity"><?php echo $meta['quantity']; ?></span><?php endif; ?>
								</article>
								<?php

							endwhile;

						endif;

						wp_reset_query();

						?>

					</div><!-- .dishes -->

				</div><!-- .panel -->
				<?php

				$i++;

			endforeach;

		endif;

		echo $args['after_widget'];

	} // END widget



} // END Custom_Post_Type_Dishes_Cart_Widget

endif;
