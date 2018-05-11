var jq_scrape = $.noConflict();

angular
	.module('octolooks', [])
	.controller('options', function(
		$scope,
		$compile,
		$timeout
	){
        $ = jq_scrape;

		$scope.submit = function($event) {
			if ($scope.form.$invalid) {
				$event.preventDefault();
				$scope.submitted = true;
				$('.collapse:not(.in)').collapse('show');
				$timeout(function() {
					$('body').animate({
						scrollTop: ($('.has-error:eq(0)').offset().top) - 30
					}, 1000);
				});
			}
		};

		$scope.set_sliders = function () {
			$('.slider').append('<div></div>').find('div').slider({
				create: function() {
					var slider = $(this);
					$timeout(function () {
						var current, input;
						var value = eval('$scope.model.' + slider.parent().data('value'));
						var values = eval('$scope.model.' + slider.parent().data('values'));
						for (var key in values) { if (values[key]['id'] == value) { current = key; } }
						slider
							.slider('option', 'max', (values.length - 1))
							.slider('option', 'value', current)
							.find('.ui-slider-handle').text(function () { return values[current].name; });
						input = angular.element('<input type="text" name="' + slider.parent().data('value') + '" class="hidden" ng-model="model.' + slider.parent().data('value') + '">');
						$compile(input)($scope);
						slider.parent().after(input);
					});
				},
				slide: function($event, ui) {
					var slider = $(this);
					$timeout(function () {
						var value = ui.value;
						var values = eval('$scope.model.' + slider.parent().data('values'));
						slider.find('.ui-slider-handle').text(function () {
							return values[value].name;
						});
						eval('$scope.model.' + slider.parent().data('value') + ' = ' + '\'' + values[value].id + '\'');
					});
				}
			});
		};

		$scope.set_popover = function() {
            $('[data-toggle="popover"]').popover({
				trigger: 'hover',
				container: '.bootstrap',
				html: true
			});
		};

		$scope.set_radio_value = function() {
			var input = 'input[type="radio"]:not([name="scrape_type"])';

			$(input + ':not(:checked)').each(function() {
				if (!$(input + '[name="' + $(this).attr('name') + '"]:checked').length) {
					$(this).prop('checked', function() {
						eval('$scope.' + $(this).attr('ng-model') + ' = \'' + $(this).val() + '\'');
						return true;
					});
				}
			});
		};

		$scope.set_template_tags = function() {
			$(document).on('click', '.input-tags .btn', function() {
				var pos = 0;
				var range;
				var browser;
				var text = $(this).data('value');
				var target = $(this).parent().prev().find('input[type="text"]');

				if (!target.length) {
					target = $('textarea.wp-editor-area');

					if (target.is(':hidden')) {
						$('#scrapetemplate-html').click();
					}
				}

				target.focus();
				var input = document.activeElement;

				if (input.selectionStart || input.selectionStart == '0') {
					browser = 'ff'
				} else {
					if (document.selection) {
						browser = 'ie'
					} else {
						browser = false;
					}
				}

				if (browser == 'ie') {
					input.focus();
					range = document.selection.createRange();
					range.moveStart ("character", -input.value.length);
					pos = range.text.length;
				} else if (browser == 'ff') {
					pos = input.selectionStart;
				}

				var front = (input.value).substring(0, pos);
				var back = (input.value).substring(pos, input.value.length);

				input.value = front + text + back;
				pos = pos + text.length;

				if (browser == 'ie') {
					input.focus();
					range = document.selection.createRange();
					range.moveStart ('character', -input.value.length);
					range.moveStart ('character', pos);
					range.moveEnd('character', 0);
					range.select();
				}

				else if (browser == "ff") {
					input.selectionStart = pos;
					input.selectionEnd = pos;
					input.focus();
				}
			});
		};

		$scope.set_sidebar = function() {
			var sidebar = $('.sidebar');
			sidebar.width(sidebar.parent().width()).addClass('fixed');
		};

		$scope.show_media_library = function($event) {
			var media = wp.media({
				multiple: false,
				title: translate.media_library_title,
				library: {
					type: 'image'
				}
			});

			media.on('select', function() {
				var input = $($event.target).closest('.input-group').find('input[type="text"]');
				input.val(media.state().get('selection').first().toJSON().id);
				eval('$scope.' + $(input).attr('ng-model') + ' = \'' + $(input).val() + '\'');
			});

			media.open();
		};

		$scope.set_custom_field_name_auto_complete = function() {
			var input = $('input[name^="scrape_custom_fields"][name*="[name]"]');

			if (input.data('autocomplete')) {
				input.autocomplete('destroy');
			}

			if ($scope.model.all_custom_fields[$scope.model.scrape_post_type]) {
				input.autocomplete({
					source: $scope.model.all_custom_fields[$scope.model.scrape_post_type],
					minLength: 0
				}).on('focus', function() {
					if ($(this).val().length === 0) {
						$(this).autocomplete('search');
					}
				});
			} else {
				input.autocomplete({
					source: []
				});
			}
		};

		$scope.add_field = function($event, type) {
			if (type == 'custom_field') {
				var custom_field_id = new Date().getTime();

				$($event.target).closest('.form-group').before(
					'<div class="form-group" ng-class="{\'has-error\' : form[\'scrape_custom_fields[' + custom_field_id + '][value]\'].$invalid && (form[\'scrape_custom_fields[' + custom_field_id + '][value]\'].$dirty || submitted)}">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.name + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_id + '][name]" placeholder="' + translate.eg_name + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.value + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_id + '][value]" placeholder="' + translate.xpath_placeholder + '" class="form-control" ng-model="model[\'scrape_custom_fields[' + custom_field_id + '][value]\']" ng-pattern="/^///">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="show_iframe_single($event)"><i class="icon ion-android-locate"></i></button></span>' +
							'</div>' +
							'<p class="help-block" ng-show="form[\'scrape_custom_fields[' + custom_field_id + '][value]\'].$invalid && (form[\'scrape_custom_fields[' + custom_field_id + '][value]\'].$dirty || submitted)">' + translate.enter_valid + '</p>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.attribute + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_id + '][attribute]" placeholder="' + translate.eg_href + '" class="form-control">' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12" ng-show="model.scrape_custom_fields[' + custom_field_id + '].template_status">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.template + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_id + '][template]" placeholder="' + translate.eg_scrape_value + '" class="form-control">' +
							'</div>' +
							'<div class="input-tags">' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_value]\'>' + translate.btn_value + '</button>' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'calc([scrape_value] + 0)\'>' + translate.btn_calculate + '</button>' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_date]\'>' + translate.btn_date + '</button>' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'[scrape_url]\'>' + translate.btn_source_url + '</button>' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'{{amazon_product_url()}}\' ng-if="special_url == \'amazon\'"><i class="fa fa-amazon"></i> ' + translate.btn_product_url + '</button>' +
								'<button type="button" class="btn btn-primary btn-xs" data-value=\'{{amazon_cart_url()}}\' ng-if="special_url == \'amazon\'"><i class="fa fa-amazon"></i> ' + translate.btn_cart_url + '</button>' +
							'</div>' +
						'</div>' +
						'<div class="separator">' +
							'<div class="col-sm-12">' +
								'<div class="form-group" ng-show="model.scrape_custom_fields[' + custom_field_id + '].regex_status">' +
									'<div class="col-sm-12">' +
										'<button type="button" class="btn btn-link" ng-click="add_field($event, \'custom_field_regex\')"><i class="icon ion-plus-circled"></i> ' + translate.add_new_replace + '</button>' +
									'</div>' +
								'</div>' +
								'<div class="form-group">' +
									'<div class="col-sm-12">' +
										'<div class="checkbox"><label><input type="checkbox" name="scrape_custom_fields[' + custom_field_id + '][template_status]" ng-model="model.scrape_custom_fields[' + custom_field_id + '].template_status"> ' + translate.enable_template + '</label></div>' +
										'<div class="checkbox"><label><input type="checkbox" name="scrape_custom_fields[' + custom_field_id + '][regex_status]" ng-model="model.scrape_custom_fields[' + custom_field_id + '].regex_status"> ' + translate.enable_find_replace + '</label></div>' +
										'<div class="checkbox"><label><input type="checkbox" name="scrape_custom_fields[' + custom_field_id + '][allowhtml]" ng-model="model.scrape_custom_fields[' + custom_field_id + '].allowhtml"> ' + translate.allow_html_tags + '</label></div>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>'
				);

				$compile($($event.target).closest('.form-group').prev())($scope);
				$scope.set_custom_field_name_auto_complete();
			}

			if (type == 'custom_field_regex') {
				var custom_field_regex_id = $($event.target).closest('.form-group').attr('ng-show').split(/[\[\]]/)[1];

				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_custom_fields[' + custom_field_regex_id + '].regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_regex_id + '][regex_finds][]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_custom_fields[' + custom_field_regex_id + '][regex_replaces][]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'title_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_title_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_title_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_title_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'content_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_type && model.scrape_content_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_content_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">'+translate.replace + '</div>' +
								'<input type="text" name="scrape_content_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'excerpt_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_excerpt_type == \'xpath\' && model.scrape_excerpt_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_excerpt_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_excerpt_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}
			
			if (type == 'tags_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_tags_type == \'xpath\' && model.scrape_tags_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_tags_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_tags_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'category_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_categoryxpath_tax && model.scrape_category_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_category_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_category_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'date_regex') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group" ng-show="model.scrape_date_type == \'xpath\' && model.scrape_date_regex_status">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.find + '</div>' +
								'<input type="text" name="scrape_date_regex_finds[]" placeholder="' + translate.eg_find + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.replace + '</div>' +
								'<input type="text" name="scrape_date_regex_replaces[]" placeholder="' + translate.eg_replace + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}

			if (type == 'cookie') {
				$($event.target).closest('.form-group').before($compile(
					'<div class="form-group">' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.name + '</div>' +
								'<input type="text" name="scrape_cookie_names[]" placeholder="' + translate.eg_name + '" class="form-control">' +
								'<span class="input-group-btn"><button type="button" class="btn btn-primary btn-block" ng-click="remove_field($event)"><i class="icon ion-trash-a"></i></button></span>' +
							'</div>' +
						'</div>' +
						'<div class="col-sm-12">' +
							'<div class="input-group">' +
								'<div class="input-group-addon">' + translate.value + '</div>' +
								'<input type="text" name="scrape_cookie_values[]" placeholder="' + translate.eg_value + '" class="form-control">' +
							'</div>' +
						'</div>' +
					'</div>'
				)($scope));
			}
		};

		$scope.remove_field = function($event) {
			$($event.target).closest('.form-group').remove();
		};

		$scope.update_categories = function(post_type) {
			var modal_loading = $('#loading');

			$scope.model.scrape_categoryxpath_tax = null;

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'html',
				data: {
					action: 'get_post_cats',
					post_type: post_type,
					post_id: $('#post_ID').val()
				},
				beforeSend: function() {
					modal_loading.modal({backdrop: 'static'});
				},
				success: function(response) {
					var categories = $('[ng-model="model.scrape_post_type"]')
						.parents().find('.overflow');

					if (response) {
						$scope.model.category_exists = true;
						$scope.$apply();
						categories.replaceWith($compile(
							'<div class="overflow">' +
								response +
							'</div>'
						)($scope));
					} else {
						$scope.model.category_exists = false;
						$scope.$apply();
					}

					modal_loading.modal('hide');
				}
			});
			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'html',
				data: {
					action: 'get_post_tax',
					post_type: post_type,
					post_id: $('#post_ID').val()
				},
				beforeSend: function() {
					modal_loading.modal({backdrop: 'static'});
				},
				success: function(response) {
					var taxonomies = $('[ng-model="model.scrape_categoryxpath_tax"]');

					if (response) {
						$scope.model.taxonomy_exists = true;
						$scope.$apply();
						taxonomies.replaceWith($compile(
							'<select name="scrape_categoryxpath_tax" class="form-control" ng-model="model.scrape_categoryxpath_tax">' +
							'<option value="">' + translate.select_taxonomy + '</option>' +
							response +
							'</select>'
							)($scope));
					} else {
						$scope.model.taxonomy_exists = false;
						$scope.$apply();
					}

					modal_loading.modal('hide');
				}
			});

			$scope.set_custom_field_name_auto_complete();
		};

		$scope.set_iframe = function() {
			var url_serial;
			var url_single;
			var modal_error = $('#error');
			var modal_loading = $('#loading');
			var modal_iframe = $('#iframe');
			var iframe = modal_iframe.find('iframe');
			var iframe_serial = $('#iframe_serial');
			var iframe_single = $('#iframe_single');

			$scope.show_iframe = function($event, type) {
				$scope.input_current = $($event.target).closest('.input-group').find('input[type="text"]');
				$scope.input_post_item = $('input[type="text"][name="scrape_listitem"]');
				$scope.input_next_page = $('input[type="text"][name="scrape_nextpage"]');
				$scope.input_next_page_innerhtml = $('input[type="text"][name="scrape_nextpage_innerhtml"]');
				$scope.input_featured_image = $('input[type="text"][name="scrape_featured"]');

				$scope.input_cookies = function() {
					var cookie_string = '';
					var names = $('input[type="text"][name="scrape_cookie_names[]"]');
					var values = $('input[type="text"][name="scrape_cookie_values[]"]');

					$.each(names, function(index, item) {
						cookie_string += '&cookie_names[]=' + encodeURIComponent($(item).val());
						cookie_string += '&cookie_values[]=' + encodeURIComponent($(values[index]).val());
					});

					return cookie_string;
				};

				if ($scope.model.scrape_type == 'list') {
					if (type == 'serial') {
						if ($scope.form.scrape_url.$valid) {
							url_serial = ajaxurl + '?action=get_url&address=' + encodeURIComponent($scope.model.scrape_url) + $scope.input_cookies();
							iframe_serial.show();

							if (iframe_serial.attr('src') == url_serial) {
								modal_iframe.modal('show');
							} else {
								modal_loading.modal({backdrop: 'static'});
								iframe_serial.attr('src', url_serial);
							}
						} else {
							$scope.error = translate.source_url_not_valid;
							modal_error.modal('show');
							modal_iframe.modal('hide');
						}
					}
					if (type == 'single') {
						if ($scope.form.scrape_url.$valid || $scope.form.scrape_exact_match.$valid) {
							if($scope.form.scrape_exact_match.$valid) {
                                $scope.model.scrape_url_single = $scope.convert_xpath_to_jquery(iframe_serial, $scope.model.scrape_listitem).attr('href');
							}

							if ($scope.form.scrape_listitem.$valid) {
								url_single = ajaxurl + '?action=get_url&address=' + encodeURIComponent($scope.model.scrape_url_single) + $scope.input_cookies();
								iframe_single.show();

								if (iframe_single.attr('src') == url_single) {
									modal_iframe.modal('show');
								} else {
									iframe_single.attr('src', url_single);
									modal_loading.modal({backdrop: 'static'});
								}
							} else {
								$scope.error = translate.post_item_not_valid;
								modal_error.modal('show');
								modal_iframe.modal('hide');
							}
						} else {
							$scope.error = translate.source_url_not_valid;
							modal_error.modal('show');
							modal_iframe.modal('hide');

						}
					}
				}
				if ($scope.model.scrape_type == 'single') {
					if ($scope.form.scrape_url.$valid) {
						url_single = ajaxurl + '?action=get_url&address=' + encodeURIComponent($scope.model.scrape_url) + $scope.input_cookies();
						iframe_single.show();

						if (iframe_single.attr('src') == url_single) {
							modal_iframe.modal('show');
						} else {
							iframe_single.attr('src', url_single);
							modal_loading.modal({backdrop: 'static'});
						}
					} else {
						$scope.error = translate.source_url_not_valid;
						modal_error.modal('show');
						modal_iframe.modal('hide');
					}
				}
				if ($scope.model.scrape_type == 'feed') {
					if ($scope.form.scrape_url.$valid) {
						url_single = ajaxurl + '?action=get_url&address=' + encodeURIComponent($scope.model.scrape_url) + $scope.input_cookies() + "&scrape_feed";
						iframe_single.show();

						if (iframe_single.attr('src') == url_single) {
							modal_iframe.modal('show');
						} else {
							iframe_single.attr('src', url_single);
							modal_loading.modal({backdrop: 'static'});
						}
					} else {
						$scope.error = translate.source_url_not_valid;
						modal_error.modal('show');
						modal_iframe.modal('hide');
					}
				}

				modal_iframe.on('hidden.bs.modal', function () {
					iframe_serial.hide();
					iframe_single.hide();
				});
			};

			iframe.on('load', function() {
				var iframe_current = $(this);

				$scope.toggle_iframe_styles();
				modal_loading.modal('hide');
				modal_iframe.modal('show');

				$(this).contents().find('head').append(
					$('<link/>', {
						rel: 'stylesheet',
						type: 'text/css',
						href: translate.plugin_path + '/ol_scrapes/assets/css/iframe.css',
						id: 'ol_scrapes_inspector'
					})
				);

				$(this).contents()
					.on('mouseover', function(event) {
						$(event.target).addClass('ol_scrapes_inspector');
					})
					.on('mouseout', function(event) {
						$(event.target).removeClass('ol_scrapes_inspector');
					})
					.on('click', function(event) {
						event.preventDefault();

						if (iframe_current.attr('id') == iframe_serial.attr('id')) {
							var checked_a = $scope.check_element('a', $scope.convert_xpath_to_jquery(iframe_serial, $scope.get_xpath(event.target)));

							if ($scope.input_current.attr('name') == $scope.input_post_item.attr('name')) {
								if (checked_a == true) {
									$scope.input_current.val($scope.get_absolute_xpath(event.target));
								} else {
									if (typeof (checked_a) === 'object') {
										$scope.input_current.val($scope.get_absolute_xpath(checked_a));
									} else {
										$scope.error = translate.item_not_link;
										modal_error.modal('show');
										modal_iframe.modal('hide');
										return false;
									}
								}
								if ($scope.special_url == 'mfacebook') {
									$scope.input_current.val('//a[text()="' + $(event.target).text() + '"]');
								}

								$scope.model.scrape_url_single = $scope.convert_xpath_to_jquery(iframe_serial, $scope.input_current.val()).attr('href');
								$scope.next_page_found = '//html//link[@rel="next"]';

								if (!$scope.input_next_page.val().length && $scope.convert_xpath_to_jquery(iframe_serial, $scope.next_page_found).length) {
									$scope.input_next_page.val($scope.next_page_found);
									eval('$scope.' + $scope.input_next_page.attr('ng-model') + ' = \'' + $scope.input_next_page.val() + '\'');
								}
							} else {
								if (checked_a == true) {
									$scope.input_current.val($scope.get_next_page_xpath(event.target));
								} else {
									if (typeof (checked_a) === 'object') {
										$scope.input_current.val($scope.get_next_page_xpath(checked_a));
									} else {
										$scope.error = translate.item_not_link;
										modal_error.modal('show');
										modal_iframe.modal('hide');
										return false;
									}
								}
							}
						}

						if (iframe_current.attr('id') == iframe_single.attr('id')) {
							if ($scope.input_featured_image.length &&
								$scope.form.scrape_featured.$pristine &&
								!$scope.input_featured_image.val().length) {
								var xpaths = [
									'//meta[@itemprop="image"]/@content',
									'//meta[@property="og:image"]/@content',
									'//meta[@name="twitter:image"]/@content'
								];
								for (var i = 0; i < xpaths.length; i++) {
									if ($scope.convert_xpath_to_jquery(iframe_single, xpaths[i]).length) {
										$scope.featured_image_found = xpaths[i];
										break;
									}
								}
								$scope.input_featured_image.val($scope.featured_image_found);
								eval('$scope.' + $scope.input_featured_image.attr('ng-model') + ' = \'' + $scope.input_featured_image.val() + '\'');
							}

							if ($scope.input_current.attr('name') == $scope.input_featured_image.attr('name')) {
								var checked_img = $scope.check_element('img', $scope.convert_xpath_to_jquery(iframe_single, $scope.get_xpath(event.target)));

								if (checked_img == true) {
									img_xpath = $scope.get_xpath(event.target);
									img_xpath = img_xpath.split(" | ");
									if(img_xpath.length == 2) {
										img_xpath = img_xpath[0] + "/@src" + " | " +  img_xpath[1] + "/@src";
									} else {
										img_xpath = img_xpath[0] + "/@src";
									}
									$scope.input_current.val(img_xpath);
								} else {
									if (typeof(checked_img) === 'object') {
										img_xpath = $scope.get_xpath(checked_img);
										img_xpath = img_xpath.split(" | ");
										if (img_xpath.length == 2) {
											img_xpath = img_xpath[0] + "/@src" + " | " +  img_xpath[1] + "/@src";
										} else {
											img_xpath = img_xpath[0] + "/@src";
										}
										$scope.input_current.val(img_xpath);
									} else {
										$scope.error = translate.item_not_image;
										modal_error.modal('show');
										modal_iframe.modal('hide');
										return false;
									}
								}
							} else {
								$scope.input_current.val($scope.get_xpath(event.target));
							}
						}

						eval('$scope.' + $scope.input_current.attr('ng-model') + ' = \'' + $scope.input_current.val() + '\'');
						$scope.$apply();
						modal_iframe.modal('hide');
					});
			});
		};

		$scope.show_iframe_serial = function($event) {
			$scope.show_iframe($event, 'serial');
		};

		$scope.show_iframe_single = function($event) {
			$scope.show_iframe($event, 'single');
		};

		$scope.toggle_iframe_styles = function() {
			var modal_iframe = $('#iframe');
			var iframe = modal_iframe.find('iframe');

			iframe.each(function() {
				var contents = $(this).contents();
				var elements = $(this).contents().find('[style]');

				if ($scope.iframe_styles) {
					contents.find('link[rel="stylesheet"]').not('#ol_scrapes_inspector').attr('disabled', 'disabled');
					contents.find('style[media="print"]').remove();
					contents.find('style').attr('media', 'print');

					for (var i = 0; i < elements.length; i++) {
						$(elements[i]).attr('data-style', $(elements[i]).attr('style'));
						$(elements[i]).removeAttr('style');
					}
				} else {
					contents.find('link[rel="stylesheet"]').removeAttr('disabled');
					contents.find('style[media="print"]').attr('media', 'screen');
					elements = contents.find('[data-style]');

					for (var j = 0; j < elements.length; j++) {
						$(elements[j]).attr('style', $(elements[j]).attr('data-style'));
						$(elements[j]).removeAttr('data-style');
					}
				}
			});
		};

		$scope.check_element = function(type, element) {
			if (element.is(type)) {
				return true;
			} else {
				if (element.find(type + ':first').is(type)) {
					return element.find(type + ':first').get(0);
				} else {
					if (element.parents().find(type + ':last').is(type)) {
						return element.parents().find(type + ':last').get(0);
					} else {
						return false;
					}
				}
			}
		};

		$scope.get_absolute_xpath = function(element) {
			var result = [];

			$($(element).parents().addBack().get().reverse()).each(function () {
				var name_tag = this.nodeName.toLowerCase();
				var name_node = name_tag;

				if ($(this).siblings(name_node).length > 0) {
					name_tag += "[" + ($(this).prevAll(name_tag).length + 1) + "]";
				}

				result.push(name_tag);
			});

			return '//' + result.reverse().join('/');
		};
		
		$scope.get_next_page_xpath = function(element) {
			var result = [];

			$($(element).parents()).each(function () {
				var name_tag = this.nodeName.toLowerCase();
				if ($(this).attr("class")) {
					name_tag += '[contains(concat (" ", normalize-space(@class), " "), " ' + $(this).attr("class").trim().replace(/\s+/g, ' ') + ' ")]';
					result.push(name_tag);
					return false;
				}
			});
			
			name_tag = 'a';
			
			if ($(element).attr("class")) {
				name_tag += '[contains(concat (" ", normalize-space(@class), " "), " ' + $(element).attr("class").trim().replace(/\s+/g, ' ') + ' ")]';
			}
			
			$scope.input_next_page_innerhtml.val($(element).text().replace(/\s+/g, ' '));
			eval('$scope.' + $scope.input_next_page_innerhtml.attr('ng-model') + ' = \'' + $scope.input_next_page_innerhtml.val() + '\'');
			result.push(name_tag);

			return '//' + result.join('//');
		};

		$scope.get_xpath = function(element) {
			var result = [];
            var parent_index = 0;

			$($(element).parents().addBack().get().reverse()).each(function () {
				var name_tag = this.nodeName.toLowerCase();
				var name_node = name_tag;

				if (name_node == 'body') {
					return false;
				}

				if ($(this).hasClass('ol_scrapes_inspector')) {
					$(this).removeClass('ol_scrapes_inspector');
				}

				if ($(this).attr('id')) {
					non_digits = $(this).attr('id').split(/\s+/).filter(function(c){
						return ! /\d/.test(c);
					}).join(' ');
					if(non_digits != "") {
						name_tag += '[@id="' + non_digits + '"]';
						result.push(name_tag);
						return false;
					}
				}

				if ($(this).siblings(name_node).length > 0) {
					name_tag += "[" + ($(this).prevAll(name_tag).length + 1) + "]";
				}

				if ($(this).attr('class')) {
					non_digits = $(this).attr('class').split(/\s+/).filter(function(c){
						return ! /\d/.test(c);
					}).join(' ');
					if(non_digits != "") {
						non_digits = non_digits.trim().replace(/\s+/g, ' ');
						name_tag += '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]';
						$elements = $scope.convert_xpath_to_jquery($('#iframe_single'), "//" + name_node + '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]');
						if($elements.length == 1 && parent_index == 0) {
							result = [];
							result.push(name_node + '[contains(concat (" ", normalize-space(@class), " "), " ' + non_digits + ' ")]');
							return false;
						}
					}
				}
				parent_index++;
				result.push(name_tag);
			});
			if(parent_index == 0) {
                return '//' + result.reverse().join('/');
            } else {
                return '//' + result.reverse().join('/') + ' | ' + $scope.get_absolute_xpath(element);
			}

		};

		$scope.convert_xpath_to_jquery = function(container, xpath) {
			var item;
			var result = [];
			var doc = container[0].contentWindow.document;
			var xpaths = doc.evaluate(xpath, doc, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);

			while (item = xpaths.iterateNext()) {
				result.push(item);
			}

			return $([]).pushStack(result);
		};

		$scope.init = function() {
			$timeout(function() {
				$scope.set_iframe();
				$scope.set_sliders();
				$scope.set_sidebar();
				$scope.set_template_tags();
				$scope.set_custom_field_name_auto_complete();
			});

			$scope.$watchCollection(
				function() { return $scope.model.scrape_url; },
				function() {
					$timeout(function() {
						var url = $scope.model.scrape_url;
						var cookie_names   = 'input[type="text"][name="scrape_cookie_names[]"]';
						var cookie_values  = 'input[type="text"][name="scrape_cookie_values[]"]';
						var cookie_button  = 'button[ng-click="add_field($event, \'cookie\')"]';

						// Amazon
						if (/(\/|\.)amazon\./.test(url)) {
							var amazon_url = url.match(/^(?:https?:)?(?:\/\/)?([^\/\?]+)/i)[0];

							$scope.amazon_product_url = function() {
								return amazon_url + '/dp/[scrape_asin]?tag=AMAZON_ASSOCIATE_TAG';
							};
							$scope.amazon_cart_url = function() {
								return amazon_url + '/gp/aws/cart/add.html?AssociateTag=AMAZON_ASSOCIATE_TAG&SubscriptionId=AMAZON_SUBSCRIPTION_ID&ASIN.1=[scrape_asin]&Quantity.1=1';
							};
							$scope.special_url = 'amazon';
						}

						// Twitter Mobile
						else if (/\/mobile\.twitter\.com/.test(url)) {
							var cookie_exists  = [];
							var cookies = [
								{name: 'm5', value: 'off'}
							];

							for (var i = 0; i < cookies.length; i++) {
								cookie_exists[i] = false;
								$(cookie_names).each(function() {
									var cookie_name = $(this).val();
									var cookie_value = $(this).closest('.form-group').find(cookie_values).val();

									if (cookie_name == cookies[i].name && cookie_value == cookies[i].value) {
										cookie_exists[i] = true;
										return false;
									}
								});
							}

							for (var i = 0; i < cookie_exists.length; i++) {
								if (cookie_exists[i] == false) {
									$(cookie_button).click();
									$(cookie_names).last().val(cookies[i].name);
									$(cookie_values).last().val(cookies[i].value);
								}
							}

							$scope.special_url = 'mtwitter';
						}

						// Facebook Mobile Basic
						else if (/\/mbasic\.facebook\.com/.test(url)) {
							var cookie_exists  = [];
							var cookies = [
								{name: 'c_user'},
								{name: 'xs'}
							];

							$scope.mfacebook_content_xpath = '//div[@data-ft]/div[1] | //div[@id="root"]/div[1]/div[1]/div[1]/div[1]';
							$scope.mfacebook_date_xpath = '//abbr';

							for (var i = 0; i < cookies.length; i++) {
								cookie_exists[i] = false;
								$(cookie_names).each(function() {
									var cookie_name = $(this).val();

									if (cookie_name == cookies[i].name) {
										cookie_exists[i] = true;
										return false;
									}
								});
							}

							for (var j = 0; j < cookie_exists.length; j++) {
								if (cookie_exists[j] == false) {
									$(cookie_button).click();
									$(cookie_names).last().val(cookies[j].name);
								}
							}
							
							if (!$scope.model.scrape_content) {
								$scope.model.scrape_content_type = 'xpath';
								$scope.model.scrape_content = $scope.mfacebook_content_xpath;
							}

							if (!$scope.model.scrape_date) {
								$scope.model.scrape_date_type = 'xpath';
								$scope.model.scrape_date = $scope.mfacebook_date_xpath;
							}

							$scope.special_url = 'mfacebook';
						}

						// YouTube
						else if (/(\/|\.)youtube\./.test(url)) {
							var cookie_exists  = [];
							var cookies = [
								{name: 'PREF', value: 'f6=8'}
							];

							for (var i = 0; i < cookies.length; i++) {
								cookie_exists[i] = false;
								$(cookie_names).each(function() {
									var cookie_name = $(this).val();
									var cookie_value = $(this).closest('.form-group').find(cookie_values).val();

									if (cookie_name == cookies[i].name && cookie_value == cookies[i].value) {
										cookie_exists[i] = true;
										return false;
									}
								});
							}

							for (var i = 0; i < cookie_exists.length; i++) {
								if (cookie_exists[i] == false) {
									$(cookie_button).click();
									$(cookie_names).last().val(cookies[i].name);
									$(cookie_values).last().val(cookies[i].value);
								}
							}

							$scope.special_url = 'youtube';
						}

						// AliExpress
						else if (/(\/|\.)aliexpress\./.test(url)) {
							var cookie_exists  = [];
							var cookies = [
								{name: 'xman_t'}
							];

							for (var i = 0; i < cookies.length; i++) {
								cookie_exists[i] = false;
								$(cookie_names).each(function() {
									var cookie_name = $(this).val();

									if (cookie_name == cookies[i].name) {
										cookie_exists[i] = true;
										return false;
									}
								});
							}

							for (var j = 0; j < cookie_exists.length; j++) {
								if (cookie_exists[j] == false) {
									$(cookie_button).click();
									$(cookie_names).last().val(cookies[j].name);
								}
							}

							$scope.special_url = 'aliexpress';
						}

						// Others
						else {
							$scope.special_url = false;
						}
					});
				}
			);

			$scope.$watchCollection(
				function() { return $scope; },
				function(value_new, value_old) {
					if (value_new !== value_old) {
						$scope.set_popover();
						$scope.set_radio_value();
					}
				}
			);

			$(window).resize(function () {
				$scope.set_sidebar();
			});
		};
	});

jQuery = jQuery_scrapes.noConflict();
$ = jQuery_scrapes.noConflict();