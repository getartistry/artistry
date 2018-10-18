<?php
/**
 * Forecast weather template.
 */
$settings = $this->get_settings();
$data     = $this->weather_data;

$forecast_data = $data['forecast'];

if ( isset( $settings['show_current_weather'] ) && '' === $settings['show_current_weather'] ) {
	array_unshift( $forecast_data, array(
		'code'     => $data['current']['code'],
		'desc'     => $data['current']['desc'],
		'temp_min' => $data['current']['temp_min'],
		'temp_max' => $data['current']['temp_max'],
		'week_day' => $data['current']['week_day'],
	) );
}

$forecast_days = ! empty( $settings['forecast_count']['size'] ) ? abs( $settings['forecast_count']['size'] ) : 5;
$forecast_days = ( $forecast_days <= 5 ) ? $forecast_days : 5;
?>
<div class="jet-weather__forecast"><?php
	for ( $i = 0; $i < $forecast_days; $i ++ ) { ?>
		<div class="jet-weather__forecast-item">
			<div class="jet-weather__forecast-day"><?php echo $forecast_data[ $i ]['week_day']; ?></div>
			<div class="jet-weather__forecast-icon" title="<?php echo esc_attr( $forecast_data[ $i ]['desc'] ); ?>"><?php echo $this->get_weather_svg_icon( $forecast_data[ $i ]['code'] ); ?></div>
			<div class="jet-weather__forecast-max-temp"><?php echo $this->get_weather_temp( $forecast_data[ $i ]['temp_max'] ); ?></div>
			<div class="jet-weather__forecast-min-temp"><?php echo $this->get_weather_temp( $forecast_data[ $i ]['temp_min'] ); ?></div>
		</div>
	<?php }
?></div>
