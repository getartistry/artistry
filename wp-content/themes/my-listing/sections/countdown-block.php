<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'countdown_date' => '',
			'wrapper_class' => 'block-element grid-item reveal',
			'ref' => '',
			'timezone' => c27()->get_timezone(),
		], $data);

	if ( ! strtotime( $data['countdown_date'] ) ) {
		return false;
	}

	$end_date = new \DateTime( $data['countdown_date'], $data['timezone'] );
	$remain = $end_date->diff( new \DateTime );
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element countdown-box countdown-block">
		<div class="pf-head">
			<div class="title-style-1 title-style-<?php echo esc_attr( $data['icon_style'] ) ?>">
				<?php if ($data['icon_style'] != 3): ?>
					<?php echo c27()->get_icon_markup($data['icon']) ?>
				<?php endif ?>
				<h5><?php echo esc_html( $data['title'] ) ?></h5>
			</div>
		</div>
		<div class="pf-body">
			<ul class="countdown-list">
				<li>
					<p><?php echo $end_date && $remain->invert ? sprintf('%02d', $remain->format('%a')) : '00' ?></p>
					<span><?php _e( 'Days', 'my-listing' ) ?></span>
				</li>
				<li>
					<p><?php echo $end_date && $remain->invert ? $remain->format('%H') : '00' ?></p>
					<span><?php _e( 'Hours', 'my-listing' ) ?></span>
				</li>
				<li>
					<p><?php echo $end_date && $remain->invert ? $remain->format('%I') : '00' ?></p>
					<span><?php _e( 'Minutes', 'my-listing' ) ?></span>
				</li>
			</ul>
		</div>
	</div>
</div>
