<?php
/**
 * Plugin management admin screen.
 *
 * @package    PluginDeveloper
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Adds additional columns and tags to the plugins admin screen.
 *
 * @since  1.0.0
 * @access public
 */
final class PDEV_Manage_Plugins {

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-edit.php', array( $this, 'load' ) );

		// Hook the handler to the manage plugins load screen.
		add_action( 'pdev_load_manage_plugins', array( $this, 'handler' ), 0 );

		// Add the help tabs.
		add_action( 'pdev_load_manage_plugins', array( $this, 'add_help_tabs' ) );
	}

	/**
	 * Runs on the page load. Checks if we're viewing the plugin post type and adds
	 * the appropriate actions/filters for the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$screen      = get_current_screen();
		$plugin_type = pdev_get_plugin_post_type();

		// Bail if not on the plugins screen.
		if ( empty( $screen->post_type ) || $plugin_type !== $screen->post_type )
			return;

		// Custom action for loading the manage plugins screen.
		do_action( 'pdev_load_manage_plugins' );

		// Filter the posts query.
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		// Add custom views.
		add_filter( "views_edit-{$plugin_type}", array( $this, 'views' ) );

		// Category and tag table filters.
		add_action( 'restrict_manage_posts', array( $this, 'categories_dropdown' ) );
		add_action( 'restrict_manage_posts', array( $this, 'tags_dropdown' ) );

		// Custom columns on the edit portfolio items screen.
		add_filter( "manage_edit-{$plugin_type}_columns",          array( $this, 'columns' )              );
		add_filter( "manage_edit-{$plugin_type}_sortable_columns", array( $this, 'sortable_columns' )     );
		add_action( "manage_{$plugin_type}_posts_custom_column",   array( $this, 'custom_column' ), 10, 2 );

		// Print custom styles.
		add_action( 'admin_head', array( $this, 'print_styles' ) );

		// Filter post states (shown next to post title).
		add_filter( 'display_post_states', array( $this, 'display_post_states' ), 0, 2 );

		// Filter the row actions (shown below title).
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
	}

	/**
	 * Filter on the `pre_get_posts` hook to change what posts are loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $query
	 */
	public function pre_get_posts( $query ) {

		$new_vars = array();

		// If viewing sticky plugins.
		if ( isset( $_GET['sticky'] ) && 1 == $_GET['sticky'] ) {

			$query->set( 'post__in', pdev_get_sticky_plugins() );

		} else if ( isset( $_GET['orderby'] ) && 'download_count' === $_GET['orderby'] ) {

			$query->set( 'orderby',  'meta_value_num' );
			$query->set( 'meta_key', 'download_count' );

		} else if ( isset( $_GET['orderby'] ) && 'install_count' === $_GET['orderby'] ) {

			$query->set( 'orderby',  'meta_value_num' );
			$query->set( 'meta_key', 'install_count'  );

		} else if ( isset( $_GET['orderby'] ) && 'rating' === $_GET['orderby'] ) {

			$query->set( 'orderby',  'meta_value_num' );
			$query->set( 'meta_key', 'rating'         );

		// Default ordering by post title.
		} else if ( ! isset( $_GET['order'] ) && ! isset( $_GET['orderby'] ) ) {

			$query->set( 'order',   'ASC'        );
			$query->set( 'orderby', 'post_title' );
		}
	}

	/**
	 * Print styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $hook_suffix
	 * @return void
	 */
	public function print_styles() { ?>

		<style type="text/css">.column-icon img { max-width: 64px; height: auto; }
		@media only screen and (min-width: 783px) {
			.fixed .column-icon { width: 75px; }
			.fixed .column-downloads { width: 110px; }
			.fixed .column-rating { width: 120px; }
			.fixed .column-installs { width: 100px; }
			.fixed [class*="column-taxonomy-"] { width: 115px; }
		}</style>
	<?php }

	/**
	 * Add custom views (status list).
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $views
	 * @return array
	 */
	public function views( $views ) {

		$count = count( pdev_get_sticky_plugins() );

		if ( 0 < $count ) {
			$post_type = pdev_get_plugin_post_type();

			$noop = _n( 'Sticky <span class="count">(%s)</span>', 'Sticky <span class="count">(%s)</span>', $count, 'plugin-developer' );
			$text = sprintf( $noop, number_format_i18n( $count ) );

			$views['sticky'] = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'post_type' => $post_type, 'sticky' => 1 ), admin_url( 'edit.php' ) ), $text );
		}

		return $views;
	}

	/**
	 * Renders a categories dropdown below the table nav.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function categories_dropdown() {

		$this->terms_dropdown( pdev_get_category_taxonomy() );
	}

	/**
	 * Renders a tag dropdown below the table nav.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tags_dropdown() {

		$this->terms_dropdown( pdev_get_tag_taxonomy() );
	}

	/**
	 * Renders a terms dropdown based on the given taxonomy.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function terms_dropdown( $taxonomy ) {

		wp_dropdown_categories(
			array(
				'show_option_all' => false,
				'show_option_none'    => get_taxonomy( $taxonomy )->labels->all_items,
				'option_none_value'  => '',
				'orderby'            => 'name',
				'order'              => 'ASC',
				'show_count'         => true,
				'selected'           => isset( $_GET[ $taxonomy ] ) ? esc_attr( $_GET[ $taxonomy ] ) : '',
				'hierarchical'       => true,
				'name'               => $taxonomy,
				'id'                 => '',
				'class'              => 'postform',
				'taxonomy'           => $taxonomy,
				'hide_if_empty'      => true,
				'value_field'	     => 'slug',
			)
		);
	}

	/**
	 * Sets up custom columns on the plugins edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $columns
	 * @return array
	 */
	public function columns( $columns ) {

		$new_columns = array( 'cb'    => $columns['cb'] );

		$new_columns['title'] = __( 'Plugin', 'plugin-developer' );

		if ( current_theme_supports( 'post-thumbnails' ) )
			$new_columns['icon'] = __( 'Icon', 'plugin-developer' );

		$columns = array_merge( $new_columns, $columns );

		//$columns['title'] = $new_columns['title'];

		if ( pdev_use_wporg_api() ) {
			$columns['downloads'] = __( 'Downloads', 'plugin-developer' );
			$columns['installs']  = __( 'Installs',  'plugin-developer' );
			$columns['rating']    = __( 'Rating',    'plugin-developer' );
		}

		if ( isset( $columns['date'] ) ) {

			$date = $columns['date'];
			unset( $columns['date'] );

			$columns['date'] = $date;
		}

		return $columns;
	}

	/**
	 * Adds sortable columns.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $columns
	 * @return array
	 */
	public function sortable_columns( $columns ) {

		// Get the order.
		$order = strtolower( get_query_var( 'order' ) );

		// Fix for the default ordering, which is by post title. Make sure 'desc' is the default sort option.
		if ( 'post_title' === get_query_var( 'orderby' ) && 'asc' === $order )
			$columns['title'] = array( 'title', true );

		if ( pdev_use_wporg_api() ) {

			// Need variables b/c of https://core.trac.wordpress.org/ticket/34479
			$meta_key = get_query_var( 'meta_key' );

			$d_order = 'download_count' === $meta_key && 'desc' === $order ? false : true;
			$r_order = 'rating'         === $meta_key && 'desc' === $order ? false : true;
			$i_order = 'installs'       === $meta_key && 'desc' === $order ? false : true;

			$columns['downloads'] = array( 'download_count', $d_order );
			$columns['rating']    = array( 'rating',         $r_order );
			$columns['installs']  = array( 'install_count',  $i_order );
		}

		return $columns;
	}

	/**
	 * Displays the content of custom plugin columns on the edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $column
	 * @param  int     $post_id
	 * @return void
	 */
	public function custom_column( $column, $post_id ) {

		if ( 'icon' === $column ) {

			$icon = pdev_get_plugin_icon( $post_id, array( 128, 128 ) );

			if ( ! $icon )
				$icon = sprintf( '<img src="%s" alt="" />', pdev_plugin()->img_uri . 'icon-128x128.png' );

			echo $icon;

		} else if ( 'downloads' === $column ) {

			$count = pdev_get_plugin_download_count( $post_id );

			$count ? pdev_plugin_download_count( $post_id ) : print( '&mdash;' );

		} else if ( 'installs' === $column ) {

			$count = pdev_get_wporg_plugin_active_installs( $post_id );

			$count ? pdev_plugin_install_count( $post_id ) : print( '&mdash;' );

		} else if ( 'rating' === $column ) {

			$rating = pdev_get_plugin_rating( $post_id );
			$number = pdev_get_plugin_rating_count( $post_id );

			wp_star_rating( array( 'type' => 'rating', 'rating' => floatval( $rating ), 'number' => absint( $number ) ) );
		}
	}

	/**
	 * Filter for the `post_states` hook.  We're going to add the plugin type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $states
	 * @param  object  $post
	 */
	public function display_post_states( $states, $post ) {

		if ( pdev_is_plugin_sticky( $post->ID ) )
			$states['sticky'] = esc_html__( 'Sticky', 'plugin-developer' );

		return $states;
	}

	/**
	 * Custom row actions below the post title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $actions
	 * @param  object  $post
	 * @return array
	 */
	function row_actions( $actions, $post ) {

		$post_type_object = get_post_type_object( pdev_get_plugin_post_type() );
		$plugin_id         = pdev_get_plugin_id( $post->ID );

		if ( 'trash' === get_post_status( $plugin_id ) || ! current_user_can( $post_type_object->cap->publish_posts ) )
			return $actions;

		$current_url = remove_query_arg( array( 'plugin_id', 'pdev_plugin_notice' ) );

		// Build text.
		$text = pdev_is_plugin_sticky( $plugin_id ) ? esc_html__( 'Unstick', 'plugin-developer' ) : esc_html__( 'Stick', 'plugin-developer' );

		// Build toggle URL.
		$url = add_query_arg( array( 'plugin_id' => $plugin_id, 'action' => 'pdev_toggle_sticky' ), $current_url );
		$url = wp_nonce_url( $url, "pdev_toggle_sticky_{$plugin_id}" );

		// Add sticky action.
		$actions['sticky'] = sprintf( '<a href="%s" class="%s">%s</a>', esc_url( $url ), 'sticky', esc_html( $text ) );

		// Move view action to the end.
		if ( isset( $actions['view'] ) ) {
			$view_action = $actions['view'];
			unset( $actions['view'] );

			$actions['view'] = $view_action;
		}

		return $actions;
	}

	/**
	 * Callback function for handling post status changes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function handler() {

		// Checks if the sticky toggle link was clicked.
		if ( isset( $_GET['action'] ) && 'pdev_toggle_sticky' === $_GET['action'] && isset( $_GET['plugin_id'] ) ) {

			$plugin_id = absint( pdev_get_plugin_id( $_GET['plugin_id'] ) );

			// Verify the nonce.
			check_admin_referer( "pdev_toggle_sticky_{$plugin_id}" );

			if ( pdev_is_plugin_sticky( $plugin_id ) )
				pdev_remove_sticky_plugin( $plugin_id );
			else
				pdev_add_sticky_plugin( $plugin_id );

			// Redirect to correct admin page.
			$redirect = add_query_arg( array( 'updated' => 1 ), remove_query_arg( array( 'action', 'plugin_id', '_wpnonce' ) ) );
			wp_safe_redirect( esc_url_raw( $redirect ) );

			// Always exit for good measure.
			exit();
		}

		return;
	}

	/**
	 * Adds custom help tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		$screen = get_current_screen();

		// Overview help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'overview',
				'title'    => esc_html__( 'Overview', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_overview' )
			)
		);

		// Screen content help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'screen_content',
				'title'    => esc_html__( 'Screen Content', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_screen_content' )
			)
		);

		// Available actions help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'available_actions',
				'title'    => esc_html__( 'Available Actions', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_available_actions' )
			)
		);

		// Set the help sidebar.
		$screen->set_help_sidebar( pdev_get_help_sidebar_text() );
	}

	/**
	 * Displays the overview help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_overview() { ?>

		<p>
			<?php esc_html_e( 'This screen provides access to all of your plugin projects. You can customize the display of this screen to suit your workflow.', 'plugin-developer' ); ?>
		</p>
	<?php }

	/**
	 * Displays the screen content help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_screen_content() { ?>

		<p>
			<?php esc_html_e( "You can customize the display of this screen's contents in a number of ways:", 'plugin-developer' ); ?>
		</p>

		<ul>
			<li><?php esc_html_e( 'You can hide/display columns based on your needs and decide how many plugins to list per screen using the Screen Options tab.', 'plugin-developer' ); ?></li>
			<li><?php esc_html_e( 'You can filter the list of plugins by post status using the text links in the upper left to show All, Published, Draft, or Trashed plugins. The default view is to show all plugins.', 'plugin-developer' ); ?></li>
			<li><?php esc_html_e( 'You can view plugins in a simple title list or with an excerpt. Choose the view you prefer by clicking on the icons at the top of the list on the right.', 'plugin-developer' ); ?></li>
			<li><?php esc_html_e( 'You can refine the list to show only plugins with a specific category, with a specific tag, or from a specific month by using the dropdown menus above the plugins list. Click the Filter button after making your selection. You also can refine the list by clicking on the plugin author or category in the posts list.', 'plugin-developer' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the available actions help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_available_actions() { ?>

		<p>
			<?php esc_html_e( 'Hovering over a row in the plugins list will display action links that allow you to manage your plugin. You can perform the following actions:', 'plugin-developer' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>Edit</strong> takes you to the editing screen for that plugin. You can also reach that screen by clicking on the plugin title.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Quick Edit</strong> provides inline access to the metadata of your plugin, allowing you to update plugin details without leaving this screen.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Trash</strong> removes your plugin from this list and places it in the trash, from which you can permanently delete it.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Stick</strong> puts your plugin in the list of "sticky" plugins, which are shown first on the plugin archive page.', 'plugin-developer' ); ?></li>
			<li><?php _e( "<strong>Preview</strong> will show you what your draft plugin will look like if you publish it. View will take you to your live site to view the plugin. Which link is available depends on your plugin's status.", 'plugin-developer' ); ?></li>
		</ul>
	<?php }

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

PDEV_Manage_Plugins::get_instance();
