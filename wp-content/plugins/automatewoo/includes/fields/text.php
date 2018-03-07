<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Text
 */
class Text extends Field {

	protected $name = 'text_input';

	protected $type = 'text';

	public $multiple = false;


	function __construct() {
		parent::__construct();
		$this->title = __( 'Text Input', 'automatewoo' );
	}


	/**
	 * @param bool $multi
	 * @return $this
	 */
	function set_multiple( $multi = true ) {
		$this->multiple = $multi;
		return $this;
	}


	/**
	 * @param $value
	 */
	function render( $value ) {
	?>
		<input type="<?php echo $this->get_type() ?>"
		       name="<?php echo $this->get_full_name() ?><?php echo $this->multiple ? '[]' : '' ?>"
		       value="<?php echo $value ?>"
		       class="<?php echo $this->get_classes() ?>"
		       placeholder="<?php echo $this->get_placeholder() ?>"
			   <?php echo $this->get_extra_attrs(); ?>
			   <?php echo ( $this->get_required() ? 'required' : '' ) ?>
			>
	<?php
	}

}
