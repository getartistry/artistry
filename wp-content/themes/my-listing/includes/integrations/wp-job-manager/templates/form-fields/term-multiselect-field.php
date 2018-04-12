<?php
/**
 * Term multiselect field frontend template.
 *
 * @since 1.0.0
 */

// To maintain backward compatibility, transform every terms field to a 'terms-select'.
if ( $field['type'] !== 'term-select' ) {
	$field['type'] = 'term-select';
	return require locate_job_manager_template( 'form-fields/term-select-field.php' );
}

$used_terms = [];
if ( $listing_id ) {
	$used_terms = array_filter( (array) wp_get_object_terms(
		$listing_id,
		$field['taxonomy'],
		['orderby' => 'term_order', 'order' => 'ASC']
	) );
	$used_terms = array_column( $used_terms, 'term_id' );
}

?>
<input type="hidden" name="c27_<?php echo esc_attr( $field['taxonomy'] ) ?>_values" value="<?php echo htmlspecialchars(json_encode($used_terms), ENT_QUOTES, 'UTF-8') ?>">

<div class="c27-term-multiselect">
	<select
		name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ) ?>[]"
		id="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ) ?>"
		multiple="multiple"
		<?php if ( ! empty( $field['required'] ) ) echo 'required="required"'; ?>
		<?php if ( ! empty( $field['placeholder'] ) ) echo 'placeholder="' . esc_attr( $field['placeholder'] ) . '"'; ?>
	>
		<?php foreach ( $terms as $term ): $term = new \CASE27\Classes\Term( $term ) ?>
			<option
				value="<?php echo $term->get_id() ?>"
				<?php selected( in_array( $term->get_id(), $selected ), true ) ?>
			>
				<?php echo esc_attr( $term->get_full_name() ) ?>
			</option>
		<?php endforeach ?>

	</select>
</div>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
