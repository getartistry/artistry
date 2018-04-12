<?php

if ( $field['slug'] == 'job_description' && ! empty( $_REQUEST[ 'job_id' ] ) && ( $postID = absint( $_REQUEST[ 'job_id' ] ) ) ) {
	$field['value'] = get_post_meta( $postID, '_job_description', true );
}

if ( ! empty( $field['editor-controls'] ) && in_array( $field['editor-controls'], [ 'basic', 'advanced', 'all' ] ) ) {
	$controls = $field['editor-controls'];
} else {
	$controls = 'basic';
}

$editor = [
	'textarea_name' => $key,
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

wp_editor( ( isset( $field['value'] ) ? wp_kses_post( $field['value'] ) : '' ), $key, $editor );
if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>
