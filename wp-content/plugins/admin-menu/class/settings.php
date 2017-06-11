<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
class admin_menu_pro_settings {
    function __construct(){
        add_action("admin_enqueue_scripts",array($this,"add_lib"));
        add_action('admin_menu', array($this,"add_menu_settings"));
        add_action("admin_menu_top",array($this,"up_to_pro"));
    }
    function up_to_pro(){
        ?>
        <h3>Upgrade to Pro:</h3>
        <ul>
            <li>Enalble Open link in a new tab</li>
            <li>Change icon</li>
            <li>Free Support</li>
            <li></li>
            <li><a href="https://codecanyon.net/item/admin-menu-pro-for-wordpress/19964657?ref=rednumber" target="_blank">Buy on Envato</a> |
                <a href="http://preview.codecanyon.net/item/admin-menu-pro-for-wordpress/full_screen_preview/19964657?ref=rednumber" target="_blank">Demo</a>
            </li>
        </ul>
        <?php
    }
    /*
    * add js
    */
    function add_lib(){
        $page = @$_GET["page"];
        if( $page == "admin-menu-pro" ):
            // wp_enqueue_script( 'nav-menu-custom', ADMIN_MENU_PLUGIN_URL."js/nav-menu.js",array("jquery") );
             wp_enqueue_script("nav-menu");
                $nav_menus_l10n = array(
            	'oneThemeLocationNoMenus' => "",
            	'moveUp'       => __( 'Move up one' ),
            	'moveDown'     => __( 'Move down one' ),
            	'moveToTop'    => __( 'Move to the top' ),
            	/* translators: %s: previous item name */
            	'moveUnder'    => __( 'Move under %s' ),
            	/* translators: %s: previous item name */
            	'moveOutFrom'  => __( 'Move out from under %s' ),
            	/* translators: %s: previous item name */
            	'under'        => __( 'Under %s' ),
            	/* translators: %s: previous item name */
            	'outFrom'      => __( 'Out from under %s' ),
            	/* translators: 1: item name, 2: item position, 3: total number of items */
            	'menuFocus'    => __( '%1$s. Menu item %2$d of %3$d.' ),
            	/* translators: 1: item name, 2: item position, 3: parent item name */
            	'subMenuFocus' => __( '%1$s. Sub item number %2$d under %3$s.' ),
            );
            wp_localize_script( 'nav-menu', 'menus', $nav_menus_l10n );
            wp_enqueue_style( 'nav-menu' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'admin-menu-pro', ADMIN_MENU_PLUGIN_URL."css/admin-menu-pro.css" );
            wp_enqueue_style( 'dashicons-picker', ADMIN_MENU_PLUGIN_URL."css/dashicons-picker.css",array("dashicons") );
            wp_enqueue_script( 'dashicons-picker', ADMIN_MENU_PLUGIN_URL."js/dashicons-picker.js",array("jquery") );
            wp_enqueue_script( 'admin-menu-pro', ADMIN_MENU_PLUGIN_URL."js/admin-menu-pro.js", array( 'wp-color-picker' ),time() );
            $menu_pro = array();
            foreach (get_editable_roles() as $role_name => $role_info):
                $menu_pro[$role_name] = $role_info["capabilities"];
            endforeach;
            wp_localize_script( 'admin-menu-pro', 'menu_pro',array("current_role"=>"ok","roles"=>$menu_pro)  );
            if ( wp_is_mobile() )
    	       wp_enqueue_script( 'jquery-touch-punch' );
        endif;
    }
    /*
    * add menu
    */
    function add_menu_settings(){
        add_menu_page('Admin menu', 'Admin menu', 'manage_options', "admin-menu-pro", array($this,"add_form")  );
    }
    /*
    * add form
    */
    function add_form(){
        global $menu,$submenu;
        $user_type = "role";
        if(isset($_GET["role"])){
            $role = $_GET["role"];
            if(is_numeric($role)){
                $user_id = $role;
                $user_meta=get_userdata($user_id);
                $role =$user_meta->roles[0];
                $user_name = $user_meta->data->user_login;
                $role_default = $_GET["role"];
            }else{
               $role_default = $_GET["role"];
            }

        }else{
            $role = "administrator";
            $role_default ="";
        }
        ?>
        <div class="wrap">
            <h1><?php _e("Manager Admin Menu",ADMIN_MENU_TEXT_DOMAIN) ?></h1>
            <p><?php _e("Reset default to delete menu"); ?></p>
            <?php do_action("admin_menu_top") ?>
            <div class="nav-menus-php menu-max-depth-0 ">
                <div id="nav-menus-frame" class="wp-clearfix">
                    <div id="menu-settings-column">
                        <div class="side-sortables tab-admin-style">
                            <h3><?php _e("New Menu",ADMIN_MENU_TEXT_DOMAIN) ?></h3>
                            <div class="tab-admin-content1">
                                <ul  id="admin-menu-new">
                                    <?php
                                    $data_sub = array("name"=>"New Menu","url"=>"#","icon"=>"dashicons-admin-generic","capability"=>"read","id"=>rand(),"role" => $role);
                                    $this->get_html_menu($data_sub,1);
                                    ?>
                                </ul>
                                <div id="menu-settings-column1">
                                    <h3><?php _e("Settings",ADMIN_MENU_TEXT_DOMAIN) ?></h3>
                                    <div class="tab-admin-content">
                                        <ul class="tab-style">
                                            <?php
                                            $settings = get_option("_admin_menu_pro_settings_$role_default");
                                            if( !is_array($settings)){
                                                $settings["backgound"] = "#23282d";
                                                $settings["color"] = "#eee";
                                                $settings["backgound_active"] = "#0073aa";
                                                $settings["color_active"] = "#fff";
                                            }
                                            ?>
                                            <li><input type="text" value="<?php echo $settings["backgound"] ?>" class="wall-color" id="admin-menu-style-background" /><label>: Backgound</label> </li>
                                            <li><input type="text" value="<?php echo $settings["color"] ?>" class="wall-color" id="admin-menu-style-color" /><label>: Color</label> </li>
                                            <li><input type="text" value="<?php echo $settings["backgound_active"] ?>" class="wall-color" id="admin-menu-style-background-active" /><label>: Backgound active</label> </li>
                                            <li><input type="text" value="<?php echo $settings["color_active"] ?>" class="wall-color" id="admin-menu-style-color-active" /><label>: Color active</label> </li>
                                        </ul>
                                        <div class="aligncenter-admin">
                                        <a href="#" class="reset-setttings-admin-menu"><?php _e("Reset default settings",ADMIN_MENU_TEXT_DOMAIN) ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu-settings-column2">
                                    <h3><?php _e("Menu by",ADMIN_MENU_TEXT_DOMAIN) ?></h3>
                                    <div class="tab-admin-content">
                                        <ul class="tab-style">
                                            <li class="<?php if(!$role_default){echo "active";} ?>" ><a href="<?php echo add_query_arg(array("page"=>"admin-menu-pro"),admin_url("admin.php")) ?>">All Users</a></li>
                                            <?php
                                            foreach (get_editable_roles() as $role_name => $role_info) :
                                            ?>
                                            <li class="<?php if( $role_default == $role_name ){echo "active";} ?>"><a href="<?php echo add_query_arg(array("page"=>"admin-menu-pro","role"=>$role_name),admin_url("admin.php")) ?>">Role: <?php echo $role_name ?></a></li>
                                            <?php  endforeach; ?>
                                            <?php
                                            $this->load_html_list_user($role_default);
                                             ?>
                                            <li class="admin_menu_new_user" ><a href="<?php echo add_query_arg(array("page"=>"admin-menu-pro"),admin_url("admin.php")) ?>"><?php _e("New user") ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="footer-botton-style">
                                    <div>
                                        <a href="#" class="button reset-menu-pro"><?php _e("Reset default",ADMIN_MENU_TEXT_DOMAIN)?></a>
                                    </div>
                                    <div class="text-right">
                                        <a href="#" class="button button-primary save-menu-pro"><?php _e("Save",ADMIN_MENU_TEXT_DOMAIN)?></a>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div id="menu-management-liquid">
                        <div class="tab-admin-style">
                            <h3><?php
                                if(!$role_default){
                                    _e("ALL Users",ADMIN_MENU_TEXT_DOMAIN);
                                }else{
                                    if(is_numeric($role_default)){
                                        _e("Menu user",ADMIN_MENU_TEXT_DOMAIN);echo ": ". $user_name;
                                    }else{
                                        _e("Menu",ADMIN_MENU_TEXT_DOMAIN);echo ": ". $role;
                                    }

                                } ?>
                                <span>
                                    <a href="#" class="button button-primary save-menu-pro"><?php _e("Save",ADMIN_MENU_TEXT_DOMAIN)?></a>
                                </span>
                                <span>
                                    <a href="#" class="button reset-menu-pro"><?php _e("Reset default",ADMIN_MENU_TEXT_DOMAIN)?></a>
                                </span>
                            </h3>
                            <div class="tab-admin-content" id="tba_contnet">
                                <form action="post" method="" id="update-nav-menu">
                                <input id="admin_menu_role_id" name="admin_menu_role" value="<?php echo @$_GET["role"]; ?>" type="hidden" />
                                <ul id="menu-to-edit" class="menu ui-sortable">

                                    <?php
                                        $menu_main = get_option("_admin_menu_pro_main_{$role_default}");
                                        $submenu_main = get_option("_admin_menu_pro_sub_{$role_default}");
                                        $default = false;
                                        if( !is_array($menu_main) || count($menu_main) < 1 ) {
                                            $default =true;
                                            $menu_main = $menu;
                                           $submenu_main = $submenu;
                                        }
                                        $left_sub = false;
                                        foreach ($menu_main as $menu_id => $menu_value) {
                                                $menu_id = preg_replace("#[^0-9]#","",$menu_id);
                                                if($menu_id==0){
                                                    $menu_id = 1;
                                                }
                                                $key = $menu_value[2];
                                                $name = $this->strip_tags_content($menu_value[0]);
                                                if($name == ""){
                                                   /*
                                                   * Separator
                                                   */
                                                }else{
                                                    $icon = $menu_value[6];
                                                    $capability = $menu_value[1];
                                                    $class = $menu_value[4];

                                                    $data = array(  "name"=>$name,
                                                                    "icon"=>$icon,
                                                                    "capability"=>$capability,
                                                                    "id"=>$menu_id,
                                                                    "class"=>$class,
                                                                    "url" => $key,
                                                                    "parent" => 0,
                                                                    "key_5" => $menu_value[5],
                                                                    "target" => $menu_value[8],
                                                                    "readonly" => $this->check_readonly($key),
                                                                    "role" => $role
                                                                    );
                                                    if( $this->check_role($role,$menu_value[1])):
                                                        $left_sub =true;
                                                        $this->get_html_menu($data);
                                                    endif;
                                                }
                                            if( @is_array($submenu_main[$key]) ):
                                                foreach( $submenu_main[$key] as $number => $data ){
                                                    if( $this->check_role($role,$data[1])):
                                                        $i++;
                                                        $name_sub = $this->strip_tags_content($data[0]);
                                                        if($name_sub == ""){
                                                            //$this->get_html_separator(array("name"=>"Separator","id"=>$i),1);
                                                        }else{
                                                            if( !$default ){
                                                                $data_sub = array(  "name"=>$name_sub,
                                                                "icon"=>$data[6],
                                                                "capability"=>$data[1],
                                                                "id"=>"88".$menu_id.$number,
                                                                "class"=>$data[4],
                                                                "url" => $data[2],
                                                                "parent" => $menu_id,
                                                                "key_5" => $data[5],
                                                                "target" => $data[8],
                                                                "readonly" => $this->check_readonly($data[2]),
                                                                "role" => $role
                                                                );
                                                            }else{
                                                                $icon_sub = "";
                                                                $capability_sub = $data[1];
                                                                $data_sub = array("name"=>$name_sub,"icon"=>$icon_sub,"capability"=>$capability_sub,"id"=>"88".$menu_id.$number,"parent"=>$menu_id,"url"=>$data[2],"readonly"=>true,"role" => $role);
                                                            }
                                                            if($left_sub){
                                                                $this->get_html_menu($data_sub,1);
                                                            }else{
                                                                $this->get_html_menu($data_sub,0);
                                                            }

                                                        }
                                                    endif;
                                                }
                                            endif;
                                            $left_sub = false;
                                         } ?>
                                </ul>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <?php
    }
    /*
    * get html menu
    */
    function get_html_menu($data, $lv=0){
        $name = trim($data["name"]);
        ?>
    <li id="menu-item-<?php echo $data["id"] ?>" class="menu-item menu-item-depth-<?php echo $lv ?> menu-item-page menu-item-edit-inactive menu-item-<?php echo $data["id"] ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo $name ?></span></span>
					<span class="item-controls">
                        <span class="item-remove"><?php _e("Remove",ADMIN_MENU_TEXT_DOMAIN)?></span>
						<span class="item-type">Menu</span>
						<a class="item-edit" id="edit-<?php echo $data["id"] ?>" href="#menu-item-settings-<?php echo $data["id"] ?>" aria-label="Edit menu item">Edit</a>
					</span>
				</div>
			</div>

			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $data["id"] ?>">
			     <p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $data["id"] ?>">
						<?php _e("Menu title",ADMIN_MENU_TEXT_DOMAIN)?><br>
						<input type="text" id="edit-menu-item-title-<?php echo $data["id"] ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $data["id"] ?>]" value="<?php echo $name ?>">
					</label>
				</p>
                <p class="description description-wide">
					<label for="edit-menu-item-target-page-<?php echo $data["id"] ?>">
						<?php _e("Target Page",ADMIN_MENU_TEXT_DOMAIN)?><br>
						<?php $this->get_target_page($data["role"],$data["url"]) ?>
					</label>
				</p>
                <p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $data["id"] ?>">
						<?php _e("Capability",ADMIN_MENU_TEXT_DOMAIN)?><br>
						<?php $this->get_capabilities_page($data["role"],$data["capability"],$data["readonly"]) ; ?>
                        <input <?php if( isset($data["readonly"])){echo 'readonly="readonly"';} ?>  type="text" id="edit-menu-item-title-<?php echo $data["id"] ?>" class="widefat edit-menu-item-capability <?php if(!$data["readonly"]){echo "hidden";} ?>" name="menu-item-capability[<?php echo $data["id"] ?>]" value="<?php echo $data["capability"] ?>">
					   <a class="info-capt" href="https://codex.wordpress.org/Roles_and_Capabilities" target="_blank">List capabilities</a>
                    </label>
				</p>
                <p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $data["id"] ?>">
						<?php _e("URL",ADMIN_MENU_TEXT_DOMAIN)?><br>
						<input <?php if( isset($data["readonly"])){echo 'readonly="readonly"';} ?> type="text" id="edit-menu-item-title-<?php echo $data["id"] ?>" class="widefat edit-menu-item-ulr" name="menu-item-key[<?php echo $data["id"] ?>]" value="<?php echo $data["url"] ?>">
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $data["id"] ?>">
                    <input  <?php checked("_blank",$data["target"]) ?> type="checkbox" id="edit-menu-item-target-<?php echo $data["id"] ?>" value="_blank" name="menu-item-target[<?php echo $data["id"] ?>]">
					<?php _e("Open link in a new tab (Pro version)",ADMIN_MENU_TEXT_DOMAIN) ?></label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $data["id"] ?>">
						<?php _e("CSS Classes",ADMIN_MENU_TEXT_DOMAIN) ?><br>
						<input type="text" id="edit-menu-item-classes-<?php echo $data["id"] ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $data["id"] ?>]" value="<?php echo preg_replace("# admin_menu_blank#","",$data["class"] ) ?>">
					</label>
				</p>
				<div class="field-xfn description description-thin description-thin-icon">
					<label for="edit-menu-item-xfn-<?php echo $data["id"] ?>">
						<?php _e("Icon (Pro version)",ADMIN_MENU_TEXT_DOMAIN)?><br>
                        <input readonly="readonly" class="widefat code edit-menu-item-classes" type="text" id="icon_picker_<?php echo $data["id"] ?>" name="menu-item-icon[<?php echo $data["id"] ?>]" value="<?php echo $data["icon"] ?>"/>
					</label>
                    <div id="preview_icon_picker_<?php echo $data["id"] ?>" data-target="#icon_picker_<?php echo $data["id"] ?>" class="dashicons button icon-picker <?php echo $data["icon"] ?>">
				    </div>
                </div>
				<div class="menu-item-actions description-wide submitbox">
				    <a class="item-delete submitdelete deletion" id="delete-<?php echo $data["id"] ?>" href="#menu-item-settings-<?php echo $data["id"] ?>">Remove</a>
                    <span class="meta-sep hide-if-no-js"> | </span>
                    <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $data["id"] ?>" href="#menu-item-settings-<?php echo $data["id"] ?>">Cancel</a>
				</div>
                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $data["id"] ?>]" value="<?php echo $data["id"] ?>">
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $data["id"] ?>]" value="<?php echo $data["parent"] ?>">
			</div><!-- .menu-item-settings-->
		</li>
        <?php
    }
    /*
    * Show list capabilities by role
    */
    function get_capabilities_page($role,$current,$show){
        $capabilities = get_role($role);
        $capabilities = $capabilities->capabilities;
        ?>
        <select class="menu-taget-page-capt <?php if($show){echo "hidden";} ?>">
            <?php foreach( $capabilities as $key => $value): ?>
                <option <?php selected($key,$current) ?> value="<?php echo $key ?>"><?php echo $key ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    /*
    * Check role
    */
    function check_role($role,$capabilitie){
       $capabilities = get_role($role);
       $capabilities = $capabilities->capabilities;
       if(array_key_exists($capabilitie,$capabilities)){
            return true;
       }
    }
    function load_html_list_user($user_id=1){
        global $wpdb;
        $lists = $wpdb->get_results("SELECT * FROM $wpdb->options WHERE option_name LIKE '_admin_menu_pro_main_%'");
        $saved = false;
        foreach( $lists as $list){
            $name = $list->option_name;
            $check_users= explode("_admin_menu_pro_main_",$name);
            if(is_numeric($check_users[1])){
                $user_meta=get_userdata($check_users[1]);
                $user_name = $user_meta->data->user_login;
                ?>
                <li  class="<?php if( $check_users[1] == $user_id ){echo "active";$saved=true;} ?>"><a href="<?php echo add_query_arg(array("page"=>"admin-menu-pro","role"=>$check_users[1]),admin_url("admin.php")) ?>"><?php _e("User:",ADMIN_MENU_TEXT_DOMAIN) ?> <?php echo $user_name ?></a></li>
                <?php
            }
        }
        if(!$saved){
            if(is_numeric($user_id)):
                $user_meta=get_userdata($user_id);
                $user_name = $user_meta->data->user_login;
                ?>
                <li  class="active"><a href="<?php echo add_query_arg(array("page"=>"admin-menu-pro","role"=>$user_id),admin_url("admin.php")) ?>"><?php _e("User:",ADMIN_MENU_TEXT_DOMAIN) ?> <?php echo $user_name ?></a></li>
                <?php
            endif;
        }
    }
    /*
    * Load target page
    */
    function get_target_page($role,$url){
        $capabilities = get_role($role);
        $capabilities = $capabilities->capabilities;
        $menus = get_option("_default_menu_pro_main");
        $submenus = get_option("_default_menu_pro_sub");
        ?>
        <select class="menu-taget-page-sel">
            <option value="#admin-menu-<?php random_int(1000,999999) ?>"><?php _e("Custom",ADMIN_MENU_TEXT_DOMAIN)?></option>
        <?php
        foreach( $menus as $menu ){
            $name = $this->strip_tags_content($menu[0]);
            $key = $menu[2];
            $capabilitie = $menu[1];
            if( $name != "" && array_key_exists($capabilitie,$capabilities)):
            ?>
            <option <?php selected($key,$url) ?> data-cap="<?php echo $capabilitie ?>" value="<?php echo $key ?>"><?php echo $name ?></option>
            <?php
            if( @is_array($submenus[$key]) ):
                foreach( $submenus[$key] as $number => $data ){
                        $name_sub = $this->strip_tags_content($data[0]);
                        $capabilitie_sub = $data[1];
                        $key_sub = $data[2];
                        if( $name_sub != "" && array_key_exists($capabilitie_sub,$capabilities)):
                            ?>
                            <option <?php selected($key_sub,$url) ?> data-cap="<?php echo $capabilitie_sub ?>" value="<?php echo $key_sub ?>">---<?php echo $name." --> ".$name_sub ?></option>
                            <?php
                        endif;
                }
            endif;
            endif;
        }
        ?>
        </select>
        <?php
    }
    /*
    * check readonly
    */
    function check_readonly($url){
        $menus = get_option("_default_menu_pro_main");
        $submenus = get_option("_default_menu_pro_sub");
        foreach( $menus as $menu ){
            if( $menu[2] == $url){
                return true;
            }
             if( @is_array($submenus[$key]) ):
                foreach( $submenus[$key] as $number => $data ){
                    if( $data[2] == $url ) {
                        return true;
                    }
                }
             endif;
        }

    }
    /*
    *
    */
    function strip_tags_content($text) {
          $ok = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
          return preg_replace('#<(.*?)>#', '', $ok);
        }
}
new admin_menu_pro_settings;