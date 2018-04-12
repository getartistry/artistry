<?php

/*
Widget Name: Livemesh Tabs
Description: Display tabbed content in variety of styles.
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


class LAE_Tabs_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-tabs';
    }

    public function get_title() {
        return __('Livemesh Tabs', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_tabs',
            [
                'label' => __('Tabs', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(

            'style',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Choose Style', 'livemesh-el-addons'),
                'default' => 'style1',
                'options' => [
                    'style1' => __('Tab Style 1', 'livemesh-el-addons'),
                    'style2' => __('Tab Style 2', 'livemesh-el-addons'),
                    'style3' => __('Tab Style 3', 'livemesh-el-addons'),
                    'style4' => __('Tab Style 4', 'livemesh-el-addons'),
                    'style5' => __('Tab Style 5', 'livemesh-el-addons'),
                    'style6' => __('Tab Style 6', 'livemesh-el-addons'),
                    'style7' => __('Vertical Tab Style 1', 'livemesh-el-addons'),
                    'style8' => __('Vertical Tab Style 2', 'livemesh-el-addons'),
                    'style9' => __('Vertical Tab Style 3', 'livemesh-el-addons'),
                    'style10' => __('Vertical Tab Style 4', 'livemesh-el-addons'),
                ],
                'prefix_class' => 'lae-tabs-',
            ]
        );

        $this->add_control(
            'mobile_width',
            [
                'label' => __('Mobile Resolution', 'livemesh-el-addons'),
                'description' => __('The device resolution at which the mobile view takes effect', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 767,
                'min' => 400,
                'max' => 1024,
                'step' => 5,
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __('Tab Panes', 'livemesh-el-addons'),
                'type' => Controls_Manager::REPEATER,
                'separator' => 'before',
                'default' => [
                    [
                        'tab_title' => __('Tab #1', 'livemesh-el-addons'),
                        'tab_content' => __('I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'livemesh-el-addons'),
                    ],
                    [
                        'tab_title' => __('Tab #2', 'livemesh-el-addons'),
                        'tab_content' => __('I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'livemesh-el-addons'),
                    ],
                    [
                        'tab_title' => __('Tab #3', 'livemesh-el-addons'),
                        'tab_content' => __('I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'livemesh-el-addons'),
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'tab_id',
                        'label' => __('Tab ID', 'livemesh-el-addons'),
                        'description' => __('The Tab ID is required to link to a tab. It must be unique across the page, must begin with a letter and may be followed by any number of letters, digits, hyphens or underscores.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'icon_type',
                        'label' => __('Tab Icon Type', 'livemesh-el-addons'),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'none',
                        'options' => [
                            'none' => __('None', 'livemesh-el-addons'),
                            'icon' => __('Icon', 'livemesh-el-addons'),
                            'icon_image' => __('Icon Image', 'livemesh-el-addons'),
                        ],
                    ],
                    [
                        'name' => 'icon_image',
                        'label' => __('Tab Image', 'livemesh-el-addons'),
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
                        'label' => __('Tab Icon', 'livemesh-el-addons'),
                        'type' => Controls_Manager::ICON,
                        'label_block' => true,
                        'default' => '',
                        'condition' => [
                            'icon_type' => 'icon',
                        ],
                    ],
                    [
                        'name' => 'tab_title',
                        'label' => __('Tab Title & Content', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Tabs Title', 'livemesh-el-addons'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'tab_content',
                        'label' => __('Tab Content', 'livemesh-el-addons'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => __('Tabs Content', 'livemesh-el-addons'),
                        'show_label' => false,
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab_title',
            [
                'label' => __( 'Tab Title', 'livemesh-el-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Tab Title Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_title_color',
            [
                'label' => __( 'Active Tab Title Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab.lae-active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_title_color',
            [
                'label' => __( 'Hover Tab Title Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'highlight_color',
            [
                'label' => __('Tab highlight Border color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f94213',
                'selectors' => [
                    '{{WRAPPER}}.lae-tabs-style4 .lae-tab-nav .lae-tab.lae-active:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}}.lae-tabs-style4.lae-mobile-layout.lae-mobile-open .lae-tab.lae-active' => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}}.lae-tabs-style4.lae-mobile-layout.lae-mobile-open .lae-tab.lae-active' => 'border-right-color: {{VALUE}};',
                    '{{WRAPPER}}.lae-tabs-style6 .lae-tab-nav .lae-tab.lae-active a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}.lae-tabs-style7 .lae-tab-nav .lae-tab.lae-active a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}.lae-tabs-style8 .lae-tab-nav .lae-tab.lae-active a' => 'border-left-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['style4', 'style6', 'style7', 'style8'],
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label' => __('Tab Title Padding', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => false
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab .lae-tab-title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab_content',
            [
                'label' => __( 'Tab Content', 'livemesh-el-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_spacing',
            [
                'label' => __('Tab Content Padding', 'livemesh-el-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-panes .lae-tab-pane' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'isLinked' => false
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-panes .lae-tab-pane' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .lae-tabs .lae-tab-panes .lae-tab-pane',
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
                'label' => __('Icon or Icon Image size in pixels', 'livemesh-el-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 256,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab .lae-image-wrapper img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab .lae-icon-wrapper span' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab .lae-icon-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_icon_color',
            [
                'label' => __('Active Tab Icon Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab.lae-active .lae-icon-wrapper span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_icon_color',
            [
                'label' => __('Hover Tab Icon Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lae-tabs .lae-tab-nav .lae-tab .lae-icon-wrapper:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );
    }

    protected function render() {

        $settings = $this->get_settings();

        $plain_styles = array('style2', 'style6', 'style7');

        $vertical_class = '';

        $vertical_styles = array('style7', 'style8', 'style9', 'style10');

        if (in_array($settings['style'], $vertical_styles, true)):

            $vertical_class = 'lae-vertical';

        endif;

        foreach ($settings['tabs'] as $tab) :

            if (in_array($settings['style'], $plain_styles, true)):

                $icon_type = 'none'; // do not display icons for plain styles even if chosen by the user

            else :

                $icon_type = $tab['icon_type'];

            endif;

            if (empty($tab['tab_id']))
                $tab_id = sanitize_title_with_dashes($tab['tab_title']);
            else
                $tab_id = $tab['tab_id'];

            $tab_element = '<a class="lae-tab-label" href="#' . $tab_id . '">';

            if ($icon_type == 'icon_image') :

                $tab_element .= '<span class="lae-image-wrapper">';

                $icon_image = $tab['icon_image'];

                $tab_element .= wp_get_attachment_image($icon_image['id'], 'thumbnail', false, array('class' => 'lae-image'));

                $tab_element .= '</span>';

            elseif ($icon_type == 'icon') :

                $tab_element .= '<span class="lae-icon-wrapper">';

                $tab_element .= '<span class="' . esc_attr($tab['icon']) . '"></span>';

                $tab_element .= '</span>';

            endif;

            $tab_element .= '<span class="lae-tab-title">';

            $tab_element .= esc_html($tab['tab_title']);

            $tab_element .= '</span>';

            $tab_element .= '</a>';

            $tab_nav = '<div class="lae-tab">' . $tab_element . '</div>';

            $tab_content = '<div id="' . $tab_id . '" class="lae-tab-pane">' . $this->parse_text_editor($tab['tab_content']) . '</div>';

            $tab_elements[] = $tab_nav;

            $tab_panes[] = $tab_content;

        endforeach;

        ?>

        <div class="lae-tabs <?php echo $vertical_class; ?> <?php echo esc_attr($settings['style']); ?>"
             data-mobile-width="<?php echo intval($settings['mobile_width']); ?>">

            <a href="#" class="lae-tab-mobile-menu"><i class="lae-icon-menu"></i>&nbsp;</a>

            <div class="lae-tab-nav">

                <?php

                foreach ($tab_elements as $tab_nav) :

                    echo $tab_nav;

                endforeach;

                ?>

            </div>

            <div class="lae-tab-panes">

                <?php

                foreach ($tab_panes as $tab_pane) :

                    echo $tab_pane;

                endforeach;

                ?>

            </div>

        </div>

        <?php
    }

    protected function content_template() {

    }

}