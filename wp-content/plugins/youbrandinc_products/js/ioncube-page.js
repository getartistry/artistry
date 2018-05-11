jQuery(document).ready(function($){

var myOptions = {
      heightStyle: "content",
	  collapsible: true
};
$( "#ybi-accordian" ).accordion(myOptions);

$(".reload_server_info").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	$( '.check_iframe' ).attr( 'src', function ( i, val ) { return val; });
})



$(".reload_godaddy_php_check").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
	$( '#go_daddy_php_iframe' ).attr( 'src', theName);
})

$(".reload_final_godady_check").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
	$( '#reload_final_godady_check_iframe' ).attr( 'src', theName);
})

$(".reload_other_php_check").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
	$( '#reload_other_php_check_iframe' ).attr( 'src', theName);
})

$(".advanced_check").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
	$( '#advanced_check_iframe' ).attr( 'src', theName);
})


//reload_final_godady_check

$(".hostgator_setup").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
   
   if(confirm("This will make changes to files on your server. Backups are created during this process but to be safe you should create your own backed up files via FTP. Do you want to continue?")){


		var elem = jQuery(this);
		var theName = elem.attr('name')
		$( '.hostgator_setup_iframe' ).attr( 'src', theName);
		$(this).css("visibility","hidden");
		$(this).css("display","none");

    } else {
        event.preventDefault();
        return false;
    }

})

$(".load_ioncube").click(function() {
	// if link is checked then we reload the iframe
   // $('#server_info_iframe').attr("src", $('#server_info_iframe').attr("src"));
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
	$( '.copy_ioncube_iframe' ).attr( 'src', theName);
})

   $('.step_check').change(function() {
	   var elem = jQuery(this);
	   var theName = elem.attr('name')
        if($(this).is(":checked")) {
			$("."+theName).css({"text-decoration":"line-through","color":"#0C0"});
			$("."+theName+"_directions").css({"display":"none"});
			
			if($("."+theName+"_directions").hasClass('last'))
				$("#"+theName +"_last_action").css({"display":"block","visibility":"visible"});

        }
		else
		{
			$("."+theName).css({"text-decoration":"none","color":"#362B36"});
			$("."+theName+"_directions").css({"display":"block"});
			if($("."+theName+"_directions").hasClass('last'))
			$("#"+theName +"_last_action").css({"display":"none","visibility":"hidden"});

		}

    });


});