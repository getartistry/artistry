<div class="datepicker-wrapper submit-listing-datepicker-wrapper <?php echo isset($field['format']) && $field['format'] == 'datetime' ? 'datetime-picker' : '' ?>">
	<input type="text" class="input-text input-datepicker picker" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>" maxlength="<?php echo ! empty( $field['maxlength'] ) ? esc_attr( $field['maxlength'] ) : ''; ?>" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> />
	<input type="text" class="display-value" readonly>
	<i class="material-icons c-hide">clear_all</i>
</div>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>