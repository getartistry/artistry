jQuery(function($){

	var last_ids = [];
	/* input element that should recieve the data from the conditions box */
	var target;

	function getKeys(e){var t=[];for(var n in e){if(!e.hasOwnProperty(n))continue;t.push(n)}return t}

	var getDocHeight = function() {
		var D = document;
		return Math.max(
			Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
			Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
			Math.max(D.body.clientHeight, D.documentElement.clientHeight)
		);
	};

	var add_conditions_button = function( $el, id, value ) {
		value = value || '';
		$el.find( '.locations-row-links' ).empty().html( '<input type="hidden" name="'+ id +'" value=\''+ value +'\' /><a href="#" class="themify-cm-conditions">' + themify_cm.lang.conditions + '</a> <a class="themify-cm-remove" href="#">x</a>' );
		return $el;
	}

	$('.menu-locations .locations-row-links').empty();

	$('body').on( 'click', '.themify-cm-conditions', function(e){
		e.preventDefault();

		target = $(this).prev();
		var top = $(document).scrollTop() + 80,
			$lightbox = $("#themify-cm-conditions");

		$('#themify-cm-overlay').show();
		$.ajax({
			'type' : 'POST',
			url: ajaxurl,
			data: {
				action: 'themify_cm_get_conditions',
				selected: target.val(),
				// nonce: themify_js_vars.nonce
			},
			success: function(data){
				$( '.lightbox_container', $lightbox ).append(data);
				$lightbox
				.show()
				.css('top', getDocHeight())
				.animate({
					'top': top
				}, 800 );
				$('#visibility-tabs', $lightbox).tabs();
				$('#visibility-tabs .themify-visibility-inner-tabs', $lightbox).tabs();
			}
		});
		return false;
	} );

	$('body').on( 'click', '#themify-cm-close, #themify-cm-overlay', function(e){
		e.preventDefault();
		$('#themify-cm-conditions').animate({
			'top': getDocHeight()
		}, 800, function() {
			$('#themify-cm-overlay').hide();
			$('#themify-cm-conditions').hide().find( '.lightbox_container' ).empty();
		});
		return false;
	});

	function add_assignment( $menu_row, new_id, selected_menu, condition_value ) {
		var clone = $menu_row.clone().removeClass( 'cm-location' );
		clone.find( '.menu-location-title' ).empty();
		var menu_id = clone.find( 'select' ).attr('name').match( /menu-locations\[(.*)\]/ )[1];
		if( new_id == null ) {
			if( typeof last_ids[menu_id] == 'undefined' ) {
				last_ids[menu_id] = parseInt( $( getKeys( themify_cm.options[menu_id] ) ).last()[0] );
				if( ! $.isNumeric( last_ids[menu_id] ) )
					last_ids[menu_id] = 1;
			}
			new_id = last_ids[menu_id]++;
		}
		clone.find( 'select' ).find('option[value="0"]').text( themify_cm.lang.disable_menu ).before( '<option value=""></option>' ).end().val( selected_menu ).attr( 'name', 'themify_cm[' + menu_id + '][' + new_id + '][menu]' );
		clone = add_conditions_button( clone, 'themify_cm[' + menu_id + '][' + new_id + '][condition]', condition_value );
		clone.insertBefore( jQuery( '.menu-locations tr[data-menu="'+ menu_id +'"]' ) );
	}

	$('body').on( 'click', '.themify-mc-add-assignment', function(e){
		add_assignment( $( '#locations-' + $(this).closest( 'tr' ).attr( 'data-menu' ) ).closest('tr') );
		return false;
	});

	$('body').on('click', '.themify-cm-save', function(){
		var data = $('#themify-cm-conditions form').serialize();
		/* save the data from conditions lightbox */
		target.val( data );
		/* close conditions lightbox */
		$( '#themify-cm-close' ).click();
		return false;
	});

	$('body').on('click', '.themify-cm-remove', function(){
		$(this).closest( 'tr' ).fadeOut(function(){
			$(this).remove();
		});
		return false;
	});

	$('#themify-cm-conditions').on('click', '.uncheck-all', function(){
		$( 'input:checkbox', $('#themify-cm-conditions') ).removeAttr( 'checked' );
		return false;
	});

	/* add the Menu Replacement button */
	$.each( themify_cm.nav_menus, function( i, v ){
		$( '#locations-' + v ).closest('tr').after( '<tr class="cm-replacement-button" data-menu="'+ v +'"><td>&nbsp;</td><td><a href="#" class="themify-mc-add-assignment">'+ themify_cm.lang.add_assignment +'</a></td></tr>' );
	} );

	/* add the previously saved menu replacements */
	$.each( themify_cm.options, function( menu, assignments ){
		if( typeof assignments == 'object' ) {
			$.each( assignments, function( id, value ){
				add_assignment( $( '#locations-' + menu ).closest( 'tr' ), id, value['menu'], value['condition'] );
				last_ids[menu] = ++id;
			} );
		}
	});

	$( '#themify-cm-about' ).appendTo( 'p.button-controls' );
});