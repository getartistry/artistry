// DRIVE SERVICE HANDLER

var gdmDriveServiceHandler = function() {
    gdmBaseServiceHandler.call(this);
    this.APIName = 'Drive';
};

gdmDriveServiceHandler.prototype = new gdmBaseServiceHandler();


gdmDriveServiceHandler.prototype.getAvailable = function() {
    return true;
};

gdmDriveServiceHandler.prototype.getRequest = function(params) {
    params.trashed = false;
    return gapi.client.request({
        'path': '/drive/v3/files',
        'corpus': 'DEFAULT',
        'fields': 'kind, nextPageToken, files(id, name, kind, viewedByMeTime, modifiedTime, owners, mimeType, webContentLink, webViewLink, imageMediaMetadata, iconLink, teamDriveId, size)',
        'params': params,
        includeTeamDriveItems: true
    });
};

gdmDriveServiceHandler.prototype.isCorrectType = function(resp) {
    return resp.kind == 'drive#fileList';
};

gdmDriveServiceHandler.prototype.getAllowSearch = function() {
    return true;
};

gdmDriveServiceHandler.prototype._getFolderUrlsAndReasons = function(links, drivefile) {
    links.embed.reason = 'PREMIUM';
    links.embed.url = '';
    links.download.reason = 'FOLDERDOWNLOAD';
    return links;
};

gdmDriveServiceHandler.prototype.getReasonText = function(reason) {
    switch (reason) {
        case 'NODOWNLOAD':
            return 'It is not possible to download native Google documents';
            break;

        case 'PREMIUM':
            return 'Embedded folders in Premium/Enterprise versions only '
                +'(<a href="http://wp-glogin.com/drive/?utm_source=Embed%20Reason&utm_medium=freemium&utm_campaign=Drive" '
                +'target="_blank">Find out more</a>)';
            break;

        case 'FOLDERDOWNLOAD':
            return 'Not possible to download this type';
            break;

        case 'WEBCONTENT':
            return 'There is no content available';
            break;

        default:
            return 'Not possible for this file type';
    }
};

gdmDriveServiceHandler.prototype.allowSetEmbedOwnerParent = function() {
    return false;
};

gdmDriveServiceHandler.prototype.showOwnerEditorWarning = function() {
    return false;
};

gdmDriveServiceHandler.prototype.allowInsertDriveFile = function() {
    return true;
};


// CALENDAR SERVICE HANDLER


var gdmCalendarServiceHandler = function() {
    gdmBaseServiceHandler.call(this);
    this.APIName = 'Calendar';
};

gdmCalendarServiceHandler.prototype = new gdmBaseServiceHandler();

gdmCalendarServiceHandler.prototype.getAvailable = function() {
    return false;
};

gdmCalendarServiceHandler.prototype.allowSetEmbedOwnerParent = function() {
    return false;
};

gdmCalendarServiceHandler.prototype.showOwnerEditorWarning = function() {
    return false;
};

gdmCalendarServiceHandler.prototype.allowInsertDriveFile = function() {
    return true;
}

gdmCalendarServiceHandler.prototype.isCorrectType = function(resp) {
    return resp.kind == 'calendar#calendarList';
};

gdmCalendarServiceHandler.prototype.getUrlsAndReasons = function(calendar) {
    return {};
};

gdmCalendarServiceHandler.prototype.getAllowSearch = function() {
    return false;
};

GdmBrowserRegistry = {
	'allfiles' : GdmSimpleFileBrowser,
	'drive' : GdmSimpleFileBrowser,
	'recent' : GdmSimpleFileBrowser,
	'calendar' : GdmSimpleFileBrowser
};


