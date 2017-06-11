<style>
	body {font-family:Verdana,Arial,sans-serif; font-size:13px}
	fieldset {border:1px solid silver; border-radius:5px; padding:10px}
	h3 {margin:1px; padding:1px; font-size:13px;}
	a {color:#222}
	a:hover{color:gray}
	input[type=text], input[type=password], select {width:97%; border-radius:2px; border:1px solid silver; padding:4px; font-family:Verdana,Arial,sans-serif;}
	select {padding-left:0; width:99%}
	select:disabled {background:#EBEBE4}
	input.readonly {background-color:#efefef;}

	/* ============================
	COMMON VIEWS
     ============================ */
	div#content {border:1px solid #CDCDCD; width:750px; min-height:550px; margin:auto; margin-top:18px; border-radius:5px; box-shadow:0 8px 6px -6px #333; font-size:13px}
	div#content-inner {padding:10px 30px; min-height:550px}
	form.content-form {min-height:550px; position:relative; line-height:17px}
	div.logfile-link {float:right; font-weight:normal; font-size:12px}

	/* Header */
	table.header-wizard {border-top-left-radius:5px; border-top-right-radius:5px; width:100%; box-shadow:0 5px 3px -3px #999; background-color:#F1F1F1; font-weight:bold}
	div.dupx-logfile-link {float:right; font-weight:normal; font-size:12px}
	.wiz-dupx-version {white-space:nowrap; color:#777; font-size:11px; font-style:italic; text-align:right;  padding:0 15px 5px 0; line-height:14px; font-weight:normal}
	.wiz-dupx-version a { color:#999; }

	.dupx-pass {display:inline-block; color:green;}
	.dupx-fail {display:inline-block; color:#AF0000;}
	.dupx-notice {display:inline-block; color:#000;}
	div.status-badge-pass {float:right; border-radius:4px; color:#fff; padding:0 4px 0 4px;  font-size:12px; min-width:30px; text-align:center;background-color:#418446; }
	div.status-badge-fail {float:right; border-radius:4px; color:#fff; padding:0 4px 0 4px;  font-size:12px; min-width:30px; text-align:center;background-color:maroon; }

    button.default-btn, input.default-btn {
		cursor:pointer; color:#fff; font-size:16px; border-radius:5px;	padding:7px 25px 5px 25px;
	    background-color:#13659C; border:1px solid gray;
	}
	button.disabled, input.disabled {
		background-color:#F4F4F4; color:silver; border:1px solid silver;
	}

	div.log-ui-error {padding-top:2px; font-size:13px}
	div#progress-area {padding:5px; margin:150px 0 0 0; text-align:center;}
	div#ajaxerr-data {padding:6px; height:425px; width:99%; border:1px solid silver; border-radius:5px; background-color:#F1F1F1; font-size:13px; overflow-y:scroll; line-height:20px}
	div.hdr-main {font-size:22px; padding:0 0 5px 0; border-bottom:1px solid #D3D3D3; font-weight:bold; margin:15px 0 20px 0;}
	div.hdr-main span.step {color:#DB4B38}

	div.hdr-sub1 {font-size:18px; margin-bottom:5px; border-bottom:1px solid #D3D3D3;padding:2px 2px 5px 0;font-weight:bold;}
	div.hdr-sub1 a {cursor:pointer; text-decoration: none !important}
	div.hdr-sub1 i.fa {font-size:15px; display:inline-block; margin-right:5px; vertical-align:top}
	div.hdr-sub2 {font-size:15px; padding:2px 2px 2px 0; font-weight:bold; margin-bottom:5px; border:none}
	div.hdr-sub3 {font-size:15px; padding:2px 2px 2px 0; border-bottom:1px solid #D3D3D3; font-weight:bold; margin-bottom:5px;}

    /*Adv Opts */
    table.dupx-opts {width:100%; border:0px;}
	table.dupx-opts td{white-space:nowrap; padding:5px;}
	table.dupx-opts td:first-child{width:125px; font-weight: bold}
	table.dupx-advopts td:first-child{width:125px;}
	table.dupx-advopts label.radio {width:50px; display:inline-block}
	table.dupx-advopts label {cursor:pointer}

	div.error-pane {border:1px solid #efefef; border-left:4px solid #D54E21; padding:0 0 0 10px; margin:2px 0 10px 0}
	div.dupx-ui-error {padding-top:2px; font-size:13px; line-height: 20px}

	div.footer-buttons {position:absolute; bottom:10px; padding:10px;  right:0}
	div.footer-buttons  input:hover, button:hover {border:1px solid #000}
	div.footer-buttons input[disabled=disabled], button[disabled=disabled]{background-color:#F4F4F4; color:silver; border:1px solid silver;}
	form#form-debug {display:block; margin:10px auto; width:750px;}
	form#form-debug a {display:inline-block;}
	form#form-debug pre {margin-top:-2px; display:none}

    /*Dialog Info */
	div.dlg-serv-info {line-height:22px; font-size:12px}
	div.dlg-serv-info label {display:inline-block; width:200px; font-weight: bold}
    div.dlg-serv-info div.hdr {background-color: #dfdfdf; font-weight: bold; margin-top:5px; border-radius: 4px; padding:2px 5px 2px 5px; border: 1px solid silver; font-size:13px}

	/* ============================
	INIT 1:SECURE PASSWORD
	============================ */
    button.pass-toggle {height:23px; width:23px; position:absolute; top:0px; right:0px; border:1px solid silver;  border-radius:0 4px 4px 0;}
	button.pass-toggle  i { padding:0; display:block; margin:-4px 0 0 -5px}
	div.i1-pass-area {width:100%; text-align:center}
	div.i1-pass-data {padding:30px; margin:auto; text-align:center; width:300px}
	div.i1-pass-data table {width:100%; border-collapse:collapse; padding:0}
	div.i1-pass-data label {font-weight:bold}
	div.i1-pass-errmsg {color:maroon; font-weight:bold}
	div#i1-pass-input {position:relative; margin:2px 0 15px 0}
	input#secure-pass {border-radius:4px 0 0 4px; width:250px}

	/* ============================
	STEP 1 VIEW
	 ============================ */
	div#s1-area-archive-file .ui-widget.ui-widget-content {border: 0px solid #d3d3d3}
	table.s1-archive-local td {padding:5px}
	table.s1-archive-local td:first-child {font-weight:bold; min-width:50px}
	div.s1-err-msg {padding:0 0 80px 0; line-height:20px}
	div.s1-err-msg i {color:maroon}

	div#s1-area-sys-setup {padding:15px 0 0 10px}
	table.s1-checks-area {width:100%; margin:0; padding:0}
	table.s1-checks-area td.title {font-size:16px; width:100%}
	table.s1-checks-area td.toggle {font-size:11px; margin-right:7px; font-weight:normal}

	div.s1-reqs {background-color:#efefef; border:1px solid silver; border-radius:5px; margin-top:-5px}
	div.s1-reqs div.notice {background-color:#E0E0E0; color:#000; text-align:center; font-size:12px; border-bottom: 1px solid silver; padding:2px; font-style:italic}
	div.s1-reqs div.status {float:right; border-radius:4px; color:#fff; padding:0 4px 0 4px; margin:4px 5px 0 0; font-size:12px; min-width:30px; text-align:center; font-weight:bold}
	div.s1-reqs div.pass {background-color:green;}
	div.s1-reqs div.fail {background-color:maroon;}
	div.s1-reqs div.title {padding:4px; font-size:13px;}
	div.s1-reqs div.title:hover {background-color:#dfdfdf; cursor:pointer}
	div.s1-reqs div.info {padding:8px 8px 20px 8px; background-color:#fff; display:none; line-height:18px; font-size: 12px}
	div.s1-reqs div.info a {color:#485AA3;}
	select#archive_engine {width:80%; cursor:pointer}

	/*Terms and Notices*/
	div#s1-warning-check label{cursor:pointer;}
    div#s1-warning-msg {padding:5px;font-size:12px; color:#333; line-height:14px;font-style:italic; overflow-y:scroll; height:150px; border:1px solid #dfdfdf; background:#fff; border-radius:3px}
	div#s1-warning-check {padding:3px; font-size:14px; font-weight:normal;}
    input#accept-warnings {height: 17px; width:17px}

	/* ============================
	STEP 2 VIEW
	============================ */

	/*Toggle Buttons */
	div.s2-btngrp {text-align:center; margin:2px 0 0 0}
	div.s2-btngrp input[type=button] {font-size:13px; padding:5px; width:120px; border:1px solid silver;  cursor:pointer}
	div.s2-btngrp input[type=button]:first-child {border-radius:5px 0 0 5px; margin-right:-2px}
	div.s2-btngrp input[type=button]:last-child {border-radius:0 5px 5px 0; margin-left:-2px}
	div.s2-btngrp input[type=button].active {background:#999999; color:#fff; font-weight:bold;  box-shadow:inset 0 0 10px #444;}
	div.s2-btngrp input[type=button].in-active {background:#E4E4E4; }
	div.s2-btngrp input[type=button]:hover {border:1px solid #999}

	/*Basic DB */
	select#dbname-select {width:100%; border-radius:3px; height:20px; font-size:12px; border:1px solid silver;}

	/*cPanel DB */
	td#cpnl-prefix-dbname {width:10px}
	td#cpnl-prefix-dbuser {width:10px; white-space:normal}
	div#s2-cpnl-area div#cpnl-host-warn {white-space:normal; font-size:11px; display:none; font-style: italic}
	a#s2-cpnl-status-msg {font-size:11px}
	span#s2-cpnl-status-icon {display:none}
	div#s2-cpnl-connect {margin:auto; text-align:center; margin:15px 0 20px 0}
	div#s2-cpnl-status-details {border:1px solid silver; border-radius:3px; background-color:#f9f9f9; padding:10px 10px 2px 10px; margin-top:10px; height:55px; overflow-y:scroll;}
	div#cpnl-dbname-prefix {display:none; float:left; margin-top:3px;}
	span#s2-cpnl-db-opts-lbl {font-size:11px; font-weight:normal; font-style:italic}
	div#s2-cpnl-dbname-area2 table {border-collapse: collapse; width: 100%}
	div#s2-cpnl-dbname-area2 table td {padding:0 !important; margin:0; border:0}
	div#s2-cpnl-dbname-area2 table td:first-child {vertical-align:bottom;}
	div#s2-cpnl-dbname-area2 table td:nth-child(2) {width:100%; padding-right:0 !important}
	div#s2-cpnl-dbuser-area2 table {border-collapse: collapse; width: 100%}
	div#s2-cpnl-dbuser-area2 table td {padding:0 !important; margin:0; border:0}
	div#s2-cpnl-dbuser-area2 table td:first-child {vertical-align:bottom;}
	div#s2-cpnl-dbuser-area2 table td:nth-child(2) {width:100%; padding-right:0 !important}

	/*Test DB connection */
	div.s2-dbconn-area {margin:auto; text-align:center; margin:10px 0 15px 0}
	div.s2-dbconn-area input[type=button] {font-size:11px; height:20px; border:1px solid gray; border-radius:3px; cursor:pointer}
	div.s2-dbonn-result-newuser {width:85%; margin:auto; text-align:center; line-height:17px}
	div.s2-dbconn-result  {border:1px solid silver; border-radius:3px; background:#f9f9f9; padding:3px; margin-top:10px; height:225px; overflow-y:scroll; display:none; max-width:680px}
	div.s2-dbconn-result-data small{display:block; font-style:italic; color:#333; padding:3px 2px 5px 2px; border-bottom:1px dashed silver; margin-bottom:10px; text-align:center; font-size:10px}
	div.s2-dbconn-result-data table.details {text-align: left; margin: auto}
	div.s2-dbconn-result-data table.details td:first-child {font-weight: bold; width: 65px; vertical-align: top}
	div.s2-dbconn-result-data div.warning {padding:5px 0 2px 0}
	div.s2-dbconn-result-data {white-space: normal}
	div.s2-dbconn-result-faq {font-style: italic; font-size:12px; border-top:1px dashed silver; padding:8px; margin-top:10px}
	div.s2-dbconn-result-data div.warn-msg {text-align: left; padding:5px; margin:10px 0 10px 0}
	div.s2-dbconn-result-data div.warn-msg b{color:maroon}
	div#s2-adv-opts label {cursor:pointer}

	/*Warning Area and Message */
	div.s2-warning-emptydb {color:#AF2222; margin:2px 0 0 0; font-size:11px; display: none; white-space:normal; width: 550px}
	div.s2-warning-manualdb {color:#1B67FF; margin:2px 0 0 0; font-size:11px; display:none; white-space:normal; width: 550px}
	div.s2-warning-renamedb {color:#1B67FF; margin:2px 0 0 0; font-size:11px; display:none; white-space:normal; width: 550px}
	div#s2-tryagain {padding-top:50px; text-align:center; width:100%; font-size:16px; color:#444; font-weight:bold;}

	/* ============================
	STEP 3 VIEW
	============================ */
	table.s3-opts{width:100%; border:0;}
	table.s3-opts input[type=text] {width:95% !important}
	table.s3-opts td{white-space:nowrap; padding:3px;}
	table.s3-opts td:first-child{width:90px; font-weight: bold}
	div#s3-adv-opts {margin-top:5px; }
	div.s3-allnonelinks {font-size:11px; float:right;}
	div.s3-manaual-msg {font-style: italic; margin:-2px 0 5px 0}
	div#s3-custom-replace i.fa {font-size:13px}

	/* ============================
	STEP 4 VIEW
	============================ */
	div.s4-final-msg {height:110px; border:1px solid #CDCDCD; padding:8px;font-size:12px; border-radius:5px;box-shadow:0 4px 2px -2px #777;}
	div.s4-final-title {color:#BE2323; font-size:18px}
	div.s4-connect {font-size:12px; text-align:center; font-style:italic; position:absolute; bottom:10px; padding:10px; width:100%; margin-top:20px}
	table.s4-report-results,
	table.s4-report-errs {border-collapse:collapse; border:1px solid #dfdfdf; }
	table.s4-report-errs  td {text-align:center; width:33%}
	table.s4-report-results th, table.s4-report-errs th {background-color:#efefef; padding:0; font-size:12px; padding:0}
	table.s4-report-results td, table.s4-report-errs td {padding:0; white-space:nowrap; border:1px solid #dfdfdf; text-align:center; font-size:11px}
	table.s4-report-results td:first-child {text-align:left; font-weight:bold; padding-left:3px}
	div.s4-err-title {width:100%; background-color: #dfdfdf; font-weight: bold; margin:-5px 0 15px 0; padding:3px 0 1px 3px; border-radius: 4px; font-size:13px}

	div.s4-err-msg {padding:8px;  display:none; border:1px dashed #999; margin:10px 0 20px 0; border-radius:5px;}
	div.s4-err-msg div.content{padding:5px; font-size:11px; line-height:17px; max-height:125px; overflow-y:scroll; border:1px solid silver; margin:3px;  }
	div.s4-err-msg div.info-error{padding:7px; background-color:#EAA9AA; border:1px solid silver; border-radius:5px; font-size:12px; line-height:16px }
	div.s4-err-msg div.info-notice{padding:7px; background-color:#FCFEC5; border:1px solid silver; border-radius:5px; font-size:12px; line-height:16px;}
	table.s4-final-step {width:100%;}
	table.s4-final-step td {padding:5px 15px 5px 5px;font-size:13px; }
	table.s4-final-step td:first-child {white-space:nowrap; width:165px}
	div.s4-go-back {border-bottom:1px dotted #dfdfdf; border-top:1px dotted #dfdfdf; margin:auto; text-align:center}
	div.s4-btns-msg {text-align: center; font-size:10px; color:#777; margin:5px 0 15px 0}
	a.s4-final-btns {display: block; width:135; padding:5px; line-height: 1.4; background-color:#F1F1F1; border:1px solid silver;
		color: #000; box-shadow: 5px 5px 5px -5px #949494; text-decoration: none; text-align: center; border-radius: 4px;
	}
	a.s4-final-btns:hover {background-color: #dfdfdf;}

	/* ============================
	STEP 5 HELP
	============================	*/
	div.help-target {float:right;}
	div.help-target a {float:right; font-size:16px; color:#13659C}
	div#main-help sup {font-size:11px; font-weight:normal; font-style:italic; color:blue}
	div.help-online {text-align:center; font-size:18px; padding:10px 0 0 0; line-height:24px}
	div.help {color:#555; font-style:italic; font-size:11px; padding:4px; border-top:1px solid #dfdfdf}
	div.help-page {padding:5px 0 0 5px}
	div.help-page fieldset {margin-bottom:25px}
    div#main-help {font-size:13px; line-height:17px}
	div#main-help h2 {background-color:#F1F1F1; border:1px solid silver; border-radius:4px; padding:10px; margin:26px 0 8px 0; font-size:22px; }
	div#main-help h3 {border-bottom:1px solid silver; padding:8px; margin:4px 0 8px 0; font-size:20px}
    div#main-help span.step {color:#DB4B38}
	table.help-opt {width: 100%; border: none; border-collapse: collapse;  margin:5px 0 0 0;}
	table.help-opt td.section {background-color:#dfdfdf;}
	table.help-opt td, th {padding:7px; border:1px solid silver;}
	table.help-opt td:first-child {font-weight:bold; padding-right:10px; white-space:nowrap}
	table.help-opt th {background: #333; color: #fff;border:1px solid #333; padding:3px}


	/*!
	 * password indicator
	 */
	.top_testresult{font-weight:bold;	font-size:11px; color:#222;	padding:1px 1px 1px 4px; margin:4px 0 0 0; width:495px; dislay:inline-block}
	.top_testresult span{margin:0;}
	.top_shortPass{background:#edabab; border:1px solid #bc0000;display:block;}
	.top_badPass{background:#edabab;border:1px solid #bc0000;display:block;}
	.top_goodPass{background:#ffffe0; border:1px solid #e6db55;	display:block;}
	.top_strongPass{background:#d3edab;	border:1px solid #73bc00; display:block;}

	/*================================================
	LIB OVERIDES*/
	input.parsley-error, textarea.parsley-error, select.parsley-error {
	  color:#B94A48 !important;
	  background-color:#F2DEDE !important;
	  border:1px solid #EED3D7 !important;
	}
	ul.parsley-errors-list {margin:1px 0 0 -40px; list-style-type:none; font-size:10px}
    .ui-widget {font-size:13px}


	<?php if ($GLOBALS['DUPX_DEBUG']) : ?>
		.dupx-debug {display:block; margin:0 0 25px 0; font-size:11px; background-color:#f5dbda; padding:8px; border:1px solid silver; border-radius:4px}
		.dupx-debug label {font-weight:bold; display:block; margin:4px 0 1px 0}
		.dupx-debug textarea {width:95%; height:100px; font-size:11px}
		.dupx-debug input {font-size:11px; padding:3px}
	<?php else : ?>
		.dupx-debug {display:none}
	<?php endif; ?>

</style>