(function ($) {
  'use strict';
  $(function () {
	//Detect action on the customizer
	$('#cmb2-metabox-glossary_options2 input[type="text"]').on("input change keyup", function () {
	  if ($(this).hasClass('wp-color-picker')) {
		send_new_css($(this).attr('name'), $(this).val());
	  } else {
		send_new_css(false, false);
	  }
	});
	$('.cmb2-colorpicker').wpColorPicker({
	  change: function (event, ui) {
		if (ui.color._hsv.h !== 0 && ui.color._hsv.s !== 0 && ui.color._hsv.v !== 0) {
		  send_new_css($(this).attr('name'), hsvToRgb(ui.color._hsv.h, ui.color._hsv.s, ui.color._hsv.v));
		} else {
		  setTimeout(function () {
			send_new_css($(this).attr('name'), hsvToRgb(ui.color._hsv.h, ui.color._hsv.s, ui.color._hsv.v));
		  }, 100);
		}
	  }
	});

	//Send to the iframe the new css
	function send_new_css(name, value) {
	  var list_css = {};
	  list_css['text_color'] = $('#cmb2-metabox-glossary_options2 .cmb2-id-text-color .wp-color-result').css('background-color');
	  list_css['text_background'] = $('#cmb2-metabox-glossary_options2 .cmb2-id-text-background .wp-color-result').css('background-color');
	  list_css['text_size'] = $('#cmb2-metabox-glossary_options2 #text_size').val();
	  list_css['lemma_color'] = $('#cmb2-metabox-glossary_options2 .cmb2-id-lemma-color .wp-color-result').css('background-color');
	  list_css['lemma_background'] = $('#cmb2-metabox-glossary_options2 .cmb2-id-lemma-background .wp-color-result').css('background-color');
	  list_css['lemma_size'] = $('#cmb2-metabox-glossary_options2 #lemma_size').val();
	  list_css['link_lemma_color'] = $('#cmb2-metabox-glossary_options2 .cmb2-id-link-lemma-color .wp-color-result').css('background-color');
	  console.log(list_css)
	  if (name !== false) {
		list_css[name] = value;
	  }
	  $(list_css).each(function (index, value) {
		if (value === '' || value === '#') {
		  list_css[index] = 'auto';
		}
	  });
	  $('#gt_preview').get(0).contentWindow.update_css(list_css);
	}
  });
})(jQuery);

//http://snipplr.com/view/14590/
function hsvToRgb(h, s, v) {
  var r, g, b;
  var i;
  var f, p, q, t;
  h = Math.max(0, Math.min(360, h));
  s = Math.max(0, Math.min(100, s));
  v = Math.max(0, Math.min(100, v));

  s /= 100;
  v /= 100;

  if (s === 0) {
	r = g = b = v;
	return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
  }

  h /= 60;
  i = Math.floor(h);
  f = h - i;
  p = v * (1 - s);
  q = v * (1 - s * f);
  t = v * (1 - s * (1 - f));

  switch (i) {
	case 0:
	  r = v;
	  g = t;
	  b = p;
	  break;

	case 1:
	  r = q;
	  g = v;
	  b = p;
	  break;

	case 2:
	  r = p;
	  g = v;
	  b = t;
	  break;

	case 3:
	  r = p;
	  g = q;
	  b = v;
	  break;

	case 4:
	  r = t;
	  g = p;
	  b = v;
	  break;

	default:
	  r = v;
	  g = p;
	  b = q;
  }

  return 'rgb(' + Math.round(r * 255) + ',' + Math.round(g * 255) + ',' + Math.round(b * 255) + ')';
}
