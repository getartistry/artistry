<?php
/**
 * Admin page : dashboard
 *
 * @package SIB_Page_Scenarios
 */

if ( ! class_exists( 'SIB_Page_Scenarios' ) ) {
	/**
	 * Page class that handles backend page <i>scenario ( for admin )</i> with form generation and processing
	 *
	 * @package SIB_Page_Scenarios
	 */
	class SIB_Page_Scenarios {

		/**
		 * Page slug
		 */
		const PAGE_ID = 'SIB_Page_Scenarios';

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
			$this->page_hook = add_submenu_page( SIB_Page_Home::PAGE_ID, __( 'Workflows', 'sib_lang' ), __( 'Workflows', 'sib_lang' ), 'manage_options', self::PAGE_ID, array( &$this, 'generate' ) );
			add_action( 'load-' . $this->page_hook, array( &$this, 'init' ) );
			add_action( 'admin_print_scripts-' . $this->page_hook, array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_print_styles-' . $this->page_hook, array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Init Process
		 */
		function Init() {

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
			<div id="wrap" class="box-border-box container-fluid">
				<h2><img id="logo-img" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/logo.png' ); ?>"></h2>
				<div id="wrap-left" class="box-border-box col-md-9 ">
					<?php
					if ( SIB_Manager::is_done_validation() ) {
						$this->generate_main_page();
					} else {
						$this->generate_welcome_page();
					}
					?>
				</div>
				<div id="wrap-right-side" class="box-border-box col-md-3">
					<?php

					SIB_Page_Home::generate_side_bar();
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
			$ma_link = 'https://automation.sendinblue.com/scenarios/?create_new_scenario';
			$ma_exist_link = 'https://automation.sendinblue.com/scenarios/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link';
			$ma_logs_link = 'https://automation.sendinblue.com/log/scenarios/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link';

			?>
			<h2 style="text-align: center;"><?php esc_attr_e( 'Create your workflow', 'sib_lang' ); ?></h2>
			<div class="sib-scenarios">
				<div class="col-md-4 ">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Custom workflow','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/custom.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Mix and match triggers, conditions, and actions to create a custom workflow.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Welcome Message','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/welcome_message.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send a welcome email after a subscriber joins your list.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Anniversary Date','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/anniversary_of_date.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send an annual email or series of emails based on a special event or birthday.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Page Visit','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/page_visit.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send emails after a contact visits a specific page on your website.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Website Event','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/website_event.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send emails after a contact performs a specific action on your website.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Product Purchase','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/product_purchase.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send emails when a product is purchased on your website.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Marketing Activity','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/newsletter_activity.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send emails based on whether contacts open or click on an email campaign.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a class="sib-scenario-link" href="<?php echo esc_url( $ma_link ); ?>" target="_blank">
						<div class="thumbnail thumbanail_focus">
							<h4 class="text-center"><?php esc_attr_e( 'Transactional Activity','sib_lang' ); ?></h4>
							<p class="text-center">
								<img class="" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/transactional_activity.png' ); ?>" height="70px">
							</p>
							<p class="text-center"><?php esc_attr_e( 'Send an email based whether contacts open or click on transactional emails.', 'sib_lang' ); ?></p>
						</div>
					</a>
				</div>
			</div>

			<div class="col-md-6" style="text-align: right;padding: 0 50px;">
				<a href="<?php echo esc_url( $ma_exist_link ); ?>" class="button" target="_blank" style="width: 200px;text-align: center;"><?php esc_attr_e( 'View my existing workflows', 'sib_lang' ); ?></a>
				</div>
			<div class="col-md-6" style="text-align: left;padding: 0 50px;">
				<a href="<?php echo esc_url( $ma_logs_link ); ?>" class="button" target="_blank" style="width: 200px;text-align: center;"><?php esc_attr_e( 'View logs', 'sib_lang' ); ?></a>
			</div>

			<?php

		}

		/** Generate welcome page */
		function generate_welcome_page() {
			?>
			<img src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/background/lists.png' ); ?>" style="width: 100%;">
		<?php
			SIB_Page_Home::print_disable_popup();
		}

	}
}