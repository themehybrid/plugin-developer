<?php
/**
 * General template tags.
 *
 * @package    PluginDeveloper
 * @subpackage Template
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Conditional tag to check if viewing any Theme Designer plugin page.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function pdev_is_plugin_developer() {

	$is_pdev = pdev_is_plugin_archive() || pdev_is_single_plugin() || pdev_is_author() || pdev_is_category() || pdev_is_tag();

	return apply_filters( 'pdev_is_plugin_designer', $is_pdev );
}

/**
 * Checks if viewing one of the available archive pages.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function pdev_is_archive() {

	return apply_filters( 'pdev_is_archive', is_archive() && pdev_is_plugin_developer() );
}
