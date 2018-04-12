<?php
/**
 * Google Analytics Insights actions
 *
 * @package Convert Pro
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'Cp_V2_Ga_Insights' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class Cp_V2_Ga_Insights {

		/**
		 * The class instance.
		 *
		 * @since 1.0.0
		 * @var string $instance
		 */
		private static $instance;

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
			add_action( 'cp_before_design_list', array( $this, 'render_insights_view' ) );
			add_action( 'cp_after_insights_header', array( $this, 'add_insights_header' ) );

			add_filter( 'cp_design_list_columns', array( $this, 'render_design_table_cols' ) );

			add_filter( 'cp_design_list_rows', array( $this, 'render_design_table_rows' ) );

			add_action( 'cp_get_impressions_row_value', array( $this, 'render_style_impressions' ), 10 );

			add_action( 'cp_get_conversions_row_value', array( $this, 'render_style_conversions' ), 10 );

			add_action( 'cp_get_rate_row_value', array( $this, 'render_style_rate' ), 10 );
		}

		/**
		 * Function Name: render_style_impressions.
		 * Function Description: render style impressions.
		 *
		 * @param int $style Design ID.
		 */
		function render_style_impressions( $style ) {

			$analytics_data = get_option( 'cp_ga_analytics_data' );
			$impressions    = 0;
			$ga_data        = ( isset( $analytics_data[ $style->post_name ] ) ) ? $analytics_data[ $style->post_name ] : '';

			if ( isset( $ga_data ) && ! empty( $ga_data ) ) {
				foreach ( $ga_data as $date => $style_data ) {
					$impressions = $impressions + (int) $style_data['impressions'];
				}
			}

			echo '<span>' . $impressions . '</span>';
		}

		/**
		 * Function Name: render_style_conversions.
		 * Function Description: render style conversions.
		 *
		 * @param int $style Design ID.
		 */
		function render_style_conversions( $style ) {

			$analytics_data = get_option( 'cp_ga_analytics_data' );
			$conversions    = 0;
			$ga_data        = ( isset( $analytics_data[ $style->post_name ] ) ) ? $analytics_data[ $style->post_name ] : '';

			if ( isset( $ga_data ) && ! empty( $ga_data ) ) {
				foreach ( $ga_data as $date => $style_data ) {
					$conversions = $conversions + (int) $style_data['conversions'];
				}
			}

			echo '<span>' . $conversions . '</span>';
		}

		/**
		 * Function Name: render_style_rate.
		 * Function Description: render style rate.
		 *
		 * @param int $style Design ID.
		 */
		function render_style_rate( $style ) {

			$analytics_data  = get_option( 'cp_ga_analytics_data' );
			$conversion_rate = 'NA';
			$impressions     = 0;
			$conversions     = 0;
			$ga_data         = ( isset( $analytics_data[ $style->post_name ] ) ) ? $analytics_data[ $style->post_name ] : '';

			if ( isset( $ga_data ) && ! empty( $ga_data ) ) {
				foreach ( $ga_data as $date => $style_data ) {
					$impressions = $impressions + (int) $style_data['impressions'];
					$conversions = $conversions + (int) $style_data['conversions'];
				}
			}

			$cp_moule_type = get_post_meta( $style->ID, 'cp_module_type', true );
			$cp_moule_type = ucwords( str_replace( '_', ' ', $cp_moule_type ) );

			if ( 0 !== $conversions && 0 !== $impressions ) {
				$conversion_rate = $conversions / $impressions;
				$conversion_rate = round( $conversion_rate, 2 ) * 100 . '%';
			}

			echo '<span>' . $conversion_rate . '</span>';
		}

		/**
		 * Function Name: render_design_table_rows.
		 * Function Description: render design table rows.
		 *
		 * @param string $rows rows.
		 */
		function render_design_table_rows( $rows ) {
			array_unshift( $rows, 'impressions', 'conversions', 'rate' );
			return $rows;
		}

		/**
		 * Function Name: render_design_table_cols.
		 * Function Description: render design table cols.
		 *
		 * @param string $cols cols.
		 */
		function render_design_table_cols( $cols ) {

			$custom_cols = array(
				'impressions' => array(
					'label' => __( 'Impressions', 'convertpro-addon' ),
				),
				'conversions' => array(
					'label' => __( 'Conversions', 'convertpro-addon' ),
				),
				'rate'        => array(
					'label' => __( 'Conversion Rate', 'convertpro-addon' ),
				),
			);

			$cols = $custom_cols + $cols;
			return $cols;
		}

		/**
		 * Function Name: render_insights_view.
		 * Function Description: render insights view.
		 */
		function render_insights_view() {
			require_once( CP_ADDON_DIR . 'addons/analytics/views/ga-insights-view.php' );
		}

		/**
		 * Function Name: add_insights_header.
		 * Function Description: add insights header.
		 */
		function add_insights_header() {

			$updated_on  = get_option( 'cp_ga_analytics_updated_on' );
			$credentials = get_option( 'cp_ga_credentials' );
			?>
				<div class="cp-flex-center">
					<h2 class="cp-sub-head cp-insight-head"><?php _e( "Today's Summary", 'convertpro-addon' ); ?>
						<?php
						if ( false != $updated_on ) {
						?>
							<span class="cp-ga-notice"><?php _e( 'Last updated ', 'convertpro-addon' ); ?><?php echo $this->get_last_updated_string( $updated_on ) . ' ago'; ?></span>
						<?php } ?>
					</h2>

					<?php if ( false !== $credentials ) { ?>
						<button class="cp-sm-btn cp-button-style" id="cp-resync-ga"><i class="dashicons-update dashicons"></i><span><?php _e( 'Update Analytics data', 'convertpro-addon' ); ?></button>
					<?php } ?>
				</div>
			<?php
		}

		/**
		 * Function Name: get_last_updated_string.
		 * Function Description: get last updated string.
		 *
		 * @param string $last_updated string parameter.
		 */
		function get_last_updated_string( $last_updated ) {

			$seconds_ago = ( time() - strtotime( $last_updated ) );

			if ( $seconds_ago >= 31536000 ) {
				$val   = intval( $seconds_ago / 31536000 );
				$sufix = ( 1 != $val ) ? __( ' years', 'convertpro-addon' ) : __( ' year', 'convertpro-addon' );
			} elseif ( $seconds_ago >= 2419200 ) {
				$val   = intval( $seconds_ago / 2419200 );
				$sufix = ( 1 != $val ) ? __( ' months', 'convertpro-addon' ) : __( ' month', 'convertpro-addon' );
			} elseif ( $seconds_ago >= 86400 ) {
				$val   = intval( $seconds_ago / 86400 );
				$sufix = ( 1 != $val ) ? __( ' days', 'convertpro-addon' ) : __( ' day', 'convertpro-addon' );
			} elseif ( $seconds_ago >= 3600 ) {
				$val   = intval( $seconds_ago / 3600 );
				$sufix = ( 1 != $val ) ? __( ' hours', 'convertpro-addon' ) : __( ' hour', 'convertpro-addon' );
			} elseif ( $seconds_ago >= 60 ) {
				$val   = intval( $seconds_ago / 60 );
				$sufix = ( 1 != $val ) ? __( ' minutes', 'convertpro-addon' ) : __( ' minute', 'convertpro-addon' );
			} else {
				$val   = __( 'less than a minute', 'convertpro-addon' );
				$sufix = '';
			}
			$since = $val . $sufix;
			return $since;
		}
	}

	$ga_insights = Cp_V2_Ga_Insights::get_instance();
}
