<?php
// Register Custom reservas
require PLUGIN_DIR . 'cpt/metabox_reservas.php';
function custom_post_type_reserva() {

	$labels = array(
		'name'                  => _x( 'reservas', 'reserva General Name', 'wp-reserves-system' ),
		'singular_name'         => _x( 'reserva', 'reserva Singular Name', 'wp-reserves-system' ),
		'menu_name'             => __( 'reservas', 'wp-reserves-system' ),
		'name_admin_bar'        => __( 'reserva', 'wp-reserves-system' ),
		'archives'              => __( 'Item Archives', 'wp-reserves-system' ),
		'attributes'            => __( 'Item Attributes', 'wp-reserves-system' ),
		'parent_item_colon'     => __( 'Parent Item:', 'wp-reserves-system' ),
		'all_items'             => __( 'All Items', 'wp-reserves-system' ),
		'add_new_item'          => __( 'Add New Item', 'wp-reserves-system' ),
		'add_new'               => __( 'Add New', 'wp-reserves-system' ),
		'new_item'              => __( 'New Item', 'wp-reserves-system' ),
		'edit_item'             => __( 'Edit Item', 'wp-reserves-system' ),
		'update_item'           => __( 'Update Item', 'wp-reserves-system' ),
		'view_item'             => __( 'View Item', 'wp-reserves-system' ),
		'view_items'            => __( 'View Items', 'wp-reserves-system' ),
		'search_items'          => __( 'Search Item', 'wp-reserves-system' ),
		'not_found'             => __( 'Not found', 'wp-reserves-system' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'wp-reserves-system' ),
		'featured_image'        => __( 'Featured Image', 'wp-reserves-system' ),
		'set_featured_image'    => __( 'Set featured image', 'wp-reserves-system' ),
		'remove_featured_image' => __( 'Remove featured image', 'wp-reserves-system' ),
		'use_featured_image'    => __( 'Use as featured image', 'wp-reserves-system' ),
		'insert_into_item'      => __( 'Insert into item', 'wp-reserves-system' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp-reserves-system' ),
		'items_list'            => __( 'Items list', 'wp-reserves-system' ),
		'items_list_navigation' => __( 'Items list navigation', 'wp-reserves-system' ),
		'filter_items_list'     => __( 'Filter items list', 'wp-reserves-system' ),
	);
	$args = array(
		'label'                 => __( 'reserva', 'wp-reserves-system' ),
		'description'           => __( 'Post Type Description', 'wp-reserves-system' ),
		'labels'                => $labels,
		'supports'              => ['title', 'author'],
		'taxonomies'            => [],
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'menu_icon'             => '',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true
	);
	register_post_type( 'reservas', $args );

}

add_action( 'init', 'custom_post_type_reserva');


