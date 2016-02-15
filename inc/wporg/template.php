<?php
/**
 * WordPress.org plugin integration template tags.
 *
 * @package    PluginDeveloper
 * @subpackage WPORG
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Conditional check to see if the plugin is a WordPress.org plugin.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_is_wporg_plugin( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$plugin    = pdev_get_wporg_plugin( $plugin_id );

	return apply_filters( 'pdev_is_wporg_plugin', is_object( $plugin ), $plugin_id );
}

/**
 * Prints the plugin SVN repo URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_svn_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_svn_url( $plugin_id ) );
}

/**
 * Returns the plugin SVN repo URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_svn_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$slug      = pdev_get_plugin_wporg_slug( $plugin_id );
	$url       = $slug ? "https://plugins.svn.wordpress.org/{$slug}" : '';

	return apply_filters( 'pdev_get_wporg_plugin_svn_url', $url, $plugin_id );
}

/**
 * Prints the plugin translate URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_translate_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_translate_url( $plugin_id ) );
}

/**
 * Returns the plugin translate URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_translate_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$slug      = pdev_get_plugin_wporg_slug( $plugin_id );
	$url       = $slug ? "https://translate.wordpress.org/projects/wp-plugins/{$slug}" : '';

	return apply_filters( 'pdev_get_wporg_plugin_translate_url', $url, $plugin_id );
}

/**
 * Prints the plugin support URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_support_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_support_url( $plugin_id ) );
}

/**
 * Returns the plugin support URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_support_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$slug     = pdev_get_plugin_wporg_slug( $plugin_id );
	$url      = $slug ? "https://wordpress.org/support/plugin/{$slug}" : '';

	return apply_filters( 'pdev_get_wporg_plugin_support_url', $url, $plugin_id );
}

/**
 * Prints the plugin reviews URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_reviews_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_reviews_url( $plugin_id ) );
}

/**
 * Returns the plugin reviews URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_reviews_url( $plugin_id = 0 ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );
	$slug      = pdev_get_plugin_wporg_slug( $plugin_id );
	$url       = $slug ? "https://wordpress.org/support/view/plugin-reviews/{$slug}" : '';

	return apply_filters( 'pdev_get_wporg_plugin_reviews_url', $url, $plugin_id );
}

/**
 * Returns a property from the WordPress.org plugin object.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $property
 * @return mixed
 */
function pdev_get_wporg_plugin_property( $plugin_id = 0, $property ) {

	$plugin_id = pdev_get_plugin_id( $plugin_id );

	$plugin_object = pdev_get_wporg_plugin( $plugin_id );

	$data = isset( $plugin_object->$property ) ? $plugin_object->$property : '';

	return apply_filters( "pdev_get_wporg_plugin_{$property}", $data, $plugin_id );
}

/**
 * Prints the plugin name.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_name( $plugin_id = 0 ) {

	echo esc_html( pdev_get_wporg_plugin_name( $plugin_id ) );
}

/**
 * Returns the plugin name.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_name( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'name' );
}

/**
 * Prints the plugin version.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_version( $plugin_id = 0 ) {

	echo esc_html( pdev_get_wporg_plugin_version( $plugin_id ) );
}

/**
 * Returns the plugin version.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_version( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'version' );
}

/**
 * Prints the plugin author (WordPress.org username). Can be linked to Author URI.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_author( $plugin_id = 0 ) {

	echo pdev_get_wporg_plugin_author( $plugin_id );
}

/**
 * Returns the plugin author's WordPress.org username.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_author( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'author' );
}

/**
 * Returns a list of the plugin contributors listed on WordPress.org.  An array in the form
 * of `array( $username => array( 'profile' => $profile_url, 'avatar' => $avatar_url ) )` is
 * returned.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_wporg_plugin_contributors( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'contributors' );
}

/**
 * Returns a specific plugin contributor's array from the contributor's list.  The array
 * returned is in the form of `array( 'profile' => $profile_url, 'avatar' => $avatar_url )`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $username
 * @return array
 */
function pdev_get_wporg_plugin_contributor( $plugin_id = 0, $username ) {

	$contributors = pdev_get_wporg_plugin_contributors( $plugin_id );

	return isset( $contributors[ $username ] ) ? $contributors[ $username ] : false;
}

/**
 * Prints a specific plugin contributor's profile URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $username
 * @return void
 */
function pdev_wporg_plugin_contributor_profile( $plugin_id = 0, $username ) {

	echo esc_url( pdev_get_wporg_plugin_contributor_profile( $plugin_id, $username ) );
}

/**
 * Returns a specific plugin contributor's profile URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $username
 * @return string
 */
function pdev_get_wporg_plugin_contributor_profile( $plugin_id = 0, $username ) {

	$contributor = pdev_get_wporg_plugin_contributor( $plugin_id, $username );

	return $contributor['profile'];
}

/**
 * Returns a specific plugin contributor's avatar URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $username
 * @return void
 */
function pdev_wporg_plugin_contributor_avatar( $plugin_id = 0, $username ) {

	echo esc_url( pdev_get_wporg_plugin_contributor_avatar( $plugin_id, $username ) );
}

/**
 * Returns a specific plugin contributor's avatar URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $username
 * @return string
 */
function pdev_get_wporg_plugin_contributor_avatar( $plugin_id = 0, $username ) {

	$contributor = pdev_get_wporg_plugin_contributor( $plugin_id, $username );

	return $contributor['avatar'];
}

/**
 * Prints the plugin author's username.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_author_username( $plugin_id = 0 ) {

	echo esc_html( pdev_get_wporg_plugin_author_username( $plugin_id ) );
}

/**
 * Returns the plugin author's username.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_author_username( $plugin_id = 0 ) {

	$url          = pdev_get_wporg_plugin_author_profile( $plugin_id );
	$contributors = pdev_get_wporg_plugin_contributors( $plugin_id );

	$match = preg_match( "/profiles.wordpress.org\/(.*?)/i", $url, $matches );

	if ( ! empty( $matches[ 0 ] ) && in_array( trim( $matches[ 0 ], '/' ), $contributors ) )
		return trim( $matches[ 0 ], '/' );

	return false;
}

/**
 * Prints the plugin author's profile URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_author_profile( $plugin_id = 0 ) {

	echo pdev_get_wporg_plugin_author_profile( $plugin_id );
}

/**
 * Returns the plugin author's profile URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_author_profile( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'author_profile' );
}

/**
 * Prints the plugin author's plugins URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_author_plugins_url( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_author_plugins_url( $plugin_id ) );
}

/**
 * Returns the plugin author's plugins URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_author_plugins_url( $plugin_id = 0 ) {

	$author = pdev_get_wporg_plugin_author_username( $plugin_id );

	return $author ? "https://profiles.wordpress.org/{$author}/#content-plugins" : '';
}

/**
 * Prints the plugin rating.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_rating( $plugin_id = 0 ) {

	echo absint( pdev_get_wporg_plugin_rating( $plugin_id ) );
}

/**
 * Returns the plugin rating.  This is the total ratings count.  To get the
 * actual rating, divide by 5.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_wporg_plugin_rating( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'rating' );
}

/**
 * Prints the number of plugin ratings.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_num_ratings( $plugin_id = 0 ) {

	echo esc_html( pdev_get_wporg_plugin_num_ratings( $plugin_id ) );
}

/**
 * Returns the number of ratings a plugin has.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_wporg_plugin_num_ratings( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'num_ratings' );
}

/**
 * Prints the plugin download count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_downloaded( $plugin_id = 0 ) {

	echo number_format_i18n( pdev_get_wporg_plugin_downloaded( $plugin_id ) );
}

/**
 * Returns the plugin download count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_wporg_plugin_downloaded( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'downloaded' );
}

/**
 * Prints the plugin install count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_active_installs( $plugin_id = 0 ) {

	echo number_format_i18n( pdev_get_wporg_plugin_active_installs( $plugin_id ) );
}

/**
 * Returns the plugin install count.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return int
 */
function pdev_get_wporg_plugin_active_installs( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'active_installs' );
}

/**
 * Prints the plugin's last updated date.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_last_updated( $plugin_id = 0 ) {

	echo mysql2date( get_option( 'date_format' ), pdev_get_wporg_plugin_last_updated( $plugin_id ), true );
}

/**
 * Returns the plugin last updated date in the form of `YYYY-MM-DD 00:00am/pm GMT`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_last_updated( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'last_updated' );
}

/**
 * Prints the plugin's added date.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_added( $plugin_id = 0 ) {

	echo mysql2date( get_option( 'date_format' ), pdev_get_wporg_plugin_added( $plugin_id ), true );
}

/**
 * Returns the plugin added date in the form of `YYYY-MM-DD`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_added( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'added' );
}

/**
 * Prints the WP version required.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_requires( $plugin_id = 0 ) {

	echo esc_html( pdev_get_wporg_plugin_requires( $plugin_id ) );
}

/**
 * Returns the required WP version (e.g., 4.1.2).
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_requires( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'requires' );
}

/**
 * Prints the plugin WordPress.org page URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_homepage( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_homepage( $plugin_id ) );
}

/**
 * Returns the plugin WordPress.org page URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_homepage( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'homepage' );
}

/**
 * Prints the plugin's description.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_description( $plugin_id = 0 ) {

	echo pdev_get_wporg_plugin_description( $plugin_id );
}

/**
 * Returns the plugin's description.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_description( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'description' );
}

/**
 * Prints the plugin's short description.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_short_description( $plugin_id = 0 ) {

	echo pdev_get_wporg_plugin_short_description( $plugin_id );
}

/**
 * Prints the plugin's short description.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_short_description( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'short_description' );
}

/**
 * Returns an array of sections.  Sections are the `readme.txt` sections.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_wporg_plugin_sections( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'sections' );
}

/**
 * Prints a specific section from the plugin's `readme.txt`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $section
 * @return void
 */
function pdev_wporg_plugin_section( $plugin_id = 0, $section = 'description' ) {

	echo pdev_get_wporg_plugin_section( $plugin_id, $section );
}

/**
 * Prints a specific section from the plugin's `readme.txt`.  Available sections include:
 * 'description', 'installation', 'screenshotss', 'changelog', 'faq'.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $section
 * @return string
 */
function pdev_get_wporg_plugin_section( $plugin_id = 0, $ection = 'description' ) {

	$sections = pdev_get_wporg_plugin_sections( $plugin_id );

	return $sections && isset( $sections[ $section ] ) ? $sections[ $section ] : '';
}

/**
 * Prints the plugin ZIP file URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_download_link( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_download_link( $plugin_id ) );
}

/**
 * Returns the plugin ZIP file URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_download_link( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'download_link' );
}

/**
 * Prints the plugin ZIP file URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return void
 */
function pdev_wporg_plugin_donate_link( $plugin_id = 0 ) {

	echo esc_url( pdev_get_wporg_plugin_donate_link( $plugin_id ) );
}

/**
 * Returns the plugin ZIP file URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return string
 */
function pdev_get_wporg_plugin_donate_link( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'donate_link' );
}

/**
 * Returns an array of plugin tags in the form of tag (key) and human-readable name (value)
 * (e.g., `array( 'custom-background' => 'Custom Background' )`).
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_wporg_plugin_tags( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'tags' );
}

/**
 * Returns an array of the plugin's banners.  The banners are in an array in the form of:
 * `array( 'high' => $url, 'low' => $url )`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_wporg_plugin_banners( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'banners' );
}

/**
 * Prints a specific plugin banner's URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $handle
 * @return void
 */
function pdev_wporg_plugin_banner( $plugin_id = 0, $handle = 'high' ) {

	echo esc_url( pdev_get_wporg_plugin_banner( $plugin_id, $handle ) );
}

/**
 * Returns a specific plugin banner's URL.  Available handles are 'high' and 'low'.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $handle
 * @return string
 */
function pdev_get_wporg_plugin_banner( $plugin_id = 0, $handle = 'low' ) {

	$banners = pdev_get_wporg_plugin_banners( $plugin_id );

	return $banners && isset( $banners[ $handle ] ) ? $banner[ $handle ] : '';
}

/**
 * Returns an array of the plugin's icons.  The iconss are in an array in the form of:
 * `array( '2x' => $url, '1x' => $url )`.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @return array
 */
function pdev_get_wporg_plugin_icons( $plugin_id = 0 ) {

	return pdev_get_wporg_plugin_property( $plugin_id, 'icons' );
}

/**
 * Prints a specific plugin icon's URL.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $handle
 * @return void
 */
function pdev_wporg_plugin_icon( $plugin_id = 0, $handle = '1x' ) {

	echo esc_url( pdev_get_wporg_plugin_icon( $plugin_id, $handle ) );
}

/**
 * Prints a specific plugin icon's URL.  Available handles are '2x' and '1x'.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $plugin_id
 * @param  string  $handle
 * @return void
 */
function pdev_get_wporg_plugin_icon( $plugin_id = 0, $handle = '2x' ) {

	$icons = pdev_get_wporg_plugin_icons( $plugin_id );

	return $icons && isset( $icons[ $handle ] ) ? $icon[ $handle ] : '';
}
