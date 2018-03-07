<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Dashboard_Widget_Key_Figures
 */
class Dashboard_Widget_Key_Figures extends Dashboard_Widget {

	function __construct() {
		$this->id = 'key-figures';
	}


	/**
	 * @return array
	 */
	function get_figures() {

		$figures = [];

		if ( ! $this->date_to || ! $this->date_from )
			return [];

		$unsubscribes = $this->controller->get_unsubscribed_customers();
		$carts_count = $this->controller->get_carts_count();
		$guests_count = $this->controller->get_guests_count();
		$queued_count = $this->controller->get_queued_count();

		$figures[] = [
			'name' => __( 'workflows queued', 'automatewoo' ),
			'value' => $queued_count,
			'link' => Admin::page_url( 'queue' )
		];

		$figures[] = [
			'name' => __( 'active carts', 'automatewoo' ),
			'value' => $carts_count,
			'link' => Admin::page_url( 'carts' )
		];

		$figures[] = [
			'name' => __( 'guests captured', 'automatewoo' ),
			'value' => $guests_count,
			'link' => Admin::page_url( 'guests' )
		];

		$figures[] = [
			'name' => __( 'unsubscribes', 'automatewoo' ),
			'value' => $unsubscribes ? count( $unsubscribes ) : 0,
			'link' => Admin::page_url( 'unsubscribes' )
		];


		return apply_filters('automatewoo/dashboard/key_figures', $figures );
	}


	function output_content() {

		$figures = $this->get_figures();

		?>

		<div class="automatewoo-dashboard__figures">
			<?php foreach ( $figures as $figure ): ?>

				<a href="<?php echo esc_url( $figure['link'] ) ?>" class="automatewoo-dashboard__figure">
					<div class="automatewoo-dashboard__figure-value"><?php echo $figure['value'] ?></div>
					<div class="automatewoo-dashboard__figure-name"><?php echo esc_attr( $figure['name'] ) ?></div>
				</a>

			<?php endforeach; ?>
		</div>

		<?php
	}

}

return new Dashboard_Widget_Key_Figures();
