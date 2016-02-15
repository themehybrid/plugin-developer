<?php
/**
 * Handles custom rewrite rules.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Add custom rewrite rules.
add_action( 'init', 'pdev_rewrite_rules', 5 );

/**
 * Adds custom rewrite rules for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_rewrite_rules() {

	$plugin_type = pdev_get_plugin_post_type();
	$author_slug = pdev_get_author_rewrite_slug();

	// Where to place the rewrite rules.  If no rewrite base, put them at the bottom.
	$after = pdev_get_author_rewrite_base() ? 'top' : 'bottom';

	add_rewrite_rule( $author_slug . '/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?post_type=' . $plugin_type . '&author_name=$matches[1]&paged=$matches[2]', $after );
	add_rewrite_rule( $author_slug . '/([^/]+)/?$',                   'index.php?post_type=' . $plugin_type . '&author_name=$matches[1]',                   $after );
}

/**
 * Returns the plugin rewrite slug used for single plugins.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugin_rewrite_slug() {
	$rewrite_base = pdev_get_rewrite_base();
	$plugin_base  = pdev_get_plugin_rewrite_base();

	$slug = $plugin_base ? trailingslashit( $rewrite_base ) . $plugin_base : $rewrite_base;

	return apply_filters( 'pdev_get_plugin_rewrite_slug', $slug );
}

/**
 * Returns the category rewrite slug used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_category_rewrite_slug() {
	$rewrite_base = pdev_get_rewrite_base();
	$category_base = pdev_get_category_rewrite_base();

	$slug = $category_base ? trailingslashit( $rewrite_base ) . $category_base : $rewrite_base;

	return apply_filters( 'pdev_get_category_rewrite_slug', $slug );
}

/**
 * Returns the tag rewrite slug used for feature archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_tag_rewrite_slug() {
	$rewrite_base = pdev_get_rewrite_base();
	$tag_base     = pdev_get_tag_rewrite_base();

	$slug = $tag_base ? trailingslashit( $rewrite_base ) . $tag_base : $rewrite_base;

	return apply_filters( 'pdev_get_tag_rewrite_slug', $slug );
}

/**
 * Returns the author rewrite slug used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_author_rewrite_slug() {
	$rewrite_base = pdev_get_rewrite_base();
	$author_base  = pdev_get_author_rewrite_base();

	$slug = $author_base ? trailingslashit( $rewrite_base ) . $author_base : $rewrite_base;

	return apply_filters( 'pdev_get_author_rewrite_slug', $slug );
}
