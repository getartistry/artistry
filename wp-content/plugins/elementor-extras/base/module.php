<?php

namespace ElementorExtras\Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module
 *
 * Base 
 *
 * @since 1.6.0
 */
abstract class Module_Base {

	/**
	 * @var \ReflectionClass
	 */
	private $reflection;

	/**
	 * @var Module_Base
	 */
	protected static $_instances = [];

	/**
	 * Abstract method for retrieveing the module name
	 *
	 * @access public
	 * @since 1.6.0
	 */
	abstract public function get_name();

	/**
	 * Return the current module class name
	 *
	 * @access public
	 * @since 1.6.0
	 *
	 * @eturn string
	 */
	public static function class_name() {
		return get_called_class();
	}

	/**
	 * @return static
	 */
	public static function instance() {
		if ( empty( static::$_instances[ static::class_name() ] ) ) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	/**
	 * Constructor
	 *
	 * Hook into Elementor to register the widgets
	 *
	 * @access public
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->reflection = new \ReflectionClass( $this );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	/**
	 * Initializes all widget for the current module
	 *
	 * @access public
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function init_widgets() {
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {

			$class_name = $this->reflection->getNamespaceName() . '\Widgets\\' . $widget;

			if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() ) {
				continue;
			}

			$module_filename = $this->get_name();
			$widget_name = strtolower( $widget );
			$widget_filename = str_replace( '_', '-', $widget_name );

			// Skip widget if it's disabled in admin settings
			if ( $this->is_widget_disabled( $widget_name ) ) {
				continue;
			}

			$widget_filename = ELEMENTOR_EXTRAS_PATH . "includes/modules/{$module_filename}/widgets/{$widget_filename}.php";

			$widget_manager->register_widget_type( new $class_name() );
		}
	}

	/**
	 * Check if widget is disabled through admin settings
	 *
	 * @access public
	 * @since 1.8.0
	 *
	 * @param  string   $widget_name Widget unique name
	 * @return bool
	 */
	public function is_widget_disabled( $widget_name ) {
		if ( ! $widget_name )
			return false;

		$option_name 	= 'enable_' . $widget_name;
		$section 		= 'elementor_extras_widgets';
		$option 		= \ElementorExtras\ElementorExtrasPlugin::instance()->settings->get_option( $option_name, $section, false );

		if ( 'off' === $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Method for retrieveing this module's widgets
	 *
	 * @access public
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function get_widgets() {
		return [];
	}

	/**
	 * Method for setting module dependancy on Elementor Pro plugin
	 *
	 * When returning false it doesn't allow the module to be registered
	 *
	 * @access public
	 * @since 1.6.0
	 *
	 * @return bool
	 */
	public static function requires_elementor_pro() {
		return false;
	}
}
