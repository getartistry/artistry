jQuery(function ($) {

    //TinyMCE Button
    var image_url = '';
    tinymce.create('tinymce.plugins.YITH_WooCommerce_Quick_Checkout_Digital_Goods', {
        init : function(ed, url) {
            ed.addButton('ywqcdg_shortcode', {
                title : 'Add Shortcode',
                image : url+'/../images/icon_shortcode.png',
                onclick : function() {
                    $('#ywqcdg_shortcode').click();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo      : function () {
            return {
                longname : 'YITH WooCommerce Quick Checkout for Digital Goods',
                author   : 'YITHEMES',
                authorurl: 'http://yithemes.com/',
                infourl  : 'http://yithemes.com/',
                version  : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('ywqcdg_shortcode', tinymce.plugins.YITH_WooCommerce_Quick_Checkout_Digital_Goods);

});
