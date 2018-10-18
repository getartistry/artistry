window.wp = window.wp || {};
window.eml = window.eml || { l10n: {} };



( function( $, _ ) {

    var media = wp.media,
        mediaTrash = media.view.settings.mediaTrash,
        l10n = media.view.l10n,
        original = {};



    _.extend( eml.l10n, wpuxss_eml_media_grid_l10n );



    _.extend( media.view.Attachment.Library.prototype, {

        buttons: {
            check  : true,
            edit   : true,
            remove : false, // TODO: consider 'delete' button
            attach : false // TODO: consider 'attach' button
        }
    });



    var newEvents = { 'click .edit': 'emlEditAttachment' };
    _.extend( newEvents, media.view.Attachment.prototype.events);

    _.extend( media.view.Attachment.prototype, {

        template:  media.template('attachment-grid-view'),

        events: newEvents,

        emlEditAttachment: function( event ) {

            if ( this.controller.isModeActive( 'eml-grid' ) ) {

                this.controller.trigger( 'edit:attachment', this.model);

                event.stopPropagation();
                return;
            }
        }
    });




    _.extend( media.view.Attachment.Details.prototype, {

        editAttachment: function( event ) {

            if ( this.controller.isModeActive( 'eml-grid' ) ) {

                event.preventDefault();
                this.controller.trigger( 'edit:attachment', this.model);
            }
        }
    });



    var newEvents = { 'click .eml-toggle-collapse': 'toggleCollapse' };
    _.extend( newEvents, media.view.Attachment.Details.prototype.events);

    media.view.emlGridAttachmentDetails = media.view.Attachment.Details.extend({

        template:  media.template('eml-grid-attachment-details'),

        events: newEvents,

        initialize: function() {

            media.view.Attachment.Details.prototype.initialize.apply( this, arguments );
            this.render();
            this.toggleCollapse();
        },

        toggleCollapse: function( event ) {

            var collapsed = this.controller._attachmentDetailsCollapsed;

            if ( typeof event !== 'undefined' && 'eml-toggle-collapse' === event.currentTarget.className ) {

                this.$el.find('.eml-collapse').toggle( 300 );
                collapsed = ! collapsed;
            }
            else if ( collapsed ) {
                this.$el.find('.eml-collapse').hide();
            }
            else {
                this.$el.find('.eml-collapse').show();
            }

            this.$el.find('.eml-toggle-collapse').html(function(i, html) {
                return ! collapsed ? eml.l10n.less_details+' \u2191' : eml.l10n.more_details+' \u2193';
            });

            this.controller._attachmentDetailsCollapsed = collapsed;
        }
    });



    /**
     * media.view.emlGridSelection
     *
     */
    media.view.emlGridSelection = media.view.Selection.extend({

        template:  media.template('eml-media-selection')
    });



    /**
     * wp.media.view.emlSelectAllButton
     *
     * Used in the bulk mode only right now
     */
    media.view.emlSelectAllButton = media.view.Button.extend({

        initialize: function() {
            media.view.Button.prototype.initialize.apply( this, arguments );
            this.controller.state().get( 'selection' ).on( 'add remove reset', this.toggleDisabled, this );
        },

        toggleDisabled: function() {
            this.$el.toggleClass( 'hidden', !! this.controller.state().get( 'selection' ).length );
            this.model.set( 'disabled', !! this.controller.state().get( 'selection' ).length );
        },

        render: function() {
            media.view.Button.prototype.render.apply( this, arguments );
            this.toggleDisabled();
            return this;
        },

        click: function() {
            if ( typeof this.controller._stopSelecting !== 'undefined' ) {
                this.controller.selectAll();
            }
        }
    });



    /**
     * wp.media.view.emlDeselectButton
     *
     */
    media.view.emlDeselectButton = media.view.Button.extend({

        initialize: function() {
            media.view.Button.prototype.initialize.apply( this, arguments );
            this.controller.state().get( 'selection' ).on( 'add remove reset', this.toggleDisabled, this );
        },

        toggleDisabled: function() {
            this.$el.toggleClass( 'hidden', ! this.controller.state().get( 'selection' ).length );
            this.model.set( 'disabled', ! this.controller.state().get( 'selection' ).length );
        },

        render: function() {
            media.view.Button.prototype.render.apply( this, arguments );
            this.toggleDisabled();
            return this;
        },

        click: function() {

            var selection = this.controller.state().get( 'selection' );

            selection.reset();

            // Keep focus inside media modal
            if ( this.controller.modal ) {
                this.controller.modal.focusManager.focus();
            }
        }
    });



    /**
     * wp.media.view.emlDeleteSelectedButton
     *
     */
    media.view.emlDeleteSelectedButton = media.view.DeleteSelectedButton.extend({

        initialize: function() {
            media.view.Button.prototype.initialize.apply( this, arguments );
            if ( this.options.filters ) {
                this.options.filters.model.on( 'change', this.filterChange, this );
            }
            this.controller.state().get( 'selection' ).on( 'add remove reset', this.toggleDisabled, this );
        },

        toggleDisabled: function() {
            this.$el.toggleClass( 'hidden', ! this.controller.state().get( 'selection' ).length );
            this.model.set( 'disabled', ! this.controller.state().get( 'selection' ).length );
        },

        render: function() {
            media.view.Button.prototype.render.apply( this, arguments );
            this.toggleDisabled();
            return this;
        },

        click: function() {

            var changed = [], removed = [], action = 'delete',
                selection = this.controller.state().get( 'selection' ),
                library = this.controller.state().get( 'library' );


            if ( typeof this.controller._stopSelecting !== 'undefined' ) {

                if ( mediaTrash ) {
                    action = 'trash' === selection.at( 0 ).get( 'status' ) ? 'restore' : 'trash';
                }

                this.controller.bulk( action );
            }
            else {

                if ( ! selection.length ) {
                    return;
                }

                if ( ! mediaTrash && ! window.confirm( l10n.warnBulkDelete ) ) {
                    return;
                }

                if ( mediaTrash &&
                    'trash' !== selection.at( 0 ).get( 'status' ) &&
                    ! window.confirm( l10n.warnBulkTrash ) ) {

                    return;
                }

                selection.each( function( model ) {
                    if ( ! model.get( 'nonces' )['delete'] ) {
                        removed.push( model );
                        return;
                    }

                    if ( mediaTrash && 'trash' === model.get( 'status' ) ) {
                        model.set( 'status', 'inherit' );
                        changed.push( model.save() );
                        removed.push( model );
                    } else if ( mediaTrash ) {
                        model.set( 'status', 'trash' );
                        changed.push( model.save() );
                        removed.push( model );
                    } else {
                        model.destroy({wait: true});
                    }
                } );

                if ( changed.length ) {
                    selection.remove( removed );

                    $.when.apply( null, changed ).then( _.bind( function() {
                        library._requery( true );
                        this.controller.trigger( 'selection:action:done' );
                    }, this ) );
                } else {
                    this.controller.trigger( 'selection:action:done' );
                }
            }
        }
    });



    /**
     * wp.media.view.emlDeleteSelectedPermanentlyButton
     *
     */
    media.view.emlDeleteSelectedPermanentlyButton = media.view.emlDeleteSelectedButton.extend({

        filterChange: function( model ) {
            this.canShow = ( 'trash' === model.get( 'status' ) );
        },

        toggleDisabled: function() {
            this.$el.toggleClass( 'hidden', ! this.canShow );
            this.model.set( 'disabled', ! this.canShow );
        },

        click: function() {

            if ( typeof this.controller._stopSelecting !== 'undefined' ) {
                this.controller.bulk( 'delete' );
            }
            else {

                var removed = [],
                    destroy = [],
                    selection = this.controller.state().get( 'selection' );

                if ( ! selection.length || ! window.confirm( l10n.warnBulkDelete ) ) {
                    return;
                }

                selection.each( function( model ) {
                    if ( ! model.get( 'nonces' )['delete'] ) {
                        removed.push( model );
                        return;
                    }

                    destroy.push( model );
                } );

                if ( removed.length ) {
                    selection.remove( removed );
                }

                if ( destroy.length ) {
                    $.when.apply( null, destroy.map( function (item) {
                        return item.destroy();
                    } ) ).then( _.bind( function() {
                        this.controller.trigger( 'selection:action:done' );
                    }, this ) );
                }
            }
        }
    });



    /**
     * wp.media.view.MediaFrame.emlGrid
     *
     */
    media.view.MediaFrame.emlGrid = media.view.MediaFrame.Select.extend({

        _attachmentDetailsCollapsed: true,

        initialize: function() {

            var self = this;

            _.defaults( this.options, {
                title    : '',
                modal    : false,

                selection: [],
                library:   {}, // Options hash for the query to the media library.
                // uploader:  true,

                multiple : 'reset',
                state    : 'library',
                mode     : [ 'eml-grid', 'edit' ]
            });

            $( document ).on( 'click', '.page-title-action', _.bind( this.addNewClickHandler, this ) );

            // Ensure core and media grid view UI is enabled.
            this.$el.addClass('wp-core-ui');

            this.gridRouter = new media.view.MediaFrame.Manage.Router();

            // Call 'initialize' directly on the parent class.
            media.view.MediaFrame.Select.prototype.initialize.apply( this, arguments );

            // Append the frame view directly the supplied container.
            this.$el.appendTo( this.options.container );

            this.createStates();
            this.render();

            media.frames.browse = this;
        },

        createStates: function() {

            var options = this.options;

            if ( this.options.states ) {
                return;
            }

            this.states.add([

                new media.controller.Library({
                    library            : media.query( options.library ),
                    title              : options.title,
                    multiple           : options.multiple,

                    content            : 'browse',
                    toolbar            : 'bulk-edit',
                    menu               : false,
                    router             : false,

                    contentUserSetting : true,

                    searchable         : true,
                    filterable         : 'all',

                    autoSelect         : true,
                    idealColumnWidth   : $( window ).width() < 640 ? 135 : 175
                })
            ]);
        },

        bindHandlers: function() {

            media.view.MediaFrame.Select.prototype.bindHandlers.apply( this, arguments );

            this.on( 'toolbar:create:bulk-edit', this.createToolbar, this );
            this.on( 'toolbar:render:bulk-edit', this.selectionStatusToolbar, this );
            this.on( 'edit:attachment', this.openEditAttachmentModal, this );
        },

        selectionStatusToolbar: function( view ) {

            view.set( 'selection', new media.view.emlGridSelection({
                controller: this,
                collection: this.state().get('selection'),
                priority:   -40,
            }).render() );
        },

        addNewClickHandler: function( event ) {

            event.preventDefault();
            this.trigger( 'toggle:upload:attachment' );
        },

        browseContent: function( contentRegion ) {

            var state = this.state();

            this.$el.removeClass('hide-toolbar');

            // Browse our library of attachments.
            this.browserView = contentRegion.view = new media.view.AttachmentsBrowser({
                controller: this,
                collection: state.get('library'),
                selection:  state.get('selection'),
                model:      state,
                sortable:   state.get('sortable'),
                search:     state.get('searchable'),
                filters:    state.get('filterable'),
                date:       state.get('date'), // ???
                display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
                dragInfo:   state.get('dragInfo'),

                idealColumnWidth: state.get('idealColumnWidth'),
                suggestedWidth:   state.get('suggestedWidth'),
                suggestedHeight:  state.get('suggestedHeight'),

                AttachmentView: state.get('AttachmentView')
            });

            this.browserView.on( 'ready', _.bind( this.bindDeferred, this ) );
        },

        bindDeferred: function() {

            if ( ! this.browserView.dfd ) {
                return;
            }
            this.browserView.dfd.done( _.bind( this.startHistory, this ) );
        },

        startHistory: function() {

            // Verify pushState support and activate
            if ( window.history && window.history.pushState ) {
                Backbone.history.start( {
                    root: _wpMediaGridSettings.adminUrl,
                    pushState: true
                } );
            }
        },

        openEditAttachmentModal: function( model ) {

            wp.media( {
                frame:       'edit-attachments',
                controller:  this,
                library:     this.state().get('library'),
                model:       model
            } );
        }
    });




    _.extend( media.view.UploaderInline.prototype, {

        show: function() {

            this.$el.removeClass( 'hidden' );
            if ( this.controller.browserView ) {
                this.controller.browserView.attachments.$el.css( 'top', this.$el.outerHeight() + 20 + 'px' );
            }
        },

        hide: function() {

            this.$el.addClass( 'hidden' );
            if ( this.controller.browserView ) {
                this.controller.browserView.attachments.$el.css( 'top', 0 );
            }
        }
    });




    original.controllerLibrary = {

        beforeUpload: media.controller.Library.prototype.beforeUpload
    };

    _.extend( media.controller.Library.prototype, {

        beforeUpload: function() {

            original.controllerLibrary.beforeUpload.apply( this, arguments );
            this.frame.browserView.uploader.hide();
        }
    });




    $( document ).ready( function() {

        media.frame = new media.view.MediaFrame.emlGrid({
            container: $('#wp-media-grid')
        });
    });




    // TODO: move to PHP side
    $('body').addClass('eml-grid');


})( jQuery, _ );
