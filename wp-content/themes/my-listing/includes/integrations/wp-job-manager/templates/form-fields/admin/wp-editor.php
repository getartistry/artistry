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

if ( ! empty( $field['editor-controls'] ) && in_array( $field['editor-controls'], [ 'basic', 'advanced', 'all' ] ) ) {
	$controls = $field['editor-controls'];
} else {
	$controls = 'basic';
}

$editor = [
	'textarea_name' => $name,
	'textarea_rows' => 10,
];

if ( $controls == 'basic' ) {
	$editor['media_buttons'] = false;
	$editor['quicktags'] = false;
	$editor['tinymce'] = [
		'plugins'                       => 'lists,paste,tabfocus,wplink,wordpress',
		'paste_as_text'                 => true,
		'paste_auto_cleanup_on_paste'   => true,
		'paste_remove_spans'            => true,
		'paste_remove_styles'           => true,
		'paste_remove_styles_if_webkit' => true,
		'paste_strip_class_attributes'  => true,
		'toolbar1'                      => 'bold,italic,|,bullist,numlist,|,link,unlink,|,undo,redo',
		'toolbar2'                      => '',
		'toolbar3'                      => '',
		'toolbar4'                      => ''
	];
}

if ( $controls == 'advanced' ) {
	$editor['media_buttons'] = false;
	$editor['quicktags'] = false;
}

// Print another empty form-field div, so when the description editor is made
// full width, the order of items after it is preserved in css when using :even and :odd
if ( $name === '_job_description' ): ?>
	<div class="form-field"></div>
<?php endif; ?>

<div class="form-field <?php echo esc_attr( "form-field-{$name}" ) ?>">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>
	<?php wp_editor( wp_kses_post( $field['value'] ), $key, $editor ) ?>
</div>
