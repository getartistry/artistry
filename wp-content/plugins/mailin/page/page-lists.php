<?php
/**
 * Admin page : dashboard
 *
 * @package SIB_Page_Lists
 */

if ( ! class_exists( 'SIB_Page_Lists' ) ) {
	/**
	 * Page class that handles backend page <i>dashboard ( for admin )</i> with form generation and processing
	 *
	 * @package SIB_Page_Lists
	 */
	class SIB_Page_Lists {

		/**
		 * Page slug
		 */
		const PAGE_ID = 'sib_page_lists';

		/**
		 * Page hook
		 *
		 * @var string
		 */
		protected $page_hook;

		/**
		 * Page tabs
		 *
		 * @var mixed
		 */
		protected $tabs;

		/**
		 * Constructs new page object and adds entry to WordPress admin menu
		 */
		function __construct() {
			$this->page_hook = add_submenu_page( SIB_Page_Home::PAGE_ID, __( 'Lists', 'sib_lang' ), __( 'Lists', 'sib_lang' ), 'manage_options', self::PAGE_ID, array( &$this, 'generate' ) );
			add_action( 'load-' . $this->page_hook, array( &$this, 'init' ) );
			add_action( 'admin_print_scripts-' . $this->page_hook, array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_print_styles-' . $this->page_hook, array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Init Process
		 */
		function Init() {
			add_action( 'admin_notices', array( 'SIB_Manager', 'language_admin_notice' ) );
		}

		/**
		 * Enqueue scripts of plugin
		 */
		function enqueue_scripts() {
			wp_enqueue_script( 'sib-admin-js' );
			wp_enqueue_script( 'sib-bootstrap-js' );
			wp_localize_script(
				'sib-admin-js', 'ajax_sib_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Enqueue style sheets of plugin
		 */
		function enqueue_styles() {
			wp_enqueue_style( 'sib-admin-css' );
			wp_enqueue_style( 'sib-bootstrap-css' );
			wp_enqueue_style( 'sib-fontawesome-css' );
			wp_enqueue_style( 'thickbox' );
		}

		/** Generate page script */
		function generate() {
			?>
			<div id="wrap1" class="box-border-box container-fluid">
				<div id="main-content"  class="row">
					<?php
					if ( SIB_Manager::is_done_validation() ) {
						$this->generate_main_page();
					} else {
						$this->generate_welcome_page();
					}
					?>
				</div>
			</div>
			<style>
				#wpcontent {
					margin-left: 160px !important;
				}

				@media only screen and (max-width: 918px) {
					#wpcontent {
						margin-left: 40px !important;
					}
				}
			</style>
		<?php
		}

		/** Generate main page */
		function generate_main_page() {
			$access_token = SIB_API_Manager::update_access_token();
			$lang = substr( get_bloginfo( 'language' ),0,2 );
			if ( '' == $access_token ) {
				?>
				<p>Sorry Inconvenience. Please refresh</p>
				<?php
			} else {
				?>
				<iframe id="datamain"
						src="https://my.sendinblue.com/lists/index/access_token/<?php echo esc_attr( $access_token ); ?>/lang/<?php echo esc_attr( $lang ); ?>"
						width="100%" height="800" scrolling="yes"></iframe>
				<?php
			}
		}

		/** Generate welcome page */
		function generate_welcome_page() {
			?>
			<img src="<?php echo esc_attr( SIB_Manager::$plugin_url ) . '/img/background/lists.png'; ?>" style="width: 100%;">
		<?php
			SIB_Page_Home::print_disable_popup();
		}

	}
}
