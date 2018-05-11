var jq_scrape = $.noConflict();

angular
	.module('octolooks', [])
	.controller('settings', function(
		$scope,
	    $timeout
	){
		$ = jq_scrape;
		$scope.submit = function($event) {
			if ($scope.form.$invalid || $scope.model.pc_valid) {
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

		$scope.remove_pc = function() {
			$scope.model.action = 'remove_pc';
		};

		$scope.set_popover = function() {
			$('[data-toggle="popover"]').popover({
				trigger: 'hover',
				container: '.bootstrap',
				html: true
			});
		};

		$scope.set_sidebar = function() {
			var sidebar = $('.sidebar');
			sidebar.width(sidebar.parent().width()).addClass('fixed');
		};

		$scope.init = function() {
			$timeout(function() {
				$scope.set_popover();
				$scope.set_sidebar();
			});
		};

		$(window).resize(function () {
			$scope.set_sidebar();
		});
	});
jQuery = jQuery_scrapes.noConflict();
$ = jQuery_scrapes.noConflict();