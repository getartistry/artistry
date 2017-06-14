<?php
/**
 * Admin Panel Template
 *
 * @author 		WaspThemes
 * @category 	Template
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/* ---------------------------------------------------- */
/* Adding welcome screen Hook							*/
/* ---------------------------------------------------- */
function welcome_screen_activate() {
  set_transient( '_welcome_screen_activation_redirect', true, 30 );
}

register_activation_hook( WT_PLUGIN_DIR.'yellow-pencil.php', 'welcome_screen_activate' );



/* ---------------------------------------------------- */
/* Automatic redirect after active						*/
/* ---------------------------------------------------- */
function welcome_screen_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_welcome_screen_activation_redirect' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( '_welcome_screen_activation_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'yp-welcome-screen' ), admin_url( 'admin.php' ) ) );

}

add_action( 'admin_init', 'welcome_screen_do_activation_redirect' );



/* ---------------------------------------------------- */
/* Welcome Screen Content 								*/
/* ---------------------------------------------------- */
function yp_welcome_screen_content(){
  ?>
  <div class="wrap yp-page-welcome about-wrap">
	<h1>Welcome to Yellow Pencil <?php echo YP_VERSION; ?></h1>

	<div class="about-text">
		Congratulations! You are about to use most powerful design tool for WordPress ever - Yellow Pencil Style Editor Plugin.</div>
	<div class="wp-badge yp-badge">Version <?php echo YP_VERSION; ?></div>
	<p>
		<a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>" class="button button-primary button-large">let's start!</a>
	</p>
	<h2 class="nav-tab-wrapper">
			<a href="<?php echo admin_url('admin.php?page=yp-welcome-screen'); ?>" class="nav-tab<?php if(!isset($_GET['tab'])){ ?> nav-tab-active<?php } ?>">Hello</a>
			<a href="<?php echo admin_url('admin.php?page=yp-welcome-screen&tab=resources'); ?>" class="nav-tab<?php if(isset($_GET['tab'])){ ?> nav-tab-active<?php } ?>">Resources</a>
	</h2>

	<?php if(!isset($_GET['tab'])){ ?>
	<div class="yp-welcome-tab">

		<img class="yp-featured-img" src="<?php echo WT_PLUGIN_URL; ?>images/promo.png" />

		<div class="yp-right-content">
			<h3>Front-End Design Tool For WordPress!</h3>
			<p>Yellow Pencil is a Visual Style Editor WordPress plugin that you can use with any theme and plugin to make customizing your website much easier. 100% visual interface, Edit your site in real-time.</p>

			<p>Today become a professional web designer and personalizing your website in a few minutes!</p>

			<h3>See What's New!</h3>
			Check out <a href="http://waspthemes.com/yellow-pencil/release-notes/" target="_blank">release notes</a> to see update details.
		</div>
		<div class="clear"></div>

	</div>
	<?php }else{ ?>
	<div class="yp-welcome-tab">

		<div class="yp-resources-left">
			<h3>Resources</h3>
			<p></p>
			<ul>
				<li><a href="http://waspthemes.com/yellow-pencil/release-notes/" target="_blank">Release Notes</a></li>
				<li><a href="http://waspthemes.com/yellow-pencil/documentation/" target="_blank">Documentation</a></li>
				<li><a href="http://waspthemes.com/yellow-pencil/" target="_blank">Plugin Website</a></li>
				<li><a href="https://waspthemes.ticksy.com/" target="_blank">Official support</a></li>
			</ul>

			<h3>Join Community</h3>
			<ul>
				<li><a href="https://www.facebook.com/groups/YellowPencils/" target="_blank">Facebook Community</a></li>
			</ul>
			
		</div>

		<div class="yp-resources-right">

			<h3>Versions</h3>
			<ul>
				<li><a href="https://wordpress.org/plugins/yellow-pencil-visual-theme-customizer/" target="_blank">Get Lite Version (Free)</a></li>
				<li><a href="http://codecanyon.net/item/yellow-pencil-visual-customizer-for-wordpress/11322180?ref=WaspThemes" target="_blank">Get Pro Version</a></li>
			</ul>

			<h3>Follow Us</h3>
			<ul>
				<li><a href="https://twitter.com/@waspthemes" target="_blank">Twitter</a></li>
				<li><a href="https://www.facebook.com/waspthemes/" target="_blank">Facebook</a></li>
				<li><a href="https://codecanyon.net/user/waspthemes" target="_blank">Envato</a></li>
			</ul>

		</div>
		<div class="clear"></div>

	</div>
	<?php } ?>

	<?php if(!isset($_GET['tab'])){ ?>
	<div class="yp-welcome-feature feature-section">

		<div class="yp-column">
			<img class="yp-img-center" src="<?php echo WT_PLUGIN_URL; ?>images/promo-1.png">
			
			<div class="yp-feature-column">
				<h4>Start to Customize!</h4>
				<p>Edit colors, fonts, sizes and other all styles with a few click. <a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>">Start to visual customizing</a>.</p>
			</div>

		</div>

		<div class="yp-column">
			<img class="yp-img-center" src="<?php echo WT_PLUGIN_URL; ?>images/promo-2.png">
			
			<div class="yp-feature-column">
				<h4>Manage CSS Style</h4>

				<p>Keep site design in your control, manage customized pages and changes from <a href="<?php echo admin_url('admin.php?page=yellow-pencil-changes'); ?>">this page</a>.</p>
			</div>

		</div>

		<div class="yp-column">
			<img class="yp-img-center" src="<?php echo WT_PLUGIN_URL; ?>images/promo-3.png">
			
			<div class="yp-feature-column">
				<h4>Help & Support!</h4>
				<p>We recommend join <a target="_blank" href="https://www.facebook.com/groups/YellowPencils/">Facebook Community</a> and check out <a target="_blank" href="http://waspthemes.com/yellow-pencil/documentation/">the plugin documentation</a> before start to customize</a>.</p>
			</div>

		</div>

		<div class="clear"></div>

	</div>

	<p class="yp-thank-you">Thank you for choosing Yellow Pencil,<br>Made By WaspThemes.</p>
	<?php } ?>

</div>
  <?php
}



/* ---------------------------------------------------- */
/* Adding plugin control menu 							*/
/* ---------------------------------------------------- */
function yp_add_setting_menu() {

    add_menu_page (
        'Yellow Pencil Options',
        'Yellow Pencil',
        'edit_theme_options',
        'yellow-pencil-changes',
        'yp_option_func',
        'dashicons-admin-customizer'
    );

    add_submenu_page( 'yellow-pencil-changes', __('CSS Styles','yp'), __('CSS Styles','yp'), 'edit_theme_options', 'yellow-pencil-changes', 'yp_option_func' );
    add_submenu_page( 'yellow-pencil-changes', __('Settings','yp'), __('Settings','yp'), 'edit_theme_options', 'yellow-pencil-settings', 'yp_option_func' );
    add_submenu_page( 'yellow-pencil-changes', __('Custom Animations','yp'), __('Custom Animations','yp'), 'edit_theme_options', 'yellow-pencil-animations', 'yp_option_func' );
    
    if(defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', __('Product License','yp'), __('Product License','yp'), 'edit_theme_options', 'yellow-pencil-license', 'yp_option_func');
    }
    
    add_submenu_page( 'yellow-pencil-changes', __('Import/Export','yp'), __('Import/Export','yp'), 'edit_theme_options', 'yellow-pencil-export', 'yp_option_func' );

    if(!defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', __('Go Pro!','yp'), __('Go Pro!','yp'), 'edit_theme_options', 'yellow-pencil-license', 'yp_option_func');
    }

    add_submenu_page( 'yellow-pencil-changes', __('About','yp'), __('About','yp'), 'read', 'yp-welcome-screen', 'yp_welcome_screen_content' );

}

add_action('admin_menu', 'yp_add_setting_menu');


function yp_css_style_li($title,$type,$href,$id){

	if($type == 'type'){

		$linkType = '&yp_type=';
		$resetType = '&yp_reset_type=';

	}elseif($type == 'id'){

		$linkType = '&yp_id=';
		$resetType = '&yp_reset_id=';

	}elseif($type == 'global'){

		$linkType = '';
		$resetType = '&yp_reset_global=true';
		$id = null;

	}

	?>
	<li>

		<span class="yp-title"><?php echo $title; ?></span>
		<a class="yp-remove" onclick="return confirm('<?php _e("Are you sure you want to delete this data?","yp"); ?>')" href="<?php echo admin_url('admin.php?page=yellow-pencil-changes'.$resetType.''.$id.''); ?>"><span class="dashicons dashicons-no"></span></a>

		<a class="yp-customize" href="<?php echo admin_url('admin.php?page=yellow-pencil-editor&href='.yp_urlencode(esc_url($href)).''.$linkType.''.$id.''); ?>"><span class="dashicons dashicons-edit"></span></a>

		<span class="yp-clearfix"></span>

	</li>
	<?php
}



/* ---------------------------------------------------- */
/* Updating admin footer text 							*/
/* ---------------------------------------------------- */
function yp_admin_footer () {

	// Get screen
	$current_screen = get_current_screen();

	// if is Yellow Pencil page
	$is_yellow_pencil_screen = ( $current_screen && false !== strpos( $current_screen->base, 'yellow-pencil' ) );

	// if yellow pencil page
	if($is_yellow_pencil_screen){

		if(defined('WTFV')){
			echo 'Enjoyed <strong>Yellow Pencil</strong>? Please leave us a <a target="_blank" href="https://codecanyon.net/downloads">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support!';
		}else{
			echo 'Enjoyed <strong>Yellow Pencil</strong>? Please leave us a <a target="_blank" href="https://wordpress.org/support/plugin/yellow-pencil-visual-theme-customizer/reviews/?filter=5#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support!';
		}

	}


}

add_filter('admin_footer_text', 'yp_admin_footer');



/* ---------------------------------------------------- */
/* Update changes            							*/
/* ---------------------------------------------------- */
function yp_option_update(){

	// Can?
	if(current_user_can("edit_theme_options") == true){

		$activePage = 'yellow-pencil-changes';

		// Reset global data.
		if(isset($_GET['yp_reset_global'])){
			delete_option('wt_css');
			delete_option('wt_styles');
		}

		if(isset($_GET['yp_delete_animate'])){
			delete_option(trim(strip_tags(($_GET['yp_delete_animate']))));
			$activePage = 'yellow-pencil-animations';
		}

		// Reset Post type.
		if(isset($_GET['yp_reset_type'])){

			$reset_type = trim( strip_tags( $_GET['yp_reset_type'] ) );

			delete_option('wt_'.$reset_type.'_css');
			delete_option('wt_'.$reset_type.'_styles');
		}

		// Reset by id.
		if(isset($_GET['yp_reset_id'])){
			delete_post_meta(intval($_GET['yp_reset_id']),'_wt_css');
			delete_post_meta(intval($_GET['yp_reset_id']),'_wt_styles');
		}

		// Updated.
		if(isset($_GET['yp_reset_global']) || isset($_GET['yp_reset_id']) || isset($_GET['yp_reset_type']) || isset($_GET['yp_delete_animate'])){

			// Updating URL after delete
			wp_safe_redirect( admin_url('admin.php?page='.$activePage.'&yp_updated=true') );

			// Get All CSS data as ready-to-use
	        $output = yp_get_export_css("create");
	        
	        // Update custom.css file
	        yp_create_custom_css($output);
			
		}

		// Import the data
		if(isset($_POST['yp_json_import_data'])){

			$data = trim( strip_tags ( $_POST['yp_json_import_data'] ) );

			if(empty($data) == false){

				yp_import_data($data);

				// Get All CSS data as ready-to-use
				$output = yp_get_export_css("create");

				// Update custom.css file
				yp_create_custom_css($output);

			}

		}

		// Update output format.
		if(isset($_POST['yp-output-option'])){

			$value =  sanitize_key($_POST['yp-output-option']);

			if(!update_option('yp-output-option',$value)){
				add_option('yp-output-option',$value);
			}

		}

	}

}
add_action("admin_init","yp_option_update");


/* ---------------------------------------------------- */
/* Admin Control Functions  							*/
/* ---------------------------------------------------- */
function yp_option_func() {

		// GEt page, tab.
		$screen = get_current_screen();
		$active_tab = $screen->base;
		$active_tab = str_replace("yellow-pencil_page_", "", $active_tab);
		$active_tab = str_replace("toplevel_page_", "", $active_tab);

		// Updated message.
		if(isset($_GET['yp_updated']) || isset($_POST['yp-output-option']) || isset($_POST['yp_json_import_data'])){
			?>
				<div id="message" class="updated">
			        <p><strong><?php _e('Settings saved.') ?></strong></p>
			    </div>
			<?php
		}

        ?>
        <div class="wrap">

            <h2>Yellow Pencil Options</h2>

            <h2 class="nav-tab-wrapper">  
                <a href="?page=yellow-pencil-changes" class="nav-tab <?php echo $active_tab == 'yellow-pencil-changes' ? 'nav-tab-active' : ''; ?>">CSS Styles</a>
                <a href="?page=yellow-pencil-settings" class="nav-tab <?php echo $active_tab == 'yellow-pencil-settings' ? 'nav-tab-active' : ''; ?>">Settings</a> 
                <a href="?page=yellow-pencil-animations" class="nav-tab <?php echo $active_tab == 'yellow-pencil-animations' ? 'nav-tab-active' : ''; ?>">Custom Animations</a>
                <?php if(defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Product License</a>
                <?php } ?>
                <a href="?page=yellow-pencil-export" class="nav-tab <?php echo $active_tab == 'yellow-pencil-export' ? 'nav-tab-active' : ''; ?>">Import / Export</a>
                <?php if(!defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Go Pro</a>
                <?php } ?>
            </h2>

            <?php

            	
            	/* ---------------------------------------------------- */
				/* CSS CHANGES               							*/
				/* ---------------------------------------------------- */
                if( $active_tab == 'yellow-pencil-changes' ) {

                ?>

                	<div class="yp-tab-section">

                    <p><?php _e('All customized pages are listed below. You can delete and customize to them.','yp'); ?></p>

					<div class="yp-code-group">

					<ul>

						<?php

						$count = 0;

						// Global
						if(get_option("wt_css") != ''){
							$count = 1;
							yp_css_style_li('Global','global',get_home_url().'/',null);
						}

						// post types
						$post_types = get_post_types();
						foreach ($post_types as $post_type){

							if(get_option("wt_".$post_type."_css") != ''){

								$count = 1;

								$last_post = wp_get_recent_posts(array("post_status" => "publish","numberposts" => 1, "post_type" => $post_type));

								if(empty($last_post) == false){
									$last_post_id = $last_post['0']['ID'];
								}

								yp_css_style_li(__('Single','yp').' '.ucfirst($post_type).' '.__('Template','yp'),'type',get_the_permalink($last_post_id),$post_type);
	
							}

						}
						
						// home
						if(get_option("wt_home_css") != ''){

							$count = 1;

							$frontpage_id = get_option('page_on_front');

							if($frontpage_id == 0 || $frontpage_id == null){

								yp_css_style_li('Home Page','type',get_home_url().'/','home');
							
							}

						}

						
						// 404
						if(get_option("wt_404_css") != ''){

							$count = 1;

							yp_css_style_li('Search Template','type',get_home_url().'/?p=987654321','404');

						}

						// Search
						if(get_option("wt_search_css") != ''){

							$count = 1;

							yp_css_style_li('Search Template','type',get_home_url().'/?s='.yp_getting_last_post_title().'','search');
						
						}

						// Tag CSS
						if(get_option("wt_tag_css") != ''){

							$count = 1;

							$tag_id = '';
							$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
							if(empty($tags) == false){
								$tag_id = $tags[0];
							}

							yp_css_style_li('Tag Template','type',get_tag_link($tag_id),'tag');

						}

						// Category 
						if(get_option("wt_category_css") != ''){

							$count = 1;

							$cat_id = '';
							$cats = get_categories(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
							if(empty($cats) == false){
								$cat_id = $cats[0];
							}

							yp_css_style_li('Category Template','type',get_category_link($cat_id),'category');

						}
						
						// Author
						if(get_option("wt_author_css") != ''){

							$count = 1;

							yp_css_style_li('Author Template','type',get_author_posts_url(1),'author');

						}

						// Unknown
						global $wpdb;
						$querystr = "SELECT * FROM `$wpdb->postmeta` WHERE `meta_key` LIKE '_wt_css'";
						$pageposts = $wpdb->get_results($querystr, OBJECT);

						if($pageposts):

							global $post;

							foreach ($pageposts as $post):

							$id = $post->post_id;
							$title = "'".ucfirst(get_the_title($id))."'";

							if($title == "''"){
								$title = '(Unknown)';
							}

							if(get_post_meta($id, '_wt_css', true) != ''){

								$count = 1;

								yp_css_style_li($title.' '.ucfirst(get_post_type($id)),'id',get_the_permalink($id),$id);

							}

							endforeach;

						endif;
						wp_reset_query();

						// Count zero
						if(0 == $count){
							echo '<li>'.__("No CSS Style! First, Customize something on your website.","yp").'</li>';
						}

					?>

					</ul>

					<?php if($count > 0){ ?>
					<p><a href="<?php echo admin_url('admin.php?page=yellow-pencil-changes&yp_exportCSS=true'); ?>" class="button">Download</a> all style codes as ready to use.</p>	
					<?php } ?>

					</div>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* SETTINGS                 							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-settings' )  {

                	?>

                	<div class="yp-tab-section">

                	<h2>Output CSS Options</h2>
					<p>External CSS option still in beta test, Please use dynamic CSS option if there is an issue.</p>
					<form method="POST">
						<table class="form-table yp-form-table">
							<tbody>
							<tr>
								<?php

									$a = '';
									$b = '';
									if(get_option('yp-output-option') == 'external'){
										$a = 'checked="checked"';
									}

									if(get_option('yp-output-option') != 'external'){
										$b = 'checked="checked"';
									}

								?>
								<th><label><input name="yp-output-option" value="external" <?php echo $a; ?> type="radio"> Static External CSS File</label></th>
								<td><code><?php echo get_site_url(null,'custom.css'); ?></code></td>
							</tr>
							<tr>
								<th><label><input name="yp-output-option" value="inline" <?php echo $b; ?> type="radio"> Dynamic Inline CSS</label></th>
								<td><code>&lt;head&gt;&lt;style&gt;.body{color:gray...</code></td>
							</tr>
							</tbody>
						</table>
						<div class="yp-output-css-footer">
							<input type="submit" class="button-primary" value="Save Changes" />
						</div>
					</form>

				</div>

                	<?php


                /* ---------------------------------------------------- */
				/* ANIMATIONS               							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-animations' )  {

                    ?>

                    <div class="yp-tab-section">

                    <p><?php _e('Generated animations are listed below. You can manage them.','yp'); ?></p>

					<div class="yp-code-group">

					<ul>

						<?php

							$countAnim = 0;

							$all_options =  wp_load_alloptions();
							foreach($all_options as $name => $value){
								if(stristr($name, 'yp_anim')){
									$countAnim = $countAnim+1;
									$name = str_replace("yp_anim_", "", $name);
									?>
									<li>
									<span class="yp-title"><?php echo ucwords(strtolower($name)); ?></span>
									<a class="yp-remove" onclick="return confirm('<?php _e("Are you sure?","yp"); ?>')" href="<?php echo admin_url('admin.php?page=yellow-pencil-animations&yp_delete_animate=yp_anim_'.$name.''); ?>"><span class="dashicons dashicons-no"></span></a>
									<span class="yp-clearfix"></span>
									</li>
									<?php
								}
							}

							if(0 == $countAnim){
								echo '<li>'.__("No Custom Animation! First, Generate a few animations!","yp").'</li>';
							}

						?>
						

					</ul>

					</div>

					</div>

                    <?php


                /* ---------------------------------------------------- */
				/* LICENSE               							    */
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-license' )  {

                	// If isset product license, ie activation success.
                	if(isset($_GET['purchase_code']) == true){

                		// Purchase Code
                		$code = sanitize_key($_GET['purchase_code']);

                		// Adds Product code
                		if(!update_option("yp_purchase_code",$code)){
							add_option("yp_purchase_code",$code);
						}

                	}elseif(defined('WTFV') == false){

                		// Get purchase code from database
                		$purchase_code = get_option("yp_purchase_code");

                		// Has?
                		if($purchase_code){
                			delete_option('yp_purchase_code');
                		}

                	}

                	// Get purchase code from database
                	$purchase_code = get_option("yp_purchase_code");

                	$isActive = false;

                	// Button Text
                	if(isset($_GET['purchase_code']) || $purchase_code){

                		$isActive = true;
                		$activate_btn = 'Yellow Pencil Activated';
                		$aclink = '<a class="button button-primary button-hero yp-product-activation disabled">';

                	}else{
                		$activate_btn = 'Activate Yellow Pencil Pro';
                		$aclink = '<a class="button button-primary button-hero yp-product-activation" href="http://waspthemes.com/yellow-pencil/auto-update/?client-redirect='.urlencode(admin_url('admin.php?page=yellow-pencil-license')).'">';
                	}

                	// Thank you.
                	if(isset($_GET['purchase_code'])){
                		echo '<div class="updated"><p><strong>Yellow Pencil Pro successfully activated.</strong></p></div>';
                	}

                	// no license founded
                	if(isset($_GET['activation_error'])){
                		echo '<div class="error"><p><strong>No licenses found on your Envato account, <a href="http://waspthemes.com/yellow-pencil/buy/" target="_blank">Get your copy today</a>.</strong></p></div>';
                	}

                	?>

                	<div class="yp-activation-section <?php if(defined('WTFV') == false && $isActive == false){ echo 'yp-go-pro'; } ?>">

                		<?php if($isActive == false){ ?>

                			<?php if(defined('WTFV')){ ?>

	                			<p>In order to receive all benefits of Yellow Pencil, you need to activate your copy of the plugin. By activating Yellow Pencil License you will unlock <strong>premium features</strong> and <strong>direct plugin updates</strong>.</p>

	                		<?php }else{ ?>

	                			<h1>Unlock Premium Features!</h1>
	                			<p>Would you like to access all premium features? <strong>Font families</strong>, <strong>Backgrounds</strong>, <strong>Animations</strong> and much more. Go Pro today! First, buy a license and then activate it.</strong></p>

	                		<?php } ?>

	                	<?php }else{ ?>
	                		<?php if(defined('YP_PRO_DIRECTORY')){ ?>
	                			<p>You have activated Yellow Pencil Pro version which allows you to access all the customer benefits! You will be notified when new updates are available. Thank you for choosing Yellow Pencil!</p>
	                			<?php }else{ ?> 
	                			<p>You have activated Yellow Pencil Pro version which allows you to access all features! <strong>Font families</strong>, <strong>Backgrounds</strong>, and other all features are now available. Thank you for choosing Yellow Pencil!</p>
	                		<?php } ?>
	                	<?php } ?>
	                	
	                	<br />

	                    <p style="margin:0px;padding:0px;">

	                    	<?php if(defined('WTFV') == false && $isActive == false){ ?>
	                    	<a target="_blank" class="button button-hero" href="http://waspthemes.com/yellow-pencil/buy/" style="margin-right:20px;position:relative;top:1px;">Buy license</a>
	                    	<?php } ?>

	                    	<?php echo $aclink; ?><?php echo $activate_btn; ?></a>

	                    </p>

	                    <?php if($isActive == false){ ?>

	                    		<?php if(defined('WTFV')){ ?>
		                    		<p class='description'>Don't have license yet? <a href='http://waspthemes.com/yellow-pencil/buy/' target='_blank'>Purchase Yellow Pencil license</a>.</p>
		                    	<?php }else{ ?>
	                    			<p class='description'>Check out <a href='http://waspthemes.com/yellow-pencil/' target='_blank'>plugin website</a> for more information.</p>
		                    	<?php } ?>

						<?php }else{ ?>

							<p class='description'>Yellow Pencil Pro Successfully activated. <a href='<?php echo admin_url('admin.php?page=yp-welcome-screen'); ?>'>Let's Start</a></p>

						<?php } ?>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* EXPORT/IMPORT               							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-export' )  {

                    ?>

                    <div class="yp-tab-section">
	                    <h2>Export</h2>
						<p>Copy what appears to be a random string of alpha numeric characters in following text area<br />and paste into Import field on another web site.</p>
						<div class="yp-export-section">
							<textarea rows="6" class="yp-admin-textarea"><?php echo yp_get_export_data(); ?></textarea>
						</div>

						<hr style="margin-top: 50px;margin-bottom: 25px;">

						<h2>Import</h2>
						<p>Paste the exported data and click "Import Data" button.</p>
						<form method="POST">
							<div class="yp-import-section">
								<textarea name="yp_json_import_data" rows="6" class="yp-admin-textarea"></textarea>
							</div>
							<input type="submit" class="button" value="Import Data" />
						</form>
					</div>

                    <?php

                }

            ?>

        </div>

    <?php

}