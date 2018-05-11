<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "categories".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'categories', 'cp_v2_categories_settings_field' );
}

/**
 * Function Name: cp_v2_categories_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_categories_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	ob_start();
	?>
<select name="<?php echo esc_attr( $input_name ); ?>" id="cp_<?php echo esc_attr( $input_name ); ?>" class="select2-cat-dropdown form-control cp-input <?php echo esc_attr( 'cp-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class ); ?>" multiple="multiple" style="width:260px;"> 
	<?php
	$args = array(
		'public'   => true,
		'_builtin' => true,
	);

	// names or objects, note names is the default.
	$output     = 'objects';
	$operator   = 'and';
	$taxonomies = get_taxonomies( $args, $output, $operator );

	foreach ( $taxonomies as $taxonomy ) {
		?>
		<optgroup label="<?php echo ucwords( $taxonomy->label ); ?>">
		<?php
		$terms = get_terms(
			$taxonomy->name, array(
				'orderby'    => 'count',
				'hide_empty' => 0,
			)
		);

		foreach ( $terms as $term ) {
		?>
		<?php
		$val_arr  = explode( ',', $value );
		$selected = ( in_array( $term->term_id, $val_arr ) ) ? 'selected="selected"' : '';
		?>
	<option <?php echo $selected; ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		<?php
		}
	}
	?>
	</optgroup>
</select>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('select.select2-cat-dropdown').select2({
		placeholder: "<?php _e( 'Select Categories', 'convertpro' ); ?>",
	});
});
</script>
	<?php
	return ob_get_clean();
}
