<?php

/*
Widget Name: Livemesh FAQ
Description: Create a responsive faq of custom HTML content.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_FAQ_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-faq';
    }

    public function get_title() {
        return __('Livemesh FAQ', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-bullet-list';
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
            'section_faq',
            [
                'label' => __('FAQ', 'livemesh-el-addons'),
            ]
        );


        $this->add_control(
            'per_line',
            [
                'label' => __('Columns per row', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 2,
            ]
        );

        $this->add_control(
            'faq_heading',
            [
                'label' => __('FAQ Items', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'faq_list',
            [
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'question' => 'Nam commodo suscipit quam?',
                        'answer' => 'Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Donec venenatis vulputate lorem. In hac habitasse aliquam.',
                    ],
                    [
                        'question' => 'Morbi mattis ullamcorper velit?',
                        'answer' => 'Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Phasellus nec sem in justo pellentesque facilisis platea dictumst.',
                    ],
                    [
                        'question' => 'Phasellus leo dolor, tempus non?',
                        'answer' => 'Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Etiam ut purus mattis mauris sodales.',
                    ],
                    [
                        'question' => 'Donec quam felis, ultricies nec?',
                        'answer' => 'Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris ac eros. Nullam tincidunt adipiscing enim.',
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'question',
                        'label' => __('Question & Answer', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                    ],

                    [
                        'name' => 'answer',
                        'label' => __('Answer', 'mo_theme'),
                        'description' => __('The HTML content as answer for the FAQ item.', 'mo_theme'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => __('The HTML content as answer for the FAQ item.', 'livemesh-el-addons'),
                        'show_label' => false,
                    ],

                    [
                        "type" => Controls_Manager::SELECT,
                        "name" => "widget_animation",
                        "label" => __("Animation Type", "livemesh-el-addons"),
                        'options' => lae_get_animation_options(),
                        'default' => 'none',
                    ],

                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'section_faq_style',
            [
                'label' => __( 'FAQ', 'livemesh-el-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );


        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title HTML Tag', 'livemesh-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __( 'H1', 'livemesh-el-addons' ),
                    'h2' => __( 'H2', 'livemesh-el-addons' ),
                    'h3' => __( 'H3', 'livemesh-el-addons' ),
                    'h4' => __( 'H4', 'livemesh-el-addons' ),
                    'h5' => __( 'H5', 'livemesh-el-addons' ),
                    'h6' => __( 'H6', 'livemesh-el-addons' ),
                    'div' => __( 'div', 'livemesh-el-addons' ),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-faq-list .lae-faq-item .lae-faq-question' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .lae-faq-list .lae-faq-item .lae-faq-question',
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label' => __( 'Content', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-faq-list .lae-faq-item .lae-faq-answer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .lae-faq-list .lae-faq-item .lae-faq-answer',
            ]
        );
    }

    protected function render() {

        $settings = $this->get_settings();
        ?>

        <?php $column_style = lae_get_column_class(intval($settings['per_line'])); ?>

        <div class="lae-faq-list lae-grid-container">

            <?php foreach ($settings['faq_list'] as $faq): ?>

                <?php list($animate_class, $animation_attr) = lae_get_animation_atts($faq['widget_animation']); ?>

                <div class="lae-faq-item <?php echo $column_style; ?><?php echo $animate_class; ?>" <?php echo $animation_attr; ?>>

                    <<?php echo esc_html($settings['title_tag']); ?> class="lae-faq-question"><?php echo esc_html($faq['question']) ?></<?php echo esc_html($settings['title_tag']); ?>>

                    <div class="lae-faq-answer"><?php echo do_shortcode($faq['answer']) ?></div>

                </div>

                <?php

            endforeach;

            ?>

        </div>

        <div class="lae-clear"></div>

        <?php
    }

    protected function content_template() {
    }

}