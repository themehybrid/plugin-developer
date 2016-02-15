<?php
/**
 * Admin functions and filters.
 *
 * @package    PluginDeveloper
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register scripts and styles.
add_action( 'admin_enqueue_scripts', 'pdev_admin_register_scripts', 0 );
add_action( 'admin_enqueue_scripts', 'pdev_admin_register_styles',  0 );

# Registers plugin details box sections, controls, and settings.
add_action( 'pdev_plugin_details_manager_register', 'pdev_plugin_details_register', 5 );

# Change cap group name in Members plugin.
add_action( 'members_register_cap_groups', 'pdev_register_cap_groups' );

/**
 * Registers admin scripts.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_admin_register_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'pdev-edit-plugin', pdev_plugin()->js_uri . "edit-plugin{$min}.js", array( 'jquery' ), '', true );

	// Localize our script with some text we want to pass in.
	$i18n = array(
		'label_sticky'      => esc_html__( 'Sticky',     'plugin-developer' ),
		'label_not_sticky'  => esc_html__( 'Not Sticky', 'plugin-developer' ),
		'label_icon_title'  => esc_html__( 'Set Icon',   'plugin-developer' ),
		'label_icon_button' => esc_html__( 'Set icon',   'plugin-developer' ),
	);

	wp_localize_script( 'pdev-edit-plugin', 'pdev_i18n', $i18n );
}

/**
 * Registers admin styles.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_admin_register_styles() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'pdev-admin', pdev_plugin()->css_uri . "admin{$min}.css" );
}

/**
 * Registers the default cap groups.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_plugin_details_register( $manager ) {

	/* === Register Sections === */

	// General section.
	$manager->register_section( 'general',
		array(
			'label' => esc_html__( 'General', 'plugin-developer' ),
			'icon'  => 'dashicons-admin-generic'
		)
	);

	// Integration section.
	$manager->register_section( 'integration',
		array(
			'label' => esc_html__( 'Integration', 'plugin-developer' ),
			'icon'  => 'dashicons-editor-code'
		)
	);

	// Links section.
	$manager->register_section( 'links',
		array(
			'label' => esc_html__( 'Links', 'plugin-developer' ),
			'icon'  => 'dashicons-admin-links'
		)
	);

	// Description section.
	$manager->register_section( 'description',
		array(
			'label' => esc_html__( 'Description', 'plugin-developer' ),
			'icon'  => 'dashicons-edit'
		)
	);

	// Contributors section.
	$manager->register_section( 'contributors',
		array(
			'label' => esc_html__( 'Contributors', 'plugin-developer' ),
			'icon'  => 'dashicons-groups'
		)
	);

	/* === Register Controls === */

	$version_args = array(
		'section'     => 'general',
		'attr'        => array( 'placeholder' => '1.0.0' ),
		'label'       => esc_html__( 'Version', 'plugin-developer' ),
		'description' => esc_html__( 'The current version of the plugin.', 'plugin-developer' )
	);

	$download_url_args = array(
		'section'     => 'general',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Download URL', 'plugin-developer' ),
		'description' => esc_html__( 'URL to ZIP or other type of file to download.', 'plugin-developer' )
	);

	$parent_plugin_args = array(
		'section'     => 'general',
		'label'       => esc_html__( 'Parent Plugin', 'plugin-developer' ),
		'description' => esc_html__( 'The parent plugin if this is an add-on.', 'plugin-developer' )
	);

	$repo_url_args = array(
		'section'     => 'links',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Repository URL', 'plugin-developer' ),
		'description' => esc_html__( 'URL to the code repository.', 'plugin-developer' )
	);

	$purchase_url_args = array(
		'section'     => 'links',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Purchase URL', 'plugin-developer' ),
		'description' => esc_html__( 'URL to where the plugin can be purchased.', 'plugin-developer' )
	);

	$support_url_args = array(
		'section'     => 'links',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Support URL', 'plugin-developer' ),
		'description' => esc_html__( 'Plugin support or forum URL.', 'plugin-developer' )
	);

	$docs_url_args = array(
		'section'     => 'links',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Documentation URL', 'plugin-developer' ),
		'description' => esc_html__( 'URL to the plugin documentation.', 'plugin-developer' )
	);

	$translate_url_args = array(
		'section'     => 'links',
		'attr'        => array( 'class' => 'widefat', 'placeholder' => 'http://example.com' ),
		'label'       => esc_html__( 'Translation URL', 'plugin-developer' ),
		'description' => esc_html__( 'URL to the plugin translations.', 'plugin-developer' )
	);

	$wporg_slug_args = array(
		'section'     => 'integration',
		'attr'        => array( 'placeholder' => 'example' ),
		'label'       => esc_html__( 'WordPress.org Slug', 'plugin-developer' ),
		'description' => esc_html__( 'Slug (not URL) of the plugin on the WordPress.org plugin directory.', 'plugin-developer' )
	);

	$github_slug_args = array(
		'section'     => 'integration',
		'attr'        => array( 'placeholder' => 'username/repository' ),
		'label'       => esc_html__( 'GitHub Slug', 'plugin-developer' ),
		'description' => esc_html__( 'Username and slug of repository on GitHub (e.g., username/repository).', 'plugin-developer' )
	);

	$excerpt_args = array(
		'section'     => 'description',
		'type'        => 'textarea',
		'attr'        => array( 'id' => 'excerpt', 'name' => 'excerpt' ),
		'label'       => esc_html__( 'Description', 'plugin-developer' ),
		'description' => esc_html__( 'Write a short description (excerpt) of the plugin.', 'plugin-developer' )
	);

	$contributor_args = array(
		'section'     => 'contributors',
		'label'       => esc_html__( 'Contributors', 'plugin-developer' ),
		'description' => esc_html__( 'Select contributors to the plugin.', 'plugin-developer' )
	);

	$manager->register_control( 'version',         $version_args         );
	$manager->register_control( 'download_url',    $download_url_args    );
	$manager->register_control( 'repo_url',        $repo_url_args        );
	$manager->register_control( 'purchase_url',    $purchase_url_args    );
	$manager->register_control( 'support_url',     $support_url_args     );
	$manager->register_control( 'translate_url',   $translate_url_args   );
	$manager->register_control( 'docs_url',        $docs_url_args        );

	if ( pdev_use_wporg_api() )
		$manager->register_control( 'wporg_slug',      $wporg_slug_args      );

	$manager->register_control( new PDEV_Fields_Control_Parent( $manager, 'parent_id', $parent_plugin_args ) );

	$manager->register_control( new PDEV_Fields_Control_Excerpt( $manager, 'excerpt',    $excerpt_args    ) );

	$manager->register_control( new PDEV_Fields_Control_Multi_Avatars( $manager, 'contributor',    $contributor_args    ) );

	/* === Register Settings === */

	$manager->register_setting( 'version',         array( 'sanitize_callback' => '' ) );
	$manager->register_setting( 'download_url',    array( 'sanitize_callback' => 'esc_url_raw' ) );
	$manager->register_setting( 'repo_url',        array( 'sanitize_callback' => 'esc_url_raw' ) );
	$manager->register_setting( 'purchase_url',    array( 'sanitize_callback' => 'esc_url_raw' ) );
	$manager->register_setting( 'support_url',     array( 'sanitize_callback' => 'esc_url_raw' ) );
	$manager->register_setting( 'translate_url',   array( 'sanitize_callback' => 'esc_url_raw' ) );
	$manager->register_setting( 'docs_url',        array( 'sanitize_callback' => 'esc_url_raw' ) );

	$manager->register_setting( new PDEV_Fields_Setting_Array( $manager, 'contributor', array( 'sanitize_callback' => 'absint' ) ) );

	if ( pdev_use_wporg_api() )
		$manager->register_setting( 'wporg_slug',      array( 'sanitize_callback' => 'sanitize_title_with_dashes' ) );
}

/**
 * Help sidebar for all of the help tabs.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function pdev_get_help_sidebar_text() {

	// Get docs and help links.
	$docs_link = sprintf( '<li><a href="http://themehybrid.com/docs">%s</a></li>', esc_html__( 'Documentation', 'plugin-developer' ) );
	$help_link = sprintf( '<li><a href="http://themehybrid.com/board/topics">%s</a></li>', esc_html__( 'Support Forums', 'plugin-developer' ) );

	// Return the text.
	return sprintf(
		'<p><strong>%s</strong></p><ul>%s%s</ul>',
		esc_html__( 'For more information:', 'plugin-developer' ),
		$docs_link,
		$help_link
	);
}

/**
 * Overwrites the cap group in the Members plugin specifically for our CPT and CT.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function pdev_register_cap_groups() {

	if ( ! function_exists( 'members_cap_group_exists' ) || ! function_exists( 'members_get_cap_group' ) )
		return;

	$type = pdev_get_plugin_post_type();

	if ( members_cap_group_exists( "type-{$type}" ) ) {

		$group = members_get_cap_group( "type-{$type}" );

		$group->label = esc_html__( 'Plugin Developer', 'plugin-developer' );

		$cat_caps = array_values( pdev_get_category_capabilities() );
		$tag_caps = array_values( pdev_get_tag_capabilities() );

		$group->caps = array_unique( array_merge( $group->caps, $cat_caps, $tag_caps ) );
	}
}
