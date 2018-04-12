<div class="repeater social-networks-repeater" data-list="<?php echo htmlspecialchars(json_encode(isset($field['value']) ? $field['value'] : []), ENT_QUOTES, 'UTF-8') ?>">
	<div data-repeater-list="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) ) ?>">
		<div data-repeater-item>
			<select name="network" class="ignore-custom-select">
				<option value=""><?php _ex( 'Select Network', 'Listing social networks', 'my-listing' ) ?></option>
				<?php foreach ( (array) mylisting()->schemes()->get('social-networks') as $network ): ?>
					<option value="<?php echo esc_attr( $network['key'] ) ?>"><?php echo esc_attr( $network['name'] ) ?></option>
				<?php endforeach ?>
			</select>
			<input type="text" name="url" placeholder="<?php esc_attr_e( 'Enter URL...', 'my-listing' ) ?>">
			<button data-repeater-delete type="button" class="buttons button-5 icon-only small"><i class="material-icons delete"></i></button>
		</div>
	</div>
	<input data-repeater-create type="button" value="<?php esc_attr_e( 'Add', 'my-listing' ) ?>">
</div>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
