<?php
/**
 * Astra Addon Customizer
 *
 * @package Astra Addon
 * @since 1.6.0
 */

if ( ! class_exists( 'Astra_Addon_Brizy_Compatibility' ) ) :

	/**
	 * Astra Addon Page Builder Compatibility base class
	 *
	 * @since 1.6.0
	 */
	class Astra_Addon_Brizy_Compatibility extends Astra_Addon_Page_Builder_Compatibility {

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
		 *  Constructor
		 */
		public function __construct() {
		}

		/**
		 * Render content for post.
		 *
		 * @param int $post_id Post id.
		 *
		 * @since 1.6.0
		 */
		public function render_content( $post_id ) {

			$post = Brizy_Editor_Post::get( $post_id );

			if ( $post && $post->uses_editor() ) {

				$html = new Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
				echo $html->get_body(); // Executing only the HTML from the Brizy page builder.
			}
		}

		/**
		 * Load styles and scripts.
		 *
		 * @param int $post_id Post id.
		 *
		 * @since 1.6.0
		 */
		public function enqueue_scripts( $post_id ) {

			$post    = Brizy_Editor_Post::get( $post_id );
			$project = Brizy_Editor_Project::get();
			$main    = new Brizy_Public_Main( $project, $post );

			// Add page CSS.
			add_filter( 'body_class', array( $main, 'body_class_frontend' ) );
			add_action( 'wp_enqueue_scripts', array( $main, '_action_enqueue_preview_assets' ), 9999 );

			add_action(
				'wp_head',
				function() use ( $post ) {
					$html = new Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
					echo $html->get_head();
				}
			);

			if ( $post && $post->uses_editor() ) {

				// Add page admin edit menu.
				add_action(
					'admin_bar_menu',
					function( $wp_admin_bar ) use ( $post ) {
						$wp_post_id = $post->get_wp_post()->ID;
						$args       = array(
							'id'    => 'brizy_Edit_page_' . $wp_post_id . '_link',
							/* translators: %s is the page title */
							'title' => sprintf( __( 'Edit %s with Brizy', 'astra-addon' ), get_the_title( $wp_post_id ) ),
							'href'  => $post->edit_url(),
							'meta'  => array(),
						);

						if ( $wp_admin_bar->get_node( 'brizy_Edit_page_link' ) ) {
							$args['parent'] = 'brizy_Edit_page_link';
						}

						$wp_admin_bar->add_node( $args );

					},
					1000
				);
			}
		}
	}

endif;
