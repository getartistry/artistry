<?php
/**
 * HTML for social login report
 */
?>
<div id="poststuff" class="woocommerce-reports-wide wc-social-login-report">
	<table class="wp-list-table widefat fixed social-registrations">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Provider', 'woocommerce-social-login' ); ?></th>
				<th><?php /* translators: The number of registrations for a provider */ esc_html_e( 'Registrations', 'woocommerce-social-login' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $social_registrations ) ) : ?>
				<?php foreach ( $social_registrations as $data ) : ?>
					<tr>
						<td>
							<span class="chart-legend" style="background-color: <?php echo esc_attr( $data['chart_color'] ); ?>"></span>
							<?php echo esc_html( $data['provider_title'] ); ?>
						</td>
						<td><?php echo esc_html( $data['linked_accounts'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="chart-container">
		<div class="chart-placeholder social-registrations pie-chart" style="height:200px"></div>
	</div>
	<script type="text/javascript">
		jQuery(function(){
			jQuery.plot(
				jQuery('.chart-placeholder.social-registrations'),
				[
					<?php if ( ! empty( $social_registrations ) ) : ?>
					<?php foreach ( $social_registrations as $data ) : ?>
					{
						label: "<?php echo esc_js( $data['provider_title'] ); ?>",
						data:  "<?php echo esc_js( $data['linked_accounts'] ); ?>",
						color: "<?php echo esc_js( $data['chart_color'] ); ?>"
					},
					<?php endforeach; ?>
					<?php endif; ?>
				],
				{
					grid: {
						hoverable: true
					},
					series: {
							pie: {
								show: true,
								radius: 1,
								innerRadius: 0.6,
								label: {
									show: false
								}
							},
							enable_tooltip: true,
							append_tooltip: "<?php echo ' ' . /* translators: Number of linked accounts for a provider. A number will be prepended to this string. Example: 5 linked accounts */ esc_attr__( 'linked accounts', 'woocommerce-social-login' ); ?>"
					},
					legend: {
							show: false
					}
				}
			);

			jQuery('.chart-placeholder.social-registrations').resize();
		});
	</script>
</div>
