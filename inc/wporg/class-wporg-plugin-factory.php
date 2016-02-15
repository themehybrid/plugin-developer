<?php
/**
 * WordPress.org plugin integration plugin factory class.
 *
 * @package    PluginDeveloper
 * @subpackage WPORG
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * WPorg Plugin Factory class.
 *
 * @since  1.0.0
 * @access public
 */
class PDEV_WPORG_Plugin_Factory {

	/**
	 * Array of plugin objects.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $plugins = array();

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Register a new plugin object
	 *
	 * @see    PDEV_WPORG_Plugin::__construct()
	 * @since  1.0.0
	 * @access public
	 * @param  int     $plugin_id
	 * @return void
	 */
	public function register( $plugin_id ) {

		if ( ! $this->exists( $plugin_id ) ) {

			$plugin = new PDEV_WPORG_Plugin( $plugin_id );

			$this->plugins[ $plugin_id ] = $plugin;
		}
	}

	/**
	 * Unregisters a plugin object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $plugin_id
	 * @return void
	 */
	public function unregister( $plugin_id ) {

		if ( $this->exists( $plugin_id ) )
			unset( $this->plugins[ $plugin_id ] );
	}

	/**
	 * Checks if a plugin exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $plugin_id
	 * @return bool
	 */
	public function exists( $plugin_id ) {

		return isset( $this->plugins[ $plugin_id ] );
	}

	/**
	 * Gets a plugin object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $plugin_id
	 * @return object|bool
	 */
	public function get( $plugin_id ) {

		return $this->exists( $plugin_id ) ? $this->plugins[ $plugin_id ] : false;
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new self;

		return $instance;
	}
}
