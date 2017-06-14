(function() {
    tinymce.PluginManager.add('agilecrm_button', function( editor, url ) {
        editor.addButton( 'agilecrm_button', {
            text: 'Agile Forms',
            icon: false,
            onclick: function() {
            jQuery.ajax({
                url: "?agile_list_form=1",
            }).done(function(data ) {

                var re = /(?![\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})./g;
                data = data.replace(re, "")
                var obj = JSON.parse(data);
                if(data == "[]"){
                    alert("Sorry, you don't have any forms.");
                }else{
                editor.windowManager.open( {
                    autoScroll: true,
                    width: 360,
                    height: 130,
                    classes: 'myAwesomeClass-panel',
                    title: 'Agile Forms',
                    body: [{
                    classes:'mce-arrow-up', 
                    type: 'listbox', 
                    name: 'form', 
                    label: 'Forms',
                    
                    'values': obj 
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '[agileform id="' + e.data.form+ '"]');
                    }
                });
                }
            });
            }
        });
    });
     ( function($) {
                     $('.mce-label').css('margin-top',130); 
    } ) ( jQuery );
})();

