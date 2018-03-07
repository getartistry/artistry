(function($){
	
	$(document).on( 'click', '.usin-notice .notice-dismiss', function() {
		$parent = $(this).parent('.usin-notice');
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'usin_mark_notice_as_dismissed',
                notice_id: $parent.data('notice_id'),
                nonce: $parent.data('nonce'),
                dismiss_period: $parent.data('dismiss_period')
			}
		});

	});
	
})(jQuery);