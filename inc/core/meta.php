<?php
/**
 * Registers metadata and handles custom meta functions.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register meta on the 'init' hook.
add_action( 'init', 'pdev_register_meta' );

/**
 * Registers custom metadata for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_register_meta() {

	// URLs.
	register_meta( 'post', 'download_url',  'esc_url_raw', '__return_false' );
	register_meta( 'post', 'repo_url',      'esc_url_raw', '__return_false' );
	register_meta( 'post', 'purchase_url',  'esc_url_raw', '__return_false' );
	register_meta( 'post', 'support_url',   'esc_url_raw', '__return_false' );
	register_meta( 'post', 'docs_url',      'esc_url_raw', '__return_false' );
	register_meta( 'post', 'translate_url', 'esc_url_raw', '__return_false' );

	// Child plugins.
	register_meta( 'post', 'parent_plugin_id', 'absint', '__return_false' ); // back-compat - use post_parent

	// Other data.
	register_meta( 'post', 'wporg_slug',     'sanitize_title_with_dashes', '__return_false' );
	register_meta( 'post', 'version',        'wp_filter_no_html_kses',     '__return_false' );
	register_meta( 'post', 'download_count', 'absint',                     '__return_false' );
	register_meta( 'post', 'install_count',  'absint',                     '__return_false' );
}

/**
 * Returns plugin metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function pdev_get_plugin_meta( $post_id, $meta_key ) {

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Adds/updates plugin metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @param  mixed   $meta_value
 * @return bool
 */
function pdev_set_plugin_meta( $post_id, $meta_key, $meta_value ) {

	return update_post_meta( $post_id, $meta_key, $meta_value );
}

/**
 * Deletes plugin metadata.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $meta_key
 * @return mixed
 */
function pdev_delete_plugin_meta( $post_id, $meta_key ) {

	return delete_post_meta( $post_id, $meta_key );
}
