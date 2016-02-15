<?php
/**
 * Registers custom shortcodes.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register shortcodes.
add_action( 'init', 'pdev_register_shortcodes' );

/**
 * Register shortcodes for the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_register_shortcodes() {

	// Plugin shortcodes.
	add_shortcode( 'pdev_plugin_title',          'pdev_plugin_title_shortcode'          );
	add_shortcode( 'pdev_plugin_author',         'pdev_plugin_author_shortcode'         );
	add_shortcode( 'pdev_plugin_author_link',    'pdev_plugin_author_link_shortcode'    );
	add_shortcode( 'pdev_plugin_version',        'pdev_plugin_version_shortcode'        );
	add_shortcode( 'pdev_plugin_download_link',  'pdev_plugin_download_link_shortcode'  );
	add_shortcode( 'pdev_plugin_repo_link',      'pdev_plugin_repo_link_shortcode'      );
	add_shortcode( 'pdev_plugin_purchase_link',  'pdev_plugin_purchase_link_shortcode'  );
	add_shortcode( 'pdev_plugin_support_link',   'pdev_plugin_support_link_shortcode'   );
	add_shortcode( 'pdev_plugin_translate_link', 'pdev_plugin_translate_link_shortcode' );
	add_shortcode( 'pdev_plugin_docs_link',      'pdev_plugin_docs_link_shortcode'      );
}

/**
 * Callback function for the `[pdev_plugin_title]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_title_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts( array( 'plugin_id' => pdev_get_plugin_id() ), $attr, $shortcode );

	return get_the_title( $attr['plugin_id'] );
}

/**
 * Callback function for the `[pdev_plugin_author]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_author_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts( array( 'plugin_id' => pdev_get_plugin_id() ), $attr, $shortcode );

	return pdev_get_plugin_author( $attr['plugin_id'] );
}

/**
 * Callback function for the `[pdev_plugin_author_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_author_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts( array( 'plugin_id' => pdev_get_plugin_id() ), $attr, $shortcode );

	return pdev_get_plugin_author_link( $attr['plugin_id'] );
}

/**
 * Callback function for the `[pdev_plugin_version]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_version_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts( array( 'plugin_id' => pdev_get_plugin_id() ), $attr, $shortcode );

	return pdev_get_plugin_version( $attr['plugin_id'] );
}

/**
 * Callback function for the `[pdev_plugin_download_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_download_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Download', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_download_link( $attr );
}

/**
 * Callback function for the `[pdev_plugin_repo_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_repo_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Repository', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_repo_link( $attr );
}

/**
 * Callback function for the `[pdev_plugin_purchase_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_purchase_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Purchase', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_purchase_link( $attr );
}

/**
 * Callback function for the `[pdev_plugin_support_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_support_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Support', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_support_link( $attr );
}

/**
 * Callback function for the `[pdev_plugin_translate_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_translate_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Translations', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_translate_link( $attr );
}

/**
 * Callback function for the `[pdev_plugin_docs_link]` shortcode.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $content
 * @param  string $shortcode
 * @return string
 */
function pdev_plugin_docs_link_shortcode( $attr = array(), $content = null, $shortcode = '' ) {

	$attr = shortcode_atts(
		array( 'plugin_id' => pdev_get_plugin_id(), 'text' => __( 'Docs', 'plugin-developer' ) ),
		$attr, $shortcode
	);

	return pdev_get_plugin_docs_link( $attr );
}
