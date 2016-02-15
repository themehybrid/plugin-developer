<?php
/**
 * Sticky "plugins" feature.  Works like sticky posts but for the plugin archive.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Add sticky posts to the front of the line.
add_filter( 'the_posts', 'pdev_posts_sticky_filter', 10, 2 );

/**
 * Adds a plugin to the list of sticky plugins.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_add_sticky_plugin( $plugin_id ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	if ( ! pdev_is_plugin_sticky( $plugin_id ) )
		return update_option( 'pdev_sticky_plugins', array_unique( array_merge( pdev_get_sticky_plugins(), array( $plugin_id ) ) ) );

	return false;
}

/**
 * Removes a plugin from the list of sticky plugins.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_remove_sticky_plugin( $plugin_id ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	if ( pdev_is_plugin_sticky( $plugin_id ) ) {
		$stickies = pdev_get_sticky_plugins();
		$key      = array_search( $plugin_id, $stickies );

		if ( isset( $stickies[ $key ] ) ) {
			unset( $stickies[ $key ] );
			return update_option( 'pdev_sticky_plugins', array_unique( $stickies ) );
		}
	}

	return false;
}

/**
 * Returns an array of sticky plugins.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_sticky_plugins() {
	return apply_filters( 'pdev_get_sticky_plugins', get_option( 'pdev_sticky_plugins', array() ) );
}

/**
 * Filter on `the_posts` for the plugin archive. Moves sticky posts to the top of
 * the plugin archive list.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $posts
 * @param  object $query
 * @return array
 */
function pdev_posts_sticky_filter( $posts, $query ) {

	// Allow devs to filter when to show sticky plugins.
	$show_stickies = apply_filters( 'pdev_show_stickies', $query->is_main_query() && ! is_admin() && pdev_is_plugin_archive() && ! is_paged() );

	// If we should show stickies, let's get them.
	if ( $show_stickies ) {

		remove_filter( 'the_posts', 'pdev_posts_sticky_filter' );

		$posts = pdev_add_stickies( $posts, pdev_get_sticky_plugins() );
	}

	return $posts;
}

/**
 * Adds sticky posts to the front of the line with any given set of posts and stickies.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $posts         Array of post objects.
 * @param  array  $sticky_posts  Array of post IDs.
 * @return array
 */
function pdev_add_stickies( $posts, $sticky_posts ) {

	// Only do this if on the first page and we indeed have stickies.
	if ( ! empty( $sticky_posts ) ) {

		$num_posts     = count( $posts );
		$sticky_offset = 0;

		// Loop over posts and relocate stickies to the front.
		for ( $i = 0; $i < $num_posts; $i++ ) {

			if ( in_array( $posts[ $i ]->ID, $sticky_posts ) ) {

				$sticky_post = $posts[ $i ];

				// Remove sticky from current position.
				array_splice( $posts, $i, 1);

				// Move to front, after other stickies.
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );

				// Increment the sticky offset. The next sticky will be placed at this offset.
				$sticky_offset++;

				// Remove post from sticky posts array.
				$offset = array_search( $sticky_post->ID, $sticky_posts );

				unset( $sticky_posts[ $offset ] );
			}
		}

		// Fetch sticky posts that weren't in the query results.
		if ( ! empty( $sticky_posts ) ) {

			$args = array(
					'post__in'    => $sticky_posts,
					'post_type'   => pdev_get_plugin_post_type(),
					'post_status' => 'publish',
					'nopaging'    => true
			);

			$stickies = get_posts( $args );

			foreach ( $stickies as $sticky_post ) {
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
				$sticky_offset++;
			}
		}
	}

	return $posts;
}
