(function($) {

	// Fields prototype constructor
	CPFields = function(item) {
		var arr = [];
		$(item).each(function(key, element){
			var json = $(element).attr('data-json');
			if( typeof json != 'undefined' && json != '' ) {
				element.CPField = $.parseJSON(json);
			}
			arr.push(element);
		});
		this._items = arr;
	}	

	// Fields prototype to get all fields
	CPFields.prototype = {
		getCPFields : function () { // will provide all fields with respect to element
			return this._items;
		},
		getSimplyfyCPFields : function() { // get only fields data
			var fields = {};
			$.each(this._items, function(i, item){
				fields[i] = item.CPField;
			});
			return fields;
		},
		getCPFieldByID : function(id) {
			var temp;
			$.each( this._items, function(i, item ){
				if( 'undefined' !== typeof item.CPField && 'undefined' !== typeof item.CPField['id'] ) {
					if(item.CPField['id'] == id) {
						temp = item.CPField;
						return;
					}
				}
			});
			return temp;
		},
		getMapStyle : function(id, param_id) {
			var params = map_style = {};
			$.each(this._items, function(i, item){
				if( 'undefined' !== typeof item.CPField && 'undefined' !== typeof item.CPField['id'] ) {
					if( item.CPField['id'] == id ) {
						var sections = item.CPField.sections;
						$.each( sections, function(index, val) {
							params[index] = val.params;	 
						});
						return;
					}
				}
			});

			$.each(params, function(i, param){				
				$.each( param, function(i, val) {
					if( val.name == param_id ) {
						map_style = val.map_style;
					}
				});
			});

			return map_style;
		},
		getMapValue : function(id, param_id) {
			var params = map = {};
			
			$.each(this._items, function(i, item){
				if( 'undefined' !== typeof item.CPField && 'undefined' !== typeof item.CPField['id'] ) {
					if( item.CPField['id'] == id ) {

						var sections = item.CPField.sections;

						$.each( sections, function(index, val) {

							params[index] = val.params;	 
						});					
						return;
					}
				}
			});

			if( params.length != 0 ) {
				$.each(params, function(i, param){					
					$.each(param, function(i, val){
						if( val.name == param_id ) {
							map = val.map;
						}
					});
				});
			}

			return map;
		}
	};

})(jQuery);