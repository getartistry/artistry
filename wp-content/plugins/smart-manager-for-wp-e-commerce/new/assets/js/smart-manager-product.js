jQuery(function($) {

	$(document).ready(function() {

		var attr_col_val = '',
			attribute_names = new Array(),
			attribute_visibility = new Array(),
			attribute_variation = new Array(),
			attribute_position = new Array(),
			attribute_display_text = new Array(),
			grid_rowid = 0,
			index = 0;

		//Code to add the show variations checkbox
		$(document).on('sm_jqgrid_titlebar_load',function(e){

			if (sm.dashboard_key != 'product') return;

			var show_variations_checked = '';

			if (sm.dashboard_model[sm.dashboard_key].hasOwnProperty('treegrid') && sm.dashboard_model[sm.dashboard_key].treegrid === true) {
				show_variations_checked = 'checked';
			}

			$("#sm_top_bar_left").append("<label id='sm_products_show_variations_span' style='font-weight:400 !important;vertical-align: -webkit-baseline-middle;'> <input type='checkbox' name='sm_products_show_variations' id='sm_products_show_variations' value='sm_products_show_variations' style='margin-left:5px;margin-right:0px;' "+ show_variations_checked +"> Show Variations </label>");

			$('#sm_products_show_variations').on('change',function() {

				// get_dashboard_model();

				if ($('#sm_products_show_variations').is(":checked")) {
					sm.dashboard_model[sm.dashboard_key].tables.posts.where.post_type = ['product', 'product_variation'];
					sm.dashboard_model[sm.dashboard_key].treegrid = true;
				} else {
					sm.dashboard_model[sm.dashboard_key].tables.posts.where.post_type = 'product';
					sm.dashboard_model[sm.dashboard_key].treegrid = false;
				}

				sm.state_apply = true;
				load_dashboard();
			});

		});

		$(document).on('sm_add_row',function(e,id){

			if (typeof(id) == 'undefined') {
				return;
			}

			$("#"+id).find('[aria-describedby="sm_editor_grid_terms_product_type"]').html('simple');
			$("#"+id).find('[aria-describedby="sm_editor_grid_terms_product_type"]').addClass('sm-jqgrid-dirty-cell').addClass('dirty-cell');
		});

		var grid = '#sm_editor_grid';
		var getColumnIndexByName = function(gr,columnName) {
			var cm = $(gr).jqGrid('getGridParam','colModel');
			for (var i=0,l=cm.length; i<l; i++) {
			    if (cm[i].name===columnName) {
			        return i;
			    }
			}
			return -1;
		};



		// Code for disabling the fields on show_variations
		$(document).on('sm_grid_complete',function(e){
			
			if ( sm.dashboard_key != 'product' ) return;

			var allIds = $('#sm_editor_grid').jqGrid('getDataIDs');
            editor_grid = $('#sm_editor_grid');

            var pos=getColumnIndexByName(grid,'posts_post_title');
			var allIds = $('#sm_editor_grid').jqGrid('getDataIDs');
			var cells = $("tbody > tr.jqgrow > td:nth-child("+(pos+1)+")",editor_grid[0]);

            for (var i = 0; i < allIds.length; i++) {

            	var cell = $(cells[i]);
                var rowData = $('#sm_editor_grid').jqGrid('getRowData', allIds[i]);

                if ( rowData.hasOwnProperty('posts_post_parent') && rowData.posts_post_parent > 0 ) {
	                cell.addClass('not-editable-cell');
	            }

            }
		});

		$(document).on('sm_on_cell_click',function(e,rowid, celname, value, iRow, iCol){

			if (celname != 'custom_product_attributes' || sm.dashboard_key != 'product') return;

			var columns = sm.dashboard_model.product.columns,
				attribute_taxonomy_list = '<option value="custom">Custom product attribute</option>',
				selected_attribute_list = '',
				attribute_edit_html = '',
				attribute_taxonomy_obj = '',
				rowData = $('#sm_editor_grid').jqGrid('getRowData', rowid),
				product_attributes_postmeta = '',
				is_variation = 0;

			grid_rowid = rowid;
			index = 0;

			//Code for parsing the 'product_attributes'
			if (rowData.hasOwnProperty('postmeta_meta_key__product_attributes_meta_value__product_attributes')) {
				product_attributes_postmeta = rowData.postmeta_meta_key__product_attributes_meta_value__product_attributes;

				if (product_attributes_postmeta != '') {
					product_attributes_postmeta = $.jgrid.parse(product_attributes_postmeta);
				}
			}

			//Code for setting is_variation flag
			if (rowData.hasOwnProperty('terms_product_type')) {
				if (rowData.terms_product_type == 'variable' || rowData.terms_product_type == 'Variable Subscription') {
					is_variation = 1;
				}
			}

			for (var i in columns) {
				if (columns[i].src == 'custom/product_attributes') {
					attr_col_val = columns[i].values;

					for (var val in attr_col_val) {
						attribute_taxonomy_list += '<option value="'+val+'">'+attr_col_val[val]['lbl']+'</option>';

						attribute_display_text [val] = attr_col_val[val]['lbl'];
					}
				}
			}
		
			if (product_attributes_postmeta != '') {

				for (var attr in product_attributes_postmeta) {

					var is_taxonomy = product_attributes_postmeta[attr].is_taxonomy;
					//Code for defined attributes
					if (is_taxonomy == 1) {
						attr_lbl = attr_col_val[attr].lbl;
						attr_type = attr_col_val[attr].type;
						attr_val = attr_col_val[attr].val;

						if (attr_type == 'text') {

							var attr_val_temp = '';
							attr_val = product_attributes_postmeta[attr].value;

							for (var val in attr_val) {
								attr_val_temp += attr_val[val].trim() + ' | ';
							}
							attr_val = attr_val_temp.substr(0,(attr_val_temp.length-3));
						}

					} else if (is_taxonomy == 0) {
						attr_lbl = product_attributes_postmeta[attr].name;
						attr_type = 'text';
						attr_val = product_attributes_postmeta[attr].value;
					}

					var attribute_visibility_flag = '',
						attribute_variation_flag = '',
						attribute_position = '';

					if (product_attributes_postmeta[attr].hasOwnProperty('is_visible')) {
						if (product_attributes_postmeta[attr].is_visible == 1) {
							attribute_visibility_flag = 'checked';
						}
					}

					if (product_attributes_postmeta[attr].hasOwnProperty('is_variation')) {
						if (product_attributes_postmeta[attr].is_variation == 1) {
							attribute_variation_flag = 'checked';
						}
					}

					if (product_attributes_postmeta[attr].hasOwnProperty('position')) {
						attribute_position = product_attributes_postmeta[attr].position;
					}

					var attribute_chkbox_list = '';

					attribute_chkbox_list += '<tr> <td> <input type="checkbox" id="attribute_visibility_'+attr+'" name="attribute_visibility['+index+']" '+attribute_visibility_flag+'> Visible on the product page </td> </tr>';
					
					if (is_variation == 1) {
						attribute_chkbox_list += '<tr> <td> <input type="checkbox" id="attribute_variation_'+attr+'" name="attribute_variation['+index+']" '+attribute_variation_flag+'> Used for variations </td> </tr>';
					}
					attribute_chkbox_list += '<tr> <td> <label>Position: </label> <input type="number" style="width:23% !important;" name="attribute_position['+index+']" value="'+attribute_position+'">';
					attribute_chkbox_list += '<input type="hidden" name="attribute_taxonomy['+index+']" value='+is_taxonomy+'> </td> </tr>';

					if (is_taxonomy == 1) {
						selected_attribute_list += '<tr> <td> <label style="font-weight: bold;"> '+attr_lbl+': </label> </td>';

						if(attr_type == "select") {
							selected_attribute_list += '<td rowspan="4"> <select id="'+attr+'" multiple="multiple" data-placeholder="Select terms" name="attribute_values['+index+'][]" class="multiselect">';
							
							attr_current = product_attributes_postmeta[attr].value;

							for (var j in attr_val) {
								if (attr_current.hasOwnProperty(j)) {
									selected_attribute_list += '<option value="'+j+'" selected>'+ attr_val[j] +'</option>';
								} else {
									selected_attribute_list += '<option value="'+j+'">'+ attr_val[j] +'</option>';
								}
							}

							selected_attribute_list += '</select> <br />';
							selected_attribute_list += '<button class="button select_all_attributes">Select all</button> ';
							selected_attribute_list += '<button class="button select_no_attributes">Select none</button> </td>';
							
						} else if(attr_type == "text") {
							selected_attribute_list += '<td rowspan="4"> <input type="text" id="'+attr_lbl+'" name="attribute_values['+index+']" value="'+attr_val+'" placeholder="Pipe (|) separate terms" /> </ td>';
						}
						// selected_attribute_list += attribute_chkbox_list + ' </td>';
						selected_attribute_list += '<td> <input type="hidden" name="attribute_names['+index+']" value="'+attr+'" /></td>';

					} else if (is_taxonomy == 0) {
						selected_attribute_list += '<tr> <td> <input type="text" name="attribute_names['+index+']" placeholder="Name" value="'+attr_lbl+'"> </td>';
						selected_attribute_list += '<td rowspan="4"> <input type="text" id="'+attr_lbl+'" name="attribute_values['+index+']" value="'+attr_val+'" placeholder="Pipe (|) separate terms" /> </td>';
					}
					
					selected_attribute_list += '</tr>';
					selected_attribute_list += attribute_chkbox_list;
					index++;
				}
			}
			

			attribute_edit_html += '<div id="edit_product_attributes" style="width:100% !important">'+
									'<table id= "table_edit_attributes" width="102%">'+
									selected_attribute_list +
									'</table>'+
									'<span id="edit_attributes_toolbar">'+
									'<button type="button" class= "button button-primary" id="edit_attributes_ok">OK</button>'+
									'<button type="button" class= "button button-primary" id="edit_attributes_add" style="float:right;">Add</button>'+
									'<select id="edit_attributes_taxonomy_list" style="float:right;">'+attribute_taxonomy_list+'</select>'+
									'</span>'+
									'</div>';

			//Code for creating the edit attributes dialog
			inline_edit_dlg(attribute_edit_html, 'Edit Attributes', 420);

			$("select.multiselect").chosen();
			$(".chosen-container-multi").css({'width': '120% !important', 'margin-bottom': '7px'});

			$("#edit_attributes_add").on('click',function(){
				var taxonomy_selected = $("#edit_attributes_taxonomy_list").val(),
					new_attribute = '',
					attr_type = 'text',
					attr_val = '',
					is_taxonomy = 0;

				//Code to reset the taxonomy list
				$('#edit_attributes_taxonomy_list').find('option[value="custom"]').prop('selected', true);

				if (taxonomy_selected !== "custom") {
					attr_type = attr_col_val[taxonomy_selected].type;
					attr_val = attr_col_val[taxonomy_selected].val;
					is_taxonomy = 1;
				}

				var attribute_chkbox_list = '';

				attribute_chkbox_list += '<tr> <td> <input type="checkbox" id="attribute_visibility_'+attr+'" name="attribute_visibility['+index+']" '+attribute_visibility_flag+'> Visible on the product page </td> </tr>';
				
				if (is_variation == 1) {
					attribute_chkbox_list += '<tr> <td> <input type="checkbox" id="attribute_variation_'+attr+'" name="attribute_variation['+index+']" '+attribute_variation_flag+'> Used for variations </td> </tr>';
				}
				attribute_chkbox_list += '<tr> <td> <label>Position: </label> <input type="number" style="width:23% !important;" name="attribute_position['+index+']" value="'+attribute_position+'">';
				attribute_chkbox_list += '<input type="hidden" name="attribute_taxonomy['+index+']" value="'+is_taxonomy+'"> </td> </tr>';

				if (is_taxonomy == 1) {

					new_attribute += '<tr> <td> <label style="font-weight: bold;">'+attr_col_val[taxonomy_selected].lbl+':</label> </td>';

					if(attr_type == "select") {
						new_attribute += '<td rowspan="4"> <select multiple="multiple" data-placeholder="Select terms" name="attribute_values['+index+'][]" class="multiselect" style="width:100% !important">';

						for (var j in attr_val) {
							new_attribute += '<option value="'+j+'">'+ attr_val[j] +'</option>';
						}
						new_attribute += '</select> <br />';
						new_attribute += '<button class="button select_all_attributes">Select all</button> ';
						new_attribute += '<button class="button select_no_attributes">Select none</button> </td>';
				
						
					} else if(attr_type == "text") {
						new_attribute += '<td rowspan="4"> <input type="text" name="attribute_values['+index+']" value="" placeholder="Pipe (|) separate terms" /> </td>';
					}

					// new_attribute += attribute_chkbox_list+'</td>';
					new_attribute += '<td> <input type="hidden" name="attribute_names['+index+']" value="'+ taxonomy_selected +'"/></td>';

				} else if (is_taxonomy == 0) {
					new_attribute += '<tr> <td> <input type="text" name="attribute_names['+index+']" placeholder="Name"> </td>';
					new_attribute += '<td rowspan="4"> <input type="text" name="attribute_values['+index+']" value="" placeholder="Pipe (|) separate terms" /> </td>';
				}
				
				new_attribute += '</tr>';
				new_attribute += attribute_chkbox_list;

				$('#table_edit_attributes').append(new_attribute);
				$("select.multiselect").chosen();

				index++;
			});

			//Code for select all and none attributes
			$('#edit_product_attributes').on('click', 'button.select_all_attributes', function(){
				$(this).closest('td').find('select option').attr("selected","selected");
				$(this).closest('td').find('select').trigger("chosen:updated");
				return false;
			});

			$('#edit_product_attributes').on('click', 'button.select_no_attributes', function(){
				$(this).closest('td').find('select option').removeAttr("selected");
				$(this).closest('td').find('select').trigger("chosen:updated");
				return false;
			});

			//Code for click event of 'ok' btn
			$("#edit_attributes_ok").on('click',function(){

				// var attributes_edited_val = new Array(),
					var attributes_edited_text = '',
						columns = sm.dashboard_model.product.columns,
						product_attributes_postmeta = {};

					for (var i in columns) {
						if (columns[i].src == 'custom/product_attributes') {
							attr_col_val = columns[i].values;
						}
					}

				while (index > 0) {

					index--;
					attr_nm = $( "input[name='attribute_names["+index+"]']" ).val();
					is_taxonomy = $( "input[name='attribute_taxonomy["+index+"]']" ).val();

					var edited_value = '';

					if( attributes_edited_text.length > 0 ) {
						attributes_edited_text += ', <br>';
					}

					if ($( "input[name='attribute_values["+index+"]']" ).attr('type') !== undefined && $( "input[name='attribute_values["+index+"]']" ).attr('type') == "text") {
						edited_value = $( "input[name='attribute_values["+index+"]']" ).val();

						// attributes_edited_val [attr_nm] = curr_attr_val;
						// selected_text = edited_value.replace(/(\|)/g,',');

						if (edited_value == '') continue;

						edited_text = edited_value.split("|");
						for (var i in edited_text) {
							edited_text[i] = edited_text[i].trim();
						}

						edited_value = edited_text.join(" | ");

						if (is_taxonomy == 1) {
							attributes_edited_text += attribute_display_text [attr_nm] + ': [' + edited_value + ']';
							edited_value = edited_text;
						} else if (is_taxonomy == 0) {
							attributes_edited_text += attr_nm + ': [' + edited_value + ']';
							attr_nm = attr_nm.replace(/( )/g,"-").replace(/([^a-z A-Z 0-9][^\w\s])/gi,'').toLowerCase();
						}

					} else {
						// attributes_edited_val [attr_nm] = $("#"+attr_nm).val();

						selected_text = $( "select[name='attribute_values["+index+"][]'] option:selected" ).map(function () {
										return $(this).text();
								    }).get().join(' | ');

						if (selected_text == '') continue;

						selected_val = $( "select[name='attribute_values["+index+"][]'] option:selected" ).map(function () {
										return $(this).val();
								    }).get();

						attr_col_val_og = attr_col_val[attr_nm].val;
						edited_value = {};

						for (var i in selected_val) {
							edited_value[selected_val[i]] = attr_col_val_og[selected_val[i]];
						}

						attributes_edited_text += attribute_display_text [attr_nm] + ': [' + selected_text + ']';
					}

					product_attributes_postmeta [attr_nm] = {};
					product_attributes_postmeta [attr_nm]['name'] = $( "input[name='attribute_names["+index+"]']" ).val();
					product_attributes_postmeta [attr_nm]['value'] = edited_value;
					product_attributes_postmeta [attr_nm]['position'] = $( "input[name='attribute_position["+index+"]']" ).val();

					if ($( "input[name='attribute_visibility["+index+"]']" ).is(":checked")) {
						product_attributes_postmeta [attr_nm]['is_visible'] = 1;
					} else {
						product_attributes_postmeta [attr_nm]['is_visible'] = 0;
					}

					if ($( "input[name='attribute_variation["+index+"]']" ).is(":checked")) {
						product_attributes_postmeta [attr_nm]['is_variation'] = 1;
					} else {
						product_attributes_postmeta [attr_nm]['is_variation'] = 0;
					}
					product_attributes_postmeta [attr_nm]['is_taxonomy'] = is_taxonomy;

				}

				var rowData = $('#sm_editor_grid').jqGrid('getRowData', grid_rowid);

				rowData.custom_product_attributes = attributes_edited_text;
				rowData.postmeta_meta_key__product_attributes_meta_value__product_attributes = JSON.stringify(product_attributes_postmeta);

				$('#sm_editor_grid').jqGrid('smsetCell',grid_rowid, 'custom_product_attributes', '', 'sm-jqgrid-dirty-cell', false, true, true);
				$('#sm_editor_grid').jqGrid('smsetCell',grid_rowid, 'postmeta_meta_key__product_attributes_meta_value__product_attributes', '', 'sm-jqgrid-dirty-cell', false, true, true);
				$('#sm_editor_grid').jqGrid('setRowData', grid_rowid, rowData);

				hideDialog();

			});
		});

		//Code for handling cell click for category [i.e. multiselect]
		$(window).on('oncell_multiselect_click',function(e, multiselect_chkbox_list1){
		});

	});
});