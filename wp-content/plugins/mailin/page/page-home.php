<?php
/**
 * Admin page : dashboard
 *
 * @package SIB_Page_Home
 */

if ( ! class_exists( 'SIB_Page_Home' ) ) {
	/**
	 * Page class that handles backend page <i>dashboard ( for admin )</i> with form generation and processing
	 *
	 * @package SIB_Page_Home
	 */
	class SIB_Page_Home {

		/**
		 * Page slug
		 */
		const PAGE_ID = 'sib_page_home';

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
			add_menu_page( __( 'SendinBlue', 'sib_lang' ), __( 'SendinBlue', 'sib_lang' ), 'manage_options', self::PAGE_ID, array( &$this, 'generate' ), SIB_Manager::$plugin_url . '/img/favicon.ico' );
			$this->page_hook = add_submenu_page( self::PAGE_ID, __( 'Home', 'sib_lang' ), __( 'Home', 'sib_lang' ), 'manage_options', self::PAGE_ID, array( &$this, 'generate' ) );
			add_action( 'load-' . $this->page_hook, array( &$this, 'init' ) );
			add_action( 'admin_print_scripts-' . $this->page_hook, array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_print_styles-' . $this->page_hook, array( $this, 'enqueue_styles' ) );
		}

		/**
		 * Init Process
		 */
		function Init() {
			if ( ( isset( $_GET['sib_action'] ) ) && ( 'logout' === $_GET['sib_action'] ) ) {
				$this->logout();
			}
		}

		/**
		 * Enqueue scripts of plugin
		 */
		function enqueue_scripts() {
			wp_enqueue_script( 'sib-admin-js' );
			wp_enqueue_script( 'sib-bootstrap-js' );
			wp_enqueue_script( 'sib-chosen-js' );
			wp_localize_script(
				'sib-admin-js', 'ajax_sib_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'ajax_sib_admin_nonce' ),
				)
			);
		}

		/**
		 * Enqueue style sheets of plugin
		 */
		function enqueue_styles() {
			wp_enqueue_style( 'sib-admin-css' );
			wp_enqueue_style( 'sib-bootstrap-css' );
			wp_enqueue_style( 'sib-chosen-css' );
			wp_enqueue_style( 'sib-fontawesome-css' );
		}

		/** Generate page script */
		function generate() {
			?>
			<div id="wrap" class="box-border-box container-fluid">
				<h2><img id="logo-img" src="<?php echo esc_url( SIB_Manager::$plugin_url . '/img/logo.png' ); ?>"></h2>
				<div id="wrap-left" class="box-border-box col-md-9">
				<?php
				if ( SIB_Manager::is_done_validation() == true ) {
					$this->generate_main_content();
				} else {
					$this->generate_welcome_content();
				}
				?>
				</div>
				<div id="wrap-right-side" class="box-border-box  col-md-3">
					<?php
					self::generate_side_bar();
					?>
				</div>
			</div>
			<?php
		}

		/** Generate welcome page before validation */
		function generate_welcome_content() {
		?>

			<div id="main-content" class="sib-content">
				<input type="hidden" id="cur_refer_url" value="<?php echo esc_url( add_query_arg( array( 'page' => 'sib_page_home' ), admin_url( 'admin.php' ) ) ); ?> ">
				<div class="panel panel-default row small-content">
					<div class="page-header">
						<span style="color: #777777;"><?php esc_attr_e( 'Step', 'sib_lang' ); ?> 1&nbsp;|&nbsp;</span><strong><?php esc_attr_e( 'Create a SendinBlue Account', 'sib_lang' ); ?></strong>
					</div>
					<div class="panel-body">
						<div class="col-md-9 row">
							<p><?php esc_attr_e( 'By creating a free SendinBlue account, you will be able to send confirmation emails and:', 'sib_lang' ); ?></p>
							<ul class="sib-home-feature">
								<li><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Collect your contacts and upload your lists', 'sib_lang' ); ?></li>
								<li><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Use SendinBlue SMTP to send your transactional emails', 'sib_lang' ); ?></li>
								<li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Email marketing builders', 'sib_lang' ); ?></li>
								<li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Create and schedule your email marketing campaigns', 'sib_lang' ); ?></li>
								<li class="home-read-more-content"><span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Try all of', 'sib_lang' ); ?>&nbsp;<a href="https://www.sendinblue.com/features/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><?php esc_attr_e( 'SendinBlue\'s features', 'sib_lang' ); ?></a></li>
							</ul>
							<a href="https://www.sendinblue.com/users/signup?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" class="btn btn-primary" target="_blank" style="margin-top: 10px;"><?php esc_attr_e( 'Create an account', 'sib_lang' ); ?></a>
						</div>
					</div>
				</div>
				<div class="panel panel-default row small-content">
					<div class="page-header">
						<span style="color: #777777;"><?php esc_attr_e( 'Step', 'sib_lang' ); ?> 2&nbsp;|&nbsp;</span><strong><?php esc_attr_e( 'Activate your account with your API key', 'sib_lang' ); ?></strong>
					</div>
					<div class="panel-body">
						<div class="col-md-9 row">
							<div id="success-alert" class="alert alert-success" role="alert" style="display: none;"><?php esc_attr_e( 'You successfully activate your account.', 'sib_lang' ); ?></div>
							<input type="hidden" id="general_error" value="<?php esc_attr_e( 'Please input correct information.', 'sib_lang' ); ?>">
							<input type="hidden" id="curl_no_exist_error" value="<?php esc_attr_e( 'Please install curl on site to use sendinblue plugin.', 'sib_lang' ); ?>">
							<input type="hidden" id="curl_error" value="<?php esc_attr_e( 'Curl error.', 'sib_lang' ); ?>">
							<div id="failure-alert" class="alert alert-danger" role="alert" style="display: none;"><?php esc_attr_e( 'Please input correct information.', 'sib_lang' ); ?></div>
							<p>
								<?php esc_attr_e( 'Once you have created a SendinBlue account, activate this plugin to send all of your transactional emails via SendinBlue SMTP. SendinBlue optimizes email delivery to ensure emails reach the inbox.', 'sib_lang' ); ?><br>
								<?php esc_attr_e( 'To activate your plugin, enter your API Access key.', 'sib_lang' ); ?><br>
							</p>
							<p>
								<a href="https://my.sendinblue.com/advanced/apikey/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Get your API key from your account', 'sib_lang' ); ?></a>
							</p>
							<p>
								<div class="col-md-7 row">
									<p class="col-md-12 row"><input id="sib_access_key" type="text" class="col-md-10" style="margin-top: 10px;" placeholder="<?php esc_attr_e( 'Access Key', 'sib_lang' ); ?>"></p>
									<p class="col-md-12 row"><button type="button" id="sib_validate_btn" class="col-md-4 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php esc_attr_e( 'Login', 'sib_lang' ); ?></button></p>
								</div>
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php
		}

		/** Generate main home page after validation */
		function generate_main_content() {
			$total_subscribers = SIB_API_Manager::get_totalusers();

			// get campaigns.
			$campaign_stat = SIB_API_Manager::get_campaign_stats();

			// display account info.
			$account_settings = SIB_API_Manager::get_account_info();
			$account_email = isset( $account_settings['account_email'] ) ? $account_settings['account_email'] : '';
			$account_user_name = isset( $account_settings['account_user_name'] ) ? $account_settings['account_user_name'] : '';
			$account_data = isset( $account_settings['account_data'] ) ? $account_settings['account_data'] : '';
			// check smtp available.
			$smtp_status = SIB_API_Manager::get_smtp_status();

			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			// for upgrade to 2.6.0 from old version.
			if ( ! isset( $home_settings['activate_ma'] ) ) {
				$home_settings['activate_ma'] = 'no';
			}
			// set default sender info.
			$senders = SIB_API_Manager::get_sender_lists();
			if ( ! isset( $home_settings['sender'] ) && SIB_Manager::is_done_validation() && is_array( $senders ) ) {
				$home_settings['sender'] = $senders[0]['id'];
				$home_settings['from_name'] = $senders[0]['from_name'];
				$home_settings['from_email'] = $senders[0]['from_email'];
				update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );
			}

			// Users Sync part.
			$currentUsers = count_users();
			$isSynced = get_option( 'sib_sync_users', '0' );
			$isEnableSync = '0';
			if ( $isSynced != $currentUsers ) {
				$isEnableSync = '1';
				/* translators: %s: total users */
				$desc = sprintf( esc_attr__( 'You have %s existing users. Do you want to add them to SendinBlue?', 'sib_lang' ), $currentUsers['total_users'] );
			} else {
				$desc = esc_attr__( 'All your users have been added to a SendinBlue list.','sib_lang' );
			}
			self::print_sync_popup();
		?>

			<div id="main-content" class="sib-content">
				<input type="hidden" id="cur_refer_url" value="<?php echo esc_url( add_query_arg( array( 'page' => 'sib_page_home' ), admin_url( 'admin.php' ) ) ); ?> ">
				<!-- Account Info -->
				<div class="panel panel-default row small-content">
					<div class="page-header">
						<strong><?php esc_attr_e( 'My Account', 'sib_lang' ); ?></strong>
					</div>
					<div class="panel-body">
						<span class="col-md-12"><b><?php esc_attr_e( 'You are currently logged in as : ', 'sib_lang' ); ?></b></span>
						<div class="col-md-8 row" style="margin-bottom: 10px;">
							<p class="col-md-12" style="margin-top: 5px;">
								<?php echo esc_attr( $account_user_name ); ?>&nbsp;-&nbsp;<?php echo esc_attr( $account_email ); ?><br>
								<?php
								$count = count( $account_data );
								for ( $i = 0; $i < $count - 1; $i ++ ) {
									echo esc_attr( $account_data[ $i ]['plan_type'] ) . ' - ' . esc_attr( $account_data[ $i ]['credits'] ) . ' ' . esc_attr__( 'credits', 'sib_lang' ) . '<br>';
								}
								?>
								<a href="<?php echo esc_url( add_query_arg( 'sib_action', 'logout' ) ); ?>"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Log out', 'sib_lang' ); ?></a>
							</p>
						</div>

						<span class="col-md-12"><b><?php esc_attr_e( 'Contacts', 'sib_lang' ); ?></b></span>
						<div class="col-md-12 row" style="padding-top: 10px;">
							<div class="col-md-6" style="margin-bottom: 10px;">
								<p style="margin-top: 5px;">
									<?php echo esc_attr__( 'You have', 'sib_lang' ) . ' <span id="sib_total_contacts">' . esc_attr( $total_subscribers ) . '</span> ' . esc_attr__( 'contacts.', 'sib_lang' ); ?><br>
									<a id="sib_list_link" href="https://my.sendinblue.com/users/list/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Access to the list of all my contacts', 'sib_lang' ); ?></a>
								</p>
							</div>

							<div class="col-md-6 row" style="margin-bottom: 10px;">
								<p class="col-md-8" style="margin-top: 5px;">
									<b><?php echo esc_attr__( 'Users Synchronisation', 'sib_lang' ); ?></b><br>
									<?php echo esc_attr( $desc ); ?><br>
								</p>
								<div class="col-md-4">
									<a <?php echo '1' === $isEnableSync ? '' : 'disabled'; ?> id="sib-sync-btn" class="btn btn-primary" style="margin-top: 28px; " name="<?php echo esc_attr__( 'Users Synchronisation', 'sib_lang' ); ?>" href="#"><?php esc_attr_e( 'Sync my users', 'sib_lang' ); ?></a>
								</div>

							</div>
						</div>

						<span class="col-md-12"><b><?php esc_attr_e( 'Campaigns', 'sib_lang' ); ?></b></span>
						<div class="col-md-12 row" style="padding-top: 10px;">
							<div class="col-md-4">
								<span style="line-height: 200%;">
									<span class="glyphicon glyphicon-envelope"></span>
									<?php esc_attr_e( 'Email Campaigns', 'sib_lang' ); ?>
								</span>
								<div class="list-group" id="list-group-email-campaign">
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'sent_c',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['classic']['Sent'] ); ?></span>
										<span class="glyphicon glyphicon-send"></span>
										<?php esc_attr_e( 'Sent', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'draft_c',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['classic']['Draft'] ); ?></span>
										<span class="glyphicon glyphicon-edit"></span>
										<?php esc_attr_e( 'Draft', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'submitted_c',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['classic']['Queued'] ); ?></span>
										<span class="glyphicon glyphicon-dashboard"></span>
										<?php esc_attr_e( 'Scheduled', 'sib_lang' ); ?>
									</a>
									<div class="list-group-item">
										<a href="
										<?php
										echo esc_url(
											add_query_arg(
												array(
													'page' => 'sib_page_campaigns',
													'sort' => 'create',
													'type' => 'classic',
												), admin_url( 'admin.php' )
											)
										);
?>
"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Create new email campaign', 'sib_lang' ); ?></a>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<span style="line-height: 200%;">
									<span class="glyphicon glyphicon-phone"></span>
									<?php esc_attr_e( 'SMS Campaigns', 'sib_lang' ); ?>
								</span>
								<div class="list-group" id="list-group-email-campaign">
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'sent_s',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['sms']['Sent'] ); ?></span>
										<span class="glyphicon glyphicon-send"></span>
										<?php esc_attr_e( 'Sent', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'draft_s',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['sms']['Draft'] ); ?></span>
										<span class="glyphicon glyphicon-edit"></span>
										<?php esc_attr_e( 'Draft', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'submitted_s',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['sms']['Queued'] ); ?></span>
										<span class="glyphicon glyphicon-dashboard"></span>
										<?php esc_attr_e( 'Scheduled', 'sib_lang' ); ?>
									</a>
									<div class="list-group-item">
										<a href="
										<?php
										echo esc_url(
											add_query_arg(
												array(
													'page' => 'sib_page_campaigns',
													'sort' => 'create',
													'type' => 'sms',
												), admin_url( 'admin.php' )
											)
										);
?>
"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Create new sms campaign', 'sib_lang' ); ?></a>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<span style="line-height: 200%;">
									<span class="glyphicon glyphicon-play-circle"></span>
									<?php esc_attr_e( 'Trigger Marketing', 'sib_lang' ); ?>
								</span>
								<div class="list-group" id="list-group-email-campaign">
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'sent_t',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['trigger']['Sent'] ); ?></span>
										<span class="glyphicon glyphicon-send"></span>
										<?php esc_attr_e( 'Sent', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'draft_t',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['trigger']['Draft'] ); ?></span>
										<span class="glyphicon glyphicon-edit"></span>
										<?php esc_attr_e( 'Draft', 'sib_lang' ); ?>
									</a>
									<a class="list-group-item" href="
									<?php
									echo esc_url(
										add_query_arg(
											array(
												'page' => 'sib_page_campaigns',
												'sort' => 'submitted_t',
											), admin_url( 'admin.php' )
										)
									);
?>
">
										<span class="badge"><?php echo esc_attr( $campaign_stat['trigger']['Queued'] ); ?></span>
										<span class="glyphicon glyphicon-dashboard"></span>
										<?php esc_attr_e( 'Scheduled', 'sib_lang' ); ?>
									</a>
									<div class="list-group-item">
										<a href="
										<?php
										echo esc_url(
											add_query_arg(
												array(
													'page' => 'sib_page_campaigns',
													'sort' => 'create',
													'type' => 'trigger',
												), admin_url( 'admin.php' )
											)
										);
?>
"><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Create new trigger campaign', 'sib_lang' ); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Transactional Email -->
				<div class="panel panel-default row small-content">
					<div class="page-header">
						<strong><?php esc_attr_e( 'Transactional emails', 'sib_lang' ); ?></strong>
					</div>
					<div class="panel-body">
						<?php
						if ( 'disabled' == $smtp_status ) :
							?>
							<div id="smtp-failure-alert" class="col-md-12 sib_alert alert alert-danger" role="alert"><?php esc_attr_e( 'Unfortunately, your "Transactional emails" are not activated because your SendinBlue SMTP account is not active. Please send an email to contact@sendinblue.com in order to ask for SMTP account activation', 'sib_lang' ); ?></div>
							<?php
						endif;
						?>
						<div id="success-alert" class="col-md-12 sib_alert alert alert-success" role="alert" style="display: none;"><?php esc_attr_e( 'Mail Sent.', 'sib_lang' ); ?></div>
						<div id="failure-alert" class="col-md-12 sib_alert alert alert-danger" role="alert" style="display: none;"><?php esc_attr_e( 'Please input valid email.', 'sib_lang' ); ?></div>
						<div class="row">
							<p class="col-md-4 text-left"><?php esc_attr_e( 'Activate email through SendinBlue', 'sib_lang' ); ?></p>
							<div class="col-md-3">
								<label class="col-md-6"><input type="radio" name="activate_email" id="activate_email_radio_yes" value="yes" 
								<?php
								checked( $home_settings['activate_email'], 'yes' );
								if ( 'disabled' === $smtp_status ) {
									echo ' disabled';
								}
									?>
									 >&nbsp;Yes</label>
								<label class="col-md-6"><input type="radio" name="activate_email" id="activate_email_radio_no" value="no" <?php checked( $home_settings['activate_email'], 'no' ); ?>>&nbsp;No</label>
							</div>
							<div class="col-md-5">
								<small style="font-style: italic;"><?php esc_attr_e( 'Choose "Yes" if you want to use SendinBlue SMTP to send transactional emails', 'sib_lang' ); ?></small>
							</div>
						</div>
						<div class="row" id="email_send_field" 
						<?php
						if ( 'yes' !== $home_settings['activate_email'] ) {
							echo 'style="display:none;"';
						}
						?>
						>
							<div class="row" style="margin-left: 0px;margin-bottom: 10px;">
								<p class="col-md-4 text-left"><?php esc_attr_e( 'Choose your sender', 'sib_lang' ); ?></p>
								<div class="col-md-3">
									<select id="sender_list" class="col-md-12">
										<?php
										$senders = SIB_API_Manager::get_sender_lists();
										foreach ( $senders as $sender ) {
											echo "<option value='" . esc_attr( $sender['id'] ) . "' " . selected( $home_settings['sender'], $sender['id'] ) . '>' . esc_attr( $sender['from_name'] ) . '&nbsp;&lt;' . esc_attr( $sender['from_email'] ) . '&gt;</option>';
										}
										?>
									</select>
								</div>
								<div class="col-md-5">
									<a href="https://my.sendinblue.com/users/settings/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" style="font-style: italic;" target="_blank" ><i class="fa fa-angle-right"></i>&nbsp;<?php esc_attr_e( 'Create a new sender', 'sib_lang' ); ?></a>
								</div>
							</div>
							<div class="row" style="margin-left: 0px;">
								<p class="col-md-4 text-left"><?php esc_attr_e( 'Enter email to send a test', 'sib_lang' ); ?></p>
								<div class="col-md-3">
									<input id="activate_email" type="email" class="col-md-12">
									<button type="button" id="send_email_btn" class="col-md-12 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php esc_attr_e( 'Send email', 'sib_lang' ); ?></button>
								</div>
								<div class="col-md-5">
									<small style="font-style: italic;"><?php esc_attr_e( 'Select here the email address you want to send a test email to.', 'sib_lang' ); ?></small>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Marketing Automation -->
				<div class="panel panel-default row small-content">
					<div class="page-header">
						<strong><?php esc_attr_e( 'Automation', 'sib_lang' ); ?></strong>
					</div>
					<div class="panel-body">
						<div class="sib-ma-alert sib-ma-active alert alert-success" role="alert" style="display: none;"><?php esc_attr_e( 'Your Marketing Automation script is installed correctly.', 'sib_lang' ); ?></div>
						<div class="sib-ma-alert sib-ma-inactive alert alert-danger" role="alert" style="display: none;"><?php esc_attr_e( 'Your Marketing Automation script has been uninstalled', 'sib_lang' ); ?></div>
						<div class="sib-ma-alert sib-ma-disabled alert alert-danger" role="alert" style="display: none;"><?php esc_attr_e( 'To activate Marketing Automation (beta), please go to your SendinBlue\'s account or contact us at contact@sendinblue.com', 'sib_lang' ); ?></div>
						<input type="hidden" id="sib-ma-unistall" value="<?php esc_attr_e( 'Your Marketing Automation script will be uninstalled, you won\'t have access to any Marketing Automation data and workflows', 'sib_lang' ); ?>">
						<div class="row">
							<p class="col-md-4 text-left"><?php esc_attr_e( 'Activate Marketing Automation through SendinBlue', 'sib_lang' ); ?></p>
							<div class="col-md-3">
								<label class="col-md-6"><input type="radio" name="activate_ma" id="activate_ma_radio_yes" value="yes" 
								<?php
								checked( $home_settings['activate_ma'], 'yes' );
									?>
									 >&nbsp;Yes</label>
								<label class="col-md-6"><input type="radio" name="activate_ma" id="activate_ma_radio_no" value="no" <?php checked( $home_settings['activate_ma'], 'no' ); ?>>&nbsp;No</label>
							</div>
							<div class="col-md-5">
								<small style="font-style: italic;"><?php esc_attr_e( 'Choose "Yes" if you want to use SendinBlue Automation to track your website activity', 'sib_lang' ); ?></small>
							</div>
						</div>
						<div class="row" style="">
							<p class="col-md-4 text-left" style="font-size: 13px; font-style: italic;"><?php printf( esc_attr__( '%1$s Explore our resource %2$s to learn more about SendinBlue Automation', 'sib_lang' ), '<a href="https://help.sendinblue.com/hc/en-us/articles/208775609" target="_blank">', '</a>' ); ?></p>
							<div class="col-md-3">
								<button type="button" id="validate_ma_btn" class="col-md-12 btn btn-primary"><span class="sib-spin"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i>&nbsp;&nbsp;</span><?php esc_attr_e( 'Activate', 'sib_lang' ); ?></button>
							</div>
							<div class="col-md-5">
							</div>
						</div>
					</div>
				</div>

			</div>
		<?php
		}

		/**
		 * Generate a language box on the plugin admin page.
		 */
		public static function generate_side_bar() {
			do_action( 'sib_language_sidebar' );
		?>

			<div class="panel panel-default text-left box-border-box  small-content">
				<div class="panel-heading"><strong><?php esc_attr_e( 'About SendinBlue', 'sib_lang' ); ?></strong></div>
				<div class="panel-body">
					<p><?php esc_attr_e( 'SendinBlue is an online software that helps you build and grow relationships through marketing and transactional emails, marketing automation, and text messages.', 'sib_lang' ); ?></p>
					<ul class="sib-widget-menu">
						<li>
							<a href="https://www.sendinblue.com/about/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'Who we are', 'sib_lang' ); ?></a>
						</li>
						<li>
							<a href="https://www.sendinblue.com/pricing/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'Pricing', 'sib_lang' ); ?></a>
						</li>
						<li>
							<a href="https://www.sendinblue.com/features/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'Features', 'sib_lang' ); ?></a>
						</li>
					</ul>
				</div>

			</div>
			<div class="panel panel-default text-left box-border-box  small-content">
				<div class="panel-heading"><strong><?php esc_attr_e( 'Need Help?', 'sib_lang' ); ?></strong></div>
				<div class="panel-body">
					<p><?php esc_attr_e( 'Do you have a question or need more information?', 'sib_lang' ); ?></p>
					<ul class="sib-widget-menu">
						<li><a href="https://help.sendinblue.com/hc/en-us/sections/202171729" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'Tutorials', 'sib_lang' ); ?></a></li>
						<li><a href="https://resources.sendinblue.com/category/faq/?utm_source=wordpress_plugin&utm_medium=plugin&utm_campaign=module_link" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'FAQ', 'sib_lang' ); ?></a></li>
					</ul>
					<hr>
				</div>
			</div>
			<div class="panel panel-default text-left box-border-box  small-content">
				<div class="panel-heading"><strong><?php esc_attr_e( 'Recommend this plugin', 'sib_lang' ); ?></strong></div>
				<div class="panel-body">
					<p><?php esc_attr_e( 'Let everyone know you like this plugin through a review!' ,'sib_lang' ); ?></p>
					<ul class="sib-widget-menu">
						<li><a href="http://wordpress.org/support/view/plugin-reviews/mailin" target="_blank"><i class="fa fa-angle-right"></i> &nbsp;<?php esc_attr_e( 'Recommend the SendinBlue plugin', 'sib_lang' ); ?></a></li>
					</ul>
				</div>
			</div>
		<?php
		}

		/**
		 * Get narration script
		 *
		 * @param string $title - pop up title.
		 * @param string $text - pop up content text.
		 */
		static function get_narration_script( $title, $text ) {
			?>
			<i title="<?php echo esc_attr( $title ); ?>" data-container="body" data-toggle="popover" data-placement="right" data-content="<?php echo esc_attr( $text ); ?>" data-html="true" class="fa fa-question-circle popover-help-form"></i>
			<?php
		}

		/** Print disable mode popup */
		static function print_disable_popup() {
		?>
			<div class="modal fade sib-disable-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="font-size: 22px;">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title"><?php esc_attr_e( 'SendinBlue','sib_lang' ); ?></h4>
						</div>
						<div class="modal-body" style="padding: 30px;">
							<p>
								<?php esc_attr_e( 'You are currently not logged in. Create an account or log in to benefit from all of SendinBlue\'s features an your WordPress site.', 'sib_lang' ); ?>
							</p>
							<ul>
								<li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Collect and manage your contacts', 'sib_lang' ); ?></li>
								<li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Send transactional emails via SMTP or API', 'sib_lang' ); ?></li>
								<li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Real time statistics and email tracking', 'sib_lang' ); ?></li>
								<li> <span class="glyphicon glyphicon-ok" style="font-size: 12px;"></span>&nbsp;&nbsp;<?php esc_attr_e( 'Edit and send email marketing', 'sib_lang' ); ?></li>
							</ul>
							<div class="row" style="margin-top: 40px;">
								<div class="col-md-6">
									<a href="https://www.sendinblue.com/users/login/" target="_blank"><i><?php esc_attr_e( 'Have an account?', 'sib_lang' ); ?></i></a>
								</div>
								<div class="col-md-6">
									<a href="https://www.sendinblue.com/users/signup/" target="_blank" class="btn btn-default"><i class="fa fa-angle-double-right"></i>&nbsp;<?php esc_attr_e( 'Free Subscribe Now', 'sib_lang' ); ?>&nbsp;<i class="fa fa-angle-double-left"></i></a>
								</div>
							</div>
						</div>

					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<button id="sib-disable-popup" class="btn btn-primary" data-toggle="modal" data-target=".sib-disable-modal" style="display: none;">sss</button>
			<script>
				jQuery(document).ready(function() {
					jQuery('.sib-disable-modal').modal();

					jQuery('.sib-disable-modal').on('hidden.bs.modal', function() {
						window.location.href = '<?php echo esc_url( add_query_arg( 'page', 'sib_page_home', admin_url( 'admin.php' ) ) ); ?>';
					});
				});

			</script>

		<?php
		}

		/** Print user sync popup */
		static function print_sync_popup() {
			?>
			<div class="modal fade sib-sync-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" style="font-size: 22px;">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title"><?php esc_attr_e( 'Customers Synchronisation','sib_lang' ); ?></h4>
						</div>
						<div class="modal-body sync-modal-body" style="padding: 10px;">
							<div id="sync-failure" class="sib_alert alert alert-danger" style="margin-bottom: 0px;display: none;"></div>
							<form id="sib-sync-form">
							<!-- roles -->
							<div class="row sync-row" style="margin-top: 0;">
								<b><p><?php esc_attr_e( 'Roles to sync', 'sib_lang' ); ?></p></b>
								<?php foreach ( wp_roles()->roles as $role_name => $role_info ) : ?>
								<div class="col-md-6">
									<span class="" style="display: block;float:left;padding-left: 16px;"><input type="checkbox" id="<?php echo esc_attr( $role_name ); ?>" value="<?php echo esc_attr( $role_name ); ?>" name="sync_role" checked><label for="<?php echo esc_attr( $role_name ); ?>" style="margin: 4px 24px 0 7px;font-weight: normal;"><?php echo esc_attr( $role_info['name'] ); ?></label></span>
								</div>
								<?php endforeach; ?>
							</div>
							<!-- lists -->
							<?php $lists = SIB_API_Manager::get_lists(); ?>
							<div class="row sync-row">
								<b><p><?php esc_attr_e( 'Sync Lists', 'sib_lang' ); ?></p></b>
								<div class="col-md-6">
									<p><?php esc_attr_e( 'Choose the Sendinblue list in which you want to add your existing customers:', 'sib_lang' ); ?></p>
								</div>
								<div class="col-md-6">
									<select data-placeholder="Please select the list" id="sib_select_list" name="list_id" multiple="true">
										<?php foreach ( $lists as $list ) : ?>
										<option value="<?php echo esc_attr( $list['id'] ); ?>"><?php echo esc_attr( $list['name'] ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<!-- Match Attributes -->
							<?php
							// available WordPress attributes.
							$wpAttrs = array(
								'first_name' => __( 'First Name','sib_lang' ),
								'last_name' => __( 'Last Name','sib_lang' ),
								'user_url' => __( 'Website URL','sib_lang' ),
								'role' => __( 'User Role','sib_lang' ),
							);
							// available sendinblue attributes.
							$sibAllAttrs = SIB_API_Manager::get_attributes();
							$sibAttrs = $sibAllAttrs['attributes']['normal_attributes'];
							?>
							<div class="row sync-row" id="sync-attr-area">
								<b><p><?php esc_attr_e( 'Match Attributes', 'sib_lang' ); ?></p></b>
								<div class="col-md-11" style="padding: 5px;border-bottom: dotted 1px #dedede;">
									<div class="col-md-6">
										<p><?php esc_attr_e( 'WordPress Users Attributes', 'sib_lang' ); ?></p>
									</div>
									<div class="col-md-6">
										<p><?php esc_attr_e( 'SendinBlue Contact Attributes', 'sib_lang' ); ?></p>
									</div>
								</div>

								<div class="sync-attr-line">
									<div class="col-md-11 sync-attr" style="padding: 5px;border-bottom: dotted 1px #dedede;">
										<div class="col-md-5">
											<select class="sync-wp-attr" name="" style="width: 100%;">
												<?php foreach ( $wpAttrs as $id => $label ) : ?>
													<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $label ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-md-1" style="padding-left: 10px;padding-top: 3px;"><span class="dashicons dashicons-leftright"></span></div>
										<div class="col-md-5">
											<select class="sync-sib-attr" name="" style="width: 100%;">
												<?php foreach ( $sibAttrs as $attr ) : ?>
													<option value="<?php echo esc_attr( $attr['name'] ); ?>"><?php echo esc_attr( $attr['name'] ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-md-1" style="padding-top: 3px;">
											<a href="javascript:void(0)" class="sync-attr-dismiss" style="display: none;"><span class="dashicons dashicons-dismiss"></span></a>
										</div>
										<input type="hidden" class="sync-match" name="<?php echo esc_attr( $sibAttrs[0]['name'] ); ?>" value="first_name">
									</div>
								</div>
								<div class="col-md-1" style="padding-top: 9px;">
									<a href="javascript:void(0)" class="sync-attr-plus"><span class="dashicons dashicons-plus-alt "></span></a>
								</div>
							</div>
							<!-- Apply button -->
							<div class="row" style="">
								<a href="javascript:void(0)" id="sib_sync_users_btn" class="btn btn-primary" style="float: right;"><?php esc_attr_e( 'Apply', 'sib_lang' ); ?></a>
							</div>
							</form>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<?php
		}

		/** Ajax module for validation (Home - welcome) */
		public static function ajax_validation_process() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$access_key = isset( $_POST['access_key'] ) ? sanitize_text_field( wp_unslash( $_POST['access_key'] ) ) : '';
			try {
				$mailin = new Mailin( SIB_Manager::SENDINBLUE_API_URL, $access_key );
			} catch ( Exception $e ) {
				if ( $e->getMessage() == 'Mailin requires CURL module' ) {
					wp_send_json( 'curl_no_installed' );
				} else {
					wp_send_json( 'curl_error' );
				}
			}

			$response = $mailin->get_access_tokens();
			if ( is_array( $response ) ) {
				if ( 'success' == $response['code'] ) {

					// store api info.
					$settings = array(
						'access_key' => $access_key,
					);
					update_option( SIB_Manager::MAIN_OPTION_NAME, $settings );

					SIB_Manager::$access_key = $access_key;

					$access_token = $response['data']['access_token'];
					$token_settings = array(
						'access_token' => $access_token,
					);
					update_option( SIB_Manager::ACCESS_TOKEN_OPTION_NAME, $token_settings );

					// get default language at SendinBlue.
					$mailin->partnerWordpress();

					// create tables for users and forms.
					SIB_Model_Users::createTable();
					SIB_Forms::createTable(); // create default form also
					// If the client don't have attributes regarding Double OptIn then we will create these.
					SIB_API_Manager::create_default_dopt();

					wp_send_json( 'success' );
				} else {
					wp_send_json( $response['code'] );
				}
			} else {
				wp_send_json( 'fail' );
			}
		}

		/** Ajax module to change activate marketing automation option */
		public static function ajax_validate_ma() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$main_settings = get_option( SIB_Manager::MAIN_OPTION_NAME );
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			$ma_key = $main_settings['ma_key'];
			if ( '' != $ma_key ) {
				$option_val = isset( $_POST['option_val'] ) ? sanitize_text_field( wp_unslash( $_POST['option_val'] ) ) : 'no';
				$home_settings['activate_ma'] = $option_val;
				update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );
				wp_send_json( $option_val );
			} else {
				$home_settings['activate_ma'] = 'no';
				update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );
				wp_send_json( 'disabled' );
			}
		}

		/** Ajax module to change activate email option */
		public static function ajax_activate_email_change() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$option_val = isset( $_POST['option_val'] ) ? sanitize_text_field( wp_unslash( $_POST['option_val'] ) ) : 'no';
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			$home_settings['activate_email'] = $option_val;
			update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );
			wp_send_json( $option_val );
		}

		/** Ajax module to change sender detail */
		public static function ajax_sender_change() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$sender_id = isset( $_POST['sender'] ) ? sanitize_text_field( wp_unslash( $_POST['sender'] ) ) : ''; // sender id.
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			$home_settings['sender'] = $sender_id;
			$senders = SIB_API_Manager::get_sender_lists();
			foreach ( $senders as $sender ) {
				if ( $sender['id'] == $sender_id ) {
					$home_settings['from_name'] = $sender['from_name'];
					$home_settings['from_email'] = $sender['from_email'];
				}
			}
			update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );
			wp_send_json( 'success' );
		}

		/** Ajax module for send a test email */
		public static function ajax_send_email() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$to = array(
				$_POST['email'] => '',
			);

			$subject  = __( '[SendinBlue SMTP] test email', 'sib_lang' );
			// Get sender info.
			$home_settings = get_option( SIB_Manager::HOME_OPTION_NAME );
			if ( isset( $home_settings['sender'] ) ) {
				$fromname = $home_settings['from_name'];
				$from_email = $home_settings['from_email'];
			} else {
				$from_email = __( 'no-reply@sendinblue.com', 'sib_lang' );
				$fromname = __( 'SendinBlue', 'sib_lang' );
			}

			$from = array( $from_email, $fromname );
			$email_templates = SIB_API_Manager::get_email_template( 'test' );

			$html = $email_templates['html_content'];

			$html = str_replace( '{title}', $subject, $html );

			$mailin = new Mailin( SIB_Manager::SENDINBLUE_API_URL, SIB_Manager::$access_key );

			$headers = array(
				'Content-Type' => 'text/html;charset=iso-8859-1',
				'X-Mailin-tag' => 'Wordpress Mailin Test',
			);
			$data = array(
				'to' => $to,
				'subject'  => $subject,
				'from' => $from,
				'html' => $html,
				'headers' => $headers,
			);
			$mailin->send_email( $data );

			wp_send_json( 'success' );
		}

		/** Ajax module for remove all transient value */
		public static function ajax_remove_cache() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			SIB_API_Manager::remove_transients();
			wp_send_json( 'success' );
		}

		/** Ajax module for sync wp users to contact list */
		public static function ajax_sync_users() {
			check_ajax_referer( 'ajax_sib_admin_nonce', 'security' );
			$postData = isset( $_POST['data'] ) ? $_POST['data'] : array();
			if ( ! isset( $postData['sync_role'] ) ) {
				wp_send_json(
					array(
						'code' => 'empty_role',
						'message' => __( 'Please select a user role.','sib_lang' ),
					)
				);}
			if ( isset( $postData['errAttr'] ) ) {
				wp_send_json(
					array(
						'code' => 'attr_duplicated',
						'message' => sprintf( esc_attr__( 'The attribute %s is duplicated. You can select one at a time.','sib_lang' ), '<b>' . $postData['errAttr'] . '</b>' ),
					)
				);}

			$roles = (array) $postData['sync_role']; // array or string.
			$listIDs = (array) $postData['list_id'];

			unset( $postData['sync_role'] );
			unset( $postData['list_id'] );

			$usersData = 'EMAIL';
			foreach ( $postData as $attrSibName => $attrWP ) {
				$usersData .= ';' . $attrSibName;
			}

			// sync users to sendinblue.
			// create body data like csv.
			// NAME;SURNAME;EMAIL\nName1;Surname1;example1@example.net\nName2;Surname2;example2@example.net.
			$contentData = '';
			foreach ( $roles as $role ) {
				$users = get_users(
					array(
						'role' => $role,
					)
				);
				if ( empty( $users ) ) {
					continue;
				}
				foreach ( $users as $user ) {
					$userId = $user->ID;
					$user_info = get_userdata( $userId );
					$userData = $user_info->user_email;
					foreach ( $postData as $attrSibName => $attrWP ) {
						$userData .= ';' . $user_info->$attrWP;
					}
					$contentData .= "\n" . $userData;
				}
			}
			if ( '' == $contentData ) {
				wp_send_json(
					array(
						'code' => 'empty_users',
						'message' => __( 'There is not any user in the roles.','sib_lang' ),
					)
				);}

			$usersData .= $contentData;
			$result = SIB_API_Manager::sync_users( $usersData, $listIDs );
			$currentUsers = count_users();
			if ( 'success' == $result['code'] ) {
				update_option( 'sib_sync_users', $currentUsers );
			}
			wp_send_json( $result );
		}

		/** Logout process */
		function logout() {
			$setting = array();
			update_option( SIB_Manager::MAIN_OPTION_NAME, $setting );

			$home_settings = array(
				'activate_email' => 'no',
				'activate_ma' => 'no',
			);
			update_option( SIB_Manager::HOME_OPTION_NAME, $home_settings );

			// remove sync users option.
			delete_option( 'sib_sync_users' );
			// remove all transients.
			SIB_API_Manager::remove_transients();

			// remove all forms.
			SIB_Forms::removeAllForms();
			SIB_Forms_Lang::remove_all_trans();

			wp_safe_redirect( add_query_arg( 'page', self::PAGE_ID, admin_url( 'admin.php' ) ) );
			exit();
		}

	}

}
