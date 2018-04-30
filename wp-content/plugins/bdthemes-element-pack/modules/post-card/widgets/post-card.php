<?php
namespace ElementPack\Modules\PostCard\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Card extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-post-card';
	}

	public function get_title() {
		return esc_html__( 'Post Card', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => _x( 'Source', 'Posts Query Control', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''        => esc_html__( 'Show All', 'bdthemes-element-pack' ),
					'by_name' => esc_html__( 'Manual Selection', 'bdthemes-element-pack' ),
				],
				'label_card' => true,
			]
		);

		$post_categories = get_terms( 'category' );

		$post_options = [];
		foreach ( $post_categories as $category ) :
			$post_options[ $category->slug ] = $category->name;
		endforeach;

		$this->add_control(
			'post_categories',
			[
				'label'       => esc_html__( 'Categories', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $post_options,
				'default'     => [],
				'label_block' => true,
				'multiple'    => true,
				'condition'   => [
					'source'    => 'by_name',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'     => esc_html__( 'Date', 'bdthemes-element-pack' ),
					'title'    => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'category' => esc_html__( 'Category', 'bdthemes-element-pack' ),
					'rand'     => esc_html__( 'Random', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'Descending', 'bdthemes-element-pack' ),
					'ASC'  => esc_html__( 'Ascending', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'thumb',
			[
				'label'     => esc_html__( 'Image', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'meta',
			[
				'label'     => esc_html__( 'Meta Data', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'tags',
			[
				'label'     => esc_html__( 'Tags', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label'     => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'   => esc_html__( 'Excerpt Length', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 15,
				'condition' => [
					'excerpt'   => 'yes',
				],
			]
		);

		$this->add_control(
			'button',
			[
				'label'     => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'yes', 'bdthemes-element-pack' ),
				'label_off' => esc_html__( 'no', 'bdthemes-element-pack' ),
				'default'   => 'yes',
			]
		);		
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'condition' => [
					'button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'default'     => esc_html__( 'Read More', 'bdthemes-element-pack' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Before', 'bdthemes-element-pack' ),
					'right' => esc_html__( 'After', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-button-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-card .bdt-button-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_item',
			[
				'label' => esc_html__( 'Item', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Description Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'item_background',
			[
				'label'     => esc_html__( 'Item Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-desc' => 'background-color: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'shadow_color',
			[
				'label'     => esc_html__( 'Highlightrd Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card > div:nth-child(2) .bdt-post-card-item' => 'box-shadow: 0 0 0 20px {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_tags',
			[
				'label' => esc_html__( 'Tags', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tags' => 'yes',
				],
			]
		);

		$this->add_control(
			'tags_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-tag a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tag_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-tag a' => 'background: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tags_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-tag a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-meta li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-meta li',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => esc_html__( 'Excerpt', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-excerpt' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-button',
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-post-card .bdt-post-card-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-card .bdt-post-card-button',
			]
		);

		$this->end_controls_section();
	}

	public function filter_excerpt_length() {
		return $this->get_settings( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}
	public function render() {
		$settings = $this->get_settings();

		global $post;
		$media     = '';
		$id        = uniqid('bdtpc_');
		$classes   = ['bdt-post-card', 'bdt-grid-collapse', 'bdt-child-width-1-1@s', 'bdt-child-width-1-3@m'];
		$css_class = ['bdt-post-card-desc'];

		$animation = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';

		$args = array(
			'posts_per_page' => 3,
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'post_status'    => 'publish'
		);
		
		if ( 'by_name' === $settings['source'] ) :

			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $settings['post_categories'],
			);
		endif;

		$wp_query = new \WP_Query($args);

		if( $wp_query->have_posts() ) :

			add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
			add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		?> 

			<div class="<?php echo implode(" ", $classes); ?> bdt-grid bdt-grid-match">
		
			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();

				if('yes' == $settings['thumb']) :
			  		$blog_thumbnail= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );

			  		if($blog_thumbnail[0] != '') :
			  
			  			$media = '<a href="'.esc_url(get_permalink()).'" title="' . esc_attr(get_the_title()) . '" class="bdt-post-card-thumb"><img src="'.esc_url($blog_thumbnail[0]).'" alt="' . esc_attr(get_the_title()) . '" /></a>';
			  		
			  		endif;
			  	endif;
			?>

		  		<div>
			  		<div class="bdt-post-card-item">
				  
						<?php echo wp_kses_post($media); ?>
				  		
				  		<div class="<?php echo \element_pack_helper::acssc($css_class); ?>">

				  			<?php if ('yes' == $settings['tags']) : ?>

					  			<?php $tags_list = get_the_tag_list( '<span>', '</span> <span>', '</span>'); ?>

				                <?php if ($tags_list) : ?>
				                    <p class="bdt-post-card-tag" ><?php echo  wp_kses_post($tags_list); ?></p>
				                <?php endif ?>
			            	<?php endif ?>

			            	<?php if ('yes' == $settings['title']) : ?>
								<h4 class="bdt-post-card-title"><a href="<?php echo esc_url(get_permalink()); ?>" title="<?php esc_attr(get_the_title()) ; ?>"><?php echo esc_html(get_the_title()) ; ?></a></h4>
							<?php endif ?>

							<?php if ('yes' == $settings['meta']) :
									$meta_list = '<li>'.esc_attr(get_the_date('M d, Y')).'</li><li>'.get_the_category_list(', ').'</li>';
							?>
								
								<ul class="bdt-post-card-meta bdt-subnav"><?php echo wp_kses_post($meta_list); ?></ul>
							<?php endif ?>

							<?php if ('yes' == $settings['excerpt']) : ?>
								<div class="bdt-post-card-excerpt"><?php echo wp_kses_post(the_excerpt()); ?></div>
							<?php endif ?>

							<?php if ('yes' == $settings['button']) : ?>
								<a href="<?php echo esc_url(get_permalink()); ?>" class="bdt-post-card-button<?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['button_text']); ?>

									<?php if ($settings['icon']) : ?>
										<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
											<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
										</span>
									<?php endif; ?>

								</a>
							<?php endif ?>	
				  		</div>

					</div>
				</div>
		  
			<?php 
				endwhile; 
				wp_reset_postdata(); 
			?>
		
			</div>
		
		 	<?php 
				remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
				remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
 		endif;
	}
}
