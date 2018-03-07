<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Taxonomy_Term
 */
class Taxonomy_Term extends Field {

	protected $name = 'term';

	protected $type = 'term';


	function __construct() {
		parent::__construct();
		$this->classes[] = 'automatewoo-json-search';
		$this->set_title( __( 'Term', 'automatewoo' ) );
	}


	/**
	 * @param $value
	 */
	function render( $value ) {

		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			$this->render_legacy( $value );
			return;
		}

		$term = false;

		if ( strstr( $value, '|' ) ) {
			list( $term_id, $taxonomy ) = explode( '|', $value );
			$term = get_term_by( 'id', $term_id, $taxonomy );
		}

		?>

		<select type="hidden" class="<?php echo $this->get_classes() ?>"
			 name="<?php echo $this->get_full_name(); ?>"
			 data-placeholder="<?php _e( 'Search for a term&hellip;', 'automatewoo' ); ?>"
			 data-action="aw_json_search_taxonomy_terms"
			 data-pass-sibling="aw_workflow_data[trigger_options][taxonomy]"
		>
			<?php
			if ( is_object( $term ) ) {
				echo '<option value="' . $term->term_id . '"' . selected( true, true, false ) . '>' . wp_kses_post( $term->name ) . '</option>';
			}
			?>
		 </select>

	<?php
	}


	function render_legacy( $value ) {
		?>

		<input type="hidden" class="<?php echo $this->get_classes() ?>"
				 name="<?php echo $this->get_full_name(); ?>"
				 data-placeholder="<?php _e( 'Search for a term&hellip;', 'automatewoo' ); ?>"
				 data-action="aw_json_search_taxonomy_terms"
				 data-pass-sibling="aw_workflow_data[trigger_options][taxonomy]"
				 data-selected="<?php

				 if ( strstr( $value, '|' ) ) {
					 list( $term_id, $taxonomy ) = explode( '|', $value );

					 if ( $term = get_term_by( 'id', $term_id, $taxonomy ) )
						 echo wp_kses_post( $term->name );
				 }

				 ?>"
				 value="<?php echo ( $value ? $value : '' ); ?>" />

		<?php
	}

}
