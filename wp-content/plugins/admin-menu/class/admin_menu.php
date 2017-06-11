<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
class admin_menu_pro {
    function __construct(){
        add_action( 'admin_head', array($this,'changle_name'),1);
        add_filter( 'custom_menu_order', array($this,'set_default_menu'), 999999999);
        //add_filter('menu_order', array($this,'custom_menu_order'));
        add_action( 'wp_ajax_reset_default_menu', array($this,'reset_default_menu') );
        add_action( 'wp_ajax_save_menu_pro', array($this,'save_menu') );
        add_action( 'admin_head', array($this,'set_settings'));
        add_action( 'wp_ajax_reset_default_menu_settings', array($this,'reset_default_menu_settings') );
    }
    /*
    * Ajax reset
    */
    function reset_default_menu_settings(){
        $role = $_POST["role"];
        delete_option("_admin_menu_pro_settings_{$role}");
        die();
    }
    function set_settings(){
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $roles = $current_user->roles;
        $show = false;
        $data = array();
        $settings_user = get_option("_admin_menu_pro_settings_$user_id");
        if( is_array($settings_user) && count($settings_user)>0 ) {
            $show = true;
            $data = $settings_user;
        }else{
           $settings_role = get_option("_admin_menu_pro_settings_{$roles[0]}");
           if( is_array($settings_role) && count($settings_role)>0 ) {
                $show = true;
                 $data = $settings_role;
           }else{
               $settings_all = get_option("_admin_menu_pro_settings_");
               if( is_array($settings_all) && count($settings_all)>0 ) {
                $show = true;
                 $data = $settings_all;
               }
           }

        }
        if( $show && ( $data["backgound"] != "#23282d" || $data["color"] != "#eee" || $data["backgound_active"] != "#0073aa" || $data["color_active"] != "#fff" ) ) {

            ?>
            <style type="text/css">
                #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap {
                    background-color: <?php echo $data["backgound"] ?>;
                }
                #wpadminbar {
                    background: <?php echo $data["backgound"] ?>;
                }
                #adminmenu a,#wpadminbar .ab-empty-item, #wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon,
                #adminmenu div.wp-menu-image:before,
                #wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before,
                #adminmenu .wp-submenu a,
                #wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a
                {
                    color: <?php echo $data["color"] ?>;
                }
                #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus,
                #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus,
                #wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input,
                #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu
                 {
                    background: <?php echo $data["backgound_active"] ?>;
                    background-color: <?php echo $data["backgound_active"] ?>;
                    color: <?php echo $data["color_active"] ?>;
                }
                #adminmenu .current div.wp-menu-image:before, #adminmenu .wp-has-current-submenu div.wp-menu-image:before, #adminmenu a.current:hover div.wp-menu-image:before, #adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before,
                #adminmenu a:hover, #wpadminbar .ab-empty-item:hover, #wpadminbar a.ab-item:hover, #wpadminbar>#wp-toolbar span.ab-label:hover, #wpadminbar>#wp-toolbar span.noticon:hover,
                #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before,
                #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:focus, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:hover, #wpadminbar li #adminbarsearch.adminbar-focused:before, #wpadminbar li .ab-item:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
                #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu a:hover, #adminmenu li.menu-top>a:focus,
                #adminmenu .current div.wp-menu-image:before, #adminmenu .wp-has-current-submenu div.wp-menu-image:before, #adminmenu a.current:hover div.wp-menu-image:before, #adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before
                 {
                    color: <?php echo $data["color_active"] ?>;
                }
            </style>
            <?php
        }
    }
    /*
    * Custom menu
    */
    function changle_name($menu_order){
        global $menu,$submenu;
        $current_user = wp_get_current_user();
        $roles = $current_user->roles;
        $user_id = $current_user->ID;
        $main_menu = get_option("_admin_menu_pro_main_{$user_id}");
        $sub_menu = get_option("_admin_menu_pro_sub_{$user_id}");

        if( is_array($main_menu) && count($main_menu)>0 ) {
            $submenu = $sub_menu;
            $menu = $main_menu;
        }else{
            $main_menu2 = get_option("_admin_menu_pro_main_{$roles[0]}");
            $sub_menu2 = get_option("_admin_menu_pro_sub_{$roles[0]}");

            if( is_array($main_menu2) && count($main_menu2)>0 ) { 
                 $submenu = $sub_menu2;
                 $menu = $main_menu2;   
            }else{
                 $main_menu1 = get_option("_admin_menu_pro_main_");
                $sub_menu1 = get_option("_admin_menu_pro_sub_");
                $left_sub = false;
                $new_menu = array();
                if( is_array($main_menu1) && count($main_menu1)>0 ) {
                    foreach( $main_menu1 as $key => $value ) {
                        $url = $value[2];
                        if( !admin_menu_pro_settings::check_role($roles[0],$value[1])){
                            //unset($main_menu1[$key]);
                        }else{
                            $new_menu[$key] = $value;
                            $left_sub = true;
                        }
                        if( @is_array($sub_menu1[$url]) ):
                            foreach( $sub_menu1[$url] as $number => $data) {
                                if( admin_menu_pro_settings::check_role($roles[0],$data[1]) && !$left_sub){
                                  $main_menu1[] = $sub_menu1[$url][$number];
                                  $new_menu[rand(10000,999999)] = $sub_menu1[$url][$number];
                                }
                            }
                        endif;
                    $left_sub =false;
                    }
                    $submenu = $sub_menu1;
                    $menu = $new_menu;
                }   
            }
            
            
        }

    }
    /*
    * Update default memu
    */
    public static function set_default_menu($menu_order){
        global $menu,$submenu;
        $current_user = wp_get_current_user();
        $roles = $current_user->roles;
        if( $roles[0] == "administrator") {
            update_option("_default_menu_pro_main",$menu);
            update_option("_default_menu_pro_sub",$submenu);
        }

         return $menu_order;
    }
    /*
    * Reset default
    */
    function reset_default_menu(){
        $role = $_POST["role"];
        delete_option("_admin_menu_pro_settings_{$role}");
        delete_option("_admin_menu_pro_main_{$role}");
        delete_option("_admin_menu_pro_sub_{$role}");
        die();
    }
    /*
    * Save menu
    */
    function save_menu(){
        $data = $_POST["data"];
        $role = $data[0]["value"];
        unset($data[0]);
        $total_menu = array();
        $main = array();
        $sub = array();
        foreach( $data as $key => $value ){
            $name = $value["name"];
            preg_match("#\[(.*?)\]#",$name,$rs);
            $data_key = $rs[1];
            $total_menu[$data_key] = $data_key;
        }
        //var_dump($total_menu);
        //die();
        $key_parent = "";
        foreach( $total_menu as $number ){
            $parent_done = 1;

            foreach( $data as $key => $value ){
                //var_dump($value);
                //die();
                $name = $value["name"];
                $value = $value["value"];
                switch( $name ) {
                    case "menu-item-title[{$number}]":
                        $name_done = $value;
                        break;
                    case "menu-item-classes[{$number}]":
                        if($value == ""){
                            $value ="admin-menu-class";
                        }
                        $class_done = trim($value);
                        break;
                    case "menu-item-icon[{$number}]":
                        if( $value == "" ){
                           $icon_done = "dashicons-admin-generic";
                        }else{
                           $icon_done = $value;
                        }

                        break;
                    case "menu-item-target[{$number}]":
                        $target_done = "";
                        break;
                    case "menu-item-parent-id[{$number}]":
                        $parent_done = $value;
                        //var_dump($number."|".$parent_done."|".$name_done);
                        break;
                    case "menu-item-key[{$number}]":
                        $key_done = $value;
                        break;
                    case "menu-item-capability[{$number}]":
                        $capability_done = $value;
                        break;
                    case "menu-item-key5[{$number}]":
                        $key5_done = $value;
                        break;
                }
            }
            if( $target_done == "_blank"){
                if( !preg_match("#admin_menu_blank#",$class_done)){
                    $class_done .= " admin_menu_blank";
                }
            }else{
                if( preg_match("#admin_menu_blank#",$class_done)){
                    $class_done = preg_replace("#admin_menu_blank#","",$class_done);
                }
            }
            if($parent_done == 0 ){

                $key_parent = $key_done;
                if( !preg_match("#menu-top#",$class_done) ){
                    if( $class_done == ""){
                        $class_done = "menu-top";
                    }else{
                        $class_done .=" menu-top";
                    }
                };
                $main[$number] = array(0=>$name_done,1=>$capability_done,2=>$key_done,3=>"",4=>$class_done,5=>$key5_done,6=>$icon_done,7=>1,8=>$target_done);
            }else{
                $class_done = preg_replace("#menu-top#","",$class_done);
                $sub[$key_parent][] = array(0=>$name_done,1=>$capability_done,2=>$key_done,3=>"",4=>$class_done,5=>$key5_done,6=>$icon_done,7=>1,8=>$target_done);

            }
            $target_done ="";

        }
        //var_dump($main);
        //var_dump($_POST["settings"]);
        update_option("_admin_menu_pro_settings_$role",$_POST["settings"]);
        update_option("_admin_menu_pro_main_$role",$main);
        update_option("_admin_menu_pro_sub_$role",$sub);
        die();
    }
}
new admin_menu_pro;