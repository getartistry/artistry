/**
 * Workflow field validator
 */

(function( $, localizedErrorMessages ) {

    var self;

    AW.Validate = {

        errorMessages: {},


        init: function() {

            setInterval(function() {

                if ( typeof tinyMCE !== 'undefined' ) {
                    tinyMCE.triggerSave();
                }

                $('.automatewoo-field-wrap textarea.wp-editor-area').each(function() {
                    $(this).attr( 'data-automatewoo-validate', 'variables' );
                    self.validateField( $(this) );
                });

            }, 2000 );


            $( document.body ).on( 'keyup blur change', '[data-automatewoo-validate]', function( event ){
                self.validateField( $(event.target) )
            });

            self.validateAllFields();

        },



        validateAllFields: function() {
            $( '[data-automatewoo-validate]' ).each( function() {
                self.validateField( $(this) );
            });
        },



        validateField: function( $field ) {

            if ( ! AW.workflow )
                return;

            var errors = [];
            var text = $field.val();

            self.clearFieldErrors( $field );

            var usedVariables = AW.Validate.getVariablesFromText( text );

            if ( self.fieldSupports( 'variables', $field ) ) {

                var trigger = AW.workflow.get( 'trigger' );

                _.each( usedVariables, function( variable ) {

                    var valid = self.isVariableValidForTrigger( variable, trigger );

                    if ( valid !== true ) {
                        errors.push( self.getErrorMessage( valid, self.getVariableWithoutParams( variable ) ) );
                    }

                });

            }
            else {
                if ( usedVariables ) {
                    errors.push( self.getErrorMessage( 'noVariablesSupport' ) );
                }
            }


            if ( errors.length ) {
                self.setFieldErrors( $field, errors );
            }

        },



        setFieldErrors: function( $field, errors ) {

            $field.addClass( 'automatewoo-field--invalid' );
            var $wrap = $field.parents( '.automatewoo-field-wrap:first' );
            $wrap.append('<div class="automatewoo-field-errors"></div>');
            var $errors = $wrap.find( '.automatewoo-field-errors' );

            if ( $field.is( '.wp-editor-area' ) ) {
                $wrap.find( '.wp-editor-container' ).addClass( 'automatewoo-field--invalid' )
            }

            _.each( errors, function( error ) {
                $errors.append( '<div class="automatewoo-field-errors__error">'+ error + '</div>' );
            });
        },


        clearFieldErrors: function( $field ) {
            var $wrap = $field.parents( '.automatewoo-field-wrap:first' );
            $field.removeClass( 'automatewoo-field--invalid' );

            if ( $field.is( '.wp-editor-area' ) ) {
                $wrap.find( '.wp-editor-container' ).removeClass( 'automatewoo-field--invalid' )
            }

            $wrap.find( '.automatewoo-field-errors' ).remove();
        },


        fieldSupports: function( option, $field ) {
            var options = $field.data( 'automatewoo-validate' ).split( ' ' );
            return _.indexOf( options, option ) !== -1
        },


        /**
         * @param variable
         * @param trigger
         * @return boolean|string
         */
        isVariableValidForTrigger: function( variable, trigger ) {

            var dataType = self.getDataTypeFromVariable( variable );
            var dataField = self.getDataFieldFromVariable( variable );

            if ( dataType && _.indexOf( trigger.supplied_data_items, dataType ) === -1 ) {
               return 'invalidDataType';
            }

            var variables = AW.workflow.get('variables');

            if ( variables && variables[dataType] ) {
                if ( variables[dataType].indexOf( dataField ) === -1 ) {
                    return 'invalidVariable';
                }
            }

            return true;
        },


        /**
         * Extract variables from a text field
         * @param text
         * @returns array|false
         */
        getVariablesFromText: function( text ) {

            var variables = text.match(/{{(.*?)}}/g);

            if ( ! variables ) {
                return false;
            }

            _.each( variables, function( variable, i ) {
                variables[i] = variable.replace( /{|}/g, '' ).trim();
            });

            return variables;
        },


        getVariableWithoutParams: function( variable ) {
            return variable.replace( /(\|.*)/, '' );
        },


        getDataTypeFromVariable: function( variable ) {
            if ( variable.indexOf('.') === -1 ) return false;
            return variable.replace( /(\..*)/, '' );
        },


        getDataFieldFromVariable: function( variable ) {
            variable = self.getVariableWithoutParams( variable );
            var dotpos = variable.indexOf('.');
            if ( dotpos === -1 ) return false;
            return variable.substring( dotpos + 1 ).trim();
        },



        getErrorMessage: function( error, replace ) {

            if ( ! self.errorMessages[error] ) {
                return 'Unknown error, please try refreshing your browser.';
            }

            var message = self.errorMessages[error];

            if ( typeof replace == 'string' ) {
                message = message.replace( '%s', replace );
            }

            return message;
        }

    };


    self = AW.Validate;
    self.errorMessages = localizedErrorMessages;

})( jQuery, automatewooValidateLocalizedErrorMessages );