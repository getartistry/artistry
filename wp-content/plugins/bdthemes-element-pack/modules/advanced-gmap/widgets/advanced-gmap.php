<?php
namespace ElementPack\Modules\AdvancedGmap\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Advanced_Gmap extends Widget_Base {

	public function get_name() {
		return 'bdt-advanced-gmap';
	}

	public function get_title() {
		return esc_html__( 'Advanced Google Map', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'gmap-api','gmap' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_gmap',
			[
				'label' => esc_html__( 'Google Map', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'avd_google_map_zoom_control',
			[
				'label'   => __( 'Zoom Control', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'avd_google_map_default_zoom',
			[
				'label' => __( 'Default Zoom', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 24,
					],
				],
				'condition' => ['avd_google_map_zoom_control' => 'yes']
			]
		);

		$this->add_control(
			'avd_google_map_street_view',
			[
				'label'   => __( 'Street View Control', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'avd_google_map_type_control',
			[
				'label'   => __( 'Map Type Control', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'avd_google_map_height',
			[
				'label' => __( 'Map Height', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-advanced-gmap'  => 'min-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'gmap_geocode',
			[
				'label' => __( 'Search Address', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'search_align',
			[
				'label'   => __( 'Text Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-gmap-search-wrapper' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'gmap_geocode' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'search_spacing',
			[
				'label' => __( 'Spacing', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-gmap-search-wrapper'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'gmap_geocode' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_marker',
			[
				'label' => esc_html__( 'Marker', 'bdthemes-element-pack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_content_marker' );

		$repeater->start_controls_tab(
			'tab_content_content',
			[
				'label' => esc_html__( 'Content', 'bdthemes-element-pack' ),
			]
		);

		$repeater->add_control(
			'marker_lat',
			[
				'label' => esc_html__( 'Latitude', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::TEXT,
				'default' => '24.8238746',
			]
		);

		$repeater->add_control(
			'marker_lng',
			[
				'label' => esc_html__( 'Longitude', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::TEXT,
				'default' => '89.3816299',
			]
		);

		$repeater->add_control(
			'marker_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::TEXT,
				'default' => 'Another Place',
			]
		);

		$repeater->add_control(
			'marker_content',
			[
				'label'   => esc_html__( 'Content', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your Business Address Here', 'bdthemes-element-pack'),
			]
		);
		
		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_content_marker',
			[
				'label' => esc_html__( 'Marker', 'bdthemes-element-pack' ),
			]
		);

		$repeater->add_control(
			'custom_marker',
			[
				'label'       => esc_html__( 'Custom marker', 'bdthemes-element-pack' ),
				'description' => esc_html__('Use max 32x32 px size icon for better result.', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::MEDIA,
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'marker',
			[
				'type'    => Controls_Manager::REPEATER,
				'fields'  => array_values( $repeater->get_controls() ),
				'default' => [
					[
						'marker_lat'     => '24.8248746',
						'marker_lng'     => '89.3826299',
						'marker_title'   => esc_html__( 'BdThemes', 'bdthemes-element-pack' ),
						'marker_content' => __( '<strong>BdThemes Limited</strong>,<br>Latifpur, Bogra - 5800,<br>Bangladesh', 'bdthemes-element-pack'),
					],
				],
				'title_field' => '{{{ marker_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_gmap',
			[
				'label' => __( 'GMap Style', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'avd_google_map_style',
			[
				'label'   => __( 'Style Json Code', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
		        'description' => __( 'Go to this link: <a href="https://snazzymaps.com/" target="_blank">snazzymaps.com</a> and pick a style, copy the json code from first with \'[\' to last with \']\' then come back and paste here', 'bdthemes-element-pack' ),
			]
		);

		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_style_search',
			[
				'label'     => __( 'Search', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'gmap_geocode' => 'yes',
				],
			]
		);

		$this->add_control(
			'search_background',
			[
				'label'     => __( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_color',
			[
				'label'     => __( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_placeholder_color',
			[
				'label'     => __( 'Placeholder Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-search.bdt-search-default span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'search_shadow',
				'selector' => '{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'search_border',
				'label'       => __( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input',
			]
		);

		$this->add_responsive_control(
			'search_border_radius',
			[
				'label'      => __( 'Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'search_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_margin',
			[
				'label'      => __( 'Margin', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-search.bdt-search-default .bdt-search-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings           = $this->get_settings();
		$id                 = 'bdt-advanced-gmap-'.$this->get_id();
		
		$map_settings       = [];
		$map_settings['el'] = '#'.$id;
		
		$marker_settings    = [];
		$marker_content     = [];
		$bdt_counter        = 0;
		$all_markers        = [];

		foreach ( $settings['marker'] as $marker_item ) {
			$marker_settings['lat']    = ( $marker_item['marker_lat'] ) ? $marker_item['marker_lat'] : '';
			$marker_settings['lng']    = ( $marker_item['marker_lng'] ) ? $marker_item['marker_lng'] : '';
			$marker_settings['title']  = ( $marker_item['marker_title'] ) ? $marker_item['marker_title'] : '';
			$marker_settings['icon']   = ( $marker_item['custom_marker']['url'] ) ? $marker_item['custom_marker']['url'] : '';
			
			$marker_settings['infoWindow']['content'] = ( $marker_item['marker_content'] ) ? $marker_item['marker_content'] : '';

			$all_markers[] = 'ep_gmap.addMarker(' . json_encode($marker_settings) . ');';

			$bdt_counter++;
			if ( 1 === $bdt_counter ) {
				$map_settings['lat'] = ( $marker_item['marker_lat'] ) ? $marker_item['marker_lat'] : '';
				$map_settings['lng'] = ( $marker_item['marker_lng'] ) ? $marker_item['marker_lng'] : '';
			}
		};


		$map_settings['zoomControl']       = ( 'yes' === $settings['avd_google_map_zoom_control'] ) ? true : false;
		$map_settings['zoom']              =  $settings['avd_google_map_default_zoom']['size'];
		
		$map_settings['streetViewControl'] = ( 'yes' === $settings['avd_google_map_street_view'] ) ? true : false;
		$map_settings['mapTypeControl']    = ( 'yes' === $settings['avd_google_map_type_control'] ) ? true : false;

		?>

		<?php if($settings['gmap_geocode']) : ?>

			<div class="bdt-gmap-search-wrapper bdt-margin">
			    <form method="post" id="<?php echo esc_attr($id); ?>form" class="bdt-search bdt-search-default">
			        <span bdt-search-icon></span>
			        <input id="<?php echo esc_attr($id); ?>address" name="address" class="bdt-search-input" type="search" placeholder="Search...">
			    </form>
			</div>

			
		<?php endif; ?>
		
		<div id="<?php echo esc_attr($id); ?>" class="bdt-advanced-gmap"></div>
		<script>
			jQuery(document).ready(function($) {
				'use strict';
				var ep_gmap;
				ep_gmap = new GMaps( <?php echo json_encode($map_settings); ?> );

				<?php echo implode("", $all_markers); ?>

				<?php if( '' != $settings['avd_google_map_style']) : ?>
					var styles = <?php echo $settings['avd_google_map_style']; ?>;
			        ep_gmap.addStyle({
			            styledMapName:"Styled Map",
			            styles: styles,
			            mapTypeId: "map_style"  
			        });
			        ep_gmap.setStyle("map_style");
		        <?php endif; ?>

				<?php if($settings['gmap_geocode']) : ?>
					$('#<?php echo esc_attr($id); ?>form').submit(function(e){
						e.preventDefault();
						GMaps.geocode({
							address: $('#<?php echo esc_attr($id); ?>address').val().trim(),
							callback: function(results, status){
								if(status=='OK'){
									var latlng = results[0].geometry.location;
									ep_gmap.setCenter(latlng.lat(), latlng.lng());
									ep_gmap.addMarker({
										lat: latlng.lat(),
										lng: latlng.lng()
									});
								}	
							}
						});
					});
				<?php endif; ?>
			});
		</script>

		<?php

	}


	//protected function _content_template() {}
		


}
