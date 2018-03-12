
// Google Drive Embedder

var gdmDriveMgr = (function($) {
    var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

    var gdmDriveMgr = {

        savedWidth: '100%',
        savedHeight: '400',

        serviceType: '',

        selectedId: null,

        _serviceHandler : undefined,

        _serviceHandlerRegistry : {
            'allfiles' : gdmDriveServiceHandler,
            'teamdrives' : gdmDriveServiceHandler,
            'drive' : gdmDriveServiceHandler,
            'recent' : gdmDriveServiceHandler,
            'shared' : gdmDriveServiceHandler,
            'starred' : gdmDriveServiceHandler,
            'calendar' : gdmCalendarServiceHandler
        },

        _browserRegistry : GdmBrowserRegistry, // Set in either gdm-premium-drivefile.js or gdm-basic-drivefile.js

        setServiceHandler: function (type) {
            this.serviceType = type;
            this._serviceHandler = new (this._serviceHandlerRegistry[this.serviceType])();
            this.resetSearchBox();
        },

        getServiceHandler: function () {
            return this._serviceHandler;
        },

        makeApiCall: function () {

            if (!this.getServiceHandler().getAvailable()) {
                this.fileBrowser.displayMessage('<p>Purchase the Premium or Enterprise version of Google Drive Embedder: <br />- Locate your files via <i>My Drive</i>, <i>Starred</i>, <i>Shared with Me</i> etc<br />'
                    +'- Embed Calendars and iframe folders<br /> '
                    +'- Browse and search your Team Drives (Enterprise)<br /> '
                    +'- Interactive embedded folders including drag-and-drop to upload (Enterprise)<br /> '
                    + '<a href="http://wp-glogin.com/drive/?utm_source=Calendar%20Reason&utm_medium=freemium&utm_campaign=Drive" target="_blank">Find out more</a></p>'
                );
                return;
            }

            var current_search_query = this.current_search_query;

            this.fileBrowser.startQuery(current_search_query);
        },

        gdmNothingSelected: function () {
            this.hideMoreOptions();
            $('#gdm-insert-drivefile').attr('disabled', 'disabled');
            var baseLinkTypes = $('#gdm-linktypes-div');
            baseLinkTypes.find('input, label').attr('disabled', 'disabled');

            $('#gdm-linktype-normal-options').hide();
            $('#gdm-linktype-download-options').hide();
            $('#gdm-linktype-download-reasons').hide();
            $('#gdm-linktype-embed-options').hide();
            $('#gdm-linktype-embed-reasons').hide();

            $('#gdm-ack-owner-editor').hide();

            this.selectedId = null;
        },

        gdmSomethingSelected: function (id) {
            if (!id) {
                this.gdmNothingSelected();
                return;
            }

            var links = this.getServiceHandler().getFileLinks(id);

            var baseLinkTypes = $('#gdm-linktypes-div');
            baseLinkTypes.find('input, label').removeAttr('disabled');

            this.hideMoreOptions();
            if (links.extra == 'calendar') {
                $('#gdm-linktype-normal-more').show();
            }
            else {
                $('#gdm-linktype-normal-more').hide();
            }

            $('#gdm-linktype-normal-options').hide();

            $('#gdm-linktype-download-options').hide();
            $('#gdm-linktype-download-reasons').hide();

            $('#gdm-ack-owner-editor').hide();

            if (!links.download.url && !links.download.exports) {
                $('#gdm-linktype-download').attr('gdm-available', 'true');

                $('#gdm-linktype-download-reasons').html(' - ' + this.getServiceHandler().getReasonText(links.download.reason));
            }
            else {
                $('#gdm-linktype-download').attr('gdm-available', 'false');

                var fileTypesSelect = $('#gdm-linktype-download-type');
                // Is it a download or an export
                if (links.download.url || !links.download.exports) {
                    fileTypesSelect.hide();
                }
                else {
                    fileTypesSelect.empty();
                    for (prop in links.download.exports) {
                        fileTypesSelect.append($('<option>', {value: links.download.exports[prop]}).text(prop));
                    }
                    fileTypesSelect.show();
                }
            }

            $('#gdm-linktype-embed-options').hide();
            $('#gdm-linktype-embed-reasons').hide();
            $('.gdm-linktype-embed-folder').hide();

            if (!links.embed.url) {
                $('#gdm-linktype-embed').attr('gdm-available', 'true');

                $('#gdm-linktype-embed-reasons').html(' - ' + this.getServiceHandler().getReasonText(links.embed.reason));
            }
            else {
                $('#gdm-linktype-embed').attr('gdm-available', 'false');

                if (links.extra == 'calendar' || (links.extra == 'folder' && gdm_trans.allow_non_iframe_folders)) {
                    $('#gdm-linktype-embed-more').attr('data-gdm-embed-more-type', links.extra).show();
                }
                else {
                    $('#gdm-linktype-embed-more').hide();
                }

                if (typeof links.width != 'undefined' && typeof links.height != 'undefined') {
                    if (this.saveMyDims) {
                        this.savedWidth = $('#gdm-linktype-embed-width').attr('value');
                        this.savedHeight = $('#gdm-linktype-embed-height').attr('value');
                    }
                    $('#gdm-linktype-embed-width').attr('value', links.width);
                    $('#gdm-linktype-embed-height').attr('value', links.height);
                    this.saveMyDims = false;
                }
                else {
                    if (this.savedWidth) {
                        $('#gdm-linktype-embed-width').attr('value', this.savedWidth);
                    }
                    if (this.savedHeight) {
                        $('#gdm-linktype-embed-height').attr('value', this.savedHeight);
                    }
                    this.saveMyDims = true;
                }
                // set width and height

                if (links.extra == 'folder') {
                    $('.gdm-linktype-embed-folder').show();
                }

            }

            $('.gdm-linktypes-span input:checked').change();

            // Enterprise only
            if (this.getServiceHandler().showOwnerEditorWarning()) {
                $('#gdm-ack-owner-editor').show();
            }

            this.selectedId = id;

        },

        gdmInsertDriveFile: function (event) {
            // Send the shortcode to the editor

            if (this.selectedId) {

                if (!this.getServiceHandler().allowInsertDriveFile()) {
                    return;
                }

                var id = this.selectedId;

                var links = this.getServiceHandler().getFileLinks(id);

                var icon = links.icon.url;
                var extraattrs = '';
                var url ='';

                var linkStyle = '';
                if ($('#gdm-linktype-normal').prop("checked") == true) {
                    linkStyle = 'normal';
                    if ($('#gdm-linktype-normal-window').prop("checked")) {
                        extraattrs = ' newwindow="yes"';
                    }
                    if (!$('#gdm-linktype-normal-plain').prop("checked")) {
                        extraattrs += ' plain="yes"';
                    }
                    url = links.viewer.url;
                }
                else if ($('#gdm-linktype-download').prop("checked") == true) {
                    linkStyle = 'download';
                    url = links.download.url;

                    if (!url && links.download && links.download.exports) {
                        url = $('#gdm-linktype-download-type').val();
                    }

                    if (!$('#gdm-linktype-download-plain').prop("checked")) {
                        extraattrs += ' plain="yes"';
                    }
                }
                else if ($('#gdm-linktype-embed').prop("checked") == true) {

                    if (links.extra == 'folder' && $('#gdm-foldertype-iframe').prop("checked") == false) {
                        // Completely different shortcode type
                        if (gdmInsertFolderShortcode) {
                            gdmInsertFolderShortcode(links);
                            return;
                        }
                    }

                    linkStyle = 'embed';
                    url = links.embed.url;
                    var width = this.gdmValidateDimension($('#gdm-linktype-embed-width').attr('value'), '100%');
                    var height = this.gdmValidateDimension($('#gdm-linktype-embed-height').attr('value'), '400');
                    extraattrs = ' width="' + width + '" height="' + height + '"';
                    if (links.extra) {
                        extraattrs += ' extra="' + links.extra + '"';
                    }
                }

                // Calendar more options
                if ((linkStyle == 'normal' || linkStyle == 'embed') && links.extra && links.extra == 'calendar') {
                    var extraparams = {};
                    $('.gdm-more-boolean').each(function (index, elt) {
                        var jelt = $(elt);
                        if (!jelt.prop("checked")) {
                            var optname = jelt.attr('name');
                            extraparams[optname] = "0";
                        }
                    });
                    extraparams['wkst'] = $('#gdm-more-wkst').val();
                    extraparams['mode'] = $('input:radio[name=gdm-more-mode]:checked').val();
                    var caltitle = $('#gdm-more-title').val();
                    if (caltitle != '') {
                        extraparams['title'] = encodeURIComponent(caltitle);
                    }
                    for (param in extraparams) {
                        url += "&" + param + "=" + extraparams[param];
                    }
                }

                // Send to editor
                window.send_to_editor('[google-drive-embed url="' + url + '" title="'
                    + this.escapeHTML(links.title) + '"'
                    + ' icon="' + icon + '"'
                    + extraattrs
                    + ' style="' + linkStyle + '"]');

                // Set file parent/owner in Enterprise version
                if (this.getServiceHandler().allowSetEmbedOwnerParent()) {
                    gdmSetEmbedSAOwnerParent(id);
                }

            }
        },

        gdmValidateDimension: function (dimStr, defaultStr) {
            if (dimStr.match(/^ *[0-9]+ *(\%|px)? *$/i)) {
                return dimStr.replace(/ /g, '');
            }
            return defaultStr;
        },

        gdmNormalCheckChange: function () {
            $('#gdm-insert-drivefile').removeAttr('disabled');
            $('#gdm-linktype-normal-options').show();
            $('#gdm-linktype-download-options').hide();
            $('#gdm-linktype-download-reasons').hide();
            $('#gdm-linktype-embed-options').hide();
            $('#gdm-linktype-embed-reasons').hide();
        },

        gdmDownloadCheckChange: function () {
            // Assume it is now checked
            if ($('#gdm-linktype-download').attr('gdm-available') == 'true') {
                $('#gdm-linktype-download-reasons').show();
                $('#gdm-insert-drivefile').attr('disabled', 'disabled');
            } else {
                $('#gdm-linktype-download-options').show();
                $('#gdm-insert-drivefile').removeAttr('disabled');
            }
            $('#gdm-linktype-normal-options').hide();
            $('#gdm-linktype-embed-options').hide();
            $('#gdm-linktype-embed-reasons').hide();
            this.hideMoreOptions();
        },

        gdmEmbedCheckChange: function () {
            if ($('#gdm-linktype-embed').attr('gdm-available') == 'true') {
                $('#gdm-linktype-embed-reasons').show();
                $('#gdm-insert-drivefile').attr('disabled', 'disabled');
            } else {
                $('#gdm-linktype-embed-options').show();
                $('#gdm-insert-drivefile').removeAttr('disabled');
            }
            $('#gdm-linktype-normal-options').hide();
            $('#gdm-linktype-download-options').hide();
            $('#gdm-linktype-download-reasons').hide();
        },

        gdmSearchKeyPress: function (e) {
            if (e.keyCode == 13) {
                var search_query = $('#gdm-search-box').val();
                this.setSearchQuery(search_query);
                if (search_query) {
                    $('#gdm-search-clear').show();
                }
                else {
                    $('#gdm-search-clear').hide();
                }
                this.makeApiCall();
                e.preventDefault();
            }
        },

        current_search_query: "",

        setSearchQuery: function (str) {
            this.current_search_query = str.replace(/\\/g, "\\\\").replace(/'/g, "\\'");
        },

        resetSearchBox: function() {
            this.hideMoreOptions();
            $('#gdm-search-box').val("");
            $('#gdm-search-clear').hide();
            $('#gdm-search-area').css('visibility', this.getServiceHandler().getAllowSearch() ? 'visible' : 'hidden');
            this.setSearchQuery("");
        },

        gdmClearSearch: function () {
            this.resetSearchBox();
            this.makeApiCall();
        },

        gdmStartThinking: function () {
            this.gdmNothingSelected();
            this.fileBrowser.startThinking();
        },

        entityMap: {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;',
            "[": '&#91;',
            "]": '&#93;'
        },

        escapeHTML: function (str) {
            var self = this;
            return String(str).replace(/[&<>\]\["'\/]/g, function (s) {
                return self.entityMap[s];
            });
        },

        showMoreOptions: function () {
            if (!$('.gdm-more-options').is(':visible')) {
                $('.gdm-more-options').show();

                // folder or calendar
                var extraType = $('#gdm-linktype-embed-more').attr('data-gdm-embed-more-type');

                if (extraType == 'folder') {
                    $('#gdm-more-options-folders').show();
                    $('#gdm-more-options-calendar').hide();
                }
                else {
                    $('#gdm-more-options-folders').hide();
                    $('#gdm-more-options-calendar').show();
                }

                gdmThickDims();
            }
        },

        hideMoreOptions: function () {
            if ($('.gdm-more-options').is(':visible')) {
                $('.gdm-more-options').hide();
                gdmThickDims();
            }
        },

        // Auth stuff

        handleFirstAuth: function (authResult) {
            if (authResult && !authResult.error) {
                $('#gdm-search-box').removeAttr('disabled');
                this.makeApiCall();
            } else {
                this.fileBrowser.showAuthBox();
            }
        },

        handleAuthClick2: function (event) {
            this.fileBrowser.startThinking();

            this.doAuth(false, this.handleSecondAuth);

            if (event) {
                event.preventDefault();
            }
            return false;
        },

        handleSecondAuth: function (authResult) {
            if (authResult && !authResult.error) {
                $('#gdm-search-box').removeAttr('disabled');
                this.makeApiCall();
            } else {
                alert("Failed to authenticate with Google");
                this.fileBrowser.showAuthBox();
            }
        },

        doAuth: function (immediate, handler) {
            var clientid = gdm_trans.clientid;

            if (clientid == '') {
                this.fileBrowser.displayMessage(
                    '<p>Please install and configure '
                    + '<a href="http://wp-glogin.com/?utm_source=Admin%20JSmsg&utm_medium=freemium&utm_campaign=Drive" '
                    + ' target="_blank">Google Apps Login</a>'
                    + ' plugin first</p><p>Version 2.0 or higher required (Free or Premium)</p>'
                );
            }
            else {
                var params = {
                    client_id: clientid, scope: gdm_trans.scopes, immediate: immediate,
                    include_granted_scopes: true,
                    authuser: -1
                };

                if (!gdm_trans.gdm_allow_account_switch && gdm_trans.useremail != '') {
                    params.login_hint = gdm_trans.useremail;
                }
                gapi.auth.authorize(params, handler);
            }
        },

        gdmJsClientLoaded: false,

        gdmDocReady: false,

        _doneBinding : false,

        init : function(serviceType) {

            if (!this.gdmDocReady || !this.gdmJsClientLoaded) {
                return;
            }

            if (!this._doneBinding) {
                this.handleAuthClick2 = __bind(this.handleAuthClick2, this);
                this.gdmSomethingSelected = __bind(this.gdmSomethingSelected, this);
                this.gdmClearSearch = __bind(this.gdmClearSearch, this);

                this.handleFirstAuth = __bind(this.handleFirstAuth, this);
                this.handleSecondAuth = __bind(this.handleSecondAuth, this);
                this.gdmNormalCheckChange = __bind(this.gdmNormalCheckChange, this);
                this.gdmDownloadCheckChange = __bind(this.gdmDownloadCheckChange, this);
                this.gdmEmbedCheckChange = __bind(this.gdmEmbedCheckChange, this);
                this.gdmInsertDriveFile = __bind(this.gdmInsertDriveFile, this);
                this.gdmSearchKeyPress = __bind(this.gdmSearchKeyPress, this);
                this.showMoreOptions = __bind(this.showMoreOptions, this);

                // Click events for selected-file options
                $('#gdm-insert-drivefile').on( 'click', this.gdmInsertDriveFile );

                $('#gdm-linktype-normal').on( 'change', this.gdmNormalCheckChange );
                $('#gdm-linktype-download').on( 'change', this.gdmDownloadCheckChange );
                $('#gdm-linktype-embed').on( 'change', this.gdmEmbedCheckChange );
                $('#gdm-search-box').on( 'keypress', this.gdmSearchKeyPress );
                $('.gdm-linktype-more').on( 'click', this.showMoreOptions );
                $('#gdm-search-clear').on( 'click', this.gdmClearSearch );

                $('#gdm-linktype-download-options').hide();
                $('#gdm-linktype-embed-options').hide();
                $('#gdm-linktypes-div').find('input, label').attr('disabled', 'disabled');

                this._doneBinding = true;
            }

            this.setServiceHandler(serviceType);

            this.fileBrowser = new (this._browserRegistry[this.serviceType])('#gdm-file-browser-area',
                this.getServiceHandler(),
                {
                    onAuthClicked: this.handleAuthClick2,
                    onFileSelected: this.gdmSomethingSelected,
                    onClearSearchClicked: this.gdmClearSearch
                });

            this.fileBrowser.init();

            // Initiate Google API auth
            var self = this;
            window.setTimeout(
                function() {
                    self.doAuth(true, self.handleFirstAuth);
                }
                ,1);
        }
    };

    return gdmDriveMgr;
})(jQuery);


// Invoked by Google client js file
gdmHandleGoogleJsClientLoad = function() {
    gdmDriveMgr.gdmJsClientLoaded = true;
    var tabname = gdmGetActiveTabName();
    if (tabname) {
        gdmDriveMgr.init(tabname); // Will only go ahead if document is also loaded
    }
};

gdmThickDims = function() {
	var tbWidth = 640, tbHeight = 534+50;
	var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;

	var moreBox = jQuery('.gdm-more-options:visible');
	if (moreBox.length > 0) {
		tbHeight += moreBox.height();
	}
	
	w = (tbWidth && tbWidth < W - 90) ? tbWidth : W - 90;
	h = (tbHeight && tbHeight < H - 60) ? tbHeight : H - 60;

	if ( tbWindow.size() ) {
		tbWindow.width(w).height(h);
		jQuery('#TB_ajaxContent').width(w).height(h - 31).css('padding', '0');
		tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
		/* if ( typeof document.body.style.maxWidth !== 'undefined' ) {
			tbWindow.css({'top':'30px','margin-top':'0'});
		}*/
	}
};

gdmGetActiveTabName = function() {
    var tabid = jQuery('#gdm-tabs a.nav-tab-active').attr('id');

    if (tabid) {
        return tabid.replace('-tab','');
    }

    return undefined;
};

jQuery(document).ready(function () {

    gdmDriveMgr.gdmDocReady = true; // Tel gdmDriveMgr that document is loaded

	jQuery(window).resize( function() { gdmThickDims(); } );
	
	jQuery('#gdm-thickbox-trigger').click( function() { 
			window.setTimeout( function() { 
				gdmThickDims(); 
			}, 1); 
		} );


	// Enable tabs
	jQuery('#gdm-tabs').find('a').click(function() {
		jQuery('#gdm-tabs').find('a').removeClass('nav-tab-active');
		jQuery('.gdmtab').removeClass('active');
		var serviceType = jQuery(this).attr('id').replace('-tab','');
		//jQuery('#' + id + '-section').addClass('active');
		jQuery(this).addClass('nav-tab-active');
        gdmDriveMgr.init(serviceType);
    });

    // Will only go ahead if client lib is also loaded
    gdmDriveMgr.init(gdmGetActiveTabName());

});

