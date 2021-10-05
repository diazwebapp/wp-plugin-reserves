<?php
// Register Custom tarifas
require PLUGIN_DIR . 'cpt/metabox_tarifas.php';
function custom_post_type_tarifa() {

	$labels = [
		'name'                  => __( 'tarifas', 'tarifa General Name', 'wp-reserves-system' ),
		'singular_name'         => __( 'tarifa', 'tarifa Singular Name', 'wp-reserves-system' ),
		'menu_name'             => __( 'tarifas', 'wp-reserves-system' ),
		'name_admin_bar'        => __( 'tarifa', 'wp-reserves-system' ),
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
	];
	$args = [
		'labels'                => $labels,
		'label'                 => __( 'tarifa', 'wp-reserves-system' ),
		'description'           => __( 'Post Type Description', 'wp-reserves-system' ),
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt','author'),
		'taxonomies'            => [],
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-analytics',
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	];
	register_post_type( 'tarifas', $args );

}

add_action( 'init', 'custom_post_type_tarifa');

// incluimos los metabox a la rest-api

function prepare_rest_tarifas($data, $post, $request) {
    $_data = $data->data;
	$fields = get_post_custom($post->ID);
    foreach ($fields as $key => $value){
        $_data['metas'][$key] = get_post_meta($post->ID,$key);
    }
    $data->data = $_data;
    return $data;
}
//add_filter("rest_prepare_tarifas", 'prepare_rest_tarifas', 10, 3);