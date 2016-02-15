<?php
/**
 * Plugin Name: Plugin Developer
 * Plugin URI:  http://themehybrid.com/plugins/plugin-developer
 * Description: A plugin for plugin authors to manage their plugin portfolios.
 * Version:     1.0.0-dev
 * Author:      Justin Tadlock
 * Author URI:  http://themehybrid.com
 * Text Domain: plugin-developer
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   PluginDeveloper
 * @version   1.0.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2015-2016, Justin Tadlock
 * @link      http://themehybrid.com/plugins/plugin-developer
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Singleton class that sets up and initializes the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
final class PDEV_Plugin {

	/**
	 * Directory path to the plugin folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_path = '';

	/**
	 * Directory URI to the plugin folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_uri = '';

	/**
	 * JavaScript directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $js_uri = '';

	/**
	 * CSS directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $css_uri = '';

	/**
	 * Images directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $img_uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __toString() {
		return 'plugin-developer';
	}

	/**
	 * Magic method to keep the object from being cloned.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'plugin-developer' ), '1.0.0' );
	}

	/**
	 * Magic method to keep the object from being unserialized.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'plugin-developer' ), '1.0.0' );
	}

	/**
	 * Magic method to prevent a fatal error when calling a method that doesn't exist.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __call( $method = '', $args = array() ) {
		_doing_it_wrong( "PDEV_Plugin::{$method}", __( 'Method does not exist.', 'plugin-developer' ), '1.0.0' );
		unset( $method, $args );
		return null;
	}

	/**
	 * Initial plugin setup.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->dir_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );

		$this->js_uri  = trailingslashit( $this->dir_uri . 'js'  );
		$this->css_uri = trailingslashit( $this->dir_uri . 'css' );
		$this->img_uri = trailingslashit( $this->dir_uri . 'img' );
	}

	/**
	 * Loads include and admin files for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function includes() {

		// Load core files.
		require_once( $this->dir_path . 'inc/core/filters.php'    );
		require_once( $this->dir_path . 'inc/core/options.php'    );
		require_once( $this->dir_path . 'inc/core/meta.php'       );
		require_once( $this->dir_path . 'inc/core/rewrite.php'    );
		require_once( $this->dir_path . 'inc/core/post-types.php' );
		require_once( $this->dir_path . 'inc/core/sticky.php'     );
		require_once( $this->dir_path . 'inc/core/shortcodes.php' );
		require_once( $this->dir_path . 'inc/core/taxonomies.php' );

		// Load plugin files.
		require_once( $this->dir_path . 'inc/template/author.php'   );
		require_once( $this->dir_path . 'inc/template/category.php' );
		require_once( $this->dir_path . 'inc/template/general.php'  );
		require_once( $this->dir_path . 'inc/template/plugin.php'   );
		require_once( $this->dir_path . 'inc/template/tag.php'      );

		// Load WordPress.org integration files.
		require_once( $this->dir_path . 'inc/wporg/class-wporg-plugin.php'         );
		require_once( $this->dir_path . 'inc/wporg/class-wporg-plugin-factory.php' );
		require_once( $this->dir_path . 'inc/wporg/functions.php'                 );
		require_once( $this->dir_path . 'inc/wporg/template.php'                  );

		// Load admin files.
		if ( is_admin() ) {
			require_once( $this->dir_path . 'admin/functions-admin.php'     );
			require_once( $this->dir_path . 'admin/class-manage-plugins.php' );
			require_once( $this->dir_path . 'admin/class-plugin-edit.php'    );
			require_once( $this->dir_path . 'admin/class-settings.php'      );
		}
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		// Remove update notifications for this plugin.
		add_filter( 'site_transient_update_plugins', array( $this, 'update_notifications' )        );
		add_filter( 'http_request_args',             array( $this, 'http_request_args'    ), 10, 2 );

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'plugin-developer', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
	}

	/**
	 * Overwrites the plugin update notifications to remove this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $notifications
	 * @return array
	 */
	public function update_notifications( $notifications ) {

		$basename = plugin_basename( __FILE__ );

		if ( isset( $notifications->response[ $basename ] ) )
			unset( $notifications->response[ $basename ] );

		return $notifications;
	}

	/**
	 * Blocks plugin from getting updated via WordPress.org if there's one with the same
	 * name hosted there.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $request_args
	 * @param  array  $url
	 * @return array
	 */
	public function http_request_args( $request_args, $url ) {

		if ( 0 === strpos( $url, 'https://api.wordpress.org/plugins/update-check' ) ) {

			$basename = plugin_basename( __FILE__ );
			$plugins  = json_decode( $request_args['body']['plugins'], true );

			if ( isset( $plugins['plugins'][ $basename ] ) ) {
				unset( $plugins['plugins'][ $basename ] );

				$request_args['body']['plugins'] = json_encode( $plugins );
			}
		}

		return $request_args;
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global $wpdb
	 * @return void
	 */
	public function activation() {

		// Get the administrator role.
		$role = get_role( 'administrator' );

		// If the administrator role exists, add required capabilities for the plugin.
		if ( ! is_null( $role ) ) {

			// Taxonomy caps.
			$role->add_cap( 'manage_plugin_categories' );
			$role->add_cap( 'manage_plugin_tags'       );

			// Post type caps.
			$role->add_cap( 'create_plugin_projects'           );
			$role->add_cap( 'edit_plugin_projects'             );
			$role->add_cap( 'edit_others_plugin_projects'      );
			$role->add_cap( 'publish_plugin_projects'          );
			$role->add_cap( 'read_private_plugin_projects'     );
			$role->add_cap( 'delete_plugin_projects'           );
			$role->add_cap( 'delete_private_plugin_projects'   );
			$role->add_cap( 'delete_published_plugin_projects' );
			$role->add_cap( 'delete_others_plugin_projects'    );
			$role->add_cap( 'edit_private_plugin_projects'     );
			$role->add_cap( 'edit_published_plugin_projects'   );
		}
	}
}

/**
 * Gets the instance of the `PDEV_Plugin` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function pdev_plugin() {
	return PDEV_Plugin::get_instance();
}

// Let's do this thang!
pdev_plugin();
