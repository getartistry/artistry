/**
 * AutomateWoo SMS Tester
 */

jQuery(function($) {

	AutomateWoo.SMS_Tester = {

		$fields: {
			from: $( '#automatewoo_twilio_from' ),
			auth_id: $( '#automatewoo_twilio_auth_id' ),
			auth_token: $( '#automatewoo_twilio_auth_token' ),
			test_recipient: $( '#automatewoo-sms-test-recipient' ),
			test_message: $( '#automatewoo-sms-test-message' )
		},

		$button: $( '#automatewoo-sms-test-twilio' ),


		init: function(){

			AutomateWoo.SMS_Tester.$button.click(function(){
				AutomateWoo.SMS_Tester.send_test();
			});

		},


		send_test: function() {

			var text_initial = AutomateWoo.SMS_Tester.$button.val(),
				text_loading = AutomateWoo.SMS_Tester.$button.data('loading-text');

			AutomateWoo.SMS_Tester.$button.val( text_loading ).addClass('disabled').blur();

			AutomateWoo.notices.clear_all();

			$.ajax({
				method: 'POST',
				url: ajaxurl,
				data: {
					action: 'aw_test_sms',
					from: AutomateWoo.SMS_Tester.$fields.from.val(),
					auth_id: AutomateWoo.SMS_Tester.$fields.auth_id.val(),
					auth_token: AutomateWoo.SMS_Tester.$fields.auth_token.val(),
					test_message: AutomateWoo.SMS_Tester.$fields.test_message.val(),
					test_recipient: AutomateWoo.SMS_Tester.$fields.test_recipient.val()
				}
			})
				.done(function(response){

					console.log( response );

					if ( response.success ) {
						AutomateWoo.notices.success( response.data.message, $('.automatewoo-sms-test-container') );
					}
					else {
						AutomateWoo.notices.error( response.data.message, $('.automatewoo-sms-test-container') );
					}

					AutomateWoo.SMS_Tester.$button.val( text_initial ).removeClass('disabled');
				})

				.fail(function(response){
					console.log( response );
				});
			;

		}

	};


	AutomateWoo.SMS_Tester.init();

});