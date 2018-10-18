<?php
/**
 * Astra Addon Customizer
 *
 * @package Astra Addon
 * @since 1.6.0
 */

if ( ! class_exists( 'Astra_Addon_Divi_Compatibility' ) ) :

	/**
	 * Astra Addon Page Builder Compatibility base class
	 *
	 * @since 1.6.0
	 */
	class Astra_Addon_Divi_Compatibility extends Astra_Addon_Page_Builder_Compatibility {

		/**
		 * Instance
		 *
		 * @since 1.6.0
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.6.0
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Render content for post.
		 *
		 * @param int $post_id Post id.
		 *
		 * @since 1.6.0
		 */
		public function render_content( $post_id ) {

			$current_post = get_post( $post_id, OBJECT );

			global $post;
			$post = $current_post;

			$current_post->post_content = self::add_divi_wrap( $current_post->post_content );

			echo apply_filters( 'the_content', $current_post->post_content );

			wp_reset_postdata();
		}

		/**
		 * Adds Divi wrapper container to post content.
		 *
		 * @since 1.3.3
		 *
		 * @param string $content Post content.
		 * @return string         Post content.
		 */
		public static function add_divi_wrap( $content ) {

			$outer_class   = apply_filters( 'et_builder_outer_content_class', array( 'et_builder_outer_content' ) );
			$outer_classes = implode( ' ', $outer_class );

			$outer_id = apply_filters( 'et_builder_outer_content_id', 'et_builder_outer_content' );

			$inner_class   = apply_filters( 'et_builder_inner_content_class', array( 'et_builder_inner_content' ) );
			$inner_classes = implode( ' ', $inner_class );

			$content = sprintf(
				'<div class="%2$s" id="%4$s">
					<div class="%3$s">
						%1$s
					</div>
				</div>',
				$content,
				esc_attr( $outer_classes ),
				esc_attr( $inner_classes ),
				esc_attr( $outer_id )
			);

			return $content;
		}
	}

endif;
