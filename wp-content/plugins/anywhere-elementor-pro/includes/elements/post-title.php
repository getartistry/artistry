<?php

namespace Aepro;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Text_Shadow;


class Aepro_Post_Title extends Widget_Base{
    public function get_name() {
        return 'ae-post-title';
    }

    public function get_title() {
        return __( 'AE - Title', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-type-tool';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __( 'General', 'ae-pro' ),
            ]
        );
        $this->add_control(
            'title_type',
            [
                'label' => __( 'Title', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'post_title' => __( 'Post Title', 'ae-pro' ),
                    'archive_title' => __( 'Archive Title', 'ae-pro' )
                ],
                'default' => 'post_title',
            ]
        );
        $this->add_control(
            'use_link',
            [
                'label' => __( 'Post Link', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __( 'Yes', 'ae-pro' ),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __( 'No', 'ae-pro' ),
                        'icon' => 'fa fa-ban',
                    ]
                ],
                'default' => '1',
                'condition' => [
                    'title_type' => 'post_title',
                ]
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => __( 'HTML Tag', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __( 'H1', 'ae-pro' ),
                    'h2' => __( 'H2', 'ae-pro' ),
                    'h3' => __( 'H3', 'ae-pro' ),
                    'h4' => __( 'H4', 'ae-pro' ),
                    'h5' => __( 'H5', 'ae-pro' ),
                    'h6' => __( 'H6', 'ae-pro' ),
                    'div' => __( 'div', 'ae-pro' ),
                    'span' => __( 'span', 'ae-pro' )
                ],
                'default' => 'h1',
            ]
        );
        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'ae-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'ae-pro' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'ae-pro' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'ae-pro' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
			[
                'label' => __( 'General', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ae-element-post-title',
            ]
        );

        
        $this->start_controls_tabs('normal');

            $this->start_controls_tab('normal_tab', [
                'label' => __('Normal', 'ae-pro')
            ]);

                $this->add_control(
                    'title_color',
                    [
                        'label' => __( 'Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-post-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Text_Shadow::get_type(),
                    [
                        'name' => 'text_shadow',
                        'selector' => '{{WRAPPER}} .ae-element-post-title',
                    ]
                );

            $this->end_controls_tab();


            $this->start_controls_tab('hover_tab', [
                'label' => __('Hover', 'ae-pro')
            ]);


                $this->add_control(
                    'title_color_hover',
                    [
                        'label' => __( 'Hover Color', 'ae-pro' ),
                        'type' => Controls_Manager::COLOR,
                        'scheme' => [
                            'type' => Scheme_Color::get_type(),
                            'value' => Scheme_Color::COLOR_1,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .ae-element-post-title:hover' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Text_Shadow::get_type(),
                    [
                        'name' => 'text_shadow_hover',
                        'selector' => '{{WRAPPER}} .ae-element-post-title:hover',
                    ]
                );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    protected function render( ) {
        $settings = $this->get_settings();
        $helper = new Helper();
        $post_data = $helper->get_demo_post_data();
        $post_id = $post_data->ID;
        if($settings['title_type'] == 'post_title'){
            $post_title = $post_data->post_title;
            $post_link = get_permalink($post_id );
        }else{
            $post_title = Post_Helper::instance()->get_aepro_the_archive_title();
            $post_link = "";
            $settings['use_link'] = 0;
        }


        $this->add_render_attribute( 'post-title-class', 'class', 'ae-element-post-title' );

        $title_html = '';
        if($settings['use_link'] == 1){
            $title_html = '<a href="'.$post_link.'">';
        }

        $title_html .= sprintf('<%1$s itemprop="name" %2$s>%3$s</%1$s>',$settings['title_tag'],$this->get_render_attribute_string('post-title-class'),$post_title);

        if($settings['use_link'] == 1){
            $title_html .= '</a>';
        }

        echo $title_html;
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Title() );