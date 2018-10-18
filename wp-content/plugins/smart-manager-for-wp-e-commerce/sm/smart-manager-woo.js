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
	attribute          = new Array(),
	cellClicked        = false,  	  //flag to check if any cell is clicked in the editor grid.
	search_timeout_id  = 0, 		  //timeout for sending request while searching.
	colModelTimeoutId  = 0, 		  //timeout to reconfigure the grid.
	editorGrid         = '',
	weightUnitStore    = '',
	showOrdersView     = '',
	countriesStore     = '',
    hidden_state       = false,
    sm_prod_custom_cols_formatted = new Object(); // object for storing the formatted custom cols names



Ext.onReady(function () {

	Date.prototype.format = Date.prototype.dateFormat;

	var now 		      = new Date();
	var lastMonDate       = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate() + 1);
	var search_timeout_id = 0; 			//timeout for sending request while searching.
	var updated			  = parseInt( updated_data );
	var dateFormat        = 'M d Y';
	var limit 		   	  = parseInt( sm_record_limit );		  //per page records limit.
	var dup_limit 	  	  = parseInt( sm_dup_limit );		  //duplicate products limit.
	var batch_limit 	  = parseInt( sm_batch_limit );		  //batch products limit.

	try{
		if(wpsc_woo != 1){
			//Stateful
//			Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
//				expires: new Date(new Date().getTime()+(1000*60*60*24*30)) //30 days from now
//			}));
		}
                      
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
    SM.editor_state = "";
    SM.search_type = "";
    SM.advanced_search_query = new Array();
        
        
	
	var actions = new Array();
	
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

        actions['salesprice_actions']   =         [[0, getText('set to'), 'SET_TO'],
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


	// Code for defining the renderer for amount field
	var amountRenderer = '';

	if (sm_amount_decimal_precision != '') {

		var decimal_format='0,0';

		for(var i=0;i<sm_amount_decimal_precision;i++) {
			if (i == 0) {
				decimal_format += '.';				
			}

			decimal_format += '0';
		}

		amountRenderer = Ext.util.Format.numberRenderer(decimal_format);
	}
	

	var numeric_renderer = function(decimal_precision) {
		if (decimal_precision == '') {
			decimal_precision = 2;
		}

		var decimal_format='0,0';

		for(var i=0;i<decimal_precision;i++) {
			if (i == 0) {
				decimal_format += '.';				
			}

			decimal_format += '0';
		}

		return Ext.util.Format.numberRenderer(decimal_format);
	}

	//number format in which the amounts in the grid will be displayed.

	// var amountRenderer = Ext.util.Format.numberRenderer('0,0.00'),
		
    //setting Date fields.
	var	fromDateTxt    = new Ext.form.TextField({emptyText:'From Date',readOnly: true,width: 80, id:'fromDateTxtId'}),
		toDateTxt      = new Ext.form.TextField({emptyText:'To Date',readOnly: true,width: 80, id:'toDateTxtId'
	}),
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
        value: 'Select Date',
		displayField: 'name',
		valueField: 'value',
		triggerAction: 'all',
		editable: false,
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

				}else{
					proSelectDate(dateValue);
					searchLogic();
				}
				
			}

	}
});


var taxStatusStoreData = new Array();
	taxStatusStoreData = [
							[ 'taxable', getText('Taxable') ],
							[ 'shipping', getText('Shipping only') ],
							[ 'none', getText('None') ]
						 ];

var visibilityStoreData = new Array();
	
	if( SM_IS_WOO30 == 'true' ) {
		visibilityStoreData = [
	                            ['visible', getText('Visible')],
	                            ['catalog', getText('Catalog')],
	                            ['search', getText('Search')],
	                            ['hidden', getText('Hidden')]
	                          ];
	} else {
		visibilityStoreData = [
	                            ['visible', getText('Catalog & Search')],
	                            ['catalog', getText('Catalog')],
	                            ['search', getText('Search')],
	                            ['hidden', getText('Hidden')]
	                          ];	
	}

var postStatusStoreData = new Array();
    postStatusStoreData = [
                            ['publish', getText('Publish')],
                            ['pending', getText('Pending Review')],
                            ['draft', getText('Draft')],
                            ['private', getText('Private')]
                          ];
                          

//Products custom columns bool type
var trueFalseCombo_inline = new Ext.form.ComboBox({
	typeAhead: true,
	triggerAction: 'all',
	lazyRender:true,
	editable: false,
	mode: 'local',
	store: new Ext.data.ArrayStore({
		id: 0,
		fields: ['value','name'],
		data: [['true', 'True'], ['false', 'False']]
	}),
	valueField: 'value',
	displayField: 'name'
});

//Coupons == combo box consisting of yes and no values for inline editing
var yesNoCombo_inline = new Ext.form.ComboBox({
	typeAhead: true,
	triggerAction: 'all',
	lazyRender:true,
	editable: false,
	mode: 'local',
	store: new Ext.data.ArrayStore({
		id: 0,
		fields: ['value','name'],
		data: [['yes', 'Yes'], ['no', 'No']]
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
	proSelectDate = function (dateValue){
		
	var fromDate,toDate,
		now = new Date();


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
						if (SM.activeModule != 'Coupons') {
							pagingToolbar.batchButton.enable();
						}
						
                    	// editorGrid.getTopToolbar().get('duplicateButton').enable();	
                    	if(pagingToolbar.hasOwnProperty('printButton'))
							pagingToolbar.printButton.enable();
					}					
					
					if(pagingToolbar.hasOwnProperty('duplicateButton'))
					pagingToolbar.duplicateButton.enable();

					if(pagingToolbar.hasOwnProperty('deleteButton'))
					pagingToolbar.deleteButton.enable();
					
					
				} else {					
					
					if (SM.activeModule != 'Coupons') {
						pagingToolbar.batchButton.disable();
					}
					
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
            
            if (state_apply === true && SM.activeModule != "Coupons") {

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
        jQuery(document).ready(function($)
        {	
          $.ajax({
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

//                    SM.editor_state = Ext.decode(myJsonObj[SM.dashboard_state]);

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

			$( "#dashboardComboBox" ).wrap( "<label id='dashboardComboBox_lbl'></label>" );

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
				text      : getText('Add product'),
				tooltip   : getText('Add a new product'),
				// icon      : imgURL + 'add.png',
				disabled  : true,
				hidden    : false,
				id        : 'addProductButton',
				ref 	  : 'addProductButton',
				listeners : {
					click : function() {
							productsColumnModel.getColumnById('publish').editor = newProductStatusCombo;
                            productsColumnModel.getColumnById('visibility').editor = visibilityCombo;
                            productsColumnModel.getColumnById('taxStatus').editor = taxStatusCombo;
                            productsColumnModel.getColumnById('product_type').editor = productTypeCombo;

                            if( SM_IS_WOO30 == 'true' ) {
								productsColumnModel.getColumnById('featured').editor = yesNoCombo_inline;
							}

						if(fileExists == 1){
							addProduct(productsStore, cnt_array, cnt, newCatName);
						}else{
							Ext.notification.msg('Smart Manager', getText('Add product feature is available only in Pro version')); 
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
											// Ext.notification.msg('Smart Manager', getText('Duplicate Product feature is available only in Pro version') ); 
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
											// Ext.notification.msg('Smart Manager', getText('Duplicate Store feature is available only in Pro version') ); 
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
	var wooShowPrintButton = function(){
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
							Ext.notification.msg('Smart Manager', getText('Print Preview feature is available only in Pro version') );
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

	var showBatchUpdateButton = function(){
		if(typeof pagingToolbar.batchButton == 'undefined' && typeof Ext.getCmp('beforeBatchSeparator') == 'undefined'){
			var beforeBatchSeparator = new Ext.Toolbar.Separator({
				id: 'beforeBatchSeparator'
			});

			var batchButton = new Ext.Button({
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
							if( selectedRecords >= pageTotalRecord){
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
			});

			pagingToolbar.add(beforeBatchSeparator);
			pagingToolbar.add(batchButton);
		}
	}
	
	//remove 'Batch Update' Button & its vertical Separator from the pagingtoolbar.
	var hideBatchUpdateButton = function(){
		if(typeof pagingToolbar.batchButton != 'undefined' && typeof Ext.getCmp('beforeBatchSeparator') != 'undefined'){
			pagingToolbar.remove(Ext.getCmp('beforeBatchSeparator'));
			pagingToolbar.remove(pagingToolbar.batchButton);
		}
	};

	var showSaveButton = function(){
		if(typeof pagingToolbar.saveButton == 'undefined' && typeof Ext.getCmp('beforeSaveSeparator') == 'undefined'){
			var beforeSaveSeparator = new Ext.Toolbar.Separator({
				id: 'beforeSaveSeparator'
			});

			var saveButton = new Ext.Button({
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
					else if(SM.activeModule == 'Customers')
						store = customersStore;
					else if(SM.activeModule == couponFields.coupon_dashbd.title)
						store = couponstore;

					saveRecords(store,pagingToolbar,jsonURL,editorGridSelectionModel);
				}}
			});

			pagingToolbar.add(beforeSaveSeparator);
			pagingToolbar.add(saveButton);
		}
	}

	//remove 'Save' Button & its vertical Separator from the pagingtoolbar.
	var hideSaveButton = function(){
		if(typeof pagingToolbar.saveButton != 'undefined' && typeof Ext.getCmp('beforeSaveSeparator') != 'undefined'){
			pagingToolbar.remove(Ext.getCmp('beforeSaveSeparator'));
			pagingToolbar.remove(pagingToolbar.saveButton);
		}
	};

	var showExportButton = function(){
		if(typeof pagingToolbar.exportButton == 'undefined' && typeof Ext.getCmp('beforeExportSeparator') == 'undefined'){
			var beforeExportSeparator = new Ext.Toolbar.Separator({
				id: 'beforeExportSeparator'
			});

			var exportButton = new Ext.Button({
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

						// var column_headers = '';

						// if ( SM.activeModule == 'Products' ) {
						// 	column_headers = Ext.encode(products_columns);
						// 	// column_headers = products_columns;
						// }

						var fileurl = (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file';

		                Ext.DomHelper.append(Ext.getBody(), { 
		                    tag: 'iframe', 
		                    id:'downloadIframe', 
		                    frameBorder: 0, 
		                    width: 0, 
		                    height: 0, 
		                    css: 'display:none;visibility:hidden;height:0px;', 
		                    // src: jsonURL+'?cmd=exportCsvWoo&incVariation='+SM.incVariation+'&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+'&SM_IS_WOO16='+SM_IS_WOO16+''
		                    // src: ajaxurl + '?action=sm_include_file&file='+jsonURL+'&func_nm=exportCsvWoo&incVariation='+SM.incVariation+'&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+'&SM_IS_WOO16='+SM_IS_WOO16+''
		                    src: fileurl + '&file='+jsonURL+'&func_nm=exportCsvWoo&incVariation='+SM.incVariation+'&search_query[]='+encodeURIComponent(search_query)+'&search=advanced_search&searchText='+SM.searchTextField.getValue()+'&fromDate='+fromDateTxt.getValue()+'&toDate='+toDateTxt.getValue()+'&active_module='+SM.activeModule+'&SM_IS_WOO16='+SM_IS_WOO16+'&SM_IS_WOO21='+SM_IS_WOO21+'&SM_IS_WOO22='+SM_IS_WOO22+'&SM_IS_WOO30='+SM_IS_WOO30+'&security='+SM_NONCE,
		                }); 
					}
				}
			});

			pagingToolbar.add(beforeExportSeparator);
			pagingToolbar.add(exportButton);
		}
	}

	//remove 'Export' Button & its vertical Separator from the pagingtoolbar.
	var hideExportButton = function(){
		if(typeof pagingToolbar.exportButton != 'undefined' && typeof Ext.getCmp('beforeExportSeparator') != 'undefined'){
			pagingToolbar.remove(Ext.getCmp('beforeExportSeparator'));
			pagingToolbar.remove(pagingToolbar.exportButton);
		}
	};
	
	/* ====================== Products ==================== */
	
	//Renderer for dimension units
	Ext.util.Format.comboRenderer = function(productStatusCombo){
        return function(value){
			var record = productStatusCombo.findRecord(productStatusCombo.valueField, value);
			return record ? record.get(productStatusCombo.displayField) : productStatusCombo.valueNotFoundText;
		}
	}
	
	function formatDate(value){
        return value ? value.dateFormat('M d, Y') : '';
    }
	

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
			data: [['publish', 'Published'], ['pending', 'Pending Review'], ['draft', 'Draft'],['inherit', 'Inherit'],['private', 'Privately Published']]
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
			data: [['publish', 'Published'], ['pending', 'Pending Review'], ['draft', 'Draft'],['private', 'Privately Published']]			
		}),
		valueField: 'value',
		displayField: 'name'
	});

    // Visibility combo box
    var visibilityCombo = new Ext.form.ComboBox({
        typeAhead: true,
        id: 'visibilityCombo',
        triggerAction: 'all',
        lazyRender:true,
        editable: false,
        mode: 'local',
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['value','name'],
            data: visibilityStoreData
        }),
        valueField: 'value',
        displayField: 'name'
    });

	// Visibility combo box
    var taxStatusCombo = new Ext.form.ComboBox({
        typeAhead: true,
        id: 'taxStatusCombo',
        triggerAction: 'all',
        lazyRender:true,
        editable: false,
        mode: 'local',
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['value','name'],
            data: [
                    [ 'taxable', getText('Taxable') ],
                    [ 'shipping', getText('Shipping only') ],
                    [ 'none', getText('None') ]
                  ]
        }),
        valueField: 'value',
        displayField: 'name'
    });

    // Product Type combo box

    var postTypeStoreData = new Array();

    if(SM.productsCols.hasOwnProperty('product_type')) {
    	
    	var array_index = 0;

	    jQuery.each(SM.productsCols.product_type.values, function(index, value) {
			postTypeStoreData [array_index] = new Array();
			postTypeStoreData [array_index][0] = index;
			postTypeStoreData [array_index][1] = value;
			array_index++;
		});

	    var productTypeCombo = new Ext.form.ComboBox({
	        typeAhead: true,
	        id: 'productTypeCombo',
	        triggerAction: 'all',
	        lazyRender:true,
	        editable: false,
	        mode: 'local',
	        store: new Ext.data.ArrayStore({
	            id: 0,
	            fields: ['value','name'],
	            data: postTypeStoreData
	        }),
	        valueField: 'value',
	        displayField: 'name'
	    });
    }

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

//custom columns for Products Dashboard

var products_columns = new Array();
var products_render_fields = new Array();   

products_columns = [editorGridSelectionModel,
				{
					header: '',
					id: 'type',
					dataIndex: SM.productsCols.post_parent.colName,
					tooltip: getText('Type'),
		                	width: 10,
					hidden: true,
		                        dragable:false,
					renderer: function (value, metaData, record, rowIndex, colIndex, store) {
						// return (value == 0 ? '<img id=editUrl src="' + imgURL + 'fav.gif"/>' : '');
						return (value == 0 ? '<span id=prod_variation_img> </span>' : '');
					}
				},
				{
					header: SM.productsCols.image.name,
					id: 'image',
					dataIndex: SM.productsCols.image.colName,
					tooltip: getText('Product Images'),
		                        width: 30,
					hidden: true,
					renderer: function (value, metaData, record, rowIndex, colIndex, store) {
						return (record.data.thumbnail != 'false' ? '<img width=16px height=16px src="' + record.data.thumbnail + '"/>' : '');
					}
				},
                {
                    header: SM.productsCols.id.name,
                    id: 'id_products',
                    hidden: false,
                    sortable: true,
                    dataIndex: SM.productsCols.id.colName,
                    tooltip: getText('Product Id'),
                    width: 70
                },
				{
					header: SM.productsCols.name.name,
					id: 'name_products',
					sortable: true,
					dataIndex: SM.productsCols.name.colName,
					tooltip: getText('Product Name'),
		                	width: 250,
					editor: new fm.TextField({
						allowBlank: false,
		                width: 250
					})
				},
				{
					header: SM.productsCols.regularPrice.name,
					id: 'price',
					align: 'right',
					sortable: true,
					dataIndex: SM.productsCols.regularPrice.colName,
					tooltip: getText('Price'),
					renderer: numeric_renderer(sm_amount_decimal_precision),
		            width: 70,
		            editor: new fm.TextField({
						allowBlank: true,
                        width: 70,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
					header: SM.productsCols.salePrice.name,
					id: 'salePrice',
					sortable: true,
					align: 'right',
		            width: 70,
					dataIndex: SM.productsCols.salePrice.colName,
					renderer: numeric_renderer(sm_amount_decimal_precision),
					tooltip: getText('Sale Price'),
					editor: new fm.TextField({
						allowBlank: true,
                        width: 70,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
		            header: SM.productsCols.salePriceFrom.name,
		            id: 'salePriceFrom',
					sortable: true,
		            hidden: true,
					tooltip: getText('Sale Price From'),
		            dataIndex: SM.productsCols.salePriceFrom.colName,
		            renderer: formatDate,
		                            width: 80,
		            editor: new fm.DateField({
		                format: 'm/d/y',
		                editable: true,
		                allowBlank: true,
						allowNegative: false,
		                width: 80
		            })
		        },{
		            header: SM.productsCols.salePriceTo.name,
		            id: 'salePriceTo',
					sortable: true,
		            hidden: true,
		            tooltip: getText('Sale Price To'),
		            width: 80,
		            dataIndex: SM.productsCols.salePriceTo.colName,
		            renderer: formatDate,
		            editor: new fm.DateField({
		                format: 'm/d/y',
		                editable: true,
		                allowBlank: true,
		                allowNegative: false,
		                width: 80
		            })
		        },{
					header: SM.productsCols.inventory.name,
					id: 'inventory',
					sortable: true,
		                        width: 40,
					align: 'right',
					dataIndex: SM.productsCols.inventory.colName,
					renderer: Ext.util.Format.numberRenderer('0'),
					tooltip: getText('Inventory'),
					editor: new fm.NumberField({
						allowBlank: true,
						allowNegative: true,
		                                size: 22
					})
				},{
					header: SM.productsCols.sku.name,
					id: 'sku',
		                        width: 70,
					sortable: true,
					dataIndex: SM.productsCols.sku.colName,
					tooltip: getText('SKU'),
					editor: new fm.TextField({
						allowBlank: true,
		                                width: 70
					})
				},{
					header: SM.productsCols.group.name,
					id: 'group',
		                        width: 100,
					sortable: true,
					dataIndex: SM.productsCols.group.colName,
					tooltip: getText('Category')
				},{
					header: SM.productsCols.attributes.name,
					id: 'attributes',
		            width: 100,
		            hidden: true,
					sortable: true,
					dataIndex: SM.productsCols.attributes.colName,
					tooltip: getText('Attributes')
				},{
					header: SM.productsCols.product_type.name,
					id: 'product_type',
		            width: 100,
					sortable: true,
					dataIndex: SM.productsCols.product_type.colName,
					tooltip: getText('Product Type'),
					renderer: Ext.util.Format.comboRenderer(productTypeCombo)
				},{
					header: SM.productsCols.weight.name,
					id: 'weight',
					colSpan: 2,
		            width: 60,
					sortable: true,
					align: 'right',
					dataIndex: SM.productsCols.weight.colName,
					tooltip: getText('Weight'),
					renderer: numeric_renderer(sm_dimensions_decimal_precision),
					editor: new fm.TextField({
						allowBlank: true,
                        width: 60,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
					header: SM.productsCols.publish.name,
					id: 'publish',
					width: 60,
					sortable: true,
					dataIndex: SM.productsCols.publish.colName,
					tooltip: getText('Product Status'),
					renderer: Ext.util.Format.comboRenderer(productStatusCombo)
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
		                                width: 180,
						growMax: 10000
					})
				},{
					header: SM.productsCols.addDesc.name,
					id: 'addDesc',
					hidden: true,
		                        width: 180,
		//			dataIndex: SM.productsCols.addDesc.colName,
					tooltip: getText('Additional Description'),
					hideable: false,
					editor: new fm.TextArea({
						autoHeight: true,
						grow: true,
						growMax: 10000
					})
				},{
					header: SM.productsCols.height.name,
					id: 'height',
					hidden: true,
		            width: 60,
					colSpan: 2,
					sortable: true,
					align: 'right',
					dataIndex: SM.productsCols.height.colName,
					tooltip: getText('Height'),		
					renderer: numeric_renderer(sm_dimensions_decimal_precision),
					editor: new fm.TextField({
						allowBlank: true,
                        width: 60,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
					header: SM.productsCols.width.name,
					id: 'width',
					hidden: true,
		            width: 60,
					colSpan: 2,
					sortable: true,
					align: 'right',
					dataIndex: SM.productsCols.width.colName,
					tooltip: getText('Width'),
					renderer: numeric_renderer(sm_dimensions_decimal_precision),
					editor: new fm.TextField({
						allowBlank: true,
                        width: 60,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
					header: SM.productsCols.lengthCol.name,
					id: 'lengthCol',
					hidden: true,
		            width: 60,
					colSpan: 2,
					sortable: true,
					align: 'right',
					dataIndex: SM.productsCols.lengthCol.colName,
					tooltip: getText('Length'),		
					renderer: numeric_renderer(sm_dimensions_decimal_precision),
					editor: new fm.TextField({
						allowBlank: true,
                        width: 60,
                        style: 'text-align: right',
                        maskRe: /[0-9.-]/
					})
				},{
		            header: SM.productsCols.visibility.name,
		            id: 'visibility',
		            width: 100,
		            sortable: true,
		            hidden: true,
		            dataIndex: SM.productsCols.visibility.colName,
		            tooltip: getText('Product Visibility'),
		            renderer: Ext.util.Format.comboRenderer(visibilityCombo)
		        },{
					header: SM.productsCols.taxStatus.name,
					id: 'taxStatus',
					width: 90,
					hidden: true,
					sortable: true,
					dataIndex: SM.productsCols.taxStatus.colName,
					tooltip: getText('Tax Status'),
		            renderer: Ext.util.Format.comboRenderer(taxStatusCombo)
				}];

if( SM_IS_WOO30 == 'true' ) {
	var featured = {
					header: SM.productsCols.featured.name,
					id: 'featured',
					width: 60,
					hidden: true,
					sortable: true,
					dataIndex: SM.productsCols.featured.colName,
					tooltip: getText('Featured'),
		            renderer: Ext.util.Format.comboRenderer(yesNoCombo_inline)
				};
	products_columns.push(featured);
}

// Code to create render fields array for products dashboard
var products_render_fields = new Array();

products_render_fields = [
							{name: SM.productsCols.id.colName,                type: 'int'},
							{name: SM.productsCols.name.colName,              type: 'string'},
							{name: SM.productsCols.regularPrice.colName,      type: 'string'},
							{name: SM.productsCols.salePrice.colName,         type: 'string'},
							{name: SM.productsCols.salePriceFrom.colName,     type: 'date', dateFormat: 'Y-m-d'},
							{name: SM.productsCols.salePriceTo.colName,       type: 'date', dateFormat: 'Y-m-d'},
							{name: SM.productsCols.inventory.colName,         type: 'string'},
							{name: SM.productsCols.publish.colName,           type: 'string'},
							{name: SM.productsCols.sku.colName,               type: 'string'},
							{name: SM.productsCols.group.colName,             type: 'string'},
							{name: SM.productsCols.attributes.colName,        type: 'string'},
							{name: SM.productsCols.product_type.colName,      type: 'string'},
							{name: SM.productsCols.desc.colName,              type: 'string'},
							{name: SM.productsCols.addDesc.colName,           type: 'string'},
							{name: SM.productsCols.weight.colName,            type: 'string'},
							{name: SM.productsCols.height.colName,            type: 'string'},
							{name: SM.productsCols.width.colName,             type: 'string'},
							{name: SM.productsCols.lengthCol.colName,         type: 'string'},
							{name: SM.productsCols.post_parent.colName,	      type: 'int'},
							{name: SM.productsCols.image.colName,	      	  type: 'string'},
							{name: SM.productsCols.taxStatus.colName,	      type: 'string'},
			                {name: SM.productsCols.visibility.colName,        type: 'string'}
			            ];

if( SM_IS_WOO30 == 'true' ) {
	products_render_fields.push({name: SM.productsCols.featured.colName,          type: 'string'});
}

jQuery(function($) {
	
	column_index = products_columns.length;
	render_index = products_render_fields.length;

	//Array for handling 
	var columns_render_string = new Array('total_sales'); 

	$.each(SM.productsCols, function(index, value) {

	    if (value.hasOwnProperty('colType') && (value.colType == 'custom_column' || value.colType == 'custom_column_serialized') && value.name != 'Other Meta') {

        var name = (value.hasOwnProperty('value')) ? value.value : ''
        	f_name = name.replace(/[^a-zA-z0-9_-]/g,''); // commented for meta_keys containing sp. chars [like #,~..]
     	

    	var product_column = new Object(),
	    		decimal_precision = (value.hasOwnProperty('decimal_precision')) ? value.decimal_precision : 0;

        	product_column.header = value.name;
        	
		//product_column.dataIndex = value.value;
		sm_prod_custom_cols_formatted[f_name] = name
		product_column.dataIndex = f_name;

        	product_column.width = 50;
        	product_column.editable = true;
        	product_column.hidden = true;
        	product_column.type = 'custom'; // field to detect custom fields


        	if (value.dataType == "bool") {
        		product_column.editor = trueFalseCombo_inline;
        	} else if (value.dataType == "date") {
        		product_column.renderer = formatDate;
        		product_column.editor = new fm.DateField({
					format: 'm/d/y',
					editable: false,
					allowBlank: false,
					allowNegative: false,
					width: 50
				});
        	} else if (value.dataType == "select") {

        		var select = new Array();
        		var temp = value.values;
				var array_index = 0;

        		$.each(temp, function(index, value) {
		    		select [array_index] = new Array();
		    		select [array_index][0] = index;
		    		select [array_index][1] = value;
		    		array_index++;
		    	});

        		product_column.editor = new Ext.form.ComboBox({
											typeAhead: true,
											triggerAction: 'all',
											lazyRender:true,
											editable: false,
											mode: 'local',
											store: new Ext.data.ArrayStore({
														id: 0,
														fields: ['value','name'],
														data: select
													}),
											valueField: 'value',
											displayField: 'name'
										});

        		//code for handling diff. values for variation
        		if( value.hasOwnProperty('variation_values') ) {
        			var select = new Array();
	        		var temp = value.variation_values;
					var array_index = 0;

	        		$.each(temp, function(index, value) {
			    		select [array_index] = new Array();
			    		select [array_index][0] = index;
			    		select [array_index][1] = value;
			    		array_index++;
			    	});

			    	product_column.editor_variation = new Ext.form.ComboBox({
														typeAhead: true,
														triggerAction: 'all',
														lazyRender:true,
														editable: false,
														mode: 'local',
														store: new Ext.data.ArrayStore({
																	id: 0,
																	fields: ['value','name'],
																	data: select
																}),
														valueField: 'value',
														displayField: 'name'
													});
        		}

        	} else if (value.dataType == "float") {
        		product_column.align 	=  'right';

        		if (decimal_precision > 0) {
        			product_column.renderer = numeric_renderer(decimal_precision);	
        		}
        		
				product_column.editor 	= new fm.TextField({
																allowBlank: true,
										                        width: 50,
										                        style: 'text-align: right',
										                        maskRe: /[0-9.-]/
															});

        	} else {
        		product_column.editor= new fm.TextField({ allowBlank: true, allowNegative: true});	
        	}

        	products_columns [column_index] = product_column;

        	column_index++;

	        //Code for rendering array

        	if (fileExists == 1) {
		    	var product_column_render = new Object();

                // var name = (value.hasOwnProperty('value')) ? value.value : ''
                product_column_render.name = f_name; // commented for meta_keys containing sp. chars [like #,~..]
	        	//product_column_render.name = value.value;
	        	product_column_render.type = (decimal_precision > 0 || jQuery.inArray(value.colName, columns_render_string) > -1 ) ? 'string' : value.dataType;
	        	product_column_render.table = value.tableName;
	        	products_render_fields [render_index] = product_column_render;

	        	render_index++;
        	}
	    }
	});
});

render_index = products_columns.length;

products_columns [render_index] = {
											header: getText('Edit'),
											id: 'edit',
											width: 30,
											sortable: true,
											tooltip: getText('Product Info'),
											dataIndex: 'edit_url',
								                        dragable:false,
											renderer: function (value, metaData, record, rowIndex, colIndex, store) {
								            
								                        if(record.get('post_parent') == 0 || record['json']['product_type'] == "grouped") {
								                    return '<span id=editlink> </span>';
								                }
											}
										};

render_index++;

products_columns [render_index] = {
											header: '',
						                    id: 'products_scroll',
						                    width: 6.9,
						                    Fixed: true,
						                    sortable:false,
						                    menuDisabled : true,
						                    hideable: false,
						                    dragable:false
										};


//Code to enable disabling any column to be moved to the place of the one which cannot be dragged
Ext.ProductsColumnModel = Ext.extend(Ext.grid.ColumnModel, {
  moveColumn: function (oldIndex, newIndex) {
    
  	var product_scroll_index = products_columns.length - 1;
  	var edit_url_index = products_columns.length - 2;

  	var new_index_calc = products_columns.length - 3;

    if (newIndex == 1) {
      newIndex = 2;
    }
    else if (newIndex == edit_url_index || newIndex == product_scroll_index) {
      newIndex = new_index_calc;
    }
    
    var c = this.config[oldIndex];
    this.config.splice(oldIndex, 1);
    this.config.splice(newIndex, 0, c);
    this.dataMap = null;
    this.fireEvent("columnmoved", this, oldIndex, newIndex);
  }
});  

var productsColumnModel = new Ext.ProductsColumnModel({
	columns: products_columns,
	listeners: {
		hiddenchange: function( ColumnModel,columnIndex, hidden ){

			if (ColumnModel.columns[columnIndex].hasOwnProperty('type')) {
				if (ColumnModel.columns[columnIndex].type == 'custom' && fileExists == 0) {
					Ext.notification.msg('Smart Manager',"Custom fields available only in Pro version" );
					ColumnModel.columns[columnIndex].hidden = true;
					return true;
				}	
			}
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

	var productsJsonReader = new Ext.data.JsonReader({
		totalProperty: 'totalCount',
		root: 'items',
		fields: products_render_fields
		
	});	

	//Code to get the advanced search query string
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
            SM_IS_WOO16: SM_IS_WOO16,
            SM_IS_WOO21: SM_IS_WOO21,
            SM_IS_WOO22: SM_IS_WOO22,
            SM_IS_WOO30: SM_IS_WOO30,
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
                productsColumnModel.getColumnById('visibility').editor = visibilityCombo;  
                productsColumnModel.getColumnById('taxStatus').editor = taxStatusCombo;
                productsColumnModel.getColumnById('product_type').editor = productTypeCombo;

                if( SM_IS_WOO30 == 'true' ) {
					productsColumnModel.getColumnById('featured').editor = yesNoCombo_inline;
				}
			}
		}
	});

	var mask = new Ext.LoadMask(Ext.getBody(), {
		msg: getText('Please wait') + "..."
	});

	var showProductsView = function(){
    	batchUpdateWindow.loadMask.show();
                
		productsStore.baseParams.searchText = ''; //clear the baseParams for productsStore
		SM.searchTextField.reset(); 			  //to reset the searchTextField
		SM.searchTextField.hide(); 			  //to reset the searchTextField
		
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
					parameters: products_search_cols
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
	                    url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
	                    dataType: "text",
	                    async: false,
	                    data: {

	                    	cmd: 'getData',
							active_module: SM.activeModule,
							start: 0,
							limit: limit,
							viewCols: Ext.encode(productsViewCols),
							incVariation: SM.incVariation,
				            SM_IS_WOO16: SM_IS_WOO16,
				            SM_IS_WOO21: SM_IS_WOO21,
				            SM_IS_WOO22: SM_IS_WOO22,
				            SM_IS_WOO30: SM_IS_WOO30,
				            security: SM_NONCE,
				            file:  jsonURL,
				            search_query: search_query,
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
		hideBatchUpdateButton();
		hideSaveButton();
		hideExportButton();
		showAddProductButton();
		showDuplicateButton();
		showDeleteButton();
		showBatchUpdateButton();
		showSaveButton();
		showExportButton();
		pagingToolbar.doLayout(true,true);

		batchUpdateToolbar.items.items[2].show();
		// batchUpdateToolbar.items.items[0].items.items[13].show();		
		
		for(var i=2;i<=8;i++)
		editorGrid.getTopToolbar().get(i).hide();
		editorGrid.getTopToolbar().get('incVariation').show();
        // editorGrid.getTopToolbar().get('duplicateButton').show();

		productsStore.load();
		pagingToolbar.bind(productsStore);

		incvariation = editorGrid.getTopToolbar().get('incVariation').fireEvent('getState');
        editorGrid.reconfigure(productsStore,productsColumnModel);
        fieldsStore.loadData(productsFields);

		var firstToolbar       = batchUpdatePanel.items.items[0].items.items[0];
		var textfield          = firstToolbar.items.items[5];
		textfield.show();
	};

	/* ====================== Products ==================== */

var updation_progress = updated + 1;
	
//	==== common ====


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
		} else if ( ( modifiedRecords.length >= updation_progress ) && ( fileExists == 0 ) ) {
			Ext.notification.msg('Note', 'For editing more records upgrade to Pro');
			return;
		}

		var coupon_data_table = new Object();
		
		if ( SM.activeModule == 'Coupons' ) {
			var fields = Ext.decode(store.baseParams.couponFields);
			var coupon_fields = fields.coupon_dashbd.column.items;

			for (j = 0; j < coupon_fields.length; j++) {
    			coupon_data_table[coupon_fields[j].value] = coupon_fields[j].table;
    		}
		}
			

		var edited  = [];
		var edited_ids = [];
		Ext.each(modifiedRecords, function(r, i){

			if(r.get('id') == ''){
				r.data.category = newCatId;
			}

			var records = r.get('id');
			if ( SM.activeModule == 'Coupons' ) {
				edited_ids.push(r.id);
			}

			var e_data = new Object();

			for (var item in r.data) {

				key = ( SM.activeModule == 'Products' && sm_prod_custom_cols_formatted.hasOwnProperty(item) ) ? sm_prod_custom_cols_formatted[item] : item;
				e_data[key] = (typeof r.data[item] == "string") ? r.data[item].replace(/(?:\r\n|\r|\n)/g, '<br />') : r.data[item];
			}
			
			edited.push(e_data);
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
				incVariation: SM.incVariation,
				edited:Ext.encode(edited),
				table:Ext.encode(coupon_data_table),
				edited_ids:Ext.encode(edited_ids),
                SM_IS_WOO16: SM_IS_WOO16,
                SM_IS_WOO21: SM_IS_WOO21,
                SM_IS_WOO22: SM_IS_WOO22,
                SM_IS_WOO30: SM_IS_WOO30,
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

            firstToolbar.items.items[6].reset();
            firstToolbar.items.items[6].hide();

            firstToolbar.items.items[7].reset();
            firstToolbar.items.items[7].hide();

            firstToolbar.items.items[8].reset();
            firstToolbar.items.items[8].hide();

            firstToolbar.items.items[10].reset();
            firstToolbar.items.items[10].hide();

            firstToolbar.items.items[12].hide(); // hide the warning icon
            
            batchUpdatePanel.items.items[0].items.items[2].show(); // show the add row btn

            firstToolbar.items.items[9].hide();
            firstToolbar.items.items[2].show(); // As the same is hidden if the Image functionality not available
           
            //Code for reseting the Image button icon
            jQuery('.x-batchimage').css('background-image', 'url(' + imgURL + 'batch_image.gif' + ')');
            jQuery('.x-batchimage').css('background-size', '100% 100%');

            toolbarCount = 1; // re-initialize the toolbarCount variable
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

                        function dup_prod(count, total_records, dup_data) {
                        var arr = new Array();

                        dupcnt = 0;
                        if (total_records > dup_limit) {
                            fdupcnt = dup_limit;
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
                                                    cmd: 'dupData',
                                                    part: i+1,
                                                    dup_limit: dup_limit,
                                                    dupcnt : dupcnt,
                                                    fdupcnt : fdupcnt,
                                                    count : count,
                                                    total_records : total_records,
                                                    dup_data : dup_data,
                                                    menu : menu,
                                                    active_module: SM.activeModule,
                                                    incvariation: SM.incVariation,
                                                    SM_IS_WOO21: SM_IS_WOO21,
                                                    SM_IS_WOO22: SM_IS_WOO22,
                                                    SM_IS_WOO30: SM_IS_WOO30,
                                                    security: SM_NONCE,
                                                    file:  jsonURL
                                            }
                                    };

                                    dupcnt = fdupcnt;
                                    if ((fdupcnt+dup_limit) <= total_records) {
                                          fdupcnt = fdupcnt +dup_limit;
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
                                    dup_prod(count, dupcnt, dup_data);
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

						if (SM.activeModule == 'Products') {
						store = productsStore;
                                                }
						else if (SM.activeModule == 'Orders') {
						store = ordersStore;
                                                }

						var myJsonObj    = Ext.decode(response.responseText);
						var delcnt       = myJsonObj.delCnt;
                                                var totalRecords = 0;
						if (SM.activeModule == 'Products') {
                                                    totalRecords = productsJsonReader.jsonData.totalCount;
                                                }
                                                else if (SM.activeModule == 'Orders') {
                                                    totalRecords = ordersJsonReader.jsonData.totalCount;
                                                }
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
		}else if (clickedActiveModule == couponFields.coupon_dashbd.title){
			SM.activeModule = couponFields.coupon_dashbd.title;
			showcouponView();
		}else{
			SM.activeModule = 'Products';
			showProductsView();
		}

	};
	
        
var batchMask = new Ext.LoadMask(Ext.getBody(), {
	msg: getText('Please wait') + "..."
});
        
	// Products, Customers and Orders combo box
	SM.dashboardComboBox = new Ext.form.ComboBox({
		id: 'dashboardComboBox',
		stateId : 'dashboardComboBoxWoo',
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
                	if (this.value != 'Coupons') {
                      SM.dashboard_state = this.value;
                	}
                },
		
		forceSelection: true,
		width: 135,
		listeners: {
			select: function () {
				pagingToolbar.emptyMsg = this.getValue() + ' ' + getText('list is empty');

								if(this.value == 'Coupons') {
									editorGrid.getTopToolbar().get(10).hide();
									editorGrid.getTopToolbar().get(11).hide();
									
								} else {
									editorGrid.getTopToolbar().get(10).show();
									editorGrid.getTopToolbar().get(11).show();
								}

                                if(this.value == 'Products') {
                                      editorGrid.stateId = this.value.toLowerCase()+'EditorGridPanelWoo';
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
				else if(SM.activeModule == couponFields.coupon_dashbd.title)
					store = couponstore;
				else 
					store = customersStore;

				//storing the value of clicked module name
				if (this.value == 'Customers')
					clickedActiveModule = 'Customers';
				else if (this.value == 'Orders')
					clickedActiveModule = 'Orders';
				else if (this.value == couponFields.coupon_dashbd.title)
					clickedActiveModule = couponFields.coupon_dashbd.title;
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


	// ==== common ====
SM.searchTextField = new Ext.form.TextField({
	id: 'searchTextField',
	width: 700,
	cls: 'searchPanel',
	style: {
		fontSize: '14px',
		paddingLeft: '2px',
		width: '100%'
	},
	params: {
		cmd: 'searchText',
		SM_IS_WOO16: SM_IS_WOO16
	},
	emptyText: getText('Search') + '...', 
	enableKeyEvents: true,
	listeners: {
		keyup: function () {
			/*if ( fileExists != 1 ) {
				Ext.notification.msg('Smart Manager', getText('Search feature is available only in Pro version') );
				return;
			}	*/		
			//set a store depending on the active Module
			store = productsStore;
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
				else if(SM.activeModule == 'Orders'){
					ordersStore.loadData(myJsonObj);
                } else {
					customersStore.loadData(myJsonObj);
                }
				
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
			SM_IS_WOO16: SM_IS_WOO16,
			SM_IS_WOO21: SM_IS_WOO21,
			SM_IS_WOO22: SM_IS_WOO22,
			SM_IS_WOO30: SM_IS_WOO30,
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


// Code to create the stores for custom columns
var storedata_array = new Array();

jQuery(function($) {
	$.each(SM.productsCols, function(index, value) {
	    if (value.hasOwnProperty('values')) {
	    	// storenm = index + 'StoreData';
	    	storenm = index;
			storedata_array [storenm] = new Array();
			var array_index = 0;

	    	var temp = value.values;

	    	$.each(temp, function(index, value) {
	    		storedata_array [storenm][array_index] = new Array();
	    		storedata_array [storenm][array_index][0] = index;
	    		storedata_array [storenm][array_index][1] = value;
	    		array_index++;
	    	});
	    }
	});
});


//Store for 'set to' from second combobox(actions combobox).
var countriesStore = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		idProperty: 'id',
		totalProperty: 'totalCount',
		root: 'items',
		fields: [{ name: 'id'  },
				{ name: 'name' },
				{ name: 'value'}]
	}),
	autoDestroy: false,
	dirty: false
});
countriesStore.loadData(countries);

var countriesStoreCombo = new Ext.form.ComboBox({
		store: countriesStore,
		valueField: 'value',
		displayField: 'name',
		mode: 'local',
		typeAhead: true,
		triggerAction: 'all',
		lazyRender: true,
		editable: false
});

var orderStatusStoreData = new Array();
    orderStatusStoreData = [
                            ['pending', getText('Pending')],
                            ['failed', getText('Failed')],
                            ['on-hold', getText('On Hold')],
                            ['processing',getText('Processing')],
                            ['completed', getText('Completed')],
                            ['refunded', getText('Refunded')],
                            ['cancelled', getText('Cancelled')]
                          ];

var orderStatusStore = new Ext.data.ArrayStore({
			id: 0,
			fields: ['id','name'],
			data: [
			['pending',  	'Pending'],
			['failed',  	'Failed'],
			['on-hold', 	'On Hold'],
			['processing',  'Processing'],
			['completed',   'Completed'],
			['refunded',    'Refunded'],
			['cancelled', 	'Cancelled']
			]
	});
	
// for woo2.2	
if (SM_IS_WOO22 == 'true' || SM_IS_WOO30 == 'true') {
	orderStatusStoreData[0][1] = getText('Pending payment'); 
	orderStatusStore.data.items[0].data.name = getText('Pending payment');
}
	
	var orderStatusCombo = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		lazyRender:true,
		editable: false,
		mode: 'local',
		store: orderStatusStore,
		valueField: 'id',
		displayField: 'name'
	});

//batch update window
var batchUpdateToolbarInstance = Ext.extend(Ext.Toolbar, {
	cls: 'batchtoolbar',
	constructor: function (config) {
		config = Ext.apply({
			items: [{
				xtype: 'combo',
				width: 170,
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
						var field = {};
						field = this.store.reader.jsonData.items[selectedFieldIndex];
						var field_name = this.store.reader.jsonData.items[selectedFieldIndex].name;
						var colName = this.store.reader.jsonData.items[selectedFieldIndex].colName;
						

						var actionsData = new Array();
						var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboCategoriesActionCmp = toolbarParent.get(7);
						var setTextfield = toolbarParent.get(6);
						var comboActionCmp = toolbarParent.get(2);
						var comboCountriesCmp = toolbarParent.get(4);
						var selectedActionvalue = comboActionCmp.value;
						var textField2Cmp      = toolbarParent.get(8);
                        var lblImg = toolbarParent.get(9);
                        var setTextarea = toolbarParent.get(10); // For description and additional Description
                        var comboFieldCmp = toolbarParent.get(0);

						toolbarParent.get(5).hide();			//to hide extra space on batchUpdateToolbar
						comboActionCmp.show(); // As the same is hidden if the Image functionality not available
                                                
						toolbarParent.get(12).hide(); // to hide the warning icon

						objRegExp = /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/;;
						regexError = getText('Only numbers are allowed');

						//Code for handling custom fields columns
						var actionType = '';
						if(SM.activeModule == 'Products'){
							actionType = this.store.reader.jsonData.items[selectedFieldIndex].actionType;
						}

							if(SM['productsCols'][this.value] != undefined ){
								var categoryActionType = SM['productsCols'][this.value].actionType;
							}							
                        	setTextfield.emptyText=getText('Enter the Value') + '...';
                        	setTextarea.emptyText=getText('Enter the Value') + '...';

							if (field_type == 'category' || categoryActionType == 'category_actions') {
								setTextfield.hide();
								textField2Cmp.hide();
								comboCategoriesActionCmp.show();
								comboCategoriesActionCmp.reset();
                                lblImg.hide();
                                setTextarea.hide();
							} else if (field_name == 'Stock: Quantity Limited' || field_name == 'Stock: Inform When Out Of Stock' || field_name == 'Disregard Shipping' || actionType == 'YesNoActions') {
								setTextfield.hide();
								textField2Cmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
                                setTextarea.hide();
							} else if(colName == '_tax_status' || colName == '_visibility' || colName == 'visibility' || field_name == 'Publish' || colName == 'product_type' || (field.colType == "custom_column" && field.hasOwnProperty('values'))) {
								setTextfield.hide();
								textField2Cmp.hide();
								comboCategoriesActionCmp.show();
								comboCategoriesActionCmp.reset();
                                lblImg.hide();
                                setTextarea.hide();
							} else if(field_type == 'attribute_action'){
								setTextfield.hide();
								textField2Cmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
                                setTextarea.hide();
							} else if (field_type == 'string') {
								setTextfield.hide();
								textField2Cmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
								setTextarea.hide();
							} else if (field_name == 'Weight' || field_name == 'Variations: Weight'||field_name == 'Height' ||field_name == 'Width' ||field_name == 'Length') {
								setTextfield.show();
								textField2Cmp.hide();
								comboCategoriesActionCmp.hide();
                                lblImg.hide();
                                setTextarea.hide();
							} else if(field_name == 'Order Status'){
								actions_index = field_type;

								setTextfield.hide();
								textField2Cmp.hide();
                                comboCountriesCmp.hide();
								comboCategoriesActionCmp.show();
								comboCategoriesActionCmp.reset();
								lblImg.hide();
								setTextarea.hide();
							} else if (field_name.indexOf('Country') != -1) {
								actions_index = 'bigint';
								setTextfield.hide();
                                setTextfield.emptyText="Enter State/Region...";
								textField2Cmp.hide();
								comboCategoriesActionCmp.hide();
								comboCountriesCmp.show();
								comboCountriesCmp.reset();
                                lblImg.hide();
                                setTextarea.hide();
							} else if (field_name == 'Image') {
                                if (IS_WP35) {
                                    setTextfield.hide();
                                    textField2Cmp.hide();
                                    comboCategoriesActionCmp.hide();
                                    lblImg.show();
                                }
                                else {
                                    comboFieldCmp.setValue(getText('Select a field') + '...');
                                    comboActionCmp.hide();
                                    setTextfield.hide();
                                    textField2Cmp.hide();
                                    comboCategoriesActionCmp.hide();
                                    Ext.notification.msg('Note', 'This feature is available from Wordpress 3.5 onwards');
                                }
                                setTextarea.hide();
								
							} else if (field_name == 'Description' || field_name == 'Additional Description') {

								setTextarea.show();
								setTextfield.hide();
								textField2Cmp.hide();
								comboCountriesCmp.hide();
								comboCategoriesActionCmp.hide();
								lblImg.hide();

							} else if (field_name == 'Other Meta') {

								setTextfield.emptyText = getText('Enter Meta Key') + '...';
								setTextfield.reset();

								textField2Cmp.emptyText = getText('Enter Meta Value') + '...';
								textField2Cmp.reset();

								Ext.QuickTips.register({
								    target: textField2Cmp.el,
								    title: getText('Important:'),
								    text: getText('Enter Meta Value')
								});

								setTextfield.show();
								textField2Cmp.show();
								setTextarea.hide();
								comboCountriesCmp.hide();
								comboCategoriesActionCmp.hide();
								lblImg.hide();

								objRegExp = '';
								regexError = '';

								toolbarParent.get(12).show(); // to hide the warning icon

							} else {
								setTextfield.show();
								textField2Cmp.hide();
								if (field_type == 'blob' || field_type == 'modStrActions' || actionType == 'setStrActions') {
									objRegExp = '';
									regexError = '';
								}
								comboCountriesCmp.hide();
								comboCategoriesActionCmp.hide();
								actions_index = field_type;
                                lblImg.hide();
                                setTextarea.hide();
							}
						
                            if(SM.activeModule == 'Orders' || SM.activeModule == 'Customers'){
							for (j = 0; j < actions[actions_index].length; j++) {
								actionsData[j] = new Array();
								actionsData[j][0] = actions[actions_index][j].id;
								actionsData[j][1] = actions[actions_index][j].name;
								actionsData[j][2] = actions[actions_index][j].value;
							}
							actionStore.loadData(actionsData); // @todo: check whether used only for products or is it used for any other module?
							
							// @todo apply regex accordign to the req
							setTextfield.regex = '';
							setTextfield.regexText = '';	
						}else if(SM.activeModule == 'Products'){
                                                        
                            var field_val = getText('Select a field') + '...';
                            if ( this.value.substring( 0, 14 ) != 'groupAttribute' && this.value != field_val){
								actionStore.loadData(actions[SM['productsCols'][this.value].actionType]);
							}
							// @todo apply regex accordign to the req
							setTextfield.regex = objRegExp;
							setTextfield.regexText = regexError;	
						}
						setTextfield.reset();
						setTextarea.reset();
						comboActionCmp.reset();
						
											
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
				emptyText: getText('Select an action')+ '...',
				triggerAction: 'all',
				editable: false,
				selectOnFocus: true,
				listeners: {
					focus: function () {	
							var actionsData        = new Array();
							var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
							var comboFieldCmp      = toolbarParent.get(0);
							var selectedValue      = comboFieldCmp.value;
                                                        var setTextfield       = toolbarParent.get(6);
                                                        var textField2Cmp      = toolbarParent.get(8);
							
							if(SM.activeModule == 'Orders' || SM.activeModule == 'Customers'){
								var selectedFieldIndex = comboFieldCmp.selectedIndex;
								var field_type         = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].type;
								var field_name         = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;
								var actions_index;

								actions_index = (field_type == 'category') ? field_type + '_actions' :((field_name.indexOf('Country') != -1) ? 'bigint' : field_type);

								for (j = 0; j < actions[actions_index].length; j++) {
									actionsData[j] = new Array();
									actionsData[j][0] = actions[actions_index][j].id;
									actionsData[j][1] = actions[actions_index][j].name;
									actionsData[j][2] = actions[actions_index][j].value;
								}
								actionStore.loadData(actionsData);
							}else{
                                                            
								if ( selectedValue.substring( 0, 14 ) == 'groupAttribute' ) {
									var attributeArray = Ext.decode(attribute);
									if(selectedValue == 'groupAttributeChange' || selectedValue == 'groupAttributeRemove'){
										attributeArray.splice(0,1);
										actionStore.loadData(attributeArray);
									} else {
										actionStore.loadData(attributeArray);
									}
                                                                } else {
									// on swapping between the toolbars	
									actionStore.loadData(actions[SM['productsCols'][selectedValue].actionType]);
								}
							}
						},					
					select: function() {
						var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboFieldCmp      = toolbarParent.get(0);
						var comboactionCmp     = toolbarParent.get(2);
						var comboCountriesCmp  = toolbarParent.get(4);
						var textField1Cmp      = toolbarParent.get(6);
						var selectedFieldIndex = comboFieldCmp.selectedIndex;
						var selectedValue      = comboFieldCmp.value;
                        var field_name = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].name;
						var selectedActionvalue = comboactionCmp.value;
						var comboCategoriesActionCmp = toolbarParent.get(7);
						var textField2Cmp      = toolbarParent.get(8);
						var setTextarea        = toolbarParent.get(10);
						var comboactionvalue   = comboactionCmp.value;
                        var combofieldvalue    = comboFieldCmp.value;


                        var attributeArray = Ext.decode(attribute);
                        var selected_attr_type = '';

                        for (var attr in attributeArray) {
                        	if (attributeArray[attr][2] != selectedActionvalue) continue;
                        	selected_attr_type = attributeArray[attr][3];
                        }

                        var colName = comboFieldCmp.store.reader.jsonData.items[selectedFieldIndex].colName;

                        if(SM.activeModule == 'Products' && field_name != 'Image') {
                        	if (field_name == 'Description' || field_name == 'Additional Description') {
                        		textField1Cmp.hide();
                        		setTextarea.show();
                        	} else {
                        		textField1Cmp.show();
                        		setTextarea.hide();	
                        	}
                        }
                        
                        if (comboactionvalue == 'YES' || comboactionvalue == 'NO' || comboactionvalue == 'SET_TO_SALES_PRICE' || comboactionvalue == 'SET_TO_REGULAR_PRICE' || (comboactionvalue == 'SET_TO' && (combofieldvalue == 'visibility' || combofieldvalue == 'taxStatus'  || combofieldvalue == 'publish' ||  storedata_array[colName] != undefined))) {
                            textField1Cmp.hide();
                        }
                                                
						if ( selectedValue.substring( 0, 14 ) == 'groupAttribute' ){
							if( selectedActionvalue == 'custom' || selected_attr_type == 'text' ){
								comboCategoriesActionCmp.hide();
								comboCategoriesActionCmp.reset();

								if ( selectedActionvalue == 'custom' ) {
									textField1Cmp.emptyText = getText('Enter Attribute Name') + '...';
									textField1Cmp.regex = null;
									textField1Cmp.show();
									textField1Cmp.reset();
								} else {
									textField1Cmp.hide();
								}
								
								if( field_name != 'Change Attribute' ) {
									textField2Cmp.emptyText = getText('Enter values') + '...';

									Ext.QuickTips.register({
									    target: textField2Cmp.el,
									    title: getText('Important:'),
									    text: getText('For more than one values, use pipe (|) as delimiter')
									});	
								} else {
									textField2Cmp.emptyText = getText('Enter the Value') + '...';
									Ext.QuickTips.register({
									    target: textField2Cmp.el,
									    title: getText('Important:'),
									    text: getText('Enter only single value')
									});	
								}
								
								
								textField2Cmp.show();
								textField2Cmp.reset();
								setTextarea.hide();	
							} else {
								comboCategoriesActionCmp.hide();
								comboCategoriesActionCmp.reset();
								textField2Cmp.emptyText = getText('Enter Attribute Name') + '...';
								textField2Cmp.reset();
								textField1Cmp.hide();
								textField2Cmp.hide();
								setTextarea.hide();	
								var object = {
												// url:jsonURL
												url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
												,method:'post'
												,callback: function(options, success, response)	{
													var myJsonObj = Ext.decode(response.responseText);
													
													if(true !== success){
														Ext.notification.msg('Failed',response.responseText) ;
														return;
													} try{

														if ( myJsonObj[0][2] == 'text' && field_name == 'Add Attribute' ) {
															textField2Cmp.show();
														} else if ( myJsonObj != '' ) {
															comboCategoriesActionCmp.show();
															categoryStore.loadData(myJsonObj);
														}
														return;
													} catch(e){
														var err = e.toString();
														Ext.notification.msg('Error', err);
														return;
													}
												}
												,scope:this
												,params:
												{
													cmd: 'getTerms',
											 		active_module: SM.activeModule,
											 		action_name: selectedValue,
											 		attribute_name: selectedActionvalue,
											 		security: SM_NONCE,
											 		file:  jsonURL
												}
											};
								Ext.Ajax.request(object);
							}
						} else if ( selectedValue.substring( 0, 5 ) == 'group' ) {
                                                    textField1Cmp.hide();
                                                    textField2Cmp.hide();
                                                    setTextarea.hide();	
                                                }
					}
				}
			},'',{
				xtype: 'combo',
				allowBlank: false,
				typeAhead: true,
				hidden: false,
				width: 170,
				align: 'center',
				store: countriesStore,
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
						var regions			   = new Array();
						var toolbarParent      = this.findParentByType(batchUpdateToolbarInstance, true);
						var comboRegionCmp     = toolbarParent.get(7);
						var comboCountriesCmp  = toolbarParent.get(4);
						var selectedValue      = comboCountriesCmp.value;
						
						var object = {
							// url:jsonURL
							url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file'
							,method:'post'
							,callback: function(options, success, response)	{
								var myJsonObj = Ext.decode(response.responseText);
								if(true !== success){
									Ext.notification.msg('Failed',response.responseText);
									return;
								}try{
									if ( myJsonObj != '' ) {	
										for ( var i = 0; i < myJsonObj.items.length; i++ ) {
											regions[i] = new Array();
											regions[i][0] = myJsonObj.items[i].id;
											regions[i][1] = myJsonObj.items[i].name;
										}
										comboRegionCmp.store.loadData(regions);
										
										comboRegionCmp.show();
										comboRegionCmp.reset();
										toolbarParent.get(6).hide();
									} else {
										comboRegionCmp.hide();
										toolbarParent.get(5).show();
										toolbarParent.get(6).show();
									}
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
								cmd: 'getRegion',
								active_module: SM.activeModule,
								country_id: selectedValue,
								security: SM_NONCE,
								file:  jsonURL				
							}
						};
						Ext.Ajax.request(object);
					}
				}
			},'',{
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
				hidden: true,
				selectOnFocus: true,
				listeners: {
					beforerender: function( cmp ) {
						cmp.emptyText = getText('Enter the value') + '...'; 
					}
				}
			},{
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
				emptyText: getText('Select a Value') + '...',
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
						var comboRegionCmp     = toolbarParent.get(7);
						var selectedFieldIndex = comboFieldCmp.selectedIndex;
						var selectedValue      = comboFieldCmp.value;
						var field_name		   = toolbarParent.items.items[0].store.reader.jsonData.items[selectedFieldIndex].name;
						var colName			   = toolbarParent.items.items[0].store.reader.jsonData.items[selectedFieldIndex].colName;
						var actionType		   = toolbarParent.items.items[0].store.reader.jsonData.items[selectedFieldIndex].actionType;
					
						if (SM.activeModule == 'Products') {
							if ( selectedValue.substring( 0, 14 ) != 'groupAttribute' ){
								if ( colName == '_tax_status' ) {
                                    categoryStore.loadData( taxStatusStoreData );
                                } else if ( field_name == 'Visibility' ) {
                                    categoryStore.loadData( visibilityStoreData );
                                } else if ( field_name == 'Publish' ) {
                                    categoryStore.loadData( postStatusStoreData );
                                } else if ( colName == 'product_type' ) {
                                	categoryStore.loadData( postTypeStoreData );
                                } else if (storedata_array[colName] != undefined) {
                                	categoryStore.loadData( storedata_array[colName] );
                                } else {
                                    var category = categories["category-"+SM['productsCols'][selectedValue].colFilter];

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
						} else if(SM.activeModule == 'Orders' && field_name == 'Order Status') {
							
                                                        var temp =new Array();
                                                        temp[0] = 'Pending';
                                                        temp[1] = 'Failed';
                                                        temp[2] = 'On Hold';
                                                        temp[3] = 'Processing';
                                                        temp[4] = 'Completed';
                                                        temp[5] = 'Refunded';
                                                        temp[6] = 'Cancelled';
                                                        
                                                        
//							comboRegionCmp.hide();
//                                                        this.store = orderStatusStore;                                                        
                                                        comboRegionCmp.store.loadData(orderStatusStoreData);
                                                        comboRegionCmp.show();
							comboRegionCmp.reset();
                                                        
						}
				    }
				}
			},{
				xtype: 'textfield',
				width: 170,
				allowBlank: false,
				style: {
					fontSize: '12px',
					paddingLeft: '2px'
				},
				enableKeyEvents: true,
				displayField: 'fullname',
				emptyText: getText('Enter values') + '...',
				cls: 'searchPanel',
				hidden: true,
				listeners: {
					render: function( cmp ) {
						Ext.QuickTips.register({
						    target: cmp.getEl(),
						    title: getText('Important:'),
						    text: getText('For more than one values, use pipe (|) as delimiter')
						});
					}
				},
				selectOnFocus: true
			},{
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
                        },{
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

				icon: imgURL + 'warning.png',
				tooltip: getText('Caution It is critical to put valid data in the expected format otherwise it can wreak havoc')
			},
			{
				text: getText('Add Row'),
				tooltip: getText('Add a new row') ,
				ref: 'addRowButton',
				id: 'bu_add_row',
				// icon: imgURL + 'add_row.png',
				handler: function () {
					var newBatchUpdateToolbar = new batchUpdateToolbarInstance();
					toolbarCount++;
					batchUpdatePanel.add(newBatchUpdateToolbar);
					batchUpdatePanel.doLayout();
		            
		            var count_toolbar = toolbarCount-2;

			        if (count_toolbar == 0) {
			        	batchUpdatePanel.items.items[count_toolbar].items.items[0].items.items[13].hide();
			        	batchUpdateToolbar.items.items[2].hide();
			        } else {
			        	batchUpdatePanel.items.items[count_toolbar].items.items[13].hide();
			        }

		            batchUpdatePanel.items.items[toolbarCount-1].items.items[12].hide();
				}
			},{
				// icon: imgURL + 'del_row.png',
				tooltip: getText('Delete Row'),
				id: 'bu_delete_row',
				handler: function () {


					toolbarCount--;
					// var count_toolbar = toolbarCount-2;

					var toolbarParent = this.findParentByType(batchUpdateToolbarInstance, true);

					batchUpdatePanel.remove(toolbarParent);

			        if (toolbarCount == 1) {
			        	batchUpdateToolbar.items.items[2].show();
			        } else {
			        	batchUpdatePanel.items.items[toolbarCount-1].items.items[13].show();
			        }
				}
			}]
		}, config);
		batchUpdateToolbarInstance.superclass.constructor.call(this, config);
	}
});

var batchUpdateToolbar = new Ext.Toolbar({
	id: 'tl',
	cls: 'batchtoolbar',
	items: [new batchUpdateToolbarInstance(),'->',
		{
				text: getText('Add Row'),
				tooltip: getText('Add a new row') ,
				ref: 'addRowButton',
				// icon: imgURL + 'add_row.png',
				id: 'bu_add_row_main',
				handler: function () {
					var newBatchUpdateToolbar = new batchUpdateToolbarInstance();
					toolbarCount++;
					batchUpdatePanel.add(newBatchUpdateToolbar);
					batchUpdatePanel.doLayout();
		            
		            var count_toolbar = toolbarCount-2;

			        if (count_toolbar == 0) {
			        	batchUpdatePanel.items.items[count_toolbar].items.items[0].items.items[13].hide();
			        	batchUpdateToolbar.items.items[2].hide();
			        } else {
			        	batchUpdatePanel.items.items[count_toolbar].items.items[13].hide();
			        }
		            
		            batchUpdatePanel.items.items[toolbarCount-1].items.items[12].hide();
				}
		}]
});
batchUpdateToolbar.get(0).get(12).hide(); //hide warning row icon from first toolbar.
batchUpdateToolbar.get(0).get(13).hide(); //hide Add row btn from first toolbar.
batchUpdateToolbar.get(0).get(14).hide(); //hide delete row icon from first toolbar.


var batchUpdatePanel = new Ext.Panel({
	animCollapse: true,
	autoScroll: true,
	Height: 500,
	width: 900,
	bbar: [
            {text: getText('Reset'),
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
				flag = 1;
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

				var other_meta_flag = 0;

				for(sb=0; sb<toolbarCount; sb++) {

					if (sb == 0) {
						colId = batchUpdatePanel.items.items[sb].items.items[0].items.items[0].value;
					} else {
						colId = batchUpdatePanel.items.items[sb].items.items[0].value;
					}

					if (colId == 'other_meta') {
						other_meta_flag = 1;
						break;
					}
				}

				if (SM.advanced_search_query != '' || SM.searchTextField.getValue() != '') {
					products_search_flag = true;
				}

			}
			batchUpdateRecords(batchUpdatePanel,toolbarCount,cnt_array,store,jsonURL,batchUpdateWindow,radioValue,flag,pagingToolbar,products_search_flag,batch_limit,SM_IS_WOO16,SM_IS_WOO21,SM_IS_WOO22,SM_IS_WOO30);
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
		    	
		        {boxLabel: 'Selected items', name: 'rb-batch', id:'sm_batch_selected_items_option' ,inputValue: 1, checked: true, width: 100},
		        {boxLabel: 'All items in store (including Variations)', name: 'rb-batch', id:'sm_batch_entire_store_option', inputValue: 2, width: 350}
		    ]
		})        
	]
});

batchUpdateWindow = new Ext.Window({
	title: getText('Batch Update - available only in Pro version'),
	animEl: 'BU',
	id: 'batch_update_window',
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
		stateId : 'billingDetailsWindowWoo',
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
		html: '<iframe src='+ ordersDetailsLink + '' + recordId +'&action=edit style="width:100%;height:100%;border:none;"><p> ' + getText('Your browser does not support iframes.') + '</p></iframe>'
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
	var name_emailid_arr = name_emailid.split(' ');
	var mix_emailId      = Ext.util.Format.stripTags(name_emailid_arr[name_emailid_arr.length -1]);
	var emailId          = mix_emailId.substring(1,mix_emailId.length-1);
	// END
	batchUpdateWindow.loadMask.show();
	clearTimeout(SM.colModelTimeoutId);
	SM.colModelTimeoutId = showCustomersView.defer(100,this,[emailId]);
	SM.searchTextField.setValue(emailId);
};

// ============ Customers ================

    //Code to enable disabling any column to be moved to the place of the one which cannot be dragged
        Ext.CustomersColumnModel = Ext.extend(Ext.grid.ColumnModel, {
          moveColumn: function (oldIndex, newIndex) {

            if (newIndex == 14) {
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
			id: '_billing_first_name',
			dataIndex: '_billing_first_name',
			tooltip: getText('Billing First Name'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 150
		},{
			header: getText('Last Name'),
			id: '_billing_last_name',
			dataIndex: '_billing_last_name',
			tooltip: getText('Billing Last Name'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 150
		},{
			header: getText('Email'),
			id: '_billing_email',
			dataIndex: '_billing_email',
			tooltip: getText('Email Address'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 200
		},{
			header: getText('Address 1'),
			id: '_billing_address_1',
			dataIndex: '_billing_address_1',
			tooltip: getText('Billing Address 1'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: true,
			}),
			width: 170
		},{
			header: getText('Address 2'),
			id: '_billing_address_2',
			dataIndex: '_billing_address_2',
			tooltip: getText('Billing Address 2'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: true
			}),
			width: 170
		},{
			header: getText('Postal Code'),
			id: '_billing_postcode',
			dataIndex: '_billing_postcode',
			tooltip: getText('Billing Postal Code'),
			editable: false,
			editor: new fm.TextField({
				allowBlank: true,
				allowNegative: false
			}),
			width: 115
		},{
			header: getText('City'),
			id: '_billing_city',
			dataIndex: '_billing_city',
			tooltip: getText('Billing City'),
			align: 'left',
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 130
		},
		{
			header: getText('Region'),
			id: '_billing_state',
			dataIndex: '_billing_state',
			tooltip: getText('Billing Region'), 
			align: 'center',
			width: 100
		},
		{
			header: getText('Country'),
			id: '_billing_country',
			dataIndex: '_billing_country',
			tooltip: getText('Billing Country'),
			width: 120
		},
		{
			header: getText('Last Order Total'),
			id: 'total_purchased', //@todo: change the id to Total_Purchased
			dataIndex: '_order_total',
			tooltip: getText('Last Order Total'),
			align: 'right',
			width: 90			
		},{
			header: getText('Last Order'),
			id: 'last_order',
			dataIndex: 'last_order',
			tooltip: getText('Last Order Details'), 
			width: 210			
		},{   
			header: getText('Phone Number'),
			id: '_billing_phone',
			dataIndex: '_billing_phone',
			tooltip: getText('Phone Number'), 
			editable: false,
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
            width: 90
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
            id: 'customer_scroll',
            Fixed: true,
            sortable:false,
            menuDisabled : true,
            hideable: false,
            dragable:false,
            width: 8
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
		customersColumnModel.columns[customersColumnModel.findColumnIndex('last_order')].align = 'center';
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
		{name:'_billing_first_name',type:'string'},		
		{name:'_billing_last_name',type:'string'},				
		{name:'_billing_address_1',type:'string'},
		{name:'_billing_address_2',type:'string'},
		{name:'_billing_city', type:'string'},		
		{name:'_billing_state', type:'string'},
		{name:'_billing_country', type:'string'},		
		{name:'_billing_postcode',type:'string'},
		{name:'_billing_email',type:'string'},
		{name:'_billing_phone', type:'string'},	
		{name:'_order_total',type:totPurDataType},		
		{name:'last_order', type:'string'},
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
            SM_IS_WOO16: SM_IS_WOO16,
            SM_IS_WOO21: SM_IS_WOO21,
            SM_IS_WOO22: SM_IS_WOO22,
            SM_IS_WOO30: SM_IS_WOO30,
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

			customersColumnModel.setEditable(1,true);
			customersColumnModel.setEditable(2,true);
			customersColumnModel.setEditable(3,true);
			customersColumnModel.setEditable(4,true);
			customersColumnModel.setEditable(5,true);
			customersColumnModel.setEditable(6,true);
			customersColumnModel.setEditable(11,true);
			
			if(cellClicked == false){
				ordersStore.baseParams.searchText = ''; //clear the baseParams for ordersStore
				SM.searchTextField.reset(); 			//to reset the searchTextField
			}

			hidePrintButton();
			hideDeleteButton();
			hideAddProductButton();
			hideDuplicateButton();
			hideBatchUpdateButton();
			hideSaveButton();
			hideExportButton();
			showBatchUpdateButton();
			showSaveButton();
			showExportButton();
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

			textfield.show();
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
	

	//COUPONS//
        
        var str = new Array();
        var coupon_render_fields = new Array();

        str[0] = editorGridSelectionModel;

        for (i = 0; i < couponFields.coupon_dashbd.column.items.length; i++) {
        	var coupon = new Object();
        	coupon.header = couponFields.coupon_dashbd.column.items[i].name;
        	coupon.dataIndex = couponFields.coupon_dashbd.column.items[i].value;
        	coupon.width = 50;
        	coupon.editable= true;

        	if (coupon.header == 'id') {
        		coupon.hideable = false;
				coupon.hidden = true;
        	}

        	if (couponFields.coupon_dashbd.column.items[i].type == "yesno") {
        		coupon.editor = yesNoCombo_inline;
        	} else if (couponFields.coupon_dashbd.column.items[i].type == "datetime") {
        		coupon.editor = new fm.DateField({
                					format: 'm/d/y',
                					editable: false,
                					allowBlank: false,
									allowNegative: false,
                					width: 50
            					})
        	} else if (couponFields.coupon_dashbd.column.items[i].type == "select") {

        		var select = new Array();
        		for (j = 0; j < couponFields.coupon_dashbd.column.items[i].data.length; j++) {
        			select[j] = new Array();

        			select[j][0] = couponFields.coupon_dashbd.column.items[i].data[j][0];
        			select[j][1] = couponFields.coupon_dashbd.column.items[i].data[j][1];
        		}

        		coupon.editor = new Ext.form.ComboBox({
									typeAhead: true,
									triggerAction: 'all',
									lazyRender:true,
									editable: false,
									mode: 'local',
									store: new Ext.data.ArrayStore({
												id: 0,
												fields: ['value','name'],
												data: select
											}),
									valueField: 'value',
									displayField: 'name'
								});
        	} else {
        		coupon.editor= new fm.TextField({ allowBlank: true, allowNegative: true});	
        	}

        	
        	var coupon_render = new Object();
        	coupon_render.name = couponFields.coupon_dashbd.column.items[i].value;
        	coupon_render.type = couponFields.coupon_dashbd.column.items[i].type;
        	coupon_render.table = couponFields.coupon_dashbd.column.items[i].table;

        	str[i+1] = coupon;

        	coupon_render_fields [i] = coupon_render;

        }

        var colModel1 = new Ext.grid.ColumnModel(str);
        var colModel = new Ext.grid.ColumnModel(editorGridSelectionModel,colModel1);
        var title = couponFields.coupon_dashbd.title;
        var colmodel = title + "ColumnModel";

        var  colmodel = new Ext.grid.ColumnModel({	
		columns: str,
		listeners: {
			hiddenchange: function( ColumnModel,columnIndex, hidden ){
                            state_apply = false;
			}
		},
		defaultSortable: true
	});

		var couponsJsonReader = new Ext.data.customJsonReader({
		totalProperty: 'totalCount',
		root: 'items',
		fields: coupon_render_fields
	});

		var store1 = couponFields.title + "Store";

		// create the Orders Data Store
	var couponstore = new Ext.data.Store({
		// reader: couponFields.coupon_dashbd.title + "JsonReader",
		reader: couponsJsonReader,
		proxy:new Ext.data.HttpProxy({
			// url:jsonURL
			url: (ajaxurl.indexOf('?') !== -1) ? ajaxurl + '&action=sm_include_file' : ajaxurl + '?action=sm_include_file',
		}),
		baseParams:{
			cmd: 'getData',
			active_module: couponFields.coupon_dashbd.title,
			start: 0,
			limit: limit,
			couponFields: Ext.encode(couponFields),
			SM_IS_WOO16: SM_IS_WOO16,
			SM_IS_WOO21: SM_IS_WOO21,
			SM_IS_WOO22: SM_IS_WOO22,
			SM_IS_WOO30: SM_IS_WOO30,
			security: SM_NONCE,
			file:  jsonURL
		},
		dirty:false,
		pruneModifiedRecords: true,
		listeners: {
			//Products Store onload function.
			load: function (store,records,obj) {

			}
		}
	});


	var showcouponView = function(){
        batchUpdateWindow.loadMask.show();

        SM.activeModule = couponFields.coupon_dashbd.title;
		SM.dashboardComboBox.setValue(SM.activeModule);

		jQuery("#search_switch"). hide();
        
		jQuery("#sm_advanced_search_content").hide(); //Hiding the advanced search box
		jQuery( "#sm_advanced_search_or").unbind( "click" );

		couponstore.baseParams.searchText = ''; //clear the baseParams for couponstore
		SM.searchTextField.reset(); 			  //to reset the searchTextField
		
		hidePrintButton();
		hideDeleteButton();
		hideAddProductButton();
		hideDuplicateButton();
		hideBatchUpdateButton();
		hideSaveButton();
		hideExportButton();
		showDeleteButton();
		showSaveButton();
		pagingToolbar.doLayout(true,true);
		
		for(var i=2;i<=8;i++)
		editorGrid.getTopToolbar().get(i).hide();
		editorGrid.getTopToolbar().get('incVariation').hide();
        // editorGrid.getTopToolbar().get('duplicateButton').hide();

		couponstore.load();
		editorGrid.reconfigure(couponstore,colmodel);
		pagingToolbar.bind(couponstore);
	};

	var ordersColumnModel = new Ext.OrdersColumnModel({	
		columns:[editorGridSelectionModel, //checkbox for
		{	header: getText('Order Id'),
			id: 'id_temp',
			dataIndex: 'id',
			width: 75,
			tooltip: getText('Order Id'),
			hideable:false,
			hidden:true
		},{	header: getText('Order Id'),
			id: 'id',
			dataIndex: 'display_id',
			width: 75,
			tooltip: getText('Order Id')
		},{	header: getText('Date / Time'),
			id: 'date',
			dataIndex: 'date',
			tooltip: getText('Date / Time'),
			width: 180
		},{
			header: getText('Name'), 
			id: 'name_customer',
			dataIndex: 'name',
			tooltip: getText('Customer Name'),
			width: 350
		},{
			header: getText('Amount'),
			id: '_order_total',
			dataIndex: '_order_total',
			tooltip: getText('Amount'),
			align: 'right',
			renderer: numeric_renderer(2),
			width: 100
		},{
			header: getText('Details'),
			id: 'details',
			dataIndex: 'details',
			tooltip: getText('Details'),
			width: 80
		},{
			header: getText('Payment Method'),
			id: '_payment_method',
			dataIndex: '_payment_method',
			tooltip: getText('Payment Method'),
			align: 'left',
			width: 140
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
			header: getText('Shipping Method'),
			id: '_shipping_method',
			dataIndex: '_shipping_method',
			tooltip: getText('Shipping Method'),
			width: 100
		},{   
			header: getText('Shipping First Name'),
			id: '_shipping_first_name',
			dataIndex: '_shipping_first_name',
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
			id: '_shipping_last_name',
			dataIndex: '_shipping_last_name',
			tooltip: getText('Shipping Last Name'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: false,
				allowNegative: false
			}),
			width: 130
		},{   
			header: getText('Shipping Address 1'),
			id: '_shipping_address_1',
			dataIndex: '_shipping_address_1',
			tooltip: getText('Shipping Address 1'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: true
			}),
			width: 200		
		},{   
			header: getText('Shipping Address 2'),
			id: '_shipping_address_2',
			dataIndex: '_shipping_address_2',
			tooltip: getText('Shipping Address 2'),
			hidden: true,
			editable: false,
			editor: new fm.TextField({
				allowBlank: true
			}),
			width: 200		
		},{
			header: getText('Shipping Postal Code'),
			id: '_shipping_postcode',
			dataIndex: '_shipping_postcode',
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
			id: '_shipping_city',
			dataIndex: '_shipping_city',
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
			id: '_shipping_state',
			dataIndex: '_shipping_state',
			tooltip: getText('Shipping Region'),
			align: 'center',
			hidden: true,
			width: 100		
		},
		{
			header: getText('Shipping Country'), 
			id: '_shipping_country',
			dataIndex: '_shipping_country',
			tooltip: getText('Shipping Country'),
			hidden: true,
			width: 120
		},
                {   
			header: getText('Phone Number'),
			id: '_billing_phone',
			dataIndex: '_billing_phone',
			tooltip: getText('Customer Phone Number'),
			align: 'left',
			hidden: true,
			width: 100		
		},{
                    header: getText('Order Shipping'),
                    id: '_order_shipping',
                    dataIndex: '_order_shipping',
                    tooltip: getText('Order Shipping'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Order Discount'),
                    id: '_order_discount',
                    dataIndex: '_order_discount',
                    tooltip: getText('Order Discount'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Cart Discount'),
                    id: '_cart_discount',
                    dataIndex: '_cart_discount',
                    tooltip: getText('Cart Discount'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Coupons Used'),
                    id: 'coupons',
                    dataIndex: 'coupons',
                    tooltip: getText('Coupons Used'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Order Tax'),
                    id: '_order_tax',
                    dataIndex: '_order_tax',
                    tooltip: getText('Order Tax'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Order Shipping Tax'),
                    id: '_order_shipping_tax',
                    dataIndex: '_order_shipping_tax',
                    tooltip: getText('Order Shipping Tax'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Order Excluding Tax'),
                    id: 'order_total_ex_tax',
                    dataIndex: 'order_total_ex_tax',
                    tooltip: getText('Order Total Excluding Tax'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Order Currency'),
                    id: '_order_currency',
                    dataIndex: '_order_currency',
                    tooltip: getText('Order Currency'),
                    editable: false,
                    hidden: true,
                    align: 'left',
                    width: 90
                },{
                    header: getText('Customer Provided Note'),
                    id: 'customer_provided_note',
                    dataIndex: 'customer_provided_note',
                    tooltip: getText('Customer Provided Note'),
                    editable: true,
                    hidden: true,
                    align: 'left',
                    editor: new fm.TextField({
						allowBlank: true,
		                width: 250
					}),
                    width: 90
                },{
                        header: '',
                        id: 'orders_scroll',
                        width: 8,
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
			{name:'id',type:'string'},   //DataType set to string for Sequential Orders compatibility
			{name:'display_id',type:'string'},   //DataType set to string for Sequential Orders compatibility
			{name:'date',type:'string'},
			{name:'name',type:'string'},
			{name:'_order_total', type:'float'},
			{name:'details', type:'string'},
			{name:'_payment_method',type:'string'},
			{name:'order_status', type:'string'},
			{name:'_shipping_method', type:'string'},
			{name:'_shipping_first_name', type:'string'},
			{name:'_shipping_last_name', type:'string'},
			{name:'_shipping_address_1', type:'string'},
			{name:'_shipping_address_2', type:'string'},
			{name:'_shipping_city', type:'string'},
			{name:'_shipping_country', type:'string'},
			{name:'_shipping_state', type:'string'},  
			{name:'_shipping_postcode', type:'string'},
		    {name:'_billing_phone', type:'string'},
		    {name:'_order_shipping', type:'string'},
		    {name:'_order_discount', type:'string'},
		    {name:'_cart_discount', type:'string'},
		    {name:'coupons', type:'string'},
		    {name:'_order_tax', type:'string'},
		    {name:'_order_shipping_tax', type:'string'},
		    {name:'order_total_ex_tax', type:'string'},
		    {name:'customer_provided_note', type:'string'},
		    {name:'_order_currency', type:'string'}
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
            SM_IS_WOO16: SM_IS_WOO16,
            SM_IS_WOO21: SM_IS_WOO21,
            SM_IS_WOO22: SM_IS_WOO22,
            SM_IS_WOO30: SM_IS_WOO30,
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

			ordersColumnModel.setEditable(7,true);
			ordersColumnModel.setEditable(9,true);
			ordersColumnModel.setEditable(10,true);
			ordersColumnModel.setEditable(11,true);
			ordersColumnModel.setEditable(12,true);
			ordersColumnModel.setEditable(13,true);

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
			hideBatchUpdateButton();
			hideSaveButton();
			hideExportButton();
			wooShowPrintButton();
			showDeleteButton();
			showBatchUpdateButton();
			showSaveButton();
			showExportButton();
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
			textfield.hide();

		} catch(e) {
			var err = e.toString();
			Ext.notification.msg('Error', err);
		}
	};
	
	// ======= orders =====

        

        //code to get the width of SM w.r.to width of the browser

        var wWidth = 0,
        	hHeight = 0;

        if ( document.documentElement.offsetWidth > 557 ) {
        	// if ( !jQuery(document.body).hasClass('folded') ) {
	        //     wWidth  = document.documentElement.offsetWidth - 183;
	        // }
	        // else {
	        //     wWidth  = document.documentElement.offsetWidth - 67;
	        // }	
	        wWidth  = document.documentElement.offsetWidth - 67;
	        hHeight  = document.documentElement.offsetHeight - 120;
        } else {
        	wWidth = 1000;
        	hHeight = 1000;
        }
	
        // wWidth = 480;

        var variation_state=""; // Variable to handle the incVariation checkbox state
        var column_move = false;

	// Grid panel for the records to display

	var flag_save_lite = 0;
	var row_index_save_lite = new Array();

	editorGrid = new Ext.grid.EditorGridPanel({
	stateId : 'productsEditorGridPanelWoo',
	stateEvents : ['viewready','beforerender','columnresize', 'columnmove', 'columnvisible', 'columnsort','reconfigure'],
	stateful: true,
    defaults:{ autoScroll:true },
	store: productsStore,
	cm: productsColumnModel,
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
        layout: 'fit',
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
			'<button id="sm_advanced_search_submit" style="float: left;margin-top: -26px;margin-left: 88%;cursor: pointer;"> Search </button>'+
			'</div>',

			{xtype: 'tbspacer', id:'afterDateMenuTbspacer', width: 10},

			'<label title="Switch to simple search" id="search_switch" style="display:none;"> Simple Search </label>',

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
							 			if ( SM.dashboard_state == 'Products' ) {
					                        mask.show();
					                        SM.incVariation  = bool;
							 				productsStore.setBaseParam('incVariation', SM.incVariation);

							 				// Code for getting the advanced search query
											var search_query = new Array();
											jQuery('input[id^="sm_advanced_search_box_value_"]').each(function() {
											    search_query.push(jQuery(this).val());
											});

											if (search_query != '') {
												productsStore.setBaseParam('search_query[]', search_query);
												productsStore.setBaseParam('search', 'advanced_search');
											}

							 				getVariations(productsStore.baseParams,productsColumnModel,productsStore);
							 			}
							 		}
							 	}
							},
                         // {xtype: 'tbspacer',width: 10, id:'afterShowVariation'},
                         
                        ],

                        autoScroll:true,
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

							//COUPONS == Pushing coupons in dashboard
							dashboardComboStore.push( new Array( 3, couponFields.coupon_dashbd.title, couponFields.coupon_dashbd.title ) );
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
						},scope: SM.dashboardComboBox
						,params:
						{
							cmd:'getRolesDashboard',
							security: SM_NONCE,
							file:  jsonURL
						}};
				Ext.Ajax.request(object);
		},

		cellclick: function(editorGrid,rowIndex, columnIndex, e) {
			try{

				var record  = editorGrid.getStore().getAt(rowIndex);
                                cellClicked = true;
				var editLinkColumnIndex   	  = productsColumnModel.findColumnIndex('edit_url'),
					editImageColumnIndex   	  = productsColumnModel.findColumnIndex(SM.productsCols.image.colName),
					prodTypeColumnIndex       = productsColumnModel.findColumnIndex('type'),
					totalPurchasedColumnIndex = customersColumnModel.findColumnIndex('_order_total'),
					lastOrderColumnIndex      = customersColumnModel.findColumnIndex('last_order'),
					nameLinkColumnIndex       = ordersColumnModel.findColumnIndex('name'),
					orderDetailsColumnIndex   = ordersColumnModel.findColumnIndex('details'),					
					publishColumnIndex        = productsColumnModel.findColumnIndex(SM.productsCols.publish.colName),
					nameColumnIndex           = productsColumnModel.findColumnIndex(SM.productsCols.name.colName),
					salePriceFromColumnIndex  = productsColumnModel.findColumnIndex(SM.productsCols.salePriceFrom.colName),
					salePriceToColumnIndex    = productsColumnModel.findColumnIndex(SM.productsCols.salePriceTo.colName),
					descColumnIndex        	  = productsColumnModel.findColumnIndex(SM.productsCols.desc.colName),
					addDescColumnIndex        = productsColumnModel.findColumnIndex(SM.productsCols.addDesc.colName),
                    visibilityColumnIndex     = productsColumnModel.findColumnIndex(SM.productsCols.visibility.colName),
                    taxStatusColumnIndex      = productsColumnModel.findColumnIndex(SM.productsCols.taxStatus.colName),
                    productTypeColumnIndex    = productsColumnModel.findColumnIndex(SM.productsCols.product_type.colName);

				if(SM.activeModule == 'Orders'){
					if ( fileExists != 1 && ( columnIndex == orderDetailsColumnIndex || columnIndex == nameLinkColumnIndex ) ) {
						Ext.notification.msg('Smart Manager', getText('This feature is available only in Pro version')); 
						return;
					}
					if(columnIndex == orderDetailsColumnIndex){
					// showing order details of selected id by loading the web page in a Ext window instance.
						billingDetailsIframe(record.id);
					}else if(columnIndex == nameLinkColumnIndex){
					// check for any unsaved data and show details of the respective id sent as argument.
						checkModifiedAndshowDetails(record,rowIndex);
					}
					
				// Show WPeC's product edit page in a Ext window instance.
				}else if(SM.activeModule == 'Products'){

					var customColIndex = 0,
						temp = '';

					//code for handling custom columns
					for( var key in sm_prod_custom_cols_formatted ) {

						if( !SM.productsCols.hasOwnProperty(key) ) {
							continue;
						}

						if( SM.productsCols[key].hasOwnProperty('variation_values') ) {
							customColIndex = productsColumnModel.findColumnIndex(SM.productsCols[key].colName);

							if (columnIndex == customColIndex) {
								if(record.get('post_parent') == 0){

									if( productsColumnModel.getColumnById(customColIndex).hasOwnProperty('editable_variation')
											&& productsColumnModel.getColumnById(customColIndex).editable_variation === true) {
										temp = productsColumnModel.getColumnById(customColIndex).editor;
										productsColumnModel.getColumnById(customColIndex).editor = productsColumnModel.getColumnById(customColIndex).editor_variation;
			                            productsColumnModel.getColumnById(customColIndex).editor_variation = temp;
			                            productsColumnModel.getColumnById(customColIndex).editable_variation = false;
									}

		                        } else {
		                        	if( productsColumnModel.getColumnById(customColIndex).hasOwnProperty('editable_variation')
											&& productsColumnModel.getColumnById(customColIndex).editable_variation === true) {
										continue;
									}
									
									temp = productsColumnModel.getColumnById(customColIndex).editor;
									productsColumnModel.getColumnById(customColIndex).editor = productsColumnModel.getColumnById(customColIndex).editor_variation;
		                            productsColumnModel.getColumnById(customColIndex).editor_variation = temp;
		                            productsColumnModel.getColumnById(customColIndex).editable_variation = true;
		                        }
		                    }
						}
					}

					if(columnIndex == editLinkColumnIndex) {
						var productsDetailsWindow = new Ext.Window({
							stateId : 'productsDetailsWindowWoo',
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
							html: '<iframe src='+ productsDetailsLink + '' + record.id +' style="width:100%;height:100%;border:none;"><p> ' + getText('Your browser does not support iframes.') + '</p></iframe>'
						});
						// To disable Product's details window for product variations
						if(record.get('post_parent') == 0 || record['json']['product_type'] == "grouped"){
							productsDetailsWindow.show('editLink');
						}
					
					// show Inherit option only for the product variations otherwise show only Published & Draft 	
					}else if(columnIndex == publishColumnIndex || columnIndex == nameColumnIndex || columnIndex == descColumnIndex || columnIndex == addDescColumnIndex || columnIndex == visibilityColumnIndex || columnIndex == taxStatusColumnIndex){
							if(record.get('post_parent') == 0  || record['json']['product_type'] == "grouped"){
								productsColumnModel.setEditable(columnIndex,true);
								productsColumnModel.getColumnById('publish').editor = newProductStatusCombo;
							}else{
								productsColumnModel.getColumnById('publish').editor = productStatusCombo;
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if (columnIndex == visibilityColumnIndex){
							if(record.get('post_parent') == 0){
								productsColumnModel.setEditable(columnIndex,true);
                                productsColumnModel.getColumnById('visibility').editor = visibilityCombo;   
							}else{
                                productsColumnModel.getColumnById('visibility').editor = visibilityCombo;   
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if (columnIndex == salePriceFromColumnIndex || columnIndex == salePriceToColumnIndex){
							if(record.get('post_parent') > 0 || record['json']['product_type'] != "variable"){
								productsColumnModel.setEditable(columnIndex,true);
							}else{
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if (columnIndex == taxStatusColumnIndex){
							if(record.get('post_parent') == 0){
								productsColumnModel.setEditable(columnIndex,true);
                                productsColumnModel.getColumnById('taxStatus').editor = taxStatusCombo;
							}else{
                                productsColumnModel.getColumnById('taxStatus').editor = taxStatusCombo;
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if (columnIndex == productTypeColumnIndex){
							if(record.get('post_parent') == 0){
								productsColumnModel.setEditable(columnIndex,true);
                                productsColumnModel.getColumnById('product_type').editor = productTypeCombo;
							}else{
                                productsColumnModel.getColumnById('product_type').editor = productTypeCombo;
								productsColumnModel.setEditable(columnIndex,false);
							}
					} else if ( columnIndex == editImageColumnIndex ) {
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
				else if(SM.activeModule == 'Customers'){
					if(fileExists == 1){
						if(columnIndex == totalPurchasedColumnIndex){
                            checkModifiedAndshowDetails(record,rowIndex);
						}else if(columnIndex == lastOrderColumnIndex){
							billingDetailsIframe(record.json.id);
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
					var bill_country = SM.activeRecord.data['_billing_country'];
					var curCountry;
					    
						if(SM.curDataIndex == '_billing_country' || SM.curDataIndex == '_billing_state') {
							curCountry = bill_country;
						}
				}
			}else if(SM.activeModule == 'Orders') {
				var ship_country = SM.activeRecord.data['_shipping_country'];
				
				if(SM.curDataIndex == '_shipping_country' || SM.curDataIndex == '_shipping_state') {
					 curCountry = ship_country;
				}
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
                    
                    // setInterval(function(){state_update()}, 60000);
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
	
	//disable inline editing for products
	// var productsColumnCount = productsColumnModel.getColumnCount();
	// for(var i=6; i<productsColumnCount; i++)
	// productsColumnModel.setEditable(i,false);
	
	//disable inline editing for customers
	var customersColumnCount = customersColumnModel.getColumnCount();
	for(var i=1; i<customersColumnCount; i++)
		customersColumnModel.setEditable(i,false);	
}

	}catch(e){
		var err = e.toString();
		Ext.notification.msg('Error', err);
		return;
	}
});