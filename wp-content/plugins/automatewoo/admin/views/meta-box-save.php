<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @var Workflow $workflow
 */

global $post, $action;

$post_type = 'aw_workflow';
$post_type_object = get_post_type_object($post_type);
$can_publish = current_user_can($post_type_object->cap->publish_posts);


?>
<div class="submitbox" id="submitpost">

	<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
	<div style="display:none;">
		<?php submit_button( __( 'Save' ), '', 'save' ); ?>
	</div>

	<table class="automatewoo-table">

		<tr class="automatewoo-table__row">
			<td class="automatewoo-table__col">

				<div class="automatewoo-input-group">

					<label class="automatewoo-input-group__addon automatewoo-input-group__addon--pad-right automatewoo-label--weight-normal "><?php _e( 'Status:', 'automatewoo' ) ?></label>

					<div class="automatewoo-input-group__input">
						<?php
						if ( $workflow ) {
							$status = $workflow->is_active() ? 'active': 'disabled';
						}
						else {
							$status = 'active';
						}

						( new Fields\Select( false ) )
							->set_name('workflow_status')
							->set_options([
								'active' => __('Active', 'automatewoo'),
								'disabled' => __('Disabled', 'automatewoo')
							])
							->render( $status );
						?>
					</div>
				</div>

			</td>
		</tr>

		<?php if ( $post->post_status !== 'auto-draft' ): ?>
			<tr class="automatewoo-table__row">
				<td class="automatewoo-table__col">
					<div>
						<?php printf( __( 'Created: <b>%s</b>', 'automatewoo' ), Format::datetime( $post->post_date , 0, false ) ); // post_date_gmt is blank after creating a new post? ?>
					</div>
				</td>
			</tr>
		<?php endif; ?>

	</table>


	<div id="major-publishing-actions">
		<div id="delete-action">
			<?php
			if ( current_user_can( "delete_post", $post->ID ) ) {
				if ( !EMPTY_TRASH_DAYS )
					$delete_text = __('Delete Permanently');
				else
					$delete_text = __('Move to Trash');
				?>
				<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
			} ?>
		</div>

		<div id="publishing-action">
			<span class="spinner"></span>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Save' ) ?>" />
			<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Save' ) ?>" />
		</div>
		<div class="clear"></div>
	</div>
</div>
