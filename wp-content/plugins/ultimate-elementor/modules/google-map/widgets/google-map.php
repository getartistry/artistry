<?php
/**
 * UAEL GoogleMap.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\GoogleMap\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;
use UltimateElementor\Classes\UAEL_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class GoogleMap.
 */
class GoogleMap extends Common_Widget {

	/**
	 * Retrieve GoogleMap Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'GoogleMap' );
	}

	/**
	 * Retrieve GoogleMap Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'GoogleMap' );
	}

	/**
	 * Retrieve GoogleMap Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'GoogleMap' );
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-google-maps', 'uael-google-maps-api', 'uael-google-maps-cluster' ];
	}


	/**
	 * Register GoogleMap controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_addresses_controls();
		$this->register_layout_controls();
		$this->register_controls_controls();
		$this->register_info_window_controls();
		$this->register_helpful_information();
	}

	/**
	 * Register GoogleMap Addresses Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_addresses_controls() {

		$map_options = UAEL_Helper::get_integrations_options();

		$this->start_controls_section(
			'section_map_addresses',
			[
				'label' => __( 'Addresses', 'uael' ),
			]
		);

		if ( parent::is_internal_links() && ( ! isset( $map_options['google_api'] ) || '' == $map_options['google_api'] ) ) {

			$widget_list = UAEL_Helper::get_widget_list();

			$admin_link = $widget_list['GoogleMap']['setting_url'];

			$this->add_control(
				'err_msg',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( __( 'To display customized Google Map without an issue, you need to configure Google Map API key. Please configure API key from <a href="%s" target="_blank" rel="noopener">here</a>.', 'uael' ), $admin_link ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
		}

			$this->add_control(
				'addresses',
				[
					'label'       => '',
					'type'        => Controls_Manager::REPEATER,
					'default'     => [
						[
							'latitude'        => 51.503333,
							'longitude'       => -0.119562,
							'map_title'       => __( 'Coca-Cola London Eye', 'uael' ),
							'map_description' => '',
						],
					],
					'fields'      => [
						[
							'name'        => 'latitude',
							'label'       => __( 'Latitude', 'uael' ),
							'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'uael' ), __( 'to find Latitude of your location', 'uael' ) ),
							'type'        => Controls_Manager::TEXT,
							'label_block' => true,
						],
						[
							'name'        => 'longitude',
							'label'       => __( 'Longitude', 'uael' ),
							'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'uael' ), __( 'to find Longitude of your location', 'uael' ) ),
							'type'        => Controls_Manager::TEXT,
							'label_block' => true,
						],
						[
							'name'        => 'map_title',
							'label'       => __( 'Address Title', 'uael' ),
							'type'        => Controls_Manager::TEXT,
							'label_block' => true,
						],
						[
							'name'        => 'marker_infowindow',
							'label'       => __( 'Display Info Window', 'uael' ),
							'type'        => Controls_Manager::SELECT,
							'default'     => 'none',
							'label_block' => true,
							'options'     => [
								'none'  => __( 'None', 'uael' ),
								'click' => __( 'On Mouse Click', 'uael' ),
								'load'  => __( 'On Page Load', 'uael' ),
							],
						],
						[
							'name'        => 'map_description',
							'label'       => __( 'Address Information', 'uael' ),
							'type'        => Controls_Manager::TEXTAREA,
							'label_block' => true,
							'conditions'  => [
								'terms' => [
									[
										'name'     => 'marker_infowindow',
										'operator' => '!=',
										'value'    => 'none',
									],
								],
							],
						],
						[
							'name'    => 'marker_icon_type',
							'label'   => __( 'Marker Icon', 'uael' ),
							'type'    => Controls_Manager::SELECT,
							'default' => 'default',
							'options' => [
								'default' => __( 'Default', 'uael' ),
								'custom'  => __( 'Custom', 'uael' ),
							],
						],
						[
							'name'       => 'marker_icon',
							'label'      => __( 'Select Marker', 'uael' ),
							'type'       => Controls_Manager::MEDIA,
							'conditions' => [
								'terms' => [
									[
										'name'     => 'marker_icon_type',
										'operator' => '==',
										'value'    => 'custom',
									],
								],
							],
						],
						[
							'name'        => 'custom_marker_size',
							'label'       => __( 'Marker Size', 'uael' ),
							'type'        => Controls_Manager::SLIDER,
							'size_units'  => [ 'px' ],
							'description' => __( 'Note: If you want to retain the image original size, then set the Marker Size as blank.', 'uael' ),
							'default'     => [
								'size' => 30,
								'unit' => 'px',
							],
							'range'       => [
								'px' => [
									'min' => 5,
									'max' => 100,
								],
							],
							'conditions'  => [
								'terms' => [
									[
										'name'     => 'marker_icon_type',
										'operator' => '==',
										'value'    => 'custom',
									],
								],
							],
						],
					],
					'title_field' => '<i class="fa fa-map-marker"></i> {{{ map_title }}}',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Layout Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_layout_controls() {

		$this->start_controls_section(
			'section_map_settings',
			[
				'label' => __( 'Layout', 'uael' ),
			]
		);

			$this->add_control(
				'type',
				[
					'label'   => __( 'Map Type', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'roadmap',
					'options' => [
						'roadmap'   => __( 'Road Map', 'uael' ),
						'satellite' => __( 'Satellite', 'uael' ),
						'hybrid'    => __( 'Hybrid', 'uael' ),
						'terrain'   => __( 'Terrain', 'uael' ),
					],
				]
			);

			$this->add_control(
				'skin',
				[
					'label'     => __( 'Map Skin', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'standard',
					'options'   => [
						'standard'     => __( 'Standard', 'uael' ),
						'silver'       => __( 'Silver', 'uael' ),
						'retro'        => __( 'Retro', 'uael' ),
						'dark'         => __( 'Dark', 'uael' ),
						'night'        => __( 'Night', 'uael' ),
						'aubergine'    => __( 'Aubergine', 'uael' ),
						'aqua'         => __( 'Aqua', 'uael' ),
						'classic_blue' => __( 'Classic Blue', 'uael' ),
						'earth'        => __( 'Earth', 'uael' ),
						'magnesium'    => __( 'Magnesium', 'uael' ),
						'custom'       => __( 'Custom', 'uael' ),
					],
					'condition' => [
						'type!' => 'satellite',
					],
				]
			);

			$this->add_control(
				'map_custom_style',
				[
					'label'       => __( 'Custom Style', 'uael' ),
					'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'uael' ), __( 'to get JSON style code to style your map', 'uael' ) ),
					'type'        => Controls_Manager::TEXTAREA,
					'condition'   => [
						'skin'  => 'custom',
						'type!' => 'satellite',
					],
				]
			);

			$this->add_control(
				'animate',
				[
					'label'   => __( 'Marker Animation', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''       => __( 'None', 'uael' ),
						'drop'   => __( 'On Load', 'uael' ),
						'bounce' => __( 'Continuous', 'uael' ),
					],
				]
			);

			$this->add_control(
				'zoom',
				[
					'label'   => __( 'Map Zoom', 'uael' ),
					'type'    => Controls_Manager::SLIDER,
					'default' => [
						'size' => 12,
					],
					'range'   => [
						'px' => [
							'min' => 1,
							'max' => 22,
						],
					],
				]
			);

			$this->add_responsive_control(
				'height',
				[
					'label'      => __( 'Height', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'default'    => [
						'size' => 500,
						'unit' => 'px',
					],
					'range'      => [
						'px' => [
							'min' => 80,
							'max' => 1200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .uael-google-map' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Control Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_controls_controls() {

		$this->start_controls_section(
			'section_map_controls',
			[
				'label' => __( 'Controls', 'uael' ),
			]
		);

			$this->add_control(
				'option_streeview',
				[
					'label'        => __( 'Street View Controls', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'type_control',
				[
					'label'        => __( 'Map Type Control', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'zoom_control',
				[
					'label'        => __( 'Zoom Control', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'fullscreen_control',
				[
					'label'        => __( 'Fullscreen Control', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'scroll_zoom',
				[
					'label'        => __( 'Zoom on Scroll', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'cluster',
				[
					'label'        => __( 'Cluster the Markers', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'On', 'uael' ),
					'label_off'    => __( 'Off', 'uael' ),
					'return_value' => 'yes',
				]
			);

		if ( parent::is_internal_links() ) {
			$this->add_control(
				'cluster_doc',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s admin link */
					'raw'             => sprintf( __( 'Enable this to group your markers together if you have many in a close proximity to only display one larger marker on your map.<br> Read %1$s this article %2$s for more information.', 'uael' ), '<a href="https://uaelementor.com/docs/what-are-cluster-markers-in-uael/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
					'condition'       => [ 'cluster' => 'yes' ],
				]
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Info Window Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_info_window_controls() {

		$this->start_controls_section(
			'section_info_window_style',
			[
				'label' => __( 'Info Window', 'uael' ),
			]
		);

			$this->add_control(
				'info_window_size',
				[
					'label'       => __( 'Max Width for Info Window', 'uael' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => [
						'size' => 250,
						'unit' => 'px',
					],
					'range'       => [
						'px' => [
							'min'  => 50,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'size_units'  => [ 'px' ],
					'label_block' => true,
				]
			);

			$this->add_responsive_control(
				'info_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .gm-style .uael-infowindow-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'title_spacing',
				[
					'label'      => __( 'Spacing Between Title & Info.', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'default'    => [
						'size' => 5,
						'unit' => 'px',
					],
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .gm-style .uael-infowindow-description' => 'margin-top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gm-style .uael-infowindow-title' => 'font-weight: bold;',
					],
				]
			);

			$this->add_control(
				'title_heading',
				[
					'label' => __( 'Address Title', 'uael' ),
					'type'  => Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'title_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gm-style .uael-infowindow-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
					'selector' => '{{WRAPPER}} .gm-style .uael-infowindow-title',
				]
			);

			$this->add_control(
				'description_heading',
				[
					'label' => __( 'Address Information', 'uael' ),
					'type'  => Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'description_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gm-style .uael-infowindow-description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'description_typography',
					'selector' => '{{WRAPPER}} .gm-style .uael-infowindow-description',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_helpful_information() {

		if ( parent::is_internal_links() ) {
			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => __( 'Helpful Information', 'uael' ),
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s Google Map localization » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/how-to-display-uaels-google-maps-widget-in-your-local-language/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->end_controls_section();
		}
	}


	/**
	 * Renders Locations JSON array.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_locations() {

		$settings = $this->get_settings();

		$locations = array();

		foreach ( $settings['addresses'] as $index => $item ) {

			$location_object = array(
				$item['latitude'],
				$item['longitude'],
			);

			$location_object[] = ( 'none' != $item['marker_infowindow'] ) ? true : false;
			$location_object[] = $item['map_title'];
			$location_object[] = $item['map_description'];

			if (
				'custom' == $item['marker_icon_type'] &&
				'' != $item['marker_icon']['url']
			) {
				$location_object[] = 'custom';
				$location_object[] = $item['marker_icon']['url'];
				$location_object[] = $item['custom_marker_size']['size'];
			} else {
				$location_object[] = '';
				$location_object[] = '';
				$location_object[] = '';
			}

			$location_object[] = ( 'load' == $item['marker_infowindow'] ) ? 'iw_open' : '';

			$locations[] = $location_object;
		}

		return $locations;
	}

	/**
	 * Renders Map Control option JSON array.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_map_options() {

		$settings = $this->get_settings();

		return array(
			'zoom'              => ( ! empty( $settings['zoom']['size'] ) ) ? $settings['zoom']['size'] : 4,
			'mapTypeId'         => ( ! empty( $settings['type'] ) ) ? $settings['type'] : 'roadmap',
			'mapTypeControl'    => ( 'yes' == $settings['type_control'] ) ? true : false,
			'streetViewControl' => ( 'yes' == $settings['option_streeview'] ) ? true : false,
			'zoomControl'       => ( 'yes' == $settings['zoom_control'] ) ? true : false,
			'fullscreenControl' => ( 'yes' == $settings['fullscreen_control'] ) ? true : false,
			'gestureHandling'   => ( 'yes' == $settings['scroll_zoom'] ) ? true : false,
		);
	}

	/**
	 * Render Google Map output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		ob_start();

		$map_options = $this->get_map_options();
		$locations   = $this->get_locations();

		$this->add_render_attribute(
			'google-map',
			[
				'id'               => 'uael-google-map-' . esc_attr( $this->get_id() ),
				'class'            => 'uael-google-map',
				'data-map_options' => wp_json_encode( $map_options ),
				'data-cluster'     => $settings['cluster'],
				'data-max-width'   => $settings['info_window_size']['size'],
				'data-locations'   => wp_json_encode( $locations ),
				'data-animate'     => $settings['animate'],
			]
		);

		if ( 'standard' != $settings['skin'] ) {
			if ( 'custom' != $settings['skin'] ) {
				$this->add_render_attribute( 'google-map', 'data-predefined-style', $settings['skin'] );
			} elseif ( ! empty( $settings['map_custom_style'] ) ) {
				$this->add_render_attribute( 'google-map', 'data-custom-style', $settings['map_custom_style'] );
			}
		}

		?>
		<div class="uael-google-map-wrap">
			<div <?php echo $this->get_render_attribute_string( 'google-map' ); ?>></div>
		</div>
		<?php
		$html = ob_get_clean();
		echo $html;
	}

	/**
	 * Render GoogleMap widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#

		function get_map_options( settings ) {

			return {
				'zoom' : ( '' != settings.zoom.size ) ? settings.zoom.size : 4,
				'mapTypeId' : ( '' != settings.type ) ? settings.type : 'roadmap',
				'mapTypeControl' : ( 'yes' == settings.type_control ) ? true : false,
				'streetViewControl' : ( 'yes' == settings.option_streeview ) ? true : false,
				'zoomControl' : ( 'yes' == settings.zoom_control ) ? true : false,
				'fullscreenControl' : ( 'yes' == settings.fullscreen_control ) ? true : false,
				'gestureHandling' : ( 'yes' == settings.scroll_zoom ) ? true : false
			};
		}

		function get_locations( settings ) {

			var all_locations = [];

			_.each( settings.addresses, function( item ) {

				var this_location = [ item.latitude, item.longitude ];

				if ( 'none' != item.marker_infowindow ) {
					this_location.push( true );
				} else {
					this_location.push( false );
				}
				this_location.push( item.map_title );
				this_location.push( item.map_description );

				if (
					'custom' == item.marker_icon_type &&
					'' != item.marker_icon.url
				) {
					this_location.push( 'custom' );
					this_location.push( item.marker_icon.url );
					this_location.push( item.custom_marker_size.size );
				} else {
					this_location.push( "" );
					this_location.push( "" );
					this_location.push( "" );
				}

				if ( 'load' == item.marker_infowindow ) {
					this_location.push( 'iw_open' );
				} else {
					this_location.push( "" );
				}

				all_locations.push( this_location );

			});

			return all_locations;
		}

		var map_options = get_map_options( settings );
		var locations 	= get_locations( settings );

		view.addRenderAttribute(
			'google-map',
			{
				'class' : 'uael-google-map',
				'data-map_options' : JSON.stringify( map_options ),
				'data-cluster' : settings.cluster,
				'data-max-width' : settings.info_window_size.size,
				'data-locations' : JSON.stringify( locations ),
				'data-animate'   : settings.animate,
			}
		);

		if ( 'standard' != settings.skin ) {

			if ( 'custom' != settings.skin ) {

				view.addRenderAttribute( 'google-map', 'data-predefined-style', settings.skin );

			} else if ( '' != settings.map_custom_style ) {

				view.addRenderAttribute( 'google-map', 'data-custom-style', settings.map_custom_style );
			}
		}

		#>
		<div class="uael-google-map-wrap">
			<div {{{ view.getRenderAttributeString( 'google-map' ) }}}></div>
		</div>

		<# elementorFrontend.hooks.doAction( 'frontend/element_ready/uael-google-map.default' ); #>
		<?php
	}

}
