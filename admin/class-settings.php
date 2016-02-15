<?php
/**
 * Plugin settings screen.
 *
 * @package    PluginDeveloper
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Sets up and handles the plugin settings screen.
 *
 * @since  1.0.0
 * @access public
 */
final class PDEV_Settings_Page {

	/**
	 * Settings page name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $settings_page = '';

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Sets up custom admin menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		// Create the settings page.
		$this->settings_page = add_submenu_page(
			'edit.php?post_type=' . pdev_get_plugin_post_type(),
			esc_html__( 'Plugin Developer Settings', 'plugin-developer' ),
			esc_html__( 'Settings', 'plugin-developer' ),
			apply_filters( 'pdev_settings_capability', 'manage_options' ),
			'pdev-settings',
			array( $this, 'settings_page' )
		);

		if ( $this->settings_page ) {

			// Register settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	function register_settings() {

		// Register the setting.
		register_setting( 'pdev_settings', 'pdev_settings', array( $this, 'validate_settings' ) );

		/* === Settings Sections === */

		add_settings_section( 'general',     esc_html__( 'General',     'plugin-developer' ), array( $this, 'section_general'     ), $this->settings_page );
		add_settings_section( 'reading',     esc_html__( 'Reading',     'plugin-developer' ), array( $this, 'section_reading'     ), $this->settings_page );
		add_settings_section( 'integration', esc_html__( 'Integration', 'plugin-developer' ), array( $this, 'section_integration' ), $this->settings_page );
		add_settings_section( 'permalinks',  esc_html__( 'Permalinks',  'plugin-developer' ), array( $this, 'section_permalinks'  ), $this->settings_page );

		/* === Settings Fields === */

		// General section fields.
		add_settings_field( 'menu_title',          esc_html__( 'Menu Title',          'plugin-developer' ), array( $this, 'field_menu_title',         ), $this->settings_page, 'general' );
		add_settings_field( 'archive_title',       esc_html__( 'Archive Title',       'plugin-developer' ), array( $this, 'field_archive_title'       ), $this->settings_page, 'general' );
		add_settings_field( 'archive_description', esc_html__( 'Archive Description', 'plugin-developer' ), array( $this, 'field_archive_description' ), $this->settings_page, 'general' );

		// Reading section fields.
		add_settings_field( 'plugins_per_page', esc_html__( 'Plugins Per Page', 'plugin-developer' ), array( $this, 'field_plugins_per_page' ), $this->settings_page, 'reading' );
		add_settings_field( 'plugins_orderby',  esc_html__( 'Sort By',          'plugin-developer' ), array( $this, 'field_plugins_orderby'  ), $this->settings_page, 'reading' );
		add_settings_field( 'plugins_order',    esc_html__( 'Order',            'plugin-developer' ), array( $this, 'field_plugins_order'    ), $this->settings_page, 'reading' );

		// Integration section fields.
		add_settings_field( 'wporg_integration', esc_html__( 'WordPress.org',           'plugin-developer' ), array( $this, 'field_wporg_integration' ), $this->settings_page, 'integration' );
		add_settings_field( 'wporg_transient',   esc_html__( 'WordPress.org Transient', 'plugin-developer' ), array( $this, 'field_wporg_transient'   ), $this->settings_page, 'integration' );

		// Permalinks section fields.
		add_settings_field( 'rewrite_base',          esc_html__( 'Rewrite Base', 'plugin-developer' ), array( $this, 'field_rewrite_base'         ), $this->settings_page, 'permalinks' );
		add_settings_field( 'plugin_rewrite_base',   esc_html__( 'Plugin Slug',  'plugin-developer' ), array( $this, 'field_plugin_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'category_rewrite_base',  esc_html__( 'Category Slug', 'plugin-developer' ), array( $this, 'field_category_rewrite_base' ), $this->settings_page, 'permalinks' );
		add_settings_field( 'tag_rewrite_base',      esc_html__( 'Tag Slug',     'plugin-developer' ), array( $this, 'field_tag_rewrite_base' ), $this->settings_page, 'permalinks' );
		add_settings_field( 'author_rewrite_base',   esc_html__( 'Author Slug',  'plugin-developer' ), array( $this, 'field_author_rewrite_base'  ), $this->settings_page, 'permalinks' );
	}

	/**
	 * Validates the plugin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $input
	 * @return array
	 */
	function validate_settings( $settings ) {

		// Text boxes.
		$settings['rewrite_base']         = $settings['rewrite_base']         ? trim( strip_tags( $settings['rewrite_base']         ), '/' ) : 'plugins';
		$settings['plugin_rewrite_base']  = $settings['plugin_rewrite_base']  ? trim( strip_tags( $settings['plugin_rewrite_base']   ), '/' ) : '';
		$settings['category_rewrite_base'] = $settings['category_rewrite_base'] ? trim( strip_tags( $settings['category_rewrite_base'] ), '/' ) : '';
		$settings['tag_rewrite_base']     = $settings['tag_rewrite_base']     ? trim( strip_tags( $settings['tag_rewrite_base'] ), '/' ) : 'tags';
		$settings['author_rewrite_base']  = $settings['author_rewrite_base']  ? trim( strip_tags( $settings['author_rewrite_base']  ), '/' ) : '';

		$settings['menu_title']    = $settings['menu_title']    ? strip_tags( $settings['menu_title'] )    : esc_html__( 'Plugins', 'plugin-developer' );
		$settings['archive_title'] = $settings['archive_title'] ? strip_tags( $settings['archive_title'] ) : esc_html__( 'Plugins', 'plugin-developer' );

		// Kill evil scripts.
		$settings['archive_description'] = stripslashes( wp_filter_post_kses( addslashes( $settings['archive_description'] ) ) );

		// Numbers.
		$expire = absint( $settings['wporg_transient'] );
		$settings['wporg_transient'] = 0 < $expire && 91 < $expire ? $expire : 3;

		$plugins_per_page = intval( $settings['plugins_per_page'] );
		$settings['plugins_per_page'] = -2 < $plugins_per_page ? $plugins_per_page : 10;

		// Select boxes.
		$settings['plugins_orderby'] = isset( $settings['plugins_orderby'] ) ? strip_tags( $settings['plugins_orderby'] ) : 'date';
		$settings['plugins_order']   = isset( $settings['plugins_order'] )   ? strip_tags( $settings['plugins_order']   ) : 'DESC';

		// Checkboxes.
		$settings['wporg_integration'] = ! empty( $settings['wporg_integration'] );

		/* === Handle Permalink Conflicts ===*/

		// No plugin or category base, plugins win.
		if ( ! $settings['plugin_rewrite_base'] && ! $settings['category_rewrite_base'] )
			$settings['category_rewrite_base'] = 'categories';

		// No plugin or author base, plugins win.
		if ( ! $settings['plugin_rewrite_base'] && ! $settings['author_rewrite_base'] )
			$settings['author_rewrite_base'] = 'authors';

		// No category or author base, categories win.
		if ( ! $settings['category_rewrite_base'] && ! $settings['author_rewrite_base'] )
			$settings['author_rewrite_base'] = 'authors';

		// Return the validated/sanitized settings.
		return $settings;
	}

	/**
	 * General section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_general() { ?>

		<p class="description">
			<?php esc_html_e( 'General settings for the plugins section of your site.', 'plugin-developer' ); ?>
		</p>
	<?php }

	/**
	 * Menu title field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_menu_title() { ?>

		<label>
			<input type="text" class="regular-text" name="pdev_settings[menu_title]" value="<?php echo esc_attr( pdev_get_menu_title() ); ?>" />
			<br />
			<span class="description"><?php esc_html_e( 'The title for the plugins admin menu.', 'plugin-developer' ); ?></span>
		</label>
	<?php }

	/**
	 * Archive title field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_archive_title() { ?>

		<label>
			<input type="text" class="regular-text" name="pdev_settings[archive_title]" value="<?php echo esc_attr( pdev_get_archive_title() ); ?>" />
			<br />
			<span class="description"><?php esc_html_e( 'The title used for plugin archive.', 'plugin-developer' ); ?></span>
		</label>
	<?php }

	/**
	 * Archive description field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_archive_description() {

		wp_editor(
			pdev_get_archive_description(),
			'pdev_archive_description',
			array(
				'textarea_name'    => 'pdev_settings[archive_description]',
				'drag_drop_upload' => true,
				'editor_height'    => 150
			)
		); ?>

		<p>
			<span class="description"><?php esc_html_e( 'Your plugin archive description. This may or may not be shown by your plugin.', 'plugin-developer' ); ?></span>
		</p>
	<?php }

	/**
	 * Reading section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_reading() { ?>

		<p class="description">
			<?php esc_html_e( 'Reading settings for the front end of your site.', 'plugin-developer' ); ?>
		</p>
	<?php }

	/**
	 * Plugins per page field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_plugins_per_page() { ?>

		<label>
			<input type="number" class="small-text" min="-1" name="pdev_settings[plugins_per_page]" value="<?php echo esc_attr( pdev_get_plugins_per_page() ); ?>" />
		</label>
	<?php }

	/**
	 * Plugins orderby field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_plugins_orderby() {

		$orderby = array(
			'author'   => __( 'Author',           'plugin-developer' ),
			'date'     => __( 'Date (Published)', 'plugin-developer' ),
			'modified' => __( 'Date (Modified)',  'plugin-developer' ),
			'ID'       => __( 'ID',               'plugin-developer' ),
			'rand'     => __( 'Random',           'plugin-developer' ),
			'name'     => __( 'Slug',             'plugin-developer' ),
			'title'    => __( 'Title',            'plugin-developer' )
		); ?>

		<label>
			<select name="pdev_settings[plugins_orderby]">

			<?php foreach ( $orderby as $option => $label ) : ?>
				<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, pdev_get_plugins_orderby() ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>

			</select>
		<label>
	<?php }

	/**
	 * Plugins order field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_plugins_order() {

		$order = array(
			'ASC'  => __( 'Ascending',  'plugin-developer' ),
			'DESC' => __( 'Descending', 'plugin-developer' )
		); ?>

		<label>
			<select name="pdev_settings[plugins_order]">

			<?php foreach ( $order as $option => $label ) : ?>
				<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $option, pdev_get_plugins_order() ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>

			</select>
		<label>
	<?php }

	/**
	 * Integration section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_integration() { ?>

		<p class="description">
			<?php esc_html_e( 'Integrate your plugins with third-party sites.', 'plugin-developer' ); ?>
		</p>
	<?php }

	/**
	 * WordPress.org integration field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_wporg_integration() { ?>

		<label>
			<input type="checkbox" name="pdev_settings[wporg_integration]" value="true" <?php checked( pdev_use_wporg_api() ); ?> />
			<?php esc_html_e( 'Use the WordPress.org plugins API?', 'plugin-developer' ); ?>
		</label>
	<?php }

	/**
	 * WordPress.org integration transient expiration field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_wporg_transient() { ?>

		<label>
			<input type="number" class="small-text" min="1" max="90" name="pdev_settings[wporg_transient]" value="<?php echo esc_attr( pdev_get_setting( 'wporg_transient' ) ); ?>" />
			<br />
			<span class="description"><?php esc_html_e( 'How often (in days) to update plugin data from the WordPress.org API.', 'plugin-developer' ); ?></span>
		</label>
	<?php }

	/**
	 * Permalinks section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section_permalinks() { ?>

		<p class="description">
			<?php esc_html_e( 'Set up custom permalinks for the plugins section on your site.', 'plugin-developer' ); ?>
		</p>
	<?php }

	/**
	 * Rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="pdev_settings[rewrite_base]" value="<?php echo esc_attr( pdev_get_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Plugin rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_plugin_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( pdev_get_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="pdev_settings[plugin_rewrite_base]" value="<?php echo esc_attr( pdev_get_plugin_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Category rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_category_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( pdev_get_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="pdev_settings[category_rewrite_base]" value="<?php echo esc_attr( pdev_get_category_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Feature rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_tag_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( pdev_get_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="pdev_settings[tag_rewrite_base]" value="<?php echo esc_attr( pdev_get_tag_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Author rewrite base field callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_author_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( pdev_get_rewrite_base() . '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="pdev_settings[author_rewrite_base]" value="<?php echo esc_attr( pdev_get_author_rewrite_base() ); ?>" />
		</label>
	<?php }

	/**
	 * Renders the settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_page() {

		// Flush the rewrite rules if the settings were updated.
		if ( isset( $_GET['settings-updated'] ) )
			flush_rewrite_rules(); ?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Plugin Developer Settings', 'plugin-developer' ); ?></h1>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'pdev_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'plugin-developer' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
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

PDEV_Settings_Page::get_instance();
