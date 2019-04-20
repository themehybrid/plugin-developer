<?php
/**
 * Handles the registration of custom post types and related filters.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register custom post types on the 'init' hook.
add_action( 'init', 'pdev_register_post_types' );

# Filter the "enter title here" text.
add_filter( 'enter_title_here', 'pdev_enter_title_here', 10, 2 );

# Filter the bulk and post updated messages.
add_filter( 'bulk_post_updated_messages', 'pdev_bulk_post_updated_messages', 5, 2 );
add_filter( 'post_updated_messages',      'pdev_post_updated_messages',      5    );

/**
 * Returns the name of the plugin post type.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugin_post_type() {

	return apply_filters( 'pdev_get_plugin_post_type', 'plugin' );
}

/**
 * Returns the capabilities for the plugin post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_plugin_capabilities() {

	$caps = array(

		// meta caps (don't assign these to roles)
		'edit_post'              => 'edit_plugin_project',
		'read_post'              => 'read_plugin_project',
		'delete_post'            => 'delete_plugin_project',

		// primitive/meta caps
		'create_posts'           => 'create_plugin_projects',

		// primitive caps used outside of map_meta_cap()
		'edit_posts'             => 'edit_plugin_projects',
		'edit_others_posts'      => 'edit_others_plugin_projects',
		'publish_posts'          => 'publish_plugin_projects',
		'read_private_posts'     => 'read_private_plugin_projects',

		// primitive caps used inside of map_meta_cap()
		'read'                   => 'read',
		'delete_posts'           => 'delete_plugin_projects',
		'delete_private_posts'   => 'delete_private_plugin_projects',
		'delete_published_posts' => 'delete_published_plugin_projects',
		'delete_others_posts'    => 'delete_others_plugin_projects',
		'edit_private_posts'     => 'edit_private_plugin_projects',
		'edit_published_posts'   => 'edit_published_plugin_projects'
	);

	return apply_filters( 'pdev_get_plugin_capabilities', $caps );
}

/**
 * Returns the labels for the plugin post type.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_plugin_labels() {

	$labels = array(
		'name'                  => __( 'Plugins',                   'plugin-developer' ),
		'singular_name'         => __( 'Plugin',                    'plugin-developer' ),
		'menu_name'             => pdev_get_menu_title(),
		'name_admin_bar'        => __( 'Plugin',                    'plugin-developer' ),
		'add_new'               => __( 'New Plugin',                'plugin-developer' ),
		'add_new_item'          => __( 'Add New Plugin',            'plugin-developer' ),
		'edit_item'             => __( 'Edit Plugin',               'plugin-developer' ),
		'new_item'              => __( 'New Plugin',                'plugin-developer' ),
		'view_item'             => __( 'View Plugin',               'plugin-developer' ),
		'search_items'          => __( 'Search Plugins',            'plugin-developer' ),
		'not_found'             => __( 'No plugins found',          'plugin-developer' ),
		'not_found_in_trash'    => __( 'No plugins found in trash', 'plugin-developer' ),
		'all_items'             => __( 'Plugins',                   'plugin-developer' ),
		'featured_image'        => __( 'Banner',                    'plugin-developer' ),
		'set_featured_image'    => __( 'Set banner',                'plugin-developer' ),
		'remove_featured_image' => __( 'Remove banner',             'plugin-developer' ),
		'use_featured_image'    => __( 'Use as banner',             'plugin-developer' ),
		'insert_into_item'      => __( 'Insert into content',       'plugin-developer' ),
		'uploaded_to_this_item' => __( 'Uploaded to this plugin',   'plugin-developer' ),
		'views'                 => __( 'Filter plugins list',       'plugin-developer' ),
		'pagination'            => __( 'Plugins list navigation',   'plugin-developer' ),
		'list'                  => __( 'Plugins list',              'plugin-developer' ),

		// Custom labels b/c WordPress doesn't have anything to handle this.
		'archive_title'         => pdev_get_archive_title(),
	);

	return apply_filters( 'pdev_get_plugin_labels', $labels );
}

/**
 * Registers post types needed by the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_register_post_types() {

	// Set up the arguments for the plugin post type.
	$plugin_args = array(
		'description'         => pdev_get_archive_description(),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-hammer',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => pdev_get_rewrite_base(),
		'query_var'           => 'plugin_project',
		'capability_type'     => 'plugin_project',
		'map_meta_cap'        => true,
		'capabilities'        => pdev_get_plugin_capabilities(),
		'labels'              => pdev_get_plugin_labels(),
		'show_in_rest'        => true,

		// The rewrite handles the URL structure.
		'rewrite' => array(
			'slug'       => pdev_get_plugin_rewrite_slug(),
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		// What features the post type supports.
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',

			// Theme/Plugin feature support.
			'custom-background', // Custom Background Extended
			'custom-header',     // Custom Header Extended
		)
	);

	// Register the post types.
	register_post_type( pdev_get_plugin_post_type(), apply_filters( 'pdev_plugin_post_type_args', $plugin_args ) );
}

/**
 * Custom "enter title here" text.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @param  object  $post
 * @return string
 */
function pdev_enter_title_here( $title, $post ) {

	return pdev_get_plugin_post_type() === $post->post_type ? esc_html__( 'Enter plugin name', 'plugin-developer' ) : '';
}

/**
 * Adds custom post updated messages on the edit post screen.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @global object $post
 * @global int    $post_ID
 * @return array
 */
function pdev_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$plugin_type = pdev_get_plugin_post_type();

	if ( $plugin_type !== $post->post_type )
		return $messages;

	// Get permalink and preview URLs.
	$permalink   = get_permalink( $post_ID );
	$preview_url = get_preview_post_link( $post );

	// Translators: Scheduled plugin date format. See http://php.net/date
	$scheduled_date = date_i18n( __( 'M j, Y @ H:i', 'plugin-developer' ), strtotime( $post->post_date ) );

	// Set up view links.
	$preview_link   = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>', esc_url( $preview_url ), esc_html__( 'Preview plugin', 'plugin-developer' ) );
	$scheduled_link = sprintf( ' <a target="_blank" href="%1$s">%2$s</a>', esc_url( $permalink ),   esc_html__( 'Preview plugin', 'plugin-developer' ) );
	$view_link      = sprintf( ' <a href="%1$s">%2$s</a>',                 esc_url( $permalink ),   esc_html__( 'View plugin',    'plugin-developer' ) );

	// Post updated messages.
	$messages[ $plugin_type ] = array(
		 1 => esc_html__( 'Plugin updated.', 'plugin-developer' ) . $view_link,
		 4 => esc_html__( 'Plugin updated.', 'plugin-developer' ),
		 // Translators: %s is the date and time of the revision.
		 5 => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Plugin restored to revision from %s.', 'plugin-developer' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => esc_html__( 'Plugin published.', 'plugin-developer' ) . $view_link,
		 7 => esc_html__( 'Plugin saved.', 'plugin-developer' ),
		 8 => esc_html__( 'Plugin submitted.', 'plugin-developer' ) . $preview_link,
		 9 => sprintf( esc_html__( 'Plugin scheduled for: %s.', 'plugin-developer' ), "<strong>{$scheduled_date}</strong>" ) . $scheduled_link,
		10 => esc_html__( 'Plugin draft updated.', 'plugin-developer' ) . $preview_link,
	);

	return $messages;
}

/**
 * Adds custom bulk post updated messages on the manage plugins screen.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $messages
 * @param  array  $counts
 * @return array
 */
function pdev_bulk_post_updated_messages( $messages, $counts ) {

	$type = pdev_get_plugin_post_type();

	$messages[ $type ]['updated']   = _n( '%s plugin updated.',                             '%s plugins updated.',                               $counts['updated'],   'plugin-developer' );
	$messages[ $type ]['locked']    = _n( '%s plugin not updated, somebody is editing it.', '%s plugins not updated, somebody is editing them.', $counts['locked'],    'plugin-developer' );
	$messages[ $type ]['deleted']   = _n( '%s plugin permanently deleted.',                 '%s plugins permanently deleted.',                   $counts['deleted'],   'plugin-developer' );
	$messages[ $type ]['trashed']   = _n( '%s plugin moved to the Trash.',                  '%s plugins moved to the trash.',                    $counts['trashed'],   'plugin-developer' );
	$messages[ $type ]['untrashed'] = _n( '%s plugin restored from the Trash.',             '%s plugins restored from the trash.',               $counts['untrashed'], 'plugin-developer' );

	return $messages;
}
