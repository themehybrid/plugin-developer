<?php
/**
 * Plugin filters.
 *
 * @package    PluginDeveloper
 * @subpackage Core
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       http://pluginhybrid.com/plugins/plugin-developer
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Template hierarchy.
add_filter( 'template_include', 'pdev_template_include', 5 );

# Filter prior to getting posts from the DB.
add_action( 'pre_get_posts', 'pdev_pre_get_posts' );

# Redirect non-authors.
add_action( 'template_redirect', 'pdev_template_redirect', 5 );

# Filter the document title.
add_filter( 'document_title_parts', 'pdev_document_title_parts', 5 );

# Filter the post type archive title.
add_filter( 'post_type_archive_title', 'pdev_post_type_archive_title', 5, 2 );

# Filter the archive title and description.
add_filter( 'get_the_archive_title',       'pdev_get_the_archive_title',       5 );
add_filter( 'get_the_archive_description', 'pdev_get_the_archive_description', 5 );

# Filter the post type permalink.
add_filter( 'post_type_link', 'pdev_post_type_link', 10, 2 );

# Filter the post author link.
add_filter( 'author_link', 'pdev_author_link_filter', 10, 3 );

# Filter the post class.
add_filter( 'post_class', 'pdev_post_class', 10, 3 );

# Force taxonomy term selection.
add_action( 'save_post', 'pdev_force_term_selection' );

# Plugin content filters.
add_filter( 'pdev_get_plugin_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 5 );
add_filter( 'pdev_get_plugin_content', array( $GLOBALS['wp_embed'], 'autoembed'     ), 5 );
add_filter( 'pdev_get_plugin_content', 'wptexturize',                                  5 );
add_filter( 'pdev_get_plugin_content', 'convert_smilies',                              5 );
add_filter( 'pdev_get_plugin_content', 'convert_chars',                                5 );
add_filter( 'pdev_get_plugin_content', 'wpautop',                                      5 );
add_filter( 'pdev_get_plugin_content', 'shortcode_unautop',                            5 );
add_filter( 'pdev_get_plugin_content', 'do_shortcode',                                 5 );
add_filter( 'pdev_get_plugin_content', 'wp_make_content_images_responsive',            5 );

# Plugin excerpt filters.
add_filter( 'pdev_get_plugin_excerpt', 'wptexturize',       5 );
add_filter( 'pdev_get_plugin_excerpt', 'convert_smilies',   5 );
add_filter( 'pdev_get_plugin_excerpt', 'convert_chars',     5 );
add_filter( 'pdev_get_plugin_excerpt', 'wpautop',           5 );
add_filter( 'pdev_get_plugin_excerpt', 'shortcode_unautop', 5 );
add_filter( 'pdev_get_plugin_content', 'do_shortcode',      5 );

/**
 * Basic top-level template hierarchy. I generally prefer to leave this functionality up to
 * plugins.  This is just a foundation to build upon if needed.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $template
 * @return string
 */
function pdev_template_include( $template ) {

	// Bail if not a plugin page.
	if ( ! pdev_is_plugin_developer() )
		return $template;

	$templates = array();

	// Author archive.
	if ( pdev_is_author() ) {
		$templates[] = 'plugin-author.php';

	// Category archive.
	} else if ( pdev_is_category() ) {
		$templates[] = 'plugin-category.php';

	// Feature archive.
	} else if ( pdev_is_tag() ) {
		$templates[] = 'plugin-tag.php';

	// Single plugin.
	} else if ( pdev_is_single_plugin() ) {

		$post_template = get_post_meta( get_queried_object_id(), 'template', true );

		if ( '' === $post_template )
			$post_template = get_post_meta( get_queried_object_id(), '_wp_plugin_template', true );

		if ( $post_template )
			$templates[] = $post_template;

		$templates[] = 'plugin-single.php';
	}

	// Fallback template for all archive-type pages.
	if ( pdev_is_archive() )
		$templates[] = 'plugin-archive.php';

	// Fallback template.
	$templates[] = 'plugin-developer.php';

	// Check if we have a template.
	$has_template = locate_template( apply_filters( 'pdev_template_hierarchy', $templates ) );

	// Return the template.
	return $has_template ? $has_template : $template;
}

/**
 * Filter on `pre_get_posts` to alter the main query on plugin pages.
 *
 * @since  1.0.0
 * @access public
 * @param  object  $query
 * @return void
 */
function pdev_pre_get_posts( $query ) {

	if ( ! is_admin() && $query->is_main_query() && pdev_is_archive() ) {

		// Set the plugins per page.
		$query->set( 'posts_per_page', pdev_get_plugins_per_page() );
		$query->set( 'orderby',        pdev_get_plugins_orderby()  );
		$query->set( 'order',          pdev_get_plugins_order()    );

		if ( pdev_is_author() ) {

			$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => 'contributor',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'   => 'contributor',
					'value' => $user->ID,
					'type'  => 'NUMERIC'
				)
			);

			$query->set( 'meta_query', $meta_query );

			add_filter( 'posts_where', 'pdev_plugin_author_posts_where', 10, 2 );
		}
	}
}

/**
 * Filter on `posts_where`.  This is only used on the author archives if we're on the main query.  The
 * filter is used to make sure that if an author is a "contributor" to a plugin, that the plugin shows
 * up in their author archive.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $where
 * @param  object  $query
 * @return string
 */
function pdev_plugin_author_posts_where( $where, $query ){
	global $wpdb;

	if ( ! is_admin() && $query->is_main_query() && pdev_is_author() ) {

		$user = get_user_by( 'slug', get_query_var( 'author_name' ) );

		$where .= $wpdb->prepare(
			" OR ( $wpdb->postmeta.meta_key = 'contributor' AND $wpdb->postmeta.meta_value = %d )",
			absint( $user->ID )
		);

		remove_filter( 'posts_where', 'pdev_plugin_author_posts_where' );
	}

	return $where;
}

/**
 * Redirects author requests for users who have not published any plugins to the
 * plugin archive page.
 *
 * @since  1.0.0
 * @access public
 * @global object  $wp_the_query
 * @return void
 */
function pdev_template_redirect() {
	global $wp_the_query;

	if ( pdev_is_author() && 0 >= $wp_the_query->post_count ) {

		wp_redirect( esc_url_raw( pdev_get_plugin_archive_url() ) );
		exit();
	}
}

/**
 * Filter on `document_title_parts` (WP 4.4.0).
 *
 * @since  1.0.0
 * @access public
 * @param  array  $title
 * @return array
 */
function pdev_document_title_parts( $title ) {

	if ( pdev_is_author() )
		$title['title'] = pdev_get_single_author_title();

	return $title;
}

/**
 * Filter on 'post_type_archive_title' to allow for the use of the 'archive_title' label that isn't supported
 * by WordPress.  That's okay since we can roll our own labels.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @param  string  $post_type
 * @return string
 */
function pdev_post_type_archive_title( $title, $post_type ) {

	$plugin_type = pdev_get_plugin_post_type();

	return $plugin_type === $post_type ? get_post_type_object( $plugin_type )->labels->archive_title : $title;
}

/**
 * Filters the archive title. Note that we need this additional filter because core WP does
 * things like add "Archives:" in front of the archive title.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $title
 * @return string
 */
function pdev_get_the_archive_title( $title ) {

	if ( pdev_is_author() )
		$title = pdev_get_single_author_title();

	else if ( pdev_is_plugin_archive() )
		$title = post_type_archive_title( '', false );

	return $title;
}

/**
 * Filters the archive description.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $desc
 * @return string
 */
function pdev_get_the_archive_description( $desc ) {

	if ( pdev_is_author() )
		$desc = get_the_author_meta( 'description', get_query_var( 'author' ) );

	else if ( pdev_is_plugin_archive() && ! $desc )
		$desc = pdev_get_archive_description();

	return $desc;
}

/**
 * Filter on `post_type_link` to make sure that single plugins have the correct permalink.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $post_link
 * @param  object  $post
 * @return string
 */
function pdev_post_type_link( $post_link, $post ) {

	// Bail if this isn't a plugin.
	if ( ! pdev_is_plugin( $post->ID ) )
		return $post_link;

	$category_taxonomy = pdev_get_category_taxonomy();

	$author = $category = '';

	// Check for the category.
	if ( false !== strpos( $post_link, "%{$category_taxonomy}%" ) ) {

		// Get the terms.
		$terms = get_the_terms( $post, $category_taxonomy );

		// Check that terms were returned.
		if ( $terms ) {

			usort( $terms, '_usort_terms_by_ID' );

			$category = $terms[0]->slug;
		}
	}

	// Check for the author.
	if ( false !== strpos( $post_link, '%author%' ) ) {

		$authordata = get_userdata( $post->post_author );
		$author     = $authordata->user_nicename;
	}

	$rewrite_tags = array(
		"%{$category_taxonomy}%",
		'%author%'
	);

	$map_tags = array(
		$category,
		$author
	);

	return str_replace( $rewrite_tags, $map_tags, $post_link );
}

/**
 * Filter on `author_link` to change the URL when viewing a plugin post. The new link
 * should point to the plugin author archive.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $url
 * @param  int     $author_id
 * @param  string  $nicename
 * @return string
 */
function pdev_author_link_filter( $url, $author_id, $nicename ) {

	return pdev_is_plugin() ? pdev_get_author_url( $author_id ) : $url;
}

/**
 * Filter the post class.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $classes
 * @param  string $class
 * @param  int    $post_id
 * @return array
 */
function pdev_post_class( $classes, $class, $post_id ) {

	if ( pdev_is_plugin( $post_id ) && pdev_is_plugin_archive() && pdev_is_plugin_sticky( $post_id ) && ! is_paged() )
		$classes[] = 'sticky';

	return $classes;
}

/**
 * If a plugin has `%plugin_category%` or `%plugin_tag%` in its permalink structure,
 * it must have a term set for the taxonomy.  This function is a callback on `save_post`
 * that checks if a term is set.  If not, it forces the first term of the taxonomy to be
 * the selected term.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return void
 */
function pdev_force_term_selection( $post_id ) {

	if ( pdev_is_plugin( $post_id ) ) {

		$plugin_base = pdev_get_plugin_rewrite_base();
		$category_tax = pdev_get_category_taxonomy();

		if ( false !== strpos( $plugin_base, "%{$category_tax}%" ) )
			pdev_set_term_if_none( $post_id, $category_tax, pdev_get_default_category() );
	}
}

/**
 * Checks if a post has a term of the given taxonomy.  If not, set it with the first
 * term available from the taxonomy.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  string  $taxonomy
 * @param  int     $default
 * @return void
 */
function pdev_set_term_if_none( $post_id, $taxonomy, $default = 0 ) {

	// Get the current post terms.
	$terms = wp_get_post_terms( $post_id, $taxonomy );

	// If no terms are set, let's roll.
	if ( ! $terms ) {

		$new_term = false;

		// Get the default term if set.
		if ( $default )
			$new_term = get_term( $default, $taxonomy );

		// If no default term or if there's an error, get the first term.
		if ( ! $new_term || is_wp_error( $new_term ) ) {
			$available = get_terms( $taxonomy, array( 'number' => 1 ) );

			// Get the first term.
			$new_term = $available ? array_shift( $available ) : false;
		}

		// Only run if there are taxonomy terms.
		if ( $new_term ) {
			$tax_object = get_taxonomy( $taxonomy );

			// Use the ID for hierarchical taxonomies. Use the slug for non-hierarchical.
			$slug_or_id = $tax_object->hierarchical ? $new_term->term_id : $new_term->slug;

			// Set the new post term.
			wp_set_post_terms( $post_id, $slug_or_id, $taxonomy, true );
		}
	}
}
