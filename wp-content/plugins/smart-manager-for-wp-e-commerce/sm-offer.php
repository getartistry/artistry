<?php 
 if( get_option('sm_dismiss_offer_christmas_admin_notice') == 1 ) return;
 $current_url = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
?>
<style type="text/css">
.sm_offer{
    width: 96%;
    height: auto;
    margin: 1em auto;
    text-align: center;
    font-size: 1.2em;
    /*font-family: sans-serif;*/
    letter-spacing: 3px;
    line-height: 1.2em;
    padding: 2em;
    background-image: url('<?php echo  SM_IMG_URL; ?>/christmas.png'), linear-gradient(to right, #040303, #3d72b4 ) !important;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: left;
}
.sm_offer_heading{
    color: #64badd;
    color: #64ddc1;
    padding: 1em 0;
    line-height: 1.2em;
}
.sm_main_heading {
    font-size: 3em;
    color: #FFFFFF;
    font-weight: 600;
    margin-bottom: 0.6em;
    line-height: 1.2em;
    position: relative;
}

.sm_text{
    font-size: 0.9em;
}
.sm_left_text{
    padding: 1em 5.4em 0.4em;
    color: #B5B5B5;
}
.sm_right_text{
    color: #FFFFFF;
    font-weight: 600;
    max-width: 50%;
    padding: 10px 56px;
    width: auto;
    margin: 0;
    display: inline-block;
    text-decoration: none;
    background: #b70f0f;
}
.sm_right_text:hover, .sm_right_text:active{
    color: inherit; 
}
.sm_offer_content{
    margin-left: 30%;
    width: fit-content;
}
</style>
<div class="sm_offer">
    <div style="float:right;"><img width=100 src="<?php echo SM_IMG_URL; ?>/StoreApps-Logo.png"/></div>
        <div  class="sm_offer_content">
            <div class="sm_offer_heading">It's time to be merry! </div>
            <div class="sm_main_heading">Grab FLAT 20% OFF Storewide </div>
            <div class="sm_text">
                <a href="?sm_dismiss_offer_christmas_admin_notice=1&sm_redirect=1" target="_blank" class="sm_right_text">Start Shopping</a>
                <div class="sm_left_text">Offer ends on 26th December, 2017 - so hurry.. </div>
            </div>
        </div>
    <div style="float:right;letter-spacing:0px;"><a style="color:#999!important;text-decoration:none!important;font-size:0.9em!important;" href="<?php echo $current_url; ?>&sm_dismiss_offer_christmas_admin_notice=1">No, I dont like offers...</a></div>
</div>