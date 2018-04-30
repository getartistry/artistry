<!-- start ConvertFox JS code-->
<script>
(function(d,h,w){var convertfox=w.convertfox=w.convertfox||[];convertfox.methods=['trackPageView','identify','track','setAppId'];convertfox.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);convertfox.push(e);return convertfox;}};for(var i=0;i<convertfox.methods.length;i++){var c=convertfox.methods[i];convertfox[c]=convertfox.factory(c)}s=d.createElement('script'),s.src="//d3sjgucddk68ji.cloudfront.net/convertfox.min.js",s.async=!0,e=d.getElementsByTagName(h)[0],e.appendChild(s),s.addEventListener('load',function(e){},!1),convertfox.setAppId("<?php echo $settings['project_id']; ?>"),convertfox.trackPageView()})(document,'head',window);
</script>
<!-- end ConvertFox JS code-->

<?php
	$current_user = wp_get_current_user();
	$sanitized_email = sanitize_email($current_user->user_email);

	if ( 0 != $current_user->ID  ) {
?>
<!-- This code is for identifying WordPress users in ConvertFox -->
<script>
	// pass user info to the convertfox.identify method
	convertfox.identify(<?php echo $current_user->ID; ?>, {
		'email': <?php echo "\"" . $sanitized_email . "\""; ?>,
		'name': <?php echo "\"" . $current_user->user_firstname . " " . $current_user->user_lastname . "\""; ?>,
        'username': <?php echo "\"" . sanitize_text_field($current_user->user_login) . "\""; ?>,
        'role': <?php echo "\"" . sanitize_text_field($current_user->roles[0]) . "\""; ?>
	});
</script>
<?php } ?>