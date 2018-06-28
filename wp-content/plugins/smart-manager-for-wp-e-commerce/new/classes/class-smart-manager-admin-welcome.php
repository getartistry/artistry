<?php
/**
 * Welcome Page Class
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * SC_Admin_Welcome class
 */
class Smart_Manager_Admin_Welcome {

	/**
	 * Hook in tabs.
	 */

	public $sm_redirect_url,
			$plugin_url;

	static $text_domain, $prefix, $sku, $plugin_file;

	public function __construct() {


		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->sm_redirect_url = admin_url( 'edit.php?post_type=product&page=smart-manager-woo' );
		} else if( is_plugin_active( 'wp-e-commerce/wp-shopping-cart.php' ) ) {
			$this->sm_redirect_url = admin_url( 'edit.php?post_type=wpsc-product&page=smart-manager-wpsc' );
		}

		self::$text_domain = (defined('SM_TEXT_DOMAIN')) ? SM_TEXT_DOMAIN : 'smart-manager-for-wp-e-commerce';
		self::$prefix = (defined('SM_PREFIX')) ? SM_PREFIX : 'sa_smart_manager';
        self::$sku = (defined('SM_SKU')) ? SM_SKU : 'sm';
        self::$plugin_file = (defined('SM_PLUGIN_FILE')) ? SM_PLUGIN_FILE : '';

		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'smart_manager_welcome' ),11 );
		add_action( 'admin_footer', array( $this, 'smart_manager_support_ticket_content' ) );

		$this->plugin_url = plugins_url( '', __FILE__ );
	}

	/**
	 * Handle welcome page
	 */
	public function show_welcome_page() {
		
		if( empty($_GET['landing-page']) ) {
			return;
		}
		
		switch ( $_GET['landing-page'] ) {
			case 'sm-about' :
				$this->about_screen();
				break;
			case 'sm-faqs' :
			 	$this->faqs_screen();
				break;
			case 'sm-beta' :
			 	$this->sm_beta_screen();
				break;
		}
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 */
	public function admin_head() {

		?>

			<style type="text/css">
				/*<![CDATA[*/
				.sm-welcome.about-wrap h3 {
					margin-top: 1em;
					margin-right: 0em;
					margin-bottom: 0.1em;
					font-size: 1.25em;
					line-height: 1.3em;
				}
				.sm-welcome.about-wrap .button-primary {
					margin-top: 18px;
				}
				.sm-welcome.about-wrap .button-hero {
					color: #FFF!important;
					border-color: #03a025!important;
					background: #03a025 !important;
					box-shadow: 0 1px 0 #03a025;
					font-size: 1em;
					font-weight: bold;
				}
				.sm-welcome.about-wrap .button-hero:hover {
					color: #FFF!important;
					background: #0AAB2E!important;
					border-color: #0AAB2E!important;
				}
				.sm-welcome.about-wrap p {
					margin-top: 0.6em;
					margin-bottom: 0.8em;
					line-height: 1.6em;
					font-size: 14px;
				}
				.sm-welcome.about-wrap .feature-section {
					padding-bottom: 5px;
				}
				#sm_promo_valid_msg {
					text-align: center;
					padding-left: 0.5em;
					font-size: 0.8em;
					float: left;
					padding-top: 0.25em;
					font-style: italic;
					color: #E34F4C;
				}
				/*]]>*/
			</style>
		
			<script type="text/javascript">
				jQuery(function($) {
					$(document).ready(function() {
						$('#sm_promo_msg').insertBefore('.sm-welcome');
					});
				});

			</script>

		<?php
	}

	/**
	 * Smart Manager's Support Form
	 */
	function smart_manager_support_ticket_content() {

			if ( !( isset($_GET['page']) && ($_GET['page'] == "smart-manager-woo" || $_GET['page'] == "smart-manager-wpsc" || $_GET['page'] == "smart-manager-settings") && (isset($_GET['landing-page']) && $_GET['landing-page'] == "sm-faqs") ) ) {
				return;
			}

            if (!wp_script_is('thickbox')) {
            	if (!function_exists('add_thickbox')) {
                	require_once ABSPATH . 'wp-includes/general-template.php';
            	}
            	add_thickbox();
        	}

            if ( ! method_exists( 'StoreApps_Upgrade_2_8', 'support_ticket_content' ) ) return;

            $plugin_data = get_plugin_data( self::$plugin_file );
            $license_key = get_site_option( self::$prefix.'_license_key' );

            StoreApps_Upgrade_2_8::support_ticket_content( self::$prefix, self::$sku, $plugin_data, $license_key, self::$text_domain );
    }

	/**
	 * Intro text/links shown on all about pages.
	 */
	private function intro() {
		
		if ( function_exists('smart_manager_get_data') ) {
			$plugin_data = smart_manager_get_data();
			$version = $plugin_data['Version'];
		} else {
			$version = '';
		}

		?>
		<h1><?php printf( __( 'Welcome to Smart Manager %s', self::$text_domain ), $version ); ?></h1>

		<div style="margin-top:0.3em;"><?php _e("Thanks for installing! We hope you enjoy using Smart Manager.", self::$text_domain); ?></div>

		<div id="sm_welcome_feature_section" class="feature-section col two-col" style="margin-bottom:30px!important;">
			<div class="col">
				<!-- <p class="woocommerce-actions"> -->
					<a href="<?php echo $this->sm_redirect_url; ?>" class="button button-hero"><?php _e( 'Go To Smart Manager', self::$text_domain ); ?></a>
				<!-- </p> -->
			</div>

			<style>
			    div#TB_window {
			        background: lightgrey;
			    }
			</style>    
			<!-- edit.php#TB_inline?max-height=420px&inlineId=smart_manager_post_query_form -->
			<div class="col last-feature">
				<p align="right">
					<?php 
						if ( !wp_script_is( 'thickbox' ) ) {
	                        if ( !function_exists( 'add_thickbox' ) ) {
	                            require_once ABSPATH . 'wp-includes/general-template.php';
	                        }
	                        add_thickbox();
	                    }
						echo __( 'Questions? Need Help?', self::$text_domain ); 
					?><br>

					<?php if (SMBETAPRO === true) { ?>
						<a href="options-general.php?page=smart-manager-settings" target="_blank"><?php _e( 'Settings', self::$text_domain ); ?></a> | 
					<?php } ?>
					<a href="https://www.storeapps.org/knowledgebase_category/smart-manager/?utm_source=sm&utm_medium=welcome_page&utm_campaign=view_docs" target="_blank"><?php _e( 'Docs', self::$text_domain ); ?></a>
				</p>
			</div>
		</div>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['landing-page'] == 'sm-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'landing-page' => 'sm-about' ), $this->sm_redirect_url ) ); ?>">
				<?php _e( "Know Smart Manager", self::$text_domain ); ?>
			</a>
			<a class="nav-tab <?php if ( $_GET['landing-page'] == 'sm-faqs' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'landing-page' => 'sm-faqs' ), $this->sm_redirect_url ) ); ?>">
				<?php _e( "FAQ's", self::$text_domain ); ?>
			</a>
			<a class="nav-tab <?php if ( $_GET['landing-page'] == 'sm-beta' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'landing-page' => 'sm-beta' ), $this->sm_redirect_url ) ); ?>">
				<?php _e( "Smart Manager Beta", self::$text_domain ); ?> 
			</a>
		</h2>
		<?php
	}


	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		?>
		<div class="wrap sm-welcome about-wrap">

			<?php $this->intro();?>
			<div>
				<p><?php echo __( 'Smart Manager is a unique, revolutionary tool that gives you the power to <b> boost your productivity by 10x </b> in managing your store by using a using a <b>familiar, single page, spreadsheet like interface</b>. ', self::$text_domain ); ?></p>
				<!-- <div class="headline-feature feature-video">
					<?php echo $embed_code = wp_oembed_get('http://www.youtube.com/watch?v=kOiBXuUVF1U', array('width'=>5000, 'height'=>560)); ?>
				</div> -->
			</div>

			<div>
				<center><h3><?php echo __( 'What is possible', self::$text_domain ); ?></h3></center>
				<div class="feature-section col three-col" >
					<div class="col">
						<h4><?php echo __( 'One Stop Dashboard', self::$text_domain ); ?></h4>
						<p>
							<?php echo __( 'You can easily and efficiently manage <b>products, product variations, customers and orders</b> from a single page.', self::$text_domain ); ?>
						</p>
					</div>
					<div class="col">
						<h4><?php echo __( 'Inline Editing', self::$text_domain ); ?></h4>
						<p>
							<?php echo sprintf(__( 'You can quickly update your products, customers and orders in the grid itself. This facilitates editing of multiple rows at a time instead of editing and sacing each row separately, %s.', self::$text_domain ), '<a href="https://www.storeapps.org/docs/sm-how-to-use-inline-editing/?utm_source=sm&utm_medium=welcome_page&utm_campaign=sm_know" target="_blank">' . __( 'see how', self::$text_domain ) . '</a>' ); ?>
						</p>
					</div>
					<div class="last-feature col">
						<h4><?php echo __( 'Filter/Search Records', self::$text_domain ); ?></h4>
						<p>
							<?php echo sprintf(__( 'If you would like to filter the records, you can easily do the same by simply entering keyword in the “Search” field at the top of the grid (%s). If you need to have a more specific search result, then you can switch to “%s“ and then search.', self::$text_domain ), '<a href="https://www.storeapps.org/docs/sm-how-to-filter-records-using-simple-search/?utm_source=sm&utm_medium=welcome_page&utm_campaign=sm_know" target="_blank">' . __( 'see how', self::$text_domain ) . '</a>', '<a href="https://www.youtube.com/watch?v=hX7CcZYo060" target="_blank">' . __( 'Advanced Search', self::$text_domain ) . '</a>' ); ?>
						</p>
					</div>
				</div>
				<div class="feature-section col three-col" >
					<div class="col">
						<h4>
							<?php 
								if (SMBETAPRO === true) {
									echo __( 'Batch Update', self::$text_domain );											
								} else {
									echo sprintf(__( 'Batch Update (only in %s)', self::$text_domain ), '<a href="https://www.storeapps.org/product/smart-manager/" target="_blank">' . __( 'Pro', self::$text_domain ) . '</a>' );
								}
							?>
						</h4>
						<p>
							<?php echo sprintf(__( 'You can change / update multiple fields of the entire store OR for selected items by selecting multiple records and then simply click on “Batch Update”, %s.', self::$text_domain ), '<a href="https://www.storeapps.org/docs/sm-how-to-use-batch-update/?utm_source=sm&utm_medium=welcome_page&utm_campaign=sm_know" target="_blank">' . __( 'see how', self::$text_domain ) . '</a>' ); ?>
						</p>
					</div>
					<div class="col">
						<h4>
							<?php 
								if (SMBETAPRO === true) {
									echo __( 'Duplicate Products', self::$text_domain );											
								} else {
									echo sprintf(__( 'Duplicate Products (only in %s)', self::$text_domain ), '<a href="https://www.storeapps.org/product/smart-manager/" target="_blank">' . __( 'Pro', self::$text_domain ) . '</a>' );
								}
							?>
						</h4>
						<p>
							<?php echo sprintf(__( 'You can duplicate products of the entire store OR selected products by simply selecting products and then click on “Duplicate Products”, %s.', self::$text_domain ), '<a href="https://www.storeapps.org/docs/sm-how-to-duplicate-products/?utm_source=sm&utm_medium=welcome_page&utm_campaign=sm_know" target="_blank">' . __( 'see how', self::$text_domain ) . '</a>' ); ?>
						</p>
					</div>
					<div class="last-feature col">
						<h4><?php 
								if (SMBETAPRO === true) {
									echo __( 'Export CSV', self::$text_domain );											
								} else {
									echo sprintf(__( 'Export CSV (only in %s)', self::$text_domain ), '<a href="https://www.storeapps.org/product/smart-manager/" target="_blank">' . __( 'Pro', self::$text_domain ) . '</a>' );
								}
							?>
						</h4>
						<p>
							<?php echo __( 'You can export all the records OR filtered records (<i>filtered using “Search” or “Advanced Search”</i>) by simply clicking on the “Export CSV” button at the bottom right of the grid.', self::$text_domain ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="changelog" align="center">
				<h4><?php _e( 'Do check out Some of our other products!', self::$text_domain ); ?></h4>
				<p><a target="_blank" href="<?php echo esc_url('https://www.storeapps.org/shop/'); ?>"><?php _e('Let me take you to product catalog', self::$text_domain); ?></a></p>
			</div>
		</div>

		<?php
	}

	/**
	 * Output the about screen.
	 */
	public function sm_beta_screen() {
		?>
		<div class="wrap sm-welcome about-wrap">

		<?php $this->intro(); ?>

			<div>
				<div class="headline-feature">
					<h2 style="text-align:center;"><?php _e( 'Introducing Smart Manager Beta', self::$text_domain ); ?></h2>
					<div class="featured-image">
						<img src="<?php echo $this->plugin_url . '/../../images/smart-manager-beta.png'?>" />
					</div>
					<p><?php echo __( 'Smart Manager Beta is nothing but a transformed more bigger version of the previous Smart Manager. It has ton’s of functionality and only promises to be better than what Smart Manager ever was.', self::$text_domain ); ?></p>
				</div>
				<center><h3><?php echo __( 'Digging Deeper into Smart Manager Beta...', self::$text_domain ); ?></h3></center>
				<div class="feature-section col three-col" >
					<div>
						<h4><?php echo __( 'Everything Wordpress', self::$text_domain ); ?></h4>
						<p>
							<?php echo sprintf(__( 'Unlike previous Smart Manager, Smart Manager gives you the power to manage %s and %s.', self::$text_domain ),'<code>all post types</code>', '<code>any custom field</code>'); ?>
						</p>
					</div>
					<div>
						<h4><?php echo __( 'Infinite Scrolling', self::$text_domain ); ?></h4>
						<p>
							<?php echo sprintf(__( 'Unlike the older version of Smart Manager that displayed records in various pages, with Smart Manager Beta all the records are in one single page itself enabling %s and %s.', self::$text_domain ), '<code>one glance at all records</code>', '<code>faster loading</code>'); ?>
						</p>
					</div>
					<div class="last-feature">
						<h4><?php echo __( 'Improved Performance', self::$text_domain ); ?></h4>
						<p>
							<?php echo __( 'We\'ve worked on every inch of the coding to make Smart Manager Beta way faster and the performance a lot smoother.', self::$text_domain ); ?>
						</p>
					</div>
				</div>
				<a href="<?php $this->sm_redirect_url .= '&sm_beta=1'; echo $this->sm_redirect_url; ?>" class="button button-primary"><?php _e( 'Try Smart Manager Beta', self::$text_domain ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the FAQ's screen.
	 */
	public function faqs_screen() {
		?>
		<div class="wrap sm-welcome about-wrap">

			<?php $this->intro(); ?>
        
            <h3><?php echo __("FAQ / Common Problems", self::$text_domain); ?></h3>

            <?php
            	$faqs = array(
            				array(
            						'que' => __( 'Smart Manager grid is empty?', self::$text_domain ),
            						'ans' => __( 'Make sure you are using latest version of Smart Manager. If still the issue persist, deactivate all plugins except WooCommerce/WPeCommerce & Smart Manager. Recheck the issue, if the issue still persists, contact us. If the issue goes away, re-activate other plugins one-by-one & re-checking the fields, to find out which plugin is conflicting. Inform us about this issue.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'Can I import using Smart Manager?', self::$text_domain ),
            						'ans' => __( 'Sorry! currently you cannot import using Smart Manager.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'Smart Manager search functionality not working', self::$text_domain ),
            						'ans' => __( 'Request you to kindly deactivate and activate the Smart Manager plugin once and then have a recheck with the Smart Manager search functionality.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'How do I upgrade a Lite version to a Pro version?', self::$text_domain ),
            						'ans' => __( 'Request you to kindly first delete and deactivate the Smart Manager Lite plugin from your site and then upload and activate the Smart Manager Pro plugin on your site once and you are done.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'Updating variation parent price/sales price not working?', self::$text_domain ),
            						'ans' => __( 'Smart Manager is based on WooCommerce and WPeCommerce and the same e-commerce plugins sets the price/sales price of the variation parents automatically based on the price/sales price of its variations.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'Is Smart Manager, WPML compatible?', self::$text_domain ),
            						'ans' => __( 'Smart Manager is not fully WPML compatible. In other words, Smart Manager will display and let you manage the different translations of the product, however, it will not update all the translations on updation of any one of the translations for a given product.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'How to manage any custom field of any custom plugin using Smart Manager?', self::$text_domain ),
            						'ans' => sprintf(__( 'Smart Manager by default considers all the product custom fields that are stored in the wordpress postmeta table. So, if any plugin adds custom fields to wordpress %s table then only Smart Manager lets you manage the same else managing the same is currently not possible. Also, you can have a check whether you are able to manage the same custom field using Smart Manager Beta (a complete revamped version of Smart Manager)', self::$text_domain ), '<code>postmeta</code>' )
            					),
            				array(
            						'que' => __( 'How can I increase the number of rows per page?', self::$text_domain ),
            						'ans' => sprintf(__( 'You can modify number of records to be shown in the Smart Manager on one page.
														For that, you\'ll have to make manual change in your wordpress database and also maintain the same changes whenever you update your Smart Manager copy.
														%s Go to your database, open table wp_options & look for the row having \'%s\' as \'%s\'.
														%s Enter \'%s\' as number of records you want to display in the Smart Manager on one page and click on save. That\'s it!
														%s: Updating the \'%s\' option to a larger value can hamper some processes like loading & updating data.', self::$text_domain ), '<ul><li>', '<code>option_name</code>', '<code>_sm_set_record_limit</code>', '</li><li>', '<code>option_name</code>', '</li></ul><br/><b>P.S.</b>', '<code>_sm_set_record_limit</code>'  )
            					),
            				array(
            						'que' => __( 'How to add columns to Smart Manager dashboard?', self::$text_domain ),
            						'ans' => sprintf(__( 'To show/hide columns from the Smart Manager dashboard, click the %s next to any of the column headers and simply check/uncheck the columns from the \'%s\' sub-menu. %s.', self::$text_domain ), '<code>down arrow</code>', '<code>Columns</code>', '<a href="https://www.storeapps.org/docs/sm-how-to-show-hide-columns-in-dashboard/?utm_source=sm&utm_medium=welcome_page&utm_campaign=sm_faqs" target="_blank">' . __( 'See how', self::$text_domain ) . '</a>')
            					),
            				array(
            						'que' => __( 'How to sort on the entire database in Smart Manager?', self::$text_domain ),
            						'ans' => __( 'Currently, Smart Manager sorts only the records visible on a particular page and not on the entire database. However, we do have same functionality in the roadmap for Smart Manager Beta and would be implemented soon.', self::$text_domain )
            					),
            				array(
            						'que' => __( 'How to reset the sort in Smart Manager?', self::$text_domain ),
            						'ans' => sprintf(__( 'Currently, for resetting the sort, you would need to make changes at the databse level.
		            									%s Search for \'%s\' option_name in the wordpress %s table and simply delete the same and then refresh Smart Manager and the sort should be resetted.
		            									%s So, for example, if the login_email is \'%s\' and you want to reset the sort for \'%s\' dashboard then you would need to search for \'%s\' in the options table and delete the same.', self::$text_domain ), '<br/><br/>', '<code>_sm_{login email}_{Smart Manager Dashboard}</code>', '<code>options</code>', '<br/><br/>', '<code>abc@wordpress.com</code>', '<code>Products</code>', '<code>_sm_abc@wordpress.com_Products</code>')
            					),
            				array(
            						'que' => __( 'How can I batch update entire search result spread across multiple pages?', self::$text_domain ),
            						'ans' => sprintf(__( 'For batch updating the entire search result, you need to select the checkbox on the header row and then select the \'%s\' option in the batch update wibndow and clicking on update button will update all the records in the search result', self::$text_domain ), '<code>All items in the store(including variations)</code>')
            					),
            				array(
            						'que' => __( 'How to get rid of smart manager pro advertising in backend?', self::$text_domain ),
            						'ans' => sprintf(__( 'In order to get rid of the advertising, you would need to make some code level changes and also maintain the same whenever you update your copy of Smart Manager
            									%s To remove the same, please follow the below steps:
            									%s Go to your Smart Manager folder, open smart-manager.php file. 
            									%s Find the \'%s\' line of code.
            									%s Comment that particular html span element. Save the file.
            									%s Refresh your Smart Manager dashboard page. The ad won\'t be visible now.', self::$text_domain ), '<br/><br/>', '<br/> <ul><li>', '</li><li>', '<code>span style="float:right; margin: -6px -21px -20px 0px;"</code>', '</li><li>', '</li></ul>')
            					),
            				array(
            						'que' => __( 'How to get increase the product thumbnail image size in Smart Manager?', self::$text_domain ),
            						'ans' => sprintf(__( 'In order to increase the product thumbnail image size, you would need to make some code level changes and also maintain the same whenever you update your copy of Smart Manager
            									%s To remove the same, please follow the below steps:
            									%s Go to your Smart Manager folder, open \'smart-manager-for-wp-e-commerce/sm/smart-manager-woo.js\' file. 
            									%s Find the \'%s\' line of code.
            									%s Make changes to the \'%s\' and \'%s\' CSS property values in the line of code. Save the file.
            									%s Refresh your Smart Manager dashboard page.', self::$text_domain ), '<br/><br/>', '<br/> <ul><li>', '</li><li>', '<code>img width=16px height=16px src="\' + record.data.thumbnail + \'"</code>', '</li><li>', '<code>width</code>', '<code>height</code>', '</li></ul>')
            					)
            			);

				
				if (SMBETAPRO === true) {
					$faqs[] = array(
								'que' => __( 'I can\'t find a way to do X...', self::$text_domain ),
								'ans' => sprintf(__( 'Smart Manager is actively developed. If you can\'t find your favorite feature (or have a suggestion) %s. We\'d love to hear from you.', self::$text_domain ), '<a class="thickbox" href="' . admin_url('#TB_inline?inlineId=sa_smart_manager_post_query_form&height=550') .'" title="' . __( 'Submit your query', self::$text_domain ) .'">' . __( 'contact us', self::$text_domain ) . '</a>' )
							);
				}

				$faqs = array_chunk( $faqs, 2 );

				echo '<div>';
				foreach ( $faqs as $fqs ) {
					echo '<div class="two-col">';
					foreach ( $fqs as $index => $faq ) {
						echo '<div' . ( ( $index == 1 ) ? ' class="col last-feature"' : ' class="col"' ) . '>';
						echo '<h4>' . sprintf(__( '%s', self::$text_domain ), $faq['que'] ) . '</h4>';
						echo '<p>' . $faq['ans'] . '</p>';
						echo '</div>';
					}
					echo '</div>';
				}
				echo '</div>';
            ?>

		</div>
		
		<?php
	}


	/**
	 * Sends user to the welcome page on first activation.
	 */
	public function smart_manager_welcome() {

		if ( ! get_transient( '_sm_activation_redirect' ) ) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient( '_sm_activation_redirect' );

		if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			wp_redirect( admin_url( 'edit.php?post_type=product&page=smart-manager-woo&landing-page=sm-about' ) );
		} else if( is_plugin_active( 'wp-e-commerce/wp-shopping-cart.php' ) ) {
			wp_redirect( admin_url( 'edit.php?post_type=wpsc-product&page=smart-manager-wpsc&landing-page=sm-about' ) );	
		}

		exit;

	}
}

$GLOBALS['smart_manager_admin_welcome'] = new Smart_Manager_Admin_Welcome();