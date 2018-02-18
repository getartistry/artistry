<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
@media only screen and (min-width: 981px) {

	/* Sidebar expand / collapse button */
	#db_hide_sidebar {
		z-index: 10000;
		padding: 10px;
		cursor: pointer;
	}
	.db_right_sidebar_collapsible #db_hide_sidebar {
		right: 0;
	}
	.db_left_sidebar_collapsible #db_hide_sidebar {
		left: 0;
	}
	.et_fixed_nav #db_hide_sidebar {	
		position: fixed; 
	}
	.et_non_fixed_nav #db_hide_sidebar {	
		position: absolute; 
	}
	#db_hide_sidebar:before {
		font-family: 'ETModules';
		font-size: 24px;
	}
	.et_right_sidebar #db_hide_sidebar:before,
	.db_left_sidebar_collapsible.et_full_width_page #db_hide_sidebar:before {
		content: '\39';
	}
	.db_right_sidebar_collapsible.et_full_width_page #db_hide_sidebar:before,
	.et_left_sidebar #db_hide_sidebar:before {
		content: '\38';
	}

	/* Ensure fullwidth formatting matches sidebar formatting */
	.db_sidebar_collapsible.et_full_width_page .et_post_meta_wrapper:first-child {
		padding-top: 58px !important;
	}
}