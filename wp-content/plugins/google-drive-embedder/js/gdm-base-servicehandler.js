
var gdmBaseServiceHandler = function() {
    this.gdmPrevTokenStore = {};
    this._linksStore = {};
    this.APIName = 'Base'; // E.g. Drive / Calendar
};

// Is this service actually available to the user?
gdmBaseServiceHandler.prototype.getAvailable = function() {
    return false;
};

// Enterprise Drive has this requirement to be true
gdmBaseServiceHandler.prototype.allowSetEmbedOwnerParent = function() {
    return false;
};

// Enterprise Drive has this requirement to be true
gdmBaseServiceHandler.prototype.showOwnerEditorWarning = function() {
    return false;
};

// Enterprise Drive has this requirement to be true
gdmBaseServiceHandler.prototype.allowInsertDriveFile = function() {
    return false;
};

// Does this service allow search?
gdmBaseServiceHandler.prototype.getAllowSearch = function() {
    return false;
};

// Validate that the resonse from Google is of the correct type
gdmBaseServiceHandler.prototype.isCorrectType = function(resp) {
    return false;
};

// return a links object based on a drivefile/calendar entry
// links objects are useful to the main dialog to know whether to display different options
gdmBaseServiceHandler.prototype.getUrlsAndReasons = function(drivefile) {
    return {};
};

gdmBaseServiceHandler.prototype.storeFileLinks = function (id, links) {
    this._linksStore[id] = links;
};

gdmBaseServiceHandler.prototype.getFileLinks = function (id) {
    return this._linksStore[id];
};

gdmBaseServiceHandler.prototype.getErrorHTML = function(error) {
    if (error.errors && error.errors.length > 0) {
        var errorhtml = '';
        var serviceName = this.APIName;
        if (error.errors[0].reason && (error.errors[0].reason == 'accessNotConfigured' || error.errors[0].reason == 'insufficientPermissions')) {
            errorhtml = '<p>Please enable <b>' + serviceName + ' API</b> on the APIs page in '
                + '<a href="http://cloud.google.com/console" target="_blank">Google Cloud Console</a>'
                + '<br></br> (or reload this page and try again if ' + serviceName + ' API is already enabled)<br></br>'
                + '</p>'
                + '<p>Error message from Google: <i>' + gdmDriveMgr.escapeHTML(error.errors[0].message) + '</i></p>';
        }
        else if (error.errors[0].reason && (error.errors[0].reason == 'authError' || error.errors[0].reason == 'required')) {
            // Do auth again
            errorhtml = '<p>There was a problem accessing <b>' + serviceName + ' API</b>'
                + '<br></br>Please <a href="#" onclick="gdmDriveMgr.handleAuthClick2(); return false">click here</a> to authenticate again<br></br>'
                + '</p>'
                + '<p>Error message from Google: <i>' + gdmDriveMgr.escapeHTML(error.errors[0].message) + '</i></p>';
        }
        else {
            errorhtml = '<p>There was a problem accessing <b>' + serviceName + ' API</b> '
                + '<br></br>Reload this page and try again - please <a href="mailto:contact@wp-glogin.com">email us</a> if it persists<br></br>'
                + '</p>'
                + '<p>Error message from Google: <i>' + gdmDriveMgr.escapeHTML(error.errors[0].message) + '</i></p>';

        }
        return errorhtml;
    }
    return 'No error provided';
};

gdmBaseServiceHandler.prototype.getUrlsAndReasons = function(drivefile) {
    var links = {
        id : drivefile.id,
        embed : { url : '', reason : '' },
        viewer : { url : drivefile.webViewLink ? drivefile.webViewLink : '', reason : '' },
        download : { url : drivefile.webContentLink ? drivefile.webContentLink : '' , reason : '' },
        title : drivefile.name,
        icon: { url : drivefile.iconLink }
    };

    if (drivefile.embedLink) {
        links.embed.url = drivefile.embedLink;
    }
    else {
        if (drivefile.webViewLink) {
            links.embed.url = drivefile.webViewLink.replace(/\/(edit|view)(\?|$)/g, '/preview?');
        }
        else if (drivefile.webContentLink) {
            // Old-style Google Doc Viewer as fallback
            links.embed.url = '//docs.google.com/viewer?embedded=true&url=' + encodeURIComponent(drivefile.webContentLink);
        }
        else {
            links.embed.reason = 'WEBCONTENT';
        }
    }

    if (!links.download.url) {
        links.download.reason = 'NODOWNLOAD';
    }

    if (drivefile.mimeType == 'application/vnd.google-apps.folder' || drivefile.kind == 'drive#teamDrive') {

        links = this._getFolderUrlsAndReasons(links, drivefile);

    } else if (drivefile.mimeType == 'application/vnd.google-apps.form') {
        /*
         * Map e.g. https://docs.google.com/a/danlester.com/forms/d/<driveid>/edit?usp=drivesdk
         * to       https://docs.google.com/a/danlester.com/forms/d/<driveid>/viewform?embedded=true
         */
        links.embed.url = drivefile.webViewLink.replace(/\/(edit|view)(\?|$)/g, '/viewform?embedded=true&');
        links.embed.reason = '';
    } else if (drivefile.mimeType && drivefile.mimeType.match(/^image\//) && drivefile.webContentLink) {
        links.embed.url = drivefile.webContentLink;
        links.extra = 'image';
        if (drivefile.imageMediaMetadata) {
            if (drivefile.imageMediaMetadata.width) {
                links.width = drivefile.imageMediaMetadata.width;
            }
            if (drivefile.imageMediaMetadata.height) {
                links.height = drivefile.imageMediaMetadata.height;
            }
        }
    }

    // Video needs special attention
    if (drivefile.mimeType && drivefile.mimeType.match(/^video\//) && drivefile.webViewLink) {
        links.embed.url = drivefile.webViewLink.replace(/\/(edit|view)(\?|$)/g, '/preview?');
        links.embed.reason = '';
    }

    return links;
};

// request based on a token for a page, or undefined for default.
// Returns object containing {error: errors object} or
// [] containing drive files / calendar data
gdmBaseServiceHandler.prototype.makeAPICall = function (current_search_query, thisPageToken, callback) {
    var params = {
        pageSize: 8,
        fields: 'kind, nextPageToken, files(id, name, kind, viewedByMeTime, modifiedTime, owners, mimeType, webContentLink, webViewLink, imageMediaMetadata, iconLink, teamDriveId, size)'
    };

    if (thisPageToken) {
        params.pageToken = thisPageToken;
    }
    if (current_search_query != "") {
        params.q = "name contains '" + current_search_query + "' and trashed = false";
    }
    else {
        params.q = "trashed = false";
    }
    var restRequest = this.getRequest(params);

    var self = this;
    restRequest.execute(function (resp) {
        if (resp.error || (!resp.files && !resp.items)) {
            callback({error: resp.error});
        }
        else {
            if (!self.isCorrectType(resp)) {
                return;
            }

            // next and prev buttons
            var newNextPageToken = '';
            if (resp.nextPageToken) {
                newNextPageToken = resp.nextPageToken;
                self.gdmPrevTokenStore[newNextPageToken] = thisPageToken;
            }

            var newPrevPageToken = undefined;
            if (thisPageToken && self.gdmPrevTokenStore.hasOwnProperty(thisPageToken)) {
                newPrevPageToken = self.gdmPrevTokenStore[thisPageToken];
            }

            var items = resp.files ? resp.files : resp.items;

            var linkslist = [];
            if (items.length > 0) {
                for (var i = 0; i < items.length; ++i) {
                    var drivefile = items[i];
                    var links = self.getUrlsAndReasons(drivefile);
                    self.storeFileLinks(links.id, links);
                    linkslist.push(links);
                }
            }

            callback(linkslist, current_search_query, thisPageToken, newNextPageToken, newPrevPageToken);

        }
    });

};
