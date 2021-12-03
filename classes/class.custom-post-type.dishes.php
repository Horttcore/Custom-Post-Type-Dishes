<?php
/**
 * undocumented class
 *
 * @package default
 * @author
 **/
final class Custom_Post_Type_Dishes
{




	/**
	 * undocumented function
	 *
	 * @return void
	 * @author
	 **/
	function __construct()
	{

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

	} // END __construct



	/**
	 * Load plugin translation
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since v1.0.0
	 **/
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain( 'custom-post-type-dishes', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/'  );

	} // END load_plugin_textdomain



	/**
	 *
	 * Register post type
	 *
	 * @access public
	 * @author Ralf Hortt <me@horttcore.de>
 	 * @since v1.0.0
	 */
	public function register_post_type()
	{

		$labels = array(
			'name' => _x( 'Dishes', 'post type general name', 'custom-post-type-dishes' ),
			'singular_name' => _x( 'Dish', 'post type singular name', 'custom-post-type-dishes' ),
			'add_new' => _x( 'Add New', 'dish', 'custom-post-type-dishes' ),
			'add_new_item' => __( 'Add New dish', 'custom-post-type-dishes' ),
			'edit_item' => __( 'Edit dish', 'custom-post-type-dishes' ),
			'new_item' => __( 'New dish', 'custom-post-type-dishes' ),
			'view_item' => __( 'View dish', 'custom-post-type-dishes' ),
			'search_items' => __( 'Search dishes', 'custom-post-type-dishes' ),
			'not_found' =>  __( 'No dishes found', 'custom-post-type-dishes' ),
			'not_found_in_trash' => __( 'No dishes found in Trash', 'custom-post-type-dishes' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Menu', 'custom-post-type-dishes' )
		);

		$args = array(
			'labels' => $labels,
			'public' => FALSE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x( 'dishes', 'post type slug', 'custom-post-type-dishes' ) ),
			'capability_type' => 'post',
			'has_archive' => FALSE,
			'hierarchical' => TRUE,
			'menu_position' => null,
			'show_in_nav_menus' => FALSE,
			'menu_icon' => 'dashicons-book',
			'supports' => array( 'title', 'editor', 'page-attributes' )
		);

		register_post_type( 'dish',$args);

	} // END register_post_type



	/**
	 *
	 * Register taxonomy
	 *
	 * @access public
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since v1.0.0
	 */
	public function register_taxonomy()
	{

		$labels = array(
			'name' => _x( 'Categories', 'taxonomy general name', 'custom-post-type-dishes' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name', 'custom-post-type-dishes' ),
			'search_items' =>  __( 'Search Categories', 'custom-post-type-dishes' ),
			'popular_items' => __( 'Popular Categories', 'custom-post-type-dishes' ),
			'all_items' => __( 'All Categories', 'custom-post-type-dishes' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Category', 'custom-post-type-dishes' ),
			'update_item' => __( 'Update Category', 'custom-post-type-dishes' ),
			'add_new_item' => __( 'Add New Category', 'custom-post-type-dishes' ),
			'new_item_name' => __( 'New Category Name', 'custom-post-type-dishes' ),
			'menu_name' => __( 'Category', 'custom-post-type-dishes' ),
		);

		register_taxonomy( 'dish-category', 'dish', array(
			'hierarchical' => TRUE,
			'labels' => $labels,
			'show_ui' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x( 'dish-category', 'taxonomy slug', 'custom-post-type-dishes' ) ),
			'show_admin_column' => TRUE,
			'show_in_menu' => TRUE,
		));

	} // END register_taxonomy



	/**
	 *
	 * Register taxonomy
	 *
	 * @access public
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since v1.0.0
	 */
	public function widgets_init()
	{

		register_widget( 'Custom_Post_Type_Dishes_Widget' );
		register_widget( 'Custom_Post_Type_Dishes_Cart_Widget' );

	} // END widgets_init



} // END final class Custom_Post_Type_Dishes

new Custom_Post_Type_Dishes;
