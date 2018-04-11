<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tiled Posts Widget
 */
class PP_Tiled_Posts_Widget extends Widget_Base {
    
    /**
	 * Retrieve tiled posts widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-tiled-posts';
    }

    /**
	 * Retrieve tiled posts widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Tiled Posts', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the tiled posts widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve tiled posts widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eicon-posts-group power-pack-admin-icon';
    }

    /**
	 * Register tiled posts widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	Content Tab
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_post_settings',
            [
                'label'             => __( 'Settings', 'power-pack' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'             => __( 'Layout', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'layout-1'       => __( 'Layout 1', 'power-pack' ),
                   'layout-2'       => __( 'Layout 2', 'power-pack' ),
                   'layout-3'       => __( 'Layout 3', 'power-pack' ),
                   'layout-4'       => __( 'Layout 4', 'power-pack' ),
                   'layout-5'       => __( 'Layout 5', 'power-pack' ),
                ],
                'default'           => 'layout-1',
            ]
        );

		$this->add_control(
			'content_vertical_position',
			[
				'label'             => __( 'Content Position', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'           => [
					'top'       => [
						'title' => __( 'Top', 'power-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle'    => [
						'title' => __( 'Middle', 'power-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'power-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'           => 'bottom',
			]
		);
        
        $this->add_control(
            'post_title',
            [
                'label'             => __( 'Post Title', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'post_excerpt',
            [
                'label'             => __( 'Post Excerpt', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'excerpt_length',
            [
                'label'             => __( 'Excerpt Length', 'power-pack' ),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 20,
                'min'               => 0,
                'max'               => 58,
                'step'              => 1,
                'condition'         => [
                    'post_excerpt'  => 'yes'
                ]
            ]
        );
        
        $this->add_control(
            'read_more',
            [
                'label'             => __( 'Read More', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'content_vertical_position'  => 'top'
                ]
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label'             => __( 'Read More Text', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __( 'Read More', 'power-pack' ),
                'condition'         => [
                    'read_more'     => 'yes'
                ]
            ]
        );
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'              => 'image_size',
				'label'             => __( 'Image Size', 'power-pack' ),
				'default'           => 'medium_large',
			]
		);

        $this->end_controls_section();

        /**
         * Content Tab: Query
         */
        $this->start_controls_section(
            'section_post_query',
            [
                'label'             => __( 'Query', 'power-pack' ),
            ]
        );

		$this->add_control(
            'post_type',
            [
                'label'             => __( 'Post Type', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => pp_get_post_types(),
                'default'           => 'post',

            ]
        );

        $this->add_control(
            'categories',
            [
                'label'             => __( 'Categories', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_post_categories(),
                'condition'         => [
                    'post_type' => 'post'
                ]
            ]
        );

        $this->add_control(
            'authors',
            [
                'label'             => __( 'Authors', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_auhtors(),
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'             => __( 'Tags', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_tags(),
            ]
        );

        $this->add_control(
            'exclude_posts',
            [
                'label'             => __( 'Exclude Posts', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_posts(),
            ]
        );

        $this->add_control(
            'order',
            [
                'label'             => __( 'Order', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'DESC'           => __( 'Descending', 'power-pack' ),
                   'ASC'       => __( 'Ascending', 'power-pack' ),
                ],
                'default'           => 'DESC',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'             => __( 'Order By', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'date'           => __( 'Date', 'power-pack' ),
                   'modified'       => __( 'Last Modified Date', 'power-pack' ),
                   'rand'           => __( 'Rand', 'power-pack' ),
                   'comment_count'  => __( 'Comment Count', 'power-pack' ),
                   'title'          => __( 'Title', 'power-pack' ),
                   'ID'             => __( 'Post ID', 'power-pack' ),
                   'author'         => __( 'Post Author', 'power-pack' ),
                ],
                'default'           => 'date',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'             => __( 'Offset', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Post Meta
         */
        $this->start_controls_section(
            'section_post_meta',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'post_meta',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->add_control(
            'post_meta_divider',
            [
                'label'             => __( 'Post Meta Divider', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '-',
				'selectors'         => [
					'{{WRAPPER}} .pp-tiled-posts-meta > span:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_author',
            [
                'label'             => __( 'Post Author', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_category',
            [
                'label'             => __( 'Post Category', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_date',
            [
                'label'             => __( 'Post Date', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_post_content_style',
            [
                'label'             => __( 'Content', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'post_content_bg',
                'label'             => __( 'Post Content Background', 'power-pack' ),
                'types'             => [ 'classic', 'gradient' ],
                'selector'          => '{{WRAPPER}} .pp-tiled-post-content',
            ]
        );

		$this->add_control(
			'post_content_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tiled-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Title
         */
        $this->start_controls_section(
            'section_title_style',
            [
                'label'             => __( 'Title', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-title' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'title_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tiled-post-title',
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Post Category
         */
        $this->start_controls_section(
            'section_cat_style',
            [
                'label'             => __( 'Post Category', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'category_style',
            [
                'label'             => __( 'Category Style', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'style-1'       => __( 'Style 1', 'power-pack' ),
                   'style-2'       => __( 'Style 2', 'power-pack' ),
                ],
                'default'           => 'style-1',
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'cat_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories-style-2 span' => 'background: {{VALUE}}',
                ],
                'condition'         => [
                    'post_category'     => 'yes',
                    'category_style'    => 'style-2'
                ]
            ]
        );

        $this->add_control(
            'cat_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'cat_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-post-categories',
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'cat_margin_bottom',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

		$this->add_control(
			'cat_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-post-categories-style-2 span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'         => [
                    'post_category'     => 'yes',
                    'category_style'    => 'style-2'
                ]
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Post Meta
         */
        $this->start_controls_section(
            'section_meta_style',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-posts-meta' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'meta_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tiled-posts-meta',
            ]
        );

        $this->end_controls_section();

    }

    /**
	 * Render tiled posts widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'tiled-posts', 'class', 'pp-tiled-posts clearfix' );
        
        if ( $settings['layout'] ) {
            $this->add_render_attribute( 'tiled-posts', 'class', 'pp-tiled-posts-' . $settings['layout'] );
        }
        
        $this->add_render_attribute( 'post-content', 'class', 'pp-tiled-post-content' );
        
        if ( $settings['content_vertical_position'] ) {
            $this->add_render_attribute( 'post-content', 'class', 'pp-tiled-post-content-' . $settings['content_vertical_position'] );
        }
        
        $this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );
        
        if ( $settings['category_style'] ) {
            $this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories-' . $settings['category_style'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'tiled-posts' ); ?>>
            <?php
                $pp_post_position = 1;
        
                $pp_layout = $settings['layout'];
        
                if ( $pp_layout == 'layout-1' ) {
                    $pp_posts_count = '4';
                }
                elseif ( $pp_layout == 'layout-2' || $pp_layout == 'layout-3' ) {
                    $pp_posts_count = '3';
                }
                elseif ( $pp_layout == 'layout-4' || $pp_layout == 'layout-5' ) {
                    $pp_posts_count = '5';
                }
                else {
                    $pp_posts_count = '3';
                }

                // Post Authors
                $pp_tiled_post_author = '';
                $pp_tiled_post_authors = $settings['authors'];
                if ( !empty( $pp_tiled_post_authors) ) {
                    $pp_tiled_post_author = implode( ",", $pp_tiled_post_authors );
                }

                // Post Categories
                $pp_tiled_post_cat = '';
                $pp_tiled_post_cats = $settings['categories'];
                if ( !empty( $pp_tiled_post_cats) ) {
                    $pp_tiled_post_cat = implode( ",", $pp_tiled_post_cats );
                }
        
                // Query Arguments
                $args = array(
                    'post_status'           => array( 'publish' ),
                    'post_type'             => $settings['post_type'],
                    'post__in'              => '',
                    'cat'                   => $pp_tiled_post_cat,
                    'author'                => $pp_tiled_post_author,
                    'tag__in'               => $settings['tags'],
                    'orderby'               => $settings['orderby'],
                    'order'                 => $settings['order'],
                    'post__not_in'          => $settings['exclude_posts'],
                    'offset'                => $settings['offset'],
                    'ignore_sticky_posts'   => 1,
                    'showposts'             => $pp_posts_count
                );
                $featured_posts = new \WP_Query( $args );


                if ( $featured_posts->have_posts() ) : while ($featured_posts->have_posts()) : $featured_posts->the_post();
                    if ( $pp_layout == 'layout-1' || $pp_layout == 'layout-2' || $pp_layout == 'layout-3' || $pp_layout == 'layout-4' ) {
                        if ( $pp_post_position == 2 ) { ?><div class="pp-tiles-posts-right"><?php }
                    }

                    if ( has_post_thumbnail() ) {
                        $image_id = get_post_thumbnail_id( get_the_ID() );
                        $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
                    } else {
                        $pp_thumb_url = '';
                    }
                    ?>
                    <div class="pp-tiled-post pp-tiled-post-<?php echo intval( $pp_post_position ); ?>">
                            <div class="pp-tiled-post-bg" <?php if ( $pp_thumb_url ) { echo "style='background-image:url(".esc_url( $pp_thumb_url ).")'"; } ?>>
                                
                        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                        </a>
                            </div>
                        <div <?php echo $this->get_render_attribute_string( 'post-content' ); ?>>
                            <?php if ( $settings['post_meta'] == 'yes' ) { ?>
                                <?php if ( $settings['post_category'] == 'yes' ) { ?>
                                    <div <?php echo $this->get_render_attribute_string( 'post-categories' ); ?>>
                                        <span>
                                            <?php
                                                $category = get_the_category();
                                                if ( $category ) {
                                                    echo esc_attr( $category[0]->name );
                                                }
                                            ?>
                                        </span>
                                    </div><!--.pp-post-categories-->
                                <?php } ?>
                            <?php } ?>
                            <?php if ( $settings['post_title'] == 'yes' ) { ?>
                                <header>
                                    <h2 class="pp-tiled-post-title">
                                        <?php the_title(); ?>
                                    </h2>
                                </header>
                            <?php } ?>
                            <?php if ( $settings['post_meta'] == 'yes' ) { ?>
                                <div class="pp-tiled-posts-meta">
                                    <?php if ( $settings['post_author'] == 'yes' ) { ?>
                                        <span class="pp-post-author">
                                            <?php echo get_the_author(); ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ( $settings['post_date'] == 'yes' ) { ?>
                                            <?php
                                                $pp_time_string = sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>',
                                                    esc_attr( get_the_date( 'c' ) ),
                                                    get_the_date()
                                                );

                                                printf( '<span class="pp-post-date"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                                                    __( 'Posted on', 'power-pack' ),
                                                    $pp_time_string
                                                );
                                            ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div><!--.post-inner-->
                        <?php if ( $pp_layout == 'style-2') { ?>
                            <span class="read-story heading">
                                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> <?php _e( 'Read Story', 'power-pack' ); ?>
                            </span>
                        <?php } ?>
                    </div>
                    <?php
                    if ( $pp_layout == 'layout-1' ) {
                        if ( $pp_post_position == 4 ) { ?></div><?php }
                    }
                    elseif ( $pp_layout == 'layout-2' || $pp_layout == 'layout-3' ) {
                        if ( $pp_post_position == 3 ) { ?></div><?php }
                    }
                    if ( $pp_layout == 'layout-4' ) {
                        if ( $pp_post_position == 5 ) { ?></div><?php }
                    }
                $pp_post_position++; endwhile; endif; wp_reset_query();
        ?>
        </div><!--.slider-->
        <?php
    }

    /**
	 * Render tiled posts widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {}
}

Plugin::instance()->widgets_manager->register_widget_type( new PP_Tiled_Posts_Widget() );