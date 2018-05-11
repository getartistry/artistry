var webFontLoaded = [];

function loadFonts(family) {
  WebFontConfig = {
    google: { families: [family+":400,700"] }
  };
  if ( webFontLoaded.indexOf(family) < 0 ) {
      WebFont.load(WebFontConfig);
      webFontLoaded.push(family);
  }
}
(function($) {
$(document).ready(function() { 
  $(".wpdreamsFont .wpdreamsfontselect").change(function() {
     var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
     $(weightNode).trigger('change');
     return; 
  });
  $(".wpdreamsFont .color").change(function() {
     var weightNode = $('.wpdreams-fontweight:checked', this.parentNode.parentNode)[0];
     $(weightNode).trigger('change');
     return;
  });
  $(".wpdreamsFont .wpdreams-fontsize").change(function() {
     var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
     $(weightNode).trigger('change');
     return;
  });
  $(".wpdreamsFont .wpdreams-lineheight").change(function() {
     var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
     $(weightNode).trigger('change');
  });
  $('.wpdreamsFont .wpdreams-fontweight').change(function() {
     var weight = "font-weight:"+jQuery(this).val()+";";
     var familyNode = $('.wpdreamsfontselect', this.parentNode)[0];
     var colorNode = $('.color', this.parentNode)[0];
     var sizeNode = $('.wpdreams-fontsize', this.parentNode)[0];
     var lhNode = $('.wpdreams-lineheight', this.parentNode)[0];
     
     var family = "font-family:"+jQuery(familyNode).val()+";";
     var color = "color:"+$(colorNode).val()+";";
     var size = "font-size:"+$(sizeNode).val()+";";
     var lh =  "line-height:"+$(lhNode).val()+";"; 

      if ( jQuery(familyNode).length > 0 && jQuery(familyNode).val() != null ) {
        var realFamilyName = jQuery(familyNode).val().replace('--g--', '');
        loadFonts(realFamilyName);
      } else {
          var realFamilyName = 'Open Sans';
      }
     $("label", this.parentNode).css("font-family", realFamilyName);
     $("label", this.parentNode).css("font-weight", $(this).val());
     $("label", this.parentNode).css("color", $(colorNode).val());
     $("input[isparam=1]", this.parentNode).val("font-weight:"+$(this).val()+";"+family+color+size+lh);
  });
  
  
  $(".wpdreamsFont>fieldset>.triggerer").click(function() {
      var parent = $(this).parent();
    
      var hidden = $('input[type=hidden]', parent);
      var val = hidden.val().replace(/(\r\n|\n|\r)/gm,"");
      var familyNode = $('.wpdreamsfontselect', parent)[0];
      var colorNode = $('.color', parent)[0];
      var sizeNode = $('.wpdreams-fontsize', parent)[0];
      var lhNode = $('.wpdreams-lineheight', this.parentNode)[0];
      
      $(familyNode).val(val.match(/family:(.*?);/)[1]);
      $(sizeNode).val(val.match(/size:(.*?);/)[1]); 
      $(colorNode).val(val.match(/color:(.*?);/)[1]);
      $(colorNode).spectrum('set', val.match(/color:(.*?);/)[1]);
      $(lhNode).val(val.match(/height:(.*?);/)[1]);   
  });
});  
}(jQuery));


(function($) {
    $(document).ready(function() {
        $(".wpdreamsFontComplete .wpdreamsfontcompleteselect").change(function() {
            var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
            $(weightNode).trigger('change');
            return;
        });
        $(".wpdreamsFontComplete .color").change(function() {
            var weightNode = $('.wpdreams-fontweight:checked', this.parentNode.parentNode)[0];
            $(weightNode).trigger('change');
            return;
        });
        $(".wpdreamsFontComplete .wpdreams-fontsize").change(function() {
            var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
            $(weightNode).trigger('change');
            return;
        });
        $(".wpdreamsFontComplete .wpdreams-lineheight").change(function() {
            var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
            $(weightNode).trigger('change');
        });
        $(".wpdreamsFontComplete .twodigit").change(function() {
            var weightNode = $('.wpdreams-fontweight:checked', this.parentNode)[0];
            $(weightNode).trigger('change');
        });
        $('.wpdreamsFontComplete .wpdreams-fontweight').change(function() {
            var weight = "font-weight:"+jQuery(this).val()+";";
            var familyNode = $('.wpdreamsfontcompleteselect', this.parentNode)[0];
            var colorNode = $('input[name*="fontColor"]', this.parentNode)[0];
            var sizeNode = $('.wpdreams-fontsize', this.parentNode)[0];
            var lhNode = $('.wpdreams-lineheight', this.parentNode)[0];
            var hlength = $('input[wpddata="hlength"]', this.parentNode)[0];
            var vlength = $('input[wpddata="vlength"]', this.parentNode)[0];
            var blurradius = $('input[wpddata="blurradius"]', this.parentNode)[0];
            var tsColor = $('input[name*="tsColor"]', this.parentNode)[0];

            var family = "font-family:"+jQuery(familyNode).val()+";";
            var color = "color:"+$(colorNode).val()+";";
            var size = "font-size:"+$(sizeNode).val()+";";
            var lh =  "line-height:"+$(lhNode).val()+";";
            var tShadow = $(hlength).val() + "px " + $(vlength).val() + "px " + $(blurradius).val() + "px " + $(tsColor).val();
            var textShadow = "text-shadow:" + tShadow + ";";

            if ( jQuery(familyNode).length > 0 && jQuery(familyNode).val() != null ) {
                var realFamilyName = jQuery(familyNode).val().replace('--g--', '');
                loadFonts(realFamilyName);
            } else {
                var realFamilyName = 'Open Sans';
            }
            $("label[wpddata='testText']", this.parentNode).css({
                "font-family" : realFamilyName,
                "font-weight" : $(this).val(),
                "color" : $(colorNode).val(),
                "text-shadow" : tShadow
            });
            $("input[isparam=1]", this.parentNode).val("font-weight:"+$(this).val()+";"+family+color+size+lh+textShadow);
            $("input[isparam=1]", this.parentNode).change();
        });


        $(".wpdreamsFontComplete>fieldset>.triggerer").click(function() {
            var parent = $(this).parent();

            var param = $("input[isparam=1]", parent);
            var val = param.val().replace(/(\r\n|\n|\r)/gm,"");
            jQuery('input[type="radio"]').filter('[value="normal"]')
            var $weightNodes = $('.wpdreams-fontweight', parent);
            var familyNode = $('.wpdreamsfontcompleteselect', parent)[0];
            var colorNode = $('input[name*="fontColor"]', parent)[0];
            var sizeNode = $('.wpdreams-fontsize', parent)[0];
            var lhNode = $('.wpdreams-lineheight', parent)[0];
            var hlength = $('input[wpddata="hlength"]', parent)[0];
            var vlength = $('input[wpddata="vlength"]', parent)[0];
            var blurradius = $('input[wpddata="blurradius"]', parent)[0];
            var tsColor = $('input[name*="tsColor"]', parent)[0];

            $weightNodes.filter('[value="' + val.match(/font-weight:(.*?);/)[1] + '"]').attr("checked", "checked");
            $(familyNode).val(val.match(/family:(.*?);/)[1]);
            $(sizeNode).val(val.match(/size:(.*?);/)[1]);
            $(colorNode).val(val.match(/color:(.*?);/)[1]);
            $(lhNode).val(val.match(/height:(.*?);/)[1]);
            var ts = val.match(/text-shadow:(.*?)px (.*?)px (.*?)px (.*?);/);
            if (ts != null && ts.length > 0) {
              $(hlength).val(ts[1]);
              $(vlength).val(ts[2]);
              $(blurradius).val(ts[3]);
              $(tsColor).val(ts[4]);
              $(tsColor).spectrum('set', ts[4]);
            }

            $(colorNode).spectrum('set', val.match(/color:(.*?);/)[1]);           
            $(familyNode).change();
        });
    });
}(jQuery));