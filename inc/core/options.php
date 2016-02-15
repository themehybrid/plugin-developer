<?php
/**
 * Functions for handling plugin options.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Returns the menu title.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_menu_title() {
	return apply_filters( 'pdev_get_menu_title', pdev_get_setting( 'menu_title' ) );
}

/**
 * Returns the archive title.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_archive_title() {
	return apply_filters( 'pdev_get_archive_title', pdev_get_setting( 'archive_title' ) );
}

/**
 * Returns the archive description.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_archive_description() {
	return apply_filters( 'pdev_get_archive_description', pdev_get_setting( 'archive_description' ) );
}

/**
 * Returns the rewrite base. Used for the plugin archive and as a prefix for taxonomy,
 * author, and any other slugs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_rewrite_base() {
	return apply_filters( 'pdev_get_rewrite_base', pdev_get_setting( 'rewrite_base' ) );
}

/**
 * Returns the plugin rewrite base. Used for single plugins.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugin_rewrite_base() {
	return apply_filters( 'pdev_get_plugin_rewrite_base', pdev_get_setting( 'plugin_rewrite_base' ) );
}

/**
 * Returns the category rewrite base. Used for category archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_category_rewrite_base() {
	return apply_filters( 'pdev_get_category_rewrite_base', pdev_get_setting( 'category_rewrite_base' ) );
}

/**
 * Returns the tag rewrite base. Used for tag archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_tag_rewrite_base() {
	return apply_filters( 'pdev_get_tag_rewrite_base', pdev_get_setting( 'tag_rewrite_base' ) );
}

/**
 * Returns the author rewrite base. Used for author archives.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_author_rewrite_base() {
	return apply_filters( 'pdev_get_author_rewrite_base', pdev_get_setting( 'author_rewrite_base' ) );
}

/**
 * Checks if we're integrating with WordPress.org.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function pdev_use_wporg_api() {
	return apply_filters( 'pdev_use_wporg_api', pdev_get_setting( 'wporg_integration' ) );
}

/**
 * Returns the transient expiration time for WordPress.org plugin integration.  The setting is
 * saved as days.  We multiply that by the `DAY_IN_SECONDS` constant.  The final value will
 * be days in seconds.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function pdev_get_wporg_transient_expiration() {

	return apply_filters( 'pdev_get_wporg_transient_expiration', absint( pdev_get_setting( 'wporg_transient' ) ) * DAY_IN_SECONDS );
}

/**
 * Returns the number of plugins to show per page.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function pdev_get_plugins_per_page() {

	return apply_filters( 'pdev_get_plugins_per_page', intval( pdev_get_setting( 'plugins_per_page' ) ) );
}

/**
 * Returns the field to order plugins by.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugins_orderby() {

	return apply_filters( 'pdev_get_plugins_orderby', pdev_get_setting( 'plugins_orderby' ) );
}

/**
 * Returns the plugins order.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_plugins_order() {

	return apply_filters( 'pdev_get_plugins_order', pdev_get_setting( 'plugins_order' ) );
}

/**
 * Returns the default category term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function pdev_get_default_category() {
	return apply_filters( 'pdev_get_default_category', 0 );
}

/**
 * Returns the default tag term ID.
 *
 * @since  1.0.0
 * @access public
 * @return int
 */
function pdev_get_default_tag() {
	return apply_filters( 'pdev_get_default_tag', 0 );
}

/**
 * Returns a plugin setting.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $setting
 * @return mixed
 */
function pdev_get_setting( $setting ) {

	$defaults = pdev_get_default_settings();
	$settings = wp_parse_args( get_option( 'pdev_settings', $defaults ), $defaults );

	return isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
}

/**
 * Returns the default settings for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function pdev_get_default_settings() {

	$settings = array(
		'menu_title'                 => __( 'Plugin Developer', 'plugin-developer' ),
		'archive_title'              => __( 'Plugins', 'plugin-developer' ),
		'archive_description'        => '',
		'rewrite_base'               => 'plugins',
		'plugin_rewrite_base'        => '',
		'category_rewrite_base'       => 'categories',
		'tag_rewrite_base'           => 'tags',
		'author_rewrite_base'        => 'authors',
		'wporg_integration'          => true,
		'wporg_transient'            => 3, // days
		'plugins_per_page'           => 10,
		'plugins_orderby'            => 'date',
		'plugins_order'              => 'DESC',
	);

	return $settings;
}
