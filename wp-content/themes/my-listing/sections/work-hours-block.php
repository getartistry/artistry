<?php
	$data = c27()->merge_options([
			'icon' => '',
			'icon_style' => 1,
			'title' => '',
			'hours' => [],
			'wrapper_class' => 'grid-item reveal',
			'ref' => '',
		], $data);

	$schedule = new CASE27\Classes\WorkHours( $data['hours'] );

	if ( $schedule->is_empty() ) {
		return false;
	}
?>

<div class="<?php echo esc_attr( $data['wrapper_class'] ) ?>">
	<div class="element work-hours-block">
		<div class="pf-head" data-toggle="collapse" data-target="#open-hours">
			<div class="title-style-1">
				<?php echo c27()->get_icon_markup( $data['icon'] ) ?>
				<h5><span class="<?php echo esc_attr( $schedule->get_status() ) ?> work-hours-status"><?php echo esc_html( $schedule->get_message() ) ?></span></h5>
				<div class="timing-today">
					<?php echo $schedule->get_todays_schedule() ?>
					<span class="mi expand_more" data-toggle="tooltip" data-placement="top" title="<?php esc_attr_e( 'Toggle weekly schedule', 'my-listing' ) ?>"></span>
				</div>
			</div>
		</div>
		<div id="open-hours" class="pf-body collapse">
			<ul class="extra-details">
				<?php foreach ( $schedule->get_schedule() as $weekday ): ?>
					<li>
						<p class="item-attr"><?php echo esc_html( $weekday['day_l10n'] ) ?></p>
						<p class="item-property"><?php echo $schedule->get_day_schedule( $weekday['day'] ) ?></p>
					</li>
				<?php endforeach ?>

				<?php if ( ! empty( $data['hours']['timezone'] ) ):
					$localTime = new DateTime( 'now', new DateTimeZone( $data['hours']['timezone'] ) );
					?>
					<p class="work-hours-timezone">
						<em><?php printf( __( '%s local time', 'my-listing' ), $localTime->format( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ) ?></em>
					</p>
				<?php endif ?>
			</ul>
		</div>
	</div>
</div>
