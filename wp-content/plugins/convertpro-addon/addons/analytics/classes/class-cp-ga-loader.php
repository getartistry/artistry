<?php
/**
 * Google Analytics settings
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cp_GA_Loader' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class Cp_GA_Loader {

		/**
		 * The class instance.
		 *
		 * @since 1.0.0
		 * @var string $instance
		 */
		private static $instance;

		/**
		 * The analytics data.
		 *
		 * @since 1.0.0
		 * @var string $analytics_data
		 */
		private $analytics_data;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {

			add_action( 'cp_after_email-template_nav_menu', array( $this, 'add_navigation_menu' ) );
			add_action( 'cp_after_email_template_content', array( $this, 'render_settings_content' ) );
			add_action( 'wp_head', array( $this, 'ga_events_header' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_ga_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_front_scripts' ), 99 );

			add_action( 'cp_after_insight_actions', array( $this, 'render_analytics_icon' ) );

			add_action( 'wp_footer', array( $this, 'load_inline_scripts' ), 99 );
			$this->load_files();
			add_action( 'cpro_ab_test_actions', array( $this, 'cpro_ab_test_actions' ) );
			$this->analytics_data = get_option( 'cp_ga_analytics_data' );

		}

		/**
		 * A/B Test actions.
		 *
		 * @param string $slug Design slug.
		 */
		public function cpro_ab_test_actions( $slug ) {
			$ga_data = get_option( 'cp_ga_analytics_data' );
			if ( false != $ga_data ) {
			?>
			<span class="has-tip cp-ab-test-analytics" data-position="bottom" title="<?php _e( 'Analytics', 'convertpro-addon' ); ?>" data-ab-test="<?php echo $slug; ?>"><i class="dashicons dashicons-chart-bar"></i></span>
			<?php
			}
		}

		/**
		 * Load Files.
		 */
		public function load_files() {

			require_once( CP_ADDON_DIR . 'addons/analytics/classes/class-cp-v2-ga.php' );
			require_once( CP_ADDON_DIR . 'addons/analytics/classes/class-cp-v2-ga-insights.php' );
		}

		/**
		 * Render analytics icon.
		 *
		 * @param int $style Design ID.
		 */
		public function render_analytics_icon( $style ) {

			$analytics_data = get_option( 'cp_ga_analytics_data' );
			if ( false !== $analytics_data ) {
			?>
			<span class="has-tip cp-style-analytics" data-style="<?php echo $style->post_name; ?>" data-position="bottom" title="<?php _e( 'Analytics', 'convertpro-addon' ); ?>">
				<i class="dashicons dashicons-chart-bar"></i>
			</span>
			<?php
			}
		}

		/**
		 * Add navigation menu.
		 */
		public function add_navigation_menu() {

			echo '<a href="#analytics" class="cp-settings-nav"><span class="cp-gen-set-icon"><i class="dashicons dashicons-chart-bar"></i></span>' . __( 'Analytics', 'convertpro-addon' ) . '</a>';
		}

		/**
		 * Render settings content.
		 */
		public function render_settings_content() {
			require_once( CP_ADDON_DIR . 'addons/analytics/views/ga-settings-view.php' );
		}

		/**
		 * Calculate analytics data.
		 */
		public function calculate_analytics_data() {

			$todays_total_impressions = 0;
			$todays_total_conversions = 0;
			$todays_conversion_rate   = 'NA';

			$credentials = get_option( 'cp_ga_credentials' );
			$timezone    = isset( $credentials['timezone'] ) ? $credentials['timezone'] : '';

			if ( '' !== $timezone ) {
				date_default_timezone_set( $timezone );
			}

			$analytics_data = $this->analytics_data;
			$credentials    = get_option( 'cp_ga_credentials' );

			$ga_inst = new CP_V2_GA();
			if ( false === $analytics_data && false != $credentials ) {

				$analytics_data = $ga_inst->get_analytics_data();
				$ga_data        = array();

				if ( is_array( $analytics_data ) && ! empty( $analytics_data ) ) {
					foreach ( $analytics_data as $key => $value ) {
						$date               = date( 'Y-m-d', strtotime( $value[0] ) );
						$action             = $value[1];
						$style_slug         = $value[2];
						$unique_impressions = $value[3];

						if ( isset( $ga_data[ $style_slug ] ) ) {

							if ( isset( $ga_data[ $style_slug ][ $date ] ) ) {
								$impressions = isset( $ga_data[ $style_slug ][ $date ]['impressions'] ) ? $ga_data[ $style_slug ][ $date ]['impressions'] : 0;

													$impressions = (int) $impressions + $unique_impressions;

								$ga_data[ $style_slug ][ $date ]['impressions'] = $impressions;
							}
						} else {

							$data = array(
								'impressions' => (int) $unique_impressions,
								'conversions' => 0,
							);

							$ga_data[ $style_slug ][ $date ] = $data;
						}
					}

					update_option( 'cp_ga_analytics_data', $ga_data );
					$analytics_data = $ga_data;
				}
			}

			if ( is_array( $analytics_data ) && ! empty( $analytics_data ) ) {
				foreach ( $analytics_data as $slug => $ga_data ) {
					$p = get_posts(
						array(
							'name'        => $slug,
							'post_type'   => CP_CUSTOM_POST_TYPE,
							'post_status' => 'publish',
							'numberposts' => 1,
						)
					);

					if ( ! empty( $p ) ) {

						foreach ( $ga_data as $date => $style_data ) {

							if ( strtotime( $date ) == strtotime( date( 'Y-m-d' ) ) ) {
								$impressions = $style_data['impressions'];
								$conversions = $style_data['conversions'];

								$todays_total_conversions = $todays_total_conversions + (int) $conversions;
								$todays_total_impressions = $todays_total_impressions + (int) $impressions;
							}
						}
					}
				}
			}

			if ( 0 !== $todays_total_conversions && 0 !== $todays_total_impressions ) {
				$todays_conversion_rate = $todays_total_conversions / $todays_total_impressions;
				$todays_conversion_rate = number_format( (float) $todays_conversion_rate, 2, '.', '' ) * 100 . '%';
			}

			return array(
				'total_conversions'     => $todays_total_conversions,
				'total_impressions'     => $todays_total_impressions,
				'total_conversion_rate' => $todays_conversion_rate,
			);

		}

		/**
		 * Google Analytics events header.
		 */
		public function ga_events_header() {

			$id              = esc_attr( get_option( 'cp-ga-identifier' ) );
			$domain          = $_SERVER['SERVER_NAME'];
			$cp_ga_auth_type = esc_attr( get_option( 'cp-ga-auth-type' ) );
			$additional_atts = apply_filters( 'cpro_ga_manual_script_atts', 'type="text/javascript"' );

			$tracking_script_path = apply_filters( 'cpro_analytics_script_path', 'https://www.google-analytics.com/analytics.js' );

			$trackingcode  = "  ga( 'create', '" . $id . "', 'auto' );\n";
			$trackingcode .= "  ga( 'send', 'pageview' );";

			if ( 'manual' == $cp_ga_auth_type ) {
			?>
				<!-- This is google analytics tracking code added by Convert Pro -->
				<script <?php echo $additional_atts; ?> >
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','<?php echo $tracking_script_path; ?>','ga');
<?php echo $trackingcode; ?>
				</script>
				<!-- End of google analytics tracking code added by Convert Pro -->
				<?php
			}
		}

		/**
		 * Google Analytics load scripts
		 *
		 * @param string $hook hook.
		 */
		public function load_ga_scripts( $hook ) {

			if ( false !== strpos( $hook, CP_PRO_SLUG ) ) {

				wp_enqueue_script( 'cp-gc-script', 'https://www.gstatic.com/charts/loader.js', false, CP_ADDON_VER, true );

				wp_enqueue_script( 'cp-ga-js-script', CP_ADDON_URL . 'addons/analytics/assets/js/cp-ga.js', array( 'cp-gc-script' ), CP_ADDON_VER, true );

				wp_enqueue_script( 'cp-analytics-script', CP_ADDON_URL . 'addons/analytics/assets/js/google-analytics.js', array( 'cp-gc-script' ), CP_ADDON_VER, true );

				$ga_inst   = new CP_V2_GA();
				$cp_ga_url = $ga_inst->generate_auth_url();

				wp_localize_script(
					'jquery',
					'cp_ga_object',
					array(
						/* translators: %s link */
						'no_ga_code'        => sprintf( __( 'You have not entered the access code! Please <a class="google-analytic-page-link" href="%s" target="_blank" rel="noopener">click here</a> to get an access code.', 'convertpro-addon' ), $cp_ga_url ),
						'ga_resync'         => __( 'Processing your request. This might take few minutes.', 'convertpro-addon' ),
						'ga_resync_done'    => __( 'Google Analytics data updated. Redirecting...', 'convertpro-addon' ),
						'authorizing'       => __( 'Authorizing...', 'convertpro-addon' ),
						'confirm_delete_ga' => __( 'Are you sure you want to delete Google Analytics Integration?', 'convertpro-addon' ),
						'ga_category_name'  => CPRO_BRANDING_NAME,
						'ga_event_name'     => CPRO_GA_EVENT_NAME,
					)
				);
			}
		}

		/**
		 * Google Analytics load scripts at frontend
		 */
		public function load_front_scripts() {

			$cp_ga_auth_type = get_option( 'cp-ga-auth-type' ) ? esc_attr( get_option( 'cp-ga-auth-type' ) ) : 'universal-ga';

			wp_localize_script(
				'cp-popup-script',
				'cp_ga_object',
				array(
					'ga_auth_type'     => $cp_ga_auth_type,
					'ga_category_name' => CPRO_BRANDING_NAME,
					'ga_event_name'    => CPRO_GA_EVENT_NAME,
				)
			);

			$dev_mode = get_option( 'cp_dev_mode' );

			if ( '1' == $dev_mode ) {

				wp_enqueue_script( 'cp-gc-script', CP_ADDON_URL . 'addons/analytics/assets/js/cp-ga-front.js', array( 'cp-popup-script' ), CP_ADDON_VER, true );
			}
		}

		/**
		 * Google Analytics load inline scripts.
		 */
		public function load_inline_scripts() {

			$dev_mode        = get_option( 'cp_dev_mode' );
			$additional_atts = apply_filters( 'cpro_ga_analytics_script_atts', 'type="text/javascript"' );

			if ( '1' != $dev_mode ) {
			?>
				<!-- This is minified version of cp-ga-front.js file located at convertpro-addon/analytics/assets/js/cp-ga-front.js if you want to make changes to this, edit cp-ga-front.js file, minify it and paste code here -->

				<script <?php echo $additional_atts; ?>>
				!function(e){var t="";e(window).on("cp_after_popup_open",function(e,t,n,o){var a=jQuery('.cp-popup-container[data-style="cp_style_'+o+'"]').data("styleslug");cpUpdateImpressions(a)}),cpUpdateImpressions=function(e){var t=cp_ga_object.ga_category_name;cpCreateGoogleAnalyticEvent(t,"impression",e)},cpIsModuleOnScreen=function(e){var t=jQuery(window),n={top:t.scrollTop(),left:t.scrollLeft()};n.right=n.left+t.width(),n.bottom=n.top+t.height();var o=e.offset();return o.right=o.left+e.outerWidth(),o.bottom=o.top+e.outerHeight(),!(n.right<o.left||n.left>o.right||n.bottom<o.top||n.top>o.bottom)},e(document).on("cp_after_form_submit",function(e,t,n,o){if(!0===n.success){var a=cp_ga_object.ga_category_name;cpCreateGoogleAnalyticEvent(a,"conversion",o)}}),cpCreateGoogleAnalyticEvent=function(e,n,o){void 0!==t&&("undefined"!=typeof ga?t=ga:"undefined"!=typeof _gaq?t=_gaq:"function"==typeof __gaTracker?t=__gaTracker:"function"==typeof gaplusu&&(t=gaplusu));var a=void 0!==cp_ga_object.ga_auth_type?cp_ga_object.ga_auth_type:"universal-ga";"undefined"!=typeof dataLayer&&"gtm-code"==a?dataLayer.push({event:cp_ga_object.ga_event_name,eventCategory:e,eventAction:n,eventLabel:o,eventValue:"1",nonInteraction:!0}):"universal-ga"!=a&&"manual"!=a||"function"!=typeof t||t("send","event",e,n,o,{nonInteraction:!0})},cp_track_inline_modules=function(){jQuery(".cp-popup-container.cp-module-before_after, .cp-popup-container.cp-module-inline, .cp-popup-container.cp-module-widget").each(function(){var e=jQuery(this);e.data("style").replace("cp_style_","");if(cpIsModuleOnScreen(e)&&!e.hasClass("cp-impression-counted")){var t=e.data("styleslug");cpUpdateImpressions(t),e.addClass("cp-impression-counted")}})},e(document).ready(function(){cp_track_inline_modules()}),e(document).scroll(function(e){cp_track_inline_modules()})}(jQuery);
				</script>
			<?php
			}
		}
	}

	$ga_loader = Cp_GA_Loader::get_instance();
}
