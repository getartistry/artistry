<?php
/**
 * Blog Grid Module
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OEW_Widget_Blog_Grid extends Widget_Base {

	public function get_name() {
		return 'oew-blog-grid';
	}

	public function get_title() {
		return __( 'Blog Grid', 'ocean-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'oceanwp-elements' ];
	}

	public function get_script_depends() {
		return [ 'oew-blog-grid', 'isotope', 'imagesloaded' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_blog_grid',
			[
				'label' 		=> __( 'Blog Grid', 'ocean-elementor-widgets' ),
			]
		);

		$this->add_control(
			'count',
			[
				'label' 		=> __( 'Posts Per Page', 'ocean-elementor-widgets' ),
				'description' 	=> __( 'You can enter "-1" to display all posts.', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '6',
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=> __( 'Grid Columns', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '3',
				'options' 		=> [
					'2' 		=> '2',
					'3' 		=> '3',
					'4' 		=> '4',
					'5' 		=> '5',
					'6' 		=> '6',
				],
			]
		);

		$this->add_control(
			'grid_style',
			[
				'label' 		=> __( 'Grid Style', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'fit-rows',
				'options' 		=> [
					'fit-rows' 	=> __( 'Fit Rows', 'ocean-elementor-widgets' ),
					'masonry' 	=> __( 'Masonry', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'grid_equal_heights',
			[
				'label' 		=> __( 'Equal Heights', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'no',
			]
		);

		$this->add_control(
			'order',
			[
				'label' 		=> __( 'Order', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 			=> __( 'Default', 'ocean-elementor-widgets' ),
					'DESC' 		=> __( 'DESC', 'ocean-elementor-widgets' ),
					'ASC' 		=> __( 'ASC', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 		=> __( 'Order By', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 				=> __( 'Default', 'ocean-elementor-widgets' ),
					'date' 			=> __( 'Date', 'ocean-elementor-widgets' ),
					'title' 		=> __( 'Title', 'ocean-elementor-widgets' ),
					'name' 			=> __( 'Name', 'ocean-elementor-widgets' ),
					'modified' 		=> __( 'Modified', 'ocean-elementor-widgets' ),
					'author' 		=> __( 'Author', 'ocean-elementor-widgets' ),
					'rand' 			=> __( 'Random', 'ocean-elementor-widgets' ),
					'ID' 			=> __( 'ID', 'ocean-elementor-widgets' ),
					'comment_count' => __( 'Comment Count', 'ocean-elementor-widgets' ),
					'menu_order' 	=> __( 'Menu Order', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' 		=> __( 'Pagination', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'no',
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' 		=> __( 'Pagination Position', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'ocean-elementor-widgets' ),
						'icon' 	=> 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'ocean-elementor-widgets' ),
						'icon' 	=> 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'ocean-elementor-widgets' ),
						'icon' 	=> 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.page-numbers' => 'text-align: {{VALUE}};',
				],
				'default' 		=> 'center',
				'condition' => [
					'pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'include_categories',
			[
				'label' 		=> __( 'Include Categories', 'ocean-elementor-widgets' ),
				'description' 	=> __( 'Enter the categories slugs seperated by a "comma"', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' 		=> __( 'Exclude Categories', 'ocean-elementor-widgets' ),
				'description' 	=> __( 'Enter the categories slugs seperated by a "comma"', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'section_elements',
            [
                'label' => __( 'Elements', 'ocean-elementor-widgets' )
            ]
        );

		$this->add_control(
			'image_size',
			[
				'label' 		=> __( 'Image Size', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'medium',
				'options' 		=> $this->get_img_sizes(),
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' 		=> __( 'Learn More Text', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Learn More', 'ocean-elementor-widgets' ),
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'title',
			[
				'label' 		=> __( 'Display Title', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'author',
			[
				'label' 		=> __( 'Display Author', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'comments',
			[
				'label' 		=> __( 'Display Comments', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'cat',
			[
				'label' 		=> __( 'Display Categories', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label' 		=> __( 'Display Excerpt', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' 		=> __( 'Excerpt Length', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '15',
				'label_block' 	=> true,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_grid',
			[
				'label' 		=> __( 'Grid', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'grid_background_color',
			[
				'label' 		=> __( 'Background Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_border_color',
			[
				'label' 		=> __( 'Border Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-inner' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' 		=> __( 'Overlay Button', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'ocean-elementor-widgets' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' 		=> __( 'Background Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' 		=> __( 'Border Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' 		=> __( 'Border Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .overlay-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tab();

        $this->end_controls_section();

		$this->start_controls_section(
			'section_avatar',
			[
				'label' 		=> __( 'Author Avatar', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'avatar_border_color',
			[
				'label' 		=> __( 'Border Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-media .entry-author-link' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' 		=> __( 'Title', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-details .oew-grid-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' 		=> __( 'Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-details .oew-grid-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'title_typo',
				'selector' 		=> '{{WRAPPER}} .oew-blog-grid .oew-grid-details .oew-grid-title',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_excerpt',
			[
				'label' 		=> __( 'Excerpt', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-details .oew-grid-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'excerpt_typo',
				'selector' 		=> '{{WRAPPER}} .oew-blog-grid .oew-grid-details .oew-grid-excerpt',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_meta',
			[
				'label' 		=> __( 'Meta', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_bg',
			[
				'label' 		=> __( 'Background Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-meta' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-meta, {{WRAPPER}} .oew-blog-grid .oew-grid-meta li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color_hover',
			[
				'label' 		=> __( 'Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-blog-grid .oew-grid-meta li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'meta_typo',
				'selector' 		=> '{{WRAPPER}} .oew-blog-grid .oew-grid-meta',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

	}

	public function get_img_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();
	    $get_intermediate_image_sizes = get_intermediate_image_sizes();
	 
	    // Create the full array with sizes and crop info
	    foreach( $get_intermediate_image_sizes as $_size ) {
	        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
	            $sizes[ $_size ]['width'] 	= get_option( $_size . '_size_w' );
	            $sizes[ $_size ]['height'] 	= get_option( $_size . '_size_h' );
	            $sizes[ $_size ]['crop'] 	= (bool) get_option( $_size . '_crop' );
	        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
	            $sizes[ $_size ] = array( 
	                'width' 	=> $_wp_additional_image_sizes[ $_size ]['width'],
	                'height' 	=> $_wp_additional_image_sizes[ $_size ]['height'],
	                'crop' 		=> $_wp_additional_image_sizes[ $_size ]['crop'],
	            );
	        }
	    }

	    $image_sizes = array();

		foreach ( $sizes as $size_key => $size_attributes ) {
			$image_sizes[ $size_key ] = ucwords( str_replace( '_', ' ', $size_key ) ) . sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
		}

		$image_sizes['full'] 	= _x( 'Full', 'Image Size Control', 'ocean-portfolio' );

	    return $image_sizes;
	}

	protected function render() {
		$settings = $this->get_settings();

		// Vars
		$posts_per_page = $settings['count'];
		$order 			= $settings['order'];
		$orderby  		= $settings['orderby'];
	    $include 		= $settings['include_categories'];
	    $exclude 		= $settings['exclude_categories'];
		$pagination  	= $settings['pagination'];

		// Paged
		global $paged;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$args = array(
	        'post_type'         => 'post',
	        'posts_per_page'    => $posts_per_page,
			'paged' 			=> $paged,
	        'order'             => $order,
	        'orderby'           => $orderby,
			'tax_query' 		=> array(
				'relation' 		=> 'AND',
			),
	    );

	    // Include category
		if ( ! empty( $include ) ) {

			// Sanitize category and convert to array
			$include = str_replace( ', ', ',', $include );
			$include = explode( ',', $include );

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $include,
				'operator' => 'IN',
			);

		}

		// Exclude category
		if ( ! empty( $exclude ) ) {

			// Sanitize category and convert to array
			$exclude = str_replace( ', ', ',', $exclude );
			$exclude = explode( ',', $exclude );

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $exclude,
				'operator' => 'NOT IN',
			);

		}

	    // Build the WordPress query
	    $oew_query = new \WP_Query( $args );

		// Output posts
		if ( $oew_query->have_posts() ) :

			// Vars
			$grid_style 	= $settings['grid_style'];
			$equal_heights 	= $settings['grid_equal_heights'];
			$readmore 		= $settings['readmore_text'];
			$title   		= $settings['title'];
			$excerpt 		= $settings['excerpt'];
			$columns 		= $settings['columns'];
			$author 		= $settings['author'];
			$comments 		= $settings['comments'];
			$cat 			= $settings['cat'];

			// Image size
			$img_size 		= $settings['image_size'];
			$img_size 		= $img_size ? $img_size : 'medium';

			// Wrapper classes
			$wrap_classes = array( 'oew-blog-grid', 'oceanwp-row', 'clr' );

			if ( 'masonry' == $grid_style ) {
				$wrap_classes[] = 'oew-masonry';
			}

			if ( 'yes' == $equal_heights ) {
				$wrap_classes[] = 'match-height-grid';
			}

			if ( 'true' == $author ) {
				$wrap_classes[] = 'has-avatar';
			}

			$wrap_classes = implode( ' ', $wrap_classes ); ?>

			<div class="<?php echo esc_attr( $wrap_classes ); ?>">

				<?php
				// Define counter var to clear floats
				$count = '';

				// Start loop
				while ( $oew_query->have_posts() ) : $oew_query->the_post();

					// Counter
					$count++;

					// Inner classes
					$inner_classes 		= array( 'oew-grid-entry', 'col', 'clr' );
					$inner_classes[] 	= 'span_1_of_' . $columns;
					$inner_classes[] 	= 'col-' . $count;

					if ( 'masonry' == $grid_style ) {
						$inner_classes[] = 'isotope-entry';
					}

					$inner_classes = implode( ' ', $inner_classes );

					// If equal heights
					$details_class = '';
					if ( 'yes' == $equal_heights ) {
						$details_class = ' match-height-content';
					}

					// Meta class
					$meta_class = '';
					if ( 'false' == $comments
						|| 'false' == $cat ) {
						$meta_class = ' oew-center';
					}

					// Create new post object.
					$post = new \stdClass();

					// Get post data
					$get_post = get_post();

					// Post Data
					$post->ID           = $get_post->ID;
					$post->permalink    = get_the_permalink( $post->ID );
					$post->title        = $get_post->post_title;

					// Only display carousel item if there is content to show
					if ( has_post_thumbnail()
						|| 'true' == $title
						|| 'true' == $excerpt
					) { ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( $inner_classes ); ?>>

							<?php
							// Open details if the elements are true
							if ( 'true' == $title
								|| 'true' == $excerpt
							) { ?>

								<div class="oew-grid-inner clr">
							
									<?php
									// Display thumbnail if enabled and defined
									if ( has_post_thumbnail() ) { ?>

										<div class="oew-grid-media clr">

											<a href="<?php echo $post->permalink; ?>" title="<?php the_title(); ?>" class="oew-grid-img">

												<?php
												// Display post thumbnail
												the_post_thumbnail( $img_size, array(
													'alt'		=> get_the_title(),
													'itemprop' 	=> 'image',
												) ); ?>

												<span class="overlay">
													<?php
													// Display read more
													if ( '' != $readmore ) { ?>
														<span class="overlay-btn">
															<?php echo $readmore; ?>
														</span>
													<?php } ?>
												</span>

											</a>

											<?php if ( 'true' == $author ) { ?>
												<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'ocean-elementor-widgets' ); ?>" class="entry-author-link" rel="author" >
													<?php
													// Display author avatar
													echo get_avatar( get_the_author_meta( 'user_email' ), 100 ); ?>
												</a>
											<?php } ?>

										</div><!-- .oew-grid-media -->

									<?php } ?>

									<?php
									// Open details element if the title or excerpt are true
									if ( 'true' == $title
										|| 'true' == $excerpt
									) { ?>

										<div class="oew-grid-details<?php echo esc_attr( $details_class ); ?> clr">

											<?php
											// Display title if $title is true and there is a post title
											if ( 'true' == $title ) { ?>

												<h2 class="oew-grid-title entry-title">
													<a href="<?php echo $post->permalink; ?>" title="<?php the_title(); ?>"><?php echo $post->title; ?></a>
												</h2>

											<?php } ?>

											<?php
											// Display excerpt if $excerpt is true
											if ( 'true' == $excerpt ) { ?>

												<div class="oew-grid-excerpt clr">
													<?php oew_excerpt( $settings['excerpt_length'] ); ?>
												</div>
												
											<?php } ?>

										</div><!-- .oew-grid-details -->

									<?php } ?>

									<?php
									// Display meta
									if ( 'true' == $comments
										|| 'true' == $cat ) { ?>

										<ul class="oew-grid-meta<?php echo esc_attr( $meta_class ); ?> clr">

											<?php if ( 'true' == $comments && comments_open() && ! post_password_required() ) { ?>
												<li class="meta-comments"><i class="icon-bubble"></i><?php comments_popup_link( esc_html__( '0 Comments', 'ocean-elementor-widgets' ), esc_html__( '1 Comment',  'ocean-elementor-widgets' ), esc_html__( '% Comments', 'ocean-elementor-widgets' ), 'comments-link' ); ?></li>
											<?php } ?>

											<?php if ( 'true' == $cat ) { ?>
												<li class="meta-cat"><i class="icon-folder"></i><?php the_category( ' / ', get_the_ID() ); ?></li>
											<?php } ?>

										</ul>

									<?php } ?>

								</div>

							<?php } ?>

						</article>

					<?php } ?>

					<?php
					// Reset entry counter
					if ( $count == $columns ) {
						$count = '0';
					} ?>

				<?php
				// End entry loop
				endwhile; ?>

			</div><!-- .oew-blog-grid -->
				
			<?php
			// Display pagination if enabled
			if ( 'yes' == $pagination ) {
				oceanwp_pagination( $oew_query );
			} ?>

			<?php
			// Reset the post data to prevent conflicts with WP globals
			wp_reset_postdata(); wp_reset_query();

		// If no posts are found display message
		else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'ocean-elementor-widgets' ); ?></p>

		<?php
		// End post check
		endif; ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new OEW_Widget_Blog_Grid() );