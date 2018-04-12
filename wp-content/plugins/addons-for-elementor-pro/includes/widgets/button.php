<?php

/*
Widget Name: Livemesh Button
Description: Flat style buttons with rich set of customization options.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_Button_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-button';
    }

    public function get_title() {
        return __('Livemesh Button', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Button', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                "label" => __("Button Title", "livemesh-el-addons"),
                'type' => Controls_Manager::TEXT,
                "default" => __("Buy Now", "livemesh-el-addons"),
                'placeholder' => __("Buy Now", "livemesh-el-addons"),
            ]
        );
        $this->add_control(
            'href',
            [
                "description" => __("The URL to which button should point to.", "livemesh-el-addons"),
                "label" => __("Target URL", "livemesh-el-addons"),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => 'true',
                ],
                'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
            ]);

        $this->add_control(
            'icon_type',
            [
                'label' => __('Icon Type', 'livemesh-el-addons'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'livemesh-el-addons'),
                    'icon' => __('Icon', 'livemesh-el-addons'),
                    'icon_image' => __('Icon Image', 'livemesh-el-addons'),
                ],
            ]
        );
        $this->add_control(
            'icon_image',
            [
                'label' => __('Button Image', 'livemesh-el-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                'condition' => [
                    'icon_type' => 'icon_image',
                ],
            ]
        );
        $this->add_control(
            'icon',
            [
                'label' => __('Button Icon', 'livemesh-el-addons'),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => '',
                'condition' => [
                    'icon_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            [
                'label' => __('General', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'button_class', [
                'type' => Controls_Manager::TEXT,
                "description" => __("Set a unique CSS class for the button. (optional).", "livemesh-el-addons"),
                "label" => __("Class", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'button_style', [
                'type' => Controls_Manager::TEXT,
                "description" => __("Inline CSS styling for the button element. (optional).", "livemesh-el-addons"),
                "label" => __("Style", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'button_color', [
                "type" => Controls_Manager::SELECT,
                "description" => __("Can be overridden with custom colors in Style tab.", "livemesh-el-addons"),
                "label" => __("Button Color", "livemesh-el-addons"),
                "options" => array(
                    "default" => __("Default", "livemesh-el-addons"),
                    "black" => __("Black", "livemesh-el-addons"),
                    "blue" => __("Blue", "livemesh-el-addons"),
                    "cyan" => __("Cyan", "livemesh-el-addons"),
                    "green" => __("Green", "livemesh-el-addons"),
                    "orange" => __("Orange", "livemesh-el-addons"),
                    "pink" => __("Pink", "livemesh-el-addons"),
                    "red" => __("Red", "livemesh-el-addons"),
                    "teal" => __("Teal", "livemesh-el-addons"),
                    "trans" => __("Transparent", "livemesh-el-addons"),
                    "semitrans" => __("Semi Transparent", "livemesh-el-addons"),
                ),
                'default' => 'default',
            ]
        );

        $this->add_control(
            'button_type',
            [
                "type" => Controls_Manager::SELECT,
                "label" => __("Button Size", "livemesh-el-addons"),
                "description" => __("Can be overridden with custom padding in Style tab.", "livemesh-el-addons"),
                "options" => [
                    "medium" => __("Medium", "livemesh-el-addons"),
                    "large" => __("Large", "livemesh-el-addons"),
                    "small" => __("Small", "livemesh-el-addons"),
                ],
                'default' => 'medium',

            ]
        );

        $this->add_control(
            'rounded',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Rounded Button?', 'livemesh-el-addons'),
                "description" => __("Can be overridden with custom border radius in Style tab.", "livemesh-el-addons"),
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'default' => 'no',
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
                'default' => 'left',
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
            'section_button_styling',
            [
                'label' => __('General', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_custom_color',
            [
                'label' => __('Button Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_custom_hover_color',
            [
                'label' => __('Button Hover Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __('Custom Padding', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lae-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => false
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Custom Border Radius', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lae-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
            'section_label',
            [
                'label' => __('Button Label', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_label_color',
            [
                'label' => __('Label Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'livemesh-el-addons'),
                'selector' => '{{WRAPPER}} .lae-button',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_styling',
            [
                'label' => __('Icons', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_type' => ['icon', 'icon_image']
                ],
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
                'default' => 24,
                'selectors' => [
                    '{{WRAPPER}} a.lae-button.lae-with-icon img.lae-thumbnail' => 'max-width: {{VALUE}}px;',
                    '{{WRAPPER}} a.lae-button.lae-with-icon span' => 'font-size: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'icon_spacing',
            [
                'label' => __('Spacing', 'livemesh-el-addons'),
                'description' => __('Space between icon/image and label.', 'livemesh-el-addons'),
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
                    '{{WRAPPER}} a.lae-button.lae-with-icon span, {{WRAPPER}} a.lae-button.lae-with-icon img.lae-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} a.lae-button.lae-with-icon span' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_type' => 'icon',
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
                    '{{WRAPPER}} a.lae-button.lae-with-icon:hover span' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_type' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        list($animate_class, $animation_attr) = lae_get_animation_atts($settings['widget_animation']);

        $icon_html = $type = '';

        $class = (!empty($settings['button_class'])) ? ' ' . $settings['button_class'] : '';

        $color_class = ' lae-' . esc_attr($settings['button_color']);
        if (!empty($settings['button_type']))
            $type = ' lae-' . esc_attr($settings['button_type']);

        $rounded = ($settings['rounded'] == 'yes') ? ' lae-rounded' : '';

        $target = $settings['href']['is_external'] ? 'target="_blank"' : '';

        if (!empty($settings['href']['url'])) {
            $link = $settings['href']['url'];
        }
        else {
            $link = '#';
        }

        $style = ($settings['button_style']) ? ' style="' . esc_attr($settings['button_style']) . '"' : '';

        if ($settings['icon_type'] == 'icon_image') {
            if (!empty($settings['icon_image']))
                $icon_html = wp_get_attachment_image($settings['icon_image']['id'], 'thumbnail', false, array('class' => 'lae-image lae-thumbnail'));
        }
        elseif ($settings['icon_type'] == 'icon') {
            $icon_html = '<span class="' . esc_attr($settings['icon']) . '"></span>';
        }

        $button_content = '<a class= "lae-button ' . ((!empty($icon_html)) ? ' lae-with-icon' : '') . esc_attr($class) . $color_class . $type . $rounded . $animate_class . '"' . $style . $animation_attr . ' href="' . esc_url($link) . '"' . esc_html($target) . '>' . $icon_html . esc_html($settings['button_text']) . '</a>';

        if ($settings['align'] != 'none')
            $button_content = '<div class="lae-button-wrap" style="clear: both; text-align:' . esc_attr($settings['align']) . ';">' . $button_content . '</div>';

        echo $button_content;
    }

    protected function content_template() {
    }

}