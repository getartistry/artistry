<?php

global $thepostid;

if ( ! isset( $field['value'] ) ) {
	$field['value'] = get_post_meta( $thepostid, $key, true );
}

if ( empty( $field['name'] ) ) {
	$field['name'] = $key;
}

$_REQUEST[ 'job_id' ] = $thepostid;
?>

<div class="form-field">
	<?php require trailingslashit( CASE27_INTEGRATIONS_DIR ) . "wp-job-manager/templates/form-fields/location-field.php"; ?>
</div>