<?php
/* 
Plugin Name: Agile CRM
Plugin URI: #
Version: v1.0.3
Author: Agile CRM
Developer: Agile CRM
Author URI: http://www.agilecrm.com
Developer URL: http://www.agilecrm.com
Description:  Agile CRM is an all-in-one, affordable and next-gen Customer Relationship Management (CRM) software with marketing, sales and service automation, built with love for small businesses.
Copyright (C) 2007-2016 www.agilecrm.com
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
error_reporting(0);
if( !defined( 'ABSPATH' ) ) {
        exit( 'You are not allowed to access this file directly.' );
}
require("curlwrap_v2.php");
// Register style sheet.
add_action('wp_enqueue_styles', 'agilecrm_css' );
/**
 * Register style sheet.
 */
function agilecrm_css() {
   wp_enqueue_style( 'agile-crm-lead-management', plugins_url( '/css/style.css', __FILE__ ) );
}
add_action( 'wp', 'agilecrm_landing_page_setpup', 10, 0 ); 
function agilecrm_landing_page_setpup(){
    if(!is_admin()){
        global $post;
        $landing_page = get_post_meta($post->ID, 'landing_page',true);
        if($landing_page != ""){
            $domain = (esc_textarea(get_option( "agile_domain" )));
            $email= (esc_textarea(get_option( "agile_email" )));
            $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
            if($domain != "" && $email != "" && $rest_api != ""){
               $request  = wp_remote_get('https://'.$domain.'.agilecrm.com/landing/'.$landing_page);
               echo $response = wp_remote_retrieve_body( $request );
               die();
            }
        }
    } 
}
add_action( 'init', 'agilecrm_list_agile_form', 10, 0 ); 
function agilecrm_list_agile_form(){
    if(isset($_GET["agile_list_form"]) == 1){
        $domain = (esc_textarea(get_option( "agile_domain" )));
        $email= (esc_textarea(get_option( "agile_email" )));
        $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
        if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("forms", null, "GET", "application/json",$email,$rest_api,$domain);
           $result = json_decode($result, false);
           $data = array();
           if(count($result) > 0){           
               foreach($result as $k => $v){
                   $tmp = array();
                   $tmp["text"] = $v->formName;
                   $tmp["value"] = $v->id;
                   $data[] = $tmp;
               }
           }
           echo json_encode($data); 
        }
        die();
    }
}
add_action('admin_menu', 'agilecrm_create_menu');
function agilecrm_create_menu() {
     add_menu_page('Agile CRM', 'Agile CRM', 'administrator', 'agilecrm_dashboard', 'agilecrm_dashboard_page',plugins_url( 'agile-crm-lead-management/images/icon.png' ),0);
    add_submenu_page('agilecrm_dashboard', 'Home', 'Home', 'administrator', 'agilecrm_dashboard', 'agilecrm_dashboard_page');
    add_submenu_page('agilecrm_dashboard','Web Rules','Web Rules','administrator', 'agilecrm_webrules', 'agilecrm_webrules_page' );
    add_submenu_page('agilecrm_dashboard','Form Builder','Form Builder','administrator', 'agilecrm_formbuilder', 'agilecrm_formbuilder_page' );
    add_submenu_page('agilecrm_dashboard','Landing Pages','Landing Pages','administrator', 'agilecrm_landing', 'agilecrm_landing_page' );
    add_submenu_page('agilecrm_dashboard','Email Campaigns','Email Campaigns','administrator', 'agilecrm_email', 'agilecrm_email_page' );
    add_submenu_page('agilecrm_dashboard','Web Stats','Web Stats','administrator', 'agilecrm_webstats', 'agilecrm_webstats_page' );
    add_submenu_page('agilecrm_dashboard','Refer a Friend','Refer a Friend','administrator', 'agilecrm_refer', 'agilecrm_refer_page' );
    add_submenu_page('agilecrm_dashboard','Settings','Settings','administrator', 'agilecrm_settings', 'agilecrm_settings_page' );
}
function agilecrm_header($domain){
 $agile_email = (esc_textarea(get_option( "agile_email" )));
 $agile_password = (esc_textarea(get_option( "agile_password" )));
 agilecrm_css();
 $page_name = $_GET['page'];
 if($page_name == 'agilecrm_dashboard')
 {
  agilecrm_hidedata_js(); ?> <div id="agilewrapper" class="textaligncenter">
<?php echo "<img src='".plugins_url( '/images/agile-crm.png', __FILE__ )."'  title='Agile Crm logo'/>"; ?> </div>
<?php $webstats= get_option( "agile_webstats" );
    if($webstats === false){
        add_option( "agile_webstats", 1, $deprecated, $autoload );
        $webstats= 1;
    }
    $webrules = get_option( "agile_webrules" );
    if($webrules === false){
        add_option( "agile_webrules", 1, $deprecated, $autoload );
       $webrules = 1;
    }
    $status = $_GET['status'];
    if($status){
    echo "<div class='label-success'>Your settings have been updated successfully.</div>";
    }
    $restatus = $_GET['restatus'];
    if($restatus) {
      echo "<div class='label-success'>You have successfully created your Agile CRM account.</div>";
    }
} else { ?> <div id="agilewrapper2">
<?php echo "<img src='".plugins_url( '/images/agile-crm.png', __FILE__ )."' title='Agile Crm logo'/>"; ?> </div>
<?php }
}
function agilecrm_dashboard_page(){
    $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    $password= (esc_textarea(get_option( "agile_password" )));
   if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    } else {
    $deprecated = null;
    $autoload = 'no';
    if($_POST["featuresform"] && check_admin_referer( 'agilecrm_nonce_action', 'agilecrm_nonce_field' )){
        $webrules = intval($_POST["webrules"]);
        $webstats = intval($_POST["webstats"]);
        if ( get_option( "agilecrm_webrules" ) !== false ) {
            update_option( "agilecrm_webrules", $webrules);
        } else {
            add_option( "agile_webrules", $webrules, $deprecated, $autoload );
        }
             $pages = get_pages(); 
             foreach ( $pages as $page ) {
                 if($webrules == "0")
                     update_post_meta($page->ID , 'enable_webrule', 2);
                 else
                     update_post_meta($page->ID , 'enable_webrule', 1);
             }
        if ( get_option( "agile_webstats" ) !== false ) {
            update_option( "agile_webstats", $webstats);
        } else {
            add_option( "agile_webstats", $webstats, $deprecated, $autoload );
        }
             $pages = get_pages(); 
             foreach ( $pages as $page ) {
                 if($webstats == "0")
                     update_post_meta($page->ID , 'enable_webstats', 2);
                 else
                    update_post_meta($page->ID , 'enable_webstats', 1);
             }
    }
agilecrm_header($domain);
?> <div id="features">
<?php $webrules = get_option( "agile_webrules" );
$webstats = get_option( "agile_webstats" );
?> <form action="#" method="post" >
<?php wp_nonce_field('agilecrm_nonce_action','agilecrm_nonce_field'); ?>
<input type="hidden" name="featuresform" id="featuresform" value="featuresform" />
<a href="admin.php?page=agilecrm_webrules" id="boxm" >
<div class="box">
  <div class="right stripline">
   <div class="header"><?php echo "<img src='".plugins_url( '/images/webrules.svg', __FILE__ )."' width='60px' height='60px' title='Web Rules'/>";?> </div>
   <h2 class="heading">
   <input type="checkbox" id="webrules" name="webrules" value="1" <?php if($webrules== 1){echo " checked ";} ?> />
   Web Rules</h2>
   <h5>Web Rules automate actions in response to user activity on your website.</h5>
   <a href="admin.php?page=agilecrm_webrules" class="more">More</a>
  </div>
</div></a>
<a href="admin.php?page=agilecrm_formbuilder" id="boxm">
<div class="box">
  <div class="right stripline">
    <div class="header"><?php echo "<img src='".plugins_url( '/images/form.svg', __FILE__ )."' width='60px' height='60px' title='Form Builder'/>";?> </div>
    <div class="left">
    </div>
    <h2 class="heading">Form Builder</h2>
   <h5>Agile helps you create your custom Web Rules at ease and place it on your website.</h5>
   <a href="admin.php?page=agilecrm_formbuilder" class="more">More</a>
   </div> 
 </div></a>
<a href="admin.php?page=agilecrm_landing" id="boxm"> 
   <div class="box">
   <div class="right stripline">
   <div class="header"><?php echo "<img src='".plugins_url( '/images/landing.svg', __FILE__ )."' width='60px' height='60px' title='Landing Pages'/>";?> </div>
   <div class="left">
   </div>
   <h2 class="heading">Landing Pages</h2>
   <h5>The Landing Page Builder helps create high converting landing pages.</h5>
   <a href="admin.php?page=agilecrm_landing" class="more">More</a>
 </div>
</div> </a>
<a href="admin.php?page=agilecrm_email" id="boxm">
  <div class="box">
   <div class="right stripline">
    <div class="header"><?php echo "<img src='".plugins_url( '/images/mail.svg', __FILE__ )."' width='60px' height='60px' title='Email Campaigns'/>";?> </div>
    <div class="left">
    </div>
    <h2 class="heading">Email Campaigns</h2>
    <h5>Send newsletters and track performance with Agile CRM's email marketing tools.</h5>
    <a href="admin.php?page=agilecrm_email" class="more">More</a>
   </div>
</div> </a>
<a href="admin.php?page=agilecrm_webstats" id="boxm">
<div class="box">
  <div class="right stripline">
     <div class="header"><?php echo "<img src='".plugins_url( '/images/webstats.svg', __FILE__ )."' width='60px' height='60px' title='Web Stats'/>";?> </div>
     <h2 class="heading"> <input type="checkbox" name="webstats" value="1" <?php if($webstats == 1){echo " checked ";} ?> /> Web Stats</h2>
    <h5>Agile gives you deep insight into customer behavior and website performance.</h5>
    <a href="admin.php?page=agilecrm_webstats" class="more">More</a>
  </div> 
</div></a> 
<a href="admin.php?page=agilecrm_refer" id="boxm">
  <div class="box">
    <div class="right stripline">
    <div class="header"><?php echo "<img src='".plugins_url( '/images/refer a friend.svg', __FILE__ )."' width='60px' height='60px' title='Refer a Friend'/>";?> </div>
    <div class="left">
    </div>
    <h2 class="heading">Refer a Friend</h2>
    <h5 >Our in-app referral program for all of Agile CRMs users is currently effective.</h5>
    <a class="more"  href="admin.php?page=agilecrm_refer">Refer</a>
   </div>
</div></a>
</form>
</div>
<div class="create_account" id="submitclass">
<div id="cloud_logo">
<h2>Agile CRM </h2>
<?php echo "<img src='".plugins_url( '/images/agilecrm-cloud-png.png', __FILE__ )."' title='preview' width='20%'/>"; ?> 
</div>
<div class="create_account_dashborad">
<h4 id="create_content">Complete All-In-One CRM with Marketing, Sales & Service Automation with Telephony, Helpdesk, Web Engagement, Social Media Integration, Email Campaigns, Mobile Marketing and much more.</h4>
<a id="agile-top-button" class="btn btn-default"  href="https://<?php echo $domain; ?>.agilecrm.com/" target="_blank">Access Agile CRM Account & Setup</a>
</div>
</div>
<?php 
agilecrm_customfeautre_js();
agilecrm_customsubmit_js();
 }
}
function agilecrm_webstats_page(){
     $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    }else{
    if(isset($_POST["featuresform"]) && check_admin_referer( 'agilecrm_nonce_action', 'agilecrm_nonce_field' )){
        $webstats= intval($_POST["webstats"]);
        if ( get_option( "agile_webstats" ) !== false ) {
            update_option( "agile_webstats", $webstats);
        } else {
            add_option( "agile_webstats", $webstats, $deprecated, $autoload );
        }
             $pages = get_pages(); 
             foreach ( $pages as $page ) {
                 if($webstats == "0")
                     update_post_meta($page->ID , 'enable_webstats', 2);
                 else
                     update_post_meta($page->ID , 'enable_webstats', 1);
             }
    }
    //agile_css();
    agilecrm_header($domain);
    $webstats= get_option( "agile_webstats" );
    if($webstats === false){
        add_option( "agile_webstats", 1, $deprecated, $autoload );
        $webstats= 1;
    }
?>
<div id="features">
<form action="" method="post">
<?php wp_nonce_field('agilecrm_nonce_action','agilecrm_nonce_field'); ?>
<input type="hidden" name="featuresform" value="featuresform">
<div class="mainLeftbox">
<?php //echo "<img src='".plugins_url( '/landing.png', __FILE__ )."' class='contentimage'  title='Landing Page' width='95%'/>"; ?> 
 <div class="box" id="webstatsi">
  <div class="right stripline">
     <div class="header"><?php echo "<img src='".plugins_url( '/images/webstats.svg', __FILE__ )."' width='60px' height='60px' title='Web Stats'/>";?></div>
      <br/> <br/>
     <h5>Get actionable insights into customer acitvity on your site or app and detailed web analytics reports for each of your customers. Learn about the traffic source of your new customers with referral URL reporting in Agile CRM, and show personalized messages to your contacts.</h5>
     <h2 class="heading"> <input type="checkbox" name="webstats" value="1" <?php if($webstats == 1){echo " checked ";} ?> /> Web Stats</h2>
  </div> 
</div>
</div>
<div class="mainrightbox">
  <div class="box-right">
             <div id="my-content-id_webrules">
                <h3 class="font-thin h3">WEB STATS</h3>
               <p>Web Stats-By ticking the checkbox, the analytics will be automatically activated and synced with your Agile CRM account. You can view this in your dashboard. Agile's Analytics CRM gives you deep insight into customer behavior and website performance.</p>
                <?php echo "<img src='".plugins_url( '/images/web-stats.png', __FILE__ )."' width='100%' title='Web Stats'/>";?>
               <p> Agile offers you contact-level analytics and web traffic analysis for companies that are serious about growth.</p>
            </div>        
            <h3 class="m-t-none h4 m-b-sm">WEB STATS</h3>
            <p>Agile's Analytics CRM gives you deep insight into customer behavior and website performance. Agile offers you contact-level analytics and web traffic analysis for companies that are serious about growth.</p>
         <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/PYCMsB5YPN4" frameborder="0" allowfullscreen="" class="wp-campaigns-video"></iframe>
        </p>
        <p>You can use javascript API to track page views on your site, add / delete contacts from your website or blog directly. Copy and paste the below code in your webpage's HTML just before the </BODY> tag to enable tracking / API methods.</p>
        <a href='https://www.agilecrm.com/crm-analytics' target='_blank' class='fb-read'>Read more</a>
     </div>
 </div>
</form>
<div class="clear"></div>
<?php agilecrm_customfeautre_js(); ?>
</div>
<?php }
}
function agilecrm_webrules_page(){
    $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    }else{
    if(isset($_POST["featuresform"]) && check_admin_referer( 'agilecrm_nonce_action', 'agilecrm_nonce_field' )){
        $webrules = intval($_POST["webrules"]);
        if ( get_option( "agile_webrules" ) !== false ) {
            update_option( "agile_webrules", $webrules);
        } else {
            add_option( "agile_webrules", $webrules, $deprecated, $autoload );
         }
         $pages = get_pages(); 
             foreach ( $pages as $page ) {
                 if($webrules == "0")
                     update_post_meta($page->ID , 'enable_webrule', 2);
                 else
                     update_post_meta($page->ID , 'enable_webrule', 1);
             }
    }
    agilecrm_header($domain);
    $webrules = get_option( "agile_webrules" );
    if($webrules === false){
        add_option( "agile_webrules", 1, $deprecated, $autoload );
       $webrules = 1;
    }
    $agile_email = (esc_textarea(get_option( "agile_email" )));
    $agile_password = (esc_textarea(get_option( "agile_password" )));
    agilecrm_custom_pubnub_refresh_js ();
?><div id="features">
<div class="mainLeftbox">
<div id="my-content-id_webrules" id="crmj">
<h3 class="font-thin h3">Templates</h3>
<?php   $url = "https://".$domain.".agilecrm.com/misc/modal-templates/webrule-templates.json";
    $request  = wp_remote_get( $url );
    $response = wp_remote_retrieve_body( $request );
    $arrayresult = json_decode($response, true);
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $hrefwebrules ="https://".$domain.".agilecrm.com/#webrules-templates";
    foreach($arrayresult as $webv){  
      $c = count($webv);
           for($i = 0; $i <= $c; $i++) {
               if (is_array($webv[$i]['list']) || is_object($webv[$i]['list']))
{
               foreach($webv[$i]['list'] as $list){ 
                $link = "https://".$domain.".agilecrm.com/login#webrules-add/".$list['link'];
                $id = $list['id'];
                if($list['thumbnail'] == "misc/modal-templates/form/split-message/images/split-message.png"){
                  $list['thumbnail'] = "misc/modal-templates/form/split-message/images/split-message-update.png";
                $imgpath = "https://".$domain.".agilecrm.com/".$list['thumbnail'];
                }else{
                   $imgpath = "https://".$domain.".agilecrm.com/".$list['thumbnail'];
                }
                 ?> <div class="landing-icon"><div class="langing-img"> <?php echo"<img src='$imgpath' width='87%' id='heightimg'>"; ?> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="https://<?php echo $domain; ?>.agilecrm.com/login#web-rules" target="_blank" >Go</a>
             
        </div></div></div>
          <?php }
}
             }
         } ?>
</div> 
<?php if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("webrule", null, "GET", "application/json",$email,$rest_api,$domain);
           $result = json_decode($result, false);
           echo "<div class='crm-form-list' id='crmj'>"; ?>
             <div class='formbilder'><div class='add-forms'><a class='more' target='_blank' href="https://<?php echo $domain;?>.agilecrm.com/login#web-rules">Create Web rules</a>
             <a onclick='window.location.reload();' title="Refresh" class='reload more'>&#x21bb;</a></div></div>
           <?php $i = 1;
           if(count($result) > 0){ ?>
              <div class="formbilder">
            <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="name" class="manage-column column-description">Action</th>  
                       <th scope="col" id="name" class="manage-column column-description"></th>
                     </tr>
                   </thead>
                   <tbody id="the-list">
                   <?php foreach($result as $k => $v){ ?>                   
                   <?php echo "<tr><th><strong>".$i.".</strong></th><th>".ucfirst($v->name)."</th>"; ?>
                   <th><?php $actions = $v->actions; 
                       foreach($actions as $as => $av){
                           $action = $av->action;
                           if($action == 'CORNER_NOTY'){
                            $out = 'Noty message';
                           }
                           $output = str_replace('_', ' ', $action);
                           $output = strtolower($output);
                           echo $out = ucfirst($output);
                      }
                  ?> </th>
                  <th><?php $disable = $v->disabled; 
                   if($disable){ ?> <span id="disablebutton">Disabled</span> <?php }
                   ?></th>
                  </tr>
                  <?php $i++;
               } ?>
                </tbody>
                 </table>
                 </div>
              <?php }else{ ?>
              <div class="formbilder">
                 <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No.</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
              <?php echo "<tr><th id='count' colspan='3'>Sorry, you don't have any Web Rules yet.</th></tr>";?>
                   </tbody>
                 </table>
               </div>
              <?php }
              ?> </div>
           <?php }
?> </div>
<div class="mainrightbox">
  <div class="box-right">  
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Web Rules</h3>
<p>Web Rules allow you to set action items for your website visitors. Based on the actions, you can place a popup, increase the lead score, and many other things through Web Rules. </p>
<?php echo "<img src='".plugins_url( '/images/webrules.png', __FILE__ )."' class='contentimage' title='webrules' width='95%'/>"; ?>
<p>For example, when visitors are about to leave your website, show them a sign-up popup. When contacts in Agile visit a specific product page, send them an email asking if they need a demo.</p>
</div>      
      <h3 class="m-t-none h4 m-b-sm">Setup Tracking Code</h3>
      <p>To start using Web Rules, please setup our <a href="#analytics-code/analytics">tracking code</a>.</p>
       <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/kVg39upuT1s" frameborder="0" allowfullscreen class="wp-campaigns-video"></iframe>
      </p>
      <h4 class="m-t-none h4 m-b-sm">What are Web Rules?</h4>
     <p>
      Web Rules allow you to perform certain actions when people visit your website - like showing a popup or increase score. <br><br>
      For example, when visitors are about to leave your website, show them a sign-up popup. When contacts in Agile visit a specific product page, send them an email asking if they need a demo.</p>
      <a href='https://www.agilecrm.com/marketing-automation/web-rules' target='_blank' class='fb-read'>Read more</a>
     </div>
 </div>
</form>
<?php
 agilecrm_customfeautre_js();
?>
</div>
<?php }
}
function agilecrm_landing_page(){
    $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    }else{
    //agile_css();
    agilecrm_header($domain);
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $hreflink = "https://".$domain.".agilecrm.com/";
    $agile_email = (esc_textarea(get_option( "agile_email" )));
    $agile_password = (esc_textarea(get_option( "agile_password" )));
    agilecrm_custom_pubnub_refresh_js();
?>
<div id="features">
<div class="mainLeftbox">
<div id="my-content-id_landing">
<h3 class="font-thin h3">Template</h3>
<div class=""><div class="langing-img">
<?php echo "<img src='".plugins_url( '/images/landingpage_main.png', __FILE__ )."' title='Preview' width='20px' height='20px'/>";
?> <div>
    <a class="btn btn-sm btn-default landingPageTemplateSelect" target="_blank" href="https://<?php echo $domain; ?>.agilecrm.com/login#landing-pages">Go</a>
        </div></div></div>
</div>
<?php    if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("landingpages", null, "GET", "application/json",$email,$rest_api,$domain);          
           if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $result = json_decode($result,false, 512, JSON_BIGINT_AS_STRING);
            } else {
            $result = json_decode($result,false);
           }
           echo "<div class='crm-form-list'>"; ?>
             <div class='formbilder'><div class='add-forms'><a class='more' href="https://<?php echo $domain; ?>.agilecrm.com/login#landing-pages" target='_blank' >Create Landing Pages</a>
             <a onclick='window.location.reload();' title="Refresh" class='reload more'>&#x21bb;</a></div></div>
           <?php $i = 1;
           if(count($result) > 0){ ?>
              <div class="formbilder">
            <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No.</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="name" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
                   <tbody id="the-list">
                   <?php foreach($result as $k => $v){ ?>
                    
                   <?php echo "<tr><th><strong>".$i.".</strong></th><th>".$v->name."</th>"; ?>
                   
                  <th><?php echo "<a target='_blank'  id='preview' href='https://".$domain.".agilecrm.com/landing/".$v->id."'> <img src='".plugins_url( '/images/preview.png', __FILE__ )."' title='Preview' width='20px' height='20px'/></a>"; ?> </th></tr>
                  <?php $i++;
               } ?>
                </tbody>
                 </table>
                 </div>
              <?php }else{ ?>
              <div class="formbilder">
                 <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">SNo</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
              <?php echo "<tr ><th id='count' colspan='3'>Sorry, you dont have any Landing Pages yet.</th></tr>";?>
                   </tbody>
                 </table>
               </div>
              <?php }
              ?>
           <?php }
?> </div>
<hr id="hrheight"></hr>
</div>
<div class="mainrightbox">
  <div class="box-right">   
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Landing Page</h3>
<p>Landing pages are your lead magnet - a web page created to gather leads online. Create a landing page in Agile CRM and link it from your website, email messages or online ads.</p>
 <?php echo "<img src='".plugins_url( '/images/landing.png', __FILE__ )."' class='contentimage'  title='Landing Page' width='95%'/>"; ?> 
 <p>Add a form to your landing page to gather visitor details, create contacts in Agile CRM automatically, and nurture them using campaigns.</p>
</div>     
            <h3 class="m-t-none h4 m-b-sm">How to use Landing Pages?</h3>
      <div>
      Landing page is your lead magnet - a web page created to gather leads online. Create a landing page in Agile and link it from your website, email messages or online ads. Add a Form to your landing page to gather visitor details, create Contacts in Agile automatically and nature them using Campaigns.
      </div>
       <p>
        <iframe width="100%" height="180px" class='embed-responsive-item wp-campaigns-video' src='https://www.youtube.com/embed/iVTvUoEXTKY' frameborder='0' allowfullscreen=''></iframe>
     </p>
      <h4 class="m-t-none h4 m-b-sm">What are Landing Pages?</h4>
     <p>
      The Landing Page Builder helps create high converting landing pages in Agile CRM. With Agile's rich and customizable templates, drag & drop designer features, web forms, responsive designs and code editor, experience a new level in building high quality landing pages.</p>
      <a href='https://www.agilecrm.com/landing-page' target='_blank' class='fb-read'>Read more</a>
     </div>
 </div>
 </div>
<?php }
}
function agilecrm_formbuilder_page(){
    $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    }else{
 agilecrm_header($domain);
 add_thickbox();
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $url = "https://".$domain.".agilecrm.com/misc/formbuilder/templates/templates.json";
    $request  = wp_remote_get( $url );
    $response = wp_remote_retrieve_body( $request );
    $arrayresult = json_decode($response, true);
    $hrefwebrules ="https://".$domain.".agilecrm.com/#formbuilder?template";
   $agile_email = (esc_textarea(get_option( "agile_email" )));
 $agile_password = (esc_textarea(get_option( "agile_password" )));
 agilecrm_custom_pubnub_refresh_js();
?>
<div id="features">
<div class="mainLeftbox">
<div id="my-content-id_fombuilders">
<h3 class="font-thin h3">Templates</h3>
   <?php foreach($arrayresult as $webv){  
      $c = count($webv);
           for($i = 0; $i <= $c; $i++) {
               if (is_array($webv[$i]['list']) || is_object($webv[$i]['list']))
{
               foreach($webv[$i]['bootstrap-list'] as $bootstraplist){ 
                $link = "https://".$domain.".agilecrm.com/login#formbuilder?template".$bootstraplist['json-path'];
                $id = $bootstraplist['id'];
                $imgpath = "https://".$domain.".agilecrm.com/misc/formbuilder/templates/".$bootstraplist['id']."/".$bootstraplist['img-path'];
                 ?>
               <div class="landing-icon"><div class="langing-img"> <?php echo"<img src='$imgpath' width='87%'>"; ?> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="https://<?php echo $domain;?>.agilecrm.com/#formbuilder?template"  target="_blank" >Go</a>
             
        </div></div></div>
          <?php }
          foreach($webv[$i]['metro-list'] as $metrolist){ 
                $link = "https://".$domain.".agilecrm.com/login#formbuilder?template".$metrolist['json-path'];
                $id = $metrolist['id'];
                $imgpath = "https://".$domain.".agilecrm.com/misc/formbuilder/templates/".$metrolist['id']."/".$metrolist['img-path'];
                 ?>
               <div class="landing-icon"><div class="langing-img"> <?php echo"<img src='$imgpath' width='87%'>"; ?> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="https://<?php echo $domain;?>.agilecrm.com/#formbuilder?template"  target="_blank" >Go</a>
             
        </div></div></div>
          <?php }
           foreach($webv[$i]['flat-list'] as $flatlist){ 
                $link = "https://".$domain.".agilecrm.com/login#formbuilder?template".$flatlist['json-path'];
                $id = $flatlist['id'];
                $imgpath = "https://".$domain.".agilecrm.com/misc/formbuilder/templates/".$flatlist['id']."/".$flatlist['img-path'];
                 ?>
               <div class="landing-icon"><div class="langing-img"> <?php echo"<img src='$imgpath' width='87%'>"; ?> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="https://<?php echo $domain;?>.agilecrm.com/#formbuilder?template"  target="_blank" >Go</a>
             
        </div></div></div>
          <?php }
           foreach($webv[$i]['material-list'] as $materiallist){ 
                $link = "https://".$domain.".agilecrm.com/login#formbuilder?template".$materiallist['json-path'];
                $id = $materiallist['id'];
                $imgpath = "https://".$domain.".agilecrm.com/misc/formbuilder/templates/".$materiallist['id']."/".$materiallist['img-path'];
                 ?>
               <div class="landing-icon"><div class="langing-img"> <?php echo"<img src='$imgpath' width='87%'>"; ?> <div>
            <a class="btn btn-sm btn-default landingPageTemplateSelect" href="https://<?php echo $domain;?>.agilecrm.com/#formbuilder?template"  target="_blank" >Go</a>
             
        </div></div></div>
          <?php }
}
             }
         } ?>
</div>
<?php if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("forms", null, "GET", "application/json",$email,$rest_api,$domain);
           $result = json_decode($result, false);
           echo "<div class='crm-form-list'>";
           $i = 1; ?>
           <div class='formbilder'><div class='add-forms'><a class='more' href="https://<?php echo $domain;?>.agilecrm.com/#formbuilder?template" target="_blank">Create Forms</a>
           <a onclick='window.location.reload();' title="Refresh" class='reload more'>&#x21bb;</a></div></div>
          <?php  if(count($result) > 0){ ?>
           <div class="formbilder">
              <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No.</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
                   <tbody id="the-list">
                   <?php foreach($result as $k => $v){ ?>
                    
                   <?php echo "<tr><th><strong>".$i.".</strong></th><th>".$v->formName."</th>"; ?>
                   <div id="my-content-id_<?php echo $i ?>" style="display:none;">
                    <p>
                     <?php echo $v->formHtml; ?>   
                    </p>
                  </div>
                  <th><a href="#TB_inline?width=600&height=550&inlineId=my-content-id_<?php echo $i ?>" class="thickbox" id="preview"><?php echo "<img src='".plugins_url( '/images/preview.png', __FILE__ )."' title='Preview' width='20px'  height='20px'/>"; ?> </a> 
                  </th></tr>
                  <?php $i++;
               }
               ?>
               </tbody>
                 </table>
          </div>
           <?php }else{ ?>
              <div class="formbilder">
                 <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
              <?php echo "<tr><th id='count' colspan='3'>Sorry, you dont have any Forms yet.</th></tr>";  ?>
                   </tbody>
                 </table>
               </div>
              <?php }
          } ?>
</div>
</div>
<div class="mainrightbox">
  <div class="box-right">  
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Form Builder</h3>
<p>Form Builder helps you create your custom web forms easily, and you can place it on your website. Whenever a web visitor fills up the web form, a new contact gets created in Agile CRM, and all the data submitted through the form gets added to the contact page as various attributes </p>
<?php echo "<img src='".plugins_url( '/images/frombuilder.png', __FILE__ )."' class='contentimage'  title='Form Builder' width='95%'/>"; ?>
<p> Name, Company, Phone number, Email, Address, Notes etc. Also, you can keep tracking this contact whenever he visits your website and get his detailed browsing history on the contact page.</p>
</div>      
      <h3 class="m-t-none h4 m-b-sm">What are Forms?</h3>
      <p>
      Forms created using the Form Builder can be placed on your website or app. These Forms are readily linked to your Agile account.  When a visitor fills the form, a Contact is created and subsequent web activity is logged automatically.</p>
       <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/jarxzsC_R0g" frameborder="0" allowfullscreen="" class="wp-campaigns-video"></iframe>
     </p>
      <p>Agile's Form Builder helps you create your custom web forms at ease and place it on your website. Whenever a web visitor fills up the web form, a new contact gets created in Agile and all the data submitted through the form gets added to the contact page as various attributes - Name, Company, Email, Phone no, Address, Notes etc, Also, you can keep tracking this contact whenever he visits your website & get his detailed browsing history on the contact page.</p>
      <a href='https://www.agilecrm.com/web-engagement' target='_blank' class='fb-read'>Read more</a>
     </div>

 </div>
 </div>
<?php }
}
function agilecrm_refer_page(){
    $domain = (esc_textarea(get_option( "agile_domain" )));
    agilecrm_header($domain); ?>
<div id="features">
<div class="mainLeftbox">
 <div class="box" id="boxjss">
  <div class="right stripline">
    <div class="header"> <?php  echo "<img src='".plugins_url( '/images/refer a friend.svg', __FILE__ )."' title='Refer a Friend' width='60px' height='60px'/>"; ?></div>
    <div class="left">
    </div>
    <h2 class="heading">Refer a Friend</h2>
   <h5>Our customers are our biggest ambassadors. Your love for the product is what makes you refer other awesome clients to us time and again. Not surprisingly, 'word of mouth' has been our most successful source of new customers.
     Weâ€™d like to show our gratitude to all of you who take the time to recommend us to your colleagues and partners.</h5>
     </div> 
 </div>
 </div>
<div class="mainrightbox">
  <div class="box-right">        
      <h3 class="m-t-none h4 m-b-sm">Refer a Friend</h3>
      <div id="textaligncenter">
                  <div class="refer-friend-desc"> 
                    <p><span>Follow us on Twitter:<br/></span> Follow @agilecrm on Twitter and share about Agile CRM to claim <span>500 extra</span> emails.</p>
                    <p><span>Tweet about us:<br/></span> Mention and retweet about Agile CRM on your Twitter timeline to claim <span>500 extra</span> emails.</p>
                    <p><span>Share on Facebook:<br/></span> Share Agile updates with your Facebook contacts to claim <span>500 extra</span> emails.</p>
                   <p><span>Refer friends:<br/></span> Invite friends to sign up and try Agile CRM to claim <span>500 extra</span> emails per signup.</p>
                   <p><span>Write a blog about us:<br/></span> Blog about Agile CRM and spread the word for <span>2500 extra</span> emails.</p>
                  <a href="https://www.agilecrm.com/blog/agiles-crm-referral-program/" target="_blank" class="fb-read">Read more</a>
                  </div>
                 </div>
     </div>
 </div>
</div>
<?php }
function agilecrm_email_page(){
   $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    if(empty($rest_api) || empty($js_api) || empty($domain) || empty($email)){
        agilecrm_settings_page();
    }else{
    agilecrm_header($domain);
 $agile_email = (esc_textarea(get_option( "agile_email" )));
 $agile_password = (esc_textarea(get_option( "agile_password" )));
 agilecrm_custom_pubnub_refresh_js();
?>
<div id="features">
<div class="mainLeftbox" >
 <?php if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("workflows", null, "GET", "application/json",$email,$rest_api,$domain);
           $result = json_decode($result, false);
           echo "<div class='crm-form-list' id='crmj'>";
           $i = 1; ?>
           <div class='formbilder'><div class='add-forms'><a class='more' href="https://<?php echo $domain; ?>.agilecrm.com/login#workflows" target='_blank' href='#'>Manage Campaigns</a>
          <a onclick='window.location.reload();' title="Refresh" class='reload more'>&#x21bb;</a></div></div>
          <?php  if(count($result) > 0){ ?>
           <div class="formbilder">
              <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No.</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary"></th>
                     </tr>
                   </thead>
                   <tbody id="the-list">
                   <?php foreach($result as $k => $v){ ?>
                   <?php echo "<tr><th><strong>".$i.".</strong></th><th>".ucfirst($v->name)."</th>"; ?>
                   <th id="textaligncenter"><?php $disable = $v->is_disabled; 
                   if($disable){ ?><span id="disablebutton">Disabled</span> <?php } ?></th>
                  </tr>
                  <?php $i++;
               } ?>
               </tbody>
                 </table>
          </div>
           <?php }else{ ?>
              <div class="formbilder">
                 <table class="wp-list-table widefat plugins" id="form_builder_list">
                   <thead>
                     <th scope="col" id="name" class="manage-column column-name column-primary">S.No</th>
                       <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
                       <th scope="col" id="description" class="manage-column column-description">Preview</th>  
                     </tr>
                   </thead>
              <?php echo "<tr><th id='count' colspan='3'>Sorry, you dont have any Campaigns yet.</th></tr>";  ?>
                   </tbody>
                 </table>
               </div>
              <?php }
          } ?>
</div>
</div>
<div class="mainrightbox" id="bordersright">
  <div class="box-right">  
  <div id="my-content-id_webrules">
<h3 class="font-thin h3">Campaigns</h3>
<p>Email Campaigns-Track the effectiveness of your email campaigns with real-time notifications for email opens. Email tracking software takes on a better way to give the analytics on campaign emails. </p>
 <?php echo "<img src='".plugins_url( '/images/email.png', __FILE__ )."' class='contentimage' title='Email Campaigns' width='95%'/>"; ?>  
 <p>Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing with Agile's Email Marketing CRM.</p>
</div>
<div id="my-content-id_webrules">
<h3 class="font-thin h3">News Letters</h3>
<p>Newsletter-Track the effectiveness of your newsletter campaigns with real-time notifications for email opens. Email tracking software takes on a better way to give the analytics on campaign emails.</p>
<?php echo "<img src='".plugins_url( '/images/campaign-newsletter.png', __FILE__ )."' class='contentimage' title='Email Campaigns' width='95%'/>"; ?> 
<p> Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing.</p>
</div>      
      <h3 class="m-t-none h4 m-b-sm">Email Campaigns</h3>
      <p>
      Run bulk email campaigns, send newsletters and track performance with Agile CRM's email marketing tools. Enjoy effective templates, personalization, scoring and tagging, advanced reporting, automated responders, real-time alerts and Email A/B testing with Agile's Email Marketing CRM.</p>
       <p>
        <iframe width="100%" height="180px" src="https://www.youtube.com/embed/pXwKUnQa5Ec" frameborder="0" allowfullscreen class="wp-campaigns-video"></iframe>
     </p>
     <a href="https://www.agilecrm.com/email-marketing" target="_blank" class="fb-read">Read more</a>
     </div>

 </div>
</div>
<?php }
}
function agilecrm_settings_page(){
    $deprecated = null;
    $autoload = 'no';
    $getdoamin = sanitize_text_field(isset($_GET["domain"]));
    if($getdoamin){
        $domain = sanitize_text_field($_GET["domain"]);
        $email= sanitize_text_field($_GET["emailid"]);
        $password= sanitize_text_field($_GET["password"]);
        $result = agilecrm_curl_wrap("api-key", null, "GET", "application/json",$email,$password,$domain);
        $arr = json_decode($result, TRUE);
        extract($arr);
        $rest_api = $api_key;
        $js_api = $js_api_key;
        if ( get_option( "agile_rest_api" ) !== false ) {
            update_option( "agile_rest_api", $rest_api);
        } else {
            add_option( "agile_rest_api", $rest_api, $deprecated, $autoload );
        }

        if ( get_option( "agile_js_api" ) !== false ) {
            update_option( "agile_js_api", $js_api);
        } else {
            add_option( "agile_js_api", $js_api, $deprecated, $autoload );
        }
        if ( get_option( "agile_domain" ) !== false ) {
            update_option( "agile_domain", $domain);
        } else {
            add_option( "agile_domain", $domain, $deprecated, $autoload );
        }

        if ( get_option( "agile_email" ) !== false ) {
            update_option( "agile_email", $email);
        } else {
            add_option( "agile_email", $email, $deprecated, $autoload );
        }
        $hash_password = wp_hash_password( $password );
        if ( get_option( "agile_password" ) !== false ) {
            update_option( "agile_password", $hash_password);
        } else {
            add_option( "agile_password", $hash_password, $deprecated, $autoload );
        } 
        agilecrm_get_customrefresh_js();    
       }
    if(isset($_POST["save"]) && check_admin_referer( 'agilecrm_nonce_action', 'agilecrm_nonce_field' )){
        $domain = sanitize_text_field($_POST["domain"]);
        $email= sanitize_text_field($_POST["email"]);
        $password= sanitize_text_field($_POST["password"]);
        $result = agilecrm_curl_wrap("api-key", null, "GET", "application/json",$email,$password,$domain);
        $arr = json_decode($result, TRUE);
        extract($arr);
        $rest_api = $api_key;
        $js_api = $js_api_key;
        if($rest_api){
        if ( get_option( "agile_rest_api" ) !== false ) {
            update_option( "agile_rest_api", $rest_api);
        } else {
            add_option( "agile_rest_api", $rest_api, $deprecated, $autoload );
        }

        if ( get_option( "agile_js_api" ) !== false ) {
            update_option( "agile_js_api", $js_api);
        } else {
            add_option( "agile_js_api", $js_api, $deprecated, $autoload );
        }


        if ( get_option( "agile_domain" ) !== false ) {
            update_option( "agile_domain", $domain);
        } else {
            add_option( "agile_domain", $domain, $deprecated, $autoload );
        }

        if ( get_option( "agile_email" ) !== false ) {
            update_option( "agile_email", $email);
        } else {
            add_option( "agile_email", $email, $deprecated, $autoload );
        }
        $hash_password = wp_hash_password( $password );
        if ( get_option( "agile_password" ) !== false ) {
            update_option( "agile_password", $hash_password);
        } else {
            add_option( "agile_password", $hash_password, $deprecated, $autoload );
        } 
        agilecrm_customrefresh_js();
        }else{
          $errors = 'Please verify the above details given in the above fields';           
        }

     }
    $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain = (esc_textarea(get_option( "agile_domain" )));
    $email= (esc_textarea(get_option( "agile_email" )));
    $password= (esc_textarea(get_option( "agile_password" )));
    agilecrm_header($domain); ?>
    <div id="features">
    <div class="mainLeftbox" id="crmj">
    <div class="well">
    <form action="?page=agilecrm_settings" method="post" >
    <div class="form-group m-t m-b-none" id="textaligncenter">       
     <h2>Settings</h2>
      </div>
      <div class="line line-lg"></div>
      <div id="holl">
      <label>Enter Domain Name</label>
      <div class="input-group">
      <?php wp_nonce_field('agilecrm_nonce_action','agilecrm_nonce_field'); ?>
       <input type="text" required name="domain" class="form-control" id="domain" value="<?php echo $domain; ?>" />
            <span class="input-group-addon">.agilecrm.com</span>
            </div>
      <label>User ID (Email Address)</label><input type="text" required name="email" class="form-control" id="email" value="<?php echo $email; ?>" />
      <label>Password</label><input required type="password" name="password" class="form-control" id="password" value="" /> 
      <input class="saveBtn"  type="submit"  name="save" value="Save"/>
<?php echo '<div id="errormessage">' . isset($errors) . '<br/></div>'; ?>
</div>
</form>
<?php 
agilecrm_custom_js();
if($email == '') { ?>
<div class="alert alert-warning">
          <div class="row" id="accountcreate">
              <div class="col-sm-7">
              <div id="opacityaccount">Do not have an account with Agile CRM?</div> <div><small class="text-muted">It's fast and free for Ten users</small></div>
              </div>
              <div class="col-sm-5">
               <input type="button" value="Create a new account" onclick="openAgileRegisterPage('wodpress')" class="btn"/>
              </div>
          </div>
</div>
<?php }else { ?>
<div id="simplediv"></div>
<div class="form-group m-t m-b-none">       
  </div>
<?php } ?>
</div>
</div>
<div class="mainrightbox">
  <div class="box-right" id="boxrights">        
      <h3 class="m-t-none h4 m-b-sm">Benefits of Agile CRM Plugin</h3>
      <ul class="listitem">
     <li>  <!--&#9679;--> Simple to integrate web rule & web stats, no need of coding knowledge.</li>
 <li>  Show real-time web popups to get more info about your website visitors and also increase the number of subscriptions or sign ups</li>
 <li>  Easily integrate customized web forms to your website or app to create or update contacts and log subsquent web activity. </li>
 <li>  Easily integrate attractive landing pages with your website using this plugin.</li>
 <li> Schedule bulk Email Campaigns for newsletters or other marketing activity, with simple drag-and-drop features</li>
</ul>
  
     </div>

 </div>
<?php }
add_action( 'load-post.php', 'agilecrm_page_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'agilecrm_page_add_post_meta_boxes' );
add_action( 'save_post', 'agilecrm_page_save_postdata' );

function agilecrm_page_post_meta_boxes_setup() {
    add_action( 'add_meta_boxes', 'agilecrm_page_add_post_meta_boxes' );
}

function agilecrm_page_add_post_meta_boxes() {
    add_meta_box('page_section',__('Agilecrm Section', 'page_metabox' ),'agilecrm_page_post_box','page','advanced','high');
}

function agilecrm_page_save_postdata( $post_id ) {
 
    if(isset($_POST["enable_webrule"])){
        $enable_webrule  = intval($_POST["enable_webrule"]);
        update_post_meta($post_id , 'enable_webrule', $enable_webrule);
    }

    if(isset($_POST["enable_webstats"])){
        $enable_webstats = intval($_POST["enable_webstats"]);
        update_post_meta($post_id , 'enable_webstats', $enable_webstats);
    }

    if(isset($_POST["landing_page"])){
        $landing_page = sanitize_text_field($_POST["landing_page"]);
        update_post_meta($post_id , 'landing_page', $landing_page);
    }
}

function agilecrm_page_post_box( $post ) {
    echo "<style>";
    echo "#page-list label{width:50%;float:left}";
    echo "</style>";
    echo "<ul id='page-list'>";
        echo "<li>";
        echo '<label for="enable_webrule">'.__("Enable Web Rule :");
        echo '</label> ';
        $enable_webrule = "";
        if(get_post_meta($post->ID, 'enable_webrule',true) == 1 || get_post_meta($post->ID, 'enable_webrule',true) == "")
            $enable_webrule = " selected " ;

        echo '<select id="enable_webrule" autocomplete="off" name="enable_webrule">';

        echo '<option value="2">No</option>';
        echo '<option value="1" '.$enable_webrule.'>Yes</option>';
        echo '</select>';

        echo "</li>";

        echo "<li>";
        echo '<label for="enable_webstats">'.__("Enable Web Stats :");
        echo '</label> ';
        $enable_webstats = "";

        if(get_post_meta($post->ID, 'enable_webstats',true) == 1 || get_post_meta($post->ID, 'enable_webstats',true) == "")
            $enable_webstats= " selected " ;

 
        echo '<select id="enable_webstats" autocomplete="off" name="enable_webstats">';

        echo '<option value="2">No</option>';
        echo '<option value="1" '.$enable_webstats.'>Yes</option>';
        echo '</select>';
        echo "</li>";

        $domain = (esc_textarea(get_option( "agile_domain" )));
        $email= (esc_textarea(get_option( "agile_email" )));
        $rest_api = (esc_textarea(get_option( "agile_rest_api" )));

        $result = agilecrm_curl_wrap("landingpages", null, "GET", "application/json",$email,$rest_api,$domain);
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $result = json_decode($result,false, 512, JSON_BIGINT_AS_STRING);
            } else {
            $result = json_decode($result,false);
        }
        $data = array();
        if(count($result) > 0){   
        echo "<li>";
        echo '<label for="landing_page">'.__("Landing Page :");
        echo '</label> ';
        $landing_page = get_post_meta($post->ID, 'landing_page',true);
        echo '<select id="landing_page" autocomplete="off" name="landing_page">';
        echo '<option value="">Select</option>';
        foreach($result as $k => $v){
            if($landing_page == $v->id)
                echo '<option value="'.$v->id.'" selected >'.$v->name.'</option>';
            else
                echo '<option value="'.$v->id.'"  >'.$v->name.'</option>';
        }
        echo '</select>';
        echo "</li>";

        }else{
          echo "<li>";
        echo '<label for="landing_page">'.__("Landing Page :");
        echo '</label> ';
        echo '<select id="landing_page" autocomplete="off" name="landing_page" disabled>';
        echo '<option value="">No Landing Pages.</option>';
        echo '</select>';
        echo "</li>";

        }
    echo "</ul>";
}
add_action('admin_head', 'agilecrm_button');
function agilecrm_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "agilecrm_add_tinymce_plugin");
        add_filter('mce_buttons', 'agilecrm_register_button');
    }
}

function agilecrm_add_tinymce_plugin($plugin_array) {
    $plugin_array['agilecrm_button'] = plugins_url( '/js/agile.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}

function agilecrm_register_button($buttons) {
   array_push($buttons, "agilecrm_button");
   return $buttons;
}

add_shortcode('agileform','agileform');
function agileform($atts,$content,$tag){
    if(isset($atts["id"])){

       $domain = (esc_textarea(get_option( "agile_domain" )));
        $email= (esc_textarea(get_option( "agile_email" )));
        $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
        if($domain != "" && $email != "" && $rest_api != ""){
           $result = agilecrm_curl_wrap("forms/form?formId=".$atts["id"], null, "GET", "application/json",$email,$rest_api,$domain);
           $result = json_decode($result, false);
           $result = $result->formHtml;
          return $result;
        }
    }
}

function agilecrm_custom_pubnub_refresh_js() {
   $rest_api = (esc_textarea(get_option( "agile_rest_api" )));
     $page = $_GET['page'];
   if($page =='agilecrm_webrules' || $page =='agilecrm_landing' || $page =='agilecrm_formbuilder' || $page =='agilecrm_email'){
        wp_enqueue_script('pubnub_autorefresh',plugins_url('/js/pubnub-3.4.min.js', __FILE__), array(), TRUE );
      }
        wp_add_inline_script( 'pubnub_autorefresh', 'Agile_Pubnub = PUBNUB.init({ "publish_key" : "pub-c-e4c8fdc2-40b1-443d-8bb0-2a9c8facd274", "subscribe_key" : "sub-c-118f8482-92c3-11e2-9b69-12313f022c90",
      ssl : true, origin : "pubsub.pubnub.com", });
  Agile_Pubnub.subscribe({ channel : getAgileChannelName(), restore : false, message : function(message, env, channel)
{
    console.log(message);
    var action = message.action;
    var name = message.type;
    if(name== "WebRule"){
      window.location.href = "admin.php?page=agilecrm_webrules";
    }else if(name== "LandingPages"){
      window.location.href = "admin.php?page=agilecrm_landing";
    }else if(name=="Forms"){
      window.location.href = "admin.php?page=agilecrm_formbuilder";
    }else if(name=="Campaigns"){
      window.location.href = "admin.php?page=agilecrm_email";
    }
}});
 function getAgileChannelName(){  
     return  '.json_encode($rest_api).';
    }' );
} 
add_action ( 'admin_enqueue_scripts', 'agilecrm_custom_pubnub_refresh_js');


function agilecrm_footer() {
    global $post;
    $js_api = (esc_textarea(get_option( "agile_js_api" )));
    $domain= (esc_textarea(get_option( "agile_domain" )));
    $webrules = get_option( "agile_webrules" );
    $webstats = get_option( "agile_webstats" );
    $pagewebrule = get_post_meta($post->ID, 'enable_webrule',true);
    $pagewebstats=get_post_meta($post->ID, 'enable_webstats',true);
    if($webstats == $pagewebstats && $webrules == $pagewebrule){
     wp_enqueue_script( 'main-js', 'https://'.$domain.'.agilecrm.com/stats/min/agile-min.js', array('jquery') );
     wp_add_inline_script( 'main-js', '_agile.set_account("'.$js_api.'","'.$domain.'"); _agile_execute_web_rules();_agile.track_page_view();' );
      }
     elseif($webstats == $pagewebstats){ 
       wp_enqueue_script( 'main-js', 'https://'.$domain.'.agilecrm.com/stats/min/agile-min.js', array('jquery') );
       wp_add_inline_script( 'main-js', '_agile.set_account("'.$js_api.'","'.$domain.'");_agile.track_page_view();' );
      }
     elseif($webrules == $pagewebrule){ 
       wp_enqueue_script( 'main-js', 'https://'.$domain.'.agilecrm.com/stats/min/agile-min.js', array('jquery') );
       wp_add_inline_script( 'main-js', '_agile.set_account("'.$js_api.'","'.$domain.'"); _agile_execute_web_rules();' );
      }
      else{ 
      	if($domain){
        wp_enqueue_script( 'main-js', 'https://'.$domain.'.agilecrm.com/stats/min/agile-min.js', array('jquery') );
        wp_add_inline_script( 'main-js', '_agile.set_account("'.$js_api.'","'.$domain.'"); _agile_execute_web_rules();_agile.track_page_view();' );
     }
      }
 }
add_action( 'wp_footer', 'agilecrm_footer');
function agilecrm_customfeautre_js() {
  $page = $_GET['page'];
    if($page != 'agilecrm_settings'){
        wp_enqueue_script('feautre',plugins_url('/js/inputsubmit.js', __FILE__),array('jquery') );
      }
    }
   add_action( 'wp_enqueue_scripts',  'agilecrm_customfeautre_js');
function agilecrm_customsubmit_js() {
        wp_enqueue_script('formsubmit',plugins_url('/js/submitclassform.js', __FILE__),array('jquery') );
    }
   add_action( 'admin_enqueue_scripts',  'agilecrm_customsubmit_js');

function agilecrm_custom_js() {
    wp_enqueue_script('pubnub',plugins_url('/js/pubnub-3.4.min.js', __FILE__),array('jquery') );
    wp_enqueue_script('custom_script',plugins_url('/js/pubnub.js', __FILE__),array('jquery') );
}
 add_action( 'admin_enqueue_scripts',  'agilecrm_custom_js');

function agilecrm_hidedata_js() {
        wp_enqueue_script('hidedata',plugins_url('/js/hidedata.js', __FILE__),array('jquery') );
    }
add_action( 'admin_enqueue_scripts',  'agilecrm_hidedata_js');
function agilecrm_customrefresh_js() {
  $page = $_GET['page'];
  $get_domain = $_GET['domain'];
    if($page == 'agilecrm_settings' && $_POST["save"])
    {
          wp_enqueue_script('refresh',plugins_url('/js/refreshpage.js', __FILE__),array('jquery') );
    }
  }
add_action( 'admin_enqueue_scripts',  'agilecrm_customrefresh_js');

function agilecrm_get_customrefresh_js() {
  $page = $_GET['page'];
  $get_domain = $_GET['domain'];
    if($page == 'agilecrm_settings' && $get_domain)
    {
          wp_enqueue_script('refresh',plugins_url('/js/refreshpage.js', __FILE__),array('jquery') );
    }
 }
add_action( 'admin_enqueue_scripts',  'agilecrm_get_customrefresh_js');
?>