(function($) {
	$(document).ready(function() {
		var get_tasks = function () {
			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'get_tasks'
				},
				success: function(response) {
					if (response) {
						for (var i = 0; i < response.length; i++) {
							var item = $('.posts #post-' + response[i][0]);

							item.find('td.status span').attr('class', 'ol_status ol_status_' + response[i][1]).html(response[i][2]);
							item.find('td.schedules p:eq(0) span').html(response[i][3]);
							item.find('td.schedules p:eq(1) span').html(response[i][4]);
							item.find('td.schedules p:eq(2) span').html(response[i][5]);
							item.find('td.schedules p:eq(3) span').html(response[i][6]);
							item.find('td.actions .run').attr('class', 'button run ol_status_' + response[i][1]);
							item.find('td.actions .stop').attr('class', 'button stop ol_status_' + response[i][1]);
						}
					}

					setTimeout(function () {
						get_tasks();
					}, 3000);
				}
			});
		};

		get_tasks();
	});
})(jQuery);

