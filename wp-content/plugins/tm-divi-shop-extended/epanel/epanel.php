<?php

	// Adds admin menu on top level in dashboard left panel
	add_action( 'admin_menu', 'tm_admin_menu' );
	function tm_admin_menu() {
		add_menu_page( 'Divi Shop Options', 'Divi Shop Extended', 'manage_options', 'divi-shop-ex-page.php', 'tm_admin_page', TM_PLUGIN_URI . '/epanel/img/logo.png', 6  );
	}

	// Shows the page
	function tm_admin_page(){

		// get settings
		include_once(TM_PLUGIN_DIR . 'epanel/options.php');
		
		?>

		<div id="wrapper" class="divi-shop-ex-form">
			<div id="panel-wrap">
				<form action="" method="POST" id="shop-ex-form" name="shop-ex-form" autocomplete="false">
					<div id="epanel-top">
						<button class="save-button" id="epanel-save-top">Save Changes</button>
					</div><!-- #epanel-top -->

                    <div id="epanel-wrapper">
                        <div id="epanel">
                            <div id="epanel-content-wrap">
                                <div id="epanel-content">
                                    
                                    <div id="epanel-header">
                                    	<div id="epanel-logo"></div>
										<!-- Title -->
                                        <?php if ( $header_title ) { ?>
                                            <h1 id="epanel-title"><?php echo esc_html( $header_title ); ?></h1>
                                        <?php } ?>
                                        <!-- /Title -->
                                    </div><!-- #epanel-header --> 

									
									<div id="wrap-general" class="content-div">
                                        
                                        <!-- Option Tabs -->        
                                        <ul class="idTabs">
                                            <?php
                                            $tab_count = count( $tabs );
                                            foreach($tabs as $tab){ ?>
                                                	<li class="ui-tabs <?php if($tab['id']==='products') echo 'ui-tabs-active';?>" content="<?php _e( $tab['id'] ); ?>">
                                                    	<a href="#shop-<?php _e( $tab['id'] ); ?>"><?php _e( $tab['desc'] ); ?></a>
                                                	</li>
                                            <?php
                                            } ?>
                                        </ul><!-- .idTabs -->
                                                        
                                        <!-- generate field -->
										<?php
	                                        foreach( $tabs as $tab){ ?>
	                                            <div class="tab-content <?php if($tab['id'] === 'products') echo 'tab-active';?> <?php _e( $tab['id'] ); ?>" tab="<?php _e( $tab['id'] ); ?>">
	                                                <?php
	                                                if ( ! empty( $settings ) ) {
	                                                    foreach ( $settings as $setting ) {
	                                                    	
	                                                        // Print fields asociated to this tab

	                                                        if($tab['id'] === $setting['tab_id']){?>

	                                                            <div class="epanel-box" data-type="<?php echo esc_attr( $setting['type'] ); ?>">
	                                                                <?php if($setting['type'] !== 'label-header'){ ?>
																		<div class="box-title">
																			<?php
																				if ( $setting['name'] ) {
																					printf(
																						'<h3>%1$s</h3>',
																						esc_attr( $setting['name'] )
																					);
																				}

																				if ( $setting['desc' ]  ) {
																					printf(
																						'<div class="box-descr"><p>%1$s</p></div><!-- .box-descr -->',
																						esc_attr( $setting['desc'] )
																					);
																				}
																			?>
																		</div><!-- .box-title -->

																		<!-- FIELD -->
																		<div class="box-content">

																			<?php render_field($setting); ?>

																		</div><!-- .box-content -->
																		
																		<span class="box-description" label-name="<?php echo $help_header; ?>"></span>
																	<?php } else { ?>
																		<div class="epanel-label">
																			<h3><?php echo $setting['name'];?></h3>
																		</div>

																	<?php } ?>
																</div><!-- .epanel-box -->

	                                                            <?php
	                                                        }  // /if
	                                                    } // /foreach
	                                                } // /if
	                                                ?>
	                                            </div> <!-- #tab-content -->
	                                    <?php } ?>

                                        <!--/generate field -->



                                    </div><!-- #wrap-general -->



								</div><!-- #epanel-content -->
							</div><!-- #epanel-content-wrap -->
						</div><!-- #epanel -->
					</div><!-- #epanel-wrapper -->	

					<div id="epanel-bottom">
                    	<button class="save-button" id="epanel-save-top" name="epanel-save-button">Save Changes</button>
                	</div><!-- #epanel-bottom -->	
				</form>
			</div><!-- #panel-wrap -->
		</div><!-- #wrapper -->
		<div id="form-msg-box"></div><!-- for display form messages -->
		<?php

		
	}

	// script and style
		function tm_epanel_enqueue_scripts(){
			wp_enqueue_script(
				'tm-epanel-admin-script',
				TM_PLUGIN_URI . '/epanel/js/admin-script.js',
				array(),
				null,
				true
			);
			wp_enqueue_script(
				'tm-epanel-color-picker',
				TM_PLUGIN_URI . '/epanel/js/wp-color-picker-alpha.min.js',
				array('wp-color-picker'),
				'1.0.0', 
				true
			);
		}
		
		function tm_epanel_enqueue_styles(){
			wp_enqueue_style(
					'tm-epanel-admin-style',
					TM_PLUGIN_URI . '/epanel/css/admin-style.css', 
					array()
			);
			wp_enqueue_style( 'wp-color-picker' );
			
			// WP 4.9+ Color Picker fix
			global $wp_version;
			if(version_compare($wp_version, '4.9', '>=')){
				wp_enqueue_style(
					'tm-epanel-color-picker-fix',
					TM_PLUGIN_URI . '/epanel/css/wp-4.9-color-picker-fix.css', 
					array()
				);
			}
		}
			

		function enqueue_my_scripts($hook) {
		    if ( 'toplevel_page_divi-shop-ex-page' != $hook ) {
		        return;
		    }
		    // ENQUEUE SCRIPTS…
		    tm_epanel_enqueue_scripts();
		   	tm_epanel_enqueue_styles();
		    
		}
		add_action( 'admin_enqueue_scripts', 'enqueue_my_scripts' );
?>