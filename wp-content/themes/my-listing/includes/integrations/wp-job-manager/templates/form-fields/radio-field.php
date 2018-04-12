<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$field['default'] = empty( $field['default'] ) ? current( array_keys( $field['options'] ) ) : $field['default'];
$default          = ! empty( $field['value'] ) ? $field['value'] : $field['default'];

foreach ( $field['options'] as $option_key => $value ): $option_id = 'option_' . uniqid(); ?>

	<div class="md-checkbox">
		<input
			type="radio"
			id="<?php echo esc_attr( $option_id ) ?>"
			name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"
			value="<?php echo esc_attr( $option_key ); ?>"
			<?php checked( $default, $option_key ); ?>
		>
		<label for="<?php echo esc_attr( $option_id ) ?>">
			<?php echo esc_html( $value ); ?>
		</label>
	</div>

<?php endforeach; ?>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
