<?php

/*
Widget Name: Livemesh Gallery Carousel
Description: Display images or videos in a responsive carousel.
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


class LAE_Gallery_Carousel_Widget extends Widget_Base {

    static public $gallery_counter = 0;

    public function get_name() {
        return 'lae-gallery-carousel';
    }

    public function get_title() {
        return __('Livemesh Gallery Carousel', 'livemesh-el-addons');
    }

    public function get_icon() {
        return 'eicon-slider-3d';
    }

    public function get_categories() {
        return array('livemesh-addons');
    }

    public function get_script_depends() {
        return [
            'lae-widgets-scripts',
            'lae-frontend-scripts',
            'jquery-fancybox',
            'slick',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_gallery_carousel',
            [
                'label' => __('Gallery Items', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'gallery_items',
            [
                'type' => Controls_Manager::REPEATER,
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
                        'name' => 'item_label',
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
                        'description' => __('One or more comma separated tags for the gallery item.', 'livemesh-el-addons'),
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
                        'name' => 'item_description',
                        'label' => __('Item description', 'livemesh-el-addons'),
                        'type' => Controls_Manager::TEXTAREA,
                        'description' => __('Short description for the gallery item displayed in the lightbox gallery.(optional)', 'livemesh-el-addons'),
                        'label_block' => true,
                    ],

                ],
                'title_field' => '{{{ item_label }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general',
            [
                'label' => __('General', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'gallery_class', [
                'type' => Controls_Manager::TEXT,
                'description' => __('Specify an unique identifier used as a custom CSS class name and lightbox group name/slug for the gallery carousel element.', 'livemesh-el-addons'),
                'label' => __('Gallery Class/Identifier', 'livemesh-el-addons'),
                'default' => ''
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
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'label' => __('Gallery Image Size', 'livemesh-el-addons'),
                'default' => 'large',
                'separator' => 'before',
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
            'section_carousel_options',
            [
                'label' => __('Carousel Options', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'arrows',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'separator' => 'before',
                'default' => 'yes',
                'label' => __('Prev/Next Arrows?', 'livemesh-el-addons'),
            ]
        );


        $this->add_control(
            'dots',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'label' => __('Show dot indicators for navigation?', 'livemesh-el-addons'),
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
                'label' => __('Pause on Hover?', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __('No', 'livemesh-el-addons'),
                'label_on' => __('Yes', 'livemesh-el-addons'),
                'separator' => 'before',
                'return_value' => 'yes',
                'default' => 'no',
                'label' => __('Autoplay?', 'livemesh-el-addons'),
                'description' => __('Should the carousel autoplay as in a slideshow.', 'livemesh-el-addons'),
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay speed in ms', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
            ]
        );


        $this->add_control(
            'animation_speed',
            [
                'label' => __('Autoplay animation speed in ms', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_responsive',
            [
                'label' => __('Responsive Options', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => __('Desktop', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'display_columns',
            [
                'label' => __('Columns per row', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 3,
            ]
        );


        $this->add_control(
            'scroll_columns',
            [
                'label' => __('Columns to scroll', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 3,
            ]
        );


        $this->add_control(
            'gutter',
            [
                'label' => __('Gutter', 'livemesh-el-addons'),
                'description' => __('Space between columns.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item' => 'padding: {{VALUE}}px;',
                ],
            ]
        );


        $this->add_control(
            'heading_tablet',
            [
                'label' => __('Tablet', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'tablet_display_columns',
            [
                'label' => __('Columns per row', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 2,
            ]
        );

        $this->add_control(
            'tablet_scroll_columns',
            [
                'label' => __('Columns to scroll', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'default' => 2,
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
                    '(tablet-){{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item' => 'padding: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'tablet_width',
            [
                'label' => __('Tablet Resolution', 'livemesh-el-addons'),
                'description' => __('The resolution to treat as a tablet resolution.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 800,
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label' => __('Mobile Phone', 'livemesh-el-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'mobile_display_columns',
            [
                'label' => __('Columns per row', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'mobile_scroll_columns',
            [
                'label' => __('Columns to scroll', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 1,
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
                    '(mobile-){{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item' => 'padding: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'mobile_width',
            [
                'label' => __('Tablet Resolution', 'livemesh-el-addons'),
                'description' => __('The resolution to treat as a tablet resolution.', 'livemesh-el-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 480,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_thumbnail_styling',
            [
                'label' => __('Gallery Thumbnail', 'livemesh-el-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'thumbnail_hover_bg_color',
            [
                'label' => __('Thumbnail Hover Background Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_hover_opacity',
            [
                'label' => __('Thumbnail Hover Opacity (%)', 'livemesh-el-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image:hover .lae-image-overlay' => 'opacity: {{SIZE}};',
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
                'label' => __('Title Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-info .lae-entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_border_color',
            [
                'label' => __('Title Hover Border Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-info .lae-entry-title a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-info .lae-entry-title',
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
            'tags_color',
            [
                'label' => __('Item Tags Color', 'livemesh-el-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-info .lae-terms' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tags_typography',
                'selector' => '{{WRAPPER}} .lae-gallery-carousel .lae-gallery-carousel-item .lae-project-image .lae-image-info .lae-terms',
            ]
        );

    }

    protected function render() {

        $settings = $this->get_settings();

        self::$gallery_counter++;

        $settings['gallery_class'] = !empty($settings['gallery_class']) ? sanitize_title($settings['gallery_class']) : 'gallery-carousel-' . self::$gallery_counter;

        $settings['gallery_id'] = $this->get_id();

        $items = $settings['gallery_items'];

        $carousel_settings = [
            'enable_lightbox' => ('yes' === $settings['enable_lightbox']),
            'lightbox_library' => $settings['lightbox_library'],
            'arrows' => ('yes' === $settings['arrows']),
            'dots' => ('yes' === $settings['dots']),
            'autoplay' => ('yes' === $settings['autoplay']),
            'autoplay_speed' => absint($settings['autoplay_speed']),
            'animation_speed' => absint($settings['animation_speed']),
            'pause_on_hover' => ('yes' === $settings['pause_on_hover']),
        ];

        $responsive_settings = [
            'display_columns' => $settings['display_columns'],
            'scroll_columns' => $settings['scroll_columns'],
            'gutter' => $settings['gutter'],
            'tablet_width' => $settings['tablet_width'],
            'tablet_display_columns' => $settings['tablet_display_columns'],
            'tablet_scroll_columns' => $settings['tablet_scroll_columns'],
            'tablet_gutter' => $settings['tablet_gutter'],
            'mobile_width' => $settings['mobile_width'],
            'mobile_display_columns' => $settings['mobile_display_columns'],
            'mobile_scroll_columns' => $settings['mobile_scroll_columns'],
            'mobile_gutter' => $settings['mobile_gutter'],

        ];

        $carousel_settings = array_merge($carousel_settings, $responsive_settings);

        if (!empty($items)) : ?>

            <div id="lae-gallery-carousel-<?php echo uniqid(); ?>"
                 class="lae-gallery-carousel lae-container <?php echo $settings['gallery_class']; ?>"
                 data-settings='<?php echo wp_json_encode($carousel_settings); ?>'>

                <?php foreach ($items as $item): ?>

                    <?php

                    // No need to populate anything if no image is provided for video or for the image
                    if (empty($item['item_image']))
                        continue;

                    $style = '';
                    if (!empty($item['item_tags'])) {
                        $terms = explode(',', $item['item_tags']);

                        foreach ($terms as $term) {
                            $style .= ' term-' . $term;
                        }
                    }
                    ?>

                    <?php

                    $item_type = $item['item_type'];
                    $item_class = 'lae-' . $item_type . '-type';

                    ?>

                    <div class="lae-gallery-carousel-item <?php echo $style; ?> <?php echo $item_class; ?>">

                        <div class="lae-project-image">

                            <?php $image_html = lae_get_image_html($item['item_image'], 'thumbnail_size', $settings); ?>

                            <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                                <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                                   title="<?php echo esc_html($item['item_label']); ?>"><?php echo $image_html; ?> </a>

                            <?php else: ?>

                                <?php echo $image_html; ?>

                            <?php endif; ?>

                            <div class="lae-image-info">

                                <div class="lae-entry-info">

                                    <?php if ($settings['display_item_title'] == 'yes'): ?>

                                        <<?php echo $settings['title_tag']; ?> class="lae-entry-title">

                                        <?php if ($item_type == 'image' && !empty($item['item_link']['url'])): ?>

                                            <?php $target = $item['item_link']['is_external'] ? 'target="_blank"' : ''; ?>

                                            <a href="<?php echo esc_url($item['item_link']['url']); ?>"
                                               title="<?php echo esc_html($item['item_label']); ?>"
                                                <?php echo $target; ?>><?php echo esc_html($item['item_label']); ?></a>

                                        <?php else: ?>

                                            <?php echo esc_html($item['item_label']); ?>

                                        <?php endif; ?>

                                        </<?php echo $settings['title_tag']; ?>>

                                    <?php endif; ?>

                                    <?php if ($item_type == 'youtube' || $item_type == 'vimeo') : ?>

                                        <?php
                                        $video_url = $item['video_link'];
                                        ?>
                                        <?php if (!empty($video_url)) : ?>

                                            <a class="lae-video-lightbox"
                                               data-fancybox="<?php echo $settings['gallery_class']; ?>"
                                               href="<?php echo $video_url; ?>"
                                               title="<?php echo esc_html($item['item_label']); ?>"
                                               data-description="<?php echo wp_kses_post($item['item_description']); ?>"><i
                                                        class="lae-icon-video-play"></i></a>

                                        <?php endif; ?>

                                    <?php elseif ($item_type == 'html5video' && !empty($item['mp4_video_link'])) : ?>

                                        <?php $video_id = 'lae-video-' . $item['item_image']['id']; // will use thumbnail id as id for video for now ?>

                                        <a class="lae-video-lightbox"
                                           data-fancybox="<?php echo $settings['gallery_class']; ?>"
                                           href="#<?php echo $video_id; ?>"
                                           title="<?php echo esc_html($item['item_label']); ?>"
                                           data-description="<?php echo wp_kses_post($item['item_description']); ?>"><i
                                                    class="lae-icon-video-play"></i></a>

                                        <div id="<?php echo $video_id; ?>" class="lae-fancybox-video">

                                            <video poster="<?php echo $item['item_image']['url']; ?>"
                                                   src="<?php echo $item['mp4_video_link']; ?>"
                                                   autoplay="1"
                                                   preload="metadata"
                                                   controls
                                                   controlsList="nodownload">
                                                <source type="video/mp4"
                                                        src="<?php echo $item['mp4_video_link']; ?>">
                                                <source type="video/webm"
                                                        src="<?php echo $item['webm_video_link']; ?>">
                                            </video>

                                        </div>

                                    <?php endif; ?>

                                    <?php if ($settings['display_item_tags'] == 'yes'): ?>

                                        <span class="lae-terms"><?php echo esc_html($item['item_tags']); ?></span>

                                    <?php endif; ?>

                                </div>

                                <?php if ($item_type == 'image' && $carousel_settings['enable_lightbox']) : ?>

                                    <?php $anchor_type = (empty($item['item_link']['url']) ? 'lae-click-anywhere' : 'lae-click-icon'); ?>

                                    <?php if ($carousel_settings['lightbox_library'] == 'elementor'): ?>

                                        <a class="lae-lightbox-item <?php echo $anchor_type; ?> elementor-clickable"
                                           href="<?php echo $item['item_image']['url']; ?>"
                                           data-elementor-open-lightbox="yes"
                                           data-elementor-lightbox-slideshow="<?php echo esc_attr($this->get_id()); ?>"
                                           title="<?php echo esc_html($item['item_label']); ?>"><i
                                                    class="lae-icon-full-screen"></i></a>

                                    <?php else: ?>

                                        <a class="lae-lightbox-item <?php echo $anchor_type; ?>"
                                           data-fancybox="<?php echo $settings['gallery_class']; ?>"
                                           href="<?php echo $item['item_image']['url']; ?>"
                                           data-elementor-open-lightbox="no"
                                           title="<?php echo esc_html($item['item_label']); ?>"
                                           data-description="<?php echo wp_kses_post($item['item_description']); ?>"><i
                                                    class="lae-icon-full-screen"></i></a>

                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div> <!-- .lae-gallery-carousel -->

        <?php endif; ?>

        <?php
    }

    protected function content_template() {
    }

}