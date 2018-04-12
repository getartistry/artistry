<?php
if ( is_numeric( $value ) ) {
	$image_src = wp_get_attachment_image_src( absint( $value ) );
	$image_src = $image_src ? $image_src[0] : '';
} else {
	$image_src = $value;
}

$extension = ! empty( $extension ) ? $extension : substr( strrchr( $image_src, '.' ), 1 );

if ( in_array( $extension, array( 'jpg', 'gif', 'png', 'jpeg', 'jpe' ) ) ) : ?>
<?php $image_src = job_manager_get_resized_image( $image_src, 'medium' ) ?: $image_src; ?>
	<div class="job-manager-uploaded-file c27-uploaded-image">
		<span class="job-manager-uploaded-file-preview"><img src="<?php echo esc_url( $image_src ); ?>" class="img-style-1" /> <a class="job-manager-remove-uploaded-file" href="#"><i class="material-icons">delete</i></a></span>
		<input type="hidden" class="input-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
	</div>
<?php else : ?>
	<div class="job-manager-uploaded-file c27-uploaded-file">
		<span class="job-manager-uploaded-file-name">
			<i class="mi insert_drive_file"></i>
			<code><?php echo esc_html( basename( $image_src ) ); ?></code>
			<a class="job-manager-remove-uploaded-file" href="#"><i class="material-icons">delete</i></a>
		</span>
		<input type="hidden" class="input-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
	</div>
<?php endif; ?>
