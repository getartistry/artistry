<?php

/*
Widget Name: Livemesh Icon List
Description: Use images or icon fonts to create social icons list, show payment options etc.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_Icon_List_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-icon-list';
    }

    public function get_title() {
        return __('Livemesh Icon List', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-form-vertical';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
            'waypoints',
            'jquery-powertip'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_icon_list',
            [
                'label' => __('Icon List', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'icon_list',
            [
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'icon_title' => __('Facebook', 'livemesh-el-addons'),
                        'icon_type' => 'icon',
                        'icon' => 'fa fa-facebook',
                        'href' => ['url' => 'http://facebook.com', 'is_external' => true]
                    ],
                    [
                        'icon_title' => __('Twitter', 'livemesh-el-addons'),
                        'icon_type' => 'icon',
                        'icon' => 'fa fa-twitter',
                        'href' => ['url' => 'http://twitter.com', 'is_external' => true]
                    ],
                    [
                        'icon_title' => __('Linkedin', 'livemesh-el-addons'),
                        'icon_type' => 'icon',
                        'icon' => 'fa fa-linkedin',
                        'href' => ['url' => 'http://linkedin.com', 'is_external' => true]
                    ],
                    [
                        'icon_title' => __('Google Plus', 'livemesh-el-addons'),
                        'icon_type' => 'icon',
                        'icon' => 'fa fa-google-plus',
                        'href' => ['url' => 'http://linkedin.com', 'is_external' => true]
                    ],
                    [
                        'icon_title' => __('Dribbble', 'livemesh-el-addons'),
                        'icon_type' => 'icon',
                        'icon' => 'fa fa-dribbble',
                        'href' => ['url' => 'http://dribbble.com', 'is_external' => true]
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'icon_title',
                        'label' => __('Icon Title', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'icon_type',
                        'label' => __('Icon Type', 'livemesh-el-addons'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'icon',
                        'options' => [
                            'icon' => __('Icon', 'livemesh-el-addons'),
                            'icon_image' => __('Icon Image', 'livemesh-el-addons'),
                        ],
                    ],
                    [
                        'name' => 'icon_image',
                        'label' => __('Icon Image', 'livemesh-el-addons'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                        'condition' => [
                            'icon_type' => 'icon_image',
                        ],
                    ],
                    [
                        'name' => 'icon',
                        'label' => __('Icon', 'livemesh-el-addons'),
                        'type' => Controls_Manager::ICON,
                        'label_block' => true,
                        'default' => '',
                        'condition' => [
                            'icon_type' => 'icon',
                        ],
                    ],
                    [
                        'name' => 'href',
                        'label' => __('Link', 'livemesh-el-addons'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'default' => [
                            'url' => '',
                            'is_external' => 'true',
                        ],
                        'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
                    ],

                ],
                'title_field' => '{{{ icon_title }}}',
            ]
        );

        $this->add_control(
            'heading_settings',
            [
                'label' => __( 'Settings', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => __('Alignment', 'livemesh-el-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'livemesh-el-addons'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'livemesh-el-addons'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'livemesh-el-addons'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'livemesh-el-addons'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .lae-icon-list' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widget_animation',
            [
                "type" => Controls_Manager::SELECT,
                "label" => __("Animation Type", "livemesh-el-addons"),
                'options' => lae_get_animation_options(),
                'default' => 'none',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_styling',
            [
                'label' => __('Icons', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => __('Icon/Image size in pixels', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 128,
                'step' => 1,
                'default' => 32,
                'selectors' => [
                    '{{WRAPPER}} .lae-icon-list-item .lae-image-wrapper img' => 'width: {{VALUE}}px;',
                    '{{WRAPPER}} .lae-icon-list-item .lae-icon-wrapper span' => 'font-size: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'icon_spacing',
            [
                'label' => __('Spacing', 'livemesh-el-addons'),
                'description' => __('Space between icons.', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => 0,
                    'right' => 15,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lae-icon-list .lae-icon-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => false
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-icon-list-item .lae-icon-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => __('Icon Hover Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-icon-list-item .lae-icon-wrapper span:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tooltip_styling',
            [
                'label' => __('Tooltip', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label' => __('Tooltip Background Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '#powerTip' => 'background-color: {{VALUE}};',
                    '#powerTip.n:before' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label' => __('Tooltip Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '#powerTip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_padding',
            [
                'label' => __('Padding', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '#powerTip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => true
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_typography',
                'label' => __('Typography', 'livemesh-el-addons'),
                'selector' => '#powerTip',
            ]
        );

    }

    protected function render() {

        $settings = $this->get_settings();
        ?>


        <?php list($animate_class, $animation_attr) = lae_get_animation_atts($settings['widget_animation']); ?>

        <div class="lae-icon-list lae-align<?php echo $settings['align']; ?>">

            <?php foreach ($settings['icon_list'] as $icon_item): ?>

                <?php $icon_type = esc_html($icon_item['icon_type']); ?>

                <?php $icon_title = esc_html($icon_item['icon_title']); ?>

                <?php $icon_url = !empty($icon_item['href']['url']) ? $icon_item['href']['url'] : null; ?>

                <?php $target = $icon_item['href']['is_external'] ? 'target="_blank"' : ''; ?>

                <div class="lae-icon-list-item<?php echo $animate_class; ?>" <?php echo $animation_attr; ?>
                     title="<?php echo $icon_title; ?>">

                    <?php if (($icon_type == 'icon_image') && !empty($icon_item['icon_image'])) : ?>

                        <?php if (empty($icon_url)) : ?>

                            <div class="lae-image-wrapper">

                                <?php echo wp_get_attachment_image($icon_item['icon_image']['id'], 'full', false, array('class' => 'lae-image full', 'alt' => $icon_title)); ?>

                            </div>

                        <?php else : ?>

                            <a class="lae-image-wrapper" href="<?php echo $icon_url; ?>" <?php echo $target; ?>>

                                <?php echo wp_get_attachment_image($icon_item['icon_image']['id'], 'full', false, array('class' => 'lae-image full', 'alt' => $icon_title)); ?>

                            </a>

                        <?php endif; ?>

                    <?php else : ?>

                        <?php if (empty($icon_url)) : ?>

                            <div class="lae-icon-wrapper">

                                <span class="<?php echo esc_attr($icon_item['icon']); ?>"></span>

                            </div>

                        <?php else : ?>

                            <a class="lae-icon-wrapper" href="<?php echo $icon_url; ?>" <?php echo $target; ?>>

                                <span class="<?php echo esc_attr($icon_item['icon']); ?>"></span>

                            </a>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

                <?php

            endforeach;

            ?>

        </div>

        <?php
    }

    protected function content_template() {
    }

}