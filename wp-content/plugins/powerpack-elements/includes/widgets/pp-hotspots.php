<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Hotspots Widget
 */
class PA_Image_Hot_Spots_Widget extends Widget_Base {

    /**
	 * Retrieve image hotspots widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-image-hotspots';
    }

    /**
	 * Retrieve image hotspots widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Image Hotspots', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the image hotspots widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve image hotspots widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eicon-image-hotspot power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the image hotspots widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'tipso',
            'pp-scripts'
        ];
    }

    /**
	 * Register image hotspots widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Image
         */
        $this->start_controls_section(
            'section_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
            ]
        );

		$this->add_control(
			'image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'power-pack' ),
                'default'               => 'full',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Hotspots
         */
        $this->start_controls_section(
            'section_hotspots',
            [
                'label'                 => __( 'Hotspots', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'hot_spots_tabs' );

        $repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'power-pack' ) ] );
        
            $repeater->add_control(
                'hotspot_type',
                [
                    'label'           => __( 'Type', 'power-pack' ),
                    'type'            => Controls_Manager::SELECT,
                    'default'         => 'icon',
                    'options'         => [
                        'icon'  => __( 'Icon', 'power-pack' ),
                        'text'  => __( 'Text', 'power-pack' ),
                        'blank' => __( 'Blank', 'power-pack' ),
                     ],
                ]
            );
        
            $repeater->add_control(
                'hotspot_icon',
                [
                    'label'           => __( 'Icon', 'power-pack' ),
                    'type'            => Controls_Manager::ICON,
                    'default'         => 'fa fa-plus',
                    'condition'       => [
                        'hotspot_type'   => 'icon',
                    ],
                ]
            );
        
            $repeater->add_control(
                'hotspot_text',
                [
                    'label'           => __( 'Text', 'power-pack' ),
                    'type'            => Controls_Manager::TEXT,
                    'label_block'     => true,
                    'default'         => '#',
                    'condition'       => [
                        'hotspot_type'   => 'text',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_position', [ 'label' => __( 'Position', 'power-pack' ) ] );

            $repeater->add_control(
                'left_position',
                [
                    'label'         => __( 'Left Position', 'power-pack' ),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px' 	=> [
                            'min' 	=> 0,
                            'max' 	=> 100,
                            'step'	=> 0.1,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
                    ],
                ]
            );

            $repeater->add_control(
                'top_position',
                [
                    'label'         => __( 'Top Position', 'power-pack' ),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px' 	=> [
                            'min' 	=> 0,
                            'max' 	=> 100,
                            'step'	=> 0.1,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_tooltip', [ 'label' => __( 'Tooltip', 'power-pack' ) ] );
        
            $repeater->add_control(
                'tooltip',
                [
                    'label'           => __( 'Tooltip', 'power-pack' ),
                    'type'            => Controls_Manager::SWITCHER,
                    'default'         => '',
                    'label_on'        => __( 'Show', 'power-pack' ),
                    'label_off'       => __( 'Hide', 'power-pack' ),
                    'return_value'    => 'yes',
                ]
            );

            $repeater->add_control(
                'tooltip_position_local',
                [
                    'label'                 => __( 'Tooltip Position', 'power-pack' ),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'global',
                    'options'               => [
                        'global'        => __( 'Global', 'power-pack' ),
                        'top'           => __( 'Top', 'power-pack' ),
                        'bottom'        => __( 'Bottom', 'power-pack' ),
                        'left'          => __( 'Left', 'power-pack' ),
                        'right'         => __( 'Right', 'power-pack' ),
                        'top-left'      => __( 'Top Left', 'power-pack' ),
                        'top-right'     => __( 'Top Right', 'power-pack' ),
                        'bottom-left'   => __( 'Bottom Left', 'power-pack' ),
                        'bottom-right'  => __( 'Bottom Right', 'power-pack' ),
                    ],
                    'condition'       => [
                        'tooltip'   => 'yes',
                    ],
                ]
            );
        
            $repeater->add_control(
                'tooltip_content',
                [
                    'label'           => __( 'Tooltip Content', 'power-pack' ),
                    'type'            => Controls_Manager::WYSIWYG,
                    'default'         => __( 'Tooltip Content', 'power-pack' ),
                    'condition'       => [
                        'tooltip'   => 'yes',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'hot_spots',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'feature_text'    => __( 'Hotspot #1', 'power-pack' ),
						'feature_icon'    => 'fa fa-plus',
                        'left_position'   => 20,
                        'top_position'    => 30,
                    ],
                ],
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '{{{ hotspot_text }}}',
            ]
        );
        
        $this->add_control(
            'hotspot_pulse',
            [
                'label'                 => __( 'Glow Effect', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Tooltip Settings
         */
        $this->start_controls_section(
            'section_tooltip',
            [
                'label'                 => __( 'Tooltip Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'tooltip_arrow',
            [
                'label'                 => __( 'Show Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'tooltip_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'default',
                'options'               => [
                    'default'       => __( 'Default', 'power-pack' ),
                    'tiny'          => __( 'Tiny', 'power-pack' ),
                    'small'         => __( 'Small', 'power-pack' ),
                    'large'         => __( 'Large', 'power-pack' )
                ],
            ]
        );
        
        $this->add_control(
            'tooltip_position',
            [
                'label'                 => __( 'Global Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'top',
                'options'               => [
                    'top'           => __( 'Top', 'power-pack' ),
                    'bottom'        => __( 'Bottom', 'power-pack' ),
                    'left'          => __( 'Left', 'power-pack' ),
                    'right'         => __( 'Right', 'power-pack' ),
                    'top-left'      => __( 'Top Left', 'power-pack' ),
                    'top-right'     => __( 'Top Right', 'power-pack' ),
                    'bottom-left'   => __( 'Bottom Left', 'power-pack' ),
                    'bottom-right'  => __( 'Bottom Right', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'tooltip_animation_in',
            [
                'label'                 => __( 'Animation In', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
                'default'               => '',
                'options'               => [
                    'bounce'            => __( 'Bounce', 'power-pack' ),
                    'flash'             => __( 'Flash', 'power-pack' ),
                    'pulse'             => __( 'Pulse', 'power-pack' ),
                    'rubberBand'        => __( 'rubberBand', 'power-pack' ),
                    'shake'             => __( 'Shake', 'power-pack' ),
                    'swing'             => __( 'Swing', 'power-pack' ),
                    'tada'              => __( 'Tada', 'power-pack' ),
                    'wobble'            => __( 'Wobble', 'power-pack' ),
                    'bounceIn'          => __( 'bounceIn', 'power-pack' ),
                    'bounceInDown'      => __( 'bounceInDown', 'power-pack' ),
                    'bounceInLeft'      => __( 'bounceInLeft', 'power-pack' ),
                    'bounceInRight'     => __( 'bounceInRight', 'power-pack' ),
                    'bounceInUp'        => __( 'bounceInUp', 'power-pack' ),
                    'bounceOut'         => __( 'bounceOut', 'power-pack' ),
                    'bounceOutDown'     => __( 'bounceOutDown', 'power-pack' ),
                    'bounceOutLeft'     => __( 'bounceOutLeft', 'power-pack' ),
                    'bounceOutRight'    => __( 'bounceOutRight', 'power-pack' ),
                    'bounceOutUp'       => __( 'bounceOutUp', 'power-pack' ),
                    'fadeIn'            => __( 'fadeIn', 'power-pack' ),
                    'fadeInDown'        => __( 'fadeInDown', 'power-pack' ),
                    'fadeInDownBig'     => __( 'fadeInDownBig', 'power-pack' ),
                    'fadeInLeft'        => __( 'fadeInLeft', 'power-pack' ),
                    'fadeInLeftBig'     => __( 'fadeInLeftBig', 'power-pack' ),
                    'fadeInRight'       => __( 'fadeInRight', 'power-pack' ),
                    'fadeInRightBig'    => __( 'fadeInRightBig', 'power-pack' ),
                    'fadeInUp'          => __( 'fadeInUp', 'power-pack' ),
                    'fadeInUpBig'       => __( 'fadeInUpBig', 'power-pack' ),
                    'fadeOut'           => __( 'fadeOut', 'power-pack' ),
                    'fadeOutDown'       => __( 'fadeOutDown', 'power-pack' ),
                    'fadeOutDownBig'    => __( 'fadeOutDownBig', 'power-pack' ),
                    'fadeOutLeft'       => __( 'fadeOutLeft', 'power-pack' ),
                    'fadeOutLeftBig'    => __( 'fadeOutLeftBig', 'power-pack' ),
                    'fadeOutRight'      => __( 'fadeOutRight', 'power-pack' ),
                    'fadeOutRightBig'   => __( 'fadeOutRightBig', 'power-pack' ),
                    'fadeOutUp'         => __( 'fadeOutUp', 'power-pack' ),
                    'fadeOutUpBig'      => __( 'fadeOutUpBig', 'power-pack' ),
                    'flip'              => __( 'flip', 'power-pack' ),
                    'flipInX'           => __( 'flipInX', 'power-pack' ),
                    'flipInY'           => __( 'flipInY', 'power-pack' ),
                    'flipOutX'          => __( 'flipOutX', 'power-pack' ),
                    'flipOutY'          => __( 'flipOutY', 'power-pack' ),
                    'lightSpeedIn'      => __( 'lightSpeedIn', 'power-pack' ),
                    'lightSpeedOut'     => __( 'lightSpeedOut', 'power-pack' ),
                    'rotateIn'          => __( 'rotateIn', 'power-pack' ),
                    'rotateInDownLeft'  => __( 'rotateInDownLeft', 'power-pack' ),
                    'rotateInDownLeft'  => __( 'rotateInDownRight', 'power-pack' ),
                    'rotateInUpLeft'    => __( 'rotateInUpLeft', 'power-pack' ),
                    'rotateInUpRight'   => __( 'rotateInUpRight', 'power-pack' ),
                    'rotateOut'         => __( 'rotateOut', 'power-pack' ),
                    'rotateOutDownLeft' => __( 'rotateOutDownLeft', 'power-pack' ),
                    'rotateOutDownLeft' => __( 'rotateOutDownRight', 'power-pack' ),
                    'rotateOutUpLeft'   => __( 'rotateOutUpLeft', 'power-pack' ),
                    'rotateOutUpRight'  => __( 'rotateOutUpRight', 'power-pack' ),
                    'hinge'             => __( 'Hinge', 'power-pack' ),
                    'rollIn'            => __( 'rollIn', 'power-pack' ),
                    'rollOut'           => __( 'rollOut', 'power-pack' ),
                    'zoomIn'            => __( 'zoomIn', 'power-pack' ),
                    'zoomInDown'        => __( 'zoomInDown', 'power-pack' ),
                    'zoomInLeft'        => __( 'zoomInLeft', 'power-pack' ),
                    'zoomInRight'       => __( 'zoomInRight', 'power-pack' ),
                    'zoomInUp'          => __( 'zoomInUp', 'power-pack' ),
                    'zoomOut'           => __( 'zoomOut', 'power-pack' ),
                    'zoomOutDown'       => __( 'zoomOutDown', 'power-pack' ),
                    'zoomOutLeft'       => __( 'zoomOutLeft', 'power-pack' ),
                    'zoomOutRight'      => __( 'zoomOutRight', 'power-pack' ),
                    'zoomOutUp'         => __( 'zoomOutUp', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'tooltip_animation_out',
            [
                'label'                 => __( 'Animation Out', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
                'default'               => '',
                'options'               => [
                    'bounce'            => __( 'Bounce', 'power-pack' ),
                    'flash'             => __( 'Flash', 'power-pack' ),
                    'pulse'             => __( 'Pulse', 'power-pack' ),
                    'rubberBand'        => __( 'rubberBand', 'power-pack' ),
                    'shake'             => __( 'Shake', 'power-pack' ),
                    'swing'             => __( 'Swing', 'power-pack' ),
                    'tada'              => __( 'Tada', 'power-pack' ),
                    'wobble'            => __( 'Wobble', 'power-pack' ),
                    'bounceIn'          => __( 'bounceIn', 'power-pack' ),
                    'bounceInDown'      => __( 'bounceInDown', 'power-pack' ),
                    'bounceInLeft'      => __( 'bounceInLeft', 'power-pack' ),
                    'bounceInRight'     => __( 'bounceInRight', 'power-pack' ),
                    'bounceInUp'        => __( 'bounceInUp', 'power-pack' ),
                    'bounceOut'         => __( 'bounceOut', 'power-pack' ),
                    'bounceOutDown'     => __( 'bounceOutDown', 'power-pack' ),
                    'bounceOutLeft'     => __( 'bounceOutLeft', 'power-pack' ),
                    'bounceOutRight'    => __( 'bounceOutRight', 'power-pack' ),
                    'bounceOutUp'       => __( 'bounceOutUp', 'power-pack' ),
                    'fadeIn'            => __( 'fadeIn', 'power-pack' ),
                    'fadeInDown'        => __( 'fadeInDown', 'power-pack' ),
                    'fadeInDownBig'     => __( 'fadeInDownBig', 'power-pack' ),
                    'fadeInLeft'        => __( 'fadeInLeft', 'power-pack' ),
                    'fadeInLeftBig'     => __( 'fadeInLeftBig', 'power-pack' ),
                    'fadeInRight'       => __( 'fadeInRight', 'power-pack' ),
                    'fadeInRightBig'    => __( 'fadeInRightBig', 'power-pack' ),
                    'fadeInUp'          => __( 'fadeInUp', 'power-pack' ),
                    'fadeInUpBig'       => __( 'fadeInUpBig', 'power-pack' ),
                    'fadeOut'           => __( 'fadeOut', 'power-pack' ),
                    'fadeOutDown'       => __( 'fadeOutDown', 'power-pack' ),
                    'fadeOutDownBig'    => __( 'fadeOutDownBig', 'power-pack' ),
                    'fadeOutLeft'       => __( 'fadeOutLeft', 'power-pack' ),
                    'fadeOutLeftBig'    => __( 'fadeOutLeftBig', 'power-pack' ),
                    'fadeOutRight'      => __( 'fadeOutRight', 'power-pack' ),
                    'fadeOutRightBig'   => __( 'fadeOutRightBig', 'power-pack' ),
                    'fadeOutUp'         => __( 'fadeOutUp', 'power-pack' ),
                    'fadeOutUpBig'      => __( 'fadeOutUpBig', 'power-pack' ),
                    'flip'              => __( 'flip', 'power-pack' ),
                    'flipInX'           => __( 'flipInX', 'power-pack' ),
                    'flipInY'           => __( 'flipInY', 'power-pack' ),
                    'flipOutX'          => __( 'flipOutX', 'power-pack' ),
                    'flipOutY'          => __( 'flipOutY', 'power-pack' ),
                    'lightSpeedIn'      => __( 'lightSpeedIn', 'power-pack' ),
                    'lightSpeedOut'     => __( 'lightSpeedOut', 'power-pack' ),
                    'rotateIn'          => __( 'rotateIn', 'power-pack' ),
                    'rotateInDownLeft'  => __( 'rotateInDownLeft', 'power-pack' ),
                    'rotateInDownLeft'  => __( 'rotateInDownRight', 'power-pack' ),
                    'rotateInUpLeft'    => __( 'rotateInUpLeft', 'power-pack' ),
                    'rotateInUpRight'   => __( 'rotateInUpRight', 'power-pack' ),
                    'rotateOut'         => __( 'rotateOut', 'power-pack' ),
                    'rotateOutDownLeft' => __( 'rotateOutDownLeft', 'power-pack' ),
                    'rotateOutDownLeft' => __( 'rotateOutDownRight', 'power-pack' ),
                    'rotateOutUpLeft'   => __( 'rotateOutUpLeft', 'power-pack' ),
                    'rotateOutUpRight'  => __( 'rotateOutUpRight', 'power-pack' ),
                    'hinge'             => __( 'Hinge', 'power-pack' ),
                    'rollIn'            => __( 'rollIn', 'power-pack' ),
                    'rollOut'           => __( 'rollOut', 'power-pack' ),
                    'zoomIn'            => __( 'zoomIn', 'power-pack' ),
                    'zoomInDown'        => __( 'zoomInDown', 'power-pack' ),
                    'zoomInLeft'        => __( 'zoomInLeft', 'power-pack' ),
                    'zoomInRight'       => __( 'zoomInRight', 'power-pack' ),
                    'zoomInUp'          => __( 'zoomInUp', 'power-pack' ),
                    'zoomOut'           => __( 'zoomOut', 'power-pack' ),
                    'zoomOutDown'       => __( 'zoomOutDown', 'power-pack' ),
                    'zoomOutLeft'       => __( 'zoomOutLeft', 'power-pack' ),
                    'zoomOutRight'      => __( 'zoomOutRight', 'power-pack' ),
                    'zoomOutUp'         => __( 'zoomOutUp', 'power-pack' ),
                ],
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_image_style',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Hotspot
         */
        $this->start_controls_section(
            'section_hotspots_style',
            [
                'label'                 => __( 'Hotspot', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'hotspot_icon_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '14' ],
                'range'                 => [
                    'px' => [
                        'min'   => 6,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#fff',
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before, {{WRAPPER}} .pp-hotspot-icon-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-hot-spot-wrap'
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-hot-spot-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'icon_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-hot-spot-wrap',
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Tooltip
         */
        $this->start_controls_section(
            'section_tooltips_style',
            [
                'label'                 => __( 'Tooltip', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
            ]
        );

        $this->add_control(
            'tooltip_width',
            [
                'label'         => __( 'Width', 'power-pack' ),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px' 	=> [
                        'min' 	=> 50,
                        'max' 	=> 400,
                        'step'	=> 1,
                    ],
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'tooltip_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '.pp-tooltip-{{ID}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}
        ?>
        <div class="pp-image-hotspots">
            <div class="pp-hot-spot-image">
                <?php
                    $i = 1;
                    foreach ( $settings['hot_spots'] as $index => $item ) :

                    $this->add_render_attribute( 'hotspot' . $i, 'class', 'pp-hot-spot-wrap elementor-repeater-item-' . esc_attr( $item['_id'] ) );
        
                    if ( $item['tooltip'] == 'yes' && $item['tooltip_content'] != '' ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'class', 'pp-hot-spot-tooptip' );
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tipso', '<span class="pp-tooltip-'.$this->get_id().'">' . $this->parse_text_editor( $item['tooltip_content'] ) . '</span>' );
                    }
        
                    $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-position-global', $settings['tooltip_position'] );
        
                    if ( $item['tooltip_position_local'] != 'global' ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-position-local', $item['tooltip_position_local'] );
                    }
        
                    if ( $settings['tooltip_size'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-size', $settings['tooltip_size'] );
                    }
        
                    if ( $settings['tooltip_width'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-width', $settings['tooltip_width']['size'] );
                    }
        
                    if ( $settings['tooltip_animation_in'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-animation-in', $settings['tooltip_animation_in'] );
                    }
        
                    if ( $settings['tooltip_animation_out'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-animation-out', $settings['tooltip_animation_out'] );
                    }
        
                    if ( $settings['tooltip_bg_color'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-background', $settings['tooltip_bg_color'] );
                    }
        
                    if ( $settings['tooltip_color'] ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-text-color', $settings['tooltip_color'] );
                    }
        
                    if ( $settings['tooltip_arrow'] == 'yes' ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-arrow', $settings['tooltip_arrow'] );
                    }
        
                    $this->add_render_attribute( 'hotspot_inner_' . $i, 'class', 'pp-hot-spot-inner' );
        
                    if ( $settings['hotspot_pulse'] == 'yes' ) {
                        $this->add_render_attribute( 'hotspot_inner_' . $i, 'class', 'hotspot-animation' );
                    }
                    ?>
                    <span <?php echo $this->get_render_attribute_string( 'hotspot' . $i ); ?>>
                        <span <?php echo $this->get_render_attribute_string( 'hotspot_inner_' . $i ); ?>>
                        <?php
                            if ( $item['hotspot_type'] == 'icon' ) {
                                printf( '<span class="pp-hotspot-icon-wrap"><span class="pp-hotspot-icon tooltip %1$s"></span></span>', esc_attr( $item['hotspot_icon'] ) );
                            }
                            elseif ( $item['hotspot_type'] == 'text' ) {
                                printf( '<span class="pp-hotspot-icon-wrap"><span class="pp-hotspot-text">%1$s</span></span>', esc_attr( $item['hotspot_text'] ) );
                            }
                        ?>
                        </span>
                    </span>
                <?php $i++; endforeach; ?>
                
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
            </div>
        </div>
        <?php
    }

    /**
	 * Render image hotspots widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            var i = 1;
        #>
        <div class="pp-image-hotspots">
            <div class="pp-hot-spot-image">
                <# _.each( settings.hot_spots, function( item ) { #>
                    <#
                        var $hotspot_class      = ( item.tooltip == 'yes' ) ? 'pp-hot-spot-wrap pp-hot-spot-tooptip' : 'pp-hot-spot-wrap';
                        var $tt_content         = ( item.tooltip == 'yes' ) ? item.tooltip_content : '';
                        var $tt_pos_g           = ( settings.tooltip_position != '' ) ? settings.tooltip_position : 'top';
                        var $tt_pos_l           = ( item.tooltip_position_local != 'global' ) ? item.tooltip_position_local : 'global';
                        var $tt_size            = ( settings.tooltip_size ) ? settings.tooltip_size : '';
                        var $tt_width           = ( settings.tooltip_width.size ) ? settings.tooltip_width.size : '';
                        var $tt_animation_in    = ( settings.tooltip_animation_in ) ? settings.tooltip_animation_in : '';
                        var $tt_animation_out   = ( settings.tooltip_animation_out ) ? settings.tooltip_animation_out : '';
                        var $tt_bg_color        = ( settings.tooltip_bg_color ) ? settings.tooltip_bg_color : '#55b555';
                        var $tt_color           = ( settings.tooltip_color ) ? settings.tooltip_color : '#ffffff';
                        var $tt_arrow           = ( settings.tooltip_arrow == 'yes' ) ? settings.tooltip_arrow : '';
                        var $hotspot_animation  = ( settings.hotspot_pulse == 'yes' ) ? 'hotspot-animation' : '';
                    #>
                    <span class="{{ $hotspot_class }} elementor-repeater-item-{{ item._id }}" data-tipso="{{ $tt_content }}" data-tooltip-position-global="{{ $tt_pos_g }}" data-tooltip-position-local="{{ $tt_pos_l }}" data-tooltip-size="{{ $tt_size }}" data-tooltip-width="{{ $tt_width }}" data-tooltip-animation-in="{{ $tt_animation_in }}" data-tooltip-animation-out="{{ $tt_animation_out }}" data-tooltip-background="{{ $tt_bg_color }}" data-tooltip-text-color="{{ $tt_color }}" data-tooltip-arrow="{{ $tt_arrow }}">
                        <span class="pp-hot-spot-inner {{ $hotspot_animation }}">
                            <# if ( item.hotspot_type == 'icon' ) { #>
                                <span class="pp-hotspot-icon-wrap">
                                    <span class="pp-hotspot-icon tooltip {{ item.hotspot_icon }}"></span>
                                </span>
                            <# } else if ( item.hotspot_type == 'text' ) { #>
                                <span class="pp-hotspot-icon-wrap">
                                    <span class="pp-hotspot-icon tooltip">{{ item.hotspot_text }}</span>
                                </span>
                            <# } #>
                        </span>
                    </span>
                <# i++ } ); #>
                
                <# if ( settings.image.url != '' ) { #>
                    <img src="{{ settings.image.url }}">
                <# } #>
            </div>
        </div>
        <?php
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new PA_Image_Hot_Spots_Widget() );