<?php
/**
 * WordPress.org plugin integration plugin object class.
 *
 * @package    PluginDeveloper
 * @subpackage WPORG
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Creates new WPorg plugin objects.
 *
 * @since  1.0.0
 * @access public
 */
class PDEV_WPORG_Plugin {

	/**
	 * Arguments for creating the plugin object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $args = array();

	/* ====== Magic Methods ====== */

	/**
	 * Magic method for getting plugin object properties.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return mixed
	 */
	public function __get( $property ) {

		return isset( $this->$property ) ? $this->args[ $property ] : null;
	}

	/**
	 * Magic method for setting plugin object properties.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @param  mixed   $value
	 * @return void
	 */
	public function __set( $property, $value ) {

		if ( isset( $this->$property ) )
			$this->args[ $property ] = $value;
	}

	/**
	 * Magic method for checking if a plugin property is set.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return bool
	 */
	public function __isset( $property ) {

		return isset( $this->args[ $property ] );
	}

	/**
	 * Don't allow properties to be unset.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $property
	 * @return void
	 */
	public function __unset( $property ) {}

	/**
	 * Magic method to use in case someone tries to output the plugin object as a string.
	 * We'll just return the plugin name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Register a new plugin object
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $slug
	 * @return void
	 */
	public function __construct( $plugin_id ) {

		// Get the plugin's WordPress.org slug.
		$slug = pdev_get_plugin_wporg_slug( $plugin_id );

		// Bail if there's no slug.
		if ( ! $slug )
			return;

		// Get the transient based on the plugin slug.
		$api = get_transient( "pdev_wporg_{$slug}" );

		// If there's no transient, we need to get the data from the WP Plugins API.
		if ( ! $api ) {

			// If `plugins_api()` isn't available, load the file that holds the function
			if ( ! function_exists( 'plugins_api' ) && file_exists( trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php' ) )
				require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin-install.php' );

			// Make sure the function exists.
			if ( function_exists( 'plugins_api' ) ) {

				$fields = array(
					'short_description' => true,
					'description'       => true,
					'sections'          => true,
					'tested'            => true,
					'requires'          => true,
					'rating'            => true,
					'ratings'           => false,
					'downloaded'        => true,
					'downloadlink'      => true,
					'last_updated'      => true,
					'added'             => true,
					'tags'              => true,
					'compatibility'     => false,
					'homepage'          => true,
					'versions'          => false,
					'reviews'           => false,
					'banners'           => true,
					'icons'             => true,
					'active_installs'   => true,
					'group'             => false,
					'contributors'      => true
				);

				// @link https://developer.wordpress.org/reference/functions/plugins_api/
				$fields = apply_filters( 'pdev_wporg_plugins_api_fields', $fields, $plugin_id, $slug );

				// Get the plugin info from WordPress.org.
				$api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => $fields ) );

				// If no error, let's roll.
				if ( ! is_wp_error( $api ) ) {

					// If this is an array, let's make it an object.
					if ( is_array( $api ) )
						$api = (object) $api;

					// Only proceed if we have an object.
					if ( is_object( $api ) ) {

						// Set the transient with the new data.
						set_transient( "pdev_wporg_{$slug}", $api, pdev_get_wporg_transient_expiration() );

						// Back up download count as post meta.
						if ( isset( $api->downloaded ) )
							pdev_set_plugin_meta( $plugin_id, 'download_count', absint( $api->downloaded ) );

						// Back up install count as post meta.
						if ( isset( $api->active_installs ) )
							pdev_set_plugin_meta( $plugin_id, 'install_count', absint( $api->active_installs ) );

						// Back up ratings as post meta.
						if ( isset( $api->rating ) && isset( $api->num_ratings ) ) {

							$rating = round( ( absint( $api->rating ) / 100 ) * 5, 2 );

							pdev_set_plugin_meta( $plugin_id, 'rating', $rating );
							pdev_set_plugin_meta( $plugin_id, 'rating_count', absint( $api->num_ratings ) );
						}
					}
				}
			}
		}

		// If we have data, let's assign its keys to our plugin object properties.
		if ( $api && is_object( $api ) && ! is_wp_error( $api ) ) {

			foreach ( $api as $key => $value )
				$this->args[ $key ] = $value;
		}
	}
}
