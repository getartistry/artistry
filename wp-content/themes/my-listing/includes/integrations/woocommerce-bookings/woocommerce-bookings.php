<?php

namespace MyListing\Integrations;

class WC_Bookings {

	public function activate() {
		if ( ! class_exists( '\\WC_Bookings' ) ) {
			return false;
		}

		add_action( 'case27\listing\cover\buttons\claim-listing', [ $this, 'display_cover_button' ], 30, 2 );
		add_filter( 'wcpl_get_job_packages_args', [ $this, 'exclude_claim_package' ], 30 );
	}

	public function add_cover_button_option( $buttons ) {
		$buttons['claim-listing'] = [
			'action' => 'claim-listing',
			'label' => 'Claim Listing',
		];

		return $buttons;
	}


	public function display_cover_button( $button, $listing ) {
		if ( ! ( $claim_url = \wpjmcl\job_listing\Functions::submit_claim_url( $listing->get_id() ) ) ) {
			return false;
		} ?>
       <li>
           <a class="buttons <?php echo esc_attr( join( ' ', $button['classes'] ) ) ?> medium" href="<?php echo esc_attr( $claim_url ) ?>">
               <?php echo do_shortcode($button['label']) ?>
           </a>
       </li>
	<?php }

}

return new WC_Bookings;
