<?php
/*Plugin Name: Philosophy Companion
Plugin URI:
Description:
Version:
Author:
Author URI:
License: GPLv2 or Later
Text Domain:philosophy_companion*/

function philosophy_companion_cptui_register_my_cpts_book() {

	/**
	 * Post Type: Books.
	 */

	$labels = [
		"name" => __( "Books", "custom-post-type-ui" ),
		"singular_name" => __( "Book", "custom-post-type-ui" ),
		"all_items" => __( "My Books", "custom-post-type-ui" ),
		"add_new" => __( "New Book", "custom-post-type-ui" ),
		"add_new_item" => __( "Add New Book", "custom-post-type-ui" ),
		"edit_item" => __( "Edit Book", "custom-post-type-ui" ),
		"new_item" => __( "New Book", "custom-post-type-ui" ),
		"view_item" => __( "View Book", "custom-post-type-ui" ),
		"view_items" => __( "View Books", "custom-post-type-ui" ),
		"search_items" => __( "book", "custom-post-type-ui" ),
		"not_found" => __( "Book  not found", "custom-post-type-ui" ),
		"featured_image" => __( "Cover Image", "custom-post-type-ui" ),
		"set_featured_image" => __( "Set cover image for this book", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "Books", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => "books",
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "book", "with_front" => false ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "author", "page-attributes" ],
		"taxonomies" => [ "category", "post_tag" ],
	];

	register_post_type( "book", $args );
}

add_action( 'init', 'philosophy_companion_cptui_register_my_cpts_book' );
