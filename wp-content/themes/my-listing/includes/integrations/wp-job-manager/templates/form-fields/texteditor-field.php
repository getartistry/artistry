<?php

/**
 * Texteditor frontend template. If field 'editor-type' option is provided,
 * load the requested editor. Otherwise, load 'wp-editor' by default.
 *
 * @since 1.5.1
 */
if ( ! empty( $field['editor-type'] ) && ( $template = locate_job_manager_template( "form-fields/{$field['editor-type']}-field.php" ) ) ) {
	require $template;
} else {
	require locate_job_manager_template( 'form-fields/wp-editor-field.php' );
}
