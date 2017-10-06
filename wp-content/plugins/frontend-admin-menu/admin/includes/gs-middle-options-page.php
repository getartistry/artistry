<?php 
$roles = get_editable_roles();
$menus = frontend_admin_menu_menus();

if( ! empty( $_POST['Submit'] ) && $_POST['Submit'] == 'Save options' ) {

    $frontend_admin_menu_status = sanitize_text_field ( isset( $_POST['frontend_admin_menu_status'] ) ? esc_html( trim( $_POST['frontend_admin_menu_status'] ) ) : null );
    $frontend_admin_menu_admin_menu_backend = sanitize_text_field ( isset( $_POST['frontend_admin_menu_admin_menu_backend'] ) ? esc_html( trim( $_POST['frontend_admin_menu_admin_menu_backend'] ) ) : null );
    $frontend_admin_menu_color = sanitize_text_field ( isset( $_POST['frontend_admin_menu_color'] ) ? esc_html( trim( $_POST['frontend_admin_menu_color'] ) ) : null );       
    
    $frontend_admin_menu_mapping = array();
    $frontend_admin_menu_mapping_admin_bar = array();
    foreach ( $roles as $key => $rol ) {
        $frontend_admin_menu_mapping[$key] = sanitize_text_field ( isset( $_POST['frontend_admin_menu_mapping_' . $key] ) ? esc_html( trim( $_POST['frontend_admin_menu_mapping_' . $key] ) ) : null );
        $frontend_admin_menu_mapping_admin_bar[$key] = sanitize_text_field ( isset( $_POST['frontend_admin_menu_mapping_admin_bar_' . $key] ) ? esc_html( trim( $_POST['frontend_admin_menu_mapping_admin_bar_' . $key] ) ) : null );
        update_option( 'frontend_admin_menu_mapping_' . $key, $frontend_admin_menu_mapping[$key]);
        update_option( 'frontend_admin_menu_mapping_admin_bar_' . $key, $frontend_admin_menu_mapping_admin_bar[$key]);
    }
    
    update_option( 'frontend_admin_menu_status', $frontend_admin_menu_status );
    update_option( 'frontend_admin_menu_admin_menu_backend',  $frontend_admin_menu_admin_menu_backend );
    update_option( 'frontend_admin_menu_color',  $frontend_admin_menu_color );
    
    print '<div class="updated">';
         _e( 'Options saved.' );
    print '</div>';

}

$frontend_admin_menu_status = get_option( 'frontend_admin_menu_status' );
$frontend_admin_menu_admin_menu_backend = get_option( 'frontend_admin_menu_admin_menu_backend' );
$frontend_admin_menu_color = get_option( 'frontend_admin_menu_color' );

if ( $frontend_admin_menu_status > 0 ) {
    
    $checked_status = "checked";
    
} else {
  
    $checked_status = false;
    
}

if ( $frontend_admin_menu_admin_menu_backend > 0 ) {
    
    $checked_backend_menu = "checked";
    
} else {
  
    $checked_backend_menu = false;
    
} 
?>

<div class="container">
    <form name="frontend_admin_menu_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
         <div class="wgc-box wgc-box-1">
            <div class="header medium">
                <?php _e( '<h4>Basic options</h4>', 'frontend-admin-menu' ); ?>
            </div>
            <div class="wgc-box-body">
                <table>
                    <tr>
                        <td><input type="checkbox" name="frontend_admin_menu_status" id="frontend_admin_menu_status" value="1" <?php print $checked_status; ?> /></td>
                        <td><label for="frontend_admin_menu_status"><?php _e( 'Enable frontend admin menu to <b>frontend</b>? <i>(Remember to mapping a menu to rol)</i>', 'frontend-admin-menu' ); ?></label></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="frontend_admin_menu_admin_menu_backend" id="frontend_admin_menu_admin_menu_backend" value="1" <?php print $checked_backend_menu; ?> /></td>
                        <td><label for="frontend_admin_menu_admin_menu_backend"><?php _e( 'Enable frontend admin menu to <b>backend</b>? <i>(Remember to mapping a menu to rol - This is not apply to Administrator rol)</i>', 'frontend-admin-menu' ); ?></label></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="wgc-box wgc-box-2">
            <div class="header medium">
                <?php _e( '<h4>Mapping menu to roles</h4>', 'frontend-admin-menu' ); ?>
                ( <a href="<?php echo admin_url(); ?>nav-menus.php">MANAGE YOUR MENUS</a> - <a href="<?php echo admin_url(); ?>nav-menus.php?action=edit&menu=0">CREATE A NEW MENU</a> )
            </div>
            <div class="wgc-box-body">
                <table>
                    <?php
                    $frontend_admin_menu_mapping_option = array();
                    foreach ( $roles as $key => $rol ) {
                        $currentmenu = get_option( 'frontend_admin_menu_mapping_' . $key );
                        print '<tr>';
                            print '<td>';
                                print _e( 'Choose a menu to', 'frontend-admin-menu' ).' '.$rol['name'].': ';
                            print '</td>';
                            print '<td>';
                            print '<select name="frontend_admin_menu_mapping_' . $key . '" id="frontend_admin_menu_mapping_' . $key . '">';
                                print '<option value=""> -- No menu selected -- </option>';
                                foreach ( $menus as $val => $menu ) {
                                    if ( $currentmenu == $menu->slug) {
                                        $selectedmenu = 'selected';
                                    } else {
                                        $selectedmenu = false;
                                    }
                                    print '<option value="' . $menu->slug . '" ' . $selectedmenu . '>' . $menu->name . '</option>';
                                }
                            print '</select>';
                            print '</td>';
                        print '</tr>';
                    }
                    ?>
                </table>
                <table>
                    <tr>
                        <td align="left">
                            <?php _e( 'NOTE: If you want to create more roles we recommend you the plugin: ', 'frontend-admin-menu' ); ?>
                            <a href="https://wordpress.org/plugins/user-role-editor/" target="_blank">User Role Editor</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="wgc-box wgc-box-2">
            <div class="header medium">
                <?php _e( '<h4>Show / Hide admin bar</h4>', 'frontend-admin-menu' ); ?>
            </div>
            <div class="wgc-box-body">
                <table>
                    <?php
                    foreach ( $roles as $key => $rol ) {
                        $currentadmin = get_option( 'frontend_admin_menu_mapping_admin_bar_' . $key );
                        print '<tr>';
                            print '<td>';
                                print _e( 'Top admin bar to:', 'frontend-admin-menu' ).' '.$rol['name'].': ';
                            print '</td>';
                            print '<td>';
                                print '<select name="frontend_admin_menu_mapping_admin_bar_' . $key . '" id="frontend_admin_menu_mapping_admin_bar_' . $key . '">';
                                    $posibility = array(
                                        1 => 'Hide', 
                                        0 => 'Show'     
                                    );
                                    foreach ( $posibility as $key => $pos ) {
                                        if ( isset( $currentadmin ) && $currentadmin == $key ) {
                                            $selectedadmin = 'selected';
                                        } else {
                                            $selectedadmin = false;
                                        }
                                        print '<option value="'.$key.'" '.$selectedadmin.'>'.$pos.'</option>';
                                    }
                                print '</select>';
                            print '</td>';
                        print '</tr>';
                    }
                    ?>
                </table>
                <table>
                    <tr>
                        <td align="left">
                            <?php _e( 'NOTE: If you want to create more roles we recommend you the plugin: ', 'frontend-admin-menu' ); ?>
                            <a href="https://wordpress.org/plugins/user-role-editor/" target="_blank">User Role Editor</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="wgc-box wgc-box-3">
            <div class="header medium">
                <?php _e( '<h4>Menu appearance</h4>', 'frontend-admin-menu' ); ?>
            </div>
            <div class="wgc-box-body">
                <table>
                    <tr>
                        <td><?php _e( 'Theme:', 'frontend-admin-menu' ); ?></td>
                        <td>
                            <select name="frontend_admin_menu_color" id="frontend_admin_menu_color">
                                <?php 
                                $colors = array(
                                    "orange" => "orange",
                                    "blue" => "blue",
                                    "red" => "red",
                                    "green" => "green",
                                    "yellow" => "yellow",
                                    "purple" => "purple"
                                );
                                foreach ( $colors as $color ) {
                                    $selected = false;
                                    if( $frontend_admin_menu_color == $color ) {
                                        $selected = "selected";
                                    }
                                    print '<option value="'.$color.'" '.$selected.'>'.$color.'</option>';
                                }?>
                            </select>    
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="block">
            <p class="submit">
                <input type="submit" class="button button-primary" name="Submit" id="Submit" value="<?php _e( 'Save options', 'frontend-admin-menu' ); ?>" />
            </p>
        </div>
    </form>
</div> <!-- container -->

