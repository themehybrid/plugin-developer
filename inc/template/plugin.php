<?php
/**
 * Plugin template tags.
 *
 * @package    PluginDeveloper
 * @subpackage Template
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Returns the plugin ID (post ID).
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_id( $plugin_id = 0 ) {

	return $plugin_id ? absint( $plugin_id ) : get_the_ID();
}

/* ====== Conditionals ====== */

/**
 * Checks if the post is a plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_is_plugin( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_is_plugin', pdev_get_plugin_post_type() === get_post_type( $plugin_id ), $plugin_id );
}

/**
 * Conditional check to see if a plugin is sticky.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_is_plugin_sticky( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_is_plugin_sticky', in_array( $plugin_id, pdev_get_sticky_plugins() ), $plugin_id );
}

/**
 * Checks if viewing a single plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $post
 * @return bool
 */
function pdev_is_single_plugin( $post = '' ) {

	$is_single = is_singular( pdev_get_plugin_post_type() );

	if ( $is_single && $post )
		$is_single = is_single( $post );

	return apply_filters( 'pdev_is_single_plugin', $is_single, $post );
}

/**
 * Checks if viewing the plugin archive.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function pdev_is_plugin_archive() {

	return apply_filters( 'pdev_is_plugin_archive', is_post_type_archive( pdev_get_plugin_post_type() ) && ! pdev_is_author() );
}

/**
 * Conditional check to see if a plugin is a child plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_is_add_on_plugin( $plugin_id = 0 ) {

	$plugin_id  = pdev_get_plugin_id( $plugin_id );
	$parent_id = $plugin_id ? pdev_get_parent_plugin_id( $plugin_id ) : 0;

	return apply_filters( 'pdev_is_add_on_plugin', absint( $parent_id ) > 0, $plugin_id );
}

/**
 * Conditional check to see if a plugin is hosted on GitHub (requires a repo URL).
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_is_plugin_on_github( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$repo_url  = pdev_get_plugin_repo_url( $plugin_id );

	return $repo_url && false !== strpos( $repo_url, 'github.com' );
}

/**
 * Conditional check to see if a plugin is hosted on BitBucket (requires a repo URL).
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return bool
 */
function pdev_is_plugin_on_bitbucket( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$repo_url  = pdev_get_plugin_repo_url( $plugin_id );

	return $repo_url && false !== strpos( $repo_url, 'bitbucket.com' );
}

/* ====== Wrapper Functions ====== */

/**
 * Prints the plugin archive URL.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_plugin_archive_url() {

	echo esc_url( pdev_get_plugin_archive_url() );
}

/**
 * Returns the plugin archive URL.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugin_archive_url() {

	return apply_filters( 'pdev_get_plugin_archive_url', get_post_type_archive_link( pdev_get_plugin_post_type() ) );
}

/**
 * Displays the plugin URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_url( $plugin_id = 0 ) {
	echo esc_url( pdev_get_plugin_url( $plugin_id ) );
}

/**
 * Returns the plugin URL.  Wrapper function for `get_permalink()`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_url( $plugin_id = 0 ) {
	$plugin_id  = pdev_get_plugin_id( $plugin_id );
	$plugin_url = $plugin_id ? get_permalink( $plugin_id ) : '';

	return apply_filters( 'pdev_get_plugin_url', $plugin_url, $plugin_id );
}

/**
 * Displays the plugin title.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_title( $plugin_id = 0 ) {
	echo pdev_get_plugin_title( $plugin_id );
}

/**
 * Returns the plugin title.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_title( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$title    = $plugin_id ? get_post_field( 'post_title', $plugin_id ) : '';

	return apply_filters( 'pdev_get_plugin_title', $title, $plugin_id );
}

/**
 * Displays the plugin content.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_content( $plugin_id = 0 ) {
	echo pdev_get_plugin_content( $plugin_id );
}

/**
 * Returns the plugin content.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $forum_id
 * @return string
 */
function pdev_get_plugin_content( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$content   = $plugin_id ? get_post_field( 'post_content', $plugin_id, 'raw' ) : '';

	return apply_filters( 'pdev_get_plugin_content', $content, $plugin_id );
}

/**
 * Displays the plugin excerpt.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_excerpt( $plugin_id = 0 ) {
	echo pdev_get_plugin_excerpt( $plugin_id );
}

/**
 * Returns the plugin excerpt.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $forum_id
 * @return string
 */
function pdev_get_plugin_excerpt( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$excerpt  = $plugin_id ? get_post_field( 'post_excerpt', $plugin_id, 'raw' ) : '';

	return apply_filters( 'pdev_get_plugin_excerpt', $excerpt, $plugin_id );
}

/**
 * Displays the plugin author ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_author_id( $plugin_id = 0 ) {
	echo pdev_get_plugin_author_id( $plugin_id );
}

/**
 * Returns the plugin autor ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_author_id( $plugin_id = 0 ) {
	$plugin_id  = pdev_get_plugin_id( $plugin_id );
	$author_id = $plugin_id ? get_post_field( 'post_author', $plugin_id ) : 0;

	return apply_filters( 'pdev_get_plugin_author_id', absint( $author_id ), $plugin_id );
}

/**
 * Displays the plugin author.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_author( $plugin_id = 0 ) {
	echo pdev_get_plugin_author( $plugin_id );
}

/**
 * Returns the plugin author.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_author( $plugin_id = 0 ) {
	$plugin_id     = pdev_get_plugin_id( $plugin_id );
	$author_id     = pdev_get_plugin_author_id( $plugin_id );
	$plugin_author = $author_id ? get_the_author_meta( 'display_name', $author_id ) : '';

	return apply_filters( 'pdev_get_plugin_author', $plugin_author, $plugin_id );
}

/**
 * Displays the plugin author URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_author_url( $plugin_id = 0 ) {
	echo pdev_get_plugin_author_url( $plugin_id );
}

/**
 * Returns the plugin author URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_author_url( $plugin_id = 0 ) {
	$plugin_id  = pdev_get_plugin_id( $plugin_id );
	$author_id  = pdev_get_plugin_author_id( $plugin_id );
	$author_url = $author_id ? pdev_get_author_url( $author_id ) : '';

	return apply_filters( 'pdev_get_plugin_author_url', $author_url, $plugin_id );
}

/**
 * Displays the plugin author link.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_author_link( $plugin_id = 0 ) {
	echo pdev_get_plugin_author_link( $plugin_id );
}

/**
 * Returns the plugin author link.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_author_link( $plugin_id = 0 ) {
	$plugin_id     = pdev_get_plugin_id( $plugin_id );
	$plugin_author = pdev_get_plugin_author( $plugin_id );
	$author_url    = pdev_get_plugin_author_url( $plugin_id );
	$author_link   = $author_url ? sprintf( '<a class="pdev-plugin-author-link" href="%s">%s</a>', $author_url, $plugin_author ) : '';

	return apply_filters( 'pdev_get_plugin_author_link', $author_link, $plugin_id );
}

/**
 * Returns a list of plugin contributor IDs.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_plugin_contributor_ids( $plugin_id = 0 ) {
	$plugin_id    = pdev_get_plugin_id( $plugin_id );
	$contributors = get_post_meta( $plugin_id, 'contributor' );

	return apply_filters( 'pdev_get_plugin_contributor_ids', (array) $contributors, $plugin_id );
}

/**
 * Returns a list of plugin contributor user objects.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array|bool
 */
function pdev_get_plugin_contributor_objects( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$user_ids  = pdev_get_plugin_contributor_ids( $plugin_id );

	if ( ! $user_ids )
		return false;

	$users = get_users( array( 'include' => $user_ids ) );

	return apply_filters( 'pdev_get_plugin_contributor_objects', $users, $plugin_id );
}

function pdev_plugin_contributor_ids_plus_author( $user_ids, $plugin_id ) {

	$author_id = pdev_get_plugin_author_id( $plugin_id );

	return ! in_array( $author_id, $user_ids ) ? array_merge( array( $author_id ), $user_ids ) : $user_ids;
}

/**
 * Returns an HTML list of plugin contributors.
 *
 * @since  1.0.0
 * @access public
 * @param  array   $args
 * @return string
 */
function pdev_list_plugin_contributors( $args = array() ) {

	// Set up the default arguments.
	$defaults = array(
		'plugin_id'      => pdev_get_plugin_id(), // The plugin ID. Assumes within The Loop.
		'include_author' => false,                // Whether to include the plugin author if not listed as contrib.
		'echo'           => true
	);

	// Parse the arguments and allow devs to filter.
	$args = apply_filters( 'pdev_list_plugin_contributors_args', wp_parse_args( $args, $defaults ) );

	if ( $args['include_author'] )
		add_filter( 'pdev_get_plugin_contributor_objects', 'pdev_plugin_contributor_ids_plus_author', 5, 2 );

	// Set up some variables we'll need.
	$html       = '';
	$users      = pdev_get_plugin_contributor_objects( $args['plugin_id'] );
	$has_author = false;

	if ( $args['include_author'] )
		remove_filter( 'pdev_get_plugin_contributor_objects', 'pdev_plugin_contributor_ids_plus_author', 5 );

	// Check if we have contributor/user objects.
	if ( $users ) {

		foreach ( $users as $user ) {

			// If including the plugin author, check if the current user matches.
			if ( $args['include_author'] && $author_id === $user->ID )
				$has_author = true;

			$html .= sprintf( '<li><a href="%s">%s</a></li>', esc_url( pdev_get_author_url( $user->ID ) ), esc_html( $user->display_name ) );
		}

		$html = sprintf( '<ul class="pdev-list-plugin-contributors">%s</ul>', $html );
	}

	echo apply_filters( 'pdev_list_plugin_contributors', $html, $args['plugin_id'], $args );
}

/* ====== Images ====== */

/**
 * Conditional check to see if the plugin has a banner image.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_plugin_has_banner( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_plugin_has_banner', has_post_thumbnail( $plugin_id ), $plugin_id );
}

/**
 * Prints the plugin banner attachment ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_banner_id( $plugin_id = 0 ) {

	echo absint( pdev_get_plugin_banner_id( $plugin_id ) );
}

/**
 * Returns the plugin banner attachment ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_banner_id( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_banner_id', get_post_thumbnail_id( $plugin_id ), $plugin_id );
}

/**
 * Prints the plugin banner attachment URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_banner_url( $plugin_id = 0, $size = 'post-thumbnail' ) {

	echo esc_url( pdev_get_plugin_banner_url( $plugin_id, $size ) );
}

/**
 * Returns the plugin banner attachment URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_banner_url( $plugin_id = 0, $size = 'post-thumbnail' ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$banner_id  = pdev_get_plugin_banner_id( $plugin_id );
	$banner_url = $banner_id ? get_the_post_thumbnail_url( $banner_id, $size ) : '';

	return apply_filters( 'pdev_get_plugin_banner_url', $banner_url, $plugin_id, $icon_id, $size );
}

/**
 * Prints the plugin banner HTML.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_banner( $plugin_id = 0, $size = 'post-thumbnail', $attr = '' ) {

	echo pdev_get_plugin_banner( $plugin_id, $size, $attr );
}

/**
 * Returns the plugin banner HTML.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_banner( $plugin_id = 0, $size = 'post-thumbnail', $attr = '' ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	$banner = get_the_post_thumbnail( $plugin_id, $size, $attr );

	return apply_filters( 'pdev_get_plugin_banner', $banner, $plugin_id, $size, $attr );
}

/**
 * Conditional check to see if the plugin has an icon image.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_plugin_has_icon( $plugin_id = 0 ) {

	return apply_filters( 'pdev_plugin_has_icon', (bool) pdev_get_plugin_icon_id( $plugin_id ), $plugin_id );
}

/**
 * Prints the plugin icon attachment ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_icon_id( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );

	echo absint( pdev_get_plugin_icon_id( $plugin_id ) );
}

/**
 * Returns the plugin icon attachment ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_icon_id( $plugin_id = 0 ) {

	return apply_filters( 'pdev_get_plugin_icon_id', pdev_get_plugin_meta( $plugin_id, 'icon_image_id' ), $plugin_id );
}

/**
 * Prints the plugin icon attachment URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_icon_url( $plugin_id = 0, $size = 'post-thumbnail' ) {

	echo esc_url( pdev_get_plugin_icon_url( $plugin_id, $size ) );
}

/**
 * Returns the plugin icon attachment URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_icon_url( $plugin_id = 0, $size = 'post-thumbnail' ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$icon_id  = pdev_get_plugin_icon_id( $plugin_id );
	$icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, $size ) : '';

	return apply_filters( 'pdev_get_plugin_icon_url', $icon_url, $plugin_id, $icon_id, $size );
}

/**
 * Prints the plugin icon HTML.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_icon( $plugin_id = 0, $size = 'post-thumbnail', $attr = '' ) {

	echo pdev_get_plugin_icon( $plugin_id, $size, $attr );
}

/**
 * Returns the plugin icon HTML.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_icon( $plugin_id = 0, $size = 'post-thumbnail', $attr = '' ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$icon_id  = pdev_get_plugin_icon_id( $plugin_id );

	$icon = $icon_id ? wp_get_attachment_image( $icon_id, $size, false, $attr ) : '';

	return apply_filters( 'pdev_get_plugin_icon', $icon, $plugin_id, $icon_id, $size, $attr );
}

/* ====== Parent Plugin ====== */

/**
 * Returns the parent plugin ID.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return int
 */
function pdev_get_parent_plugin_id( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$parent_id = $plugin_id ? get_post_field( 'post_parent', $plugin_id ) : 0;

	return apply_filters( 'pdev_get_parent_plugin_id', $parent_id, $plugin_id );
}

/**
 * Returns the parent plugin `WP_POST` object if there's a parent. Else, returns `false`.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $plugin_id
 * @return object|false
 */
function pdev_get_parent_plugin( $plugin_id = 0 ) {
	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$parent_id = pdev_get_parent_plugin_id( $plugin_id );
	$parent    = 0 < $parent_id ? get_post( $plugin_id ) : false;

	return apply_filters( 'pdev_get_parent_plugin_id', $parent, $plugin_id );
}

/* ====== Meta ====== */

/**
 * Prints the plugin version number.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_plugin_version( $plugin_id = 0 ) {

	echo pdev_get_plugin_version( $plugin_id );
}

/**
 * Returns the plugin version number.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_get_plugin_version( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_version', pdev_get_plugin_meta( $plugin_id, 'version' ), $plugin_id );
}

/**
 * Prints the plugin download URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void.
 */
function pdev_plugin_download_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_download_url( $plugin_id ) );
}

/**
 * Returns the plugin download URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_download_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_download_url', pdev_get_plugin_meta( $plugin_id, 'download_url' ), $plugin_id );
}

/**
 * Prints the plugin download link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_download_link( $args = array() ) {

	echo pdev_get_plugin_download_link( $args );
}

/**
 * Returns the plugin download link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_download_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Download', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_download_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-download-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_download_link', $link, $args );
}

/**
 * Prints the plugin repository URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_repo_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_repo_url( $plugin_id ) );
}

/**
 * Returns the plugin repository URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_repo_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_repo_url', pdev_get_plugin_meta( $plugin_id, 'repo_url' ), $plugin_id );
}

/**
 * Prints the plugin repo link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_repo_link( $args = array() ) {

	echo pdev_get_plugin_repo_link( $args );
}

/**
 * Returns the plugin repo link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_repo_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Repository', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_repo_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-repo-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_repo_link', $link, $args );
}

/**
 * Prints the plugin purchasae URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_purchase_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_purchase_url( $plugin_id ) );
}

/**
 * Returns the plugin purchase URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_purchase_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_purchase_url', pdev_get_plugin_meta( $plugin_id, 'purchase_url' ), $plugin_id );
}

/**
 * Prints the plugin purchase link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_purchase_link( $args = array() ) {

	echo pdev_get_plugin_purchase_link( $args );
}

/**
 * Returns the plugin purchase link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_purchase_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Purchase', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_purchase_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-purchase-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_purchase_link', $link, $args );
}

/**
 * Prints the plugin support URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_support_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_support_url( $plugin_id ) );
}

/**
 * Returns the plugin support URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_support_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_support_url', pdev_get_plugin_meta( $plugin_id, 'support_url' ), $plugin_id );
}

/**
 * Prints the plugin support link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_support_link( $args = array() ) {

	echo pdev_get_plugin_support_link( $args );
}

/**
 * Returns the plugin support link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_support_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Support', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_support_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-support-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_support_link', $link, $args );
}

/**
 * Prints the plugin translation URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_translate_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_translate_url( $plugin_id ) );
}

/**
 * Returns the plugin translation URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_translate_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_translate_url', pdev_get_plugin_meta( $plugin_id, 'translate_url' ), $plugin_id );
}

/**
 * Prints the plugin translate link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_translate_link( $args = array() ) {

	echo pdev_get_plugin_translate_link( $args );
}

/**
 * Returns the plugin translate link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_translate_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Translations', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_translate_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-translate-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_translate_link', $link, $args );
}

/**
 * Prints the plugin documentation URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_docs_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_plugin_docs_url( $plugin_id ) );
}

/**
 * Returns the plugin documentation URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_docs_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_docs_url', pdev_get_plugin_meta( $plugin_id, 'docs_url' ), $plugin_id );
}

/**
 * Prints the plugin docs link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function pdev_plugin_docs_link( $args = array() ) {

	echo pdev_get_plugin_docs_link( $args );
}

/**
 * Returns the plugin docs link.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function pdev_get_plugin_docs_link( $args = array() ) {

	$defaults = array(
		'plugin_id' => pdev_get_plugin_id(),
		'text'     => __( 'Documentation', 'plugin-developer' )
	);

	$args = wp_parse_args( $args, $defaults );

	$url = pdev_get_plugin_docs_url( $args['plugin_id'] );

	$link = $url ? sprintf( '<a class="pdev-plugin-docs-link" href="%s">%s</a>', esc_url( $url ), $args['text'] ) : '';

	return apply_filters( 'pdev_get_plugin_docs_link', $link, $args );
}

/**
 * Prints the plugin WordPress.org slug.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_wporg_slug( $plugin_id = 0 ) {

	echo pdev_get_plugin_wporg_slug( $plugin_id );
}

/**
 * Returns the plugin WordPress.org slug.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_plugin_wporg_slug( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_wporg_slug', pdev_get_plugin_meta( $plugin_id, 'wporg_slug' ), $plugin_id );
}

/**
 * Prints the plugin download count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_download_count( $plugin_id = 0 ) {

	echo number_format_i18n( absint( pdev_get_plugin_download_count( $plugin_id ) ) );
}

/**
 * Returns the plugin download count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_download_count( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_download_count', absint( pdev_get_plugin_meta( $plugin_id, 'download_count' ) ), $plugin_id );
}

/**
 * Prints the plugin install count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_install_count( $plugin_id = 0 ) {

	// Translators: Approximate count. The %s is a number.  The + means "more than."
	echo sprintf( __( '%s+', 'plugin-developer' ), number_format_i18n( absint( pdev_get_plugin_install_count( $plugin_id ) ) ) );
}

/**
 * Returns the plugin install count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_install_count( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_install_count', absint( pdev_get_plugin_meta( $plugin_id, 'install_count' ) ), $plugin_id );
}

/**
 * Prints the plugin rating.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_rating( $plugin_id = 0 ) {

	echo pdev_get_plugin_rating( $plugin_id );
}

/**
 * Returns the plugin rating.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return float
 */
function pdev_get_plugin_rating( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_rating', floatval( pdev_get_plugin_meta( $plugin_id, 'rating' ) ), $plugin_id );
}

/**
 * Prints the plugin rating count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_plugin_rating_count( $plugin_id = 0 ) {

	echo number_format_i18n( pdev_get_plugin_rating_count( $plugin_id ) );
}

/**
 * Returns the plugin rating count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_plugin_rating_count( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	return apply_filters( 'pdev_get_plugin_rating_count', absint( pdev_get_plugin_meta( $plugin_id, 'rating_count' ) ), $plugin_id );
}
