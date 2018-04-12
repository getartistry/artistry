<?php
namespace ElementorExtras\Modules\Breadcrumbs\Widgets;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Breadcrumbs
 *
 * @since 1.2.0
 */
class Breadcrumbs extends Extras_Widget {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	private $_separator = null;

	public function get_name() {
		return 'ee-breadcrumbs';
	}

	public function get_title() {
		return __( 'Breadcrumbs', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-breadcrumbs';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Breadcrumbs', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'display_heading',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Display', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'show_home',
				[
					'label' 		=> __( 'Show Home', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-extras' ),
					'label_off' 	=> __( 'No', 'elementor-extras' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'home_text',
				[
					'label' 		=> __( 'Home Text', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( 'Homepage', 'elementor-extras' ),
					'condition'		=> [
						'show_home' => 'yes'
					],
				]
			);

			$this->add_control(
				'source',
				[
					'label' 	=> __( 'Source', 'elementor-extras' ),
					'type'		=> Controls_Manager::SELECT,
					'default'	=> '',
					'options'	=> [
						''		=> __( 'Current page', 'elementor-extras' ),
						'id'	=> __( 'Specific page', 'elementor-extras' ),
					]
				]
			);

			$this->add_control(
				'source_id',
				[
					'label' 		=> __( 'ID', 'elementor-extras' ),
					'type'			=> Controls_Manager::NUMBER,
					'min' 			=> 0,
					'placeholder' 	=> '15',
					'condition'		=> [
						'source'	=> 'id',
					]
				]

			);

			$this->add_control(
				'separator_heading',
				[
					'type'		=> Controls_Manager::HEADING,
					'label' 	=> __( 'Separator', 'elementor-extras' ),
				]
			);

			$this->add_control(
				'separator_type',
				[
					'label'		=> __( 'Separator Type', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'icon',
					'options' 	=> [
						'text' 		=> __( 'Text', 'elementor-extras' ),
						'icon' 		=> __( 'Icon', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'separator_text',
				[
					'label' 		=> __( 'Separator', 'elementor-extras' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> __( '>', 'elementor-extras' ),
					'condition'		=> [
						'separator_type' => 'text'
					],
				]
			);

			$this->add_control(
				'separator_icon',
				[
					'label' 		=> __( 'Separator', 'elementor-extras' ),
					'type' 			=> Controls_Manager::ICON,
					'label_block' 	=> true,
					'default' 		=> 'fa fa-angle-right',
					'condition'		=> [
						'separator_type' => 'icon'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' 	=> __( 'Crumbs', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'items_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'prefix_class' 	=> 'ee-breadcrumbs-align-',
				]
			);

			$this->add_responsive_control(
				'items_text_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'item_spacing',
				[
					'label' 	=> __( 'Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> 12
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 36,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs' => 'margin-left: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-breadcrumbs__item' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ee-breadcrumbs__separator' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'item_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-breadcrumbs__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'item_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs__item',
				]
			);

			$this->add_control(
				'item_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-breadcrumbs__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'item_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__item' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'item_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__item' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ee-breadcrumbs__item a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'item_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separators',
			[
				'label' 	=> __( 'Separators', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'separator_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-breadcrumbs__separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'separator_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs__separator',
				]
			);

			$this->add_control(
				'separator_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-breadcrumbs__separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'separator_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__separator' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'separator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__separator' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'separator_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs__separator',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_current_style',
			[
				'label' 	=> __( 'Current', 'elementor-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'current_border',
					'label' 	=> __( 'Border', 'elementor-extras' ),
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs__item--current',
				]
			);

			$this->add_control(
				'current_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-breadcrumbs__item--current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'current_background_color',
				[
					'label' 	=> __( 'Background Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__item--current' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'current_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-breadcrumbs__item--current' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'current_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_4,
					'selector' 	=> '{{WRAPPER}} .ee-breadcrumbs__item--current',
				]
			);

		$this->end_controls_section();

	}

	protected function get_query() {

		global $post;

		$settings 	= $this->get_settings();
		$_id 		= null;
		$_post_type = 'post';

		if ( 'id' === $settings['source'] && '' !== $settings['source_id'] ) {

			$_id = $settings['source_id'];
			$_post_type = 'any';

			$_args = array(
				'p' 		=> $_id,
				'post_type' => $_post_type,
			);

			// Create custom query
			$_post_query = new \WP_Query( $_args );

			return $_post_query;
		}

		return false;
	}

	protected function set_separator() {

		$settings = $this->get_settings();

		if ( 'icon' === $settings['separator_type'] ) {
			
			$separator = '<i class="' . $settings['separator_icon'] . '"></i>';

		} else {

			$this->add_inline_editing_attributes( 'separator_text' );
			$this->add_render_attribute( 'separator_text', 'class', 'ee-breadcrumbs__separator__text' );
			
			$separator = '<span ' . $this->get_render_attribute_string( 'separator_text' ) . '>' . $settings['separator_text'] . '</span>';

		}

		$this->_separator = $separator;
	}

	protected function get_separator() {
		return $this->_separator;
	}

	protected function render() {

		$settings 	= $this->get_settings();
		$_query 	= $this->get_query();

		$this->set_separator();
		$this->add_render_attribute( 'breadcrumbs', 'class', 'ee-breadcrumbs' );

		if ( $_query ) {
			if ( $_query->have_posts() ) {

				// Setup post
				$_query->the_post();

				// Render using the new query
				$this->render_breadcrumbs( $_query );

				// Reset post data to original query
				wp_reset_postdata();
				wp_reset_query();

			} else {

				_e( 'Post or page not found', 'elementor-extras' );

			}
		} else {
			// Render using the original query
			$this->render_breadcrumbs();
		}
	}

	protected function render_home_link() {
		$settings = $this->get_settings();

		$this->add_inline_editing_attributes( 'home_text' );
		$this->add_render_attribute( 'home_text', [
			'class' => [
				'ee-breadcrumbs__crumb--link',
				'ee-breadcrumbs__crumb--home'
			],
			'href' 	=> get_home_url(),
			'title' => $settings['home_text']
		] );

		?>
		<li class="ee-breadcrumbs__item ee-breadcrumbs__item--home">
			<a <?php echo $this->get_render_attribute_string( 'home_text' ); ?>>
				<?php echo $settings['home_text']; ?>
			</a>
		</li>
		<?php

		$this->render_separator();

	}

	protected function render_separator( $output = true ) {

		$this->add_render_attribute( 'separator', 'class', 'ee-breadcrumbs__separator' );

		$markup = '<li ' . $this->get_render_attribute_string( 'separator' ) . '>';
		$markup .= $this->get_separator();
		$markup .= '</li>';

		if ( $output === true ) {
			echo $markup;
			return;
		}

		return $markup;
	}

	protected function render_breadcrumbs( $query = false ) {

		global $post, $wp_query;

		if ( $query === false ) {

			// Reset post data to parent query
			$wp_query->reset_postdata();

			// Set active query to native query
			$query = $wp_query;
		}

		$settings = $this->get_settings();
		$separator = $this->get_separator();

		$custom_taxonomy = 'product_cat';

		if ( ! $query->is_front_page() ) { ?>
		
			<ul <?php echo $this->get_render_attribute_string( 'breadcrumbs' ); ?>>

			<?php

			if ( 'yes' === $settings['show_home'] ) {
				$this->render_home_link();
			}

			if ( $query->is_archive() && ! $query->is_tax() && ! $query->is_category() && ! $query->is_tag() ) {
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--archive"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--archive">' . post_type_archive_title( $prefix, false ) . '</strong></li>';
				
			} else if ( $query->is_archive() && $query->is_tax() && ! $query->is_category() && ! $query->is_tag() ) {
				
				$post_type = get_post_type();
				
				if( $post_type != 'post' ) {
					
					$post_type_object = get_post_type_object($post_type);
					$post_type_archive = get_post_type_archive_link($post_type);
					
					echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--cat ee-breadcrumbs__item--custom-post-type-' . $post_type . '"><a class="ee-breadcrumbs__crumb--cat ee-breadcrumbs__crumb--custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';

					$this->render_separator();
				}
				$custom_tax_name = get_queried_object()->name;
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--archive"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--archive">' . $custom_tax_name . '</strong></li>';
				
			} else if ( $query->is_single() ) {
				
				$post_type = get_post_type();
				
				if( $post_type != 'post' ) {
						$post_type_object = get_post_type_object($post_type);
					$post_type_archive = get_post_type_archive_link($post_type);
					echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--cat ee-breadcrumbs__item--custom-post-type-' . $post_type . '"><a class="ee-breadcrumbs__crumb--cat ee-breadcrumbs__crumb--custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
					
					$this->render_separator();
				}

				$category = get_the_category();

				if( ! empty( $category ) ) {

					$values = array_values($category);
					
					$last_category = end( $values );
						
					$get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
					$cat_parents = explode(',',$get_cat_parents);
						
					$cat_display = '';

					foreach( $cat_parents as $parents ) {
						$cat_display .= '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--cat">' . $parents . '</li>';
						$cat_display .= $this->render_separator( false );
					}

				}

				$taxonomy_exists = taxonomy_exists( $custom_taxonomy );

				if( empty( $last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {
						$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );

					if ( $taxonomy_terms ) {
						$cat_id = $taxonomy_terms[0]->term_id;
						$cat_nicename = $taxonomy_terms[0]->slug;
						$cat_link = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
						$cat_name = $taxonomy_terms[0]->name;
					}
				}
				if( ! empty( $last_category ) ) {

					echo $cat_display;

					echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--' . $post->ID . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
					
				} else if ( ! empty( $cat_id ) ) {
					echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--cat ee-breadcrumbs__item--cat-' . $cat_id . ' ee-breadcrumbs__item--cat-' . $cat_nicename . '"><a class="ee-breadcrumbs__crumb--cat ee-breadcrumbs__crumb--cat-' . $cat_id . ' ee-breadcrumbs__crumb--cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
					
					$this->render_separator();

					echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--' . $post->ID . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
				} else {
						echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--' . $post->ID . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
					}
				
			} else if ( $query->is_category() ) {
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--cat"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--cat">' . single_cat_title('', false) . '</strong></li>';
				
			} else if ( $query->is_page() ) {
				
				if ( $post->post_parent ) {
						
					$anc = get_post_ancestors( $post->ID );
						
					$anc = array_reverse($anc);
						
					if ( ! isset( $parents ) ) $parents = null;

					foreach ( $anc as $ancestor ) {

						$parents .= '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--parent ee-breadcrumbs__item--parent-' . $ancestor . '"><a class="ee-breadcrumbs__crumb--parent ee-breadcrumbs__crumb--parent-' . $ancestor . '" href="' . get_permalink( $ancestor ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';

						$parents .= $this->render_separator( false );
					}
						
					echo $parents;

				}

				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--' . $post->ID . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
				
			} else if ( $query->is_tag() ) {
				
				
				$term_id 		= get_query_var('tag_id');
				$taxonomy 		= 'post_tag';
				$args 			= 'include=' . $term_id;
				$terms 			= get_terms( $taxonomy, $args );
				$get_term_id 	= $terms[0]->term_id;
				$get_term_slug 	= $terms[0]->slug;
				$get_term_name 	= $terms[0]->name;
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--tag-' . $get_term_id . ' ee-breadcrumbs__item--tag-' . $get_term_slug . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--tag-' . $get_term_id . ' ee-breadcrumbs__crumb--tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
			
			} elseif ( $query->is_day() ) {
				
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--year ee-breadcrumbs__item--year-' . get_the_time('Y') . '"><a class="ee-breadcrumbs__crumb--year ee-breadcrumbs__crumb--year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';

				$this->render_separator();
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--month ee-breadcrumbs__item--month-' . get_the_time('m') . '"><a class="ee-breadcrumbs__crumb--month ee-breadcrumbs__crumb--month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
				
				$this->render_separator();
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--' . get_the_time('j') . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
				
			} else if ( $query->is_month() ) {
				
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--year ee-breadcrumbs__item--year-' . get_the_time('Y') . '"><a class="ee-breadcrumbs__crumb--year ee-breadcrumbs__crumb--year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
				
				$this->render_separator();
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--month ee-breadcrumbs__item--month-' . get_the_time('m') . '"><strong class="ee-breadcrumbs__crumb--month ee-breadcrumbs__crumb--month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
				
			} else if ( $query->is_year() ) {
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--current-' . get_the_time('Y') . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
				
			} else if ( $query->is_author() ) {
				
				
				global $author;
				$userdata = get_userdata( $author );
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--current-' . $userdata->user_nicename . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
			
			} else if ( get_query_var('paged') ) {
				
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--current-' . get_query_var('paged') . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
				
			} else if ( $query->is_search() ) {
			
				echo '<li class="ee-breadcrumbs__item ee-breadcrumbs__item--current ee-breadcrumbs__item--current-' . get_search_query() . '"><strong class="ee-breadcrumbs__crumb--current ee-breadcrumbs__crumb--current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
			
			} elseif ( $query->is_404() ) {
				
				echo '<li>' . 'Error 404' . '</li>';
			}
		
			echo '</ul>';
			
		}
	}

	protected function _content_template() {
		
	}
}
