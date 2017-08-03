<?php

class WC_Catalog_Restrictions_Location_Picker_ShortCode {

	private static $instance;

	public static function instance() {
		if ( !self::$instance ) {
			self::$instance = new WC_Catalog_Restrictions_Location_Picker_ShortCode();
		}
		return self::$instance;
	}

	public function __construct() {
		add_shortcode( 'location_picker', array($this, 'do_shortcode') );
	}

	public function do_shortcode() {
		global $woocommerce, $wc_cvo, $wc_catalog_restrictions;
		ob_start();
		?>
		<?php $locations = $woocommerce->countries->get_allowed_countries(); ?>
		<?php $location = $wc_catalog_restrictions->get_location_for_current_user(); ?>
		<?php $changeable = apply_filters( 'wc_location_changeable', $wc_cvo->setting( '_wc_restrictions_locations_changeable' ) == 'yes' ); ?>
		<?php if ( $changeable || !isset( $locations[$location] ) || empty( $location ) ) : ?>

			<div class="location-picker">

				<?php do_action( 'woocommerce_catalog_restrictions_before_choose_location_form' ); ?>
				<form name="location_picker" method="post">
					<?php woocommerce_catalog_restrictions_country_input( $wc_catalog_restrictions->get_location_for_current_user() ); ?>

					<input type="hidden" name="woocommerce_catalog_restrictions_location_picker" value="shortcode" />
					<input type="submit" name="save-location" value="Set Location" />
				</form>

				<?php do_action( 'woocommerce_catalog_restrictions_before_choose_location_form' ); ?>
				<div style="clear:both;"></div>
			</div>

		<?php else : ?>
			<?php printf( __( 'Your Location is <strong>%s</strong>', 'wc_catalog_restrictions' ), $locations[$wc_catalog_restrictions->get_location_for_current_user()] ); ?>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

}
