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

$weekdays_short = [
	__( 'Mon', 'my-listing' ), __( 'Tue', 'my-listing' ), __( 'Wed', 'my-listing' ),
	__( 'Thu', 'my-listing' ), __( 'Fri', 'my-listing' ), __( 'Sat', 'my-listing' ), __( 'Sun', 'my-listing' )
];

$daystatuses = [
	'enter-hours' => __( 'Enter hours', 'my-listing' ),
	'open-all-day' => __( 'Open all day', 'my-listing' ),
	'closed-all-day' => __( 'Closed all day', 'my-listing' ),
	'by-appointment-only' => __( 'By appointment only', 'my-listing' ),
];

$schedule = new CASE27\Classes\WorkHours( ! empty( $field['value'] ) ? (array) $field['value'] : [] );
?>

<div class="form-field c27-work-hours c27-tabs">
	<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>: <?php if ( ! empty( $field['description'] ) ) : ?><span class="tips" data-tip="<?php echo esc_attr( $field['description'] ); ?>">[?]</span><?php endif; ?></label>

	<div class="tab-switches">
		<?php $i = 0; foreach ( $schedule->get_schedule() as $weekday ): ?>
			<a href="#work_hours_day_<?php echo esc_attr( $weekday['day'] ) ?>" class="tab-switch <?php echo $weekday['day'] == 'Monday' ? 'active' : '' ?>">
			   <span class="hidden-lg"><?php echo esc_html( $weekdays_short[ $i ] ) ?></span>
			</a>
		<?php $i++; endforeach ?>
	</div>
	<div class="tab-content">
		<?php foreach ( $schedule->get_schedule() as $weekday ):
				$daykey = ( isset( $field['name'] ) ? $field['name'] : $key ) . "[{$weekday['day']}]";
				?>
			<div class="day-wrapper tab <?php echo $weekday['day'] == 'Monday' ? 'active' : '' ?> <?php echo esc_attr( 'day-status-' . $weekday['status'] ) ?>" id="work_hours_day_<?php echo esc_attr( $weekday['day'] ) ?>">
				<div class="repeater work-hours-repeater" data-list="<?php echo htmlspecialchars( json_encode( $schedule->get_day_ranges( $weekday['day'] ) ), ENT_QUOTES, 'UTF-8' ) ?>">
					<div data-repeater-list="<?php echo esc_attr( $daykey ) ?>">

						<div class="work-hours-type">
							<?php foreach ( $daystatuses as $daystatus_key => $daystatus_label ): ?>
								<div class="md-checkbox">
									<input
									id="day_<?php echo esc_attr( $weekday['day'] . '_' . $daystatus_key ) ?>"
									type="radio" name="<?php echo esc_attr( $daykey ) ?>[status]"
									value="<?php echo esc_attr( $daystatus_key ) ?>"
									<?php checked( $daystatus_key, $weekday['status'] ) ?>
									>
									<label for="day_<?php echo esc_attr( $weekday['day'] . '_' . $daystatus_key ) ?>"><?php echo esc_attr( $daystatus_label ) ?></label>
								</div>
							<?php endforeach ?>
						</div>

						<li class="day day-hour-ranges" data-repeater-item>
							<select name="from" placeholder="<?php esc_attr_e( 'From', 'my-listing' ) ?>">
								<option value=""><?php esc_html_e( 'From', 'my-listing' ) ?></option>
								<?php foreach (range(0, 86399, 900) as $time): ?>
									<option value="<?php echo esc_attr( gmdate( 'H:i', $time) ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
								<?php endforeach ?>
							</select>

							<select name="to" placeholder="<?php esc_attr_e( 'To', 'my-listing' ) ?>">
								<option value=""><?php esc_html_e( 'To', 'my-listing' ) ?></option>
								<?php foreach (range(0, 86399, 900) as $time): ?>
									<option value="<?php echo esc_attr( gmdate( 'H:i', $time) ) ?>"><?php echo esc_html( gmdate( get_option( 'time_format' ), $time ) ) ?></option>
								<?php endforeach ?>
							</select>

							<button data-repeater-delete type="button" class="button button-small"><i class="material-icons delete"></i></button>
						</li>
					</div>

					<input data-repeater-create type="button" class="button add-row-button" value="<?php esc_attr_e( 'Add Hours', 'my-listing' ) ?>">
				</div>
			</div>
		<?php endforeach ?>
	</div>

	<div class="timezone-pick">
		<?php
		$timezones = timezone_identifiers_list();
		$default_timezone = date_default_timezone_get();
		$wp_timezone = get_option('timezone_string');
		$listing_timezone = isset($field['value']) && isset($field['value']['timezone']) && in_array( $field['value']['timezone'], $timezones ) ? $field['value']['timezone'] : false;

		$current_timezone = ( $listing_timezone ?: ( $wp_timezone ?: $default_timezone ) );
		?>
		<label><?php _e( 'Timezone', 'my-listing' ) ?></label>
		<select name="<?php echo esc_attr( (isset($field['name']) ? $field['name'] : $key) . "[timezone]" ); ?>" placeholder="<?php esc_attr_e( 'Timezone', 'my-listing' ) ?>" class="custom-select">
			<?php foreach ($timezones as $timezone): ?>
				<option value="<?php echo esc_attr( $timezone ) ?>" <?php echo $timezone == $current_timezone ? 'selected="selected"' : '' ?>><?php echo esc_html( $timezone ) ?></option>
			<?php endforeach ?>
		</select>
	</div>
</div>

