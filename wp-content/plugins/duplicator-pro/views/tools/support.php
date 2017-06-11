<?php
DUP_PRO_U::hasCapability('read');

$thanks_display = 'none';
$error_display = 'none';
$form_display = 'block';
$message = '';

?>
<style>
    div.dup-support-all {font-size:13px; line-height:20px;}
    div.dup-support-txts-links {width:100%;font-size:14px; font-weight:bold; line-height:26px; text-align:center}
    div.dup-support-hlp-area {width:375px; height:160px; float:left; border:1px solid #dfdfdf; border-radius:4px; margin:10px; line-height:18px;box-shadow: 0 8px 6px -6px #ccc;}
    table.dup-support-hlp-hdrs {border-collapse:collapse; width:100%; border-bottom:1px solid #dfdfdf}
    table.dup-support-hlp-hdrs {background-color:#efefef;}
    div.dup-support-hlp-hdrs {
        font-weight:bold; font-size:17px; height: 35px; padding:5px 5px 5px 10px;
        background-image:-ms-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-moz-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-o-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(1, #DEDEDE));
        background-image:-webkit-linear-gradient(top, #FFFFFF 0%, #DEDEDE 100%);
        background-image:linear-gradient(to bottom, #FFFFFF 0%, #DEDEDE 100%);
    }
    div.dup-support-hlp-hdrs div {padding:5px; margin:4px 20px 0px -20px;  text-align: center;}
    div.dup-support-hlp-txt{padding:10px 4px 4px 4px; text-align:center}
</style>


<div class="dup-support-all">
    <div style="display:<?php echo $form_display; ?>;">

        <!--table>
            <tr>
                <td style="width:70px"><i class="fa fa-question-circle fa-5x"></i></td>
                <td valign="top" style="padding-top:10px; font-size:13px">
        <?php
        DUP_PRO_U::_e("Migrating WordPress is a complex process and the logic to make all the magic happen smoothly may not work quickly with every site.  With over 30,000 plugins and a very complex server eco-system some migrations may run into issues.  This is why the Duplicator includes a detailed knowledgebase that can help with many common issues.  Resources to additional support, approved hosting, and alternatives to fit your needs can be found below.");
        ?>
                </td>

            </tr>
        </table-->
        <!-- HELP LINKS 
        <div style="display:none" class="dup-support-hlp-area" >
            <div class="dup-support-hlp-hdrs">
                <i class="fa fa-cube fa-2x pull-left"></i>
                <div><?php DUP_PRO_U::_e('Knowledgebase') ?></div>
            </div>
            <div class="dup-support-hlp-txt">
        <?php DUP_PRO_U::_e('Complete Online Documentation'); ?><br/>
                <select id="dup-support-kb-lnks" style="margin-top:18px; font-size:16px; min-width: 170px">
                    <option> <?php DUP_PRO_U::_e('Choose A Section') ?> </option>
                    <option value="https://snapcreek.com/duplicator-quick"><?php DUP_PRO_U::_e('Quick Start') ?></option>
                    <option value="https://snapcreek.com/duplicator-guide"><?php DUP_PRO_U::_e('User Guide') ?></option>
                    <option value="https://snapcreek.com/duplicator-faq"><?php DUP_PRO_U::_e('FAQs') ?></option>
                    <option value="https://snapcreek.com/duplicator-log"><?php DUP_PRO_U::_e('Change Log') ?></option>
                    <option value="https://snapcreek.com/labs/duplicator"><?php DUP_PRO_U::_e('Product Page') ?></option>
                </select>
            </div>
        </div>-->

        <!-- ONLINE SUPPORT -->

        <div style="margin: auto; height: 350px;  text-align: center">

            <!-- HELP TICKET-->
            <div class="dup-support-hlp-area">
                <div class="dup-support-hlp-hdrs">
                    <i class="fa fa-lightbulb-o fa-2x pull-left"></i>
                    <div><?php DUP_PRO_U::_e('Submit Help Ticket') ?></div>
                </div>
                <div class="dup-support-hlp-txt">
                    <?php DUP_PRO_U::_e("Submit support ticket to Duplicator Pro support."); ?> <br/>
                    <i>
                        <?php DUP_PRO_U::_e("Please have your"); ?>
                        <a href="admin.php?page=duplicator-pro-settings&tab=licensing"><?php DUP_PRO_U::_e("license key"); ?></a>
                        <?php DUP_PRO_U::_e("ready to enter ticket."); ?>
                    </i>
                    <br/><br/>
                    <div class="dup-support-txts-links">
                        <button class="button  button-primary button-large" onclick="DupPro.Support.OpenSupportWindow();
                                return false;"><?php DUP_PRO_U::_e('Get Support!') ?></button> &nbsp; 
                    </div>	
                </div>
            </div>   


        </div>


    </div>
    <div style="margin-top:112px; text-align:center; display:<?php echo $thanks_display; ?>">
        <p style="margin-bottom:0px; font-size:32px"><?php DUP_PRO_U::_e('Thanks, we\'ll get back to you shortly.'); ?></p>
        <p style="font-size:12px"><?php DUP_PRO_U::_e('*Contact support@snapcreek.com if you don\'t get a confirmation email within an hour.'); ?></p>
    </div>
    <div style="margin-top:112px; text-align:center; display:<?php echo $error_display; ?>">
        <p style="margin-bottom:0px; font-size:32px"><?php DUP_PRO_U::_e('There was a problem sending the email.'); ?></p>
        <p><?php DUP_PRO_U::_e("We had a problem sending the support email. Instead, send your problem or question to") ?> <a href='mailto:support@snapcreek.com' target='_blank'>support@snapcreek.com.</a></p>

        <p style='font-weight:bold'><?php echo $message; ?></p>
    </div>
</div><br/><br/><br/><br/>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        DupPro.Support.OpenSupportWindow = function () {
            var url = 'https://snapcreek.com/ticket';
            window.open(url, 'Duplicator Pro Support');
        }

        //ATTACHED EVENTS
        jQuery('#dup-support-kb-lnks').change(function () {
            if (jQuery(this).val() != "null")
                window.open(jQuery(this).val())
        });

    });
</script>