<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
.et_pb_newsletter {padding: 20px 10px !important; border-radius:10px; }
.et_pb_newsletter .et_pb_newsletter_description {padding: 0 0 20px 0; width: 100%;}
.et_pb_newsletter .et_pb_newsletter_form {width: 100%;}
.et_pb_newsletter .et_pb_newsletter_form p {display: inline-block; margin-right: 40px;}
.et_pb_newsletter .et_pb_newsletter_form p input { padding: 8px 4% !important; width: 300px;}
.et_pb_newsletter .et_pb_newsletter_button {padding: 2px 6px;}
.et_pb_newsletter a.et_pb_newsletter_button:hover {padding: 2px 6px!important;}
.et_pb_newsletter a.et_pb_newsletter_button:after {display:none;}
.et_pb_newsletter #et_pb_signup_lastname {display: none !important;}
 
@media only screen and (max-width: 1100px) {
.et_pb_newsletter .et_pb_newsletter_form p input { padding: 8px 4% !important; width: 275px;}
}
 
@media only screen and (max-width: 980px) {
.et_pb_newsletter .et_pb_newsletter_form p input { padding: 8px 4% !important; width: 220px;}
.et_pb_newsletter .et_pb_newsletter_description { width: 100% !important;}
}
 
@media only screen and (max-width: 767px) {
.et_pb_newsletter .et_pb_newsletter_form p input { padding: 8px 4% !important; width: 240px;}
}
 
@media only screen and (max-width: 479px) {
.et_pb_newsletter .et_pb_newsletter_form p input { padding: 8px 4% !important; width: 180px;}
}

/* handle input box overrun on narrow columns */
.et_pb_column_1_3 .et_pb_newletter_form input { width:100% !important; }