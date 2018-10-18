<?php
/**
 * Astra Addon Customizer
 *
 * @package Astra Addon
 * @since 1.6.0
 */

if ( ! class_exists( 'Astra_Addon_Thrive_Compatibility' ) ) :

	/**
	 * Astra Addon Page Builder Compatibility base class
	 *
	 * @since 1.6.0
	 */
	class Astra_Addon_Thrive_Compatibility extends Astra_Addon_Page_Builder_Compatibility {

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

			$tve_content = apply_filters( 'the_content', $current_post->post_content );

			if ( isset( $_REQUEST[ TVE_EDITOR_FLAG ] ) ) {
				$tve_content = str_replace( 'id="tve_editor"', '', $tve_content );
			}

			echo $tve_content;

			wp_reset_postdata();
		}

		/**
		 * Load styles and scripts.
		 *
		 * @param int $post_id Post id.
		 *
		 * @since 1.6.0
		 */
		public function enqueue_scripts( $post_id ) {

			if ( tve_get_post_meta( $post_id, 'thrive_icon_pack' ) && ! wp_style_is( 'thrive_icon_pack', 'enqueued' ) ) {
				TCB_Icon_Manager::enqueue_icon_pack();
			}

			tve_enqueue_extra_resources( $post_id );
			tve_enqueue_style_family( $post_id );
			tve_enqueue_custom_fonts( $post_id, true );
			tve_load_custom_css( $post_id );

			add_filter( 'tcb_enqueue_resources', '__return_true' );
			tve_frontend_enqueue_scripts();
			remove_filter( 'tcb_enqueue_resources', '__return_true' );

		}

	}

endif;
