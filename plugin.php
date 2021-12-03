<?php
/*
Plugin Name: Custom Post Type Dishes
Plugin URI: http://horttcore.de
Description: A custom post type for managing a restaurant
Version: 1.0
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/

require( 'classes/class.custom-post-type.dishes.php' );
require( 'classes/class.custom-post-type.dishes.widget.php' );
require( 'classes/class.custom-post-type.dishes.cart.widget.php' );

if ( is_admin() )
	require( 'classes/class.custom-post-type.dishes.admin.php' );
