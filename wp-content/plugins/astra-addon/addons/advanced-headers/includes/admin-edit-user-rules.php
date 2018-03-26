<?php
/**
 * Users rules table
 *
 * @package Astra Addon
 */

?>


<script type="text/html" id="tmpl-ast-advanced-headers-saved-user-rule">
	<div class="ast-advanced-headers-saved-user-rule ast-advanced-headers-saved-rule">
		<div class="ast-advanced-headers-saved-rule-select">
			<select name="ast-advanced-headers-user-rule[]"  class="ast-advanced-headers-user-rule">
				<option value=""><?php _e( 'Choose...', 'astra-addon' ); ?></option>
				<?php foreach ( $rules as $group_key => $group_data ) : ?>
				<optgroup label="<?php echo $group_data['label']; ?>">
					<?php foreach ( $group_data['rules'] as $rule_key => $rule_data ) : ?>
								<option value='<?php echo json_encode( $rule_data ); ?>' data-rule="<?php echo $rule_data['type'] . ':' . $rule_data['id']; ?>"><?php echo $rule_data['label']; ?></option>
					<?php endforeach; ?>
				</optgroup>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="ast-advanced-headers-remove-rule-button">
			<i class="ast-advanced-headers-remove-user-rule ast-advanced-headers-remove-rule dashicons dashicons-dismiss"></i>
		</div>
	</div>
</script>
