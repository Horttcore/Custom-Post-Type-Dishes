<?php
/**
 *
 *  Custom Post Type Job
 *
 */
final class Custom_Post_Type_Dishes_Admin
{



	/**
	 * Plugin constructor
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function __construct()
	{

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'manage_dish_posts_custom_column', array( $this, 'manage_dish_posts_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-dish_columns', array( $this, 'manage_edit_dish_columns' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	} // end __construct



	/**
	 * Register Metaboxes
	 *
	 * @access public
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{

		add_meta_box( 'dish-meta', __( 'Information', 'cpt-dish' ), array( $this, 'dish_meta' ), 'dish', 'normal' );

	} // end add_meta_boxes



	/**
	 * Metabox
	 *
	 * @access public
	 * @param obj $post Post object
	 * @author Ralf Hortt
	 **/
	public function dish_meta( $post )
	{

		$meta = get_post_meta( $post->ID, '_meta', TRUE );

		do_action( 'dish-meta-table-before', $post, $meta );

		?>

		<table class="form-table">

			<?php do_action( 'dish-meta-before', $post, $meta ); ?>

			<tr>
				<th><label for="dish-quantity"><?php _e( 'Quantity', 'custom-post-type-dishes' ) ?></label></th>
				<td><input id="dish-quantity" name="dish-quantity" class="large-text" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['quantity'] ) ?>"></td>
			</tr>

			<tr>
				<th><label for="dish-price"><?php _e( 'Price', 'custom-post-type-dishes' ) ?></label></th>
				<td><input id="dish-price" name="dish-price" class="large-text" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['price'] ) ?>"></td>
			</tr>

			<?php do_action( 'dish-meta-after', $post, $meta ); ?>

		</table>

		<?php

		do_action( 'dish-meta-table-after', $post, $meta );

		wp_nonce_field( 'save-dish-meta', 'dish-meta-nonce' );

	} // end dish_meta



	/**
	 * Add custom columns
	 *
	 * @access public
	 * @param array $columns Columns
	 * @return array Columns
	 * @since 2.0
	 * @author Ralf Hortt
	 **/
	public function manage_edit_dish_columns( $columns )
	{

		$columns['quantity'] = __( 'Quantity', 'custom-post-type-dishes' );
		$columns['price'] = __( 'Price', 'custom-post-type-dishes' );

		return $columns;

	} // END manage_edit_dish_columns



	/**
	 * Print custom columns
	 *
	 * @access public
	 * @param str $column Column name
	 * @param int $post_id Post ID
	 * @since 2.0
	 * @author Ralf Hortt
	 **/
	public function manage_dish_posts_custom_column( $column, $post_id )
	{

		global $post;

		$meta = get_post_meta( get_the_ID(), '_meta', TRUE );

		switch( $column ) :

			case 'quantity' :
				echo $meta['quantity'];
				break;

			case 'price' :
				echo $meta['price'];
				break;

		endswitch;

	} // END manage_dish_posts_custom_column



	/**
	 * Update messages
	 *
	 * @access public
	 * @param array $messages Messages
	 * @return array Messages
	 * @author Ralf Hortt
	 **/
	public function post_updated_messages( $messages ) {

		global $post, $post_ID;

		$messages['dish'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Dish updated. <a href="%s">View dish</a>', 'dishs'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.', 'dishs'),
			3 => __('Custom field deleted.', 'dishs'),
			4 => __('dish updated.', 'dishs'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('dish restored to revision from %s', 'dishs'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('dish published. <a href="%s">View dish</a>', 'dishs'), esc_url( get_permalink($post_ID) ) ),
			7 => __('dish saved.', 'dishs'),
			8 => sprintf( __('dish submitted. <a target="_blank" href="%s">Preview dish</a>', 'dishs'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('dish scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview dish</a>', 'dishs'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('dish draft updated. <a target="_blank" href="%s">Preview dish</a>', 'dishs'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
		);

		return $messages;

	} // end post_updated_messages



	/**
	 * Save post meta
	 *
	 * @access public
	 * @param int $post_id Post ID
	 * @author Ralf Hortt
	 **/
	public function save_post( $post_id )
	{

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['dish-meta-nonce'] ) || !wp_verify_nonce( $_POST['dish-meta-nonce'], 'save-dish-meta' ) )
			return;

		$meta = array(
			'quantity' => sanitize_text_field( $_POST['dish-quantity'] ),
			'price' => sanitize_text_field( $_POST['dish-price'] ),
		);

		update_post_meta( $post_id, '_meta', apply_filters( 'save-dish-meta', $meta, $post_id ) );

	} // end save_post



}

new Custom_Post_Type_Dishes_Admin;
