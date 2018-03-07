<?php

namespace AutomateWoo;

/**
 * @var $controller Admin\Controllers\Guests
 * @var Guest $guest
 * @var Customer $customer
 */

?>


<div class="wrap automatewoo-page automatewoo-page--guest-details">

	<h1><?php echo $controller->get_heading() ?></h1>

	<?php $controller->output_messages(); ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<div id="postbox-container-1"></div>

			<div id="postbox-container-2">

				<div class="postbox automatewoo-metabox no-drag">
					<div class="inside">

						<table class="automatewoo-table automatewoo-table--two-column">

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Guest ID', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col">#<?php echo $guest->get_id() ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Name', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo $customer->get_full_name() ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Email', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo make_clickable( $customer->get_email() ) ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Address', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo $customer->get_formatted_billing_address( false ) ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Phone', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo $customer->get_billing_phone() ?></td>
							</tr>

                            <tr>
                                <td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Last active', 'automatewoo' ) ?></td>
                                <td class="automatewoo-table__col"><?php echo Format::datetime( $guest->get_date_last_active() ); ?></td>
                            </tr>

                            <tr>
                                <td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Created', 'automatewoo' ) ?></td>
                                <td class="automatewoo-table__col"><?php echo Format::datetime( $guest->get_date_created() ); ?></td>
                            </tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Order count', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo $customer->get_order_count() ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'Total spent', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo wc_price( $customer->get_total_spent() ) ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'IP address', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo $guest->get_ip() ?></td>
							</tr>

							<tr>
								<td class="automatewoo-table__col automatewoo-table__col--label"><?php _e( 'HTTP user agent', 'automatewoo' ) ?></td>
								<td class="automatewoo-table__col"><?php echo esc_attr( $guest->get_meta( 'user_agent' ) ) ?></td>
							</tr>

						</table>

					</div>

				</div>

			</div>

		</div>
	</div>

</div>
