var cpLayers = '';

( function( $ ) {

    /**
     * Responsible for managing layers of the panel
     * @since 0.0.1
     */
    cpLayers = {

        /* initial counter for the z-index */
        initialCounter : parseInt(11),

        /**
         * assign a z-index to the div; while creating the draggable container gotta call 'em
         */
        assignZIndex : function( elem, className, setObject, is_undo) {

            className = className || 'window-container';
            var z_index_val = '';

            /* check if the div is existing */
            var isExisting = jQuery('.'+className).length;
            if(!isExisting) { //I am Fresher 
             	z_index_val = this.initialCounter;
                this.counter = this.initialCounter;
            } else {
                var max = this.getMaxZIndex(className);
                z_index_val = ++max;
            }

            jQuery(elem).css( 'z-index', z_index_val );

            var for_edit = elem.attr("id");
    		var name = 'layerindex';
    		var value = z_index_val;

            var current_step = step_id;
            if( jQuery(elem).attr("data-overlay-respective") == 'true' ) {
                current_step = 'common';
            }

            if( setObject ) {

        		bmodel.setElementID( current_step, for_edit );

        		// save value in modal data
        		bmodel.setModalValue( for_edit, current_step, name, value, is_undo );
            }

        },

        /**
         * set a preset to the shape
         */
        assignShapePreset : function(elem, preset) {
            
            var for_edit = elem.attr("id");
    		var name = 'shape_preset';
    		var value = preset;

    		bmodel.setElementID( step_id, for_edit );

    		// save value in modal data
    		bmodel.setModalValue( for_edit, step_id, name, value, step_id );

        },

        /**
         * bring the clicked element to the forward
         */
        bringForward : function(elem, className) {
            
            var current_elem_index = parseInt( elem.css("z-index") );
            var current_panel = step_id + 1;
            // get all elements with a z-index that current_elem_index
            var upperZElements = [];

            
            jQuery('#panel-'+current_panel+' .cp-field-html-data').each(function() {
                var el_zIndex = parseInt(jQuery(this).css('z-index'));  
                if( !isNaN(el_zIndex) && el_zIndex > current_elem_index ) {
                    upperZElements.push(this);
                }
            });

            // get the element with the lowest z-index among the 
            // ones with upper z-index than current_elem_index
            var targetZ = 100000;
            var targetElem = null;
            
            for(var i=0; i<upperZElements.length; i++) {
                var el_z = parseInt(jQuery(upperZElements[i]).css('z-index'));  
                
                if(el_z < targetZ) {
                    targetZ = el_z;
                    targetElem = upperZElements[i];
                }
            }

            if ( targetElem == null ) {
                return;
            }
               
            // swap the z-indices with targetElem
            elem.css('z-index', targetZ);
            jQuery(targetElem).css('z-index', current_elem_index);

            /* Cureent element save z-index */
            var for_edit = elem.attr("id");
            var name = 'layerindex';
            var value = targetZ;

            var current_step = step_id;
            
            if( jQuery(elem).attr("data-overlay-respective") == 'true' ) {
                current_step = 'common';
            }

            bmodel.setModalValue( for_edit, current_step, name, value, false );

            /* Target element save z-index */
            for_edit = jQuery(targetElem).attr("id");
            value = current_elem_index;
            current_step = step_id;
            
            if( jQuery(targetElem).attr("data-overlay-respective") == 'true' ) {
                current_step = 'common';
            }

            bmodel.setModalValue( for_edit, current_step, name, value );
        },

        /**
         * bring the clicked element to the forward
         */
        bringForwardBack : function(elem, className) {

            var current_elem_index = parseInt( elem.css("z-index") );
            var max = 1, forwarded_index;
            var replace_element = '';

            var collision_group = this.getCollisionGroup( elem, className );
            var indexes = new Array();

            jQuery.each( collision_group, function( index, val ) {
                 
                if( index > current_elem_index )
                {
                    indexes.push(index);
                }

            });

            if( indexes.length > 0 ) {

                // sort array ascending
                indexes = indexes.sort(function(a, b){return a-b});

                forwarded_index = indexes[0];

                jQuery.each( collision_group, function( index, val ) {
                     
                    if( index == forwarded_index )
                    {
                        jQuery("#"+val).css( 'z-index', current_elem_index );
                        replace_element = val;
                    }

                });

                /* assigning the max z-index to the clicked elem. */
                jQuery(elem).css( 'z-index', forwarded_index );

                var for_edit = elem.attr("id");
                var name = 'layerindex';
                var value = forwarded_index;

                var current_step = step_id;
                if( jQuery(elem).attr("data-overlay-respective") == 'true' ) {
                    current_step = 'common';
                }

                bmodel.setElementID( current_step, for_edit );

                // save value in modal data
                bmodel.setModalValue( for_edit, current_step, name, value );
     
                var for_edit = replace_element;
                var name = 'layerindex';
                var value = current_elem_index;

                bmodel.setElementID( current_step, for_edit );

                // save value in modal data
                bmodel.setModalValue( for_edit, current_step, name, value );
            }
        },

        getCollisionGroup : function ( elem, className ) {

        	var collision_group = {};

        	jQuery( "."+className ).each(function(){
                var currentElement = jQuery(this).attr('id');

                // skip current element
                if( currentElement != elem.attr("id") ) {
                	var isColision = ConvertProHelper._collision( jQuery(this), elem );
                	
                	// if colliding with current element
                	if( isColision ) {

                		var index = parseInt( jQuery(this).css('z-index') );
                		if( index !== 0 ) {
                			collision_group[index] = currentElement; 
                		}
                	}
                }	            
            });

            return collision_group;
        },

        /**
         * set the clicked element to the back
         */
        sendBackward : function(elem, className) {
        	
        	var current_elem_index = parseInt( elem.css("z-index") );
            var current_panel = step_id + 1;
            // get all elements with a z-index that current_elem_index
            var lowerZElements = [];

            
            jQuery('#panel-'+current_panel+' .cp-field-html-data').each(function() {
                var el_zIndex = parseInt(jQuery(this).css('z-index'));  
                if( !isNaN(el_zIndex) && el_zIndex < current_elem_index ) {
                    lowerZElements.push(this);
                }
            });

            // get the element with the highest z-index among the 
            // ones with lower z-index than current_elem_index
            var targetZ = 0;
            var targetElem = null;
            
            for(var i=0; i<lowerZElements.length; i++) {
                var el_z = parseInt(jQuery(lowerZElements[i]).css('z-index'));  
                
                if(el_z > targetZ) {
                    targetZ = el_z;
                    targetElem = lowerZElements[i];
                }
            }

            if ( targetElem == null ) {
                return;
            }
               
            // swap the z-indices with targetElem
            elem.css('z-index', targetZ);
            jQuery(targetElem).css('z-index', current_elem_index);

            /* Cureent element save z-index */
            var for_edit = elem.attr("id");
            var name = 'layerindex';
            var value = targetZ;

            var current_step = step_id;
            
            if( jQuery(elem).attr("data-overlay-respective") == 'true' ) {
                current_step = 'common';
            }

            bmodel.setModalValue( for_edit, current_step, name, value, false );

            /* Target element save z-index */
            for_edit = jQuery(targetElem).attr("id");
            value = current_elem_index;
            current_step = step_id;
            
            if( jQuery(targetElem).attr("data-overlay-respective") == 'true' ) {
                current_step = 'common';
            }

            bmodel.setModalValue( for_edit, current_step, name, value );
        },

        /**
         * set the clicked element to the back
         */
        sendBackwardBack : function(elem, className) {
            
            var current_elem_index = parseInt( elem.css("z-index") );
            var min = 1, forwarded_index;
            var replace_element = '';

            var collision_group = this.getCollisionGroup( elem, className );
            var indexes = new Array();

            jQuery.each( collision_group, function( index, val ) {
                
                if( index < current_elem_index )
                {
                    indexes.push(index);
                }

            });

            if( indexes.length > 0 ) {

                // sort array ascending
                indexes = indexes.sort(function(a, b){return b-a});

                apply_index = indexes[0];

                jQuery.each( collision_group, function( index, val ) {
                     
                    if( index == apply_index )
                    {
                        jQuery("#"+val).css( 'z-index', current_elem_index );
                        replace_element = val;
                    }

                });

                /* assigning the max z-index to the clicked elem. */
                jQuery(elem).css( 'z-index', apply_index );

                var for_edit = elem.attr("id");
                var name = 'layerindex';
                var value = forwarded_index;

                var current_step = step_id;
                if( jQuery(elem).attr("data-overlay-respective") == 'true' ) {
                    current_step = 'common';
                }

                bmodel.setElementID( current_step, for_edit );

                // save value in modal data
                bmodel.setModalValue( for_edit, current_step, name, value );

                var for_edit = replace_element;
                var name = 'layerindex';
                var value = current_elem_index;

                bmodel.setElementID( current_step, for_edit );

                // save value in modal data
                bmodel.setModalValue( for_edit, current_step, name, value );

            }

        },

        /**
         * responsible for getting the max z-index
         */
        getMaxZIndex : function(className) {
            
            var max = 0, zIndex;

            /* traverse through all the classes and get the highest z-index */
            jQuery('.'+className).each(function(){
                zIndex = jQuery(this).css('z-index');
                if(parseInt(zIndex) > max)
                {
                    max = zIndex;
                }
            });

            return max;
        },
    };

    var container_el = '';

    var ConvertProLayers = {

        /**
         * Initializes the all class variables.
         *
         * @return void
         * @since 1.0.0
         */
        init: function( e ) {

            $( document ).on( 'click', '.bring-forward, .send-backward', this._forwardBackward );
            $( document ).on( 'click', '.distribute-horizontally, .distribute-vertically', this._horizontalVertical );
            $( document ).on( 'click', '.cp-layer-wrapper .show-on-mobile, .cp-layer-wrapper .hide-on-mobile', this._showHide );
        },

        _forwardBackward: function ( e ) {

            container_el = $( ".selected.cp-field-html-data, .cps-selected.cp-field-html-data" );
            var $this    = $(this);

            if( container_el.length > 0 ) {
                container_el.each(function(i) {
                    var el = jQuery(this);
                    if( $this.hasClass( "bring-forward" ) ) {
                        cpLayers.bringForward( el, "cp-field-html-data" );
                    }

                    if( $this.hasClass( "send-backward" ) ) {
                        cpLayers.sendBackward( el, "cp-field-html-data" );
                    }
                });
            }
        },

        _horizontalVertical: function ( e ) {

            var isHorizontal = false;

            if( $( this ).hasClass( "distribute-horizontally" ) ) {
                isHorizontal = true;
            }

            var id = step_id + 1,
                cpBigGhost = jQuery( '#panel-' + id ).find( '#cp-big-ghost' ),
                cpBigGhostSize = 0;

            if( isHorizontal ) {
                cpBigGhostSize = cpBigGhost.width();
            } else {
                cpBigGhostSize = cpBigGhost.height();
            }
                
            var remainingSpace = cpBigGhostSize,
                elements = cpBigGhost.find('.cp-field-html-data'),
                distCount = elements.length - 1;

            elements.sort(function(obj1, obj2) {
                // Ascending: first age less than the previous
                if( isHorizontal ) {
                    return jQuery(obj1).offset().left - jQuery(obj2).offset().left;
                } else {
                    return jQuery(obj1).offset().top - jQuery(obj2).offset().top;
                }
                
            });

            elements.each(function(e) {
                if( isHorizontal ) {
                    remainingSpace = remainingSpace - jQuery(this).outerWidth(true);
                } else {
                    remainingSpace = remainingSpace - jQuery(this).outerHeight(true);
                }
                
            });

            if ( distCount > 1 && remainingSpace > distCount ) {
                var evenSpace = parseInt( remainingSpace / distCount );
                var current_position = 0;

                elements.each(function(i) {
                    var $this = jQuery(this);
                    
                    if ( i == 0 ) {
                        
                        if( isHorizontal ) {
                            current_position = current_position + $this.outerWidth(true) + evenSpace;
                        } else {
                            current_position = current_position + $this.outerHeight(true) + evenSpace;
                        }
                        return;
                    }

                    if( isHorizontal ) {
                        $this.css({
                            'left': current_position + 'px'
                        });
                    } else {
                        $this.css({
                            'top': current_position + 'px'
                        });
                    }


                    if( isHorizontal ) {
                        current_position = current_position + $this.outerWidth(true) + evenSpace;
                    } else {
                        current_position = current_position + $this.outerHeight(true) + evenSpace;
                    }
                    
                });

                ConvertProHelper._setGroupElPosition();
            }
        },

        _showHide: function( e ) {
            container_el = $( ".selected.cp-field-html-data, .cps-selected.cp-field-html-data" );

            var canShow = false;
            var val     = 'yes';

            if( $( this ).hasClass( "show-on-mobile" ) ) {
                canShow = true;
                val     = 'no';
            }

            if( container_el.length > 0 ) {
                
                container_el.each( function( i ) {
                    
                    var el = $( this );
                    
                    if( ! canShow ) {
                        el.addClass( 'cp-invisible-on-mobile' );
                    } else {
                        el.removeClass( 'cp-invisible-on-mobile' );
                    }
                    
                    var for_edit = el.attr('id'); 
                    var name     = 'hide_on_mobile';
                    var current_step = step_id;

                    if( $("#" + for_edit ).attr("data-overlay-respective") == 'true' ) {
                        current_step = 'common'; 
                    }

                    bmodel.setModalValue( for_edit, current_step, name, val, false );
                });

                if( ! canShow ) {
                    $('.cp-layer-wrapper').addClass('cp-hidden');
                } else {
                    $('.cp-layer-wrapper').removeClass('invisible-mobile');
                }
            }
        }
    }

    ConvertProLayers.init();

})( jQuery );