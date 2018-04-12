<?php

/*
Widget Name: Livemesh Gallery
Description: Display images or videos in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/


namespace LivemeshAddons\Widgets;

use LivemeshAddons\Gallery\LAE_Gallery_Common;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


class LAE_Gallery_Widget extends Widget_Base {

    static public $gallery_counter = 0;

    public function get_name() {
        return 'lae-gallery';
    }

    public function get_title() {
        return __('Livemesh Gallery', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
            'jquery-fancybox',
            'isotope.pkgd',
            'imagesloaded.pkgd'
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_gallery',
            [
                'label' => __('Gallery', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'gallery_class', [
                'type' => Controls_Manager::TEXT,
                "description" => __("Specify an unique identifier used as a custom CSS class name and lightbox group name/slug for the gallery element.", "livemesh-el-addons"),
                "label" => __("Gallery Class/Identifier", "livemesh-el-addons"),
                'default' => ''
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading for the grid', 'livemesh-el-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('My Gallery', 'livemesh-el-addons'),
                'default' => __('My Gallery', 'livemesh-el-addons'),
            ]
        );


        $this->add_control(
            'gallery_items_heading',
            [
                'label' => __('Gallery Items', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );


        $this->add_control(
            'bulk_upload',
            [
                'label' => __('Bulk upload images?', 'livemesh-el-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'gallery_images',
            [
                'label' => __( 'Add Images', 'livemesh-el-addons' ),
                'type' => Controls_Manager::GALLERY,
                'condition' => [
                    'bulk_upload' => ['yes']
                ],
            ]
        );

        $this->add_control(
            'gallery_items',
            [
                'type' => Controls_Manager::REPEATER,
                'condition' => [
                    'bulk_upload' => ['']
                ],
                'fields' => [

                    [
                        "type" => Controls_Manager::SELECT,
                        "name" => "item_type",
                        "label" => __("Item Type", "livemesh-el-addons"),
                        'options' => array(
                            'image' => __('Image', 'livemesh-el-addons'),
                            'youtube' => __('YouTube Video', 'livemesh-el-addons'),
                            'vimeo' => __('Vimeo Video', 'livemesh-el-addons'),
                            'html5video' => __('HTML5 Video', 'livemesh-el-addons'),
                        ),
                        'default' => 'image',
                    ],

                    [
                        'name' => 'item_name',
                        'label' => __('Item Label', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'description' => __('The label or name for the gallery item.', 'livemesh-el-addons'),
                    ],

                    [
                        'name' => 'item_image',
                        'label' => __('Gallery Image', 'livemesh-el-addons'),
                        'description' => __('The image for the gallery item. If item type chosen is YouTube or Vimeo video, the image will be used as a placeholder image for video.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'label_block' => true,
                    ],

                    [
                        'name' => 'item_tags',
                        'label' => __('Item Tag(s)', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'description' => __('One or more comma separated tags for the gallery item. Will be used as filters for the items.', 'livemesh-el-addons'),
                    ],

                    [
                        'name' => 'item_link',
                        'label' => __('Item Link', 'livemesh-el-addons'),
                        'description' => __('The URL of the page to which the image gallery item points to (optional).', 'livemesh-el-addons'),
                        'type' => Controls_Manager::URL,
                        'label_block' => true,
                        'default' => [
                            'url' => '',
                            'is_external' => 'false',
                        ],
                        'placeholder' => __('http://your-link.com', 'livemesh-el-addons'),
                        'condition' => [
                            'item_type' => ['image'],
                        ],
                    ],

                    [
                        'name' => 'video_link',
                        'label' => __('Video URL', 'livemesh-el-addons'),
                        'description' => __('The URL of the YouTube or Vimeo video.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'condition' => [
                            'item_type' => ['youtube', 'vimeo'],
                        ],
                    ],

                    [
                        'name' => 'mp4_video_link',
                        'label' => __('MP4 Video URL', 'livemesh-el-addons'),
                        'description' => __('The URL of the MP4 video.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'condition' => [
                            'item_type' => ['html5video'],
                        ],
                        'default' 		=> '',
                    ],

                    [
                        'name' => 'webm_video_link',
                        'label' => __('WebM Video URL', 'livemesh-el-addons'),
                        'description' => __('The URL of the WebM video.', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXT,
                        'condition' => [
                            'item_type' => ['html5video'],
                        ],
                        'default' 		=> '',
                    ],

                    [
                        'name' => 'display_video_inline',
                        'type' => Controls_Manager::SWITCHER,
                        'label' => __('Display video inline?', 'livemesh-el-addons'),
                        'label_off' => __('No', 'livemesh-el-addons'),
                        'label_on' => __('Yes', 'livemesh-el-addons'),
                        'condition' => [
                            'item_type' => ['youtube', 'vimeo', 'html5video'],
                        ],
                        'return_value' => 'yes',
                        'default' => 'no',
                    ],

                    [
                        'name' => 'item_description',
                        'label' => __('Item description', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXTAREA,
                        'description' => __('Short description for the gallery item displayed in the lightbox gallery.(optional)', 'livemesh-el-addons')
                    ],

                ],
                'title_field' => '{{{ item_name }}}',
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
            'filterable',
            [
                'label' => __('Filterable?', 'livemesh-el-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'bulk_upload' => ['']
                ],
            ]
        );


        $this->add_control(
            'per_line',
            [
                'label' => __('Columns per row', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 4,
            ]
        );

        $this->add_control(
            'layout_mode',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Choose a layout for the grid', 'livemesh-el-addons'),
                'options' => array(
                    'fitRows' => __('Fit Rows', 'livemesh-el-addons'),
                    'masonry' => __('Masonry', 'livemesh-el-addons'),
                ),
                'default' => 'fitRows',
            ]
        );

        $this->add_control(
            'display_item_title',
            [
                'label' => __('Display image/video title?', 'livemesh-el-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'display_item_tags',
            [
                'label' => __('Display image/video tags?', 'livemesh-el-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'bulk_upload' => ['']
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_size_lightbox',
            [
                'label' => __('Image Size and Lightbox', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'label' => __( 'Gallery Image Size', 'livemesh-el-addons' ),
                'default' => 'large',
            ]
        );

        $this->add_control(
            'enable_lightbox',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'label' => __('Enable Lightbox Gallery?', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'lightbox_library',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Lightbox Library', 'livemesh-el-addons'),
                'description' => __('Choose the preferred library for the lightbox', 'livemesh-el-addons'),
                'options' => array(
                    'fancybox' => __('Fancybox', 'livemesh-el-addons'),
                    'elementor' => __('Elementor', 'livemesh-el-addons'),
                ),
                'default' => 'fancybox',
                'condition' => [
                    'enable_lightbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __('Pagination', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );


        $this->add_control(
            'pagination',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __('Pagination', 'livemesh-el-addons'),
                'description' => __('Choose pagination type or choose None if no pagination is desired. Make sure you enter the items per page value in the option \'Number of items to be displayed per page and on each load more invocation\' field below to control number of items to display per page.', 'livemesh-el-addons'),
                'options' => array(
                    'none' => __('None', 'livemesh-el-addons'),
                    'paged' => __('Paged', 'livemesh-el-addons'),
                    'load_more' => __('Load More', 'livemesh-el-addons'),
                ),
                'default' => 'none',
            ]
        );


        $this->add_control(
            'show_remaining',
            [
                'label' => __('Display count of items yet to be loaded with the load more button?', 'livemesh-el-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'label_off' => __('No', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'pagination' => 'load_more',
                ],
            ]
        );


        $this->add_control(
            'items_per_page',
            [
                'label' => __('Number of items to be displayed per page and on each load more invocation.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
                'condition' => [
                    'pagination' => ['load_more', 'paged'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_heading_styling',
            [
                'label' => __('Gallery Heading', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'heading_tag',
            [
                'label' => __( 'Heading HTML Tag', 'livemesh-el-addons' ),
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
            'heading_color',
            [
                'label' => __( 'Heading Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-heading',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filters_styling',
            [
                'label' => __('Gallery Filters', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'filter_color',
            [
                'label' => __( 'Filter Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-taxonomy-filter .lae-filter-item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_hover_color',
            [
                'label' => __( 'Filter Hover Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-taxonomy-filter .lae-filter-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_active_border',
            [
                'label' => __( 'Active Filter Border Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-taxonomy-filter .lae-filter-item.lae-active::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-taxonomy-filter .lae-filter-item a',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_thumbnail_styling',
            [
                'label' => __('Gallery Thumbnail', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'thumbnail_hover_brightness',
            [
                'label' => __( 'Thumbnail Hover Brightness (%)', 'livemesh-el-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image:hover img' => '-webkit-filter: brightness({{SIZE}}%);filter: brightness({{SIZE}}%)',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_title_styling',
            [
                'label' => __('Gallery Item Title', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_title_tag',
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
            'item_title_color',
            [
                'label' => __( 'Title Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-entry-title , {{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_border_color',
            [
                'label' => __( 'Title Hover Border Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-entry-title a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-entry-title',
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_tags',
            [
                'label' => __('Gallery Item Tags', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_tags_color',
            [
                'label' => __( 'Item Tags Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-terms' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_tags_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-gallery .lae-gallery-item .lae-project-image .lae-image-info .lae-terms',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_styling',
            [
                'label' => __('Pagination', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'pagination_border_color',
            [
                'label' => __( 'Border Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav, {{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav:first-child' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_bg_color',
            [
                'label' => __( 'Hover Background Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav:hover, {{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav.lae-current-page' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_nav_icon_color',
            [
                'label' => __( 'Nav Icon Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_disabled_nav_icon_color',
            [
                'label' => __( 'Disabled Nav Icon Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav.lae-disabled i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_nav_text',
            [
                'label' => __( 'Navigation text', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination_text_color',
            [
                'label' => __( 'Nav Text Color', 'livemesh-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Nav Text Typography', 'livemesh-el-addons' ),
                'name' => 'pagination_text_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-wrap .lae-pagination .lae-page-nav',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_responsive',
            [
                'label' => __('Gutter Options', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => __( 'Desktop', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );


        $this->add_control(
            'gutter',
            [
                'label' => __('Gutter', 'livemesh-el-addons'),
                'description' => __('Space between columns in the grid.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery' => 'margin-left: -{{VALUE}}px; margin-right: -{{VALUE}}px;',
                    '{{WRAPPER}} .lae-gallery .lae-gallery-item' => 'padding: {{VALUE}}px;',
                ]
            ]
        );

        $this->add_control(
            'heading_tablet',
            [
                'label' => __( 'Tablet', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );


        $this->add_control(
            'tablet_gutter',
            [
                'label' => __('Gutter', 'livemesh-el-addons'),
                'description' => __('Space between columns.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'selectors' => [
                    '(tablet-){{WRAPPER}} .lae-gallery' => 'margin-left: -{{VALUE}}px; margin-right: -{{VALUE}}px;',
                    '(tablet-){{WRAPPER}} .lae-gallery .lae-gallery-item' => 'padding: {{VALUE}}px;',
                ]
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label' => __( 'Mobile Phone', 'livemesh-el-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'mobile_gutter',
            [
                'label' => __('Gutter', 'livemesh-el-addons'),
                'description' => __('Space between columns.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'selectors' => [
                    '(mobile-){{WRAPPER}} .lae-gallery' => 'margin-left: -{{VALUE}}px; margin-right: -{{VALUE}}px;',
                    '(mobile-){{WRAPPER}} .lae-gallery .lae-gallery-item' => 'padding: {{VALUE}}px;',
                ]
            ]
        );

        $this->end_controls_section();

    }

    protected function get_settings_data_atts($settings) {

        $data_atts = array();

        /* Block Content */

        $data_atts['gallery_class'] = $settings['gallery_class'];

        $data_atts['gallery_id'] = $this->get_id();

        $data_atts['heading'] = $settings['heading'];

        $data_atts['bulk_upload'] = $settings['bulk_upload'];

        $data_atts['filterable'] = $settings['filterable'];

        $data_atts['per_line'] = $settings['per_line'];

        $data_atts['layout_mode'] = $settings['layout_mode'];

        $data_atts['display_item_title'] = $settings['display_item_title'];

        $data_atts['display_item_tags'] = $settings['display_item_tags'];

        $data_atts['thumbnail_size_size'] = $settings['thumbnail_size_size'];

        $data_atts['thumbnail_size_custom_dimension'] = $settings['thumbnail_size_custom_dimension'];

        $data_atts['enable_lightbox'] = $settings['enable_lightbox'];

        $data_atts['lightbox_library'] = $settings['lightbox_library'];

        $data_atts['pagination'] = $settings['pagination'];

        $data_atts['show_remaining'] = $settings['show_remaining'];

        $data_atts['items_per_page'] = $settings['items_per_page'];

        /* Gallery Customization */

        $data_atts['heading_tag'] = $settings['heading_tag'];

        $data_atts['item_title_tag'] = $settings['item_title_tag'];

        return $data_atts;

    }

    protected function render() {

        $settings = $this->get_settings();

        $common = LAE_Gallery_Common::get_instance();

        self::$gallery_counter++;

        $settings['gallery_class'] = !empty($settings['gallery_class']) ? sanitize_title($settings['gallery_class']) : 'gallery-' . self::$gallery_counter;

        $settings['gallery_id'] = $this->get_id();

        if ($settings['bulk_upload'] == 'yes') {

            $items = array();

            $images = $this->get_settings('gallery_images');

            foreach ($images as $image) {

                $item_image = array('id' => $image['id'], 'url' => $image['url']);

                $attachment = get_post($image['id']);

                $image_title = $attachment->post_title;

                $image_description = $attachment->post_excerpt;

                $item = array('item_type' => 'image', 'item_image' => $item_image, 'item_name' => $image_title, 'item_tags' => '', 'item_link' => '','item_description' => $image_description);

                $items[] = $item;
            }

            unset($settings['gallery_images']); // exclude items from settings
        }
        else {

            $items = $settings['gallery_items'];

            unset($settings['gallery_items']); // exclude items from settings

        }


        if (!empty($items)) :

            $terms = $common->get_gallery_terms($items);
            $max_num_pages = ceil(count($items) / $settings['items_per_page']);

            ?>

            <div class="lae-gallery-wrap lae-gapless-grid"
                 data-settings='<?php echo wp_json_encode($this->get_settings_data_atts($settings)); ?>'
                 data-items='<?php echo ($settings['pagination'] !== 'none') ? json_encode($items, JSON_HEX_APOS) : ''; ?>'
                 data-maxpages='<?php echo $max_num_pages; ?>'
                 data-total='<?php echo count($items); ?>'
                 data-current='1'>

                <?php if (!empty($settings['heading']) || $settings['filterable'] == 'yes'): ?>

                    <?php $header_class = (trim($settings['heading']) === '') ? ' lae-no-heading' : ''; ?>

                    <div class="lae-gallery-header <?php echo $header_class; ?>">

                        <?php if (!empty($settings['heading'])) : ?>

                            <<?php echo $settings['heading_tag']; ?> class="lae-heading"><?php echo wp_kses_post($settings['heading']); ?></<?php echo $settings['heading_tag']; ?>>

                        <?php endif; ?>

                        <?php

                        if ($settings['bulk_upload'] !== 'yes' && $settings['filterable'] == 'yes')
                            echo $common->get_gallery_terms_filter($terms);

                        ?>

                    </div>

                <?php endif; ?>

                <div id="<?php echo uniqid('lae-gallery'); ?>"
                     class="lae-gallery js-isotope lae-<?php echo esc_attr($settings['layout_mode']); ?> lae-grid-container <?php echo $settings['gallery_class']; ?>"
                     data-isotope-options='{ "itemSelector": ".lae-gallery-item", "layoutMode": "<?php echo esc_attr($settings['layout_mode']); ?>", "masonry": { "columnWidth": ".lae-grid-sizer" } }'>

                    <?php if ($settings['layout_mode'] == 'masonry'): ?>

                        <div class="lae-grid-sizer"></div>

                    <?php endif; ?>

                    <?php $common->display_gallery($items, $settings, 1); ?>

                </div><!-- Isotope items -->

                <?php echo $common->paginate_gallery($items, $settings); ?>

            </div><!-- .lae-gallery-wrap -->

            <?php

        endif;
    }

    protected function content_template() {
    }

}