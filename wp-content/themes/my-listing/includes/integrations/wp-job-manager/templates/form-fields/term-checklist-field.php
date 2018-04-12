<?php
/**
 * Term checklist field frontend template.
 *
 * @since 1.0.0
 */

// To maintain backward compatibility, transform every terms field to a 'terms-select'.
if ( $field['type'] !== 'term-select' ) {
	$field['type'] = 'term-select';
	return require locate_job_manager_template( 'form-fields/term-select-field.php' );
}
?>

<ul class="c27-term-checklist">

	<?php foreach ( $terms as $term ): $term = new \CASE27\Classes\Term( $term ) ?>

		<li class="c27-term">
			<div class="md-checkbox">
				<input
					name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>[]"
					type="checkbox"
					id="<?php echo esc_attr( 'term-checklist-' . $term->get_id() ) ?>"
					value="<?php echo esc_attr( $term->get_id() ) ?>"
					<?php checked( in_array( $term->get_id(), $selected ), true ) ?>
				>
				<label for="<?php echo esc_attr( 'term-checklist-' . $term->get_id() ) ?>"> <?php echo esc_attr( $term->get_name() ) ?></label>
			</div>
		</li>

	<?php endforeach ?>

</ul>

<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>