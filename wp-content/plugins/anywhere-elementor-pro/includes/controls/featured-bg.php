<?php
namespace Aepro;

use Elementor\Controls_Manager;
use Aepro\Aepro_Control_Manager;
class FeaturedBG{
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('elementor/element/before_section_start',[ $this, 'add_fields'],10,3);

        add_action('elementor/frontend/element/before_render',[ $this, 'before_section_render'],10,1);
    }

    public function add_fields($element,$section_id, $args){
        $helper = new Helper();
        if ( ('section' === $element->get_name() && 'section_background' === $section_id) || ('column' === $element->get_name() && 'section_style' === $section_id)) {

            $element->start_controls_section(
                'post_featured_bg',
                [
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO,
                    'label' => __( 'Featured BG', 'ae-pro' ),
                ]
            );

            $element->add_control(
                'show_featured_bg',
                [
                    'label' => __( 'Show Featured Image Background', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Show', 'ae-pro' ),
                    'label_off' => __( 'Hide', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-featured-bg-'
                ]
            );

            $element->add_control(
                'ae_featured_image_size',
                [
                    'label'         => __('Image Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $helper->ae_get_intermediate_image_sizes(),
                    'default'       => 'large',
                    'prefix_class'  => 'ae-featured-img-size-',
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_size',
                [
                    'label'         => __('Background Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => array(
                        'auto'   => __('Auto','ae-pro'),
                        'cover'   => __('Cover','ae-pro'),
                        'contain'   => __('Contain','ae-pro')
                    ),
                    'default'       => 'cover',
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_position',
                [
                    'label'         => __('Background Position','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'Background Control', 'ae-pro' ),
                        'top left' => __( 'Top Left', 'Background Control', 'ae-pro' ),
                        'top center' => __( 'Top Center', 'Background Control', 'ae-pro' ),
                        'top right' => __( 'Top Right', 'Background Control', 'ae-pro' ),
                        'center left' => __( 'Center Left', 'Background Control', 'ae-pro' ),
                        'center center' => __( 'Center Center', 'Background Control', 'ae-pro' ),
                        'center right' => __( 'Center Right', 'Background Control', 'ae-pro' ),
                        'bottom left' => __( 'Bottom Left', 'Background Control', 'ae-pro' ),
                        'bottom center' => __( 'Bottom Center', 'Background Control', 'ae-pro' ),
                        'bottom right' => __( 'Bottom Right', 'Background Control', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-position: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_attachment',
                [
                    'label'         => __('Background Attachment','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'Background Control', 'ae-pro' ),
                        'scroll' => __( 'Scroll', 'Background Control', 'ae-pro' ),
                        'fixed' => __( 'Fixed', 'Background Control', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-attachment: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_repeat',
                [
                    'label'         => __('Background Repeat','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'Background Control', 'ae-pro' ),
                        'no-repeat' => __( 'No-repeat', 'Background Control', 'ae-pro' ),
                        'repeat' => __( 'Repeat', 'Background Control', 'ae-pro' ),
                        'repeat-x' => __( 'Repeat-x', 'Background Control', 'ae-pro' ),
                        'repeat-y' => __( 'Repeat-y', 'Background Control', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-repeat: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->end_controls_section();

            /**
            $element->start_controls_section(
                'ae_box_click',
                [
                    'label' => __('Box Click','ae-pro'),
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO
                ]
            );

            $element->add_control(
                '(',
                [
                    'label' => __('Link', 'ae-pro'),
                    'type'  => Controls_Manager::URL
                ]
            );

            $element->end_controls_section();
            **/
        }
    }

    function before_section_render($element){


        /**
        if(!empty($element->get_settings('box_link'))){
            $link = $element->get_settings('box_link');

            $element->add_render_attribute( '_wrapper', [
                'data-box-link' => $link['url'],
            ] );
            $element->add_render_attribute( '_wrapper', [
                'class' => 'ae-has-link',
            ] );
        }
        **/

        if ( ! $element->get_settings( 'show_featured_bg' ) ) {
            return;
        }

        if($element->get_settings( 'show_featured_bg' ) == 'yes'){

            $img_size = $element->get_settings( 'ae_featured_image_size' );
            $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),$img_size);

            $element->add_render_attribute( '_wrapper', [
                'data-ae-bg' => $img[0],
            ] );
        }
    }


}

FeaturedBG::instance();