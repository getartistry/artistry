<?php

namespace AutomateWoo\Fields;

use AutomateWoo\Clean;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Attribute_Term
 */
class Attribute_Term extends Field {

	protected $name = 'term';

	protected $type = 'term';


	function __construct() {
		parent::__construct();
		$this->set_title( __( 'Terms', 'automatewoo' ) );
		$this->classes[] = 'automatewoo-json-search';
	}


	/**
	 * @param $values
	 */
	function render( $values ) {

		$values = Clean::multi_select_values( $values );

		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$this->render_legacy( $values );
			return;
		}

		$display_values = [];

		foreach ( $values as $value ) {
			if ( strstr( $value, '|' ) ) {
				list( $term_id, $taxonomy ) = explode( '|', $value );

				if ( $term = get_term_by( 'id', $term_id, $taxonomy ) ) {
					$display_values[ $value ] = wp_kses_post( $term->name );
				}
			}
		}

		?>

		<select class="<?php echo $this->get_classes() ?>"
				  multiple="multiple"
				  name="<?php echo $this->get_full_name(); ?>[]"
				  data-placeholder="<?php _e( 'Search for a term&hellip;', 'automatewoo' ) ?>"
				  data-action="aw_json_search_attribute_terms"
				  data-pass-sibling="aw_workflow_data[trigger_options][attribute]"
		>
			<?php
			foreach ( $display_values as $key => $value ) {
				echo '<option value="' . $key . '"' . selected( true, true, false ) . '>' . wp_kses_post( $value ) . '</option>';
			}
			?>
		</select>

	<?php
	}



	function render_legacy( $values ) {

		$display_values = [];

		foreach ( $values as $value ) {
			if ( strstr( $value, '|' ) ) {
				list( $term_id, $taxonomy ) = explode( '|', $value );

				if ( $term = get_term_by( 'id', $term_id, $taxonomy ) ) {
					$display_values[ $value ] = wp_kses_post( $term->name );
				}
			}
		}

		?>

		<input type="hidden" class="<?php echo $this->get_classes() ?>"
				 name="<?php echo $this->get_full_name(); ?>"
				 data-placeholder="<?php _e( 'Search for a term&hellip;', 'automatewoo' ) ?>"
				 data-action="aw_json_search_attribute_terms"
				 data-multiple="true"
				 data-pass-sibling="aw_workflow_data[trigger_options][attribute]"
				 data-selected="<?php echo esc_attr( json_encode( $display_values ) ); ?>"
				 value="<?php echo implode( ',', $values ); ?>" >
		<?php


	}

}