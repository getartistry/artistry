<?php

/**
 * Texteditor admin template. If field 'editor-type' option is provided,
 * load the requested editor. Otherwise, load 'wp-editor' by default.
 *
 * @since 1.5.1
 */
if ( ! empty( $field['editor-type'] ) && ( $template = locate_template( "includes/integrations/wp-job-manager/templates/form-fields/admin/{$field['editor-type']}.php" ) ) ) {
	require $template;
} else {
	require locate_template("includes/integrations/wp-job-manager/templates/form-fields/admin/wp-editor.php");
}
