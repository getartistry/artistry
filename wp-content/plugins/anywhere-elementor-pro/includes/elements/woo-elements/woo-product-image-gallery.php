<?php

namespace Aepro;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;


class Aepro_Woo_Product_Image_Gallery extends Widget_Base{
    public function get_name() {
        return 'ae-woo-gallery';
    }

    public function get_title() {
        return __( 'AE - Woo Gallery', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function is_reload_preview_required() {
        return false;
    }

    public function _register_controls(){

        $this->start_controls_section(
            'gallery_style',
            [
                'label' => __('Gallery Style','ae-pro')
            ]
        );


        $this->add_control(
            'gallery_item_spacing',
            [
                'label' => __('Gallery Item Spacing','ae-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .flex-control-thumbs li' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'gallery_item_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .flex-control-thumbs li img',
            ]
        );

        $this->add_control(
            'gallery_item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .flex-control-thumbs li img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'thumb_box_padding',
            [
                'label'  => __('Thumbnail Wrapper Padding','ae-pro'),
                'type'   => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} ol.flex-control-nav.flex-control-thumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
    }

    public function render(){
        global $product;


        $helper = new Helper();
        $product = $helper->get_ae_woo_product_data();
        if(!$product){
            return '';
        }

        
        $temp_post = $GLOBALS['post'];
        $GLOBALS['post'] = get_post($product->get_id());

        $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
        $post_thumbnail_id = get_post_thumbnail_id( $product->get_id() );
        $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
        $image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
        $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
        $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
            'woocommerce-product-gallery',
            'woocommerce-product-gallery--' . $placeholder,
            'woocommerce-product-gallery--columns-' . absint( $columns ),
            'images',
        ) );

        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
            <figure class="woocommerce-product-gallery__wrapper">
                <?php
                $attributes = array(
                    'title'                   => $image_title,
                    'data-src'                => $full_size_image[0],
                    'data-large_image'        => $full_size_image[0],
                    'data-large_image_width'  => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );

                if ( has_post_thumbnail() ) {
                    $html  = '<div data-thumb="' . get_the_post_thumbnail_url( $product->get_id(), 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                    $html .= get_the_post_thumbnail( $product->get_id(), 'shop_single', $attributes );
                    $html .= '</a></div>';
                } else {
                    $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                    $html .= '</div>';
                }

                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $product->get_id() ) );

                do_action( 'woocommerce_product_thumbnails' );
                ?>
            </figure>
        </div>
    <?php
         $GLOBALS['post'] = $temp_post;
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Woo_Product_Image_Gallery() );
