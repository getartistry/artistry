<?php

/*
Widget Name: Livemesh Accordion
Description: Displays collapsible content panels to help display information when space is limited.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

namespace LivemeshAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_Accordion_Widget extends Widget_Base {

    public function get_name() {
        return 'lae-accordion';
    }

    public function get_title() {
        return __('Livemesh Accordion', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-select';
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
            'section_accordion',
            [
                'label' => __('Accordion', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(

            'style', [
                'type' => Controls_Manager::SELECT,
                'label' => __('Choose Style', 'livemesh-el-addons'),
                'default' => 'style1',
                'options' => [
                    'style1' => __('Style 1', 'livemesh-el-addons'),
                    'style2' => __('Style 2', 'livemesh-el-addons'),
                    'style3' => __('Style 3', 'livemesh-el-addons'),
                ],
                'prefix_class' => 'lae-accordion-',
            ]
        );

        $this->add_control(

            'toggle', [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label' => __('Allow to function like toggle?', 'livemesh-el-addons'),
            ]
        );


        $this->add_control(

            'expanded', [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label' => __('Start expanded?', 'livemesh-el-addons'),
                'condition' => [
                    'toggle' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'panels',
            [
                'label' => __('Accordion Items', 'livemesh-el-addons'),
                'type' => Controls_Manager::REPEATER,
                'separator' => 'before',
                'default' => [
                    [
                        'panel_title' => __('Accordion Panel #1', 'livemesh-el-addons'),
                        'panel_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'livemesh-el-addons'),
                    ],
                    [
                        'panel_title' => __('Accordion Panel #2', 'livemesh-el-addons'),
                        'panel_content' => __('I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'livemesh-el-addons'),
                    ],
                ],
                'fields' => [
                    [
                        'name' => 'panel_id',
                        'label' => __('Panel ID', 'livemesh-el-addons'),
                        'description' => __('The Panel ID is required to link to a panel. It must be unique across the page, must begin with a letter and may be followed by any number of letters, digits, hyphens or underscores.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'panel_title',
                        'label' => __('Panel Title & Content', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'default' => __('Accordion Title', 'livemesh-el-addons'),
                        'label_block' => true,
                    ],
                    [
                        'name' => 'panel_content',
                        'label' => __('Panel Content', 'livemesh-el-addons'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => __('Accordion Content', 'livemesh-el-addons'),
                        'show_label' => false,
                    ],
                ],
                'title_field' => '{{{ panel_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_accordion_style',
            [
                'label' => __( 'Accordion', 'livemesh-el-addons' ),
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .lae-accordion .lae-panel-title',
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
                    '{{WRAPPER}} .lae-accordion .lae-panel-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .lae-accordion .lae-panel-content',
            ]
        );

    }

    protected function render() {

        $settings = $this->get_settings();
        ?>

        <div class="lae-accordion lae-<?php echo $settings['style']; ?>"
             data-toggle="<?php echo($settings['toggle'] == 'yes' ? "true" : "false"); ?>"
             data-expanded="<?php echo($settings['expanded'] == 'yes' ? "true" : "false"); ?>">

            <?php foreach ($settings['panels'] as $panel) : ?>

                <?php

                    if (empty($panel['panel_id']))
                        $panel_id = sanitize_title_with_dashes($panel['panel_title']);
                    else
                        $panel_id = $panel['panel_id'];

                ?>

                <div class="lae-panel" id="<?php echo $panel_id; ?>">

                    <div class="lae-panel-title"><?php echo esc_html($panel['panel_title']); ?></div>

                    <div class="lae-panel-content"><?php echo $this->parse_text_editor($panel['panel_content']); ?></div>

                </div>

                <?php

            endforeach;

            ?>

        </div>

        <?php
    }

    protected function _content_template() {
        ?>
            <div class="lae-accordion lae-{{ settings.style }}"
                 data-toggle="{{ settings.toggle == 'yes'  ? 'true' : 'false' }}"
                 data-expanded="{{ settings.expanded == 'yes' ? 'true' : 'false' }}">

                <# if ( settings.panels ) {

                    settings.panels.forEach(function(panel) { #>

                    <div class="lae-panel">

                        <div class="lae-panel-title">{{{ panel.panel_title }}}</div>

                        <div class="lae-panel-content">{{{ panel.panel_content }}}</div>

                    </div>

                    <# });
                } #>
            </div>

        <?php

    }

}