<?php
/**
 * WordPress.org plugin integration functions and filters.
 *
 * @package    PluginDeveloper
 * @subpackage WPORG
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# WPORG API usage.
add_action( 'wp_loaded', 'pdev_use_wporg_api_filters', 0 );

/**
 * Checks if we're using the WordPress.org Plugins API.  If so, run filters over
 * getter function hooks.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_use_wporg_api_filters() {

	if ( ! pdev_use_wporg_api() )
		return;

	add_filter( 'pdev_get_plugin_version',        'pdev_wporg_plugin_version_filter',        5, 2 );
	add_filter( 'pdev_get_plugin_download_url',   'pdev_wporg_plugin_download_url_filter',   5, 2 );
	add_filter( 'pdev_get_plugin_repo_url',       'pdev_wporg_plugin_repo_url_filter',       5, 2 );
	add_filter( 'pdev_get_plugin_support_url',    'pdev_wporg_plugin_support_url_filter',    5, 2 );
	add_filter( 'pdev_get_plugin_translate_url',  'pdev_wporg_plugin_translate_url_filter',  5, 2 );
	add_filter( 'pdev_get_plugin_download_count', 'pdev_wporg_plugin_download_count_filter', 5, 2 );
	add_filter( 'pdev_get_plugin_install_count',  'pdev_wporg_plugin_install_count_filter',  5, 2 );
	add_filter( 'pdev_get_plugin_rating',         'pdev_wporg_plugin_rating_filter',         5, 2 );
	add_filter( 'pdev_get_plugin_rating_count',   'pdev_wporg_plugin_rating_count_filter',   5, 2 );
	add_filter( 'pdev_plugin_has_banner',         'pdev_wporg_plugin_has_banner_filter',     5, 2 );
	add_filter( 'pdev_plugin_has_icon',           'pdev_wporg_plugin_has_icon_filter',       5, 2 );
	add_filter( 'pdev_get_plugin_banner_url',     'pdev_wporg_plugin_banner_url_filter',     5, 2 );
	add_filter( 'pdev_get_plugin_icon_url',       'pdev_wporg_plugin_icon_url_filter',       5, 2 );

	add_action( 'save_post', 'pdev_save_plugin_callback' );
}

/**
 * Callback on the `save_post` hook to make sure we get the WP.org plugin stored.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return void
 */
function pdev_save_plugin_callback( $post_id ) {

	if ( pdev_is_plugin( $post_id ) && pdev_use_wporg_api() && pdev_is_wporg_plugin( $post_id ) )
		pdev_get_wporg_plugin( $post_id );
}

/**
 * Returns the `PDEV_WPORG_Plugin_Factory` instance.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function pdev_wporg_plugin_factory() {

	return PDEV_WPORG_Plugin_Factory::get_instance();
}

/**
 * Returns a `PDEV_WPORG_Plugin` object.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return object
 */
function pdev_get_wporg_plugin( $plugin_id ) {

	if ( ! pdev_wporg_plugin_exists( $plugin_id ) )
		pdev_register_wporg_plugin( $plugin_id );

	return pdev_wporg_plugin_factory()->get( $plugin_id );
}

/**
 * Registers a `PDEV_WPORG_Plugin` object.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_register_wporg_plugin( $plugin_id ) {

	pdev_wporg_plugin_factory()->register( $plugin_id );
}

/**
 * Unregisters a `PDEV_WPORG_Plugin` object.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_unregister_wporg_plugin( $plugin_id ) {

	pdev_wporg_plugin_factory()->unregister( $plugin_id );
}

/**
 * Checks if a `PDEV_WPORG_Plugin` object exists.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_wporg_plugin_exists( $plugin_id ) {

	return pdev_wporg_plugin_factory()->exists( $plugin_id);
}

/**
 * Filters the plugin version.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $version
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_version_filter( $version, $plugin_id ) {

	return ! $version ? pdev_get_wporg_plugin_version( $plugin_id ) : $version;
}

/**
 * Filters the plugin download URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_download_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_download_link( $plugin_id ) : $url;
}

/**
 * Filters the plugin repository URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_repo_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_svn_url( $plugin_id ) : $url;
}

/**
 * Filters the plugin translate URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_translate_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_translate_url( $plugin_id ) : $url;
}

/**
 * Filters the plugin support URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_support_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_support_url( $plugin_id ) : $url;
}

/**
 * Filters the plugin download count.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $count
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_download_count_filter( $count, $plugin_id ) {

	return '' === $count ? pdev_get_wporg_plugin_downloaded( $plugin_id ) : $count;
}

/**
 * Filters the plugin install count.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $count
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_install_count_filter( $count, $plugin_id ) {

	return '' === $count ? pdev_get_wporg_plugin_active_installs( $plugin_id ) : $count;
}

/**
 * Filters the plugin rating.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $rating
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_rating_filter( $rating, $plugin_id ) {

	if ( '' === $rating ) {

		$wporg_rating = pdev_get_wporg_plugin_rating( $plugin_id );

		if ( $wporg_rating )
			$rating = round( ( absint( $wporg_rating ) / 100 ) * 5, 2 );
	}

	return $rating;
}

/**
 * Filters the plugin rating count.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_rating_count_filter( $count, $plugin_id ) {

	return '' === $count ? pdev_get_wporg_plugin_num_ratings( $plugin_id ) : $count;
}

/**
 * Filters the has banner conditional.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $has_banner
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_wporg_plugin_has_banner_filter( $has_banner, $plugin_id ) {

	if ( ! $has_banner ) {
		$banners = pdev_get_wporg_plugin_banners( $plugin_id );

		return ! empty( $banners );
	}

	return $has_banner;
}

/**
 * Filters the plugin has icon conditional.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $has_icon
 * @param  int     $plugin_id
 * @return bool
 */
function pdev_wporg_plugin_has_icon_filter( $has_icon, $plugin_id ) {

	if ( ! $has_icon ) {
		$icons = pdev_get_wporg_plugin_icons( $plugin_id );

		return ! empty( $icons );
	}

	return $has_icon;
}

/**
 * Filters the plugin banner URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_banner_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_banner( $plugin_id ) : $url;
}

/**
 * Filters the plugin icon URL.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $plugin_id
 * @return string
 */
function pdev_wporg_plugin_icon_url_filter( $url, $plugin_id ) {

	return ! $url ? pdev_get_wporg_plugin_icon( $plugin_id ) : $url;
}
