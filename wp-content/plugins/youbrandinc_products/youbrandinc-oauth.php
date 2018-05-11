<?php
require_once YBI_BASE_PATH . 'vendor/autoload.php';
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

//$screen = get_current_screen();
//Svar_dump($screen);

function ybi_print_jquery_notice_message($message, $level)
{
    $return_message = "<script>jQuery('#ybi_oauth_message').html('<div class=\"notice ".$level." my-acf-notice is-dismissible\" ><p>".$message."</p></div>');</script>";
    echo($return_message);
}

?>
<style>
    .bad {color: #ff0a16;}
    .good {color: #048005;}
</style>
<div class="wrap">
    <div class="products_header" style="background: url(<?php echo plugins_url('youbrandinc_products/i/you-brand-guys-32.png');?>); background-repeat: no-repeat; background-position: 0px 7px;">
        <h3><?php echo _e('Connected Apps'); ?></h3>
        <div style="clear: both; overflow:auto; margin: 0 auto;"></div>
    </div>
    <div id="ybi_oauth_message"></div>
        <?php

        $service_reset = isset($_GET['service_reset']) ? $_GET['service_reset'] : "";
        $valid_service_arr = array(
            'Pocket',
            'ImgUr',
        );

        if($service_reset) {
            if(in_array($service_reset,$valid_service_arr)) {
                delete_option('cs_'.$service_reset.'_access_data');
                ybi_print_jquery_notice_message($service_reset . ' Disconneced', 'error');
                //echo("<script>jQuery('#ybi_oauth_message').html('<div class=\"notice error my-acf-notice is-dismissible\" ><p>".$service_reset." Disconnected</p></div>');</script>");
            }
        }

        $Table = new ybi\html\Table(
            'platform_overview_table',
            array('wp-list-table', 'widefat','fixed posts'),
            array('alternate'=>true,'tbody_class'=>'overview_body') );

        $Row = new ybi\html\Row('',array());
        $Column = new ybi\html\Column('left_platform_setting');
        $Column->setContent('Platform/Service');
        $Row->addColumn($Column);
        $Column = new ybi\html\Column('right_platform_setting');
        $Column->setContent('Status');
        $Row->addColumn($Column);
        $Column = new ybi\html\Column('right_platform_setting');
        $Column->setContent('Actions');
        $Row->addColumn($Column);
        $Table->setTitleRow($Row);

        // Begin Pocket
        $Row = new ybi\html\Row('',array());
        $Row->addColumnContent('<a href="https://getpocket.com/" target="_blank" title="Check out Pocket"><img src="'. YBI_BASE_URL . '/i/pocket-logo.png" /></a>');
        $pocket_access_data = get_option('cs_pocket_access_data');
        $connected_text = '<i class="fa fa-circle good" aria-hidden="true"></i> Connected';
        $action_link = '';
        global $pagenow;
        $reset_link = admin_url('admin.php?page=youbrandinc-oauth&service_reset=Pocket');
        $pocket_reset_link = '<a href="'.$reset_link.'">Delete/Reset Connection</a>';
        $action_link = $pocket_reset_link;
        if($pocket_access_data) {
            if(is_array($pocket_access_data) && array_key_exists('username',$pocket_access_data)) {
                $connected_text .= ': <i>'.$pocket_access_data['username'].'</i>';
            }
        } else {
            $connected_text = '<i class="fa fa-circle bad" aria-hidden="true"></i> Not Connected';
        }
        require_once YBI_BASE_PATH . "oauth/pocket.php";
        $Row->addColumnContent($connected_text);
        $Row->addColumnContent($action_link);
        $Table->addRow($Row);
        // End Pocket

        // Begin ImgUR
        $Row = new ybi\html\Row('',array());
        $Row->addColumnContent('<a href="https://getpocket.com/" target="_blank" title="Check out Pocket"><img src="'. YBI_BASE_URL . '/i/imgur-logo.png" style="margin-left: 5px;" /></a>');
        $imgur_access_data = get_option('cs_ImgUr_access_data');
        $connected_text = '<i class="fa fa-circle good" aria-hidden="true"></i> Connected';
        $action_link = '';
        $reset_link = admin_url('admin.php?page=youbrandinc-oauth&service_reset=ImgUr');
        $pocket_reset_link = '<a href="'.$reset_link.'">Delete/Reset Connection</a>';
        $action_link = $pocket_reset_link;
        if($imgur_access_data) {
            if(is_array($imgur_access_data) && array_key_exists('account_username',$imgur_access_data)) {
                $connected_text .= ': <i>'.$imgur_access_data['account_username'].'</i>';
            }
        } else {
            $connected_text = '<i class="fa fa-circle bad" aria-hidden="true"></i> Not Connected';
        }
        require_once YBI_BASE_PATH . "oauth/imgur.php";
        $Row->addColumnContent($connected_text);
        $Row->addColumnContent($action_link);
        $Table->addRow($Row);
        // End ImgUr
        echo $Table->getFullTableHTML();

        ?>
    </div><!--inner-->
</div><!--wrap-->