<?php

global $thepostid;

if ( ! isset( $field['value'] ) ) {
	$field['value'] = get_post_meta( $thepostid, $key, true );
}
if ( ! empty( $field['name'] ) ) {
	$name = $field['name'];
} else {
	$name = $key;
}
if ( ! empty( $field['classes'] ) ) {
	$classes = implode( ' ', is_array( $field['classes'] ) ? $field['classes'] : array( $field['classes'] ) );
} else {
	$classes = '';
}

// dump($field['value']);
?>
<div class="form-field">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
	<div class="repeater" data-list="<?php echo htmlspecialchars(json_encode(isset($field['value']) ? $field['value'] : []), ENT_QUOTES, 'UTF-8') ?>">
		<div data-repeater-list="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) ) ?>">
			<div data-repeater-item>
				<select name="network">
					<option value=""><?php _ex( 'Select Network', 'Listing social networks', 'my-listing' ) ?></option>
					<?php foreach ( (array) mylisting()->schemes()->get('social-networks') as $network ): ?>
						<option value="<?php echo esc_attr( $network['key'] ) ?>"><?php echo esc_attr( $network['name'] ) ?></option>
					<?php endforeach ?>
				</select>
				<input type="text" name="url" placeholder="Enter URL...">
				<button data-repeater-delete type="button" class="button button-small"><i class="material-icons delete"></i></button>
			</div>
		</div>
		<input data-repeater-create type="button" value="Add" class="button">
	</div>
</div>