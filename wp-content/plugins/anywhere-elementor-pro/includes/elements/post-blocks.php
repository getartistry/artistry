<?php

namespace Aepro;


use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Plugin;
use ElementorPro\Modules\Woocommerce\Widgets\Elements;
use WP_Query;


class Aepro_Post_Blocks extends Widget_Base{
    public function get_name() {
        return 'ae-post-blocks';
    }

    public function get_title() {
        return __( 'AE - Post Blocks', 'ae-pro' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'ae-template-elements' ];
    }

    public function get_script_depends() {

        return [ 'jquery-masonry' ];

    }

    protected function _register_controls() {
		$helper = new Helper();

		$ae_post_types = $helper->get_rule_post_types();
		$ae_post_types_options = $ae_post_types;
        $ae_post_types_options['current_loop'] = __( 'Current Archive','ae-pro' );
		$ae_post_types_options['ae_by_id'] = __( 'Manual Selection','ae-pro' );
		$ae_post_types_options['related'] = __('Related Posts', 'ae-pro');

		if(class_exists('acf') || is_plugin_active('pods/init.php')){
			$ae_post_types_options['relation'] = __('Relationship', 'ae-pro');
        }

        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'ae-pro' ),
            ]
        );
		$this->add_control(
            'ae_post_type',
            [
                'label'         => __('Source','ae-pro'),
                'type'          => Controls_Manager::SELECT,
                'options'       => $ae_post_types_options,
                'default' => key( $ae_post_types ),
            ]
        );

        $this->add_control(
            'template',
            [
                'label'     =>  __('Template','ae-pro'),
                'type'      =>  Controls_Manager::SELECT,
                'options'   =>  $helper->ae_block_layouts(),
                'description' => __('Know more about templates <a href="http://aedocs.webtechstreet.com/article/9-creating-block-layout-in-anywhere-elementor-pro" target="_blank">Click Here</a>','ae-pro')
            ]
        );
        if(class_exists('acf') &&  is_plugin_active('pods/init.php')) {
            $this->add_control(
                'relationship_type',
                [
                    'label' => __('Relationship Type', 'ae-pro'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'acf',
                    'options' => [
                        'acf' => __('ACF', 'ae-pro'),
                        'pods' => __('Pods', 'ae-pro')
                    ],
                    'condition' => [
                        'ae_post_type'  => 'relation'
                    ]
                ]
            );
        }

		$this->add_control(
            'ae_post_ids',
            [
                'label'         => __('Posts','ae-pro'),
                'type'          => Controls_Manager::SELECT2,
                'multiple'    => true,
                'label_block' => true,
                'placeholder' => __( 'Selects Posts', 'ae-pro' ),
                'default' => __( '', 'ae-pro' ),
				'condition' => [
					'ae_post_type' => 'ae_by_id',
				],
            ]
        );

		$this->add_control(
		        'related_by',
                [
                    'label' => __('Related By', 'ae-pro'),
                    'type'  => Controls_Manager::SELECT2,
                    'multiple'  => true,
                    'label_block'   => true,
                    'placeholder' => __('Select Taxonomies', 'ae-pro'),
                    'default'   => '',
                    'options'   => $helper->get_rules_taxonomies(),
                    'condition' => [
                            'ae_post_type'  => 'related'
                    ]
                ]
        );
        $this->add_control(
            'related_match_with',
            [
                'label'   => __( 'Match With', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'OR',
                'options' => [
                    'OR' => __( 'Anyone Term', 'ae-pro' ),
                    'AND'  => __( 'All Terms', 'ae-pro' )
                ],
                'condition' => [
                    'ae_post_type' => 'related'
                ]
            ]
        );

	    if(class_exists('acf') || is_plugin_active('pods/init.php')){
	        $this->add_control(
	            'acf_relation_field',
                [
                    'label' => __('Relationship Field', 'ae-pro'),
                    'tyoe'  => Controls_Manager::TEXT,
                    'description'   => __('Key of ACF / Pods Relationship Field', 'ae-pro'),
                    'condition' => [
                            'ae_post_type'  => 'relation'
                    ]
                ]
            );
	    }

        $ae_taxonomy_filter_args = [
            'show_in_nav_menus' => true,
        ];

        $ae_taxonomies = get_taxonomies( $ae_taxonomy_filter_args, 'objects' );

        foreach ( $ae_taxonomies as $ae_taxonomy => $object ) {
            $this->add_control(
                $ae_taxonomy . '_ae_ids',
                [
                    'label'       => $object->label,
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'label_block' => true,
                    'placeholder' => __( 'Enter ' .$object->label. ' ID Separated by Comma', 'ae-pro' ),
                    'object_type' => $ae_taxonomy,
                    'options'     => Post_Helper::instance()->get_taxonomy_terms($ae_taxonomy),
                    'condition' => [
                        'ae_post_type' => $object->object_type,
                    ],
                ]
            );
        }

        $this->add_control(
            'current_post',
            [
                'label' => __( 'Exclude Current Post', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'advanced',
            [
                'label'   => __( 'Advanced', 'ae-pro' ),
                'type'    => Controls_Manager::HEADING,
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => __( 'Order By', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'post_date',
                'options' => [
                    'post_date'  => __( 'Date', 'ae-pro' ),
                    'post_title' => __( 'Title', 'ae-pro' ),
                    'menu_order' => __( 'Menu Order', 'ae-pro' ),
                    'rand'       => __( 'Random', 'ae-pro' ),
                    'meta_value'  => __( 'Custom Field', 'ae-pro' )
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'orderby_metakey_name',
            [
                'label' => __('Meta Key Name', 'ae-pro'),
                'tyoe'  => Controls_Manager::TEXT,
                'description'   => __('Custom Field Key', 'ae-pro'),
                'condition' => [
                    'orderby'  => 'meta_value'
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => __( 'Order', 'ae-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => __( 'ASC', 'ae-pro' ),
                    'desc' => __( 'DESC', 'ae-pro' ),
                ],
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __( 'Posts Count', 'ae-pro' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
                'condition' => [
                    'ae_post_type!' => 'current_loop'
                ]
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'   => __( 'Offset', 'ae-pro' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'ae_post_type!' => ['current_loop', 'ae_by_id'],
                ],
                'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'ae-pro' ),
            ]
        );



        $this->end_controls_section();

        $this->start_controls_section(
          'section_layout',
          [
              'label' => __( 'Layout', 'ae-pro' ),
          ]
        );

        $this->add_control(
            'layout_mode',
            [
                'label' => __('Layout Mode','ae-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'list'  => __('List','ae-pro'),
                    'grid'  => __('Grid', 'ae-pro')
                ],
                'default' => 'grid',
                'prefix_class' => 'ae-post-layout-'
            ]
        );


            $this->add_control(
            'masonry_grid',
            [
                'label' => __( 'Masonry', 'ae-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'masonry_on' => __( 'On', 'ae-pro' ),
                'masonry_off' => __( 'Off', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'layout_mode' => 'grid'
                ]
            ]
        );





        $this->add_responsive_control(
          'columns',
          [
              'label' => __('Columns', 'ae-pro'),
              'type'  => Controls_Manager::NUMBER,
              'desktop_default' => '3',
              'tablet_default' => '2',
              'mobile_default' => '1',
              'min' => 1,
              'max' => 6,
              'condition' => [
                    'layout_mode' => 'grid'
              ],
              'selectors' => [
                  '{{WRAPPER}} .ae-post-list-item' => 'width: calc(100%/{{ value }})',
               ]
          ]
        );

        $this->add_responsive_control(
            'item_col_gap',
            [
                'label' => __('Column Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'layout_mode' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}}.ae-post-layout-grid article.ae-post-list-item' => 'padding-left:{{SIZE}}{{UNIT}}; padding-right:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ae-post-layout-grid .ae-pagination-wrapper' => 'padding-right:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ae-pagination-wrapper' => 'padding-left:{{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'item_row_gap',
            [
                'label' => __('Row Gap', 'ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} article.ae-post-list-item' => 'margin-bottom:{{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        $this->pagination_controls();

        $this->start_controls_section(
            'layout_style',
            [
                'label' => __( 'Layout', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'label' => __( 'Item Background', 'ae-pro' ),
                'types' => [ 'none','classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-article-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-article-inner',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-article-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __( 'Item Shadow', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-article-inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_style',
            [
                'label' => __( 'Pagination', 'ae-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __('Item Gap','ae-pro'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'margin-left:{{SIZE}}{{UNIT}}; margin-right:{{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_responsive_control(
            'pi_padding',
            [
                'label' => __( 'Padding', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pi_color',
            [
                'label' => __('Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_bg',
            [
                'label' => __('Backround','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'background-color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_hover_color',
            [
                'label' => __('Hover/Current Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper .current' => 'color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper span:hover' => 'color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper a:hover' => 'color:{{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pi_hover_bg',
            [
                'label' => __('Hover/Current Background','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper .current' => 'background-color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper span:hover' => 'background-color:{{VALUE}}',
                    '{{WRAPPER}} .ae-pagination-wrapper a:hover' => 'background-color:{{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pi_border',
                'label' => __( 'Border', 'ae-pro' ),
                'selector' => '{{WRAPPER}} .ae-pagination-wrapper *',
            ]
        );

        $this->add_control(
            'pi_border_hover_color',
            [
                'label' => __('Border Hover Color','ae-pro'),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *:hover' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'pi_border_border!' => ''
                ]
            ]
        );

        $this->add_control(
            'pi_border_radius',
            [
                'label' => __( 'Border Radius', 'ae-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper *' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_style',
            [
                'label' => __( 'Loading Overlay', 'ae-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_color',
                'label' => __( 'Color', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .ae-post-overlay',
            ]
        );


        $this->end_controls_section();


    }

    protected function render() {

        $settings = $this->get_settings();
        if(!isset($settings['template']) || empty($settings['template'])){
            echo __('Please select a template first','ae-pro');
        }else{
            $this->generate_output($settings);
        }
    }

	function generate_output($settings,$with_wrapper = true){
		global $wp;
		$helper = new Helper();
		$post_type = $settings['ae_post_type'];

		$ae_post_ids = $settings['ae_post_ids'];

		if(isset($_POST['pid'])){
			$current_post_id = $_POST['pid'];
		}else{
			$current_post_id = get_the_ID();
		}
		$base_url = get_permalink($current_post_id);

		switch($post_type)
        {
			case 'current_loop' :   if(!Plugin::instance()->editor->is_edit_mode()){
                                        global $wp_query;
                                        $post_items = new WP_Query( $wp_query->query_vars );
                                    }else{
                                        $render_mode = get_post_meta($current_post_id, 'ae_render_mode', true);
                                        //echo $render_mode;
                                        switch($render_mode){
                                            case 'author_template': $author_data = $helper->get_preview_author_data();
                                                $query_args['author'] = $author_data['prev_author_id'];
                                                $query_args['post_type'] = 'any';
                                                break;

                                            case 'archive_template': $term_data = $helper->get_preview_term_data();
                                                $query_args['tax_query'] = [
                                                    [
                                                        'taxonomy' => $term_data['taxonomy'],
                                                        'field'    => 'term_id',
                                                        'terms'    => $term_data['prev_term_id']
                                                    ]
                                                ];
                                                $query_args['post_type'] = 'any';
                                                break;

                                            case 'date_template' : $query_args['post_type'] = 'post';
                                                                    break;

                                            default              : $query_args['post_type'] = 'post';
                                        }
                                    }
				                    break;

			case 'ae_by_id'     :   $query_args['post_type'] = 'any';
                                    $query_args['post__in']  = $ae_post_ids;

                                    if ( empty( $query_args['post__in'] ) ) {
                                        // If no selection - return an empty query
                                        $query_args['post__in'] = [ -1 ];
                                    }
                                    break;

			case 'related'      :   if(isset($_POST['fetch_mode'])){
                                        $cpost_id = $_POST['cpid'];
                                        $cpost = get_post($cpost_id);
                                    }else{
                                        $cpost = $helper->get_demo_post_data();
                                        $cpost_id = $cpost->ID;
                                    }

                                    $query_args = [
                                        'orderby' => $settings['orderby'],
                                        'order' => $settings['order'],
                                        'ignore_sticky_posts' => 1,
                                        'post_status' => 'publish', // Hide drafts/private posts for admins
                                        'offset'    => $settings['offset'],
                                        'posts_per_page' => $settings['posts_per_page'],
                                        'post__not_in'  => [ $cpost_id]
                                    ];

                                    if($settings['orderby'] == 'meta_value'){
                                        $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                    }

                                    if(isset($_POST['page_num'])){
                                        $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                    }

                                    $taxonomies = $settings['related_by'];

                                    if($taxonomies) {
                                        foreach ($taxonomies as $taxonomy) {

                                            $terms = get_the_terms($cpost_id, $taxonomy);
                                            if ($terms) {
                                                foreach ($terms as $term) {

                                                    $query_args['tax_query'][] = [
                                                        'taxonomy' => $taxonomy,
                                                        'field' => 'term_id',
                                                        'terms' => $term->term_id,
                                                    ];
                                                }
                                            }

                                        }

                                        $query_args['tax_query']['relation'] = $settings['related_match_with'];
                                    }
                                    break;

			case 'relation'     :   $field = $settings['acf_relation_field'];


                                    if(isset($_POST['fetch_mode'])){
                                        $cpost_id = $_POST['cpid'];
                                        $cpost = get_post($cpost_id);
                                    }else{
                                        $cpost = $helper->get_demo_post_data();
                                        $cpost_id = $cpost->ID;
                                    }

                                    if(is_plugin_active('advanced-custom-fields/acf.php') && is_plugin_active('pods/init.php')){
                                        if($settings['relationship_type'] == 'pods'){
                                            $pods =  get_post_meta($cpost_id,$field);
                                            foreach($pods as $pod){
                                                $post_items[] = $pod['ID'];
                                            }
                                        } else{
                                            $post_items = get_field($field, $cpost_id);
                                        }

                                    }else if(is_plugin_active('pods/init.php')){
                                        $pods =  get_post_meta($cpost_id,$field);
                                        foreach($pods as $pod){
                                            $post_items[] = $pod['ID'];
                                        }
                                    }else{
                                        $post_items = get_field($field, $cpost_id);
                                    }


                                    if($post_items){
                                        $query_args = [
                                            'orderby'           => $settings['orderby'],
                                            'order'             => $settings['order'],
                                            'ignore_sticky_posts' => 1,
                                            'post_status'       => 'publish', // Hide drafts/private posts for admins
                                            'offset'            => $settings['offset'],
                                            'posts_per_page'    => $settings['posts_per_page'],
                                            'post_type'         => 'any',
                                            'post__in'          => $post_items,
                                            'post__not_in'      => [ $cpost_id]
                                        ];

                                        if($settings['orderby'] == 'meta_value'){
                                            $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                        }

                                        if(isset($_POST['page_num'])){
                                            $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                        }
                                    }

				                    break;

			default             :   $query_args = [
                                        'orderby' => $settings['orderby'],
                                        'order' => $settings['order'],
                                        'ignore_sticky_posts' => 1,
                                        'post_status' => 'publish', // Hide drafts/private posts for admins
                                    ];

                                    if($settings['orderby'] == 'meta_value'){
                                        $query_args['meta_key'] = $settings['orderby_metakey_name'];
                                    }

                                    $query_args['post_type'] = $post_type;
                                    $query_args['offset'] = $settings['offset'];
                                    $query_args['posts_per_page'] = $settings['posts_per_page'];
                                    $query_args['tax_query'] = [];

                                    if(is_singular() && ($settings['current_post']=='yes')){
                                        $query_args['post__not_in'] = array($current_post_id);
                                    }
                                    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                                    foreach ( $taxonomies as $object ) {
                                        $setting_key = $object->name . '_ae_ids';

                                        if ( ! empty( $settings[ $setting_key ] ) ) {
                                            $query_args['tax_query'][] = [
                                                'taxonomy' => $object->name,
                                                'field'    => 'term_id',
                                                'terms'    => $settings[ $setting_key ],
                                            ];
                                        }
                                    }


                                    if(isset($_POST['page_num'])){
                                        $query_args['offset'] = ($query_args['posts_per_page'] * ($_POST['page_num']-1)) + $query_args['offset'];
                                    }
		}


		if($post_type == 'current_loop' && !Plugin::instance()->editor->is_edit_mode()){

		}else{
			if(isset($query_args)){
				$post_items = new WP_Query($query_args);
			}

		}

		if(!isset($query_args) && $post_type != 'current_loop' ){
			return;
		}

		$masonry = $settings['masonry_grid'];
		$this->add_render_attribute( 'post-list-wrapper', 'class', 'ae-post-list-wrapper' );

		$this->add_render_attribute( 'post-widget-wrapper', 'data-pid', get_the_ID() );
		$this->add_render_attribute( 'post-widget-wrapper', 'data-wid', $this->get_id() );
		$this->add_render_attribute( 'post-widget-wrapper', 'data-source', $settings['ae_post_type'] );
		$this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-post-widget-wrapper' );
		$this->add_render_attribute( 'post-widget-wrapper', 'class', 'ae-masonry-'.$masonry);

		if($post_type == 'current_loop'){
			$this->add_render_attribute( 'post-widget-wrapper', 'class', 'no-ajax' );
		}

		$this->add_render_attribute( 'post-list-item', 'class', 'ae-post-list-item' );

		$with_css = false;
		if ( is_customize_preview() || Utils::is_ajax() ) {
			$with_css = true;
		}

		?>
        <div class="ae-post-overlay"></div>
		<?php if($with_wrapper){ ?>
            <div <?php echo $this->get_render_attribute_string('post-widget-wrapper'); ?>>
		<?php } ?>
        <div <?php echo $this->get_render_attribute_string('post-list-wrapper'); ?>>
			<?php while($post_items->have_posts()){
				$post_items->the_post();
				?>
                <article <?php echo $this->get_render_attribute_string('post-list-item'); ?>>
                    <div class="ae-article-inner">
						<?php //echo Frontend::instance()->render_insert_elementor($settings['template'],$with_css); ?>
                        <div class="ae_data elementor elementor-<?php echo $settings['template']; ?>">
							<?php echo Plugin::instance()->frontend->get_builder_content( $settings['template'],$with_css ); ?>
                        </div>
                    </div>
                </article>
			<?php }
			wp_reset_postdata(); ?>
        </div>


		<?php if($settings['show_pagination'] == 'yes'){
			$this->add_render_attribute('pagination-wrapper','class','ae-pagination-wrapper');
			?>
            <div <?php echo $this->get_render_attribute_string('pagination-wrapper'); ?>>
				<?php
				$current = 1;
				if(isset($_POST['page_num'])){
					$current = $_POST['page_num'];
				}



				$paginate_args = [
					'base'  => $base_url.'%_%',
					'total' => $post_items->max_num_pages,
					'current' => $current
				];

				if($post_type == 'current_loop'){
					unset($paginate_args['base']);
					$current = get_query_var('paged');
					if($current == 0){
						$paginate_args['current'] = 1;
					}else{
						$paginate_args['current'] = $current;
					}
				}



				if($settings['show_prev_next'] == 'yes'){
					$paginate_args['prev_next'] = true;
					$paginate_args['prev_text'] = $settings['prev_text'];
					$paginate_args['next_text'] = $settings['next_text'];
				}else{
					$paginate_args['prev_next'] = false;
				}

				echo paginate_links($paginate_args);
				?>
            </div>
		<?php } ?>

		<?php if($with_wrapper){ ?>
            </div>
		<?php } ?>

		<?php
	}

    function pagination_controls(){

        $this->start_controls_section(
            'pagination_contols',
            [
                'label' => __( 'Pagination', 'ae-pro' )
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Show Pagination','ae-pro'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'yes' =>   __( 'Yes', 'ae-pro' ),
                    'no'  =>   __( 'No', 'ae-pro' )
                ],
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_prev_next',
            [
                'label' => __('Show Prev/Next','ae-pro'),
                'type'  => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Show', 'ae-pro' ),
                'label_off' => __( 'Hide', 'ae-pro' ),
                'return_value' => 'yes',
                'condition' => [
                    'show_pagination' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'prev_text',
            [
                'label' => __('Previous Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'default' => __('&laquo; Previous','ae-pro'),
                'condition' => [
                    'show_pagination' => 'yes',
                    'show_prev_next' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'next_text',
            [
                'label' => __('Previous Text','ae-pro'),
                'type'  => Controls_Manager::TEXT,
                'default' => __('Next &raquo;','ae-pro'),
                'condition' => [
                    'show_pagination' => 'yes',
                    'show_prev_next' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
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
                'condition' => [
                    'show_pagination' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ae-pagination-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Aepro_Post_Blocks() );