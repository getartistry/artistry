<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

add_action('admin_head', 'wtfdivi094_admin_css');

function wtfdivi094_admin_css() { ?>
<style>
.et-pb-preview-tab { width: 100% !important; }
.et_pb_modal_settings_container, .et_pb_prompt_modal {
	top:32px !important;
	bottom:0 !important;
	width:100%! important;
	left:0 !important;
	margin:0 !important; 
	border-radius: 0 !important; 
}
.et_pb_prompt_modal { 
	top: 100px !important; 
	bottom: 0 !important; 
}
div.et_pb_prompt_modal div.et_pb_prompt_buttons input.et_pb_prompt_proceed { 
	border-radius: 0px !important; 
}
.et-pb-options-tabs-links{
	width:100% !important;
}
div.et_pb_modal_overlay.et_pb_builder_settings .et_pb_prompt_modal,
div[data-action].et_pb_modal_overlay .et_pb_prompt_modal { 
	top: 32px !important; 
}
.et_pb_modal_settings_container div.mce-fullscreen {
    top: 32px !important;
    left: 0 !important; 
    width: 100% !important;
    margin: 0 !important;
    bottom: 0 !important; 
}

/* Make the code module content box wider */
[data-module_type="et_pb_code"] [data-option_name="raw_content"] .et-pb-option-container { 
    width: calc(100% - 480px) !important; 
}
[data-module_type="et_pb_code"] [data-option_name="raw_content"] .et-pb-option-container textarea { 
    width: 100% !important; 
}
</style>

<?php 
}
?>