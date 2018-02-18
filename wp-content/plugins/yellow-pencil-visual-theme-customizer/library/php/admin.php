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
	<h1>Welcome to YellowPencil <?php echo YP_VERSION; ?></h1>

	<div class="about-text yp-about-text">
		Congratulations! You are about to use most powerful design tool for WordPress ever.</div>
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
			<p>YellowPencil is Visual Style editor which can help you make styling changes on your website, even if you aren't web designer or developer. Easy to use, and it has live customizer and easy to use interface making you PRO in just couple minutes.</p>

			<p>You will become a professional web designer with YellowPencil!</p>

			<h3>Release Notes</h3>
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
				<li><a href="http://waspthemes.com/yellow-pencil/documentation/" target="_blank">Documentation</a></li>
				<li><a href="http://waspthemes.com/yellow-pencil/" target="_blank">Plugin Website</a></li>
				<li><a href="https://waspthemes.ticksy.com/" target="_blank">Official Support Forum</a></li>
				<li><a href="http://waspthemes.com/yellow-pencil/release-notes/" target="_blank">Release Notes</a></li>
			</ul>
			
		</div>

		<div class="yp-resources-right">

			<h3>Versions</h3>
			<ul>
				<li><a href="https://wordpress.org/plugins/yellow-pencil-visual-theme-customizer/" target="_blank">Get Free Version</a></li>
				<li><a href="http://codecanyon.net/item/yellow-pencil-visual-customizer-for-wordpress/11322180?ref=WaspThemes" target="_blank">Get Pro Version</a></li>
			</ul>

			<h3>Join Community</h3>
			<ul>
				<li><a href="https://www.facebook.com/groups/YellowPencils/" target="_blank">Facebook Community</a></li>
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
				<h4>Start Customizing!</h4>
				<p>Edit colors, fonts, sizes and all other styles with just a few clicks. <a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>">Start to customizing</a>.</p>
			</div>

		</div>

		<div class="yp-column">
			<img class="yp-img-center" src="<?php echo WT_PLUGIN_URL; ?>images/promo-2.png">
			
			<div class="yp-feature-column">
				<h4>Manage CSS Style</h4>

				<p>Keep track of all your styling changes from one place. You can find them  <a href="<?php echo admin_url("admin.php?page=yellow-pencil-changes"); ?>">here</a>.</p>
			</div>

		</div>

		<div class="yp-column">
			<img class="yp-img-center" src="<?php echo WT_PLUGIN_URL; ?>images/promo-3.png">
			
			<div class="yp-feature-column">
				<h4>Help & Support!</h4>
				<p>We recommend joining <a target="_blank" href="https://www.facebook.com/groups/YellowPencils/">Facebook Community</a> and checking out <a target="_blank" href="http://waspthemes.com/yellow-pencil/documentation/">The Plugin Docs</a> before starting customization.</p>
			</div>

		</div>

		<div class="clear"></div>

	</div>

	<p class="yp-thank-you">Thank you for choosing YellowPencil.</p>
	<?php } ?>

</div>
  <?php
}



/* ---------------------------------------------------- */
/* Adding plugin control menu 							*/
/* ---------------------------------------------------- */
function yp_add_setting_menu() {

    add_menu_page (
        'YellowPencil Options',
        'YellowPencil',
        'edit_theme_options',
        'yellow-pencil-changes',
        'yp_option_func',
        'dashicons-admin-customizer'
    );

    add_submenu_page( 'yellow-pencil-changes', "Customizations", "Customizations", 'edit_theme_options', 'yellow-pencil-changes', 'yp_option_func' );
    add_submenu_page( 'yellow-pencil-changes', "Settings", "Settings", 'edit_theme_options', 'yellow-pencil-settings', 'yp_option_func' );
    add_submenu_page( 'yellow-pencil-changes', "Custom Animations", "Custom Animations", 'edit_theme_options', 'yellow-pencil-animations', 'yp_option_func' );
    
    if(defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', "Product License", "Product License", 'edit_theme_options', 'yellow-pencil-license', 'yp_option_func');
    }
    
    add_submenu_page( 'yellow-pencil-changes', "Import/Export", "Import/Export", 'edit_theme_options', 'yellow-pencil-export', 'yp_option_func' );

    if(!defined("WTFV")){
    	add_submenu_page( 'yellow-pencil-changes', "Go Pro!", "Go Pro!", 'edit_theme_options', 'yellow-pencil-license', 'yp_option_func');
    }

    add_submenu_page( 'yellow-pencil-changes', "About", "About", 'read', 'yp-welcome-screen', 'yp_welcome_screen_content' );

}

add_action('admin_menu', 'yp_add_setting_menu');


function yp_css_style_li($title, $href, $type, $page_id = null, $page_type = null){

	$key = "";
	$plusTitle = "";

	// Reset links
	if($type == 'single'){

		$key = $page_id;
		$data = get_post_meta($page_id, '_wt_css', true);

		$frontID = get_option('page_on_front');
    	$blogID = get_option('page_for_posts');

		if($page_id == $frontID){
			$plusTitle = " — Front Page";
		}

		if($page_id == $blogID){
			$plusTitle = " — Posts Page";
		}

		$deleteData = "ID|".$page_id;
	}elseif($type == 'template'){
		$data = get_option("wt_".$page_type."_css");
		$key = $page_type;
		$deleteData = "TYPE|".$page_type;
	}elseif($type == 'global'){
		$data = get_option("wt_css");
		$key = "global";
		$deleteData = "GLOBAL|"."global";
	}

	$class = "";
	if(strstr($title, "Inactive")){
		$class = " class='yp-inactive-style'";
	}

	if($title == ""){
		$title = "Unknown";
	}

	?>
	<li<?php echo $class; ?> data-delete-value="<?php echo $deleteData; ?>">

		<span class="yp-edited-page-title"><?php echo $title; echo $plusTitle; ?></span>

		<a class="yp-delete-page-edits" title="Delete"></a>

		<?php if($href != null){ ?>
		<a class="yp-open-in-editor" title="Open With YellowPencil" target="_blank" href="<?php echo admin_url('admin.php?page=yellow-pencil-editor&href='.yp_urlencode(esc_url($href)).''); ?>&#38;yp_page_id=<?php echo $page_id; ?>&#38;yp_page_type=<?php echo $page_type; ?>&#38;yp_mode=<?php echo $type; ?>"></a>
		<?php } ?>
		
		<a class="yp-show-css"></a>

		<span class="yp-clear"></span>

	</li>
	<div id="yp-inline-editor-<?php echo $key; ?>" class='yp-inline-css'><?php echo stripslashes($data); ?></div>
	<?php
}



/* ---------------------------------------------------- */
/* Updating admin footer text 							*/
/* ---------------------------------------------------- */
function yp_admin_footer () {

	// Get screen
	$current_screen = get_current_screen();

	// if is YellowPencil page
	$is_yellow_pencil_screen = ( $current_screen && false !== strpos( $current_screen->base, 'yellow-pencil' ) );

	// if YellowPencil page
	if($is_yellow_pencil_screen){

		if(defined('WTFV')){
			echo 'Enjoyed <strong>YellowPencil</strong>? Please leave us a <a target="_blank" href="https://codecanyon.net/downloads">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support!';
		}else{
			echo 'Enjoyed <strong>YellowPencil</strong>? Please leave us a <a target="_blank" href="https://wordpress.org/support/plugin/yellow-pencil-visual-theme-customizer/reviews/?filter=5#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support';
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
		$active_tab = str_replace("yellowpencil_page_", "", $active_tab);
		$active_tab = str_replace("toplevel_page_", "", $active_tab);

		// Updated message.
		if(isset($_GET['yp_updated']) || isset($_POST['yp-output-option']) || isset($_POST['yp_json_import_data'])){
			?>
				<div id="message" class="updated">
			        <p><strong>Settings saved.</strong></p>
			    </div>
			<?php
		}

        ?>
        <div class="wrap">

            <h2>YellowPencil</h2>

            <h2 class="nav-tab-wrapper yp-tab-wrapper">  
                <a href="?page=yellow-pencil-changes" class="nav-tab <?php echo $active_tab == 'yellow-pencil-changes' ? 'nav-tab-active' : ''; ?>">Customizations</a>
                <a href="?page=yellow-pencil-settings" class="nav-tab <?php echo $active_tab == 'yellow-pencil-settings' ? 'nav-tab-active' : ''; ?>">Settings</a> 
                <a href="?page=yellow-pencil-animations" class="nav-tab <?php echo $active_tab == 'yellow-pencil-animations' ? 'nav-tab-active' : ''; ?>">Custom Animations</a>
                <?php if(defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Product License</a>
                <?php } ?>
                <a href="?page=yellow-pencil-export" class="nav-tab <?php echo $active_tab == 'yellow-pencil-export' ? 'nav-tab-active' : ''; ?>">Import / Export</a>
                <?php if(!defined('WTFV')){ // this tab available just on pro version ?>
                <a href="?page=yellow-pencil-license" class="yp-license-tab nav-tab <?php echo $active_tab == 'yellow-pencil-license' ? 'nav-tab-active' : ''; ?>">Go Pro</a>
                <?php } ?>
            </h2>

            <?php

            	// Base directory for Ace Editor
                echo "<script>window.aceEditorBase = '".(plugins_url( 'library/ace/' , __FILE__ ))."';</script>";
            	
            	/* ---------------------------------------------------- */
				/* CSS CHANGES               							*/
				/* ---------------------------------------------------- */
                if( $active_tab == 'yellow-pencil-changes' ) {

                ?>	

                	<div class="yp-no-code">
                		<img src="<?php echo WT_PLUGIN_URL; ?>images/pages.png">
                    	<p>There is no style applied. <a href="<?php echo admin_url('themes.php?page=yellow-pencil'); ?>">Let's start!</a></p>
                    </div>

                	<div class="yp-tab-section">

            		<h2>Manage Styles</h2>
                    <p class="yp-heading-text">All the changes you've made with YellowPencil are listed below. You can review, edit or delete them.</p>

					<div class="yp-code-group">

					<div class="yp-global-group">
						<h3>Global Customization</h3>
						<ul>
						<?php

							$allCount = 0;
							$count = 0;

							// Global
							if(get_option("wt_css") != ''){
								$count++;
								$allCount++;

								$frontpage_id = get_option('page_on_front');
								$frontpage_type = 'home';

								// If no home page id, use only home page editing method.
								if($frontpage_id == 0 || $frontpage_id == null){
									$frontpage_id = 'home';
								}else{
									$frontpage_type = get_post_type($frontpage_id);
								}

								yp_css_style_li("Global", get_home_url().'/', 'global', $frontpage_id, $frontpage_type);

							}

						?>
						</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.yp-global-group{display:none;}</style>";
						}
					?>

					<div class="yp-template-group">
						<h3>Template Customizations</h3>
						<ul>
						<?php

							$count = 0;

							// Post Types
							$post_types = get_post_types();

							// Using shop post type for WooCommerce shop page.
							array_push($post_types, "shop");

							foreach ($post_types as $post_type){

								if(get_option("wt_".$post_type."_css") != ''){

									$count++;
									$allCount++;

									if($post_type == "shop" && class_exists('WooCommerce')){

										$last_post_id = wc_get_page_id('shop');

										$link = get_the_permalink($last_post_id);

										$title = ucfirst($post_type).' page';

									}else{

										// get last post
										$last_post = wp_get_recent_posts(array("post_status" => "publish","numberposts" => 1, "post_type" => $post_type));

										// checks and get
										if(empty($last_post) == false){
											$last_post_id = $last_post['0']['ID'];
											$link = get_the_permalink($last_post_id);
										}

										// if no id, leave it empty and not show a edit link
										if(isset($last_post_id) == false){
											$last_post_id = 0;
											$link = null;
										}

										$title = ucfirst($post_type).' template';

									}

									yp_css_style_li($title, $link, 'template', $last_post_id, $post_type);
		
								}

							}

							// Search Template
							if(get_option("wt_search_css") != ''){

								$count++;
								$allCount++;

								yp_css_style_li("Search template", get_home_url().'/?s='.yp_getting_last_post_title(), 'template', 'search', 'search');
							
							}

							// Tag Template
							if(get_option("wt_tag_css") != ''){

								$count++;
								$allCount++;

								$tag_id = '';
								$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
								if(empty($tags) == false){
									$tag_id = $tags[0];
								}

								yp_css_style_li("Tag template", get_tag_link($tag_id), 'template', 'tag', 'tag');

							}

							// Category Template
							if(get_option("wt_category_css") != ''){

								$count++;
								$allCount++;

								$cat_id = '';
								$cats = get_categories(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
								if(empty($cats) == false){
									$cat_id = $cats[0];
								}

								yp_css_style_li("Category template", get_category_link($cat_id), 'template', 'category', 'category');

							}

							// Archive Template
							if(get_option("wt_archive_css") != ''){

								$count++;
								$allCount++;

								$latest_post = get_posts("post_type=post&numberposts=1");
								$latest_post_id = $latest_post[0]->ID;
								$last_post_date = get_the_date("Y",$latest_post_id);
								$archive_link = get_home_url()."/".$last_post_date;

								yp_css_style_li("Archive template", $archive_link, 'template', 'archive', 'archive');

							}
							
							// Author Template
							if(get_option("wt_author_css") != ''){

								$count++;
								$allCount++;

								yp_css_style_li("Author template", get_author_posts_url(1), 'template', 'author', 'author');

							}

							// 404 Template
							if(get_option("wt_404_css") != ''){

								$count++;
								$allCount++;

								yp_css_style_li("404 error template", get_home_url().'/?p=987654321', 'template', '404', '404');

							}
						?>
						</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.yp-template-group{display:none;}</style>";
						}
					?>


					<div class="yp-single-group">
						<h3>Single Customizations</h3>
						<ul>
						<?php

						$count = 0;

						// Homepage
						if(get_option("wt_home_css") != ''){

							$count++;
							$allCount++;

							$frontpage_id = get_option('page_on_front');

							if($frontpage_id == 0 || $frontpage_id == null){
								yp_css_style_li("Non-Static Homepage", get_home_url().'/', 'template', 'home', 'home');
							}else{
								yp_css_style_li("Non-Static Homepage".'<small>(Inactive)</small>', get_home_url().'/', 'template', 'home', 'home');
							}

						}

						// Unknown Customizations
						global $wpdb;
						$querystr = "SELECT * FROM `$wpdb->postmeta` WHERE `meta_key` LIKE '_wt_css'";
						$pageposts = $wpdb->get_results($querystr, OBJECT);

						if($pageposts):

							global $post;

							foreach ($pageposts as $post):

							$id = $post->post_id;
							$title = ucfirst(get_the_title($id));

							if($title == "''"){
								$title = '(Unknown)';
							}

							if(get_post_meta($id, '_wt_css', true) != ''){

								$count++;
								$allCount++;

								yp_css_style_li($title, get_the_permalink($id), 'single', $id, get_post_type($id));

							}

							endforeach;

						endif;

						wp_reset_query();

						?>
					</ul>
					</div>
					<?php
						if($count == 0){
							echo "<style>.yp-single-group{display:none;}</style>";
						}
					?>

					<!-- Shows download button -->
					<?php if($allCount > 0){ ?>
						<p><a href="<?php echo admin_url("admin.php?page=yellow-pencil-changes&yp_exportCSS=true"); ?>" class="button button-primary">Download CSS File</a> all styles as ready to use.</p>
					<?php }else{echo "<style>.yp-tab-section{display:none;}.yp-no-code{display:block}</style>";} ?>

					</div>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* SETTINGS                 							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-settings' )  {

                	?>

                	<div class="yp-tab-section">

                	<h2>CSS Print Method</h2>
					<p class="yp-heading-text">External CSS still in beta test, Please use dynamic CSS if there is an issue.</p>
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

                    <div class="yp-no-animation">
	                	<img src="<?php echo WT_PLUGIN_URL; ?>images/pages.png">
	                    <p>There is no generated animation. You can create animation by use Animation Generator in the editor.</a></p>
	                </div>

                    <div class="yp-tab-section">

	                    <div class="yp-global-group">

		                    <h2>Manage Animations</h2>
		                    <p class="yp-heading-text">Generated animations are listed below, You can review and delete them.</p>

							<div class="yp-code-group">

								<ul>

									<?php

										$countAnim = 0;

										$all_options =  wp_load_alloptions();
										foreach($all_options as $name => $value){
											if(stristr($name, 'yp_anim')){
												$countAnim = $countAnim+1;
												$name = str_replace("yp_anim_", "", $name);
												$deleteData = "yp_anim_".$name;

												$value = stripslashes($value);

												$value = str_replace("{", "{\n\t", $value);
												$value = str_replace("}", "\n}\n", $value);
												$value = str_replace(";", ";\n\t", $value);
												$value = str_replace("\t\n}", "}", $value);

												?>
												<li data-delete-value="<?php echo $deleteData; ?>">

												<span class="yp-edited-page-title"><?php echo ucwords(strtolower($name)); ?></span>

												<a class="yp-delete-page-edits" title="Delete"></a>

												<a class="yp-show-css"></a>

												<span class="yp-clear"></span>

												</li>
												<div id="yp-inline-editor-<?php echo $name; ?>" class='yp-inline-css'><?php echo $value; ?></div>
												<?php
											}
										}

									?>

								</ul>

							</div>

						</div>

						<?php

							if(0 == $countAnim){
								echo '<style>.yp-global-group{display:none;}.yp-no-animation{display:block;}</style>';
							}

						?>

					</div>

                    <?php


                /* ---------------------------------------------------- */
				/* LICENSE               							    */
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-license' )  {

                	// Delete license key
                	if(isset($_GET["yp-disable-license"])){
                		delete_option('yp_purchase_code');
                	}

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
                		$activate_btn = "YellowPencil Activated";
                		$aclink = '<a class="button button-primary button-hero yp-product-activation disabled">';
                		$disableLink = '<div><a class="yp-disable-license" href="'.admin_url('admin.php?page=yellow-pencil-license&yp-disable-license=true').'">Disable License!</a></div>';

                	}else{
                		$activate_btn = "Activate YellowPencil Pro";
                		$aclink = '<a class="button button-primary button-hero yp-product-activation" href="http://waspthemes.com/yellow-pencil/auto-update/?client-redirect='.urlencode(admin_url('admin.php?page=yellow-pencil-license')).'">';
                		$disableLink = '';
                	}

                	// Thank you.
                	if(isset($_GET['purchase_code'])){
                		echo '<div class="updated"><p><strong>YellowPencil Pro successfully activated.</strong></p></div>';
                	}

                	// no license founded
                	if(isset($_GET['activation_error'])){
                		echo '<div class="error"><p><strong>No licenses found on your Envato account, <a href="http://waspthemes.com/yellow-pencil/buy/" target="_blank">Get your copy today</a>.</strong></p></div>';
                	}

                	?>

                	<div class="yp-activation-section <?php if(defined('WTFV') == false && $isActive == false){ echo 'yp-go-pro'; } ?>">

                		<?php if($isActive == false){ ?>

                			<?php if(defined('WTFV')){ ?>

                				<h2>Activate Your Copy!</h2>
	                			<p>In order to receive all benefits of YellowPencil, you need to activate your copy of the plugin. By activating YellowPencil License you will unlock <strong>premium features</strong> and <strong>direct plugin updates</strong>.</p>

	                		<?php }else{ ?>

	                			<h1>Unlock Premium Features!</h1>
	                			<p>Would you like to access all premium features? <strong>Font families</strong>, <strong>Backgrounds</strong>, <strong>Animations</strong> and much more. Go Pro today! First, buy a license and then activate it.</strong></p>

	                		<?php } ?>

	                	<?php }else{ ?>
	                		<?php if(defined('YP_PRO_DIRECTORY')){ ?>
	                			<h2>Welcome To Pro Club!</h2>
	                			<p>You have activated YellowPencil Pro version which allows you to access all the customer benefits! You will be notified when new updates are available. Thank you for choosing YellowPencil!</p>
	                			<?php }else{ ?> 
	                			<h2>Welcome To Pro Club!</h2>
	                			<p>You have activated YellowPencil Pro version which allows you to access all features! <strong>Font families</strong>, <strong>Backgrounds</strong>, and other all features are now available. Thank you for choosing YellowPencil!</p>
	                		<?php } ?>
	                	<?php } ?>
	                	
	                	<br />

	                    <p style="margin:0px;padding:0px;">

	                    	<?php if(defined('WTFV') == false && $isActive == false){ ?>
	                    	<a target="_blank" class="button button-hero" href="http://waspthemes.com/yellow-pencil/buy/" style="margin-right:20px;position:relative;top:1px;">Buy license</a>
	                    	<?php } ?>

	                    	<?php echo $aclink; ?><?php echo $activate_btn; ?></a>

	                    	<?php echo $disableLink; ?>

	                    </p>

	                    <?php if($isActive == false){ ?>

	                    		<?php if(defined('WTFV')){ ?>
		                    		<p class='description'>Don't have the license yet? <a href='http://waspthemes.com/yellow-pencil/buy/' target='_blank'>Purchase a license</a>!</p>
		                    	<?php }else{ ?>
		                    		<p class='description'>Check out <a href='http://waspthemes.com/yellow-pencil/' target='_blank'>plugin website</a> for more information.</p>
		                    	<?php } ?>

						<?php }else{ ?>

							<p class='description'>YellowPencil Pro Successfully activated. <a href='<?php echo admin_url('admin.php?page=yp-welcome-screen'); ?>'>Let's Start!</a></p>

						<?php } ?>

					</div>

					<?php


				/* ---------------------------------------------------- */
				/* EXPORT/IMPORT               							*/
				/* ---------------------------------------------------- */
                } elseif( $active_tab == 'yellow-pencil-export' )  {

                    ?>

                    <div class="yp-tab-section">
	                    <h2>Export Project</h2>
						<p class="yp-heading-text">Copy what appears to be a random string of alpha numeric characters in following text area<br />and paste into Import field on another web site.</p>
						<div class="yp-export-section">
							<textarea rows="6" class="yp-admin-textarea"><?php echo yp_get_export_data(); ?></textarea>
						</div>

						<hr style="margin-top: 50px;margin-bottom: 25px;">

						<h2>Import Project</h2>
						<p class="yp-heading-text">Paste the exported data and click "Import Data" button.</p>
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