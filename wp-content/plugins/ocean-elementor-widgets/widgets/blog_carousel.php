<?php
/**
 * Blog Carousel Module
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class OEW_Widget_Blog_Carousel extends Widget_Base {

	public function get_name() {
		return 'oew-blog-carousel';
	}

	public function get_title() {
		return __( 'Blog Carousel', 'ocean-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return [ 'oceanwp-elements' ];
	}

	public function get_script_depends() {
		return [ 'oew-blog-carousel', 'jquery-slick' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_blog_carousel',
			[
				'label' 		=> __( 'Carousel', 'ocean-elementor-widgets' ),
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' 		=> __( 'Display Arrows', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label' 		=> __( 'Items To Display', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '3',
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'tablet',
			[
				'label' 		=> __( 'Tablet: Items To Display', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '2',
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'mobile',
			[
				'label' 		=> __( 'Mobile: Items To Display', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '1',
				'label_block' 	=> true,
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'ocean-elementor-widgets' )
            ]
        );

		$this->add_control(
			'count',
			[
				'label' 		=> __( 'Post Count', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '6',
				'label_block' 	=> true,
				'separator' 	=> 'before',
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
			'title',
			[
				'label' 		=> __( 'Title', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'meta',
			[
				'label' 		=> __( 'Meta', 'ocean-elementor-widgets' ),
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
				'label' 		=> __( 'Author Meta', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'true',
				'options' 		=> [
					'true' 		=> __( 'Show', 'ocean-elementor-widgets' ),
					'false' 	=> __( 'Hide', 'ocean-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'date',
			[
				'label' 		=> __( 'Date Meta', 'ocean-elementor-widgets' ),
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
				'label' 		=> __( 'Categories Meta', 'ocean-elementor-widgets' ),
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
				'label' 		=> __( 'Comments Meta', 'ocean-elementor-widgets' ),
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
				'label' 		=> __( 'Excerpt', 'ocean-elementor-widgets' ),
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

		$this->add_control(
			'readmore_text',
			[
				'label' 		=> __( 'Learn More Text', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Learn More', 'ocean-elementor-widgets' ),
				'label_block' 	=> true,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_arrows',
			[
				'label' 		=> __( 'Arrows', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label' 		=> __( 'Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .slick-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' 		=> __( 'Content', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label' 		=> __( 'Padding', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .oew-carousel-entry-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_bg_',
			[
				'label' 		=> __( 'Background Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .oew-carousel-entry-details' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .oew-carousel .entry-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' 		=> __( 'Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .entry-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'title_typo',
				'selector' 		=> '{{WRAPPER}} .oew-carousel .entry-title',
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
			'meta_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} ul.meta, {{WRAPPER}} ul.meta li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_links_hover_color',
			[
				'label' 		=> __( 'Links Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} ul.meta li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_icons_color',
			[
				'label' 		=> __( 'Icons Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .meta li i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'meta_typo',
				'selector' 		=> '{{WRAPPER}} ul.meta',
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
					'{{WRAPPER}} .oew-carousel .oew-carousel-entry-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'excerpt_typo',
				'selector' 		=> '{{WRAPPER}} .oew-carousel .oew-carousel-entry-excerpt',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' 		=> __( 'Button', 'ocean-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' 		=> __( 'Color', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .readmore-btn a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' 		=> __( 'Color: Hover', 'ocean-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .oew-carousel .readmore-btn a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} .oew-carousel .readmore-btn a',
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

		$args = array(
	        'post_type'         => 'post',
	        'posts_per_page'    => $settings['count'],
	        'order'             => $settings['order'],
	        'orderby'           => $settings['orderby'],
			'no_found_rows' 	=> true,
			'tax_query' 		=> array(
				'relation' 		=> 'AND',
			),
	    );

	    // Include/Exclude categories
	    $include = $settings['include_categories'];
	    $exclude = $settings['exclude_categories'];

	    // Include category
		if (  ! empty( $include ) ) {

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

		$counter = 0;

		//Output posts
		if ( $oew_query->have_posts() ) :

			// Vars
			$title   	= $settings['title'];
			$meta    	= $settings['meta'];
			$excerpt 	= $settings['excerpt'];
			$readmore 	= $settings['readmore_text'];

			// Image size
			$img_size 		= $settings['image_size'];
			$img_size 		= $img_size ? $img_size : 'medium';

			// Data settings
			$arrows             = $settings['arrows'];
			$items              = $settings['items'];
			$tablet 			= $settings['tablet'];
			$mobile  			= $settings['mobile'];

			$carousel_settings = [
	            'arrows' 	=> ( 'true' === $settings['arrows'] ),
	            'items' 	=> $settings['items'],
	            'tablet' 	=> $settings['tablet'],
	            'mobile' 	=> $settings['mobile'],
	        ]; ?>

			<div class="oew-carousel oew-carousel-blog clr" data-settings='<?php echo wp_json_encode( $carousel_settings ); ?>'>
				<?php
				// Start loop
				while ( $oew_query->have_posts() ) : $oew_query->the_post();

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
						|| 'true' == $meta
						|| 'true' == $excerpt
					) { ?>

						<div class="oew-carousel-slide">
						
							<?php
							// Display thumbnail if enabled and defined
							if ( has_post_thumbnail() ) { ?>

								<div class="oew-carousel-entry-media clr">

									<a href="<?php echo $post->permalink; ?>" title="<?php the_title(); ?>" class="oew-carousel-entry-img">

										<?php
										// Display post thumbnail
										the_post_thumbnail( $img_size, array(
											'alt'		=> get_the_title(),
											'itemprop' 	=> 'image',
										) ); ?>

									</a>

								</div><!-- .oew-carousel-entry-media -->

							<?php } ?>

							<?php
							// Open details element if the title or excerpt are true
							if ( 'true' == $title
								|| 'true' == $meta
								|| 'true' == $excerpt
							) { ?>

								<div class="oew-carousel-entry-details clr">

									<?php
									// Display title if $title is true and there is a post title
									if ( 'true' == $title ) { ?>

										<h2 class="oew-carousel-entry-title entry-title">
											<a href="<?php echo $post->permalink; ?>" title="<?php the_title(); ?>"><?php echo $post->title; ?></a>
										</h2>

									<?php } ?>

									<?php
									// Display meta
									if ( 'true' == $meta ) { ?>

										<ul class="meta">

											<?php if ( 'true' == $settings['author'] ) { ?>
												<li class="meta-author" itemprop="name"><i class="icon-user"></i><?php echo the_author_posts_link(); ?></li>
											<?php } ?>

											<?php if ( 'true' == $settings['date'] ) { ?>
												<li class="meta-date" itemprop="datePublished" pubdate><i class="icon-clock"></i><?php echo get_the_date(); ?></li>
											<?php } ?>

											<?php if ( 'true' == $settings['cat'] ) { ?>
												<li class="meta-cat"><i class="icon-folder"></i><?php the_category( ' / ', get_the_ID() ); ?></li>
											<?php } ?>

											<?php if ( 'true' == $settings['comments'] && comments_open() && ! post_password_required() ) { ?>
												<li class="meta-comments"><i class="icon-bubble"></i><?php comments_popup_link( esc_html__( '0 Comments', 'ocean-elementor-widgets' ), esc_html__( '1 Comment',  'ocean-elementor-widgets' ), esc_html__( '% Comments', 'ocean-elementor-widgets' ), 'comments-link' ); ?></li>
											<?php } ?>

										</ul>

									<?php } ?>

									<?php
									// Display excerpt if $excerpt is true
									if ( 'true' == $excerpt ) { ?>

										<div class="oew-carousel-entry-excerpt clr">
											<?php oew_excerpt( $settings['excerpt_length'] ); ?>
										</div><!-- .oew-carousel-entry-excerpt -->
										
									<?php } ?>

									<?php
									// Display read more
									if ( '' != $readmore ) { ?>

										<div class="oew-carousel-entry-readmore readmore-btn clr">
											<a href="<?php echo $post->permalink; ?>"><?php echo $readmore; ?></a>
										</div><!-- .oew-carousel-entry-excerpt -->
										
									<?php } ?>

								</div><!-- .oew-carousel-entry-details -->

							<?php } ?>

						</div>

					<?php } ?>

					<?php $counter++; ?>

				<?php
				// End entry loop
				endwhile; ?>

			</div><!-- .oew-carousel -->

			<?php
			// Reset the post data to prevent conflicts with WP globals
			wp_reset_postdata(); ?>

		<?php
		// If no posts are found display message
		else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'ocean-elementor-widgets' ); ?></p>

		<?php
		// End post check
		endif; ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new OEW_Widget_Blog_Carousel() );