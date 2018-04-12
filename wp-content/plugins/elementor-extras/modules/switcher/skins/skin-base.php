<?php
namespace ElementorExtras\Modules\Switcher\Skins;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/ee-content-switcher/section_items/before_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( Extras_Widget $widget ) {
		$this->parent 	= $widget;

		$this->register_content_controls();
	}

	public function register_content_controls() {

	}

	public function render() {

		$this->parent->render();

	}

}