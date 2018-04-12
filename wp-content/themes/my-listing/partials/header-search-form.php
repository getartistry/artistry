<?php
	$data = c27()->merge_options([
			'placeholder' => __( 'Search...', 'my-listing' ),
		], $data);
?>

<?php if (class_exists('WP_Job_Manager')): ?>

	<?php c27()->get_partial( 'quick-search', [
			'instance-id' => 'c27-header-search-form',
			'placeholder' => $data['placeholder'],
			'align' => 'left',
			]) ?>

<?php else: ?>

	<div>
		<form action="<?php echo esc_url( home_url('/') ) ?>" method="GET">
			<div class="dark-forms header-search">
				<i class="material-icons">search</i>
				<input type="search" placeholder="<?php echo esc_attr( $data['placeholder'] ) ?>" value="<?php echo isset($_GET['s']) ? esc_attr( sanitize_text_field($_GET['s']) ) : '' ?>" name="s">
				<div class="instant-results"></div>
			</div>
		</form>
	</div>

<?php endif ?>
