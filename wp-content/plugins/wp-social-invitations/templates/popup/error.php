<?php
/**
 * Error template
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/templates/popup
 */

if ( ! defined( 'ABSPATH' ) ) exit; 


		$message = __("Unspecified error!", 'wsi');
		$hint    = ""; 
	
		if( isset($e) )
		{
			switch( $e->getCode() ){
				case 0 	: $message = __("Unspecified error.", 'wsi'); break;
				case 1 	: $message = __("Hybriauth configuration error.", 'wsi'); break;
				case 2 	: $message = __("Provider not properly configured.", 'wsi'); break;
				case 3 	: $message = __("Unknown or disabled provider.", 'wsi'); break;
				case 4 	: $message = __("Missing provider application credentials.", 'wsi');
						 $hint      = sprintf( __("<b>What does this error mean ?</b><br />Most likely, you didn't setup the correct application credentials for %s", 'wsi'), $provider );
						 break;
				case 5 	: $message = __("Authentification failed. The user has canceled the authentication or the provider refused the connection.", 'wsi'); break;
				case 6 	: $message = __("User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.", 'wsi');
						 if( is_object( $adapter ) ) $adapter->logout();
						 break;
				case 7 	: $message = __("User not connected to the provider.", 'wsi');
						 if( is_object( $adapter ) ) $adapter->logout();
						 break;
				case 8 	: $message = __("Provider does not support this feature.", 'wsi'); break;
		
				case 9 	: 
				case 10 : $message = $e->getMessage(); break;
				
			}
		}
		else
		{
			$message = __("Please double check your app API Key and Secret");
		}
	?>
	<!DOCTYPE html>
	<head>
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<style> 
	HR {
		width:100%;
		border: 0;
		border-bottom: 1px solid #ccc; 
		padding: 50px;
	}
	html {
	    background: #f9f9f9;
	}
	#wsl {
		background: #fff;
		color: #333;
		font-family: sans-serif;
		margin: 2em auto;
		padding: 1em 2em;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		border: 1px solid #dfdfdf;
		max-width: 700px;
		font-size: 14px;
	}  
	</style>
	<head>  
	<body>
	<div id="wsl">
	<table width="100%" border="0">
		<tr>
		<td align="center"><br /><img src="<?php echo WSI_PLUGIN_URL ?>/public/assets/img/alert.png" /></td>
		</tr>
		<tr>
		<td align="center">
			<p style="line-height: 20px;padding: 8px;background-color: #FFEBE8;border:1px solid #CC0000;border-radius: 3px;padding: 10px;text-align:center;">
				<?php echo $message ; ?> 
			</p>
		</td> 
		</tr> 
	
		
	</table>  
	</div> 
	</body>
	</html> 