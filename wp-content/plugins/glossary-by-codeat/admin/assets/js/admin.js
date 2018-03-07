(function ($) {
  'use strict';
  $(function () {
  	$('.postbox .gl-labels span').each(function (k, v) {
		var $this = $(v);
		if( $this.text() === '0') {
			$this.addClass('zero-el');
		}		
	});
	$(".glossary-settings #tabs").tabs({
	  activate: function (event, ui) {
		var scrollPos = $(window).scrollTop();
		window.location.hash = ui.newPanel.selector;
		$(window).scrollTop(scrollPos);
	  }
	});
	$('#glossary_post_metabox input:checkbox, #glossary_metabox input:checkbox, .glossary_page_glossary .cmb-td input:checkbox').each(function (k, v) {

	  var $this = $(v);
	  if ($this.is(':checkbox') && !$this.data('checkbox-replaced')) {

		// add some data to this checkbox so we can avoid re-replacing it.
		$this.data('checkbox-replaced', true);

		// create HTML for the new checkbox.
		var $l = $('<label for="' + $this.attr('id') + '" class="chkbox"></label>');
		var $y = $('<span class="yes">checked</span>');
		var $n = $('<span class="no">unchecked</span>');
		var $t = $('<span class="toggle"></span>');

		// insert the HTML in before the checkbox.
		$l.append($y, $n, $t).insertBefore($this);
		$this.addClass('replaced');

		// check if the checkbox is checked, apply styling. trigger focus.
		$this.on('change', function () {

		  if ($this.is(':checked')) {
			$l.addClass('on');
		  } else {
			$l.removeClass('on');
		  }

		  $this.trigger('focus');

		});

		$this.on('focus', function () {
		  $l.addClass('focus')
		});
		$this.on('blur', function () {
		  $l.removeClass('focus')
		});


		// check if the checkbox is checked on init.
		if ($this.is(':checked')) {
		  $l.addClass('on');
		} else {
		  $l.removeClass('on');
		}

	  }
	});
	$('.cmb2-radio-list input').each(function (k, v) {
	  var $this = $(v);
	  if ($this.is(':radio') && !$this.data('radio-replaced')) {
		// add some data to this checkbox so we can avoid re-replacing it.
		$this.data('radio-replaced', true);

		// create HTML for the new checkbox.
		var $l = $('<label for="' + $this.attr('id') + '" class="rdio"></label>');
		var $p = $('<span class="pip"></span>');

		// insert the HTML in before the checkbox.
		$l.append($p).insertBefore($this);
		$this.addClass('replaced');

		// check if the radio is checked, apply styling. trigger focus.
		$this.on('change', function () {
		  $('label.rdio').each(function (k, v) {
			var $v = $(v);
			if ($('#' + $v.attr('for')).is(':checked')) {
			  $v.addClass('on');
			} else {
			  $v.removeClass('on');
			}
		  });

		  $this.trigger('focus');
		});

		$this.on('focus', function () {
		  $l.addClass('focus')
		});
		$this.on('blur', function () {
		  $l.removeClass('focus')
		});

		// check if the radio is checked on init.
		$('label.rdio').each(function (k, v) {
		  var $v = $(v);
		  if ($('#' + $v.attr('for')).is(':checked')) {
			$v.addClass('on');
		  } else {
			$v.removeClass('on');
		  }
		});
	  }
	});

  });
})(jQuery);