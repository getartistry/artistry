<?php
/**
 * Blog Pro Markup
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Ext_Blog_Pro_Markup' ) ) {

	/**
	 * Blog Pro Markup Initial Setup
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Blog_Pro_Markup {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
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

			add_filter( 'body_class', array( $this, 'astra_ext_blog_pro_body_classes' ) );
			add_filter( 'post_class', array( $this, 'astra_post_class_blog_grid' ) );
			add_filter( 'astra_primary_class', array( $this, 'astra_primary_class_blog_grid' ) );
			add_filter( 'astra_blog_layout_class', array( $this, 'add_blog_layout_class' ) );
			add_action( 'astra_get_js_files', array( $this, 'add_scripts' ) );
			add_action( 'astra_get_css_files', array( $this, 'add_styles' ), 1 );
			add_action( 'wp_head', array( $this, 'blog_customization' ) );
			add_action( 'astra_pagination_infinite', array( $this, 'blog_customization' ) );
			add_filter( 'astra_blog_post_featured_image_after', array( $this, 'date_box' ), 10, 1 );
			add_action( 'astra_entry_after', array( $this, 'author_info_markup' ) );

			add_action( 'wp_ajax_astra_pagination_infinite', array( $this, 'astra_pagination_infinite' ) );    // for logged in user.
			add_action( 'wp_ajax_nopriv_astra_pagination_infinite', array( $this, 'astra_pagination_infinite' ) );    // if user not logged in.
			add_filter( 'astra_theme_js_localize', array( $this, 'blog_js_localize' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );

			// Blog Pagination.
			add_filter( 'astra_pagination_markup', array( $this, 'astra_blog_pagination' ) );

			add_filter( 'astra_meta_case_read-time', array( $this, 'reading_time_content' ), 10, 3 );

			add_action( 'init', array( $this, 'init_action' ) );
		}

		/**
		 * Infinite Posts Show on scroll
		 *
		 * @since 1.0
		 * @param array $localize   JS localize variables.
		 * @return array
		 */
		function blog_js_localize( $localize ) {

			global $wp_query;

			$pagination_enabled         = apply_filters( 'astra_pagination_enabled', true );
			$blog_masonry               = astra_get_option( 'blog-masonry' );
			$blog_pagination            = ( $pagination_enabled ) ? astra_get_option( 'blog-pagination' ) : '';
			$blog_infinite_scroll_event = astra_get_option( 'blog-infinite-scroll-event' );
			$blog_grid                  = astra_get_option( 'blog-grid' );
			$blog_grid_layout           = astra_get_option( 'blog-grid-layout' );
			$blog_layout                = astra_get_option( 'blog-layout' );
			$grid_layout                = ( 'blog-layout-1' == $blog_layout ) ? $blog_grid : $blog_grid_layout;

			$localize['query_vars']            = json_encode( $wp_query->query );
			$localize['edit_post_url']         = admin_url( 'post.php?post={{id}}&action=edit' );
			$localize['ajax_url']              = admin_url( 'admin-ajax.php' );
			$localize['infinite_count']        = 2;
			$localize['infinite_total']        = $wp_query->max_num_pages;
			$localize['pagination']            = $blog_pagination;
			$localize['infinite_scroll_event'] = $blog_infinite_scroll_event;
			$localize['infinite_nonce']        = wp_create_nonce( 'ast-load-more-nonce' );
			$localize['no_more_post_message']  = apply_filters( 'astra_blog_no_more_post_text', __( 'No more posts to show.', 'astra-addon' ) );
			$localize['grid_layout']           = $grid_layout;
			$localize['site_url']              = get_site_url();

			$localize['show_comments'] = __( 'Show Comments', 'astra-addon' );

			// If woocommerce page template.
			if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
				$localize['masonryEnabled'] = false;
			} else {
				$localize['masonryEnabled']        = $blog_masonry;
				$localize['blogMasonryBreakPoint'] = absint( apply_filters( 'astra_blog_masonry_break_point', 768 ) );
			}

			return $localize;
		}

		/**
		 * Astra Blog Pagination
		 *
		 * @since 1.0
		 * @param html $output Pagination markup.
		 * @return html
		 */
		function astra_blog_pagination( $output ) {

			global $wp_query;

			$pagination     = astra_get_option( 'blog-pagination' );
			$infinite_event = astra_get_option( 'blog-infinite-scroll-event' );
			$load_more_text = astra_get_option( 'blog-load-more-text' );

			if ( '' === $load_more_text ) {
				$load_more_text = __( 'Load More', 'astra-addon' );
			}

			if ( 'infinite' == $pagination ) {
				if ( $wp_query->max_num_pages > 1 ) {
					ob_start();
					?>
					<nav class="ast-pagination-infinite">
						<div class="ast-loader">
								<div class="ast-loader-1"></div>
								<div class="ast-loader-2"></div>
								<div class="ast-loader-3"></div>
						</div>
						<?php if ( 'click' == $infinite_event ) { ?>
							<span class="ast-load-more active">
								<?php echo apply_filters( 'astra_load_more_text', esc_html( $load_more_text ) ); ?>
							</span>
						<?php } ?>
					</nav>
					<?php
					$output = ob_get_clean();
				}
			}
			return $output;
		}

		/**
		 * Infinite Posts Show on scroll
		 */
		function astra_pagination_infinite() {

			check_ajax_referer( 'ast-load-more-nonce', 'nonce' );

			do_action( 'astra_pagination_infinite' );

			$query_vars                = json_decode( stripslashes( $_POST['query_vars'] ), true );
			$query_vars['paged']       = ( isset( $_POST['page_no'] ) ) ? stripslashes( $_POST['page_no'] ) : 1;
			$query_vars['post_status'] = 'publish';
			$posts                     = new WP_Query( $query_vars );

			if ( $posts->have_posts() ) {
				while ( $posts->have_posts() ) {
					$posts->the_post();
					get_template_part( 'template-parts/content', 'blog' );
				}
			}
			wp_reset_query();

			wp_die();
		}

		/**
		 * Function to get author info for default post only
		 */
		function author_info_markup() {

			if ( astra_get_option( 'ast-author-info' ) && is_singular( 'post' ) ) {
				astra_get_template( 'blog-pro/template/author-info.php' );
			}
		}

		/**
		 * Add 'Date Box' in featured section
		 *
		 * @since 1.0
		 *
		 * @param  string $output Post content.
		 * @return string content.
		 */
		function date_box( $output ) {

			$enable_date_box = astra_get_option( 'blog-date-box' );

			if ( $enable_date_box ) :

				$date_box_style = astra_get_option( 'blog-date-box-style' );

				$time_string = '<time class="entry-date published updated" datetime="%1$s"><span class="date-month">%2$s</span> <span class="date-day">%3$s</span> <span class="date-year">%4$s</span></time>';
				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
					$time_string = '<time class="entry-date published" datetime="%1$s"><span class="date-month">%2$s</span> <span class="date-day">%3$s</span> <span class="date-year">%4$s</span></time><time class="updated" datetime="%5$s">%6$s</time>';
				}

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date( 'M' ) ),
					esc_html( get_the_date( 'j' ) ),
					esc_html( get_the_date( 'Y' ) ),
					esc_attr( get_the_modified_date( 'c' ) ),
					esc_html( get_the_modified_date() )
				);

				$posted_on = sprintf(
					esc_html( '%s' ),
					$time_string
				);

				ob_start();
				?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" >
					<div class="ast-date-meta <?php echo $date_box_style; ?>">
						<span class="posted-on"><?php echo $posted_on; ?></span>
					</div>
				</a>
				<?php
				$posted_on_data = ob_get_clean();

				$output .= $posted_on_data;
			endif;

			return $output;
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Blog Layout Class Array.
		 * @return array
		 */
		function add_blog_layout_class( $classes ) {
			$display_date_box = astra_get_option( 'blog-date-box' );

			if ( ! $display_date_box ) {
				$classes[] = 'ast-no-date-box';
			}
			return $classes;
		}

		/**
		 * Blog Customization
		 */
		function blog_customization() {

			$blog_layout = astra_get_option( 'blog-layout' );

			if ( 'blog-layout-1' !== $blog_layout ) {
				remove_action( 'astra_entry_content_blog', 'astra_entry_content_blog_template' );
				add_action( 'astra_entry_content_blog', array( $this, 'blog_template' ) );
			}
		}

		/**
		 * Blog Template Markup
		 */
		function blog_template() {
			astra_get_template( 'blog-pro/template/' . esc_attr( astra_get_option( 'blog-layout' ) ) . '.php' );
		}

		/**
		 * Add Blog Grid Class
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function astra_primary_class_blog_grid( $classes ) {

			// Apply grid class to archive page.
			if ( ( is_home() ) || is_archive() || is_search() ) {

				$blog_grid        = astra_get_option( 'blog-grid' );
				$blog_grid_layout = astra_get_option( 'blog-grid-layout' );
				$blog_layout      = astra_get_option( 'blog-layout' );
				if ( 'blog-layout-1' == $blog_layout ) {
					$classes[] = 'ast-grid-' . esc_attr( $blog_grid );
				} else {
					$classes[] = 'ast-grid-' . esc_attr( $blog_grid_layout );
				}
				$classes = apply_filters( 'astra_primary_class_blog_grid', $classes );
			}

			return $classes;
		}

		/**
		 * Add Post Class Blog Grid
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function astra_post_class_blog_grid( $classes ) {

			$is_ajax_pagination = $this->is_ajax_pagination();

			if ( is_archive() || is_home() || is_search() || $is_ajax_pagination ) {

				global $wp_query;
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				$blog_grid        = astra_get_option( 'blog-grid' );
				$blog_grid_layout = astra_get_option( 'blog-grid-layout' );
				$blog_layout      = astra_get_option( 'blog-layout' );

				$first_post_full_width = astra_get_option( 'first-post-full-width' );
				$blog_masonry          = astra_get_option( 'blog-masonry' );

				$remove_featured_padding = astra_get_option( 'blog-featured-image-padding' );
				$blog_space_bet_posts    = astra_get_option( 'blog-space-bet-posts' );

				if ( $is_ajax_pagination ) {
					$classes[] = 'ast-col-sm-12';
					$classes[] = 'ast-article-post';
				}

				if ( 'blog-layout-1' == $blog_layout ) {

					if ( $remove_featured_padding ) {
						$classes[] = 'remove-featured-img-padding';
					}

					if ( $blog_grid > 1 && $first_post_full_width && ! $blog_masonry && 0 == $wp_query->current_post && 1 == $paged ) {

						// Feature Post.
						if ( 3 == $blog_grid ) {
							$classes[] = 'ast-col-md-8';
						} elseif ( 4 == $blog_grid ) {
							$classes[] = 'ast-col-md-6';
						} else {
							$classes[] = 'ast-col-md-12';
						}

						$classes[] = 'ast-featured-post';
					} else {
						$classes[] = 'ast-col-md-' . ( 12 / $blog_grid );
					}
				} else {

					if ( $blog_grid_layout > 1 && $first_post_full_width && ! $blog_masonry && 0 == $wp_query->current_post && 1 == $paged ) {
						// Feature Post.
						$classes[] = 'ast-col-md-12';
						$classes[] = 'ast-featured-post';
					} else {
						$classes[] = 'ast-col-md-' . ( 12 / $blog_grid_layout );
					}
				}

				if ( $blog_space_bet_posts ) {
					$classes[] = 'ast-separate-posts';
				}
			} elseif ( is_singular() ) {

				$blog_layout             = astra_get_option( 'blog-layout' );
				$remove_featured_padding = astra_get_option( 'single-featured-image-padding' );

				if ( 'blog-layout-1' == $blog_layout && $remove_featured_padding ) {
					$classes[] = 'remove-featured-img-padding';
				}
			}

			return $classes;
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function astra_ext_blog_pro_body_classes( $classes ) {

			global $wp_query;

			if ( is_archive() || is_home() || is_search() ) {

				$blog_layout      = astra_get_option( 'blog-layout' );
				$blog_masonry     = astra_get_option( 'blog-masonry' );
				$blog_grid        = astra_get_option( 'blog-grid' );
				$blog_grid_layout = astra_get_option( 'blog-grid-layout' );
				$blog_pagination  = astra_get_option( 'blog-pagination' );

				// Masonry layout for blog.
				if ( $blog_masonry && $wp_query->posts ) {
					$classes[] = 'blog-masonry';
				}

				// Blog layout.
				if ( 'blog-layout-1' == $blog_layout ) {
					$classes[] = 'ast-blog-grid-' . esc_attr( $blog_grid );
				} else {
					$classes[] = 'ast-blog-grid-' . esc_attr( $blog_grid_layout );
				}

				// Blog layout.
				$classes[] = 'ast-' . esc_attr( $blog_layout );

				if ( 'number' === $blog_pagination ) {

					$blog_pagination_style = astra_get_option( 'blog-pagination-style' );

					$classes[] = 'ast-pagination-' . esc_attr( $blog_pagination_style );
				}
			}

			return $classes;
		}

		/**
		 * Add style.
		 *
		 * @since 1.0
		 *
		 * @return void.
		 */
		function add_styles() {

			$author_info = astra_get_option( 'ast-author-info' );
			/*** Start Path Logic */

			/* Define Variables */
			$uri  = ASTRA_EXT_BLOG_PRO_URI . 'assets/css/';
			$path = ASTRA_EXT_BLOG_PRO_DIR . 'assets/css/';
			$rtl  = '';

			if ( is_rtl() ) {
				$rtl = '-rtl';
			}

			/* Directory and Extension */
			$file_prefix = $rtl . '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = $rtl;
				$dir_name    = 'unminified';
			}

			$css_uri = $uri . $dir_name . '/';
			$css_dir = $path . $dir_name . '/';

			if ( defined( 'ASTRA_THEME_HTTP2' ) && ASTRA_THEME_HTTP2 ) {
				$gen_path = $css_uri;
			} else {
				$gen_path = $css_dir;
			}

			/*** End Path Logic */

			/* Add style.css */
			Astra_Minify::add_css( $gen_path . 'style' . $file_prefix . '.css' );

			if ( $author_info ) {
				Astra_Minify::add_css( $gen_path . 'post-author' . $file_prefix . '.css' );
			}

			/* Blog Layouts */
			$blog_layout = astra_get_option( 'blog-layout' );
			Astra_Minify::add_css( $gen_path . $blog_layout . $file_prefix . '.css' );
		}

		/**
		 * Add scripts.
		 *
		 * @since 1.0
		 *
		 * @return void.
		 */
		function add_scripts() {

			/*** Start Path Logic */

			/* Define Variables */
			$uri  = ASTRA_EXT_BLOG_PRO_URI . 'assets/js/';
			$path = ASTRA_EXT_BLOG_PRO_DIR . 'assets/js/';

			/* Directory and Extension */
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = '';
				$dir_name    = 'unminified';
			}

			$js_uri = $uri . $dir_name . '/';
			$js_dir = $path . $dir_name . '/';

			if ( defined( 'ASTRA_THEME_HTTP2' ) && ASTRA_THEME_HTTP2 ) {
				$gen_path = $js_uri;
			} else {
				$gen_path = $js_dir;
			}

			/*** End Path Logic */

			$blog_layout        = astra_get_option( 'blog-layout' );
			$blog_grid          = astra_get_option( 'blog-grid' );
			$blog_grid_layout   = astra_get_option( 'blog-grid-layout' );
			$astra_blog_masonry = astra_get_option( 'blog-masonry' );

			if ( ( 'blog-layout-1' == $blog_layout && 1 != $blog_grid ) || ( 'blog-layout-1' != $blog_layout && 1 != $blog_grid_layout ) ) {
				// Enqueue scripts.
				if ( $astra_blog_masonry ) {
					Astra_Minify::add_dependent_js( 'jquery' );
					Astra_Minify::add_dependent_js( 'jquery-masonry' );

					Astra_Minify::add_js( $gen_path . 'ast-ext-blog-pro' . $file_prefix . '.js' );
				}
			}
		}

		/**
		 * Frontend scripts.
		 *
		 * @since 1.0
		 *
		 * @return void.
		 */
		function enqueue_frontend_scripts() {

			/* Directory and Extension */
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = '';
				$dir_name    = 'unminified';
			}

			$js_gen_path  = ASTRA_EXT_BLOG_PRO_URI . 'assets/js/' . $dir_name . '/';
			$css_gen_path = ASTRA_EXT_BLOG_PRO_URI . 'assets/css/' . $dir_name . '/';

			if ( astra_get_option( 'ast-auto-prev-post' ) && is_singular() ) {

				if ( SCRIPT_DEBUG ) {
					wp_enqueue_script( 'astra-scrollspy', $js_gen_path . 'scrollspy' . $file_prefix . '.js', array( 'jquery' ), 1.0, true );
					wp_enqueue_script( 'astra-history', $js_gen_path . 'jquery-history' . $file_prefix . '.js', array( 'jquery' ), 1.0, true );
					wp_enqueue_script( 'astra-single-infinite', $js_gen_path . 'single-infinite' . $file_prefix . '.js', array( 'astra-scrollspy' ), 1.0, true );
				} else {
					wp_enqueue_script( 'astra-single-infinite', $js_gen_path . 'single-autopost-infinite.min.js', array( 'jquery' ), 1.0, true );
				}
			}

			if ( is_home() || is_archive() || is_search() ) {
				wp_enqueue_script( 'astra-pagination-infinite', $js_gen_path . 'pagination-infinite' . $file_prefix . '.js', array( 'jquery', 'astra-addon-js' ), 1.0, true );
			}

		}

		/**
		 * Calculate reading time.
		 *
		 * @since 1.0
		 *
		 * @param  int $post_id Post content.
		 * @return int read time.
		 */
		function calculate_reading_time( $post_id ) {

			$post_content       = get_post_field( 'post_content', $post_id );
			$stripped_content   = strip_shortcodes( $post_content );
			$strip_tags_content = strip_tags( $stripped_content );
			$word_count         = str_word_count( $strip_tags_content );
			$reading_time       = ceil( $word_count / 220 );

			return $reading_time;
		}

		/**
		 * Reading Time Meta.
		 *
		 * @since 1.0
		 *
		 * @param  string $content Post content.
		 * @param  string $loop_count Post meta loop count.
		 * @param  string $separator Separator text.
		 * @return string content
		 */
		function reading_time_content( $content, $loop_count, $separator ) {

			$post_id      = get_the_ID();
			$read_time    = $this->calculate_reading_time( $post_id );
			$time_unit    = __( ' min ', 'astra-addon' );
			$time_postfix = __( ' read ', 'astra-addon' );

			$content .= ( 1 != $loop_count && '' != $content ) ? ' ' . $separator . ' ' : '';
			$content .= '<span class="ast-reading-time">' . $read_time . $time_unit . $time_postfix . '</span>';

			return $content;
		}

		/**
		 * Init action.
		 *
		 * @return void
		 */
		function init_action() {

			$this->single_post_add_endpoint();

			if ( 'excerpt' === astra_get_option( 'blog-post-content' ) ) {
				// Excerpt Filter.
				add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ), 999 );

				add_filter( 'astra_post_read_more', array( $this, 'read_more_text' ) );
				add_filter( 'astra_post_read_more_class', array( $this, 'read_more_class' ) );
			}
		}

		/**
		 * Single post rewrite endpoint.
		 *
		 * @return void
		 */
		function single_post_add_endpoint() {

			if ( astra_get_option( 'ast-auto-prev-post' ) || is_customize_preview() ) {

				add_rewrite_endpoint( 'partial-prev', EP_PERMALINK );

				add_action( 'template_redirect', array( $this, 'single_post_template_redirect' ) );
			}
		}

		/**
		 * Single post template redirect.
		 *
		 * @return void
		 */
		function single_post_template_redirect() {
			global $wp_query;

			// if this is not a request for partial or a singular object then bail.
			if ( ( isset( $wp_query->query_vars['partial-prev'] ) || isset( $_GET['partial-prev'] ) ) && is_singular() ) {
				// include custom template.
				include ASTRA_EXT_BLOG_PRO_DIR . '/template/content-partial.php';

				exit;
			}

			return;
		}

		/**
		 * Excerpt count.
		 *
		 * @param int $length default count of words.
		 * @return int count of words
		 */
		function custom_excerpt_length( $length ) {

			$excerpt_length = astra_get_option( 'blog-excerpt-count' );

			if ( '' != $excerpt_length ) {
				$length = $excerpt_length;
			}

			return $length;
		}

		/**
		 * Read more text.
		 *
		 * @param string $text default read more text.
		 * @return string read more text
		 */
		function read_more_text( $text ) {

			$read_more = astra_get_option( 'blog-read-more-text' );

			if ( '' != $read_more ) {
				$text = $read_more;
			}

			return $text;
		}

		/**
		 * Read more class.
		 *
		 * @param array $class default classes.
		 * @return array classes
		 */
		function read_more_class( $class ) {

			$read_more_button = astra_get_option( 'blog-read-more-as-button' );

			if ( $read_more_button ) {
				$class[] = 'ast-button';
			}

			return $class;
		}

		/**
		 * Check if ajax pagination is calling.
		 *
		 * @return boolean classes
		 */
		function is_ajax_pagination() {

			$pagination = false;

			if ( isset( $_POST['astra_infinite'] ) && wp_doing_ajax() && check_ajax_referer( 'ast-load-more-nonce', 'nonce', false ) ) {
				$pagination = true;
			}

			return $pagination;
		}
	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Blog_Pro_Markup::get_instance();
