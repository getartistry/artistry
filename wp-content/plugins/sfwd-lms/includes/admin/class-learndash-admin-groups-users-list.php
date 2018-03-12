<?php
if (!class_exists('Learndash_Admin_Groups_Users_List')) {
	class Learndash_Admin_Groups_Users_List {
		
		var $list_table;
		var $form_method 	=	'get';
		var $title 			= 	'';

		var $current_action =	'';
		var $group_id 		= 	0;
		var $user_id 		= 	0;
		
		function __construct() {
			//add_action( 'load-edit.php', array( $this, 'on_load_groups') );
			
			add_action( 'admin_menu', array( $this, 'learndash_group_admin_menu' ) );
		}
		
		/*
		function on_load_groups() {
			
			if ( ( isset( $_GET['post_type'] ) ) && ( $_GET['post_type'] == 'groups' ) ) {

				add_filter( 'manage_groups_posts_columns', array( $this, 'set_groups_columns' ) );
				add_action( 'manage_groups_posts_custom_column' , array( $this, 'display_groups_columns' ), 10, 2 );

			}			
		}
		*/

		/*
		function set_groups_columns($columns) {

			$columns_new = array();
			
			foreach( $columns as $col_key => $col_label ) {
				if ($col_key == 'date') {
					$columns_new['groups_group_leaders'] = esc_html__('Group Leaders', 'learndash');
					$columns_new['groups_group_courses'] = sprintf( esc_html__('Group %s', 'Group Courses', 'learndash'), LearnDash_Custom_Label::get_label( 'courses' ));
					$columns_new['groups_group_users'] = esc_html__('Group Users', 'learndash');
				}
				$columns_new[$col_key] = $col_label;
			}
			return $columns_new;
			
		}
		*/
		/*
		function display_groups_columns( $column_name, $group_id ) {
		    switch ( $column_name ) {

		        case 'groups_group_leaders':
					$group_leaders = learndash_get_groups_administrator_ids( $group_id );
					if ( ( empty( $group_leaders ) ) || ( !is_array( $group_leaders ) ) ) {
						$group_leaders = array();
					}
					
					echo  sprintf(__('Total %s', 'learndash'), count( $group_leaders ) );
					
					if ( !empty( $group_leaders ) ) {
						$user_names = '';
						
						if ( count( $group_leaders ) > 5 ) {
							$group_leaders = array_slice( $group_leaders, 0, 5);
						}
						
						foreach( $group_leaders as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							if ( !empty( $user_names ) ) $user_names .= ', ';
							$user_names .= '<a href="'. get_edit_user_link( $user_id ) .'">'. $user->display_name .' ('.$user->user_login.')' .'</a>';
						}
						
						if ( !empty( $user_names ) )
							echo '<br />' . $user_names;
					} 
		            break;

		        case 'groups_group_users':
					$group_users = learndash_get_groups_user_ids( $group_id );
					if ( ( empty( $group_users ) ) || ( !is_array( $group_users ) ) ) {
						$group_users = array();
					}
					
					echo sprintf(__('Total %s', 'learndash'), count( $group_users ) );
				
					if ( !empty( $group_users ) ) {
						$user_names = '';

						if ( count( $group_users ) > 5 ) {
							$group_users = array_slice( $group_users, 0, 5 );
						}
					
						foreach( $group_users as $user_id ) {
							$user = get_user_by( 'id', $user_id );
							if ( !empty( $user_names ) ) $user_names .= ', ';
							$user_names .= '<a href="'. get_edit_user_link( $user_id ) .'">'. $user->display_name .' ('.$user->user_login.')' .'</a>';
						}
						
						if ( !empty( $user_names ) )
							echo '<br />'. $user_names;
					}
		            break;

		        case 'groups_group_courses':
					$group_courses = learndash_group_enrolled_courses( $group_id );
					if ( ( empty( $group_courses ) ) || ( !is_array( $group_courses ) ) ) {
						$group_courses = array();
					}
					
					echo sprintf(__('Total %s', 'learndash'), count( $group_courses ) );
					
					if ( !empty( $group_courses ) ) {

						$course_names = '';
						if ( count( $group_courses ) > 5 ) {
							$group_courses = array_slice( $group_courses, 0, 5 );
						}
				
						foreach( $group_courses as $course_id ) {
							
							if ( !empty( $course_names ) ) $course_names .= ', ';
							$course_names .= '<a href="'. get_edit_post_link( $course_id ) .'">'. get_the_title( $course_id ) .'</a>';
						}

						if ( !empty( $course_names ) )
							echo '<br />'. $course_names;
					}
		            break;


		    }
		}
		*/
		
		/**
		 * Register Group Administration submenu page
		 * 
		 * @since 2.1.0
		 */
		function learndash_group_admin_menu() {

			$menu_user_cap = '';
	
			if ( learndash_is_admin_user() ) {
				$user_group_ids = learndash_get_administrators_group_ids( get_current_user_id(), true );
				if ( !empty( $user_group_ids ) ) {
					$menu_user_cap = LEARNDASH_ADMIN_CAPABILITY_CHECK;
				}
			} else if ( learndash_is_group_leader_user() ) {
				$menu_user_cap = LEARNDASH_GROUP_LEADER_CAPABILITY_CHECK;
			}
			
			if ( !empty( $menu_user_cap ) ) {

				$pagehook = add_submenu_page( 
					'learndash-lms', 
					esc_html__( 'Group Administration', 'learndash' ),
					esc_html__( 'Group Administration', 'learndash' ),
					$menu_user_cap, 
					'group_admin_page', 
					array( $this, 'show_page') 
				);
				add_action( 'load-'. $pagehook, array( $this, 'on_load') );	
			}
		}

		function on_load() {

			if ( ( isset( $_GET['action'] ) ) && ( !empty( $_GET['action'] ) ) ) {
				$this->current_action = esc_attr( $_GET['action'] );
				//$this->current_action = $this->list_table->current_action();
			}

			if ( ( isset( $_GET['group_id'] ) ) && ( !empty( $_GET['group_id'] ) ) ) {
				$this->group_id = intval($_GET['group_id']);
			}
			
			if ( ( isset( $_GET['user_id'] ) ) && ( !empty( $_GET['user_id'] ) ) ) {
				$this->user_id 	= intval($_GET['user_id']);
			}

			wp_enqueue_style( 
				'sfwd-module-style', 
				LEARNDASH_LMS_PLUGIN_URL . '/assets/css/sfwd_module'. ( ( defined( 'LEARNDASH_SCRIPT_DEBUG' ) && ( LEARNDASH_SCRIPT_DEBUG === true ) ) ? '' : '.min') .'.css', 
				array(), 
				LEARNDASH_SCRIPT_VERSION_TOKEN 
			);
			$learndash_assets_loaded['styles']['sfwd-module-style'] = __FUNCTION__;

			wp_enqueue_script( 
				'sfwd-module-script', 
				LEARNDASH_LMS_PLUGIN_URL . '/assets/js/sfwd_module'. ( ( defined( 'LEARNDASH_SCRIPT_DEBUG' ) && ( LEARNDASH_SCRIPT_DEBUG === true ) ) ? '' : '.min') .'.js', 
				array( 'jquery' ), 
				LEARNDASH_SCRIPT_VERSION_TOKEN,
				true 
			);
			$learndash_assets_loaded['scripts']['sfwd-module-script'] = __FUNCTION__;

			wp_localize_script( 'sfwd-module-script', 'sfwd_data', array() );


			if ( empty( $this->current_action ) ) {

				require_once( LEARNDASH_LMS_PLUGIN_DIR .'includes/admin/class-learndash-admin-groups-users-list-table.php' );
				$this->list_table = new Learndash_Admin_Groups_Users_List_Table();
				$screen = get_current_screen();
			
				$screen_key = $screen->id;
				if (!empty($this->group_id)) {
					$screen_key .= '_users';
				} else {
					$screen_key .= '_groups';
				}
				$screen_key .= '_per_page';
			
				$screen_per_page_option = str_replace( '-', '_', $screen_key );
			
				if ( isset( $_POST['wp_screen_options']['option'] ) ) {
	
					if ( isset($_POST['wp_screen_options']['value'] ) ) {
						$per_page = intval( $_POST['wp_screen_options']['value'] );
						if ((!$per_page) || ($per_page < 1)) {
							$per_page = 20;
						}
						update_user_meta(get_current_user_id(), $screen_per_page_option, $per_page);
					}
				}
				$per_page = get_user_meta(get_current_user_id(), $screen_per_page_option, true);
				if ( (!$per_page) || (empty($per_page)) || ($per_page < 1) ) {
					$per_page = 20;
				}
			
				$this->list_table->per_page = $per_page;
				add_screen_option( 'per_page', array('label' => esc_html__('per Page', 'learndash' ), 'default' => $per_page) );
						
				if ( ( !empty( $this->group_id ) ) && ( !empty( $this->user_id ) ) ) {
				
					$this->on_process_actions_list();					
				
					$this->form_method = 'post';
				
					$user           = get_user_by( 'id', $this->user_id );
			
					$this->title = esc_html__( 'Group Administration', 'learndash' ) . ': ';
				
					if (learndash_is_admin_user()) {
						$this->title .= '<a tite="'. esc_html__('Edit User', 'learndash') .'" href="'. get_edit_user_link( $user->ID ).'">';
					}
				
					$this->title .= $user->display_name;
				
					if (learndash_is_admin_user()) {
						$this->title .= '</a>';
					}
				
					$this->title .= ' <small>| <a href="' . remove_query_arg( array('user_id', 's', 'paged', 'learndash-search', 'ld-group-list-view-nonce', '_wp_http_referer', '_wpnonce')) . '">' . esc_html__( 'Back', 'learndash' ) . '</a></small>';
					return;
				} else if ( !empty( $this->group_id ) ) {
					$group_post = get_post( $this->group_id );
					if ($group_post) {
						$this->title = esc_html__( 'Group Administration', 'learndash' ) . ': ';
					
						if (learndash_is_admin_user()) {
							$this->title .= '<a title="'. esc_html__('Edit Group', 'learndash') .'" href="'. get_edit_post_link( $this->group_id ).'">';
						}
					
						$this->title .= $group_post->post_title;
					
						if (learndash_is_admin_user()) {
							$this->title .= '</a>';
						}
					
						$this->title .= ' <small><a href="'. remove_query_arg( array('group_id', 's', 'paged', 'learndash-search', 'ld-group-list-view-nonce', '_wp_http_referer', '_wpnonce') ) .'">'. esc_html__( 'Back', 'learndash' ) .'</a></small>';
				
						$this->list_table->group_id = $this->group_id;
					
						$this->list_table->columns['username'] 		= 	esc_html__( 'Username', 'learndash' );
						$this->list_table->columns['name'] 			= 	esc_html__( 'Name', 'learndash' );
						$this->list_table->columns['email'] 		= 	esc_html__( 'Email', 'learndash' );
						$this->list_table->columns['user_actions'] 	= 	esc_html__( 'Actions', 'learndash' );
					
						return;
					}
				}
			} else if ($this->current_action == 'learndash-group-email') {
				//error_log('group_id['. $this->group_id .']');
				
				$group_post = get_post( $this->group_id );
				if ($group_post) {
					$this->title = esc_html__( 'Group Administration', 'learndash' ) . ': ';
				
					if (learndash_is_admin_user()) {
						$this->title .= '<a title="'. esc_html__('Edit Group', 'learndash') .'" href="'. get_edit_post_link( $this->group_id ).'">';
					}
				
					$this->title .= $group_post->post_title;
				
					if (learndash_is_admin_user()) {
						$this->title .= '</a>';
					}
				
					$this->title .= ' <small><a href="'. remove_query_arg( array('action', 's', 'paged', 'learndash-search', 'ld-group-list-view-nonce', '_wp_http_referer', '_wpnonce') ) .'">'. esc_html__( 'Back', 'learndash' ) .'</a></small>';
			
					return;
				}
			} 
			$this->title = esc_html__( 'Group Administration', 'learndash' );

			$this->list_table->columns['group_name'] 	= 	esc_html__( 'Group Name', 'learndash' );
			$this->list_table->columns['group_actions'] = 	esc_html__( 'Actions', 'learndash' );
		}

		function show_page() {

			?>
			<div class="wrap wrap-learndash-group-list">
				<h2><?php echo $this->title; ?></h2>
				<?php
					$current_user = wp_get_current_user();
					if ( ( !learndash_is_group_leader_user( $current_user ) ) && ( !learndash_is_admin_user( $current_user ) ) ) {
						die( esc_html__( 'Please login as a Group Administrator', 'learndash' ) );
					}
				?>
				<div class="wrap-learndash-view-content">
					<?php
					if ($this->current_action == 'learndash-group-email') {
						?>
						<input id="group_email_ajaxurl" type="hidden" name="group_email_ajaxurl" value="<?php echo admin_url('admin-ajax.php') ?>" />
						<input id="group_email_group_id" type="hidden" name="group_email_group_id" value="<?php echo $this->group_id ?>" />
						<input id="group_email_nonce" type="hidden" name="group_email_nonce" value="<?php echo wp_create_nonce( 'group_email_nonce_'. $this->group_id .'_'. $current_user->ID ); ?>" />
						
						<!-- Email Group feature below the Group Table (on the Group Leader page) -->
						<table class="form-table">
							<tr>
								<th scope="row"><label for="group_email_sub"><?php esc_html_e( 'Email Subject:', 'learndash' );?></label></th>
								<td><input id="group_email_sub" rows="5" class="regular-text group_email_sub"/></td>
							</tr>
							<tr>
								<th scope="row"><label for="text"><strong><?php esc_html_e( 'Email Message:', 'learndash' );?></strong></label></th>
								<td><div class="groupemailtext" ><?php wp_editor( '', 'groupemailtext', array( 'media_buttons' => true, 'wpautop' => true) );?></div></td>
							</tr>
						</table>

						<p>
							<button id="email_group" class="button button-primary" type="button"><?php esc_html_e( 'Send', 'learndash' );?></button>
							<button id="email_reset" class="button button-secondary" type="button"><?php esc_html_e( 'Reset', 'learndash' );?></button><br />
							<span class="empty_status" style="color: red; display: none;"><?php esc_html_e( 'Both Email Subject and Message are required and cannot be empty.', 'learndash' ) ?></span>
							<span class="sending_status" style="display: none;"><?php esc_html_e( 'Sending...', 'learndash' ) ?></span>
							<span class="sending_result" style="display: none;"></span>
						</p>
					<?php
					} else {
					
						$this->list_table->views(); 
						?>
						<form id="learndash-view-form" action="" method="<?php echo $this->form_method; ?>">
							<input type="hidden" name="page" value="group_admin_page" />
						
							<?php
								if ( empty( $this->user_id ) ) {
									?><input type="hidden" name="user_id" value="<?php echo $this->user_id ?>" /><?php
									$this->list_table->check_table_filters();
									$this->list_table->prepare_items();
								
									if ( !empty( $this->group_id ) ) {
										?><input type="hidden" name="group_id" value="<?php echo $this->group_id ?>" /><?php
										$this->list_table->search_box( esc_html__( 'Search Users' ), 'learndash' );
									} else {
										$this->list_table->search_box( esc_html__( 'Search Groups' ), 'learndash' );
									}
									wp_nonce_field( 'ld-group-list-view-nonce', 'ld-group-list-view-nonce' );
									$this->list_table->display();
								} else {
									$group_user_ids = learndash_get_groups_user_ids( $this->group_id );
									if ( !empty( $group_user_ids ) ) {
										if ( in_array( $this->user_id, $group_user_ids ) ) {
										
											echo learndash_course_info_shortcode( array( 'user_id' => $this->user_id ) );
										
											if ( learndash_show_user_course_complete( $this->user_id ) ) {
												echo submit_button( esc_html__('Update User') );
											}
										}
									}
								}
							?>
						</form>
						<?php
					}
				?>
				</div>
			</div>
			<?php
		}

		
		
		function on_process_actions_list() {
			if ( !empty( $this->user_id ) ) {
				learndash_save_user_course_complete( $this->user_id );
			}
		}
		
		// End of functions
	}
}

function learndash_data_group_reports_ajax() {
	$reply_data = array( 'status' => false);

	
	if ( isset( $_POST['data'] ) )
		$post_data = $_POST['data'];
	else
		$post_data = array();
		
	$ld_admin_settings_data_reports = new Learndash_Admin_Settings_Data_Reports;
	$reply_data['data'] = $ld_admin_settings_data_reports->do_data_reports( $post_data, $reply_data );
	
	
	if ( !empty( $reply_data ) )
		echo json_encode($reply_data);

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_learndash_data_group_reports', 'learndash_data_group_reports_ajax' );
