<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
jQuery(function($){
	$('.et_pb_accordion .et_pb_toggle_open').addClass('et_pb_toggle_close').removeClass('et_pb_toggle_open');

    $('.et_pb_accordion .et_pb_toggle').click(function() {
      $this = $(this);
      setTimeout(function(){
         $this.closest('.et_pb_accordion').removeClass('et_pb_accordion_toggling');
      },700);
    });
});