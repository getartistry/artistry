<?php

/*
Widget Name: Livemesh Features
Description: Display product features or services offered
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


class LAE_Features_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-features';
    }

    public function get_title() {
        return __('Livemesh Features', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'fa fa-wpforms';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
            'waypoints'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_features',
            [
                'label' => __('Features', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'feature_class', [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Container Class', 'livemesh-el-addons'),
                'description' => esc_html__('The CSS class for the features container DIV element.', 'livemesh-el-addons'),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'label' => __('Feature Image Size', 'livemesh-el-addons'),
                'default' => 'full',
            ]
        );

        $this->add_control(
            'features_heading',
            [
                'label' => __('Features', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'features',
            [
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'feature_title' => 'Nam commodo suscipit quam',
                        'feature_subtitle' => 'Nam commodo',
                        'feature_text' => 'Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Donec venenatis vulputate lorem. In hac habitasse aliquam.',
                    ],
                    [
                        'feature_title' => 'Morbi mattis ullamcorper velit',
                        'feature_subtitle' => 'Suscipit quam',
                        'feature_text' => 'Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Phasellus nec sem in justo pellentesque facilisis platea dictumst.',
                    ],
                    [
                        'feature_title' => 'Phasellus leo dolor, tempus non',
                        'feature_subtitle' => 'Pellentesque laoreet',
                        'feature_text' => 'Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Etiam ut purus mattis mauris sodales.',
                    ],
                    [
                        'feature_title' => 'Donec quam felis, ultricies nec',
                        'feature_subtitle' => 'Ligula ultrices',
                        'feature_text' => 'Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris ac eros. Nullam tincidunt adipiscing enim.',
                    ],
                ],
                'fields' => [

                    [
                        'name' => 'class',
                        'label' => esc_html__('Feature Class', 'livemesh-el-addons'),
                        'description' => esc_html__('The CSS class for the feature DIV element (optional)', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],

                    [
                        'name' => 'feature_title',
                        'label' => esc_html__('Feature Title', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],

                    [
                        'name' => 'feature_subtitle',
                        'label' => esc_html__('Feature Subtitle', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],

                    [
                        'name' => 'feature_image',
                        'library' => 'image',
                        'label' => esc_html__('Feature Image.', 'livemesh-el-addons'),
                        'description' => esc_html__('An icon image or a bitmap which best represents the feature we are capturing', 'livemesh-el-addons'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                    ],

                    [
                        'name' => 'feature_text',
                        'type' => Controls_Manager::WYSIWYG,
                        "label" => esc_html__("Text", 'livemesh-el-addons'),
                        "description" => esc_html__("The feature content.", 'livemesh-el-addons'),
                        "default" => esc_html__("Feature content goes here.", 'livemesh-el-addons'),
                        'show_label' => true,
                    ],

                    [
                        "type" => Controls_Manager::SELECT,
                        "name" => "image_animation",
                        'label' => __('Animation for the Feature Image', 'livemesh-el-addons'),
                        'options' => lae_get_animation_options(),
                        'default' => 'none',
                    ],

                    [
                        "type" => Controls_Manager::SELECT,
                        "name" => "text_animation",
                        'label' => __('Animation for the Feature Text', 'livemesh-el-addons'),
                        'options' => lae_get_animation_options(),
                        'default' => 'none',
                    ],

                ],
                'title_field' => '{{{ feature_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features_style',
            [
                'label' => __('General', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tiled',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Apply Tiled Design?', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'features_spacing',
            [
                'label' => __('Features Spacing', 'livemesh-el-addons'),
                'description' => __('Takes effect only if tiled design has not been applied', 'livemesh-el-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                    'size' => 80,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lae-features:not(.lae-tiled) .lae-feature' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features_title',
            [
                'label' => __('Title', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'livemesh-el-addons'),
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
            'title_color',
            [
                'label' => __('Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-features .lae-feature .lae-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .lae-features .lae-feature .lae-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features_subtitle',
            [
                'label' => __('Subtitle', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-features .lae-feature .lae-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .lae-features .lae-feature .lae-subtitle',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features_text',
            [
                'label' => __('Text', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-features .lae-feature .lae-feature-details' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .lae-features .lae-feature .lae-feature-details',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings();
        ?>

        <?php $class = (($settings['tiled'] == 'yes') ? 'lae-tiled ' . $settings['feature_class'] : $settings['feature_class']); ?>

        <div class="lae-features lae-container <?php echo esc_attr($class); ?>">

            <?php foreach ($settings['features'] as $feature): ?>

                <div class="lae-feature lae-image-text-toggle <?php echo esc_attr($feature['class']); ?>">

                    <?php list($animate_class, $animation_attr) = lae_get_animation_atts($feature['image_animation']); ?>

                    <div class="lae-feature-image lae-image-content <?php echo $animate_class; ?>" <?php echo $animation_attr; ?>>

                        <?php if (!empty($feature['feature_image'])): ?>

                            <?php $image_html = lae_get_image_html($feature['feature_image'], 'thumbnail_size', $settings); ?>

                            <?php echo $image_html; ?>

                        <?php endif; ?>

                    </div>

                    <?php list($animate_class, $animation_attr) = lae_get_animation_atts($feature['text_animation']); ?>

                    <div class="lae-feature-text lae-text-content <?php echo $animate_class; ?>" <?php echo $animation_attr; ?>>

                        <div class="lae-subtitle"><?php echo esc_html($feature['feature_subtitle']) ?></div>

                        <<?php echo esc_html($settings['title_tag']); ?> class="lae-title"><?php echo esc_html($feature['feature_title']) ?></<?php echo esc_html($settings['title_tag']); ?>>

                    <div class="lae-feature-details"><?php echo $this->parse_text_editor($feature['feature_text']) ?></div>

                </div>

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