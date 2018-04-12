<?php
/**
 * Term single select field frontend template.
 *
 * @since 1.0.0
 */

// To maintain backward compatibility, transform every terms field to a 'terms-select'.
if ( $field['type'] !== 'term-select' ) {
	$field['type'] = 'term-select';
	return require locate_job_manager_template( 'form-fields/term-select-field.php' );
}


// Select only supports 1 value
if ( is_array( $selected ) ) {
	$selected = current( $selected );
}
?>

<div class="c27-term-multiselect">
	<select
		class="custom-select"
		name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ) ?>"
		id="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ) ?>"
		<?php if ( ! empty( $field['required'] ) ) echo 'required="required"'; ?>
		<?php if ( ! empty( $field['placeholder'] ) ) echo 'placeholder="' . esc_attr( $field['placeholder'] ) . '"'; ?>
	>
		<?php foreach ( $terms as $term ): $term = new \CASE27\Classes\Term( $term ) ?>
			<option
				value="<?php echo $term->get_id() ?>"
				<?php selected( $term->get_id() == $selected, true ) ?>
			>
				<?php echo esc_attr( $term->get_full_name() ) ?>
			</option>
		<?php endforeach ?>

	</select>
</div>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
