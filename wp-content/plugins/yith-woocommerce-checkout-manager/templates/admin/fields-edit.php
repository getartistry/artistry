<?php
/**
 * Admin View: Fields Table Edit Form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$field_types = ywccp_get_field_type();

?>

<div id="ywccp_field_add_edit_form" style="display: none;">
	<form>
		<table>
			<tr class="remove_default">
				<td class="label"><?php _e( 'Name', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_name"/></td>
			</tr>
			<tr class="remove_default">
				<td class="label"><?php _e( 'Type', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td>
					<select name="field_type">
						<?php foreach( $field_types as $value => $label ): ?>
							<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="label"><?php _e( 'Label', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_label"/></td>
			</tr>
			<tr data-hide="checkbox,radio,heading">
				<td class="label"><?php _e( 'Placeholder', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_placeholder"/></td>
			</tr>
			<?php if( get_option('ywccp-enable-tooltip-check') == 'yes' ) : ?>
				<tr>
					<td class="label"><?php _e( 'Tooltip', 'yith-woocommerce-checkout-manager' ) ?></td>
					<td><input type="text" name="field_tooltip"/></td>
				</tr>
			<?php endif; ?>
			<tr class="remove_default" data-hide="text,password,tel,textarea,datepicker,checkbox,heading,timepicker">
				<td class="label"><?php _e( 'Options', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_options" placeholder="<?php _e( 'Seperate options with pipes (|) and key from value using (::). Es. key::value|', 'yith-woocommerce-checkout-manager' ); ?>" /></td>
			</tr>
			<?php if( isset( $positions ) && is_array( $positions ) ) : ?>
				<tr>
					<td class="label"><?php _e( 'Position', 'yith-woocommerce-checkout-manager' ) ?></td>
					<td>
						<select name="field_position"/>
							<?php foreach( $positions as $pos => $pos_label ): ?>
									<option value="<?php echo $pos ?>"><?php echo $pos_label ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="label"><?php _e( 'Class', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_class" placeholder="<?php _e( 'Seperate classes with commas', 'yith-woocommerce-checkout-manager' ); ?>"/></td>
			</tr>
			<tr data-hide="heading">
				<td class="label"><?php _e( 'Label class', 'yith-woocommerce-checkout-manager' ) ?></td>
				<td><input type="text" name="field_label_class" placeholder="<?php _e( 'Seperate classes with commas', 'yith-woocommerce-checkout-manager' ); ?>"/></td>
			</tr>
			<?php if( isset( $validation ) && is_array( $validation ) ) : ?>
				<tr data-hide="heading">
					<td class="label"><?php _e( 'Validation', 'yith-woocommerce-checkout-manager' ) ?></td>
					<td>
						<select name="field_validate"/>
						<?php foreach( $validation as $valid_rule => $valid_label ): ?>
							<option value="<?php echo $valid_rule ?>"><?php echo $valid_label ?></option>
						<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<?php endif; ?>
			<tr data-hide="heading">
				<td>&nbsp;</td>
				<td>
					<input type="checkbox" name="field_required" value="1" checked/>
					<label for="field_required"><?php _e( 'Required', 'yith-woocommerce-checkout-manager' ) ?></label>
				</td>
			</tr>
			<tr class="remove_default" data-hide="heading">
				<td>&nbsp;</td>
				<td>
					<input type="checkbox" name="field_show_in_email" value="1" checked/>
					<label for="field_show_in_email"><?php _e( 'Display in emails', 'yith-woocommerce-checkout-manager' ) ?></label><br/>

					<input type="checkbox" name="field_show_in_order" value="1" checked/>
					<label for="field_show_in_order"><?php _e( 'Display in Order Detail Pages', 'yith-woocommerce-checkout-manager' ) ?></label>
				</td>
			</tr>
		</table>
	</form>
</div>
