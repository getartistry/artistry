<?php

if ( sizeof( $existing_applist ) > 0 ) {
	echo "
				<div class=\"mo_registration_divided_layout\">
					<div class=\"mo_gsuite_registration_table_layout\">";

	is_gsuite_customer_registered();
	echo
					"
							<br><a href='#'><button disabled style='float:right'>Add Application</button></a>";
} else {
	echo "<br><a href='admin.php?page=mo_oauth_settings&action=add'>
		
		                       <button style='float:right'>Add Application</button></a>";
}

echo "<h3>Applications List</h3>";

if ( is_array( $existing_applist ) && sizeof( $existing_applist ) > 0 ) {
	echo "<p style='background-color:whitesmoke;border-color:#ebccd1;border-radius:5px;padding:12px'>You can only add 1 application with free version. Upgrade to <a href='admin.php?page=gsuitepricing'><b>premium</b></a> to add more.</p>";
}
echo "<table class='mo-oauth-applist-table'>";
echo "<tr><th>Name</th><th style='text-align: left;padding-left: 15px'>Action</th></tr>";
echo "<tr>";
display_app_list( $existing_applist,$disabled);
echo "</tr>";
echo "</table>";
echo "<br><br>
		<div id='instructions'>
					</div>
		</div></div>";

echo '	<style>
		
		.mo-oauth-applist-table{
			    color: black;
    			background-color: whitesmoke;
    			border-color: darkgrey;
    			border-radius: 5px;
    			padding: 12px;
    			width:100% ;
		}
		.mo-oauth-applist-table tr > td:first-child {
       		width: 20%;
        	padding: 15px;
        	text-align: center;
    	}
    	
    	.mo-oauth-applist-table th{
			
    	}

    	.mo-oauth-applist-table tr > td:last-child {
        	width: 75%;
        	padding: 15px;
        	text-align:left;
    	}
		</style>';

echo '<script>	
			function app_instructions(appname){
			    switch(appname){
			        case "google":
			            document.getElementById("instructions").innerHTML  ="'.$google_instructions. '";
			            break;
			            
		            case "eveonline":
			            document.getElementById("instructions").innerHTML  ="' .$eveonline_instructions. '";
			            break;
			            
		             case "facebook":
			            document.getElementById("instructions").innerHTML  ="' . $facebook_instructions . '";
			            break;
			            
		             default:
			            document.getElementById("instructions").innerHTML  ="' .$google_instructions. '";
			            break;      
			    }
			}
			
			function ajax_action(appname,action){
			        jQuery.ajax({
								url:"' . site_url() . '/?option=miniorange-oauth-app-action",
								type:"POST",
								data:{
								    oauth_appname:appname,
								    action_name:action
								},
								crossDomain:!0,
								dataType:"json",
								
								success:function(data){	
								
								console.log(JSON.stringify(data.action));
								
								    switch(data.action){
								        case "edit":
								           var redirect_url="' . site_url() . '"+data.url+"&action=edit";
								            window.location.href = redirect_url; 
								            break;
											
										case "attribute_mapping":
										    break;
										    
									    case "role_mapping":
									    	
									    	break;
								        case "delete":
								            var redirect_url="' . site_url() . '"+data.url;
								            window.location.href = redirect_url;
								            break;
								    }
								},
								error: function() {
								    //alert("error");
								}
							});
			}
							
		</script>';
/*
 * var mosseting_url = "'.site_url().'"+"/wp-admin/admin.php?page=mogalsettings";
								    window.location.href = "#";*/

/*redirect_url="' . site_url() . '"+ "/"+data.url;

								            window.location.href = redirect_url;*/