<?php
/**
 * Page to preview form
 */

$sib_form_id = isset($_GET['sib_form']) ? $_GET['sib_form'] : '';
$sib_preview = isset($_GET['action']) ? $_GET['action'] : '';

wp_head();

?>
<body style="background-color: #f5f5f5;">
<div id="page" class="site" style="padding:16px;">
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
    <div id="sib-preview-form">
    <?php
    if($sib_preview == '') {
        $formData = SIB_Forms::getForm($sib_form_id);
    }else{
        $formData = get_option(SIB_Manager::preview_option_name, array());
    }
    $html = stripslashes_deep($formData['html']);
    $css = stripslashes_deep($formData['css']);
    echo $html;
    ?>
    </div>
    <style>
<?php
    if($formData['dependTheme'] != '1'){
        $css = str_replace('[form]', '#sib-preview-form', $css);
        echo $css;
    }
    ?>
    </style>
</div>
</body>
