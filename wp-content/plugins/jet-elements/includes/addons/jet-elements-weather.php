<?php
/**
 * Class: Jet_Elements_Weather
 * Name: Weather
 * Slug: jet-weather
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Weather extends Jet_Elements_Base {

	public $weather_data = array();

	public $weather_api_url = 'https://query.yahooapis.com/v1/public/yql';

	public function get_name() {
		return 'jet-weather';
	}

	public function get_title() {
		return esc_html__( 'Weather', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-43';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/weather/css-scheme',
			array(
				'title'                 => '.jet-weather__title',
				'current_container'     => '.jet-weather__current',
				'current_temp'          => '.jet-weather__current-temp',
				'current_icon'          => '.jet-weather__current-icon .jet-weather-icon',
				'current_desc'          => '.jet-weather__current-desc',
				'current_details'       => '.jet-weather__details',
				'current_details_item'  => '.jet-weather__details-item',
				'current_details_icon'  => '.jet-weather__details-item .jet-weather-icon',
				'current_day'           => '.jet-weather__current-day',
				'forecast_container'    => '.jet-weather__forecast',
				'forecast_item'         => '.jet-weather__forecast-item',
				'forecast_day'          => '.jet-weather__forecast-day',
				'forecast_icon'         => '.jet-weather__forecast-icon .jet-weather-icon',
			)
		);

		$this->start_controls_section(
			'section_weather',
			array(
				'label' => esc_html__( 'Weather', 'jet-elements' ),
			)
		);

		$this->add_control(
			'location',
			array(
				'label'       => esc_html__( 'Location', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true, ),
				'placeholder' => esc_html__( 'London, United Kingdom', 'jet-elements' ),
				'default'     => esc_html__( 'London, United Kingdom', 'jet-elements' ),
			)
		);

		$this->add_control(
			'units',
			array(
				'label'   => esc_html__( 'Units', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'metric',
				'options' => array(
					'metric'   => esc_html__( 'Metric', 'jet-elements' ),
					'imperial' => esc_html__( 'Imperial', 'jet-elements' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'title_size',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'  => esc_html__( 'H1', 'jet-elements' ),
					'h2'  => esc_html__( 'H2', 'jet-elements' ),
					'h3'  => esc_html__( 'H3', 'jet-elements' ),
					'h4'  => esc_html__( 'H4', 'jet-elements' ),
					'h5'  => esc_html__( 'H5', 'jet-elements' ),
					'h6'  => esc_html__( 'H6', 'jet-elements' ),
					'div' => esc_html__( 'div', 'jet-elements' ),
				),
				'default' => 'h3',
			)
		);

		$this->add_control(
			'show_country_name',
			array(
				'label'        => esc_html__( 'Show country name', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_current_weather',
			array(
				'label'        => esc_html__( 'Show current weather', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'show_current_weather_details',
			array(
				'label'        => esc_html__( 'Show current weather details', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'show_current_weather' => 'true',
				),
			)
		);

		$this->add_control(
			'show_forecast_weather',
			array(
				'label'        => esc_html__( 'Show forecast weather', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'forecast_count',
			array(
				'label' => esc_html__( 'Number of forecast days', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 5,
					),
				),
				'default' => array(
					'size' => 5,
				),
				'condition' => array(
					'show_forecast_weather' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label' => esc_html__( 'Alignment', 'jet-elements' ),
				'type'  => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'title_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_current_style',
			array(
				'label'     => esc_html__( 'Current Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_current_weather' => 'true',
				),
			)
		);

		$this->add_control(
			'current_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'current_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'current_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'current_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_container'],
			)
		);

		$this->add_control(
			'current_temp_heading',
			array(
				'label'     => esc_html__( 'Temperature', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'current_temp_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_temp'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_temp_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_temp'],
			)
		);

		$this->add_control(
			'current_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'current_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_icon'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'current_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'current_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'current_desc_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_desc'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_desc'],
			)
		);

		$this->add_control(
			'current_desc_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_desc'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_current_details_style',
			array(
				'label'     => esc_html__( 'Details Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_current_weather'         => 'true',
					'show_current_weather_details' => 'true',
				),
			)
		);

		$this->add_control(
			'current_details_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'current_details_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'current_details_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'current_details_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
			)
		);

		$this->add_control(
			'current_details_items_heading',
			array(
				'label'     => esc_html__( 'Items', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'current_details_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_details_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_day_typography',
				'label'    => esc_html__( 'Day typography', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_day'],
			)
		);

		$this->add_control(
			'current_details_item_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_details_item'] . ' + ' . $css_scheme['current_details_item'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'current_details_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'current_details_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'current_details_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_forecast_style',
			array(
				'label'     => esc_html__( 'Forecast Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_forecast_weather' => 'true',
				),
			)
		);

		$this->add_control(
			'forecast_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'forecast_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'forecast_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'forecast_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_container'],
			)
		);

		$this->add_control(
			'forecast_item_heading',
			array(
				'label'     => esc_html__( 'Items', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'forecast_item_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_item'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_day_typography',
				'label'    => esc_html__( 'Day typography', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_day'],
			)
		);

		$this->add_responsive_control(
			'forecast_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'forecast_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'forecast_item_divider',
			array(
				'label'        => esc_html__( 'Divider', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'forecast_item_divider_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'options' => array(
					'solid'  => esc_html__( 'Solid', 'jet-elements' ),
					'double' => esc_html__( 'Double', 'jet-elements' ),
					'dotted' => esc_html__( 'Dotted', 'jet-elements' ),
					'dashed' => esc_html__( 'Dashed', 'jet-elements' ),
				),
				'default' => 'solid',
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'forecast_item_divider_weight',
			array(
				'label'   => esc_html__( 'Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'forecast_item_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'forecast_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'forecast_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'forecast_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->__context = 'render';

		$this->__open_wrap();

		$this->weather_data = $this->get_weather_data();

		if ( ! empty( $this->weather_data ) ) {
			include $this->__get_global_template( 'index' );
		}

		$this->__close_wrap();
	}

	/**
	 * Get weather data.
	 *
	 * @return array|bool|mixed
	 */
	public function get_weather_data() {

		$settings = $this->get_settings_for_display();
		$units    = ! empty( $settings['units'] ) ? $settings['units'] : 'metric';
		$location = $settings['location'];

		if ( empty( $location ) ) {
			return false;
		}

		$transient_key = sprintf( 'jet-weather-data-yahoo-%1$s-%2$s', $units, md5( $location ) );

		$data = get_transient( $transient_key );

		if ( ! $data ) {
			// Prepare request data
			$location = esc_attr( $location );
			$units    = ( 'metric' === $units ) ? 'c' : 'f';

			$request_args = array(
				'q'      => urlencode( sprintf( 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="%1$s") AND u="%2$s"', $location, $units ) ),
				'format' => 'json',
				'env'    => 'store://datatables.org/alltableswithkeys',
			);

			$request_url = add_query_arg(
				$request_args,
				$this->weather_api_url
			);

			$request_data = $this->__get_request_data( $request_url );

			if ( ! $request_data ) {
				return false;
			}

			if ( 0 === $request_data['query']['count'] || empty( $request_data['query']['results'] ) || empty( $request_data['query']['results']['channel'] ) ) {

				$message = esc_html__( 'Weather data of this location not found.', 'jet-elements' );

				echo $this->get_weather_notice( $message );
				return false;
			}

			$data = $this->prepare_weather_data( $request_data );

			if ( empty( $data ) ) {
				return false;
			}

			set_transient( $transient_key, $data, apply_filters( 'jet-elements/weather/cached-time', HOUR_IN_SECONDS ) );
		}

		return $data;
	}

	/**
	 * Get request data.
	 *
	 * @param string $url Request url.
	 *
	 * @return array|bool
	 */
	public function __get_request_data( $url ) {

		$response = wp_remote_get( $url, array( 'timeout' => 30 ) );

		if ( ! $response || is_wp_error( $response ) ) {
			return false;
		}

		$data = wp_remote_retrieve_body( $response );

		if ( ! $data || is_wp_error( $data ) ) {
			return false;
		}

		$data = json_decode( $data, true );

		if ( empty( $data ) ) {
			return false;
		}

		return $data;
	}

	/**
	 * Prepare weather data.
	 *
	 * @param array $request_data Request data.
	 *
	 * @return array|bool
	 */
	public function prepare_weather_data( $request_data = array() ) {

		$api_data = $request_data['query']['results']['channel'];

		$data = array(
			// Location data
			'location' => array(
				'city'    => $api_data['location']['city'],
				'country' => $api_data['location']['country'],
			),

			// Current data
			'current'  => array(
				'temp'     => round( $api_data['item']['condition']['temp'] ),
				'temp_min' => round( $api_data['item']['forecast'][0]['low'] ),
				'temp_max' => round( $api_data['item']['forecast'][0]['high'] ),
				'code'     => $api_data['item']['condition']['code'],
				'desc'     => $this->get_weather_desc( $api_data['item']['condition']['code'] ),
				'wind'     => array(
					'speed' => $api_data['wind']['speed'],
					'deg'   => $api_data['wind']['direction'],
				),
				'humidity' => $api_data['atmosphere']['humidity'] . '%',
				'pressure' => $api_data['atmosphere']['pressure'],
				'sunrise'  => $api_data['astronomy']['sunrise'],
				'sunset'   => $api_data['astronomy']['sunset'],
				'week_day' => date_i18n( 'l' ),
			),

			// Forecast data
			'forecast' => array(),
		);

		for ( $i = 1; $i <= 5; $i ++ ) {
			$data['forecast'][] = array(
				'code'     => $api_data['item']['forecast'][ $i ]['code'],
				'desc'     => $this->get_weather_desc( $api_data['item']['forecast'][ $i ]['code'] ),
				'temp_min' => $api_data['item']['forecast'][ $i ]['low'],
				'temp_max' => $api_data['item']['forecast'][ $i ]['high'],
				'week_day' => $this->get_week_day_from_date_format( 'd M Y', $api_data['item']['forecast'][ $i ]['date'] ),
			);
		}

		return $data;
	}

	/**
	 * Get weather description.
	 *
	 * @param int $code Weather code.
	 *
	 * @return string
	 */
	public function get_weather_desc( $code ) {
		$desc = '';

		switch ( $code ) {
			case 0:
				$desc = esc_html_x( 'Tornado', 'Weather description', 'jet-elements' );
				break;
			case 1:
				$desc = esc_html_x( 'Tropical storm', 'Weather description', 'jet-elements' );
				break;
			case 2:
				$desc = esc_html_x( 'Hurricane', 'Weather description', 'jet-elements' );
				break;
			case 3:
				$desc = esc_html_x( 'Severe thunderstorms', 'Weather description', 'jet-elements' );
				break;
			case 4:
				$desc = esc_html_x( 'Thunderstorms', 'Weather description', 'jet-elements' );
				break;
			case 5:
				$desc = esc_html_x( 'Mixed rain and snow', 'Weather description', 'jet-elements' );
				break;
			case 6:
				$desc = esc_html_x( 'Mixed rain and sleet', 'Weather description', 'jet-elements' );
				break;
			case 7:
				$desc = esc_html_x( 'Mixed snow and sleet', 'Weather description', 'jet-elements' );
				break;
			case 8:
				$desc = esc_html_x( 'Freezing drizzle', 'Weather description', 'jet-elements' );
				break;
			case 9:
				$desc = esc_html_x( 'Drizzle', 'Weather description', 'jet-elements' );
				break;
			case 10:
				$desc = esc_html_x( 'Freezing rain', 'Weather description', 'jet-elements' );
				break;
			case 11:
			case 12:
				$desc = esc_html_x( 'Showers', 'Weather description', 'jet-elements' );
				break;
			case 13:
				$desc = esc_html_x( 'Snow flurries', 'Weather description', 'jet-elements' );
				break;
			case 14:
				$desc = esc_html_x( 'Light snow showers', 'Weather description', 'jet-elements' );
				break;
			case 15:
				$desc = esc_html_x( 'Blowing snow', 'Weather description', 'jet-elements' );
				break;
			case 16:
				$desc = esc_html_x( 'Snow', 'Weather description', 'jet-elements' );
				break;
			case 17:
				$desc = esc_html_x( 'Hail', 'Weather description', 'jet-elements' );
				break;
			case 18:
				$desc = esc_html_x( 'Sleet', 'Weather description', 'jet-elements' );
				break;
			case 19:
				$desc = esc_html_x( 'Dust', 'Weather description', 'jet-elements' );
				break;
			case 20:
				$desc = esc_html_x( 'Foggy', 'Weather description', 'jet-elements' );
				break;
			case 21:
				$desc = esc_html_x( 'Haze', 'Weather description', 'jet-elements' );
				break;
			case 22:
				$desc = esc_html_x( 'Smoky', 'Weather description', 'jet-elements' );
				break;
			case 23:
				$desc = esc_html_x( 'Blustery', 'Weather description', 'jet-elements' );
				break;
			case 24:
				$desc = esc_html_x( 'Windy', 'Weather description', 'jet-elements' );
				break;
			case 25:
				$desc = esc_html_x( 'Cold', 'Weather description', 'jet-elements' );
				break;
			case 26:
				$desc = esc_html_x( 'Cloudy', 'Weather description', 'jet-elements' );
				break;
			case 27:
			case 28:
				$desc = esc_html_x( 'Mostly cloudy', 'Weather description', 'jet-elements' );
				break;
			case 29:
			case 30:
				$desc = esc_html_x( 'Partly cloudy', 'Weather description', 'jet-elements' );
				break;
			case 31:
				$desc = esc_html_x( 'Clear', 'Weather description', 'jet-elements' );
				break;
			case 32:
				$desc = esc_html_x( 'Sunny', 'Weather description', 'jet-elements' );
				break;
			case 33:
			case 34:
				$desc = esc_html_x( 'Fair', 'Weather description', 'jet-elements' );
				break;
			case 35:
				$desc = esc_html_x( 'Mixed rain and hail', 'Weather description', 'jet-elements' );
				break;
			case 36:
				$desc = esc_html_x( 'Hot', 'Weather description', 'jet-elements' );
				break;
			case 37:
				$desc = esc_html_x( 'Isolated thunderstorms', 'Weather description', 'jet-elements' );
				break;
			case 38:
			case 39:
				$desc = esc_html_x( 'Scattered thunderstorms', 'Weather description', 'jet-elements' );
				break;
			case 40:
				$desc = esc_html_x( 'Scattered showers', 'Weather description', 'jet-elements' );
				break;
			case 41:
			case 43:
				$desc = esc_html_x( 'Heavy snow', 'Weather description', 'jet-elements' );
				break;
			case 42:
				$desc = esc_html_x( 'Scattered snow showers', 'Weather description', 'jet-elements' );
				break;
			case 44:
				$desc = esc_html_x( 'Partly cloudy', 'Weather description', 'jet-elements' );
				break;
			case 45:
				$desc = esc_html_x( 'Thundershowers', 'Weather description', 'jet-elements' );
				break;
			case 46:
				$desc = esc_html_x( 'Snow showers', 'Weather description', 'jet-elements' );
				break;
			case 47:
				$desc = esc_html_x( 'Isolated thundershowers', 'Weather description', 'jet-elements' );
				break;
			case 3200:
				$desc = esc_html_x( 'Not available', 'Weather description', 'jet-elements' );
				break;
		}

		return $desc;
	}

	/**
	 * Get week day from date.
	 *
	 * @param string $format Date format.
	 * @param string $date   Date.
	 *
	 * @return bool|string
	 */
	public function get_week_day_from_date_format( $format = '', $date = '' ) {
		$date = date_create_from_format( $format, $date );

		return date_i18n( 'l', date_timestamp_get( $date ) );
	}

	/**
	 * Get title html markup.
	 *
	 * @return string
	 */
	public function get_weather_title() {
		$settings = $this->get_settings();
		$title    = $this->weather_data['location']['city'];
		$tag      = isset( $settings['title_size'] ) ? $settings['title_size'] : 'h3';

		if ( isset( $settings['show_country_name'] ) && 'true' === $settings['show_country_name'] ) {
			$country = $this->weather_data['location']['country'];

			$title = sprintf( '%1$s, %2$s', $title, $country );
		}

		return sprintf( '<%1$s class="jet-weather__title">%2$s</%1$s>', $tag, $title );
	}

	/**
	 * Get temperature html markup.
	 *
	 * @param int $temp Temperature value.
	 *
	 * @return string
	 */
	public function get_weather_temp( $temp ) {
		$units     = $this->get_settings( 'units' );
		$temp_unit = ( 'metric' === $units ) ? '&#176;C' : '&#176;F';

		$format = apply_filters( 'jet-elements/weather/temperature-format', '%1$s%2$s' );

		return sprintf( $format, $temp, $temp_unit );
	}

	/**
	 * Get wind.
	 *
	 * @param int $speed Wind speed.
	 * @param int $deg   Wind direction, degrees.
	 *
	 * @return string
	 */
	public function get_wind( $speed, $deg ) {
		$units      = $this->get_settings( 'units' );
		$speed_unit = ( 'metric' === $units ) ? esc_html_x( 'km/h', 'Unit of speed (kilometers/hour)', 'jet-elements' ) : esc_html_x( 'mph', 'Unit of speed (miles/hour)', 'jet-elements' );

		$direction = '';

		if ( ( $deg >= 0 && $deg <= 11.25 ) || ( $deg > 348.75 && $deg <= 360 ) ) {
			$direction = esc_html_x( 'N', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 11.25 && $deg <= 33.75 ) {
			$direction = esc_html_x( 'NNE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 33.75 && $deg <= 56.25 ) {
			$direction = esc_html_x( 'NE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 56.25 && $deg <= 78.75 ) {
			$direction = esc_html_x( 'ENE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 78.75 && $deg <= 101.25 ) {
			$direction = esc_html_x( 'E', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 101.25 && $deg <= 123.75 ) {
			$direction = esc_html_x( 'ESE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 123.75 && $deg <= 146.25 ) {
			$direction = esc_html_x( 'SE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 146.25 && $deg <= 168.75 ) {
			$direction = esc_html_x( 'SSE', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 168.75 && $deg <= 191.25 ) {
			$direction = esc_html_x( 'S', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 191.25 && $deg <= 213.75 ) {
			$direction = esc_html_x( 'SSW', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 213.75 && $deg <= 236.25 ) {
			$direction = esc_html_x( 'SW', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 236.25 && $deg <= 258.75 ) {
			$direction = esc_html_x( 'WSW', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 258.75 && $deg <= 281.25 ) {
			$direction = esc_html_x( 'W', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 281.25 && $deg <= 303.75 ) {
			$direction = esc_html_x( 'WNW', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 303.75 && $deg <= 326.25 ) {
			$direction = esc_html_x( 'NW', 'Wind direction', 'jet-elements' );
		} else if ( $deg > 326.25 && $deg <= 348.75 ) {
			$direction = esc_html_x( 'NNW', 'Wind direction', 'jet-elements' );
		}

		$format = apply_filters( 'jet-elements/weather/wind-format', '%1$s %2$s %3$s' );

		return sprintf( $format, $direction, $speed, $speed_unit );
	}

	/**
	 * Get weather notice html markup.
	 *
	 * @param string $message Message.
	 *
	 * @return string
	 */
	public function get_weather_notice( $message ) {
		return sprintf( '<div class="jet-weather-notice">%s</div>', $message );
	}

	/**
	 * Get weather svg icon.
	 *
	 * @param string|int $icon Icon slug or weather code.
	 *
	 * @return bool|string
	 */
	public function get_weather_svg_icon( $icon ) {

		$icon_path = jet_elements()->plugin_path( "assets/images/weather-icons/{$icon}.svg" );

		if ( ! file_exists( $icon_path ) ) {
			return false;
		}

		ob_start();

		include $icon_path;

		$svg = ob_get_clean();

		$_classes   = array();
		$_classes[] = 'jet-weather-icon';
		$_classes[] = sprintf( 'jet-weather-icon-%s', esc_attr( $icon ) );

		$classes = join( ' ', $_classes );

		return sprintf( '<div class="%2$s">%1$s</div>', $svg, $classes );
	}
}
