/* Fix Divi Builder styles */
jQuery(function($){
	
	/* Remove the divi builder plugin main id */
	$('.et_builder_outer_content').removeAttr('id');
	
	/* Store the (non-Divi Builder influenced) styles of elements in the learndash modules */
	$('.et_builder_outer_content .et_pb_ld_module').find("*").each(function(){
		$(this).data('dlkcss', $(this).css('cssText'));
	});
	
	/* Add the divi builder plugin main id back in */
	$('.et_builder_outer_content').attr('id', 'et_builder_outer_content');
	
	/* Override the (Divi Builder influenced) styles with the previously stored version */
	$('.et_builder_outer_content .et_pb_ld_module').find("*").each(function(){
		$(this).css('cssText', $(this).data('dlkcss'));
		$(this).data('dlkcss', '');
	});
});