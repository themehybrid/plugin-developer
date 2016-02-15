<?php
/**
 * Date control class for the fields manager.
 *
 * @package    FieldsManager
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2013-2016, Justin Tadlock
 * @link       http://themehybrid.com/plugins/custom-content-portfolio
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Date control class.
 *
 * @since  1.0.0
 * @access public
 */
class PDEV_Fields_Control_Multi_Avatars extends PDEV_Fields_Control {

	public $type = 'multi-avatars';

	/**
	 * Outputs the content template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template( $post_id ) {

		$name = "pdev_{$this->manager->name}_setting_{$this->setting}[]";

		$users = get_users( array( 'role__in' => $this->get_roles( get_post_type( $post_id ) ) ) );

		$contribs = (array) $this->get_value( $post_id ); ?>

			<?php if ( $this->label ) : ?>
				<span class="pdev-fields-label"><?php echo esc_html( $this->label ); ?></span>
				<br />
			<?php endif; ?>

			<?php if ( $this->description ) : ?>
				<span class="pdev-fields-description description"><?php echo $this->description; ?></span>
				<br />
			<?php endif; ?>

		<div class="pdev-multi-avatars-wrap">

		<?php foreach ( $users as $user ) : ?>

			<label>
				<input type="checkbox" value="<?php echo esc_attr( $user->ID ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php checked( in_array( $user->ID, $contribs ) ); ?> />

				<span class="screen-reader-text"><?php echo esc_html( $user->display_name ); ?></span>

				<?php echo get_avatar( $user->ID, 70 ); ?>
			</label>

		<?php endforeach; ?>

		</div><!-- .pdev-multi-avatars-wrap -->
	<?php }

	/**
	 * Returns an array of user roles that are allowed to edit, publish, or create
	 * posts of the given post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object  $wp_roles
	 * @return array
	 */
	public function get_roles( $post_type ) {
		global $wp_roles;

		$roles = array();
		$type  = get_post_type_object( $post_type );

		// Get the post type object caps.
		$caps = array( $type->cap->edit_posts, $type->cap->publish_posts, $type->cap->create_posts );
		$caps = array_unique( $caps );

		// Loop through the available roles.
		foreach ( $wp_roles->roles as $name => $role ) {

			foreach ( $caps as $cap ) {

				// If the role is granted the cap, add it.
				if ( isset( $role['capabilities'][ $cap ] ) && true === $role['capabilities'][ $cap ] ) {
					$roles[] = $name;
					break;
				}
			}
		}

		return $roles;
	}
}
