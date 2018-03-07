<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Text_Area
 */
class Text_Area extends Field {

	protected $name = 'text_area';

	protected $type = 'text_area';


	function __construct() {
		parent::__construct();
		$this->set_title( __( 'Text Area', 'automatewoo' ) );
	}

	/**
	 * @param $rows int
	 * @return $this
	 */
	function set_rows( $rows ) {
		$this->add_extra_attr('rows', $rows );
		return $this;
	}


	/**
	 * @param $value
	 */
	function render( $value ) {
	?>
		<textarea
		       name="<?php echo $this->get_full_name() ?>"
		       class="<?php echo $this->get_classes() ?>"
		       placeholder="<?php echo $this->get_placeholder() ?>"
			   <?php echo $this->get_extra_attrs(); ?>
			   <?php echo ( $this->get_required() ? 'required' : '' ) ?>
			><?php echo $value ?></textarea>

	<?php
	}


	/**
	 * @param $value
	 * @return mixed|string|void
	 */
	function esc_value( $value ) {
		return esc_textarea( $value );
	}

}
