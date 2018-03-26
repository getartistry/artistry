<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Maps_Widget extends Widget_Base
{
    public function get_name() {
        return 'premium-addon-maps';
    }
    
    public function is_reload_preview_required()
    {
        return true;
    }

    public function get_title() {
        return esc_html__('Premium Maps', 'premium-addons-for-elementor');
    }
    
    public function get_icon() {
        return 'pa-maps';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium maps
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /* Start Map Settings Section */
        $this->start_controls_section('premium_maps_map_settings',
                [
                    'label'         => esc_html__('Center Location', 'premium-addons-for-elementor'),
                    ]
                );

        $this->add_control('premium_maps_api_url',
            [
                    'label'         => '<span style="line-height: 1.4em;">Premium Maps requires an API key. Get your API key from <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">here</a> and add it to Premium Addons admin page. Go to Dashboard -> Premium Addons for Elementor -> Google Maps API</span>',
                    'type'          => Controls_Manager::RAW_HTML,
            ]
        );
        
        $this->add_control('premium_maps_center_lat',
                [
                    'label'         => esc_html__('Center Latitude', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'description'   => esc_html__('Center latitude and longitude are required to identify your location', 'premium-addons-for-elementor'),
                    'default'       => '18.591212',
                    'label_block'   => true,
                    ]
                );

        $this->add_control('premium_maps_center_long',
                [
                    'label'         => esc_html__('Center Longitude', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'description'   => esc_html__('Center latitude and longitude are required to identify your location', 'premium-addons-for-elementor'),
                    'default'       => '73.741261',
                    'label_block'   => true,
                    ]
                );
        
        $this->end_controls_section();
        
         $this->start_controls_section('premium_maps_map_pins_settings',
                [
                    'label'         => esc_html__('Markers', 'premium-addons-for-elementor'),
                    ]
                );

        $this->add_control('premium_maps_map_pins',
                [
                    'label'         => esc_html__('Map Pins', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::REPEATER,
                    'default'       => [
                        'map_latitude'      => '18.591212',
                        'map_longitude'     => '73.741261',
                        'pin_title'         => esc_html__('Premium Google Maps', 'premium-addons-for-elementor'),
                        'pin_desc'          => esc_html__('Add an optional description to your map pin', 'premium-addons-for-elementor'),
                    ],
                    'fields'       => [
                        [
                            'name'          => 'map_latitude',
                            'label'         => esc_html__('Latitude', 'premium-addons-for-elementor'),
                            'type'          => Controls_Manager::TEXT,
                            'description'   => 'Click <a href="https://www.latlong.net/" target="_blank">here</a> to get your location coordinates',
                            'label_block'   => true,
                            ],
                            [
                            'name'          => 'map_longitude',
                            'label'         => esc_html__('Longitude', 'premium-addons-for-elementor'),
                            'type'          => Controls_Manager::TEXT,
                            'description'   => 'Click <a href="https://www.latlong.net/" target="_blank">here</a> to get your location coordinates',
                            'label_block'   => true,
                            ],
                            [
                            'name'          => 'pin_title',
                            'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                            'type'          => Controls_Manager::TEXT,
                            'label_block'   => true,
                            ],
                            [
                            'name'          => 'pin_desc',
                            'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                            'type'          => Controls_Manager::WYSIWYG,
                            'label_block'   => true,
                            ],
                            [
                            'name'          => 'pin_icon',
                            'label'         => esc_html__('Custom Icon', 'premium-addons-for-elementor'),
                            'type'          => Controls_Manager::MEDIA,
                            ],
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_controls_section',
                [
                    'label'         => esc_html__('Controls', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_maps_map_type',
                [
                    'label'         => esc_html__( 'Map Type', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'roadmap'       => esc_html__( 'Road Map', 'premium-addons-for-elementor' ),
                        'satellite'     => esc_html__( 'Satellite', 'premium-addons-for-elementor' ),
                        'terrain'       => esc_html__( 'Terrain', 'premium-addons-for-elementor' ),
                        'hybrid'        => esc_html__( 'Hybrid', 'premium-addons-for-elementor' ),
                        ],
                    'default'       => 'roadmap',
                    ]
                );
        
        $this->add_responsive_control('premium_maps_map_height',
                [
                    'label'         => esc_html__( 'Height', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                            'size' => 500,
                    ],
                    'range'         => [
                            'px' => [
                                'min' => 80,
                                'max' => 1400,
                            ],
                    ],
                    'selectors'     => [
                            '{{WRAPPER}} .premium_maps_map_height' => 'height: {{SIZE}}px;',
                    ],
                ]
  		);
        
        $this->add_control('premium_maps_map_zoom',
                [
                    'label'         => esc_html__( 'Zoom', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size' => 12,
                    ],
                    'range'         => [
                        'px' => [
                                'min' => 0,
                                'max' => 22,
                        ],
                    ],
                ]
                );
        
        $this->add_control('premium_maps_map_option_map_type_control',
                [
                    'label'         => esc_html__( 'Map Type Controls', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );

        $this->add_control('premium_maps_map_option_zoom_controls',
                [
                    'label'         => esc_html__( 'Zoom Controls', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_streeview',
                [
                    'label'         => esc_html__( 'Street View Control', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_fullscreen_control',
                [
                    'label'         => esc_html__( 'Fullscreen Control', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_mapscroll',
                [
                    'label'         => esc_html__( 'Scroll Wheel Zoom', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_maps_marker_open',
                [
                    'label'         => esc_html__( 'Info Container Always Opened', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_custom_styling_section',
                [
                    'label'         => esc_html__('Map Style', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_maps_custom_styling',
        [
            'label'         => esc_html__('JSON Code', 'premium-addons-for-elementor'),
            'type'          => Controls_Manager::TEXTAREA,
            'description'   => 'Get your custom styling from <a href="https://snazzymaps.com/" target="_blank">here</a>',
            'label_block'   => true,
            ]
        );
    
        /*End Map Options Section*/
        $this->end_controls_section();
        
        /*Start Title Style Section*/
        $this->start_controls_section('premium_maps_pin_title_style',
                [
                    'label'         => esc_html__('Title', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Pin Title Color*/
        $this->add_control('premium_maps_pin_title_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'pin_title_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-maps-info-title',
                ]
                );
        
        $this->add_responsive_control('premium_maps_pin_title_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Pin Title Padding*/
        $this->add_responsive_control('premium_maps_pin_title_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Pin Title ALign*/
        $this->add_responsive_control('premium_maps_pin_title_align',
                [
                    'label'         => esc_html__( 'Alignment', 'elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', 'elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', 'elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', 'elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
                
        /*End Title Style Section*/
        $this->end_controls_section();
        
        /*Start Pin Style Section*/
        $this->start_controls_section('premium_maps_pin_text_style',
                [
                    'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        $this->add_control('premium_maps_pin_text_color',
                [
                    'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-desc'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
        Group_Control_Typography::get_type(),
                [
                    'name'          => 'pin_text_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-maps-info-desc',
                ]
                );
        
        $this->add_responsive_control('premium_maps_pin_text_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-desc'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_maps_pin_text_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-desc'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Pin Title ALign*/
        $this->add_responsive_control('premium_maps_pin_description_align',
                [
                    'label'         => esc_html__( 'Alignment', 'elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', 'elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', 'elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', 'elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-desc' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        /*End Pin Style Section*/
        $this->end_controls_section();
        
        /*Start Map Style Section*/
        $this->start_controls_section('premium_maps_box_style',
                [
                    'label'         => esc_html__('Map', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );

        /*First Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'map_border',
                    'selector'          => '{{WRAPPER}} .premium-maps-container',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_maps_box_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-container,{{WRAPPER}} .premium_maps_map_height' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*Box Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_maps_box_shadow',
                    'selector'      => '{{WRAPPER}} .premium-maps-container',
                ]
                );

        /*First Margin*/
        $this->add_responsive_control('premium_maps_box_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_maps_box_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End Map Style Section*/
        $this->end_controls_section();
        
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings();

        if( !empty( $settings['premium_maps_custom_styling'] ) ){
            $map_custom_style = 'styles:' . $settings['premium_maps_custom_styling'] . ',';
        }
        
        if ($settings['premium_maps_map_option_streeview'] == 'yes') {
            $street_view = 'true';
        } else {
            $street_view = 'false';
        }

        if ($settings['premium_maps_map_option_mapscroll'] == 'yes') {
            $scroll_wheel = 'true';
        } else {
            $scroll_wheel = 'false';
        }

        if ($settings['premium_maps_map_option_fullscreen_control'] == 'yes') {
            $enable_full_screen = 'true';
        } else {
            $enable_full_screen = 'false';
        }
        
        if ($settings['premium_maps_map_option_zoom_controls'] == 'yes') {
            $enable_zoom_control = 'true';
        } else {
            $enable_zoom_control = 'false';
        }
        
        if ($settings['premium_maps_map_option_map_type_control'] == 'yes') {
            $map_type_control = 'true';
        } else {
            $map_type_control = 'false';
        } 
?>
<div id="premium-map-script-trriger-<?php echo esc_attr($this->get_id()); ?>"></div>
    <div class="premium-maps-container" id="premium-maps-container">
        <div id="premium-maps-map-<?php echo esc_attr($this->get_id()); ?>" class="premium_maps_map_height"></div>
    </div>

    
    
    <script>
        
        var premium_mapDiv = document.getElementById('premium-map-script-trriger-<?php echo esc_attr($this->get_id()); ?>');
        google.maps.event.addDomListener(premium_mapDiv, 'click', initMap);
        google.maps.event.addDomListener(window, 'load', initMap);
        jQuery(document).ready(function( $ ) {
              initMap();
        });
      
      function initMap(){
          
        var myLatLng = {lat: <?php echo $settings['premium_maps_center_lat']; ?>, lng: <?php echo $settings['premium_maps_center_long']; ?>};
        var locations = [<?php 
        foreach ($settings['premium_maps_map_pins'] as $item) {echo '[' . "'" . esc_attr($item['pin_title']) . "'" . ',' . "'" . $item['pin_desc'] . "'"  . ',' . esc_attr($item['map_latitude']) . ',' . esc_attr($item['map_longitude']) . ',' ."'" . esc_attr($item['pin_icon']['url']). "'" . '],'; } ?>];
        var map = new google.maps.Map( document.getElementById('premium-maps-map-<?php echo esc_attr($this->get_id()); ?>') ,
        {
            zoom: <?php echo $settings['premium_maps_map_zoom']['size']; ?>,
            mapTypeId: '<?php echo $settings['premium_maps_map_type'];  ?>',
            center: myLatLng,
            scrollwheel: <?php echo $scroll_wheel; ?>,
            streetViewControl: <?php echo $street_view; ?>,
            fullscreenControl: <?php echo $enable_full_screen; ?>,
            
            zoomControl: <?php echo $enable_zoom_control; ?>,
            mapTypeControl: <?php echo $map_type_control; ?>,
            <?php if( !empty( $settings['premium_maps_custom_styling'] ) ){ echo $map_custom_style; }?>
        });
        
        var marker, i, infowindow;
        
         for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][2], locations[i][3]),
            icon: locations[i][4],
            optimized: false,
            map: map
        });
        infowindow = new google.maps.InfoWindow({
                content: "<div class='premium-maps-info-container'><p class='premium-maps-info-title'>" + locations[i][0] + "</p><div class='premium-maps-info-desc'>" + locations[i][1] + "</div></div>"
            });
            <?php if( $settings['premium_maps_marker_open'] == 'yes' ) : ?> 
            if(locations[i][0] !== '' || locations[i][1] !== '') {
                infowindow.open(map, marker);
            }
            <?php endif; ?>
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
        if(locations[i][0] !== '' || locations[i][1] !== '') {
          infowindow.setContent("<div class='premium-maps-info-container'><p class='premium-maps-info-title'>" + locations[i][0] + "</p><div class='premium-maps-info-desc'>" + locations[i][1] + "</div></div>");
          infowindow.open(map, marker);
            }
        }
})(marker, i));
    }
}
    </script>
    


    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Maps_Widget());