<?php
namespace ElementorExtras\Modules\Posts\Skins;

use ElementorExtras\Base\Extras_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/posts-extra/section_layout/before_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( Extras_Widget $widget ) {
		$this->parent 	= $widget;

		$this->register_layout_content_controls();
	}

	public function register_layout_content_controls() {

		$this->add_control(
			'widget_helpers',
			[
				'label' 		=> __( 'Editor Helper', 'elementor-extras' ),
				'description'	=> __( 'Shows labels overlaid on posts to help your easily identify each post area', 'elementor-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default'		=> '',
				'return_value' 	=> 'on',
				'prefix_class'	=> 'ee-posts-helpers-',
			]
		);

		$this->add_responsive_control(
			'grid_columns_spacing',
			[
				'label' 			=> __( 'Columns Spacing', 'elementor-extras' ),
				'type' 				=> Controls_Manager::SLIDER,
				'default'			=> [ 'size' => 24, ],
				'tablet_default'	=> [ 'size' => 12, ],
				'mobile_default'	=> [ 'size' => 0, ],
				'size_units' 		=> [ 'px' ],
				'range' 			=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'grid_rows_spacing',
			[
				'label' 			=> __( 'Rows Spacing', 'elementor-extras' ),
				'type' 				=> Controls_Manager::SLIDER,
				'size_units' 		=> [ 'px' ],
				'default'			=> [ 'size' => 24, ],
				'tablet_default'	=> [ 'size' => 12, ],
				'mobile_default'	=> [ 'size' => 0, ],
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ee-post' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'layout_align',
			[
				'label' 		=> __( 'Align', 'elementor-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'default' 		=> 'stretch',
				'options' 		=> [
					'top' 			=> [
						'title' 	=> __( 'Top', 'elementor-extras' ),
						'icon' 		=> 'eicon-v-align-top',
					],
					'middle' 		=> [
						'title' 	=> __( 'Middle', 'elementor-extras' ),
						'icon' 		=> 'eicon-v-align-middle',
					],
					'bottom' 		=> [
						'title' 	=> __( 'Bottom', 'elementor-extras' ),
						'icon' 		=> 'eicon-v-align-bottom',
					],
					'stretch' 		=> [
						'title' 	=> __( 'Stretch', 'elementor-extras' ),
						'icon' 		=> 'eicon-v-align-stretch',
					],
				],
				'prefix_class' 	=> 'ee-grid-align--',
			]
		);
	}

	public function render() {

		$this->parent->render();

		$this->parent->query_posts();

		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		if ( 'yes' === $this->parent->get_settings('sticky_posts') ) {
			// while( $wp_query->have_posts() )
		}

		// Add filters
		add_filter( 'excerpt_more', [ $this, 'custom_excerpt_more' ], 99 );
		add_filter( 'excerpt_length', [ $this, 'custom_excerpt_length' ], 99 );
		add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );

		$this->before_loop();
		$this->render_loop_start();
		$this->render_sizer();

		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();

			$index = $wp_query->current_post + 1;

			$this->render_post( $index );
		}

		$this->render_loop_end();
		$this->after_loop();

		wp_reset_postdata();

		$this->render_scripts();

		// Remove filters
		remove_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
		remove_filter( 'excerpt_length', [ $this, 'custom_excerpt_length' ], 99 );
		remove_filter( 'excerpt_more', [ $this, 'custom_excerpt_more' ], 99 );
	}

	/**
	 * Applies the custom excerpt length
	 *
	 * @since 1.6.0
	 */
	public function custom_excerpt_length() {
		return $this->parent->get_settings( 'post_excerpt_length' );
	}

	/**
	 * Removes the auto more link from the excerpt
	 *
	 * @since 1.6.0
	 */
	public function custom_excerpt_more( $more ) {
		return '&hellip;';
	}

	protected function render_loop_start() {

		$this->parent->add_render_attribute( 'metas-separator', 'class', 'ee-post__meta__separator' );
		$this->parent->add_render_attribute( 'terms-separator', 'class', 'ee-post__terms__separator' );

		$this->parent->add_render_attribute( 'loop', [
			'class' => [
				'ee-grid',
				'ee-loop',
			],
		] );

		if ( '' !== $this->parent->get_settings( 'layout' ) ) {
			$this->parent->add_render_attribute( 'loop', 'class', 'ee-grid--' . $this->parent->get_settings( 'classic_layout' ) );
		}

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'loop' ); ?>>
		<?php
	}

	protected function render_sizer() {
		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'sizer', 'class', [
			'ee-grid__item--sizer',
			'ee-u-1/' . $settings[ 'columns' ],
			'ee-u-1/' . $settings[ 'columns_tablet' ] . '@desktop',
			'ee-u-1/' . $settings[ 'columns_mobile' ] . '@mobile',
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'sizer' ); ?>></div><?php
	}

	protected function render_post( $index ) {

		$settings = $this->parent->get_settings();

		// Transfer to portfolio widget
		// if ( ( $index + 1 ) % 3 === 0 ) {
		// 	$this->parent->add_render_attribute( 'grid-item' . get_the_ID(), 'class', 'is--wide' );
		// }

		$this->render_post_start();

			if ( ! in_array( $settings[ 'post_media_position' ], array( 'left', 'right' ) ) ) {
				$this->render_vertical_post();		
			} else if ( 'yes' === $settings['post_media'] && in_array( $settings['columns'], array( 1, 2 ) ) ) {
				$this->render_horizontal_post();
			} else {
				$this->render_vertical_post();
			}
		$this->render_post_end();
	}

	protected function render_vertical_post() {
		$this->render_post_header();
		$this->render_post_media();
		$this->render_post_body();
		$this->render_post_footer();
	}

	protected function render_horizontal_post() {
		$this->render_post_media();

		?><div class="ee-post__content"><?php
			$this->render_post_header();
			$this->render_post_body();
			$this->render_post_footer();
		?></div><?php
	}

	protected function render_post_start() {
		global $post;

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'grid-item' . get_the_ID(), [
			'class'	=> [
				'ee-grid__item',
				'ee-loop__item',
				'ee-u-1/' . $settings[ 'columns' ],
				'ee-u-1/' . $settings[ 'columns_tablet' ] . '@desktop',
				'ee-u-1/' . $settings[ 'columns_mobile' ] . '@mobile',
			],
		] );

		$this->before_grid_item();

		$post_classes = [ 'ee-post' ];

		if ( 'yes' === $settings['post_media'] && in_array( $settings['columns'], array( 1, 2 ) ) && in_array( $settings[ 'post_media_position' ], array( 'left', 'right' ) ) ) {
			$post_classes[] = 'ee-post--horizontal';
			$post_classes[] = 'ee-post--horizontal__' . $settings[ 'post_media_position' ];
		}

		if ( is_sticky( $post->ID ) ) {
			$post_classes[] = 'sticky';
		}

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'grid-item' . get_the_ID() ); ?>>
			<article <?php post_class( $post_classes ); ?>>
		<?php
	}

	protected function render_post_header() {

		$area = 'header';

		if ( $this->parent->is_empty_area( $area ) )
			return;

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'post-header-' . get_the_ID(), 'class', [
			'ee-post__header',
			'ee-post__area',
		] );

		$this->parent->add_helper_render_attribute( 'post-header-' . get_the_ID(), 'Header' );

		?><div <?php echo $this->parent->get_render_attribute_string( 'post-header-' . get_the_ID() ); ?>><?php
			$this->render_post_parts( $area );
		?></div><!-- .ee-post__header --><?php

	}

	protected function render_post_media() {

		$area = 'media';
		$media_tag = 'div';
		$settings = $this->parent->get_settings();

		if ( ! has_post_thumbnail() || 'yes' !== $settings['post_media'] ) {
			return;
		}

		$this->parent->add_render_attribute( 'post-media-' . get_the_ID(), 'class', [
			'ee-media',
			'ee-post__media',
		] );

		$this->parent->add_helper_render_attribute( 'post-media-' . get_the_ID(), 'Media' );

		if ( 'yes' === $settings['post_media_link'] ) {
			$media_tag = 'a';
			$this->parent->add_render_attribute( 'post-media-' . get_the_ID(), 'href', get_permalink() );
		}

		if ( ! $this->parent->is_empty_area( $area ) ) {
			$this->parent->add_render_attribute( 'post-media-' . get_the_ID(), 'class', 'ee-post__media--content' );
			$this->parent->add_render_attribute( 'post-media-content-' . get_the_ID(), 'class', [
				'ee-media__content',
				'ee-post__media__content',
				'ee-post__area',
			] );
		}

		?><<?php echo $media_tag; ?> <?php echo $this->parent->get_render_attribute_string( 'post-media-' . get_the_ID() ); ?>><?php

			$this->render_post_media_thumbnail();

			$this->render_post_media_overlay();

		if ( ! $this->parent->is_empty_area( $area ) ) {

			?><div <?php echo $this->parent->get_render_attribute_string( 'post-media-content-' . get_the_ID() ); ?>><?php
				$this->render_post_parts( $area );
			?></div><!-- .ee-post__media__content --><?php
		}

		?></<?php echo $media_tag; ?>><!-- .ee-post__media --><?php
	}

	protected function render_post_body() {

		$area = 'body';

		if ( $this->parent->is_empty_area( $area ) )
			return;

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'post-body-' . get_the_ID(), 'class', [
			'ee-post__body',
			'ee-post__area',
		] );

		$this->parent->add_helper_render_attribute( 'post-body-' . get_the_ID(), 'Body' );

		?><div <?php echo $this->parent->get_render_attribute_string( 'post-body-' . get_the_ID() ); ?>><?php
			$this->render_post_parts( $area );
		?></div><!-- .ee-post__body --><?php
	}

	protected function render_post_footer() {

		$area = 'footer';

		if ( $this->parent->is_empty_area( $area ) )
			return;

		$settings = $this->parent->get_settings();

		$this->parent->add_render_attribute( 'post-footer-' . get_the_ID(), 'class', [
			'ee-post__footer',
			'ee-post__area',
		] );

		$this->parent->add_helper_render_attribute( 'post-footer-' . get_the_ID(), 'Footer' );

		?><div <?php echo $this->parent->get_render_attribute_string( 'post-footer-' . get_the_ID() ); ?>><?php
			$this->render_post_parts( $area );
		?></div><!-- .ee-post__footer --><?php
	}

	protected function render_post_parts( $area ) {

		$_ordered_parts = $this->parent->get_ordered_post_parts( $this->parent->_post_parts );

		foreach ( $_ordered_parts as $part => $index ) {
			call_user_func( array( $this, 'render_post_' . $part ), $area );
		}
	}

	protected function render_post_metas( $area ) {

		// Render any metas in an area
		if ( $this->parent->metas_in_area( $area ) || $this->parent->is_in_area( 'post_avatar_position', $area ) ) {

			$this->parent->add_render_attribute( 'post-metas-' . $area . '-' . get_the_ID(), 'class', 'ee-post__metas' );

			$this->parent->add_helper_render_attribute( 'post-metas-' . $area . '-' . get_the_ID(), 'Metas' );

			$this->parent->add_render_attribute( 'post-metas-list-' . $area . '-' . get_the_ID(), 'class', 'ee-post__metas__list' );

			if ( '' !== $this->parent->get_settings( 'metas_display' ) ) {
				$this->parent->add_render_attribute( 'post-metas-list-' . $area . '-' . get_the_ID(), 'class', 'display--' . $this->parent->get_settings( 'metas_display' ) );
			}

			if ( $this->parent->is_in_area( 'post_avatar_position', $area ) ) {
				$this->parent->add_render_attribute( 'post-metas-' . $area . '-' . get_the_ID(), 'class', 'ee-post__metas--has-avatar' );
			}

			if ( $this->parent->metas_in_area( $area ) ) {
				$this->parent->add_render_attribute( 'post-metas-' . $area . '-' . get_the_ID(), 'class', 'ee-post__metas--has-metas' );
			}

			?><div <?php echo $this->parent->get_render_attribute_string( 'post-metas-' . $area . '-' . get_the_ID() ); ?>><?php

				$this->render_post_avatar( $area );

				if ( $this->parent->metas_in_area( $area ) ) {

					?><ul <?php echo $this->parent->get_render_attribute_string( 'post-metas-list-' . $area . '-' . get_the_ID() ); ?>><?php

						$_ordered_parts = $this->parent->get_ordered_post_parts( $this->parent->_meta_parts );

						foreach ( $_ordered_parts as $part => $index ) {
							call_user_func( array( $this, 'render_post_' . $part ), $area );
						}

					?></ul><?php

				}

		?></div><?php
		}
	}

	protected function render_post_media_thumbnail() {

		$settings = $this->parent->get_settings();

		if ( 'yes' !== $settings['post_media'] ) {
			return;
		}

		$settings[ 'post_media_thumbnail_size' ] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail = Group_Control_Image_Size::get_attachment_image_html( $settings, 'post_media_thumbnail_size' );

		if ( empty( $thumbnail ) ) {
			return;
		}

		$this->parent->add_render_attribute( 'post-thumbnail' . get_the_ID(), 'class', [
			'ee-post__media__thumbnail',
			'ee-media__thumbnail',
		] );
		
		?>

		<div <?php echo $this->parent->get_render_attribute_string( 'post-thumbnail' . get_the_ID() ); ?>>
			<?php echo $thumbnail; ?>
		</div>

		<?php
	}

	protected function render_post_media_overlay() {
		$this->parent->add_render_attribute( 'post-overlay' . get_the_ID(), 'class', [
			'ee-post__media__overlay',
			'ee-media__overlay',
		] );

		?><div <?php echo $this->parent->get_render_attribute_string( 'post-overlay' . get_the_ID() ); ?>></div><?php
	}

	protected function render_post_terms( $area = 'header' ) {
		if ( ! $this->parent->is_in_area( 'post_terms_position', $area ) )
			return;

		$settings 	= $this->parent->get_settings();
		$terms 		= $this->parent->get_terms();
		$term_count = $settings['post_terms_count'];

		if ( ! $terms || $term_count === 0 )
			return;

		$count 			= 0;
		$terms_tag 		= 'span';
		$terms_linked 	= 'yes' === $this->parent->get_settings( 'post_terms_link' );
		$media_linked 	= 'yes' === $this->parent->get_settings( 'post_media_link' );
		$in_media 		= $this->parent->is_in_area( 'post_terms_position', 'media' );

		$this->parent->add_render_attribute( 'post-terms-' . get_the_ID(), 'class', 'ee-post__terms' );
		$this->parent->add_helper_render_attribute( 'post-terms-' . get_the_ID(), 'Terms' );

		?>
		<ul <?php echo $this->parent->get_render_attribute_string( 'post-terms-'. get_the_ID() ); ?>>

			<?php if ( $settings['post_terms_prefix'] ) { ?>
			<li class="ee-post__terms__term ee-post__terms__term--prefix">
				<?php echo $settings['post_terms_prefix']; ?>
			</li>
			<?php } ?>

			<?php foreach( $terms as $term ) {
				if ( $term_count === $count ) break;

				$term_render_key = 'term-item-' . get_the_ID() . ' ' . $term->term_id;
				$term_link_render_key = 'term-link-' . get_the_ID() . ' ' . $term->term_id;

				$this->parent->add_render_attribute( $term_render_key, 'class', [
					'ee-post__terms__term',
					'ee-term',
					'ee-term--' . $term->slug,
				] );

				$this->parent->add_render_attribute( $term_link_render_key, 'class', [
					'ee-post__terms__link',
					'ee-term__link',
				] );

				if ( ( $in_media && ! $media_linked && $terms_linked ) || ( ! $in_media && $terms_linked ) ) {
					$terms_tag = 'a';
					$this->parent->add_render_attribute( $term_link_render_key, 'href', get_term_link( $term ) );
				}
			?>

				<li <?php echo $this->parent->get_render_attribute_string( $term_render_key ); ?>>
					<<?php echo $terms_tag; ?> <?php echo $this->parent->get_render_attribute_string( $term_link_render_key ); ?>>
						<?php echo $term->name; ?>
					</<?php echo $terms_tag; ?>><?php echo $this->render_terms_separator(); ?>
				</li>

			<?php $count++; } ?>
		</ul>
		<?php
	}

	protected function render_post_title( $area = 'body' ) {
		if ( ! $this->parent->is_in_area( 'post_title_position', $area ) )
			return;

		$title_tag = 'div';
		$heading_tag = $this->parent->get_settings( 'post_title_element' );
		$in_media = $this->parent->is_in_area( 'post_title_position', 'media' );
		$title_linked = 'yes' === $this->parent->get_settings( 'post_title_link' );
		$media_linked = 'yes' === $this->parent->get_settings( 'post_media_link' );

		if ( ( $in_media && ! $media_linked && $title_linked ) || ( ! $in_media && $title_linked ) ) {
			$title_tag = 'a';
			$this->parent->add_render_attribute( 'post-title-' . get_the_ID(), 'href', get_permalink() );
		}

		$this->parent->add_render_attribute( 'post-title-' . get_the_ID(), 'class', 'ee-post__title' );
		$this->parent->add_helper_render_attribute( 'post-title-' . get_the_ID(), 'Title' );

		$this->parent->add_render_attribute( 'post-title-heading-' . get_the_ID(), 'class', 'ee-post__title__heading' );

		?>
			<<?php echo $title_tag; ?> <?php echo $this->parent->get_render_attribute_string( 'post-title-' . get_the_ID() ); ?>>
				<<?php echo $heading_tag; ?> <?php echo $this->parent->get_render_attribute_string( 'post-title-heading-' . get_the_ID() ); ?>><?php the_title(); ?></<?php echo $heading_tag; ?>>
			</<?php echo $title_tag; ?>>
		<?php
	}

	protected function render_metas_separator() {
		if ( '' === $this->parent->get_settings( 'post_metas_separator' ) )
			return;

		$separator = $this->parent->get_settings( 'post_metas_separator' );

		?><span <?php echo $this->parent->get_render_attribute_string( 'metas-separator' ); ?>><?php echo $separator; ?></span><?php
	}

	protected function render_terms_separator() {
		if ( '' === $this->parent->get_settings( 'post_terms_separator' ) )
			return;

		$separator = $this->parent->get_settings( 'post_terms_separator' );

		?><span <?php echo $this->parent->get_render_attribute_string( 'terms-separator' ); ?>><?php echo $separator; ?></span><?php
	}

	protected function render_post_author( $area = 'footer' ) {
		if ( ! $this->parent->is_in_area( 'post_author_position', $area ) )
			return;

		$has_link = ! $this->parent->is_in_area( 'post_author_position', 'media' ) && 'yes' === $this->parent->get_settings( 'post_author_link' );

		?>
		<li class="ee-post__meta ee-post__meta--author">
			<?php if ( $has_link ) : ?><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php endif; ?>
				<?php echo $this->parent->get_settings('post_author_prefix'); ?> <?php the_author(); ?><?php echo $this->render_metas_separator(); ?>
			<?php if ( $has_link ) : ?></a><?php endif; ?>
		</li>
		<?php
	}

	protected function render_post_avatar( $area = 'footer' ) {
		if ( ! $this->parent->is_in_area( 'post_avatar_position', $area ) )
			return;

		$has_link = ! $this->parent->is_in_area( 'post_avatar_position', 'media' ) && 'yes' === $this->parent->get_settings( 'post_avatar_link' );

		?><div class="ee-post__metas__avatar ee-post__meta--avatar">
			<?php if ( $has_link ) : ?><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php endif; ?>
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 256, '', get_the_author_meta( 'display_name' ), [ 'class' => 'ee-post__metas__avatar__image' ] ); ?>
			<?php if ( $has_link ) : ?></a><?php endif; ?>
		</div><?php
	}

	protected function render_post_date( $area = 'footer' ) {
		if ( ! $this->parent->is_in_area( 'post_date_position', $area ) )
			return;

		?>
		<li class="ee-post__meta ee-post__meta--date">
			<?php echo $this->parent->get_settings('post_date_prefix'); ?>  <?php echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' ); ?>
			<?php $this->render_post_time(); ?><?php echo $this->render_metas_separator(); ?>
		</li>
		<?php
	}

	protected function render_post_time() {
		if ( 'yes' !== $this->parent->get_settings( 'post_date_time' ) )
			return;

		?>
		<?php echo $this->parent->get_settings('post_date_time_prefix'); ?>  <?php the_time(); ?>
		<?php
	}

	protected function render_post_comments( $area = 'body' ) {
		if ( ! $this->parent->is_in_area( 'post_comments_position', $area ) )
			return;

		?>
		<li class="ee-post__meta ee-post__meta--comments">
			<?php comments_number(); ?><?php echo $this->render_metas_separator(); ?>
		</li>
		<?php
	}

	protected function render_post_excerpt( $area = 'body' ) {
		if ( ! $this->parent->is_in_area( 'post_excerpt_position', $area ) )
			return;

		$this->parent->add_render_attribute( 'post-excerpt-' . get_the_ID(), 'class', 'ee-post__excerpt' );
		$this->parent->add_helper_render_attribute( 'post-excerpt-' . get_the_ID(), 'Excerpt' );

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'post-excerpt-' . get_the_ID() ); ?>>
			<?php echo get_the_excerpt(); ?>
			<?php $this->render_post_read_more(); ?>
		</div>
		<?php
	}

	protected function render_post_read_more() {

		if ( 'yes' !== $this->parent->get_settings( 'post_read_more' ) || '' === $this->parent->get_settings( 'post_read_more_text' ) )
			return;

		$this->parent->add_render_attribute( 'post-read-more-' . get_the_ID(), [
			'class' => 'ee-post__read-more',
			'href' 	=> get_permalink( get_the_ID() ),
		] );
		
		$this->parent->add_helper_render_attribute( 'post-read-more-' . get_the_ID(), 'Read More' );

		?>
		<a <?php echo $this->parent->get_render_attribute_string( 'post-read-more-' . get_the_ID() ); ?>>
			<?php echo $this->parent->get_settings( 'post_read_more_text' ); ?>
		</a>
		<?php
	}

	protected function render_loop_end() {
		?></div><!-- .ee-loop --><?php
	}

	protected function render_post_end() {
		?>
			</article><!-- .ee-post -->
		</div><!-- .ee-loop__item -->
		<?php

		$this->after_grid_item();
	}

	public function before_loop() {}

	public function before_grid_item() {}

	public function after_grid_item() {}

	protected function after_loop() {}

	public function render_pagination() {}

	public function render_load_status() {}
	public function render_load_button() {}

	public function render_scripts() {}

}