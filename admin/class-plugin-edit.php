<?php
/**
 * Edit/New plugin admin screen.
 *
 * @package    PluginDeveloper
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Project edit screen functionality.
 *
 * @since  1.0.0
 * @access public
 */
final class PDEV_Plugin_Edit {

	/**
	 * Holds the fields manager instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	public $manager = '';

	/**
	 * Sets up the needed actions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );

		// Add the help tabs.
		add_action( 'pdev_load_plugin_edit', array( $this, 'add_help_tabs' ) );
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

		$screen     = get_current_screen();
		$plugin_type = pdev_get_plugin_post_type();

		// Bail if not on the plugins screen.
		if ( empty( $screen->post_type ) || $plugin_type !== $screen->post_type )
			return;

		// Custom action for loading the edit plugin screen.
		do_action( 'pdev_load_plugin_edit' );

		// Load the fields manager.
		require_once( pdev_plugin()->dir_path . 'admin/fields-manager/class-manager.php' );

		// Create a new plugin details manager.
		$this->manager = new PDEV_Fields_Manager( 'plugin_details' );

		// Enqueue scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Output the plugin details box.
		add_action( 'edit_form_after_editor', array( $this, 'plugin_details_box' ) );

		// Add/Remove meta boxes.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Add custom option to the publish/submit meta box.
		add_action( 'post_submitbox_misc_actions', array( $this, 'submitbox_misc_actions' ) );

		// Save metadata on post save.
		add_action( 'save_post', array( $this, 'update' ) );

		// Filter the post author drop-down.
		add_filter( 'wp_dropdown_users_args', array( $this, 'dropdown_users_args' ), 10, 2 );
	}

	/**
	 * Load scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		wp_enqueue_style( 'pdev-admin' );
		wp_enqueue_script( 'pdev-edit-plugin' );
	}

	/**
	 * Adds/Removes meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $post_type
	 * @return void
	 */
	public function add_meta_boxes( $post_type ) {

		remove_meta_box( 'postexcerpt', $post_type, 'normal' );

		add_meta_box( 'pdev_icon', esc_html__( 'Icon', 'plugin-developer' ), array( $this, 'icon_meta_box' ), $post_type, 'side', 'low' );
	}

	/**
	 * Callback on the `post_submitbox_misc_actions` hook (submit meta box). This handles
	 * the output of the sticky plugin feature.
	 *
	 * @note   Prior to WP 4.4.0, the `$post` parameter was not passed.
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @return void
	 */
	public function submitbox_misc_actions( $post = '' ) {

		// Pre-4.4.0 compatibility.
		if ( ! $post ) {
			global $post;
		}

		// Get the post type object.
		$post_type_object = get_post_type_object( pdev_get_plugin_post_type() );

		// Is the plugin sticky?
		$is_sticky = pdev_is_plugin_sticky( $post->ID );

		// Set the label based on whether the plugin is sticky.
		$label = $is_sticky ? esc_html__( 'Sticky', 'plugin-developer' ) : esc_html__( 'Not Sticky', 'plugin-developer' ); ?>

		<div class="misc-pub-section curtime misc-pub-plugin-sticky">

			<?php wp_nonce_field( 'pdev_plugin_publish_box_nonce', 'pdev_plugin_publish_box' ); ?>

			<i class="dashicons dashicons-sticky"></i>
			<?php printf( esc_html__( 'Sticky: %s', 'plugin-developer' ), "<strong class='pdev-sticky-status'>{$label}</strong>" ); ?>

			<?php if ( current_user_can( $post_type_object->cap->publish_posts ) ) : ?>

				<a href="#pdev-sticky-edit" class="pdev-edit-sticky"><span aria-hidden="true"><?php esc_html_e( 'Edit', 'plugin-developer' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit sticky status', 'plugin-developer' ); ?></span></a>

				<div id="pdev-sticky-edit" class="hide-if-js">
					<label>
						<input type="checkbox" name="pdev_plugin_sticky" id="pdev-plugin-sticky" <?php checked( $is_sticky ); ?> value="true" />
						<?php esc_html_e( 'Stick to the plugin archive', 'plugin-developer' ); ?>
					</label>
					<a href="#pdev-plugin-sticky" class="pdev-save-sticky hide-if-no-js button"><?php esc_html_e( 'OK', 'custom-content-portolio' ); ?></a>
					<a href="#pdev-plugin-sticky" class="pdev-cancel-sticky hide-if-no-js button-cancel"><?php esc_html_e( 'Cancel', 'custom-content-portolio' ); ?></a>
				</div><!-- #pdev-sticky-edit -->

			<?php endif; ?>

		</div><!-- .misc-pub-plugin-sticky -->
	<?php }

	/**
	 * Output the plugin details box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @return void
	 */
	public function plugin_details_box( $post ) { ?>

		<div id="pdev-plugin-tabs" class="postbox">

			<h3><?php printf( esc_html__( 'Details: %s', 'members' ), '<span class="pdev-which-tab"></span>' ); ?></h3>

			<div class="inside">
				<?php $this->manager->display( $post->ID ); ?>
			</div><!-- .inside -->

		</div><!-- .postbox -->
	<?php }

	function icon_meta_box( $post ) {

		// Get the icon attachment ID.
		$attachment_id = pdev_get_plugin_meta( $post->ID, 'icon_image_id' );

		// If an attachment ID was found, get the image source.
		if ( $attachment_id )
			$image = wp_get_attachment_image_src( absint( $attachment_id ), 'post-thumbnail' );

		// Get the image URL.
		$url = !empty( $image ) && isset( $image[0] ) ? $image[0] : ''; ?>

		<!-- Begin hidden fields. -->
		<?php wp_nonce_field( plugin_basename( __FILE__ ), 'pdev_icon_nonce' ); ?>
		<input type="hidden" name="pdev-icon-image" id="pdev-icon-image" value="<?php echo esc_attr( $attachment_id ); ?>" />
		<!-- End hidden fields. -->

		<!-- Begin icon image. -->
		<p>
			<a href="#" class="pdev-add-media pdev-add-media-img"><img class="pdev-icon-image-url" src="<?php echo esc_url( $url ); ?>" style="max-width: 100%; max-height: 200px; height: auto; display: block;" /></a>
			<a href="#" class="pdev-add-media pdev-add-media-text"><?php esc_html_e( 'Set icon', 'plugin-deveoper' ); ?></a>
			<a href="#" class="pdev-remove-media"><?php esc_html_e( 'Remove icon', 'plugin-developer' ); ?></a>
		</p>
		<!-- End icon image. -->

	<?php }

	/**
	 * Save plugin details settings on post save.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $post_id
	 * @return void
	 */
	public function update( $post_id ) {

		$this->manager->update( $post_id );

		// Verify the nonce.
		if ( ! isset( $_POST['pdev_plugin_publish_box'] ) || ! wp_verify_nonce( $_POST['pdev_plugin_publish_box'], 'pdev_plugin_publish_box_nonce' ) )
			return;

		// Is the sticky checkbox checked?
		$should_stick = ! empty( $_POST['pdev_plugin_sticky'] );

		// If checked, add the plugin if it is not sticky.
		if ( $should_stick && ! pdev_is_plugin_sticky( $post_id ) )
			pdev_add_sticky_plugin( $post_id );

		// If not checked, remove the plugin if it is sticky.
		elseif ( ! $should_stick && pdev_is_plugin_sticky( $post_id ) )
			pdev_remove_sticky_plugin( $post_id );

		// === Save the icon. === //

		// Verify the nonce.
		if ( ! isset( $_POST['pdev_icon_nonce'] ) || ! wp_verify_nonce( $_POST['pdev_icon_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		// Get the attachment ID.
		$new_icon = absint( $_POST['pdev-icon-image'] );
		$old_icon = pdev_get_plugin_meta( $post_id, 'icon_image_id' );

		if ( '' == $new_icon && $old_icon )
			pdev_delete_plugin_meta( $post_id, 'icon_image_id' );

		else if ( $new_icon !== $old_icon )
			pdev_set_plugin_meta( $post_id, 'icon_image_id', $new_icon );
	}

	/**
	 * Filter on the post author drop-down (used in the "Author" meta box) to only show users
	 * of roles that have the correct capability for editing portfolio plugins.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $args
	 * @param  array   $r
	 * @global object  $wp_roles
	 * @global object  $post
	 * @return array
	 */
	function dropdown_users_args( $args, $r ) {
		global $wp_roles, $post;

		// WP version 4.4.0 check. Bail if we can't use the `role__in` argument.
		if ( ! method_exists( 'WP_User_Query', 'fill_query_vars' ) )
			return $args;

		// Check that this is the correct drop-down.
		if ( 'post_author_override' === $r['name'] && pdev_get_plugin_post_type() === $post->post_type ) {

			$roles = array();

			// Loop through the available roles.
			foreach ( $wp_roles->roles as $name => $role ) {

				// Get the edit posts cap.
				$cap = get_post_type_object( pdev_get_plugin_post_type() )->cap->edit_posts;

				// If the role is granted the edit posts cap, add it.
				if ( isset( $role['capabilities'][ $cap ] ) && true === $role['capabilities'][ $cap ] )
					$roles[] = $name;
			}

			// If we have roles, change the args to only get users of those roles.
			if ( $roles ) {
				$args['who']      = '';
				$args['role__in'] = $roles;
			}
		}

		return $args;
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

		// Title and editor help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'title_editor',
				'title'    => esc_html__( 'Title and Editor', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_title_editor' )
			)
		);

		// Details: General help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'details_general',
				'title'    => esc_html__( 'Details: General', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_details_general' )
			)
		);

		// Details: Integration help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'details_integration',
				'title'    => esc_html__( 'Details: Integration', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_details_integration' )
			)
		);

		// Details: Links help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'details_links',
				'title'    => esc_html__( 'Details: Links', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_details_links' )
			)
		);

		// Details: Description help tab.
		$screen->add_help_tab(
			array(
				'id'       => 'details_description',
				'title'    => esc_html__( 'Details: Description', 'plugin-developer' ),
				'callback' => array( $this, 'help_tab_details_description' )
			)
		);

		// Set the help sidebar.
		$screen->set_help_sidebar( pdev_get_help_sidebar_text() );
	}

	/**
	 * Displays the title and editor help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_title_editor() { ?>

		<ul>
			<li><?php _e( "<strong>Title:</strong> Enter the name of your plugin. After you enter a name, you'll see the permalink below, which you can edit.", 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Editor:</strong> The editor allows you to add or edit content for your plugin. You can insert text, media, or shortcodes.', 'plugin-developer' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the general details help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_details_general() { ?>

		<p>
			<?php esc_html_e( 'The general section allows you to enter the most common information about your plugin.', 'plugin-developer' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>Version:</strong> The current version number for the plugin.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Download URL:</strong> The URL where the plugin files can be downloaded.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Demo URL:</strong> The URL to a preview/demo of the plugin.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Parent Plugin:</strong> Drop-down select to choose a parent plugin if the current plugin is a child plugin.', 'plugin-developer' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the integration details help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_details_integration() { ?>

		<p>
			<?php esc_html_e( 'The integration section is for integration with third-party plugins and APIs.', 'plugin-developer' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>WordPress.org Slug:</strong> Enter the slug if your plugin is hosted on WordPress.org.', 'plugin-developer' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the plugin details help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_details_links() { ?>

		<p>
			<?php esc_html_e( 'The links section is for entering custom URLs associated with your plugin.', 'plugin-developer' ); ?>
		</p>

		<ul>
			<li><?php _e( '<strong>Repository URL:</strong> The URL to version-controlled repository for the plugin (e.g., GitHub, BitBucket).', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Purchase URL:</strong> The URL where the plugin can be purchased.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Support URL:</strong> The URL where users can find support, such as your support forums.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Translation URL:</strong> The URL where the plugin can be translated and/or where translations can be found.', 'plugin-developer' ); ?></li>
			<li><?php _e( '<strong>Documentation URL:</strong> The URL to the plugin documentation.', 'plugin-developer' ); ?></li>
		</ul>
	<?php }

	/**
	 * Displays the plugin details help tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help_tab_details_description() { ?>

		<p>
			<?php esc_html_e( 'The description section allows you to enter a custom description (i.e., excerpt) of the plugin.', 'plugin-developer' ); ?>
		</p>
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

PDEV_Plugin_Edit::get_instance();
