<?php
/**
 * Handles registering custom taxonomies and related filters.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register taxonomies on the 'init' hook.
add_action( 'init', 'pdev_register_taxonomies', 9 );

# Filter the term updated messages.
add_filter( 'term_updated_messages', 'pdev_term_updated_messages', 5 );

/**
 * Returns the name of the category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_category_taxonomy() {

	return apply_filters( 'pdev_get_category_taxonomy', 'plugin_category' );
}

/**
 * Returns the name of the tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_tag_taxonomy() {

	return apply_filters( 'pdev_get_tag_taxonomy', 'plugin_tag' );
}

/**
 * Returns the capabilities for the category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_category_capabilities() {

	$caps = array(
		'manage_terms' => 'manage_plugin_categories',
		'edit_terms'   => 'manage_plugin_categories',
		'delete_terms' => 'manage_plugin_categories',
		'assign_terms' => 'edit_plugin_projects'
	);

	return apply_filters( 'pdev_get_category_capabilities', $caps );
}

/**
 * Returns the capabilities for the tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_tag_capabilities() {

	$caps = array(
		'manage_terms' => 'manage_plugin_tags',
		'edit_terms'   => 'manage_plugin_tags',
		'delete_terms' => 'manage_plugin_tags',
		'assign_terms' => 'edit_plugin_projects',
	);

	return apply_filters( 'pdev_get_tag_capabilities', $caps );
}

/**
 * Returns the labels for the category taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_category_labels() {

	$labels = array(
		'name'                       => __( 'Categories',                           'plugin-developer' ),
		'singular_name'              => __( 'Category',                             'plugin-developer' ),
		'menu_name'                  => __( 'Categories',                           'plugin-developer' ),
		'name_admin_bar'             => __( 'Category',                             'plugin-developer' ),
		'search_items'               => __( 'Search Categories',                    'plugin-developer' ),
		'popular_items'              => __( 'Popular Categories',                   'plugin-developer' ),
		'all_items'                  => __( 'All Categories',                       'plugin-developer' ),
		'edit_item'                  => __( 'Edit Category',                        'plugin-developer' ),
		'view_item'                  => __( 'View Category',                        'plugin-developer' ),
		'update_item'                => __( 'Update Category',                      'plugin-developer' ),
		'add_new_item'               => __( 'Add New Category',                     'plugin-developer' ),
		'new_item_name'              => __( 'New Category Name',                    'plugin-developer' ),
		'not_found'                  => __( 'No categories found.',                 'plugin-developer' ),
		'no_terms'                   => __( 'No categories',                        'plugin-developer' ),
		'pagination'                 => __( 'Categories list navigation',           'plugin-developer' ),
		'list'                       => __( 'Categories list',                      'plugin-developer' ),

		// Hierarchical only.
		'select_name'                => __( 'Select Category',                      'plugin-developer' ),
		'parent_item'                => __( 'Parent Category',                      'plugin-developer' ),
		'parent_item_colon'          => __( 'Parent Category:',                     'plugin-developer' ),
	);

	return apply_filters( 'pdev_get_category_labels', $labels );
}

/**
 * Returns the labels for the tag taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_tag_labels() {

	$labels = array(
		'name'                       => __( 'Tags',                           'plugin-developer' ),
		'singular_name'              => __( 'Tag',                            'plugin-developer' ),
		'menu_name'                  => __( 'Tags',                           'plugin-developer' ),
		'name_admin_bar'             => __( 'Tag',                            'plugin-developer' ),
		'search_items'               => __( 'Search Tags',                    'plugin-developer' ),
		'popular_items'              => __( 'Popular Tags',                   'plugin-developer' ),
		'all_items'                  => __( 'All Tags',                       'plugin-developer' ),
		'edit_item'                  => __( 'Edit Tag',                       'plugin-developer' ),
		'view_item'                  => __( 'View Tag',                       'plugin-developer' ),
		'update_item'                => __( 'Update Tag',                     'plugin-developer' ),
		'add_new_item'               => __( 'Add New Tag',                    'plugin-developer' ),
		'new_item_name'              => __( 'New Tag Name',                   'plugin-developer' ),
		'not_found'                  => __( 'No tags found.',                 'plugin-developer' ),
		'no_terms'                   => __( 'No tags',                        'plugin-developer' ),
		'pagination'                 => __( 'Tags list navigation',           'plugin-developer' ),
		'list'                       => __( 'Tags list',                      'plugin-developer' ),

		// Non-hierarchical only.
		'separate_items_with_commas' => __( 'Separate tags with commas',      'plugin-developer' ),
		'add_or_remove_items'        => __( 'Add or remove tags',             'plugin-developer' ),
		'choose_from_most_used'      => __( 'Choose from the most used tags', 'plugin-developer' ),
	);

	return apply_filters( 'pdev_get_tag_labels', $labels );
}

/**
 * Register taxonomies for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void.
 */
function pdev_register_taxonomies() {

	// Set up the arguments for the category taxonomy.
	$cat_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'query_var'         => pdev_get_category_taxonomy(),
		'capabilities'      => pdev_get_category_capabilities(),
		'labels'            => pdev_get_category_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => pdev_get_category_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),
	);

	// Set up the arguments for the tag taxonomy.
	$tag_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => false,
		'hierarchical'      => false,
		'query_var'         => pdev_get_tag_taxonomy(),
		'capabilities'      => pdev_get_tag_capabilities(),
		'labels'            => pdev_get_tag_labels(),

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'         => pdev_get_tag_rewrite_slug(),
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),
	);

	// Register the taxonomies.
	register_taxonomy( pdev_get_category_taxonomy(), pdev_get_plugin_post_type(), apply_filters( 'pdev_category_taxonomy_args', $cat_args ) );
	register_taxonomy( pdev_get_tag_taxonomy(),      pdev_get_plugin_post_type(), apply_filters( 'pdev_tag_taxonomy_args',      $tag_args ) );
}

/**
 * Filters the term updated messages in the admin.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @return array
 */
function pdev_term_updated_messages( $messages ) {

	$cat_taxonomy = pdev_get_category_taxonomy();
	$tag_taxonomy = pdev_get_tag_taxonomy();

	// Add the category messages.
	$messages[ $cat_taxonomy ] = array(
		0 => '',
		1 => __( 'Category added.',       'plugin-developer' ),
		2 => __( 'Category deleted.',     'plugin-developer' ),
		3 => __( 'Category updated.',     'plugin-developer' ),
		4 => __( 'Category not added.',   'plugin-developer' ),
		5 => __( 'Category not updated.', 'plugin-developer' ),
		6 => __( 'Categories deleted.',   'plugin-developer' ),
	);

	// Add the tag messages.
	$messages[ $tag_taxonomy ] = array(
		0 => '',
		1 => __( 'Tag added.',       'plugin-developer' ),
		2 => __( 'Tag deleted.',     'plugin-developer' ),
		3 => __( 'Tag updated.',     'plugin-developer' ),
		4 => __( 'Tag not added.',   'plugin-developer' ),
		5 => __( 'Tag not updated.', 'plugin-developer' ),
		6 => __( 'Tags deleted.',    'plugin-developer' ),
	);

	return $messages;
}
