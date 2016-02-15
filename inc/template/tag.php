<?php
/**
 * Tag template tags.
 *
 * @package    PluginDeveloper
 * @subpackage Template
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Conditional tag to check if viewing a tag archive.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed  $term
 * @return bool
 */
function pdev_is_tag( $term = '' ) {

	return apply_filters( 'pdev_is_tag', is_tax( pdev_get_tag_taxonomy(), $term ) );
}

