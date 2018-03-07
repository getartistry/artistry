<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Checkbox
 */
class Checkbox extends Field {

	protected $name = 'checkbox';

	protected $type = 'checkbox';

	public $default_to_checked = false;


	function __construct() {
		parent::__construct();
		$this->set_title( __( 'Checkbox', 'automatewoo' ) );
	}


	/**
	 * @param bool $checked
	 * @return $this
	 */
	function set_default_to_checked( $checked = true ) {
		$this->default_to_checked = $checked;
		return $this;
	}


	/**
	 * @param $value
	 */
	function render( $value ) {

		if ( $value === null || $value === '' ) {
			$value = $this->default_to_checked;
		}

		?>
		<input type="checkbox"
			 name="<?php echo $this->get_full_name() ?>"
			 value="1"
			<?php echo ( $value ? 'checked' : '' ) ?>
			 class="<?php echo $this->get_classes() ?>"
			<?php echo $this->get_extra_attrs(); ?>
			>
	<?php
	}


}