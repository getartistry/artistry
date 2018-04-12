<?php

/*
Widget Name: Livemesh Image Slider
Description: Create a responsive image slider.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_Image_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-image-slider';
    }

    public function get_title() {
        return __('Livemesh Image Slider', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-thumbnails-down';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
            'jquery-flexslider',
            'jquery-nivo',
            'slick',
            'responsiveslides'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_image_slider',
            [
                'label' => __('Image Slider', 'livemesh-el-addons'),
            ]
        );


        $this->add_control(

            'class', [
                'type' => Controls_Manager::TEXT,
                "description" => __("Provide a unique CSS class for the slider. (optional).", "livemesh-el-addons"),
                "label" => __("Class", "livemesh-el-addons"),
                'prefix_class' => 'lae-image-slider-',
            ]
        );

        $this->add_control(

            'caption_style', [
                'type' => Controls_Manager::SELECT,
                'label' => __('Caption Style', 'livemesh-el-addons'),
                'default' => 'style1',
                'options' => [
                    'style1' => __('Style 1', 'livemesh-el-addons'),
                    'style2' => __('Style 2', 'livemesh-el-addons'),
                ],
            ]
        );

        $this->add_control(
            'image_slider_heading',
            [
                'label' => __('Image Slides', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'image_slides',
            [
                'type' => Controls_Manager::REPEATER,
                'default' => [],
                'fields' => [


                    [
                        'name' => 'slide_name',
                        'label' => __('Slide Name', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'description' => __('The title to identify the slide', 'livemesh-el-addons'),
                    ],

                    [
                        'name' => 'slide_image',
                        'label' => __('Slide Image', 'livemesh-el-addons'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                    ],

                    [
                        'name' => 'slide_url',
                        'label' => __('URL to link to by image and caption heading. (optional)', 'livemesh-el-addons'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'default' => [
                            'url' => '',
                            'is_external' => 'false',
                        ],
                        'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
                    ],

                    [
                        'name' => 'heading',
                        'label' => __('Caption Heading', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],

                    [
                        'name' => 'subheading',
                        'label' => __('Caption Subheading', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],


                    [
                        'name' => 'caption_button_heading',
                        'label' => __('Caption Button', 'livemesh-el-addons'),
                        'type' => Controls_Manager::HEADING,
                    ],

                    [
                        'name' => 'button_text',
                        'label' => __('Button Text', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],

                    [
                        'name' => 'button_url',
                        'label' => __('Button URL', 'livemesh-el-addons'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'default' => [
                            'url' => '',
                            'is_external' => 'true',
                        ],
                        'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
                    ],

                    [
                        "name" => "button_color",
                        "type" => Controls_Manager::SELECT,
                        "label" => __("Button Color", "livemesh-el-addons"),
                        "options" => array(
                            "default" => __("Default", "livemesh-el-addons"),
                            "custom" => __("Custom", "livemesh-el-addons"),
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
                    ],

                    [
                        "name" => "button_size",
                        "type" => Controls_Manager::SELECT,
                        "label" => __("Button Size", "livemesh-el-addons"),
                        "options" => array(
                            "medium" => __("Medium", "livemesh-el-addons"),
                            "large" => __("Large", "livemesh-el-addons"),
                            "small" => __("Small", "livemesh-el-addons"),
                        ),
                        'default' => 'medium',
                    ],

                    [
                        "name" => "rounded",
                        'type' => Controls_Manager::SWITCHER,
                        'label' => __('Rounded Button?', 'livemesh-el-addons'),
                        'label_off' => __('No', 'livemesh-el-addons'),
                        'label_on' => __('Yes', 'livemesh-el-addons'),
                        'return_value' => 'yes',
                        'default' => 'no',
                    ]

                ],
                'title_field' => '{{{ slide_name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            [
                'label' => __('Slider Options', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        $this->add_control(

            'slider_type', [
                'type' => Controls_Manager::SELECT,
                "label" => __("Slider Type", "livemesh-el-addons"),
                'default' => 'flex',
                "options" => [
                    "flex" => __("Flex Slider", "livemesh-el-addons"),
                    "nivo" => __("Nivo Slider", "livemesh-el-addons"),
                    "slick" => __("Slick Slider", "livemesh-el-addons"),
                    "responsive" => __("Responsive Slider", "livemesh-el-addons"),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'label' => __('Slide Image Size', 'livemesh-el-addons'),
                'default' => 'full',
                'condition' => [
                    'slider_type' => ['flex','slick','responsive'],
                ],
            ]
        );

        $this->add_control(
            'slide_animation',
            [
                'label' => __('Slider Animation', 'livemesh-el-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => __('Slide', 'livemesh-el-addons'),
                    'fade' => __('Fade', 'livemesh-el-addons'),
                ],
                'condition' => [
                    'slider_type' => ['flex'],
                ],
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => __('Sliding Direction', 'livemesh-el-addons'),
                "description" => __("Select the sliding direction.", "livemesh-el-addons"),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Horizontal', 'livemesh-el-addons'),
                    'vertical' => __('Vertical', 'livemesh-el-addons'),
                ],
                'condition' => [
                    'slider_type' => ['flex', 'slick'],
                ],
            ]
        );

        $this->add_control(
            'control_nav',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'separator' => 'before',
                'default' => 'yes',
                "description" => __("Create navigation for paging control of each slide?", "livemesh-el-addons"),
                "label" => __("Control navigation?", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'direction_nav',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                "description" => __("Create navigation for previous/next navigation?", "livemesh-el-addons"),
                "label" => __("Direction navigation?", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'thumbnail_nav',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                "description" => __("Use thumbnails for Control Nav?", "livemesh-el-addons"),
                "label" => __("Thumbnails Navigation?", "livemesh-el-addons"),
                'condition' => [
                    'slider_type' => ['flex', 'nivo'],
                ],
            ]
        );

        $this->add_control(
            'randomize',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'separator' => 'before',
                'default' => 'no',
                "description" => __("Randomize slide order?", "livemesh-el-addons"),
                "label" => __("Randomize slides?", "livemesh-el-addons"),
                'condition' => [
                    'slider_type' => ['flex', 'responsive'],
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                "description" => __("Pause the slideshow when hovering over slider, then resume when no longer hovering.", "livemesh-el-addons"),
                "label" => __("Pause on hover?", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'pause_on_action',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                "description" => __("Pause the slideshow when interacting with control elements.", "livemesh-el-addons"),
                "label" => __("Pause on action?", "livemesh-el-addons"),
                'condition' => [
                    'slider_type' => ['flex'],
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                "description" => __("Should the animation loop?", "livemesh-el-addons"),
                "label" => __("Loop", "livemesh-el-addons"),
                'condition' => [
                    'slider_type' => ['flex', 'slick'],
                ],
            ]
        );

        $this->add_control(
            'slideshow',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'separator' => 'before',
                'default' => 'yes',
                "description" => __("Animate slider automatically without user intervention?", "livemesh-el-addons"),
                "label" => __("Slideshow", "livemesh-el-addons"),
            ]
        );

        $this->add_control(
            'slideshow_speed',
            [
                "description" => __("Set the speed of the slideshow cycling, in milliseconds", "livemesh-el-addons"),
                "label" => __("Slideshow speed", "livemesh-el-addons"),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
            ]
        );


        $this->add_control(
            'animation_speed',
            [
                "description" => __("Set the speed of animations, in milliseconds.", "livemesh-el-addons"),
                "label" => __("Animation speed", "livemesh-el-addons"),
                'type' => Controls_Manager::NUMBER,
                'default' => 600,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_styling',
            [
                'label' => __('Caption Heading', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => __('Heading HTML Tag', 'livemesh-el-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __('H1', 'livemesh-el-addons'),
                    'h2' => __('H2', 'livemesh-el-addons'),
                    'h3' => __('H3', 'livemesh-el-addons'),
                    'h4' => __('H4', 'livemesh-el-addons'),
                    'h5' => __('H5', 'livemesh-el-addons'),
                    'h6' => __('H6', 'livemesh-el-addons'),
                    'div' => __('div', 'livemesh-el-addons'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-heading, {{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-heading a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_hover_color',
            [
                'label' => __('Heading Hover Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-heading a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_hover_border_color',
            [
                'label' => __('Heading Hover Border Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-heading a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'livemesh-el-addons'),
                'selector' => '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-heading',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_subheading',
            [
                'label' => __('Caption Subheading', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subheading_color',
            [
                'label' => __('Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-subheading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subheading_typography',
                'selector' => '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-subheading',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_styling',
            [
                'label' => __('Caption Button', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __('Button Padding', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => false,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'livemesh-el-addons'),
                'selector' => '{{WRAPPER}} .lae-image-slider .lae-slide .lae-caption .lae-button',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        $thumbnail_attr = $button_type = '';

        $slider_options = [
            'slide_animation' => $settings['slide_animation'],
            'direction' => $settings['direction'],
            'slideshow_speed' => absint($settings['slideshow_speed']),
            'animation_speed' => absint($settings['animation_speed']),
            'randomize' => ('yes' === $settings['randomize']),
            'loop' => ('yes' === $settings['loop']),
            'slideshow' => ('yes' === $settings['slideshow']),
            'control_nav' => ('yes' === $settings['control_nav']),
            'direction_nav' => ('yes' === $settings['direction_nav']),
            'thumbnail_nav' => ('yes' === $settings['thumbnail_nav']),
            'pause_on_hover' => ('yes' === $settings['pause_on_hover']),
            'pause_on_action' => ('yes' === $settings['pause_on_action'])
        ];
        ?>

        <div class="lae-image-slider lae-container lae-caption-<?php echo $settings['caption_style']; ?>"
             data-slider-type="<?php echo $settings['slider_type']; ?>"
             data-settings='<?php echo wp_json_encode($slider_options); ?>'>

            <?php if ($settings['slider_type'] == 'flex'): ?>

                <?php if ('yes' == $settings['thumbnail_nav']):

                    $carousel_id = uniqid('lae-carousel-');
                    $slider_id = uniqid('lae-slider-');

                endif; ?>

            <div <?php echo(!empty($slider_id) ? 'id="' . $slider_id . '"' : ''); ?>
                <?php echo(!empty($carousel_id) ? 'data-carousel="' . $carousel_id . '"' : ''); ?>
                    class="lae-flexslider">

                <div class="lae-slides">

                <?php foreach ($settings['image_slides'] as $slide): ?>

                    <?php if (!empty($slide['slide_image']) && wp_attachment_is_image($slide['slide_image']['id'])) : ?>

                        <?php

                        if ('yes' == $settings['thumbnail_nav']):

                            $thumbnail_src = wp_get_attachment_image_src($slide['slide_image']['id'], 'medium');

                            if ($thumbnail_src)
                                $thumbnail_attr = 'data-thumb="' . $thumbnail_src[0] . '"';

                        endif;

                        ?>

                    <div <?php echo $thumbnail_attr; ?> class="lae-slide">

                        <?php $image_html = lae_get_image_html($slide['slide_image'], 'thumbnail_size', $settings); ?>

                        <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                            <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                               title="<?php echo esc_html($slide['slide_name']); ?>"><?php echo $image_html; ?> </a>

                        <?php else: ?>

                            <?php echo $image_html; ?>

                        <?php endif; ?>

                        <?php if (!empty($slide['heading'])): ?>

                            <div class="lae-caption">

                            <?php echo empty($slide['subheading']) ? '' : '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>'; ?>

                            <?php if (!empty($slide['heading'])): ?>

                                <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                    <<?php echo esc_html($settings['heading_tag']); ?> class="lae-heading">
                                    <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                       title="<?php echo $slide['slide_name']; ?>"><?php echo $slide['heading']; ?></a>
                                    </<?php echo esc_html($settings['heading_tag']); ?>>

                                <?php else : ?>

                                    <<?php echo esc_html($settings['heading_tag']); ?> class="lae-heading"><?php echo $slide['heading']; ?></<?php echo esc_html($settings['heading_tag']); ?>>

                                <?php endif; ?>

                            <?php endif; ?>

                            <?php if ($settings['caption_style'] == 'style1' && (!empty($slide['button_url']))) : ?>

                                <?php
                                $color_class = ' lae-' . esc_attr($slide['button_color']);
                                if (!empty($slide['button_type']))
                                    $button_type = ' lae-' . esc_attr($slide['button_type']);

                                $rounded = ($slide['rounded'] == 'yes') ? ' lae-rounded' : '';

                                ?>

                                <a class="lae-button <?php echo $color_class . $button_type . $rounded; ?>"
                                   href="<?php echo esc_url($slide['button_url']['url']); ?>"
                                    <?php echo ($slide['button_url']['is_external']) ? 'target="_blank"' : ''; ?>><?php echo $slide['button_text']; ?></a>

                            <?php endif; ?>

                            </div>

                        <?php endif; ?>


                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

                </div>

                </div>

                <?php if (!empty($carousel_id)): ?>

                    <div id="<?php echo $carousel_id; ?>" class="lae-thumbnailslider lae-flexslider">

                        <div class="lae-slides">

                            <?php foreach ($settings['image_slides'] as $slide): ?>

                                <?php if (!empty($slide['slide_image']) && wp_attachment_is_image($slide['slide_image']['id'])) : ?>

                                    <div class="lae-slide">

                                        <?php echo wp_get_attachment_image($slide['slide_image']['id'], 'medium', false, array('class' => 'lae-image medium', 'alt' => $slide['slide_name'])); ?>

                                    </div>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endif; ?>

            <?php elseif ($settings['slider_type'] == 'nivo') : ?>

                <?php $nivo_captions = array(); ?>

                <div class="nivoSlider">

                    <?php foreach ($settings['image_slides'] as $slide): ?>

                        <?php $caption_index = uniqid('lae-nivo-caption-'); ?>

                        <?php if (!empty($slide['slide_image']) && wp_attachment_is_image($slide['slide_image']['id'])) : ?>

                            <?php

                            $thumbnail_src = wp_get_attachment_image_src($slide['slide_image']['id'], 'medium');

                            if ($thumbnail_src)
                                $thumbnail_src = $thumbnail_src[0];

                            ?>

                            <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                   title="<?php echo $slide['slide_name']; ?>">

                                    <?php echo wp_get_attachment_image($slide['slide_image']['id'], 'full', false, array('class' => 'lae-image full', 'data-thumb' => $thumbnail_src, 'alt' => $slide['slide_name'], 'title' => ('#' . $caption_index))); ?>

                                </a>

                            <?php else : ?>

                                <?php echo wp_get_attachment_image($slide['slide_image']['id'], 'full', false, array('class' => 'lae-image full', 'data-thumb' => $thumbnail_src, 'alt' => $slide['slide_name'], 'title' => ('#' . $caption_index))); ?>

                            <?php endif; ?>

                            <?php if (!empty($slide['heading'])): ?>

                                <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                    <?php $nivo_captions[] = '<div id="' . $caption_index . '" class="nivo-html-caption">' . '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>' . '<h3 class="lae-heading">' . '<a href="' . esc_url($slide['slide_url']['url']) . '" title="' . $slide['slide_name'] . '">' . $slide['heading'] . '</a></h3>' . '</div>'; ?>

                                <?php else : ?>

                                    <?php $nivo_captions[] = '<div id="' . $caption_index . '" class="nivo-html-caption">' . '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>' . '<h3 class="lae-heading">' . $slide['heading'] . '</h3>' . '</div>'; ?>

                                <?php endif; ?>

                            <?php endif; ?>

                            <?php $nivo_captions[] = '<div id="' . $caption_index . '" class="nivo-html-caption">' . '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>' . '<h3 class="lae-heading">' . $slide['heading'] . '</h3>' . '</div>'; ?>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

                <div class="lae-caption nivo-html-caption">

                    <?php foreach ($nivo_captions as $nivo_caption): ?>

                        <?php echo $nivo_caption . "\n"; ?>

                    <?php endforeach; ?>

                </div>


            <?php elseif ($settings['slider_type'] == 'slick') : ?>

                <div class="lae-slickslider">

                    <?php foreach ($settings['image_slides'] as $slide): ?>

                        <div class="lae-slide">

                            <?php if (!empty($slide['slide_image']) && wp_attachment_is_image($slide['slide_image']['id'])) : ?>

                                <?php $image_html = lae_get_image_html($slide['slide_image'], 'thumbnail_size', $settings); ?>

                                <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                    <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                       title="<?php echo esc_html($slide['slide_name']); ?>"><?php echo $image_html; ?> </a>

                                <?php else: ?>

                                    <?php echo $image_html; ?>

                                <?php endif; ?>

                                <div class="lae-caption">

                                    <?php echo empty($slide['subheading']) ? '' : '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>'; ?>

                                    <?php if (!empty($slide['heading'])): ?>

                                        <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                            <h3 class="lae-heading">
                                                <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                                   title="<?php echo $slide['slide_name']; ?>"><?php echo $slide['heading']; ?></a>
                                            </h3>

                                        <?php else : ?>

                                            <h3 class="lae-heading"><?php echo $slide['heading']; ?></h3>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                    <?php if ($settings['caption_style'] == 'style1' && (!empty($slide['button_url']))) : ?>

                                        <?php
                                        $color_class = ' lae-' . esc_attr($slide['button_color']);
                                        if (!empty($slide['button_type']))
                                            $button_type = ' lae-' . esc_attr($slide['button_type']);

                                        $rounded = ($slide['rounded'] == 'yes') ? ' lae-rounded' : '';

                                        ?>

                                        <a class="lae-button <?php echo $color_class . $button_type . $rounded; ?>"
                                           href="<?php echo esc_url($slide['button_url']['url']); ?>"
                                            <?php echo ($slide['button_url']['is_external']) ? 'target="_blank"' : ''; ?>><?php echo $slide['button_text']; ?></a>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php elseif ($settings['slider_type'] == 'responsive') : ?>

                <div class="rslides_container">

                    <ul class="rslides lae-slide">

                        <?php foreach ($settings['image_slides'] as $slide): ?>

                            <li>

                                <?php if (!empty($slide['slide_image']) && wp_attachment_is_image($slide['slide_image']['id'])) : ?>

                                    <?php $image_html = lae_get_image_html($slide['slide_image'], 'thumbnail_size', $settings); ?>

                                    <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                        <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                           title="<?php echo esc_html($slide['slide_name']); ?>"><?php echo $image_html; ?> </a>

                                    <?php else: ?>

                                        <?php echo $image_html; ?>

                                    <?php endif; ?>

                                    <div class="lae-caption">

                                        <?php echo empty($slide['subheading']) ? '' : '<div class="lae-subheading">' . htmlspecialchars_decode($slide['subheading']) . '</div>'; ?>

                                        <?php if (!empty($slide['heading'])): ?>

                                            <?php if (!empty($slide['slide_url']) && !empty($slide['slide_url']['url'])) : ?>

                                                <h3 class="lae-heading">
                                                    <a href="<?php echo esc_url($slide['slide_url']['url']); ?>"
                                                       title="<?php echo $slide['slide_name']; ?>"><?php echo $slide['heading']; ?></a>
                                                </h3>

                                            <?php else : ?>

                                                <h3 class="lae-heading"><?php echo $slide['heading']; ?></h3>

                                            <?php endif; ?>

                                        <?php endif; ?>

                                        <?php if ($settings['caption_style'] == 'style1' && (!empty($slide['button_url']))) : ?>

                                            <?php
                                            $color_class = ' lae-' . esc_attr($slide['button_color']);
                                            if (!empty($slide['button_type']))
                                                $button_type = ' lae-' . esc_attr($slide['button_type']);

                                            $rounded = ($slide['rounded'] == 'yes') ? ' lae-rounded' : '';

                                            ?>

                                            <a class="lae-button <?php echo $color_class . $button_type . $rounded; ?>"
                                               href="<?php echo esc_url($slide['button_url']['url']); ?>"
                                                <?php echo ($slide['button_url']['is_external']) ? 'target="_blank"' : ''; ?>><?php echo $slide['button_text']; ?></a>

                                        <?php endif; ?>

                                    </div>

                                <?php endif; ?>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

        </div>

        <?php
    }

    protected function content_template() {
    }

}