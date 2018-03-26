<?php

namespace Aepro;

use Elementor;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use WP_Query;

class Aepro_Post_Content extends Widget_Base{
    public function get_name() {
        return 'ae-post-content';
    }

    public function get_title() {
        return __( 'AE - Post Content', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-align-left';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_layout_settings',
            [
                'label' => __( 'Layout Settings', 'ae-pro' )
            ]
        );
        $this->add_control(
            'content_type',
            [
                'label' => __( 'Content', 'ae-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'post_content' => __( 'Post Content', 'ae-pro' ),
                    'term_content' => __( 'Term Description', 'ae-pro' )
                ],
                'default' => 'post_content',
            ]
        );
        $this->add_control(
            'show_excerpt',
            [
                'label' => __( 'Show Excerpt', 'ae-pro' ),
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
                'default' => '0',
                'condition' => [
                    'content_type' => 'post_content',
                ]
            ]
        );

        $this->add_control(
            'excerpt_size',
            [
                'label' => __( 'Excerpt Size', 'ae-pro' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'show_excerpt' => '1',
                ]
            ]
        );
        $this->add_control(
            'enable_the_content_hooks',
            [
                'label' => __( 'Enable "the_content" hooks', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __( 'Yes', 'ae-pro' ),
                'label_off' => __( 'No', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'content_type' => 'post_content',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => __( 'Content', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => __( 'Content Color', 'ae-pro' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => __( 'Content Align', 'ae-pro' ),
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ae-element-post-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __( 'Content Typography', 'ae-pro' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .ae-element-post-content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render( ) {
        $this->add_render_attribute( 'post-content-class', 'class', 'ae-element-post-content' );
        $settings = $this->get_settings();
        $content_type = $settings['content_type'];
        $helper = new Helper();
        $content = "";

        switch ($content_type) {
            case "post_content":
                $post_data = $helper->get_demo_post_data();
                $post_excerpt = wpautop($post_data->post_excerpt);
                $post_content = wpautop($post_data->post_content);
                if($post_data->post_type == 'elementor_library'){
                    return false;
                }

                ?>
                <?php if($settings['show_excerpt']){ ?>
                    <div <?php echo $this->get_render_attribute_string('post-content-class');?>>
                        <?php
                            if($post_excerpt != '') {
                                    $post_excerpt = strip_shortcodes( $post_excerpt );
                                    echo wp_trim_words( $post_excerpt, $settings['excerpt_size'], '...' );
                            }else{
                                    $post_content = strip_shortcodes($post_content);
                                    echo wp_trim_words( $post_content, $settings['excerpt_size'], '...' );
                            }
                        ?>
                    </div>
                <?php }else{ ?>

                    <div <?php echo $this->get_render_attribute_string('post-content-class');?>>
                        <?php
                        if($settings['enable_the_content_hooks'] == 'yes'){
                                $_post = get_post( $post_data->ID );
                                setup_postdata($_post);
                                the_content();
                                wp_reset_postdata();
                        }else{
                                if(Plugin::$instance->db->is_built_with_elementor( $post_data->ID )){
                                    echo Elementor\Plugin::instance()->frontend->get_builder_content( $post_data->ID,true );
                                }else{
                                    $content = do_shortcode($post_content);
                                    if(isset($GLOBALS['wp_embed'])){
                                        $content = $GLOBALS['wp_embed']->autoembed($content);
                                    }
                                    echo $content;
                                }

                        }
                        ?>
                    </div>
                <?php }
                break;
            case "term_content":
                ?>
                <div <?php echo $this->get_render_attribute_string('post-content-class');?>>
                <?php
                if(Plugin::instance()->editor->is_edit_mode()){
                    $preview_term = $helper->get_preview_term_data();
                    if($preview_term['prev_term_id']){
                        $content = Post_Helper::instance()->get_aepro_the_archive_description($preview_term['prev_term_id'],$preview_term['taxonomy']);
                    }else{
                        $content = 'This is term description.';
                    }

                }else{
                    $content = Post_Helper::instance()->get_aepro_the_archive_description();
                }
                echo $content;
                ?>
                </div>
                <?php
                break;
            default:
                echo "Demo";
        }

    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Content() );