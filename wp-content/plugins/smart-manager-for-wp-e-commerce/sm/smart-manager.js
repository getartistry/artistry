// Floating notification start
Ext.notification = function(){
    var msgCt;
    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }
    return {
        msg : function(title, format){
            try{
	        	if(!msgCt){
	                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
	            }
	            msgCt.alignTo(document, 't-t');
	            Ext.DomHelper.applyStyles(msgCt, 'left: 33%; top: 30px;');
	            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
	            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
	            m.slideIn('t').pause(2).ghost("t", {remove:true});
            }catch(e){
				return;
			}
        },

        init : function(){
            var lb = Ext.get('lib-bar');
            if(lb){
                lb.show();
            }
        }
    };
}();// Floating notification end


// global Variables and array declaration.
var	categories         = new Array(), //an array for category combobox in batchupdate window.
	dimensionUnits     = new Array(), //an array for dimension units combobox in batchupdate window.
	cellClicked        = false,  	  //flag to check if any cell is clicked in the editor grid.
	search_timeout_id  = 0, 		  //timeout for sending request while searching.
	colModelTimeoutId  = 0, 		  //timeout to reconfigure the grid.
	editorGrid         = '',
	showOrdersView     = '',
	showCustomersView  = '',
	weightUnitStore    = '',
	countriesStore     = '',
	regionsStore       = '',
	reloadRegionCombo  = '';

Ext.onReady(function () {
		
	var now 		      = new Date();
	var lastMonDate       = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate() + 1);
	var search_timeout_id = 0; 			//timeout for sending request while searching.
	var updated	  		  = parseInt( updated_data );
	var dateFormat        = 'M d Y';
	var limit 		   	  = parseInt( sm_record_limit );
	var dup_limit 	  	  = parseInt( sm_dup_limit );		  //duplicate products limit.
	var batch_limit 	  = parseInt( sm_batch_limit );		  //batch products limit.
	
	try{
		//Stateful
//		Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
//			expires: new Date(new Date().getTime()+(1000*60*60*24*30)) //30 days from now
//		}));
		
	// Tooltips
	Ext.QuickTips.init();
	Ext.apply(Ext.QuickTips.getQuickTip(), {
		maxWidth: 150,
		minWidth: 100,
		dismissDelay: 9999999,
		trackMouse: true
	});

	// Global object SM....declared in manager-console.php
	SM.searchTextField   = '';
	SM.dashboardComboBox = '';
	SM.duplicateButton   = '';
	SM.colModelTimeoutId = '';		
	SM.activeModule      = 'Products'; //default module selected.
	SM.activeRecord      = '';
	SM.curDataIndex      = '';
	SM.incVariation      = false;
	SM.typeColIndex 	 = '';
    SM.products_state = "";
    SM.customers_state = "";
    SM.orders_state = "";
    SM.dashboard_state = "";
    SM.variation_state = "";
    SM.search_type = "";
    SM.editor_state = "";
    SM.advanced_search_query = new Array();
	
	var actions            = new Array(); //an array for actions combobox in batchupdate window.
	
	//creating an array of actions to be used in the actions combobox in batch update window.
	actions['blob']   = [{'id': 0,'name': getText('set to'), 'value':'SET_TO'},
					     {'id': 1,'name': getText('append'),'value': 'APPEND'},
					     {'id': 2,'name': getText('prepend'),'value': 'PREPEND'}];

	actions['bigint'] = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'}];

	actions['real']   = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
					     {'id': 1,'name': getText('increase by %'),'value': 'INCREASE_BY_PER'},
					     {'id': 1,'name': getText('decrease by %'),'value': 'DECREASE_BY_PER'},
					     {'id': 2,'name': getText('increase by number'),'value': 'INCREASE_BY_NUMBER'},
					     {'id': 3,'name': getText('decrease by number'),'value': 'DECREASE_BY_NUMBER'}];

        actions['price_actions']   = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
					     {'id': 1,'name': getText('increase by %'),'value': 'INCREASE_BY_PER'},
					     {'id': 1,'name': getText('decrease by %'),'value': 'DECREASE_BY_PER'},
					     {'id': 2,'name': getText('increase by number'),'value': 'INCREASE_BY_NUMBER'},
					     {'id': 3,'name': getText('decrease by number'),'value': 'DECREASE_BY_NUMBER'},
                                             {'id': 4,'name': getText('set to sales price'),'value': 'SET_TO_SALES_PRICE'}];
                                         
        actions['salesprice_actions']   = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
					     {'id': 1,'name': getText('increase by %'),'value': 'INCREASE_BY_PER'},
					     {'id': 1,'name': getText('decrease by %'),'value': 'DECREASE_BY_PER'},
					     {'id': 2,'name': getText('increase by number'),'value': 'INCREASE_BY_NUMBER'},
					     {'id': 3,'name': getText('decrease by number'),'value': 'DECREASE_BY_NUMBER'},
                                             {'id': 4,'name': getText('set to regular price'),'value': 'SET_TO_REGULAR_PRICE'}];

	actions['int']    = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
					     {'id': 1,'name': getText('increase by number'),'value': 'INCREASE_BY_NUMBER'},
					     {'id': 2,'name': getText('decrease by number'),'value': 'DECREASE_BY_NUMBER'}];

	actions['float']  = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
				         {'id': 1,'name': getText('increase by number'),'value': 'INCREASE_BY_NUMBER'},
				         {'id': 2,'name': getText('decrease by number'),'value': 'DECREASE_BY_NUMBER'}];

	actions['string'] = [{'id': 0,'name': getText('Yes'),'value': 'YES'},
						 {'id': 1,'name': getText('No'),'value': 'NO'}];

	actions['category_actions'] = [{'id': 0,'name': getText('set to'),'value': 'SET_TO'},
								   {'id': 1,'name': getText('add to'),'value': 'ADD_TO'},
								   {'id': 2,'name': getText('remove from'),'value': 'REMOVE_FROM'}];

	actions['modStrActions']   = [[ 0, getText('set to'), 'SET_TO'],
	                              [ 1, getText('append'), 'APPEND'],
	                              [ 2, getText('prepend'), 'PREPEND']];

	actions['setStrActions']   = [[ 0,getText('set to'), 'SET_TO']];

	actions['setAdDelActions'] = [[0, getText('set to'), 'SET_TO'],
	                              [1, getText('add to'), 'ADD_TO'],
	                              [2, getText('remove from'), 'REMOVE_FROM']];

	actions['modIntPercentActions']   = [[0, getText('set to'), 'SET_TO'],
	                                     [1, getText('increase by %'), 'INCREASE_BY_PER'],
	                                     [2, getText('decrease by %'), 'DECREASE_BY_PER'],
	                                     [3, getText('increase by number'),'INCREASE_BY_NUMBER'],
	                                     [4, getText('decrease by number'), 'DECREASE_BY_NUMBER']];

	actions['price_actions']   =         [[0, getText('set to'), 'SET_TO'],
	                                     [1, getText('increase by %'), 'INCREASE_BY_PER'],
	                                     [2, getText('decrease by %'), 'DECREASE_BY_PER'],
	                                     [3, getText('increase by number'),'INCREASE_BY_NUMBER'],
	                                     [4, getText('decrease by number'), 'DECREASE_BY_NUMBER'],
                                             [5, getText('set to sales price'), 'SET_TO_SALES_PRICE']];

        actions['salesprice_actions']   =    [[0, getText('set to'), 'SET_TO'],
	                                     [1, getText('increase by %'), 'INCREASE_BY_PER'],
	                                     [2, getText('decrease by %'), 'DECREASE_BY_PER'],
	                                     [3, getText('increase by number'),'INCREASE_BY_NUMBER'],
	                                     [4, getText('decrease by number'), 'DECREASE_BY_NUMBER'],
                                             [5, getText('set to regular price'), 'SET_TO_REGULAR_PRICE']];
        
	actions['modIntActions']   		  = [[0, getText('set to'), 'SET_TO'],
	                              		 [1, getText('increase by number'),'INCREASE_BY_NUMBER'],
	                              		 [2, getText('decrease by number'), 'DECREASE_BY_NUMBER']];

	actions['YesNoActions']   		  = [[0,getText('Yes'),'YES'],
	                             		 [1,getText('No'),'NO']];

	actions['category_actions'] 	  = [[0, getText('set to'),'SET_TO'],
								   		 [1,getText('add to'),'ADD_TO'],
								   		 [2,getText('remove from'),'REMOVE_FROM']];

    dimensionUnits    = {'items': [{'id':0 , 'name':getText('inches'), 'value': 'in'},
                                    {'id':1 , 'name':getText('cm'), 'value': 'cm'},
                                    {'id':2 , 'name':getText('meter'), 'value': 'meter'}],
                         'totalCount': 3 };
	
	//fm used as a short form for Ext.form
	var fm 		     = Ext.form,
		toolbarCount =  1,
		cnt 		 = -1,    //for checkboxSelectionModel.
		cnt_array 	 = [];	 //for checkboxSelectionModel.
	
	//Regex to allow only numbers.
	var objRegExp = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;
	var regexError = getText('Only numbers are allowed');

	// Code for defining the renderer for dimensions field
	var dimensionsRenderer = '';

	if (sm_dimensions_decimal_precision != '') {

		var decimal_format='0,0';

		for(var i=0;i<sm_dimensions_decimal_precision;i++) {
			if (i == 0) {
				decimal_format += '.';				
			}

			decimal_format += '0';
		}

		dimensionsRenderer = Ext.util.Format.numberRenderer(decimal_format);
	}
		
	//number format in which the amounts in the grid will be displayed.
	var amountRenderer = Ext.util.Format.numberRenderer('0,0.00'),
		
		//setting Date fields.
		fromDateTxt    = new Ext.form.TextField({emptyText:'From Date',readOnly: true,width: 80, id:'fromDateTxtId'}),
		toDateTxt      = new Ext.form.TextField({emptyText:'To Date',readOnly: true,width: 80, id:'toDateTxtId'}),
		toComboSearchBox = new Ext.form.ComboBox({
		id: 'ComboSearch',
        mode: 'local',
		width : 100,
        store: new Ext.data.ArrayStore({
            autoDestroy: true,
			forceSelection: true,
            fields: ['value','name'],
            data: [
                   ['TODAY',      'Today'],
					['YESTERDAY',  'Yesterday'],
					['THIS_WEEK',  'This Week'],
					['LAST_WEEK',  'Last Week'],
					['THIS_MONTH', 'This Month'],
					['LAST_MONTH', 'Last Month'],
					['3_MONTHS',   '3 Months'],
					['6_MONTHS',   '6 Months'],
					['THIS_YEAR',  'This Year'],
					['LAST_YEAR',  'Last Year']
                  ]
        }),
        
		displayField: 'name',
		valueField: 'value',
		triggerAction: 'all',
		editable: false,
		value: 'Select Date',
		style: {
			fontSize: '14px',
			paddingLeft: '2px'
		},
		forceSelection: true,
		listeners: {
		select: function () {
				var dateValue = this.value;
				
				if(fileExists == 0){
					
						Ext.notification.msg('Smart Manager',"Available only in Pro version" );
					}
				else{
					proSelectDate(dateValue);
					searchLogic();
				}
				
			}

	}
});


	proSelectDate = function (dateValue){
		
	var fromDate,toDate;
	var	now = new Date();


	switch (dateValue){

		case 'TODAY':
		fromDate = now;
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case 'YESTERDAY':
		fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
		toDate = toDate.format('M j Y');
		break;

		case 'THIS_WEEK':
		fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1));
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case 'LAST_WEEK':
		fromDate = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 7));
		fromDate = fromDate.format('M j Y');
		toDate   = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 1));
		toDate = toDate.format('M j Y');
		break;

		case 'LAST_SEVEN_DAYS':
		fromDate = SM.checkFromDate;
		fromDate = fromDate.format('M j Y');
		toDate   = SM.checkToDate;
		toDate = toDate.format('M j Y');
		break;

		case 'THIS_MONTH':
		fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case 'LAST_MONTH':
		fromDate = new Date(now.getFullYear(), now.getMonth()-1, 1);
		fromDate = fromDate.format('M j Y');
		toDate   = new Date(now.getFullYear(), now.getMonth(), 0);
		toDate = toDate.format('M j Y');
		break;

		case '3_MONTHS':
		fromDate = new Date(now.getFullYear(), now.getMonth()-2, 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case '6_MONTHS':
		fromDate = new Date(now.getFullYear(), now.getMonth()-5, 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case 'THIS_YEAR':
		fromDate = new Date(now.getFullYear(), 0, 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		case 'LAST_YEAR':
		fromDate = new Date(now.getFullYear() - 1, 0, 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = new Date(now.getFullYear(), 0, 0);
		toDate = toDate.format('M j Y');
		break;

		case 'LAST_SEVEN_DAYS':
		fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 6);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;

		default:
		fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
		fromDate = fromDate.format('M j Y');
		toDate 	 = now;
		toDate = toDate.format('M j Y');
		break;
	}

	fromDateTxt.setValue(fromDate);
	toDateTxt.setValue(toDate);
	
};
	SM.checkFromDate = new Date( now.getFullYear(), now.getMonth(), ( now.getDate() - 29 ) ); //for exactly 30 days limit
	SM.checkFromDate = SM.checkFromDate.format('M j Y');
	SM.checkToDate   = now;
	SM.checkToDate = SM.checkToDate.format('M j Y');


		now            = new Date(),
		initDate       = new Date(0),
		lastMonDate    = new Date(now.getFullYear(), now.getMonth()-1, now.getDate()+1);
	
	fromDateTxt.setValue(lastMonDate.format('M j Y'));
	toDateTxt.setValue(now.format('M j Y'));
	
	//CheckBoxes for EditorGrid Panel for selecting rows.
	var editorGridSelectionModel = new Ext.grid.CheckboxSelectionModel({
		checkOnly: true,
		listeners: {
			selectionchange: function (sm) {
				if (sm.getCount()) {					
					if(fileExists == 1) {
						pagingToolbar.batchButton.enable();
                    	// editorGrid.getTopToolbar().get('duplicateButton').enable();	
                    	if(pagingToolbar.hasOwnProperty('printButton'))
							pagingToolbar.printButton.enable();
					}	
                    
					if(pagingToolbar.hasOwnProperty('duplicateButton'))
					pagingToolbar.duplicateButton.enable();

					if(pagingToolbar.hasOwnProperty('deleteButton'))
					pagingToolbar.deleteButton.enable();
					
				} else {					
					pagingToolbar.batchButton.disable();
					
                    // editorGrid.getTopToolbar().get('duplicateButton').disable();
                       
                    if(pagingToolbar.hasOwnProperty('duplicateButton'))
					pagingToolbar.duplicateButton.disable();

					if(pagingToolbar.hasOwnProperty('deleteButton'))
					pagingToolbar.deleteButton.disable();
					
					if(pagingToolbar.hasOwnProperty('printButton'))
					pagingToolbar.printButton.disable();
				}
			}
		}
	});

        var refresh_state = false; // flag to handle the refreshing of the grid
        var products_hidden_state = false; // flag to handle the hiddenchange event of the products column module
        var hidden_change = false; // flag to manage the state apply when a column is unhidden for all the modules

        var state_apply = false;// flag to handle the firing of the state apply ajax request
        
        //Function to handle the state apply at regular intervals
        function state_update() {
            
            if (state_apply === true) {
                
                var editor_current_state = editorGrid.getState();
                if (SM.dashboard_state == "Products") {
                    SM.products_state = editor_current_state;
                }
                else if (SM.dashboard_state == "Customers") {
                    SM.customers_state = editor_current_state;
                }
                else if (SM.dashboard_state == "Orders") {
                    SM.orders_state = editor_current_state;
                }

                jQuery.ajax({
                    type : 'POST',
                    // url : jsonURL,
                    url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
                    dataType:"text",
                    async: false,
                    data: {
                                cmd: 'state',
                                op : 'set',
                                dashboardcombobox : SM.dashboard_state,
                                incVariation : SM.variation_state,
                                Products : Ext.encode(SM.products_state),
                                Customers : Ext.encode(SM.customers_state),
                                Orders : Ext.encode(SM.orders_state),
                                search_type: jQuery("#search_switch").text().trim(),
                                security: SM_NONCE,
                                file:  jsonURL
                    },
                    success: function(data) {
                        state_apply = false;
                    }
                });
            }
            
            
        }
        

        //Function to get all the states on load
        jQuery(document).ready(function()
        {
          jQuery.ajax({
                type : 'POST',
                // url : jsonURL,
                url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
                dataType:"text",
                async: false,
                data: {
                            cmd: 'state',
                            op : 'get',
                            security: SM_NONCE,
                            file:  jsonURL
                },
                success: function(response) {
                	var myJsonObj    = Ext.decode(response);

                    SM.products_state = Ext.decode(myJsonObj['Products']);
                    SM.customers_state = Ext.decode(myJsonObj['Customers']);
                    SM.orders_state = Ext.decode(myJsonObj['Orders']);
                    SM.dashboard_state = myJsonObj['dashboardcombobox'];
                    SM.variation_state = myJsonObj['incVariation'];
                    SM.search_type = myJsonObj['search_type'];
                                        
                    if(SM.dashboard_state === "" || SM.dashboard_state === null) {
                        SM.dashboard_state = 'Products';
                    }
                    if(SM.variation_state === "" || SM.variation_state === null) {
                        SM.variation_state = false;
                    }
                    if(SM.search_type === "" || SM.search_type === null) {
                        SM.search_type = "Advanced Search";
                    }
                }
            });

	        var current_url = document.URL;

		    if ( !jQuery(document.body).hasClass('folded') && current_url.indexOf("page=smart-manager") != -1 ) {
		        jQuery(document.body).addClass('folded');
		    }

		    jQuery('#collapse-menu').live('click', function() {

		        var current_url = document.URL;

		        if ( !jQuery(document.body).hasClass('folded') && current_url.indexOf("page=smart-manager") != -1 ) {
		            jQuery(document.body).addClass('folded');
		        }
		    });

        });
        
        //Function to set all the states on unload
        window.onbeforeunload = function (evt) {  
            state_apply = true;
            state_update();
        }
	
	//Function to escape white space characters	in customJsonReader
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	}
	String.prototype.ltrim = function() {
		return this.replace(/^\s+/g,"");
	}
	String.prototype.rtrim = function() {
		return this.replace(/\s+$/g,"");
	}

	// To escape new line characters.
	SM.escapeCharacters = function(result){
		// The "g" at the end of the regex statement signifies that the replacement should take place more than once (g).
		patternF = /\f/g;
		patternN = /\n/g;
		patternR = /\r/g;
		patternT = /\t/g;
		return result = result.replace(patternF,'\\f').replace(patternN,'\\n').replace(patternR,'\\r').replace(patternT,'\\t');
	};
	
	//creates new 'Add Product' Button & a vertical Separator and is added to the pagingtoolbar.
	var showAddProductButton = function(){
		if(typeof pagingToolbar.addProductButton == 'undefined' && typeof Ext.getCmp('addProductSeparator') == 'undefined'){
			var addProductSeparator = new Ext.Toolbar.Separator({
				id: 'addProductSeparator'
			});

			var addProductButton = new Ext.Button({
				text   	  : getText('Add product'),
				tooltip   : getText('Add a new product'),
				// icon      : imgURL + 'add.png',
				disabled  : true,
				hidden    : false,
				id 	 	  : 'addProductButton',
				ref 	  : 'addProductButton',
				listeners : {
					click : function() {
						productsColumnModel.getColumnById('publish').editor = newProductStatusCombo;
						if(fileExists == 1){
							addProduct(productsStore, cnt_array, cnt, newCatName);
						}else{
							Ext.notification.msg('Smart Manager', getText('Add product feature is available only in Pro version') );
						}
					}
				}
			});
			pagingToolbar.add(addProductSeparator);
			pagingToolbar.add(addProductButton);
		}
		if(fileExists == 1){
			pagingToolbar.addProductButton.enable();
		}
	};

	// removed 'Add Product' Button & the vertical Separator from the pagingtoolbar.
	var hideAddProductButton = function(){
		if(typeof pagingToolbar.addProductButton != 'undefined' && typeof Ext.getCmp('addProductSeparator') != 'undefined'){
			pagingToolbar.remove(pagingToolbar.addProductButton);
			pagingToolbar.remove(Ext.getCmp('addProductSeparator'));
		}
	};

	//creates new Duplicate Button & a vertical Separator and is added to the pagingtoolbar.
	var showDuplicateButton = function(){
		if(typeof pagingToolbar.duplicateButton == 'undefined' && typeof Ext.getCmp('duplicateProductSeparator') == 'undefined'){
			var duplicateProductSeparator = new Ext.Toolbar.Separator({
				id: 'duplicateProductSeparator'
			});

			//Code to create a new button for dulicating product
	        var duplicateButton = new Ext.SplitButton({
	                        id          : 'duplicateButton',
	                        menu: [{
	                                text: getText('Selected Products'),
	                                handler: function(){
	                                    if ( fileExists != 1 ) {
						Ext.notification.msg('Smart Manager', getText('Duplicate Product feature is available only in Pro version') ); 
						return;
	                                    }
	                                    else{
	                                        duplicateRecords('selected');
	                                    }
	                                }
	                                },{
	                                text: getText('Duplicate Store'),
	                                handler: function(){
	                                    if ( fileExists != 1 ) {
						Ext.notification.msg('Smart Manager', getText('Duplicate Store feature is available only in Pro version') ); 
						return;
	                                    }
	                                    else{
	                                        duplicateRecords('store');
	                                    }
	                                }
	                                }],
	                        text        : getText('Duplicate'),
	                        tooltip     : getText('Duplicate Product / Store'),
	                        // icon        : imgURL + 'duplicate.png',
	                        scope       : this,
	                        width       : 100,
	                        disabled    : true,
	                        hidden      : false,
	                        ref         : 'duplicateButton',
	                        listeners: {
	                                click: function () {
	                                    if(this.pressed == true){
	                                        this.hideMenu();
	                                        this.pressed = false;
	                                    }
	                                    else{
	                                        this.showMenu();
	                                        this.menu.visible = true;
	                                        this.pressed = true;
	                                    }
	                                   
	                                }}
	                        });
			pagingToolbar.add(duplicateProductSeparator);
			pagingToolbar.add(duplicateButton);
		}
	};

	// removed Duplicate Button & the vertical Separator from the pagingtoolbar.
	var hideDuplicateButton = function(){
		if(typeof pagingToolbar.duplicateButton != 'undefined' && typeof Ext.getCmp('duplicateProductSeparator') != 'undefined'){
			pagingToolbar.remove(pagingToolbar.duplicateButton);
			pagingToolbar.remove(Ext.getCmp('duplicateProductSeparator'));
		}
	};

	//creates new 'Print' Button & a vertical Separator and is added to the pagingtoolbar.
	var showPrintButton = function(){
		if(typeof pagingToolbar.printButton == 'undefined' && typeof Ext.getCmp('printSeparator') == 'undefined'){
			var printSeparator = new Ext.Toolbar.Separator({
				id: 'printSeparator'
			});

			var printButton = new Ext.Button({
				text: getText('Print'),
				tooltip: getText('Print Order'),
				disabled: true,
				ref: 'printButton',
				id: 'printButton',
				icon: imgURL + 'print.png',
				scope: this,
				listeners: {
					click: function () {
						if(fileExists == 1){
							showPrintWindow(editorGrid);
						}else{
							Ext.notification.msg('Smart Manager',  getText('Print Preview feature is available only in Pro version') );
						}
					}
				}
			});

			pagingToolbar.add(printSeparator);
			pagingToolbar.add(printButton);
		}
	};

	//removed 'Print' Button & the vertical Separator from the pagingtoolbar.
	var hidePrintButton = function(){
		if(typeof pagingToolbar.printButton != 'undefined' && typeof Ext.getCmp('printSeparator') != 'undefined'){
			pagingToolbar.remove(Ext.getCmp('printSeparator'));
			pagingToolbar.remove(pagingToolbar.printButton);
		}
	};
	
	var showDeleteButton = function(){
		if(typeof pagingToolbar.deleteButton == 'undefined' && typeof Ext.getCmp('deleteSeparator') == 'undefined'){
			var deleteSeparator = new Ext.Toolbar.Separator({
				id: 'deleteSeparator'
			});

			var deleteButton = new Ext.Button({
				text: getText('Delete'),
				tooltip: getText('Delete the selected items'),
				disabled: true,
				ref: 'deleteButton',
				id: 'deleteButton',
				// icon: imgURL + 'delete.png',
				scope: this,
				listeners: { click: function () { deleteRecords(); }}
			});

			pagingToolbar.add(deleteSeparator);
			pagingToolbar.add(deleteButton);
		}
	}
	
	//remove 'Delete' Button & its vertical Separator from the pagingtoolbar.
	var hideDeleteButton = function(){
		if(typeof pagingToolbar.deleteButton != 'undefined' && typeof Ext.getCmp('deleteSeparator') != 'undefined'){
			pagingToolbar.remove(Ext.getCmp('deleteSeparator'));
			pagingToolbar.remove(pagingToolbar.deleteButton);
		}
	};
	
	/* ====================== Products ==================== */
	
	//Renderer for dimension units
	Ext.util.Format.comboRenderer = function(dimensionCombo){
		return function(value){
			var record = dimensionCombo.findRecord(dimensionCombo.valueField, value);
			return record ? record.get(dimensionCombo.displayField) : dimensionCombo.valueNotFoundText;
		}
	}
	
	//units combo box for product's shipping details
	var dimensionCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['value','name'],
			data: [['in', 'inches'], ['cm', 'cm'], ['meter', 'meter']]
		}),
		valueField: 'value',
		displayField: 'name'
	});

	//combo box consisting of yes and no values.
	var yesNoCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['value','name'],
			data: [[1, 'Yes'], [0, 'No']]
		}),
		valueField: 'value',
		displayField: 'name'
	});	
	
	//weight combo box for product's shipping details
	var weightUnitCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['value','name'],
			data: [['pound', getText('Pounds')], ['ounce', getText('Ounces')], ['gram', getText('Grams')], ['kilogram', getText('Kilograms')]]
		}),
		valueField: 'value',
		displayField: 'name'
	});
	
	// product status combo box
	var productStatusCombo = new Ext.form.ComboBox({
		typeAhead: true,
		id: 'productStatusCombo',
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',		
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['value','name'],
			data: [['publish', 'Published'], ['draft', 'Draft'],['inherit', 'Inherit'],['private', 'Privately Published']]
		}),
		valueField: 'value',
		displayField: 'name'
	});
	
	// product status combo box when new record is added to grid
	var newProductStatusCombo = new Ext.form.ComboBox({
		typeAhead: true,
		id: 'newProductStatusCombo',
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['value','name'],
			data: [['publish', 'Published'], ['draft', 'Draft'],['private', 'Privately Published']]			
		}),
		valueField: 'value',
		displayField: 'name'
	});

        //Code to override the Drag function of EXTJS

        Ext.override(Ext.grid.HeaderDragZone, {
            getDragData : function(e){
                var t = Ext.lib.Event.getTarget(e);
                var h = this.view.findHeaderCell(t);
                if (h && (this.grid.colModel.config[this.view.getCellIndex(h)].dragable !== false)){
                    return {ddel: h.firstChild, header:h};
                }
                return false;
            }
        });

        //Code to enable disabling any column to be moved to the place of the one which cannot be dragged
        Ext.ProductsColumnModel = Ext.extend(Ext.grid.ColumnModel, {
          moveColumn: function (oldIndex, newIndex) {

            if (newIndex == 1) {
              newIndex = 2;
            }
            else if (newIndex == 23 || newIndex == 24) {
              newIndex = 22;
            }

            var c = this.config[oldIndex];
            this.config.splice(oldIndex, 1);
            this.config.splice(newIndex, 0, c);
            this.dataMap = null;
            this.fireEvent("columnmoved", this, oldIndex, newIndex);
          }
        });


        var wpec_dimension_flag = true;
        var wpec_dimension_unit_flag = false;

        if (isWPSC3814 == '1') {
        	wpec_dimension_flag = false;
        	wpec_dimension_unit_flag = true;
        }

        var productsColumnModel = new Ext.ProductsColumnModel({
		columns: [editorGridSelectionModel,
		{
			header: '',
			id: 'type',
			dataIndex: SM.productsCols.post_parent.colName,
			tooltip: getText('Type'), 
			width: 20,
			hidden: true,
                        dragable:false,
			renderer: function (value, metaData, record, rowIndex, colIndex, store) {
				return (value == 0 ? '<span id=wpsc_prod_variation_img> </span>' : '');
			}
		},
		{
			header: SM.productsCols.image.name,
			id: 'image',
			dataIndex: SM.productsCols.image.colName,
			tooltip: getText('Product Image'),
			width: 30,
			hidden: true,
			renderer: function (value, metaData, record, rowIndex, colIndex, store) {
				return (record.data.thumbnail != 'false' ? '<img id=editUrl width=16px height=16px src="' + record.data.thumbnail + '"/>' : '');
			}
		},
		{
			header: SM.productsCols.name.name,
			id: 'name_products',
			sortable: true,
			dataIndex: SM.productsCols.name.colName,
			tooltip: getText('Product Name'),
			width: 250,
			editable: true,
			editor: new fm.TextField({
				allowBlank: false
			})
		},
		{
			header: SM.productsCols.regularPrice.name,
			id: 'price',
			// type: 'float',
			align: 'right',
			sortable: true,
                        width: 70,
			dataIndex: SM.productsCols.regularPrice.colName,
			tooltip: getText('Price'),
			editable: true,
			renderer: amountRenderer,
			editor: new fm.NumberField({
				allowBlank: true,
				allowNegative: true
			})
		},{
			header: SM.productsCols.salePrice.name,
			id: 'salePrice',
			sortable: true,
			align: 'right',
			width: 70,
			dataIndex: SM.productsCols.salePrice.colName,
			renderer: amountRenderer,
			tooltip: getText('Sale Price'),
			editor: new fm.NumberField({
				allowBlank: true,
				allowNegative: true
			})
		},{
			header: SM.productsCols.inventory.name,
			id: 'inventory',
			sortable: true,
			align: 'right',
                        width: 40,
			dataIndex: SM.productsCols.inventory.colName,
			tooltip: getText('Inventory'),
			editor: new fm.NumberField({
				allowBlank: true,
				allowNegative: true
			})
		},{
			header: SM.productsCols.sku.name,
			id: 'sku',
			sortable: true,
                        width: 70,
			dataIndex: SM.productsCols.sku.colName,
			tooltip: getText('SKU'),
			editor: new fm.TextField({
				allowBlank: false
			})
		},{
			header: SM.productsCols.group.name,
			id: 'group',
			sortable: true,
                        width: 100,
			dataIndex: SM.productsCols.group.colName,
			tooltip: getText('Category')
		},{
			header: SM.productsCols.weight.name,
			id: 'weight',
			colSpan: 2,
			sortable: true,
			align: 'right',
            width: 60,
			dataIndex: SM.productsCols.weight.colName,
			tooltip: getText('Weight'),
			// renderer: amountRenderer,
			renderer: dimensionsRenderer,
			editor: new fm.NumberField({
				allowBlank: true,
				allowNegative: false,
				decimalPrecision:sm_dimensions_decimal_precision
			})
		},{
			header: SM.productsCols.weightUnit.name,
			id: 'weightUnit',
			sortable: true,
			hidden: true,
                        width: 55,
			dataIndex: SM.productsCols.weightUnit.colName,
			tooltip: getText('Weight Unit'),
			editor: weightUnitCombo,
			renderer: Ext.util.Format.comboRenderer(weightUnitCombo)
		},{
			header: SM.productsCols.publish.name,
			id: 'publish',
			sortable: true,
                        width: 60,
			dataIndex: SM.productsCols.publish.colName,
			tooltip: getText('Product Status'),
			renderer: Ext.util.Format.comboRenderer(productStatusCombo)
		},{
			header: SM.productsCols.disregardShipping.name,
			id: 'disregardShipping',
			hidden: true,
			sortable: true,
                        width: 45,
			dataIndex: SM.productsCols.disregardShipping.colName,
			tooltip: getText('Disregard Shipping'),
			editor: yesNoCombo,
			renderer: Ext.util.Format.comboRenderer(yesNoCombo)
		},{
			header: SM.productsCols.desc.name,
			id: 'desc',
//			dataIndex: SM.productsCols.desc.colName,
			tooltip: getText('Description'), 
			width: 180,
                        hideable: false,
                        hidden: true,
			editor: new fm.TextArea({				
				autoHeight: true,
				grow: true,
				growMax: 10000
			})
		}
                ,{
			header: SM.productsCols.addDesc.name,
			id: 'addDesc',
			hidden: true,
//			dataIndex: SM.productsCols.addDesc.colName,
			tooltip: getText('Additional Description'),
			width: 180,
                        hideable: false,
			editor: new fm.TextArea({
				autoHeight: true,
				grow: true,
				growMax: 10000
			})
		}
                ,{
	  		header: SM.productsCols.pnp.name,
	  		id: 'pnp',
	  		hidden: true,
			colSpan: 2,
			sortable: true,
			align: 'right',
                        width: 70,
			dataIndex: SM.productsCols.pnp.colName,
			tooltip: getText('Local Shipping Fee'),			
			renderer: amountRenderer,
			editor: new fm.NumberField({
				allowBlank: false,
				allowNegative: false
			})
		},{
			header: SM.productsCols.intPnp.name,
			id: 'intPnp',
			hidden: true,
			colSpan: 2,
			sortable: true,
			align: 'right',
                        width: 70,
			dataIndex: SM.productsCols.intPnp.colName,
			tooltip: getText('International Shipping Fee'),
			renderer: amountRenderer,
			editor: new fm.NumberField({
				allowBlank: false,
				allowNegative: false
			})
		},{
			header: SM.productsCols.height.name,
			id: 'height',
			colSpan: 2,
			hidden: true,
			sortable: true,
			align: 'right',
            width: 60,
			dataIndex: SM.productsCols.height.colName,
			tooltip: getText('Height'),		
			// renderer: amountRenderer,
			renderer: dimensionsRenderer,
			editor: new fm.NumberField({
				allowBlank: false,
				allowNegative: false,
				decimalPrecision:sm_dimensions_decimal_precision
			})
		},{
			header: SM.productsCols.heightUnit.name,
			id: 'heightUnit',
			hidden: true,
			sortable: true,
			width: 40,
			hideable: wpec_dimension_flag,
			dataIndex: SM.productsCols.heightUnit.colName,
			tooltip: getText('Height Unit'),
			editor: dimensionCombo,
			renderer: Ext.util.Format.comboRenderer(dimensionCombo)
		},{
			header: SM.productsCols.width.name,
			id: 'width',
			colSpan: 2,
			hidden: true,
			sortable: true,
			align: 'right',
            width: 60,
			dataIndex: SM.productsCols.width.colName,
			tooltip: getText('Width'),
			// renderer: amountRenderer,
			renderer: dimensionsRenderer,
			editor: new fm.NumberField({
				allowBlank: false,
				allowNegative: false,
				decimalPrecision:sm_dimensions_decimal_precision
			})
		}
		,{
			header: SM.productsCols.widthUnit.name,
			id: 'widthUnit',
			hidden: true,
			sortable: true,
			hideable: wpec_dimension_flag,
            width: 40,
			dataIndex: SM.productsCols.widthUnit.colName,
			tooltip: getText('Width Unit'),
			editor: dimensionCombo,
			renderer: Ext.util.Format.comboRenderer(dimensionCombo)
		}
			// width_unit
		,{
			header: SM.productsCols.lengthCol.name,
			id: 'lengthCol',
			colSpan: 2,
			hidden: true,
			sortable: true,
			align: 'right',
            width: 60,
			dataIndex: SM.productsCols.lengthCol.colName,
			tooltip: getText('Length'),			
			// renderer: amountRenderer,
			renderer: dimensionsRenderer,
			editor: new fm.NumberField({
				allowBlank: false,
				allowNegative: false,
				decimalPrecision:sm_dimensions_decimal_precision
			})
		},{
			header: SM.productsCols.lengthUnit.name,
			sortable: true,
			hidden: true,
			id: 'lengthUnit',
            width: 40,
            hideable: wpec_dimension_flag,
			dataIndex: SM.productsCols.lengthUnit.colName,
			tooltip: getText('Length Unit'),
			editor: dimensionCombo,
			renderer: Ext.util.Format.comboRenderer(dimensionCombo)
		}
		,{
			header: SM.productsCols.dimensionUnit.name,
			sortable: true,
			hidden: true,
			id: 'dimensionUnit',
			hideable: wpec_dimension_unit_flag,
            width: 40,
			dataIndex: SM.productsCols.dimensionUnit.colName,
			tooltip: getText('Dimension Unit'),
			editor: dimensionCombo,
			renderer: Ext.util.Format.comboRenderer(dimensionCombo)
		},{
			header: getText('Edit'), 
			id: 'edit',
			sortable: true,
			tooltip: getText('Product Info'),
			dataIndex: 'edit_url',
			width: 30,
                        dragable:false,
			id: 'editLink',
			renderer: function (value, metaData, record, rowIndex, colIndex, store) {
	            if(record.get('post_parent') == 0) {
	                // return '<img id=editUrl src="' + imgURL + 'edit.gif"/>';
	                return '<span id=editlink> </span>';
	            }
			}
		},{
			header: '',
                        id: 'products_scroll_wpsec',
                        width: 8.5,
                        Fixed: true,
                        sortable:false,
                        menuDisabled : true,
                        hideable: false,
                        dragable:false
		}],
		listeners: {
			hiddenchange: function( ColumnModel,columnIndex, hidden ){
                            state_apply = true;
                        }

		},
		defaultSortable: true
	});	

	// created a custom jsonreader by extending JsonReader and overridding read function 
	// to escape invisible/white space characters from the responseText
	Ext.data.customJsonReader = Ext.extend(Ext.data.JsonReader,{
		read : function(response){
			var responseData = response.responseText;
				responseData = responseData.trim();

			var json = SM.escapeCharacters(responseData),
				   o = Ext.decode(json);
			if(!o) {
				throw {message: 'JsonReader.read: Json object not found'};
			}
			return this.readRecords(o);
		}
	});

	var wpec_products_fields = new Array();
	if (isWPSC3814 == '1') {
		wpec_products_fields = [
									{name: SM.productsCols.id.colName,                type: 'int'},
									{name: SM.productsCols.name.colName,              type: 'string'},
									{name: SM.productsCols.regularPrice.colName,      type: 'string'},
									{name: SM.productsCols.salePrice.colName,         type: 'string'},
									{name: SM.productsCols.inventory.colName,         type: 'string'},
									{name: SM.productsCols.publish.colName,           type: 'string'},
									{name: SM.productsCols.sku.colName,               type: 'string'},
									{name: SM.productsCols.group.colName,             type: 'string'},
									{name: SM.productsCols.disregardShipping.colName, type: 'string'},
									{name: SM.productsCols.desc.colName,              type: 'string'},
									{name: SM.productsCols.addDesc.colName,           type: 'string'},
									{name: SM.productsCols.pnp.colName,               type: 'float'},
									{name: SM.productsCols.intPnp.colName,            type: 'float'},
									{name: SM.productsCols.weight.colName,            type: 'string'},
									{name: SM.productsCols.weightUnit.colName,        type: 'string'},
									{name: SM.productsCols.height.colName,            type: 'string'},
									{name: SM.productsCols.width.colName,             type: 'string'},
									{name: SM.productsCols.lengthCol.colName,         type: 'string'},
									{name: SM.productsCols.dimensionUnit.colName,     type: 'string'},
									{name: SM.productsCols.post_parent.colName,	      type: 'int'},
									{name: SM.productsCols.image.colName,	      	  type: 'string'}
								];
	} else {
		wpec_products_fields = [
									{name: SM.productsCols.id.colName,                type: 'int'},
									{name: SM.productsCols.name.colName,              type: 'string'},
									{name: SM.productsCols.regularPrice.colName,      type: 'string'},
									{name: SM.productsCols.salePrice.colName,         type: 'string'},
									{name: SM.productsCols.inventory.colName,         type: 'string'},
									{name: SM.productsCols.publish.colName,           type: 'string'},
									{name: SM.productsCols.sku.colName,               type: 'string'},
									{name: SM.productsCols.group.colName,             type: 'string'},
									{name: SM.productsCols.disregardShipping.colName, type: 'string'},
									{name: SM.productsCols.desc.colName,              type: 'string'},
									{name: SM.productsCols.addDesc.colName,           type: 'string'},
									{name: SM.productsCols.pnp.colName,               type: 'float'},
									{name: SM.productsCols.intPnp.colName,            type: 'float'},
									{name: SM.productsCols.weight.colName,            type: 'string'},
									{name: SM.productsCols.weightUnit.colName,        type: 'string'},
									{name: SM.productsCols.height.colName,            type: 'string'},
									{name: SM.productsCols.heightUnit.colName,        type: 'string'},
									{name: SM.productsCols.width.colName,             type: 'string'},
									{name: SM.productsCols.widthUnit.colName,         type: 'string'},
									{name: SM.productsCols.lengthCol.colName,         type: 'string'},
									{name: SM.productsCols.lengthUnit.colName,        type: 'string'},
									{name: SM.productsCols.post_parent.colName,	      type: 'int'},
									{name: SM.productsCols.image.colName,	      	  type: 'string'}
								];
	}
	
	var productsJsonReader = new Ext.data.customJsonReader({
		totalProperty: 'totalCount',
		root: 'items',
		fields: wpec_products_fields
	});	
	
	var productsStore = new Ext.data.Store({
		reader: productsJsonReader,
		proxy: new Ext.data.HttpProxy({
			// url: jsonURL
			url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		}),
		baseParams: {
			cmd: 'getData',
			active_module: SM.activeModule,
			start: 0,
			limit: limit,
			viewCols: Ext.encode(productsViewCols),
			incVariation: SM.incVariation,
			security: SM_NONCE,
			file:  jsonURL
		},
		dirty: false,
		pruneModifiedRecords: true,
		listeners: {
			//Products Store onload function.
			load: function (store,records,obj) {
				cnt = -1;
				cnt_array = [];
				editorGridSelectionModel.clearSelections();
				pagingToolbar.saveButton.disable();
				productsColumnModel.getColumnById('publish').editor = productStatusCombo;
			}
		}
	});

	var showProductsView = function(){
		productsStore.baseParams.searchText = ''; //clear the baseParams for productsStore
		SM.searchTextField.reset(); 			  //to reset the searchTextField

		productsStore.baseParams.searchText = ''; //clear the baseParams for productsStore
		SM.searchTextField.reset(); 			  //to reset the searchTextField
		SM.searchTextField.hide(); 			  //to reset the searchTextField
		
		// jQuery("#sm_advanced_search_content").show(); //showing the advanced search box

		// editorGrid.getTopToolbar().get('searchIconId').hide();

		if (SM.search_type == 'Simple Search') {
			
			jQuery("#search_switch").html('Simple Search');
			jQuery("#search_switch").attr('title','Switch to simple search');
			
			SM.searchTextField.hide(); 			  //to reset the searchTextField
			jQuery("#sm_advanced_search_content").show(); //showing the advanced search box
			editorGrid.getTopToolbar().get('searchIconId').hide();
			SM.searchTextField.reset();



		} else {

			jQuery("#search_switch").html('Advanced Search');
			jQuery("#search_switch").attr('title','Switch to advanced search');

			SM.searchTextField.show(); 			  //to reset the searchTextField
			jQuery("#sm_advanced_search_content").hide(); //showing the advanced search box
			editorGrid.getTopToolbar().get('searchIconId').show();	
			SM.searchTextField.reset();

		}


		jQuery(function($){
			window.visualSearch = new VisualSearch({
					el		: $("#sm_advanced_search_box_0"),
					placeholder: "Enter your search conditions here!",
					strict: false,
					search: function(json){
						$("#sm_advanced_search_box_value_0").val(json);
					},
					parameters: wpec_products_search_cols
				});


			var grid_pannel_width = $(".x-panel-tbar").width();
			$('#sm_advanced_search_content').css('width',(grid_pannel_width/2.2));

			// count = 0;
			$("#sm_advanced_search_or").on('click', function () {
				if ( fileExists != 1 ) {
					$("#sm_advanced_search_or").attr('disabled','disabled');
					Ext.notification.msg('Smart Manager', getText('This feature is available only in Pro version')); 
					return;
				} else {
					$("#sm_advanced_search_or").removeAttr('disabled');
					addAdvancedSearchCondition();
				}
			});
			
			$('#sm_advanced_search_submit').on('click',function(){ //listen for submit event

				var search_query = new Array();
				$('input[id^="sm_advanced_search_box_value_"]').each(function() {
				    search_query.push($(this).val());
				});

				// Code to get the search params in ajax request
				productsStore.setBaseParam('searchText', ''); // deleting the simple search query
				productsStore.setBaseParam('search_query[]', search_query);
				productsStore.setBaseParam('search', 'advanced_search');
				
				SM.advanced_search_query = search_query;

				mask.show();

				$.ajax({
	                    type : 'POST',
	                    url : (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
	                    dataType: "text",
	                    async: false,
	                    data: {

	                    	cmd: 'getData',
							active_module: SM.activeModule,
							start: 0,
							limit: limit,
							viewCols: Ext.encode(productsViewCols),
							incVariation: SM.incVariation,
							file:  jsonURL,
				            search_query: search_query,
				            security: SM_NONCE,
				            search: 'advanced_search'
	                    },
	                    // callback: function (options, success, response) {
	                    success: function(response) {
			
							var myJsonObj = Ext.decode(response);
							
							try {
								var records_cnt = myJsonObj.totalCount;
								if (records_cnt == 0) myJsonObj.items = '';
								if(SM.activeModule == 'Products')
									productsStore.loadData(myJsonObj);
								else if(SM.activeModule == 'Orders'){
									ordersStore.loadData(myJsonObj);
				                } else {
									customersStore.loadData(myJsonObj);
				                }
								
								mask.hide();

							} catch (e) {
								return;
							}
						}
				});
			});
		
		//Code for search switch
			$("#search_switch").show();

			var search_switch_id = $("#search_switch").parent().parent().attr('id');
			$("#"+search_switch_id).unbind( "click" );

			$("#"+search_switch_id).on('click',function(){

				if ($("#search_switch").text().trim() == 'Simple Search') {

					jQuery('div[id^="sm_advanced_search_box_"] .VS-icon-cancel').trigger("click");

					$("#search_switch").html('Advanced Search');
					$("#search_switch").attr('title','Switch to advanced search');

					SM.searchTextField.show(); 			  //to reset the searchTextField
					jQuery("#sm_advanced_search_content").hide(); //showing the advanced search box
					editorGrid.getTopToolbar().get('searchIconId').show();	
					SM.searchTextField.reset();

					jQuery('input[id^="sm_advanced_search_box_value_"]').each(function() {
					    jQuery(this).val("");
					});

				} else {

					jQuery('div[id^="sm_advanced_search_box_"] .VS-icon-cancel').trigger("click");

					$("#search_switch").html('Simple Search');
					$("#search_switch").attr('title','Switch to simple search');
					
					SM.searchTextField.hide(); 			  //to reset the searchTextField
					jQuery("#sm_advanced_search_content").show(); //showing the advanced search box
					editorGrid.getTopToolbar().get('searchIconId').hide();
					SM.searchTextField.reset();

					jQuery('input[id^="sm_advanced_search_box_value_"]').each(function() {
					    jQuery(this).val("");
					});
				}
			});

		});

		hidePrintButton();
		hideDeleteButton();
		showAddProductButton();
		showDuplicateButton();
		showDeleteButton();
		pagingToolbar.doLayout(true,true);
				
		for(var i=2;i<=8;i++)
		editorGrid.getTopToolbar().get(i).hide();
		editorGrid.getTopToolbar().get('incVariation').show();
        // editorGrid.getTopToolbar().get('duplicateButton').show();

		productsStore.load();
		pagingToolbar.bind(productsStore);

		editorGrid.reconfigure(productsStore,productsColumnModel);
                fieldsStore.loadData(productsFields);

		var firstToolbar       = batchUpdatePanel.items.items[0].items.items[0];
		var textfield          = firstToolbar.items.items[5];
		var weightUnitDropdown = firstToolbar.items.items[7];

		weightUnitDropdown.hide();
		weightUnitStore.loadData(weightUnits);
		textfield.show();
	};

	/* ====================== Products ==================== */

	
//	==== common ====

//Variations
		var getVariations = function (params,columnModel,store){
        if ( editorGrid.loadMask != undefined ) editorGrid.loadMask.show();
        var o = {
		// url: jsonURL,
		url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		method: 'post',
		callback: function (options, success, response) {
                editorGrid.loadMask.show();
			if (true !== success) {
				Ext.notification.msg('Failed',response.responseText);
				return;
			}

			try{
				if(typeof(response.responseText) != 'undefined'){
					var result = response.responseText;
					    result = result.trim();
					    result = SM.escapeCharacters(result);
					var myJsonObj = Ext.decode(result);

					var records_cnt = myJsonObj.totalCount;
					if (records_cnt == 0){
						myJsonObj.items = '';
					}
                    store.loadData(myJsonObj);
					if(SM.incVariation == false){
						columnModel.setHidden(SM.typeColIndex,true);
					}else{
						columnModel.setHidden(SM.typeColIndex,false);
					}
				}
			} catch (e) {
				return;
			}
		},
		scope: this,
		params: params
	};
	Ext.Ajax.request(o);
}

var updation_progress = updated + 1;

//Function to handle the enabling and disabling the functionality for lite version
var sm_disabled_lite = function () {
	if ( fileExists != 1 ) {
		return true;
	}
	else {
		return false;
	}
}

var pagingToolbar = new Ext.PagingToolbar({
	id: 'pagingToolbar',
	items: ['->', {xtype:'tbseparator', id:'beforeBatchSeparator'},
	{
		text: getText('Batch Update'), 
		tooltip: getText('Update selected items'),
		// icon: imgURL + 'batch_update.png',
		id: 'batchUpdateButton',
		disabled: true,
		ref: 'batchButton',
		scope: this,
		listeners: { 
			click: function () { 
				if(SM.activeModule == 'Products') {
					var pageTotalRecord = editorGrid.getStore().getCount();		
					var selectedRecords=editorGridSelectionModel.getCount();
					if( selectedRecords >= pageTotalRecord ){

						if (SM.advanced_search_query != '' || SM.searchTextField.getValue() != '') {
							jQuery("label[for='sm_batch_entire_store_option']").text('All items in search result (including Variations)');
						} else {
							jQuery("label[for='sm_batch_entire_store_option']").text('All items in store (including Variations)');
						}

						batchRadioToolbar.setVisible(true);
					} else {	
						batchRadioToolbar.setVisible(false);						
					}
				} else {
					batchRadioToolbar.setVisible(false);
				}
				batchUpdateWindow.show();	
			}
		}
	},{xtype:'tbseparator', id:'beforeSaveSeparator'},{
		text: getText('Save'),
		tooltip: getText('Save all Changes'),
		icon: sm_beta_imgURL + 'jqgrid/save_img-blue-15X15.png',
		disabled: true,
		scope: this,
		ref: 'saveButton',
		id: 'saveButton',
		listeners:{ click : function () {
			if(SM.activeModule == 'Orders')
			store = ordersStore;
			else if(SM.activeModule == 'Products')
			store = productsStore;
			else
			store = customersStore;
			saveRecords(store,pagingToolbar,jsonURL,editorGridSelectionModel);
		}}
	},{xtype:'tbseparator', id:'beforeExportSeparator'},
	{
		text: getText('Export CSV'),
		tooltip: getText('Download CSV file'), 
		// icon: imgURL + 'export_csv.gif',
		id: 'exportCsvButton',
		ref: 'exportButton',
		disabled: sm_disabled_lite(),
		scope: this,
		listeners: { 
			click: function () { 

				if ( fileExists != 1 ) {
					Ext.notification.msg('Smart Manager', getText('Export CSV feature is available only in Pro version') ); 
					return;
				}

				// Code for getting the advanced search query
				var search_query = new Array();
				jQuery('input[id^="sm_advanced_search_box_value_"]').each(function() {
				    search_query.push(jQuery(this).val());
				});

				var fileurl = (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file';

				Ext.DomHelper.append(Ext.getBody(), { 
                    tag: 'iframe', 
                    id:'downloadIframe', 
                    frameBorder: 0, 
                    width: 0, 
                    height: 0,
                    css: 'display:none;visibility:hidden;height:0px;', 
                    // src: jsonURL+'?cmd=exportCsvWpsc&incVariation='+SM.incVariation+'&viewCols='+encodeURIComponent(Ext.encode(productsViewCols))+'&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+''
                    // src: ajaxurl + '?action=sm_include_file&file='+jsonURL+'&func_nm=exportCsvWpsc&incVariation='+SM.incVariation+'&viewCols='+encodeURIComponent(Ext.encode(productsViewCols))+'&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+''
                    src: fileurl + '&file='+jsonURL+'&func_nm=exportCsvWpsc&incVariation='+SM.incVariation+'&search_query[]='+encodeURIComponent(search_query)+'&search=advanced_search&viewCols='+encodeURIComponent(Ext.encode(productsViewCols))+'&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+'&security='+SM_NONCE
                }); 
			}
		}
	}],
	pageSize: limit,
	store: productsStore,
	displayInfo: true,
	style: { width: '100%' },
	hideBorders: true,
	align: 'center',
	displayMsg: 'Displaying {0} - {1} of {2}',
	emptyMsg: SM.activeModule + ' ' + getText('list is empty')
});
var pagingActivePage = pagingToolbar.getPageData().activePage;
	
	// Function to save modified records
	var saveRecords = function(store,pagingToolbar,jsonURL,editorGridSelectionModel){
		// Gets all records modified since the last commit.
		// Modified records are persisted across load operations like pagination or store reload.
		
		var modifiedRecords = store.getModifiedRecords();		
		if(!modifiedRecords.length) {
			return;
		}else if ( ( modifiedRecords.length >= updation_progress ) && ( fileExists == 0 ) ) {
			Ext.notification.msg('Note', 'For editing more records upgrade to Pro');
			return;
		}
		var edited  = [];
		Ext.each(modifiedRecords, function(r, i){
			if(r.get('id') == ''){
				r.data.category = newCatId;
			}
                        
                        if (r.store.baseParams.active_module == 'Customers') {
                            r.data['last_order_id'] = r.json['last_order_id'];
                            r.data['modified'] = r.modified;
                        }
			
			edited.push(r.data);
		});
		
		var o = {
			// url:jsonURL
			url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
			,method:'post'
			,callback: function(options, success, response)	{
				var myJsonObj = Ext.decode(response.responseText);
				if(true !== success){
					Ext.notification.msg('Failed',response.responseText);
					return;
				}try{

					row_index_save_lite = [];
					flag_save_lite = 0;

					store.commitChanges();					
					pagingToolbar.saveButton.disable();
					Ext.notification.msg('Success', myJsonObj.msg);
					pagingToolbar.doRefresh(); // to refresh the current page.
					return;
				}catch(e){
					var err = e.toString();
					Ext.notification.msg('Error', err);					
					return;
				}
			}
			,scope:this
			,params:
			{
				cmd:'saveData',
				active_module: SM.activeModule,
				edited:Ext.encode(edited),
				isWPSC3814: isWPSC3814,
				security: SM_NONCE,
				file:  jsonURL
			}};
			Ext.Ajax.request(o);
	};

        //Function to reset all the fields of the batch update window

        function batchupdate_reset() {
            
            for (sb = toolbarCount; sb >= 1; sb--){
            if(batchUpdatePanel.get(sb) != undefined)
                batchUpdatePanel.remove(batchUpdatePanel.get(sb));
            }

            var firstToolbar = batchUpdatePanel.items.items[0].items.items[0];
            
            firstToolbar.items.items[0].reset();
            firstToolbar.items.items[2].reset();

            firstToolbar.items.items[4].reset();
            firstToolbar.items.items[4].hide();

            firstToolbar.items.items[5].reset();

            firstToolbar.items.items[7].reset();
            firstToolbar.items.items[7].hide();

            firstToolbar.items.items[9].reset();
            firstToolbar.items.items[9].hide();

            firstToolbar.items.items[11].reset();
            firstToolbar.items.items[11].hide();

            firstToolbar.items.items[13].hide();
            firstToolbar.items.items[15].hide();
            firstToolbar.items.items[2].show(); // As the same is hidden if the Image functionality not available
            
            //Code for reseting the Image button icon
            jQuery('.x-batchimage').css('background-image', 'url(' + imgURL + 'batch_image.gif' + ')');
            jQuery('.x-batchimage').css('background-size', '100% 100%');
        }


        // Function to duplicate the Selected Products
        var duplicateRecords = function (menu) {
		var selected  = editorGrid.getSelectionModel();
		var records   = selected.selections.keys;
		var getDuplicateRecords = function (btn, text) {
			if (btn == 'yes') {

                        //Code to create a extjs Messagebox with a progressbar
                        var progress = Ext.MessageBox.show({
                           title: 'Please wait',
                           msg: 'Duplicating Products...',
                           progressText: 'Initializing...',
                           width: 300,
                           progress: true,
                           closable: false
                           });

                        var count = 0;

                        function demo(count, total_records, dup_data) {
                        var arr = new Array();

                        dupcnt = 0;
                        if (total_records > 20) {
                            fdupcnt = 20;
                        }
                        else{
                            fdupcnt = total_records;
                        }

                        //Code to delay the progressbar hide task and then load the store
                        var task = new Ext.util.DelayedTask(function(){
                            progress.hide();
                            store.load();
                        });

                        //Code to create multiple AJAX request based on the count received from the first AJAX Request
                        for (i=0;i<count;i++) {

                                arr[i] = {
					// url: jsonURL,
					url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
					method: 'post',
					callback: function (options, success, response) {

                                                store = productsStore;
                                                
                                                try {
                                                    var myJsonObj    = Ext.decode(response.responseText);
                                                    var nxtreq       = myJsonObj.nxtreq;
                                                    var dupcnt       = myJsonObj.dupCnt;
                                                    var per          = myJsonObj.per;
                                                    var val          = myJsonObj.val;
                                                
                                                    if (true !== success) {
                                                        Ext.notification.msg('Failed',myJsonObj.msg);
							return;
                                                    }
                                                    else{
                                                        progress.updateProgress(val,per+"% Completed");
							
                                                        if (nxtreq < count) {
                                                            Ext.Ajax.request(arr[nxtreq]);
                                                        }
                                                        else{
                                                            task.delay(2500);
                                                        }

                                                        if (dupcnt == 0) {
                                                            Ext.notification.msg('Warning', myJsonObj.msg);
                                                        }
                                                        else{
                                                             if (per == 100) {
                                                                Ext.notification.msg('Success', myJsonObj.msg);							
                                                            }
                                                        }

                                                    }

                                                }
                                                catch (e) {
                                                            Ext.notification.msg('Warning','Duplication of the Product Not Successful');							
                                                            return;
						}
					},
					scope: this,
					params: {
						cmd : 'dupData',
                                                part : i+1,
                                                dup_limit: dup_limit,
                                                dupcnt : dupcnt,
                                                fdupcnt : fdupcnt,
                                                count : count,
                                                total_records : total_records,
                                                dup_data : dup_data,
                                                menu : menu,
                                                active_module : SM.activeModule,
                                                incvariation : SM.incVariation,
                                                security: SM_NONCE,
                                                file:  jsonURL
					}
				};

                                    dupcnt = fdupcnt;
                                    if ((fdupcnt+20) <= total_records) {
                                          fdupcnt = fdupcnt +20;
                                    }
                                     else{
                                        fdupcnt = total_records;
                                    }
     
                         }

                            Ext.Ajax.request(arr[0]);
                        }
                        
                        //Initial AJAX request to get the number of AJAX request to be made based on the number of products selected for duplication
                        var o = {
                            // url: jsonURL,
                            url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
                            method: 'post',
                            callback: function (options, success, response) {
                                try {
                                    var myJsonObj    = Ext.decode(response.responseText);
                                    var count        = myJsonObj.count;
                                    var dupcnt       = myJsonObj.dupCnt;
                                    var dup_data     = myJsonObj.data_dup;
                                    demo(count, dupcnt, dup_data);
                                }
                                catch (e) {
                                    Ext.notification.msg('Warning','Duplication of the Product Not Successful');							
                                    return;
                                }
                            },
                            scope: this,
                            params: {
                                    cmd: 'dupData',
                                    part: 'initial',
                                    dup_limit: dup_limit,
                                    menu : menu,
                                    active_module: SM.activeModule,
                                    incvariation: SM.incVariation,
                                    data: Ext.encode(records),
                                    security: SM_NONCE,
                                    file:  jsonURL
                            }
                    };
                    Ext.Ajax.request(o);
                }
            }

            var msg;
            if (menu == 'selected') {
                  getDuplicateRecords("yes");
            }
            else{
                msg = getText('Are you sure you want to duplicate the entire store?');
                
                Ext.Msg.show({
                        title: getText('Confirm Product Duplication'),
                        msg: msg,
                        width: 400,
                        buttons: Ext.MessageBox.YESNO,
                        fn: getDuplicateRecords,
                        animEl: 'dup',
                        closable: false,
                        icon: Ext.MessageBox.QUESTION
                })
            }
            
	};

	// Function to delete selected records
	var deleteRecords = function () {
		var selected  = editorGrid.getSelectionModel();
		var records   = selected.selections.keys;
		var getDeletedRecords = function (btn, text) {
			if (btn == 'yes') {
                                batchUpdateWindow.loadMask.show();
				var o = {
					// url: jsonURL,
					url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
					method: 'post',
					callback: function (options, success, response) {

						if(SM.activeModule == 'Products')
						store = productsStore;
						else if(SM.activeModule == 'Orders')
						store = ordersStore;

						var myJsonObj    = Ext.decode(response.responseText);
						var delcnt       = myJsonObj.delCnt;
						var totalRecords = productsJsonReader.jsonData.totalCount;
						var lastPage     = Math.ceil(totalRecords / limit);
						var totalPages   = Math.ceil(totalRecords / limit);
						var currentPage  = pagingToolbar.readPage();
						var lastPageTotalRecords = store.data.length;

						if (true !== success) {
							Ext.notification.msg('Failed',response.responseText);
							return;
						}try {							
							var afterDeletePageCount = lastPageTotalRecords - delcnt;

							//if all the records on the first page are deleted & there are no more records to populate in the grid.
							if (currentPage == 1 && afterDeletePageCount == 0 && totalPages == 1){							
									myJsonObj.items = '';
									store.loadData(myJsonObj);
							}else if (currentPage == lastPage && afterDeletePageCount == 0) { //if all the records on the last page are deleted
								pagingToolbar.movePrevious();
						    }else {						    	
						    	pagingToolbar.doRefresh();
						    }
							
							Ext.notification.msg('Success', myJsonObj.msg);							
						} catch (e) {
							var err = e.toString();
							Ext.notification.msg('Error', err);							
							return;
						}
					},
					scope: this,
					params: {
						cmd: 'delData',
						active_module: SM.activeModule,
						data: Ext.encode(records),
						security: SM_NONCE,
						file:  jsonURL
					}
				};
				Ext.Ajax.request(o);
			}
		}
                
                var msg = getText('Are you sure you want to delete the selected record' + ((records.length == 1) ? '?': 's?'));

		Ext.Msg.show({
			title: getText('Confirm File Delete'),
			msg: msg,
			width: 400,
			buttons: Ext.MessageBox.YESNO,
			fn: getDeletedRecords,
			animEl: 'del',
			closable: false,
			icon: Ext.MessageBox.QUESTION
		})
	};

	var showSelectedModule = function(clickedActiveModule){
		if(clickedActiveModule == 'Customers'){
			SM.activeModule = 'Customers';
			showCustomersView();
		}else if (clickedActiveModule == 'Orders'){
			SM.activeModule = 'Orders';
			showOrdersView();
		}else{
			SM.activeModule = 'Products';
			showProductsView();
		}
	};
	
        
var mask = new Ext.LoadMask(Ext.getBody(), {
	msg: getText('Please wait') + "..."
});

var batchMask = new Ext.LoadMask(Ext.getBody(), {
	msg: getText('Please wait') + "..."
});
	// Products, Customers and Orders combo box
	SM.dashboardComboBox = new Ext.form.ComboBox({
		id: 'dashboardComboBox',
		stateId : 'dashboardComboBoxWpsc',
		stateEvents : ['added','beforerender','enable','select','change','show','beforeshow'],
		stateful: true,
		store: new Ext.data.ArrayStore({
			autoDestroy: true,
			forceSelection: true,
			fields: ['id', 'fullname', 'display']
		}),
		displayField: 'display',
		valueField: 'fullname',
		cls: 'searchPanel',
		mode: 'local',
		triggerAction: 'all',
		editable: false,
		value: 'Products',
		style: {
			fontSize: '14px',
			paddingLeft: '2px'
		},

                applyState: function(state) {
                    if(state){
                        this.setValue(state);
                        mask.show();
                    }
                    pagingToolbar.emptyMsg =  state.value + ' ' + getText('list is empty');
                },

                initState : function(){
                      this.applyState(SM.dashboard_state);
                },

                saveState : function(){
                      SM.dashboard_state = this.value;
                },


		forceSelection: true,
		width: 135,
		listeners: {
			select: function () {
				pagingToolbar.emptyMsg = this.getValue() + ' ' + getText('list is empty');
//				editorGrid.stateId = this.value.toLowerCase()+'EditorGridPanelWpsc';

                                if(this.value == 'Products') {
                                    editorGrid.stateId = this.value.toLowerCase()+'EditorGridPanelWpsc';
                                }

                                var editor_current_state = editorGrid.getState();
                                if (SM.dashboard_state == "Products") {
                                    SM.products_state = editor_current_state;
                                }
                                else if (SM.dashboard_state == "Customers") {
                                    SM.customers_state = editor_current_state;
                                }
                                else if (SM.dashboard_state == "Orders") {
                                    SM.orders_state = editor_current_state;
                                }

                                SM.dashboard_state = this.value;

				cellClicked = false;
                                
                                batchupdate_reset(); // to reset the batch update window on store change
                                
				if(batchUpdateWindow.isVisible())
				batchUpdateWindow.hide();

				//set a store depending on the active Module
				if(SM.activeModule == 'Orders')
				store = ordersStore;
				else if(SM.activeModule == 'Products')
				store = productsStore;
				else
				store = customersStore;

				//storing the value of clicked module name
				if (this.value == 'Customers')
				clickedActiveModule = 'Customers';
				else if (this.value == 'Orders')
				clickedActiveModule = 'Orders';
				else
				clickedActiveModule = 'Products';

				var modifiedRecords = store.getModifiedRecords();
				if(!modifiedRecords.length) {
					showSelectedModule(clickedActiveModule);
				}else{
					var saveModification = function (btn, text) {
						if (btn == 'yes')
						saveRecords(store,pagingToolbar,jsonURL,editorGridSelectionModel);
						showSelectedModule(clickedActiveModule);
					};
					Ext.Msg.show({
						title: getText('Confirm Save'), 
						msg: getText('Do you want to save the modified records?'), 
						width: 400,
						buttons: Ext.MessageBox.YESNO,
						fn: saveModification,
						animEl: 'del',
						closable: false,
						icon: Ext.MessageBox.QUESTION
					});
				}
			}
		}
	});

//====== common ======

// ============ Customers ================

	countriesStore = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			idProperty: 'id',
			totalProperty: 'totalCount',
			root: 'items',
			fields: [{ name: 'id'  },
					 { name: 'name' },
					 { name: 'value'},
					 { name: 'country_id'}]
		}),
		autoDestroy: false,
		dirty: false
	});
	countriesStore.loadData(countries);
	
	reloadRegionCombo = function(curCountry) {
		var countryStoreArr = countriesStore.reader.jsonData.items;
		var countryIndex    = 0;
		//resetting the column value to empty of the current record
		if(curCountry != '') {
			for(var i=0;i<=countriesStore.reader.jsonData.totalCount;i++){
				var country = countryStoreArr[i].name; //not to include id w/o countyriID
				if(country == curCountry) {
					var curCountryId = countryStoreArr[i].country_id;
					(regions[curCountryId]!= undefined) ? regionsStore.loadData(regions[curCountryId]) : regionsStore.removeAll(true);
					break;
				}
			}
		}else {
			regionsStore.removeAll(true);
		}
	};
	
	// countries combo box
	var countriesCombo = new Ext.form.ComboBox({
		typeAhead: true,
	    triggerAction: 'all',
	    lazyRender:true,
	    editable: false,
		mode: 'local',
	    store:countriesStore,
	    value: 'value',
	    valueField: 'name',	    
	    displayField: 'name',
	    forceSelection: true,
	    listeners: {
	    	select: function() {
	    		// setting the region of current record to empty
	    		if(SM.curDataIndex == 'billingcountry')
	    			SM.activeRecord.set('billingstate','')
	    		else if(SM.curDataIndex == 'shippingcountry')
	    			SM.activeRecord.set('shippingstate','');

	    		var curCountry = this.value;
	    		reloadRegionCombo(curCountry);
	    	}
		}
	});
	
	regionsStore = new Ext.data.Store({
		reader: new Ext.data.JsonReader({
			idProperty: 'id',
			totalProperty: 'totalCount',
			root: 'items',
			fields: [{ name: 'id'  },
			{ name: 'name' },
			{ name: 'value'},
			{ name: 'region_id'}]
		}),
		autoDestroy: false,
		dirty: false
	});	

	var regionCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store:regionsStore,
		value: 'value',
		valueField: 'name',
		displayField: 'name',
		forceSelection: true
	});
	
	if(isWPSC37 == '1'){
		regionEditor = regionCombo;
	}else if(isWPSC38 == '1'){
		var regionEditor = new fm.TextField({
			allowBlank: true,
			allowNegative: false
		});
	}
	
        //Code to enable disabling any column to be moved to the place of the one which cannot be dragged
        Ext.CustomersColumnModel = Ext.extend(Ext.grid.ColumnModel, {
          moveColumn: function (oldIndex, newIndex) {

            if (newIndex == 15) {
              newIndex = 13;
            }

            var c = this.config[oldIndex];
            this.config.splice(oldIndex, 1);
            this.config.splice(newIndex, 0, c);
            this.dataMap = null;
            this.fireEvent("columnmoved", this, oldIndex, newIndex);
          }
        });
        
	var customersColumnModel = new Ext.CustomersColumnModel({	
		columns:[editorGridSelectionModel, //checkbox for
		{
			header: getText('First Name'), 
			id: 'billingfirstname',
			dataIndex: 'billingfirstname',
			tooltip: getText('Billing First Name'),
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 100
		},{
			header: getText('Last Name'),
			id: 'billinglastname',
			dataIndex: 'billinglastname',
			tooltip: getText('Billing Last Name'),
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 100
		},{
			header: getText('Email'),
			id: 'billingemail',
			dataIndex: 'billingemail',
			tooltip: getText('Email Address'),
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 150
		},{
			header: getText('Address'),
			id: 'billingaddress',
			dataIndex: 'billingaddress',
			tooltip: getText('Billing Address'),
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 170
		},{
			header: getText('Postal Code'), 
			id: 'billingpostcode',
			dataIndex: 'billingpostcode',
			tooltip: getText('Billing Postal Code'),
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 70
		},{
			header: getText('City'),
			id: 'billingcity',
			dataIndex: 'billingcity',
			tooltip: getText('Billing City'),
			align: 'left',
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 90
		},
		{
			header: getText('Region'),
			id: 'billingstate',
			dataIndex: 'billingstate',
			tooltip: getText('Billing Region'),
			width: 90
		},
		{
			header: getText('Country'),
			id: 'billingcountry',
			dataIndex: 'billingcountry',
			tooltip: getText('Billing Country'),
			width: 100
		},
		{
			header: getText('Last Order Total'),
			id: 'total_purchased', //@todo: change the id to Total_Purchased
			dataIndex: '_order_total',
			tooltip: getText('Last Order Total'),
			align: 'right',
			width: 70			
		},{
			header: getText('Last Order'), 
			id: 'last_order',
			dataIndex: 'Last_Order',
			tooltip: getText('Last Order Details'),
			width: 100			
		},{   
			header: getText('Phone Number'),
			id: 'billingphone',
			dataIndex: 'billingphone',
			tooltip: getText('Phone Number'),
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 150		
		},{
            header: getText('Total Number Of Orders'),
            id: 'total_count',
            dataIndex: 'count_orders',
            tooltip: getText('Total Number Of Orders'),
            editable: false,
            align: 'left',
            width: 70
        },{
            header: getText('Total Purchased'),
            id: 'sum_orders',
            dataIndex: 'total_orders',
            tooltip: getText('Sum Total Of All Orders'),
            editable: false,
            align: 'left',
            width: 90
        },{
            header: '',
            id: 'old_email',
            Fixed: true,
            sortable:false,
            menuDisabled : true,
            hideable: false,
            hidden: true,
            dragable: false,
            width: 50
        },{
            header: '',
            id: 'customer_scroll_wpsec',
            Fixed: true,
            sortable:false,
            menuDisabled : true,
            hideable: false,
            dragable: false,
            width: 23
		}],
		listeners: {
			hiddenchange: function( ColumnModel,columnIndex, hidden ){
                            state_apply = true;    
                        }
		},
		defaultSortable: true
	});
	
	var totPurDataType = '';	
	if (fileExists != 1) { 
		totPurDataType = 'string';
		customersColumnModel.columns[customersColumnModel.findColumnIndex('_order_total')].align = 'center';
		customersColumnModel.columns[customersColumnModel.findColumnIndex('Last_Order')].align = 'center';
                customersColumnModel.columns[customersColumnModel.findColumnIndex('count_orders')].align = 'center';
                customersColumnModel.columns[customersColumnModel.findColumnIndex('total_orders')].align = 'center';
	}else{
		totPurDataType = 'float';
	}
	
	// Data reader class to create an Array of Records objects from a JSON packet.
	var customersJsonReader = new Ext.data.customJsonReader({
		totalProperty: 'totalCount',
		root: 'items',
		fields:
		[
		{name:'id',type:'int'},		
		{name:'billingfirstname',type:'string'},		
		{name:'billinglastname',type:'string'},				
		{name:'billingaddress',type:'string'},
		{name:'billingcity', type:'string'},		
		{name:'billingstate', type:'string'},
		{name:'billingcountry', type:'string'},		
		{name:'billingpostcode',type:'string'},
		{name:'billingemail',type:'string'},
		{name:'billingphone', type:'string'},	
		{name:'_order_total',type:totPurDataType},
		{name:'Last_Order', type:'string'},		
		{name:'Old_Email_Id', type: 'string'},
        {name:'count_orders',type:totPurDataType},
        {name:'total_orders',type:totPurDataType}
		]
	});
	
	// create the Customers Data Store
	var customersStore = new Ext.data.Store({
		reader: customersJsonReader,
		proxy:new Ext.data.HttpProxy({
			// url:jsonURL
			url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		}),
		baseParams:{
			cmd: 'getData',
			active_module: 'Customers',
			start: 0,
			limit: limit,
			security: SM_NONCE,
			file:  jsonURL			
		},
		dirty:false,
		pruneModifiedRecords: true
	});
	
	customersStore.on('load', function () {
		editorGridSelectionModel.clearSelections();
		pagingToolbar.saveButton.disable();
	});

	showCustomersView = function(emailId){
		try{
			//initial steps when store: customers is loaded
			SM.activeModule = 'Customers';
			SM.dashboardComboBox.setValue(SM.activeModule);

			jQuery("#search_switch"). hide();

			jQuery("#sm_advanced_search_content").hide(); //Hiding the advanced search box
			jQuery( "#sm_advanced_search_or").unbind( "click" );

			if(cellClicked == false){
				ordersStore.baseParams.searchText = ''; //clear the baseParams for ordersStore
				SM.searchTextField.reset(); 			//to reset the searchTextField
			}

			SM.searchTextField.show();
            editorGrid.getTopToolbar().get('searchIconId').show();

			hidePrintButton();
			hideDeleteButton();
			hideAddProductButton();
			hideDuplicateButton();
			pagingToolbar.doLayout(true,true);
			
			for(var i=2;i<=8;i++)
			editorGrid.getTopToolbar().get(i).hide();
			editorGrid.getTopToolbar().get('incVariation').hide();
            // editorGrid.getTopToolbar().get('duplicateButton').hide();

			if(customersFields != 0)
			fieldsStore.loadData(customersFields);

			customersStore.setBaseParam('searchText',emailId);
			customersStore.load();
			pagingToolbar.bind(customersStore);

			editorGrid.reconfigure(customersStore,customersColumnModel);

			var firstToolbar 	  = batchUpdatePanel.items.items[0].items.items[0];
			var textfield    	  = firstToolbar.items.items[5];
			var countriesDropdown = firstToolbar.items.items[7];
			textfield.show();
			countriesDropdown.hide();
			weightUnitStore.loadData(countries);
		}catch(e){
			var err = e.toString();
			Ext.notification.msg('Error', err);
		}
	};
	
//	 ====== customers ======
	

// ======= orders ======
	var fromDateMenu = new Ext.menu.DateMenu({
		handler: function(dp, date){
			toComboSearchBox.reset();
			if ( fileExists != 1 ) {
				Ext.notification.msg('Smart Manager', getText('Filter through Date feature is available only in Pro version') );
				return;
			}
			fromDateTxt.setValue(date.format('M j Y'));
			searchLogic();
		},
		maxDate: now
	});

	var toDateMenu = new Ext.menu.DateMenu({
		handler: function(dp, date){
			toComboSearchBox.reset();
			if ( fileExists != 1 ) {
				Ext.notification.msg('Smart Manager', getText('Filter through Date feature is available only in Pro version') ); 
				return;
			}
			toDateTxt.setValue(date.format('M j Y'));
			searchLogic();
		},
		maxDate: now
	});

if(isWPSC38 == '1'){
	var orderStatusCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['internalname','label','value'],
			data: [
			['incomplete_sale',  'Incomplete Sale',  1],
			['order_received',   'Order Received',   2],
			['accepted_payment', 'Accepted Payment', 3],
			['job_dispatched',   'Job Dispatched',   4],
			['closed_order',     'Closed Order',     5],
			['declined_payment', 'Payment Declined', 6]
			]
		}),
		valueField: 'value',
		displayField: 'label'
	});
}else if(isWPSC37 == '1'){
	var orderStatusCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		mode: 'local',
		store: new Ext.data.ArrayStore({
			id: 0,
			fields: ['label','value'],
			data: [
			['Order Received',   1],
			['Accepted Payment', 2],
			['Job Dispatched',   3],
			['Closed Order',     4]
			]
		}),
		valueField: 'value',
		displayField: 'label'
	});
}


        //Code to enable disabling any column to be moved to the place of the one which cannot be dragged
        Ext.OrdersColumnModel = Ext.extend(Ext.grid.ColumnModel, {
          moveColumn: function (oldIndex, newIndex) {

            if (newIndex == 17) {
              newIndex = 16;
            }

            var c = this.config[oldIndex];
            this.config.splice(oldIndex, 1);
            this.config.splice(newIndex, 0, c);
            this.dataMap = null;
            this.fireEvent("columnmoved", this, oldIndex, newIndex);
          }
        });
        
	var ordersColumnModel = new Ext.OrdersColumnModel({	
		columns:[editorGridSelectionModel, //checkbox for
		{
			header: getText('Order Id'),
			id: 'id',
			dataIndex: 'id',
                        width: 75,
			tooltip: getText('Order Id')
		},{
			header: getText('Date / Time'),
			id: 'date',
			dataIndex: 'date',
			tooltip: getText('Date / Time'),
			width: 180
		},{
			header: getText('Name'), 
			id: 'name',
			dataIndex: 'name',
			tooltip: getText('Customer Name'),
			width: 350
		},{
			header: getText('Amount'),
			id: 'amount',
			dataIndex: 'amount',
			tooltip: getText('Amount'),
			align: 'right',
			renderer: amountRenderer,
			width: 100
		},{
			header: getText('Details'),
			id: 'details',
			dataIndex: 'details',
			tooltip: getText('Details'),
			width: 80
		},{
			header: getText('Track Id'), 
			id: 'track_id',
			dataIndex: 'track_id',
			tooltip: getText('Track Id'),
			align: 'left',
			editable: false,
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 70
		},{
			header: getText('Status'),
			id: 'order_status',
			dataIndex: 'order_status',
			tooltip: getText('Order Status'),
			width: 75,
			editable: true,
			editor: orderStatusCombo,
			renderer: Ext.util.Format.comboRenderer(orderStatusCombo)
		},{
			header: getText('Orders Notes'),
			id: 'notes',
			dataIndex: 'notes',
			tooltip: getText('Orders Notes'),
			width: 150,
			editable: false,
			editor: new fm.TextArea({				
				autoHeight: true
			})
		},{   
			header: getText('Shipping First Name'),
			id: 'shippingfirstname',
			dataIndex: 'shippingfirstname',
			tooltip: getText('Shipping First Name'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 130
		},{   
			header: getText('Shipping Last Name'),
			id: 'shippinglastname',
			dataIndex: 'shippinglastname',
			tooltip: getText('Shipping Last Name'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 130
		},{   
			header: getText('Shipping Address'),
			id: 'shippingaddress',
			dataIndex: 'shippingaddress',
			tooltip: getText('Shipping Address'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 200		
		},{
			header: getText('Shipping Postal Code'),
			id: 'shippingpostcode',
			dataIndex: 'shippingpostcode',
			tooltip: getText('Shipping Postal Code'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
					allowBlank: true,
					allowNegative: false
			}),
			width: 80
		},{   
			header: getText('Shipping City'), 
			id: 'shippingcity',
			dataIndex: 'shippingcity',
			tooltip: getText('Shipping City'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 100
		},
		{   
			header: getText('Shipping Region'),
			id: 'shippingstate',
			dataIndex: 'shippingstate',
			tooltip: getText('Shipping Region'),
			align: 'center',
			hidden: true,
			width: 100		
		},
		{
			header: getText('Shipping Country'),
			id: 'shippingcountry',
			dataIndex: 'shippingcountry',
			tooltip: getText('Shipping Country'),
			hidden: true,
			width: 120
		},
                {   
			header: getText('Phone Number'),
			id: 'billingphone',
			dataIndex: 'billingphone',
			tooltip: getText('Customer Phone Number'),
			align: 'left',
			hidden: true,
			width: 100		
		},{
                        header: '',
                        id: 'orders_scroll_wpsec',
                        width: 15,
                        Fixed: true,
                        sortable:false,
                        menuDisabled : true,
                        hideable: false,
                        dragable:false
                }],
		listeners: {
			hiddenchange: function( ColumnModel,columnIndex, hidden ){
                            state_apply = true;
                        }
		},
		defaultSortable: true
	});

	// Data reader class to create an Array of Records objects from a JSON packet.
	var ordersJsonReader = new Ext.data.customJsonReader({
		totalProperty: 'totalCount',
		root: 'items',
		fields:
		[
		{name:'id',type:'int'},
		{name:'customer_id',type:'int'},
		{name:'date',type:'string'},
		{name:'name',type:'string'},
		{name:'amount', type:'float'},
		{name:'details', type:'string'},
		{name:'track_id',type:'string'},
		{name:'order_status', type:'string'},
		{name:'notes', type:'string'},
		{name:'shippingfirstname', type:'string'},
		{name:'shippinglastname', type:'string'},
		{name:'shippingaddress', type:'string'},
		{name:'shippingcity', type:'string'},
		{name:'shippingcountry', type:'string'},
		{name:'shippingstate', type:'string'},  
		{name:'shippingpostcode', type:'string'},
                {name:'billingphone', type:'string'}
		]
	});
	
	// create the Orders Data Store
	var ordersStore = new Ext.data.Store({
		reader: ordersJsonReader,
		proxy:new Ext.data.HttpProxy({
			// url:jsonURL
			url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		}),
		baseParams:{
			cmd: 'getData',
			active_module: 'Orders',
			start: 0,
			limit: limit,
			security: SM_NONCE,
			file:  jsonURL
		},
		dirty:false,
		pruneModifiedRecords: true
	});

	ordersStore.on('load', function () {
		editorGridSelectionModel.clearSelections();
		pagingToolbar.saveButton.disable();
	});	

	
	showOrdersView = function(emailid){
		try{
			//initial steps when store: orders is loaded
			SM.activeModule = 'Orders';
			SM.dashboardComboBox.setValue(SM.activeModule);
			
			jQuery("#search_switch"). hide();
	
			jQuery("#sm_advanced_search_content").hide(); //hiding the advanced search box
			jQuery( "#sm_advanced_search_or").unbind( "click" );

			ordersColumnModel.setEditable(6,true);
			ordersColumnModel.setEditable(8,true);
			ordersColumnModel.setEditable(9,true);
			ordersColumnModel.setEditable(10,true);
			ordersColumnModel.setEditable(11,true);
			ordersColumnModel.setEditable(12,true);
			ordersColumnModel.setEditable(13,true);
			
			SM.searchTextField.show();
            editorGrid.getTopToolbar().get('searchIconId').show();
			
			if(cellClicked == false){
				SM.searchTextField.reset(); //to reset the searchTextField
				fromDateTxt.setValue(lastMonDate.format('M j Y'));
				toDateTxt.setValue(now.format('M j Y'));

				ordersStore.baseParams.searchText = ''; //clear the baseParams for ordersStore
				ordersStore.baseParams.fromDate  = lastMonDate.format('M j Y');
				ordersStore.baseParams.toDate = now.format('M j Y');
			}else{
				fromDateTxt.setValue(initDate.format('M j Y'));
				ordersStore.setBaseParam('searchText',emailid);
				SM.searchTextField.setValue(emailid);

				ordersStore.setBaseParam('searchText', SM.searchTextField.getValue());
				ordersStore.setBaseParam('fromDate', fromDateTxt.getValue());
				ordersStore.setBaseParam('toDate', toDateTxt.getValue());
			}

			if(ordersFields != 0)
			fieldsStore.loadData(ordersFields);
			
			hideAddProductButton();
			hideDuplicateButton();
			hideDeleteButton();
			
			showPrintButton();
			showDeleteButton();
			pagingToolbar.doLayout(true,true);
						
			for(var i=2;i<=8;i++)
			editorGrid.getTopToolbar().get(i).show();
			editorGrid.getTopToolbar().get('incVariation').hide();
                        // editorGrid.getTopToolbar().get('duplicateButton').hide();

			ordersStore.load();
			editorGrid.reconfigure(ordersStore,ordersColumnModel);
			pagingToolbar.bind(ordersStore);

			var firstToolbar 	   = batchUpdatePanel.items.items[0].items.items[0];
			var textfield 	 	   = firstToolbar.items.items[5];
			var weightUnitDropdown = firstToolbar.items.items[7];
			weightUnitDropdown.show();
			weightUnitStore.loadData(ordersStatus);
			textfield.hide();

		} catch(e) {
			var err = e.toString();
			Ext.notification.msg('Error', err);
		}
	};
	
	// ======= orders =====


	// ==== common ====
SM.searchTextField = new Ext.form.TextField({
	id: 'searchTextField',
	width: 400,
	cls: 'searchPanel',
	style: {
		fontSize: '14px',
		paddingLeft: '2px',
		width: '100%'
	},
	params: {
		cmd: 'searchText'
	},
	emptyText: getText('Search') + '...', 
	enableKeyEvents: true,
	listeners: {
		keyup: function () {/*
		if ( fileExists != 1 ) {
				Ext.notification.msg('Smart Manager', getText('Search feature is available only in Pro version') );
				return;
			}			*/
			//set a store depending on the active Module
			if(SM.activeModule == 'Orders')
			store = ordersStore;
			else if(SM.activeModule == 'Products')
			store = productsStore;
			else
			store = customersStore;		
			var modifiedRecords = store.getModifiedRecords();
			
			// make server request after some time - let people finish typing their keyword
			clearTimeout(search_timeout_id);
			search_timeout_id = setTimeout(function () {
			if(!modifiedRecords.length) {				
				 searchLogic();
			}else{
				var saveModification = function (btn, text) {
					if (btn == 'yes')
					saveRecords(store,pagingToolbar,jsonURL,editorGridSelectionModel);
					searchLogic();
				}
				Ext.Msg.show({
					title: getText('Confirm Save'),
					msg: getText('Do you want to save the modified records?'),
					width: 400,
					buttons: Ext.MessageBox.YESNO,
					fn: saveModification,
					animEl: 'del',
					closable: false,
					icon: Ext.MessageBox.QUESTION
				})
			}
		}, 1000);
	}}
});

var searchLogic = function () {
	//START setting the params to store if search fields are with values (refresh event)
	switch(SM.activeModule) {
		case 'Products':
		productsStore.setBaseParam('searchText', SM.searchTextField.getValue());
		break;
		case 'Orders':
		ordersStore.setBaseParam('searchText', SM.searchTextField.getValue());		
		ordersStore.setBaseParam('fromDate', fromDateTxt.getValue());
		ordersStore.setBaseParam('toDate', toDateTxt.getValue());
		break;
		default :
		customersStore.setBaseParam('searchText',SM.searchTextField.getValue());
	};
	//END setting the params to store if search fields are with values (refresh event)
	mask.show();
	var o = {
		// url: jsonURL,
		url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		method: 'post',
		callback: function (options, success, response) {
			
			var result = response.responseText;
				result = result.trim();
				result = SM.escapeCharacters(result);
			var myJsonObj = Ext.decode(result);
                        
                        if (true !== success) {
				Ext.notification.msg('Failed',response.responseText);
				return;
			}
			try {
				var records_cnt = myJsonObj.totalCount;
				if (records_cnt == 0) myJsonObj.items = '';
				if(SM.activeModule == 'Products')
					productsStore.loadData(myJsonObj);
				else if(SM.activeModule == 'Orders')
					ordersStore.loadData(myJsonObj);
				else
					customersStore.loadData(myJsonObj);
			} catch (e) {
				return;
			}
			mask.hide();
		},
		scope: this,
		params: {
			cmd: 'getData',
			active_module: SM.activeModule,
			searchText: SM.searchTextField.getValue(),
			fromDate: fromDateTxt.getValue(),
			toDate: toDateTxt.getValue(),
			incVariation:SM.incVariation,
			start: 0,
			limit: limit,
			viewCols: Ext.encode(productsViewCols),
			security: SM_NONCE,
			file:  jsonURL
		}
	};
	Ext.Ajax.request(o);
};
	
//store for first combobox(field combobox) of BatchUpdate window.
var fieldsStore = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		idProperty: 'id',
		totalProperty: 'totalCount',
		root: 'items',
		fields: [{ name: 'id' },
				 { name: 'name'	},
				 { name: 'type'	},
				 { name: 'value'}]
	}),
	autoDestroy: false,
	dirty: false
});
fieldsStore.loadData(productsFields);

//store for second combobox(actions combobox) of BatchUpdate window.
var actionStore = new Ext.data.ArrayStore({
	fields: ['id', 'name', 'value'],
	autoDestroy: false
});
actionStore.loadData(actions);

//store to populate category in the third combobox(category combobox) on selecting a category from first combobox(field combobox).
var categoryStore = new Ext.data.ArrayStore({
	fields: ['id', 'name'],
	autoDestroy: false
});

var postStatusStoreData = new Array();
    postStatusStoreData = [
                            ['publish', getText('Publish')],
                            ['pending', getText('Pending Review')],
                            ['draft', getText('Draft')],
                            ['private', getText('Private')]

                          ];

//store to populate weightUnits in fifth combobox(weightUnits combobox) on selecting 'weight' from first combobox(field combobox)
//and 'set to' from second combobox(actions combobox).
	weightUnitStore = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		idProperty: 'id',
		totalProperty: 'totalCount',
		root: 'items',
		fields: [{ name: 'id'  },
				{ name: 'name' },
				{ name: 'value'},
				{ name: 'country_id'}]
	}),
	autoDestroy: false,
	dirty: false
});
weightUnitStore.loadData(weightUnits);
// countries's store

//batch update window
var batchUpdateToolbarInstance = Ext.extend(Ext.Toolbar, {
	cls: 'batchtoolbar',
	constructor: function (config) {
		config = Ext.apply({
			items: [{
				xtype: 'combo',
				allowBlank: false,
				align: 'center',				
				store: fieldsStore,
				typeAhead: true,
				style: {
					fontSize: '12px',
					paddingLeft: '2px',
					verticalAlign: 'middle'
				},
				displayField: 'name',
				valueField: 'value',
				mode: 'local',
				cls: 'searchPanel',
				emptyText: getText('Select a field') + '...',
				triggerAction: 'all',
				editable: false,				
				selectOnFocus: true,
				listeners: {
					select: function () {
						var actions_index;
						var selectedFieldIndex = this.selectedIndex;
						
						if(SM.activeModule == 'Products')
							var field_type = SM['productsCols'][this.value].actionType;
						else
							var field_type = this.store.reader.jsonData.items[selectedFieldIndex].type;
						var field_name = this.store.reader.jsonData.items[selectedFieldIndex].name;

						var actionType = '';                                                
						var actionsData = new Array();
						var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboCategoriesActionCmp = toolbarParent.get(4);
						var setTextfield = toolbarParent.get(5);
						var comboActionCmp = toolbarParent.get(2);
						var comboWeightUnitCmp = toolbarParent.get(7);						
						var comboRegionCmp = toolbarParent.get(9);
                        var textState = toolbarParent.get(11);
                        var lblImg = toolbarParent.get(13);
                        var textArea = toolbarParent.get(15);
                        var comboFieldCmp = toolbarParent.get(0);
                        
                        comboActionCmp.show(); // As the same is hidden if the Image functionality not available
                                                
                        objRegExp = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;;
						regexError = getText('Only numbers are allowed'); 
                                                
							if(SM['productsCols'][this.value] != undefined ){
								var categoryActionType = SM['productsCols'][this.value].actionType;
							}							
							if (field_type == 'category' || categoryActionType == 'category_actions' || field_name == 'Publish') {
								setTextfield.hide();
                                textArea.hide();
								comboWeightUnitCmp.hide();
								comboCategoriesActionCmp.show();
								comboCategoriesActionCmp.reset();
                                lblImg.hide();
							}else if (field_name == 'Notes') {
								setTextfield.hide();
								comboWeightUnitCmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
                                textArea.show();
							}else if (field_type == 'string') {
								setTextfield.hide();
                            	textArea.hide();
								comboWeightUnitCmp.hide();
								comboCategoriesActionCmp.hide();
                            	lblImg.hide();
							}else if (field_name == 'Stock: Quantity Limited' || field_name == 'Stock: Inform When Out Of Stock' || field_name == 'Disregard Shipping' || actionType == 'YesNoActions') {								
								setTextfield.hide();
                                textArea.hide();
								comboWeightUnitCmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
							}else if (field_name == 'Weight' || field_name == 'Variations: Weight' || field_name == 'Height' || field_name == 'Width' || field_name == 'Length' ) {
								comboWeightUnitCmp.hide();
                                textArea.hide();
								setTextfield.show();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
							}else if(field_name == 'Orders Status' || field_name.indexOf('Country') != -1){
								if(field_name.indexOf('Country') != -1) {
                                    textState.emptyText="Enter State/Region...";
									actions_index = 'bigint';
									weightUnitStore.loadData(countries);
								}else{
									weightUnitStore.loadData(ordersStatus);
									actions_index = field_type;
								}
								setTextfield.hide();
                                textArea.hide();
								comboWeightUnitCmp.show();
                                lblImg.hide();

							}else if(field_type == 'YesNoActions'){
								setTextfield.hide();
                                textArea.hide();
                                lblImg.hide();
							}else if(field_name == 'Image'){
                                textArea.hide();
								if (IS_WP35) {
                                    setTextfield.hide();
                                    comboWeightUnitCmp.hide();
                                    comboCategoriesActionCmp.hide();
                                    lblImg.show();
                                }
                                else {
                                    comboFieldCmp.setValue(getText('Select a field') + '...');
                                    comboActionCmp.hide();
                                    setTextfield.hide();
                                    comboWeightUnitCmp.hide();
                                    comboCategoriesActionCmp.hide();
                                    Ext.notification.msg('Note', 'This feature is available from Wordpress 3.5 onwards');
                                }
							}else if (isWPSC3814 == '1' && field_name == 'Dimensions Unit') {
								weightUnitStore.loadData(dimensionUnits);
								setTextfield.hide();
                                textArea.hide();
								comboWeightUnitCmp.show();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
							}else {
								setTextfield.show();
                             if (field_type == 'blob' || field_type == 'modStrActions') {
									objRegExp = '';
									regexError = '';
								}
								comboWeightUnitCmp.hide();
								comboCategoriesActionCmp.hide();
								actions_index = field_type;
                                lblImg.hide();
                                textArea.hide();
							}
                                                        
                        var field_val = getText('Select a field') + '...';
                                                
						if(SM.activeModule == 'Orders' || SM.activeModule == 'Customers'){
	                        for (j = 0; j < actions[actions_index].length; j++) {
	                                actionsData[j] = new Array();
	                                actionsData[j][0] = actions[actions_index][j].id;
	                                actionsData[j][1] = actions[actions_index][j].name;
	                                actionsData[j][2] = actions[actions_index][j].value;
                            }
						actionStore.loadData(actionsData); // @todo: check whether used only for products or is it used for any other module?
						}else if(SM.activeModule == 'Products' && this.value != field_val){
							actionStore.loadData(actions[SM['productsCols'][this.value].actionType]);
						}
                                                
						setTextfield.reset();
						comboActionCmp.reset();
						comboWeightUnitCmp.reset();
						comboRegionCmp.hide();
                        textState.hide();
                        textArea.hide();
						
						// @todo apply regex accordign to the req
						setTextfield.regex = objRegExp;
						setTextfield.regexText = regexError;						
					}
				}
			}, '',
			{
				xtype: 'combo',
				width: 170,
				allowBlank: false,
				store: actionStore,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				displayField: 'name',
				valueField: 'value',
				mode: 'local',
				cls: 'searchPanel',
				emptyText: getText('Select an action') + '...',
				triggerAction: 'all',
				editable: false,
				selectOnFocus: true,
				listeners: {
					focus: function () {	
							var actionsData        = new Array();
							var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
							var comboFieldCmp      = toolbarParent.get(0);
							var comboActionCmp     = toolbarParent.get(2);
							var selectedValue      = comboFieldCmp.value;
							
							if(SM.activeModule == 'Orders' || SM.activeModule == 'Customers'){
								var selectedFieldIndex = comboFieldCmp.selectedIndex;
								var field_type         = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].type;
								var field_name         = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;
								var actions_index;

								actions_index = (field_type == 'category') ? field_type + '_actions' :((field_name.indexOf('Country') != -1) ? 'bigint' : field_type);
								(field_name.indexOf('Country') != -1) ? weightUnitStore.loadData(countries) : '';

								for (j = 0; j < actions[actions_index].length; j++) {
									actionsData[j] = new Array();
									actionsData[j][0] = actions[actions_index][j].id;
									actionsData[j][1] = actions[actions_index][j].name;
									actionsData[j][2] = actions[actions_index][j].value;
								}
								actionStore.loadData(actionsData);
							}else{
//								// on swapping between the toolbars	
                                actionStore.loadData( actions[SM['productsCols'][selectedValue].actionType] );
							}
						},
					beforeselect: function( combo, record, index ) {
							var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
							var comboFieldCmp      = toolbarParent.get(0);
							
							if ( comboFieldCmp.value.substr( 0, 14 ) == 'groupVariation' && index == 0 ) {
								return false;
							}
						},
					select: function() {
						var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboFieldCmp      = toolbarParent.get(0);
						var comboactionCmp     = toolbarParent.get(2);
						var comboWeightUnitCmp = toolbarParent.get(7);
						var selectedFieldIndex = comboFieldCmp.selectedIndex;
						var selectedValue      = comboFieldCmp.value;
                                                var comboactionvalue   = comboactionCmp.value;
                                                var setTextfield       = toolbarParent.get(5);
						var field_name = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;						

							if(wpscRunning == 1){

                                if(field_name == 'Price' || field_name == 'Sale Price') {
                                    setTextfield.show();
                                }

                                if (comboactionvalue == 'YES' || comboactionvalue == 'NO' || comboactionvalue == 'SET_TO_SALES_PRICE' || comboactionvalue == 'SET_TO_REGULAR_PRICE') {
                                    setTextfield.hide();
                                }
                                               

                                if (field_name == 'Weight' || field_name == 'Variations: Weight'||field_name == 'Height' ||field_name == 'Width' ||field_name == 'Length' ||field_name == 'Dimensions Unit') {
								if (field_name == 'Weight' || field_name == 'Variations: Weight') {
									weightUnitStore.loadData(weightUnits);
								}
								else if( (isWPSC3814 != '1' && (field_name == 'Height' ||field_name == 'Width' ||field_name == 'Length'))) {
									weightUnitStore.loadData(dimensionUnits);
								} else if (isWPSC3814 == '1' && field_name == 'Dimensions Unit') {
									setTextfield.hide();
									weightUnitStore.loadData(dimensionUnits);
									comboWeightUnitCmp.show();
									
								}
								if(comboactionvalue == 'SET_TO') {
									if (isWPSC3814 != '1' || (isWPSC3814 == '1' && field_name != 'Height' && field_name != 'Width' && field_name != 'Length')) {
										comboWeightUnitCmp.show();	
									} else {
										comboWeightUnitCmp.hide();
									}
	                            }
								else {
	                                comboWeightUnitCmp.hide();
	                            }
									
							}}
					}
				}
			},'',{
				xtype: 'combo',
				width: 170,
				allowBlank: false,
				store: categoryStore,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				displayField: 'name',
				valueField: 'id',
				mode: 'local',
				cls: 'searchPanel',
				emptyText: getText('Select a value') + '...',
				triggerAction: 'all',
				editable: false,
				forceSelection: false,
				hidden: true,
				selectOnFocus: true,
				listeners: {
					focus: function () {
						var actionsData = new Array();
						var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboFieldCmp = toolbarParent.get(0);
						var selectedFieldIndex = comboFieldCmp.selectedIndex;
						var selectedValue      = comboFieldCmp.value;

						if(SM.activeModule == 'Orders' || SM.activeModule == 'Customers'){
							var field_type = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].type;
							var field_name = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;
							var actions_index;
							
							(field_type == 'category') ? actions_index = field_type + '_actions' : actions_index = field_type;
							for (j = 0; j < actions[actions_index].length; j++) {
								actionsData[j] = new Array();
								actionsData[j][0] = actions[actions_index][j].id;
								actionsData[j][1] = actions[actions_index][j].name;
								actionsData[j][2] = actions[actions_index][j].value;
							}
							actionStore.loadData(actionsData);
							categoryStore.loadData(categories[comboFieldCmp.getValue()]);
						}else{
							var category = categories["category-"+SM['productsCols'][selectedValue].colFilter];
							var field_name = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;

							if ( field_name == 'Publish' ) {
                                categoryStore.loadData( postStatusStoreData );
                            } else {
                            	if ( category instanceof Object ) {
									var categoryData = [];
									for ( var catId in category  ) {
										if ( category[catId] != undefined ) {
											categoryData.push(category[catId]);
										}
									}
									categoryStore.loadData(categoryData);
								} else {
									categoryStore.loadData(category);
								}	
                            }
							
					    }
				    }
				}
			},{
				xtype: 'textfield',
				width: 170,
				allowBlank: true,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				enableKeyEvents: true,
				regex: objRegExp,
				regexText: regexError,
				displayField: 'fullname',
				emptyText: getText('Enter the value') + '...',
				cls: 'searchPanel',
				hidden: false,
				selectOnFocus: true
			}, '',
			{
				xtype: 'combo',
				allowBlank: false,
				typeAhead: true,
				hidden: false,
				width: 170,
				align: 'center',
				store: weightUnitStore,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				hidden: true,
				valueField: 'value',
				displayField: 'name',
				mode: 'local',
				cls: 'searchPanel',
				emptyText: getText('Select a value') + '...',
				triggerAction: 'all',
				editable: false,
				forceSelection: true,
				selectOnFocus: true,
				listeners: {
					select: function(){
							// this combo is used for weight unit, countries
							var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);
							var comboFieldCmp = toolbarParent.get(0);
							var selectedFieldIndex = comboFieldCmp.selectedIndex;
							var field_name = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;
							var comboRegionCmp = toolbarParent.get(9);
							var textState = toolbarParent.get(11);
							comboRegionCmp.reset();
							
							if(field_name.indexOf('Country') != -1) {
								var selectCountryIndex = this.selectedIndex;
								var countryId = this.store.data.items[selectCountryIndex].data['country_id'];
								textState.reset();
								if(regions[countryId]==undefined){
									regionsStore.removeAll(false);
									comboRegionCmp.hide();
									textState.show();
								}else{
									regionsStore.loadData(regions[countryId]);
									comboRegionCmp.show();
									textState.hide();
								}
							}
					}}
			},'',{
				xtype: 'combo',
				forceSelection: false,
				typeAhead: true,
				editable: false,
				allowBlank: false,
				hidden: false,
				width: 170,
				align: 'center',
				store: regionsStore,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				hidden: true,
				valueField: 'region_id',
				displayField: 'name',
				mode: 'local',
				cls: 'searchPanel',
				emptyText: getText('Select a value') + '...', 
				triggerAction: 'all',
				selectOnFocus: true,
				listeners: {
					focus: function(){
						if(isWPSC37 == '1'){
							var toolbarParent       = this.findParentByType(batchUpdateToolbarInstance, true);
							var comboCountryCmp     = toolbarParent.get(7);
							var selectedCountryName = comboCountryCmp.lastSelectionText;
							var countryData = comboCountryCmp.store.reader.jsonData;
							//comparing the countries name selected by user with the ones from datastore reader.
							for(var i=0;i<=countryData.totalCount;i++){
								if(selectedCountryName == countryData.items[i].name)
								countryId = countryData.items[i].country_id;
							}
							regionsStore.loadData(regions[countryId]);
						}
					}
				}
			},'',{
				xtype: 'textfield',
				width: 170,
				allowBlank: false,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				enableKeyEvents: true,
				emptyText: getText('Enter State/Region') + '...',
				cls: 'searchPanel',
				hidden: true,
				selectOnFocus: true
			},'',{
                            xtype: 'button',
                            icon: imgURL + 'batch_image.gif',
                            iconCls: 'x-batchimage',
                            tooltip: getText('Upload Image'),
                            image_id:'',
                            hidden: true,
                            handler: function (e) {
                                        var file_frame;
                                        
                                        // If the media frame already exists, reopen it.
                                        if ( file_frame ) {
                                          file_frame.open();
                                          return;
                                        }
                                        
                                        // Create the media frame.
                                        file_frame = wp.media.frames.file_frame = wp.media({
                                          title: jQuery( this ).data( 'uploader_title' ),
                                          button: {
                                            text: jQuery( this ).data( 'uploader_button_text' )
                                          },
                                          multiple: false  // Set to true to allow multiple files to be selected
                                        });
                                        
                                        // When an image is selected, run a callback.
                                        file_frame.on( 'select', function() {
                                          // We set multiple to false so only get one image from the uploader
                                            attachment = file_frame.state().get('selection').first().toJSON();
                                          
                                            e.image_id = attachment['id'];
                                            jQuery('.x-batchimage').css('background-image', 'url(' + attachment['url'] + ')');
                                            jQuery('.x-batchimage').css('background-size', '100% 100%');
                                        });
                                        
                                        file_frame.open();
                                }
                        }, '',{
				xtype: 'textarea',
				width: 170,
				allowBlank: true,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				enableKeyEvents: true,
				autoScroll: true,
				displayField: 'fullname',
				emptyText: getText('Enter the value') + '...',
				cls: 'searchPanel',
				hidden: true,
				selectOnFocus: true,
				listeners: {
					beforerender: function( cmp ) {
						cmp.emptyText = getText('Enter the value') + '...'; 
					}
				}
			}, '->',
			{
				// icon: imgURL + 'del_row.png',
				tooltip: getText('Delete Row'),
				id: 'bu_delete_row',
				handler: function () {
					toolbarCount--;
					var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);
					batchUpdatePanel.remove(toolbarParent);
				}
			}]
		}, config);
		batchUpdateToolbarInstance.superclass.constructor.call(this, config);
	}
});

var batchUpdateToolbar = new Ext.Toolbar({
	id: 'tl',
	cls: 'batchtoolbar',
	items: [new batchUpdateToolbarInstance(), '->',
	{
		text: getText('Add Row'), 
		tooltip: getText('Add a new row'),
		ref: 'addRowButton',
		id: 'bu_add_row_main',
		// icon: imgURL + 'add_row.png',
		handler: function () {
			var newBatchUpdateToolbar = new batchUpdateToolbarInstance();
			toolbarCount++;
			batchUpdatePanel.add(newBatchUpdateToolbar);
			batchUpdatePanel.doLayout();
                        var count_toolbar = toolbarCount-1;
                        var firstToolbar = batchUpdatePanel.items.items[count_toolbar].items.items[13];
                        firstToolbar.hide();
		}
	}]
});
batchUpdateToolbar.get(0).get(17).hide(); //hide delete row icon from first toolbar.

var batchUpdatePanel = new Ext.Panel({
	animCollapse: true,
	autoScroll: true,
	Height: 500,
	width: 900,
	bbar: [{text: getText('Reset'),
		id: 'resetButton',
		ref: 'resetButton',
		tooltip: getText('Reset all fields'),
                // icon: imgURL + '/default/grid/refresh.gif',
                disabled: false,
		listeners: { click: function () {
                        batchupdate_reset(); // to reset the batch update window on store change 
                }
            }
            }
            ,'->',
	{
		text: getText('Update'),
		id: 'updateButton',
		ref: 'updateButton',
		tooltip: getText('Apply all changes'),
		icon: sm_beta_imgURL + 'jqgrid/save_img-blue-15X15.png',
		disabled: false,
		listeners: { click: function () {
			var clickRadio = Ext.getCmp('updateItemsOrStore').getValue();
			var radioValue = clickRadio.inputValue;	
			var products_search_flag = false; // flag for all items in search result batch update				
			if(batchRadioToolbar.isVisible()){
				flag = 	1;
			} else {
				flag = 0;
			}
					
			if(SM.activeModule == 'Orders'){
				store = ordersStore;
				cm = ordersColumnModel;
			}else if(SM.activeModule == 'Customers'){
				store = customersStore;
				cm = customersColumnModel;
			}else{
				store = productsStore;
				cm = productsColumnModel;

				if (SM.advanced_search_query != '' || SM.searchTextField.getValue() != '') {
					products_search_flag = true;
				}

			}
			batchUpdateRecords(batchUpdatePanel,toolbarCount,cnt_array,store,jsonURL,batchUpdateWindow,radioValue,flag,pagingToolbar,products_search_flag,batch_limit);
		}}
	}]
});

batchUpdatePanel.add(batchUpdateToolbar);
batchUpdatePanel.items.items[0].items.items[0].cls = 'firsttoolbar';

var batchRadioToolbar = new Ext.Toolbar({
	height: 35,
	items: [
		{
			xtype: 'tbtext',
		    width: 90,
		    text: getText('Update') + '...'
		},new Ext.form.RadioGroup({
			id: 'updateItemsOrStore' ,
		    width: 400,
			height: 20,
		    items: [
		    	
		        {boxLabel: 'Selected items', name: 'rb-batch', inputValue: 1, checked: true},
		        {boxLabel: 'All items in store (including Variations)', name: 'rb-batch', id:'sm_batch_entire_store_option', inputValue: 2}
		    ]
		})        
	]
});

batchUpdateWindow = new Ext.Window({
	title: getText('Batch Update - available only in Pro version'),
	animEl: 'BU',
	collapsible:true,
	shadow : true,
	loadMask: batchMask,
	shadowOffset: 10,
	tbar: batchRadioToolbar,
	items: batchUpdatePanel,
	layout: 'fit',
	width: 810,
	height: 300,
	plain: true,
	closeAction: 'hide',
	listeners: {
		hide: function (e) {
			values = '';
			ids = '';
			batchUpdateWindow.hide();
		}
	}
});

var storeDetailsWindowState = function(obj,stateId){
	var q            = new Ext.state.CookieProvider();
	var thisObjState =  q.get(stateId);

	if(thisObjState != undefined){
		obj.setSize(thisObjState.width, thisObjState.height);
		obj.setPagePosition(thisObjState.x,thisObjState.y);
	}
};

// Order's billing details window
var billingDetailsIframe = function(recordId){
	var billingDetailsWindow = new Ext.Window({
		stateId : 'billingDetailsWindowWpsc',
		stateEvents : ['show','bodyresize','maximize'],
		stateful: true,
		title: 'Order Details',
		collapsible:true,
		shadow : true,
		shadowOffset: 10,
		width:500,
		height: 500,
		minimizable: false,
		maximizable: true,
		maximized: false,
		resizeable: true,
		listeners: { 
			maximize: function () {
				this.setPosition( 0, 30 );
			},
			show: function () {
				this.setPosition( 250, 30 );
			}
		},
		html: '<iframe src='+ ordersDetailsLink + '' + recordId +' style="width:100%;height:100%;border:none;">< p>' + getText('Your browser does not support iframes.') + '</p></iframe>' 
	});
	billingDetailsWindow.show();
};

var checkModifiedAndshowDetails = function(record,rowIndex){
	//set a store depending on the active Module
	if(SM.activeModule == 'Orders')
	store = ordersStore;
	else if(SM.activeModule == 'Products')
	store = productsStore;
	else
	store = customersStore;
	
	var modifiedRecords = store.getModifiedRecords();
	if(!modifiedRecords.length) {
		
		if(SM.activeModule == 'Customers')
			showOrderDetails(record,rowIndex);
		else if(SM.activeModule == 'Orders')
			showCustomerDetails(record,rowIndex);
		
	}else{
		
		var saveModification = function (btn, text) {
			if (btn == 'yes')
			saveRecords(store,pagingToolbar,jsonURL,editorGridSelectionModel);
			store.load();
			
			if(SM.activeModule == 'Customers')
				showOrderDetails(record,rowIndex);
			else if(SM.activeModule == 'Orders')
				showCustomerDetails(record,rowIndex);
		};
		Ext.Msg.show({
			title: getText('Confirm Save'),
			msg: getText('Do you want to save the modified records?'),
			width: 400,
			buttons: Ext.MessageBox.YESNO,
			fn: saveModification,
			animEl: 'del',
			closable: false,
			icon: Ext.MessageBox.QUESTION
		});
	}
};

//extracting the email address from the records and show customer details of the passed email address.
//Its done by just setting the search textfield value to the extracted email address.
var showCustomerDetails = function(record,rowIndex){
	//START extracting emailId
	var name_emailid     = record.json.name;
        var reg_email        = record.json.reg_email;
	var name_emailid_arr = name_emailid.split(' ');
	var mix_emailId      = Ext.util.Format.stripTags(name_emailid_arr[name_emailid_arr.length -1]);
	var emailId          = mix_emailId.substring(1,mix_emailId.length-1);
	// END
	
        if (reg_email != "") {
            emailId = reg_email;
        }
        
        batchUpdateWindow.loadMask.show();
	clearTimeout(SM.colModelTimeoutId);
	SM.colModelTimeoutId = showCustomersView.defer(100,this,[emailId]);
	SM.searchTextField.setValue(emailId);
};


        //code to get the width of SM w.r.to width of the browser
        
        var wWidth = 0,
        	hHeight = 0;

        //code to handle the sizing od the Smart Manager Grid w.r.to collapse menu
        if ( document.documentElement.offsetWidth > 557 ) {
	        // if ( !jQuery(document.body).hasClass('folded') ) {
	        //     wWidth  = document.documentElement.offsetWidth - 183;
	        // }
	        // else {
	        //     wWidth  = document.documentElement.offsetWidth - 67;
	        // }

	        wWidth  = document.documentElement.offsetWidth - 67;
	        hHeight  = document.documentElement.offsetWidth - 120;
	    } else {
	    	wWidth = 1000;
	    	hHeight = 1000;
	    }
    
        var variation_state=""; // Variable to handle the incVariation checkbox state
        var column_move = false;
        var flag_save_lite = 0;
		var row_index_save_lite = new Array();


	// Grid panel for the records to display
	editorGrid = new Ext.grid.EditorGridPanel({
	stateId : SM.dashboardComboBox.value.toLowerCase()+'EditorGridPanelWpsc',
	stateEvents : ['viewready','beforerender','columnresize', 'columnmove', 'columnvisible', 'columnsort','reconfigure'],
	stateful: true,
	store: eval(SM.dashboardComboBox.value.toLowerCase()+'Store'),
	cm: eval(SM.dashboardComboBox.value.toLowerCase()+'ColumnModel'),
	renderTo: 'editor-grid',
        width : wWidth,
	height: hHeight,
	stripeRows: true,
	frame: true,
	loadMask: mask,
	columnLines: true,
	clicksToEdit: 1,
	forceLayout: true,
	bbar: [pagingToolbar],
	viewConfig: { forceFit: true },
	sm: editorGridSelectionModel,
	tbar: [ SM.dashboardComboBox,
			{xtype: 'tbspacer',id:'afterComboTbspacer', width: 15},
		   {text:'From:', id: 'fromTextId'},fromDateTxt,{menu: fromDateMenu, id:'fromDateMenuId'},
			{text:'To:', id:'toTextId'},toDateTxt,{menu: toDateMenu, id:'toDateMenuId'},
			toComboSearchBox,
			{xtype: 'tbspacer', id:'afterDateMenuTbspacer', width: 15},
			SM.searchTextField,{ id:'searchIconId' },

			//Advanced Search Box [only for Products]
			// '<div id="sm_advanced_search_content" style="background-color:#d0def0;margin-top: -5px;margin-left: -10px;float:left;">'+
			'<div id="sm_advanced_search_content" style="margin-top: -5px;margin-left: -10px;float:left;">'+
			'<div style="width: 100%;"> <div id="sm_advanced_search_box" > <div id="sm_advanced_search_box_0" style="width:80%;margin-left:7px;margin-bottom:5px"> </div>'+
			'<input type="text" id="sm_advanced_search_box_value_0" name="sm_advanced_search_box_value_0" hidden> </div>'+ 
			'<input type="text" id="sm_advanced_search_query" hidden>'+
			'<span id="sm_advanced_search_or" style="float: left;margin-top: -23px;margin-left: 83%;opacity: 0.75;cursor: pointer;" title="Add Another Condition"> </span>'+
			'<button id="sm_advanced_search_submit" style="float: left;margin-top: -28px;margin-left: 88%;cursor: pointer;"> Search </button>'+
			'</div>',

			{xtype: 'tbspacer', id:'afterDateMenuTbspacer', width: 10},

			'<label title="Switch to simple search" id="search_switch" style="display:none;"> Simple Search </label>',

//			{xtype: 'tbspacer',width: 10, id:'afterSearchId'}
			'->',
			{ 
				xtype: 'checkbox',
				id:'incVariation',
				name: 'incVariation',
				stateEvents : ['added','check'],
				stateful: true,

                                applyState: function(state) {
                                    if(state){
                                        this.setValue(state);
                                        variation_state = state;
                                    }
                                },

                                initState : function(){
                                      this.applyState(SM.variation_state);
                                },

                                saveState : function(){
                                      SM.variation_state = this.checked;
                                },


			 	boxLabel: getText('Show Variations'),
			 	listeners: {
			 		check : function(checkbox, bool) {
			 			if ( SM.activeModule == 'Products' ) {
				 			if ( isWPSC37 == true ) {
				 				Ext.notification.msg('Smart Manager', getText('Show Variations feature is available only for WPeC 3.8+') );
				 			}
                            mask.show();
			 				SM.incVariation  = bool;
			 				productsStore.setBaseParam('incVariation', SM.incVariation);
			 				getVariations(productsStore.baseParams,productsColumnModel,productsStore);
			 			}
			 		}
			 	}
			}
                        ],
	scrollOffset: 50,
	listeners: {
		beforerender: function(grid) {
			var object = {
						// url:jsonURL
						url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
						,method:'post'
						,callback: function(options, success, response)	{
							var myJsonObj = Ext.decode(response.responseText);
							var dashboardComboStore = new Array();
							for ( var i = 0; i < myJsonObj.length; i++) {
								if ( myJsonObj[i].indexOf("_") != -1) {
									dashboardComboStore.push( new Array( i, myJsonObj[i].slice( 0,9 ), getText( myJsonObj[i].slice( 0,9 ) ) ) );
									dashboardComboStore.push( new Array( i+1, myJsonObj[i].slice( 10 ), getText( myJsonObj[i].slice( 10 ) ) ) );
								} else {
									dashboardComboStore.push( new Array( i, myJsonObj[i], getText( myJsonObj[i] ) ) );
								}
								
							}
							if ( dashboardComboStore < 1) {
								Ext.Msg.show({
									title: getText('Access Denied'),
									msg: getText('You don\'t have sufficient permission to view this page'),
									buttons: Ext.MessageBox.OK,
									fn: function() {
										location.href = 'index.php';
									},
									icon: Ext.MessageBox.WARNING
								});
							} else {
								SM.dashboardComboBox.store.loadData(dashboardComboStore);
							}
						}
						,scope: SM.dashboardComboBox
						,params:
						{
							cmd:'getRolesDashboard',
							security: SM_NONCE,
							file:  jsonURL
						}};
				Ext.Ajax.request(object);
			
		},
		cellclick: function(editorGrid, rowIndex, columnIndex, e) {
			try{
				var record  = editorGrid.getStore().getAt(rowIndex);
				cellClicked = true;
				var editLinkColumnIndex   	  = productsColumnModel.findColumnIndex('edit_url'),
					editImageColumnIndex   	  = productsColumnModel.findColumnIndex(SM.productsCols.image.colName),
					prodTypeColumnIndex       = productsColumnModel.findColumnIndex('type'),
					totalPurchasedColumnIndex = customersColumnModel.findColumnIndex('_order_total'),
					lastOrderColumnIndex      = customersColumnModel.findColumnIndex('Last_Order'),
					nameLinkColumnIndex       = ordersColumnModel.findColumnIndex('name'),
					orderDetailsColumnIndex   = ordersColumnModel.findColumnIndex('details');					
					publishColumnIndex        = productsColumnModel.findColumnIndex(SM.productsCols.publish.colName);

				if(SM.activeModule == 'Orders'){
					if(columnIndex == orderDetailsColumnIndex){
					// showing order details of selected id by loading the web page in a Ext window instance.
						billingDetailsIframe(record.id);
					}else if(columnIndex == nameLinkColumnIndex){
					// check for any unsaved data and show details of the respective id sent as argument.
						checkModifiedAndshowDetails(record,rowIndex);
					}
					
				// Show WPeC's product edit page in a Ext window instance.
				}else if(SM.activeModule == 'Products'){
					if(columnIndex == editLinkColumnIndex) {
						var productsDetailsWindow = new Ext.Window({
							stateId : 'productsDetailsWindowWpsc',
							collapsible:true,
							shadow : true,
							shadowOffset: 10,
							stateEvents : ['show','bodyresize','maximize'],
							stateful: true,
							title: getText('Products Details'), 
							width:500,
							height: 600,						
							minimizable: false,
							maximizable: true,
							maximized: false,
							resizeable: true,
							shadow : true,
							shadowOffset : 10,
							animateTarget:'editLink',
							listeners: { 
								show: function(t){ 
									storeDetailsWindowState(t,t.stateId); 
								},
								maximize: function () {
									this.setPosition( 0, 30 );
								},
								show: function () {
									this.setPosition( 250, 30 );
								}	
							},
							html: '<iframe src='+ productsDetailsLink + record.id +'&action=edit style="width:100%;height:100%;border:none;">< p >' + getText('Your browser does not support iframes.') + '</p></iframe>' 
						});
						// To disable Product's details window for product variations
						if(record.get('post_parent') == 0){
							productsDetailsWindow.show('editLink');
						}
						
					// show Inherit option only for the product variations otherwise show only Published & Draft 	
					}else if(columnIndex == publishColumnIndex){						
							if(record.get('post_parent') == 0){
								productsColumnModel.setEditable(columnIndex,true);
								productsColumnModel.getColumnById('publish').editor = newProductStatusCombo;
							}else{
								productsColumnModel.getColumnById('publish').editor = productStatusCombo;
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if ( columnIndex == editImageColumnIndex ) {

						if ( isWPSC37 != 1 ) {
							if (IS_WP35) {
                                var file_frame;
                                
                                // If the media frame already exists, reopen it.
                                if ( file_frame ) {
                                  file_frame.open();
                                  return;
                                }
                                
                                // Create the media frame.
                                file_frame = wp.media.frames.file_frame = wp.media({
                                  title: jQuery( this ).data( 'uploader_title' ),
                                  button: {
                                    text: jQuery( this ).data( 'uploader_button_text' )
                                  },
                                  multiple: false  // Set to true to allow multiple files to be selected
                                });
                                
                                // When an image is selected, run a callback.
                                file_frame.on( 'select', function() {
                                  // We set multiple to false so only get one image from the uploader
                                    attachment = file_frame.state().get('selection').first().toJSON();
                                  
                                    e.image_id = attachment['id'];
                                    
                                   var object = {
										url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
										,method:'post'
										,callback: function(options, success, response)	{
											var myJsonObj = Ext.decode(response.responseText);
											record.set("thumbnail", myJsonObj);
											record.commit();
										}
										,scope:this
										,params:
										{
											cmd:'editImage',
											thumbnail_id: attachment['id'],
											id: record.id,
											incVariation: SM.incVariation,
											security: SM_NONCE,
											file:  jsonURL
										}
									};
									Ext.Ajax.request(object);
                                });
                                
                                file_frame.open();
                        	} else {
                        		var productsImageWindow = new Ext.Window({
									collapsible:true,
									shadow : true,
									shadowOffset: 10,
									title: getText('Manage your Product Images'),
									width: 700,
									height: 400,
									minimizable: false,
									maximizable: true,
									maximized: false,
									resizeable: true,
									animateTarget: 'image',
									listeners: {
										maximize: function () {
											this.setPosition( 0, 30 );
										},
										show: function () {
											this.setPosition( 250, 30 );
										},
										close: function() {
											var object = {
												// url:jsonURL
												url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
												,method:'post'
												,callback: function(options, success, response)	{
													var myJsonObj = Ext.decode(response.responseText);
													record.set("thumbnail", myJsonObj);
													record.commit();
												}
												,scope:this
												,params:
												{
													cmd:'editImage',
													id: record.id,
													incVariation: SM.incVariation,
													security: SM_NONCE,
													file:  jsonURL
												}
											};
											Ext.Ajax.request(object);
										}
									},
									html: '<iframe src="'+ site_url + '/wp-admin/media-upload.php?post_id=' + record.id +'&type=image&tab=library&" style="width:100%;height:100%;border:none;"><p> ' + getText('Your browser does not support iframes.') + '</p></iframe>'
								});
								productsImageWindow.show('image');
                        	}
						}
					}
				}
				else if(SM.activeModule == 'Customers'){
					if(fileExists == 1){
						if(columnIndex == totalPurchasedColumnIndex){
							checkModifiedAndshowDetails(record,rowIndex);
						}else if(columnIndex == lastOrderColumnIndex){
							billingDetailsIframe(record.json.last_order_id);
						}
					}
				}
			}catch(e) {
				var err = e.toString();
				Ext.notification.msg('Error', err);
			}
		},
		// Fires before a cell is clicked
		// depending on the selected country load the corresponding regions in the region combo box
		cellmousedown : function(editorGrid,rowIndex, columnIndex, e) {
			SM.activeRecord = editorGrid.getStore().getAt(rowIndex);
			// Get field name for the column
			SM.curDataIndex = editorGrid.getColumnModel().getDataIndex(columnIndex);
			var curCountry;
			
			if(SM.activeModule == 'Customers'){
				if(fileExists == 1){
					var bill_country = SM.activeRecord.data['billingcountry'];
					var curCountry;
					    
						if(SM.curDataIndex == 'billingcountry' || SM.curDataIndex == 'billingstate') {
							curCountry = bill_country;
						}
						reloadRegionCombo(curCountry);
				}
			}else if(SM.activeModule == 'Orders') {
				var ship_country = SM.activeRecord.data['shippingcountry'];
				
				if(SM.curDataIndex == 'shippingcountry' || SM.curDataIndex == 'shippingstate') {
					 curCountry = ship_country;
				}
				reloadRegionCombo(curCountry);
			}
		},
		// Fires when the grid view is available.
		// This happens only for the first time when the page is rendered with the editorgrid panel.
		// From here the flow of the code starts.

		viewready: function(grid){
			showSelectedModule( SM.dashboardComboBox.getValue() );
            SM.dashboardComboBox.setValue( getText( SM.dashboardComboBox.getValue() ) );

            jQuery( "#dashboardComboBox" ).wrap( "<label id='dashboardComboBox_lbl'></label>" );
            jQuery( "#dashboardComboBox_lbl" ).next('img').remove();
            jQuery( "#pagingToolbar" ).css({'border': 0, 'background-image' : 'none'});

            //Fix for WP4.4
			jQuery("#editor-grid").find('.x-panel-bwrap').css('overflow','visible');
		},

        applyState : function(state){
            editorGrid.loadMask.show();

            if (SM.activeModule == "Products") {
                products_hidden_state = false;
            }
            else {
                products_hidden_state = true;
            }

            var cm = this.colModel,
                cs = state.columns,
                store = this.store,
                s,
                c,
                c_old,
                colIndex,
                hidden_colIndex,
                hidden_state = false;

                if (refresh_state === false) {

                    if(cs){
                        for(var i = 0, len = cs.length; i < len; i++){
                            s = cs[i];
                            c = cm.getColumnById(s.id);
                            if(c){
                                colIndex = cm.getIndexById(s.id);

                                c_old =  c['hidden'];

                                cm.setState(colIndex, {
                                    hidden: s.hidden,
                                    width: s.width,
                                    sortable: s.sortable
                                });

                                if(colIndex != i){
                                    column_move = true;
                                    cm.dataMap = null;

                                    var c1 = cm.config[colIndex];
                                    cm.config.splice(colIndex, 1);
                                    cm.config.splice(i, 0, c1);
                                }

                                //Code to handle the hiddenchange event
                                if(c_old !== true && c['hidden'] === true) {
                                    cm.totalWidth = 0;
                                    hidden_state = true;
                                    hidden_colIndex = colIndex;
                                }
                            }
                        }
                    }

                    if(store){
                        s = state.sort;
                        if(s){
                            store[store.remoteSort ? 'setDefaultSort' : 'sort'](s.field, s.direction);
                        }
                        s = state.group;
                        if(store.groupBy){
                            if(s){
                                store.groupBy(s);
                            }else{
                                store.clearGrouping();
                            }
                        }

                    }
                    var o = Ext.apply({}, state);
                    delete o.columns;
                    delete o.sort;

                    Ext.grid.GridPanel.superclass.applyState.call(this, o);
                    this.view.refresh(true);
                    
                    setInterval(function(){state_update()}, 60000);
                }

                editorGrid.loadMask.hide();
                if (variation_state == "true" && SM.activeModule == "Products") {
                    variation_state = "false";
                    editorGrid.loadMask.show();
                }
        },

		// Fires when the grid is reconfigured with a new store and/or column model.
		// state of the editor grid is captured and applied to back to the grid.
        reconfigure : function(grid,store,colModel ){
//            editorGrid.view.refresh(true);
            editorGrid.loadMask.show();
		
            state_apply = true;

            if (SM.dashboard_state == "Products") {
                if (SM.products_state != "" && SM.products_state != null) {
                    SM.editor_state = SM.products_state;
                }
                else {
                    SM.editor_state = productsColumnModel.columns;
                }
            }
            else if (SM.dashboard_state == "Customers") {
                if (SM.customers_state != "" && SM.customers_state != null) {
                    SM.editor_state = SM.customers_state;
                }
                else {
                    SM.editor_state = customersColumnModel.columns;
                }
                
            }
            else if (SM.dashboard_state == "Orders" ) {
                if (SM.orders_state != "" && SM.orders_state != null) {
                    SM.editor_state = SM.orders_state;
                }
                else {
                    SM.editor_state = ordersColumnModel.columns;
                }
            }
                
            refresh_state = false;

            editorGrid.fireEvent('applyState',SM.editor_state);
        },

         columnresize: function(e) {
            state_apply = true;
        },

        columnmove: function(e) {
            state_apply = true;
        },

   
        beforeedit: function(e) {

        	if(flag_save_lite == 0) {
        		row_index_save_lite[e.row]= 1;
        	}
        	
        	if(SM.activeModule == 'Orders')
			store = ordersStore;
			else if(SM.activeModule == 'Products')
			store = productsStore;
			else
			store = customersStore;
			
			var modifiedRecords = store.getModifiedRecords();

			if( modifiedRecords.length >= updated && !( row_index_save_lite[e.row] ) && ( fileExists == 0 ) ) {
				Ext.notification.msg('Note', 'For editing more records upgrade to Pro');
				return false;
			} else {
				return true;
			}
        },

        // after each edit record enable the save button.
        afteredit: function(e) {

        		if (flag_save_lite > 0) {
        			row_index_save_lite[e.row]= 1;	
        		}
        		
        		flag_save_lite++;	
                pagingToolbar.saveButton.enable();
        }
    }
});


	
for(var i=2;i<=8;i++)
editorGrid.getTopToolbar().get(i).hide();
SM.typeColIndex   = productsColumnModel.findColumnIndex(SM.productsCols.post_parent.colName);

//For pro version check if the required file exists
if(fileExists == 1){
	batchUpdateWindow.title = getText('Batch Update');
}else{	
	batchUpdateRecords = function () {
		Ext.notification.msg('Smart Manager', getText('Batch Update feature is available only in Pro version') );
	};
	
}

	}catch(e){
		var err = e.toString();
		Ext.notification.msg('Error', err);
		return;
	}
});