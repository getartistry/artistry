<input
	type="hidden" class="input-text"
	id="<?php echo esc_attr( $key ); ?>"
	name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"
	value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>"
	>