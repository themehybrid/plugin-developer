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
class PDEV_Fields_Control_Parent extends PDEV_Fields_Control {

	/**
	 * Outputs the content template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template( $post_id ) {

		$post = get_post( $post_id );

		$plugins = get_posts(
			array(
				'post_type'      => pdev_get_plugin_post_type(),
				'post_status'    => 'any',
				'post__not_in'   => array( $post_id ),
				'posts_per_page' => -1,
				'post_parent'    => 0,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => array( 'ID', 'post_title' )
			)
		); ?>

		<label>
			<?php if ( $this->label ) : ?>
				<span class="pdev-fields-label"><?php echo esc_html( $this->label ); ?></span>
				<br />
			<?php endif; ?>

			<select name="parent_id" id="parent_id">

				<option value="0" <?php selected( 0, $post->post_parent ); ?>></option>

				<?php if ( $plugins ) : ?>

					<?php foreach ( $plugins as $plugin ) : ?>

						<option value="<?php echo esc_attr( $plugin->ID ); ?>" <?php selected( $plugin->ID, $post->post_parent ); ?>><?php echo esc_html( $plugin->post_title ); ?></option>

					<?php endforeach; ?>

				<?php endif; ?>

			</select>

			<?php if ( $this->description ) : ?>
				<br />
				<span class="pdev-fields-description description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
	<?php }
}
