/**
 * Created by dan on 04/06/2015.
 *
 * Function to display the file list - including Next/Prev but not Search
 *
 *
 */

/* To be called with new, passing
 jQuery selector,
 options = {

 }
  */

GdmSimpleFileBrowser = (function($) {
    var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

    function GdmSimpleFileBrowser(selector, serviceHandler, options) {
        this.selector = selector;
        this.serviceHandler = serviceHandler;
        this.options = options;
    }

    GdmSimpleFileBrowser.prototype.init = function() {
        if (!this.boundEvents) {
            this.bindEvents();
        }
        this.boundEvents = true;

        this.drawHTML();
    };

    GdmSimpleFileBrowser.prototype.bindEvents = function() {
        this.fileClicked = __bind(this.fileClicked, this);
        this.authClicked = __bind(this.authClicked, this);
    };

    GdmSimpleFileBrowser.prototype.drawHTML = function () {
        $(this.selector).empty().append(
            $('#gdm-simple-browser-template-html').html()
        ).show()
            .find('.gdm-start-browse2').click( this.authClicked );
    };

    GdmSimpleFileBrowser.prototype.authClicked = function (event) {
        if (this.options.onAuthClicked) {
            this.options.onAuthClicked(event);
        }
    };

    GdmSimpleFileBrowser.prototype.showAuthBox = function () {
        $(this.selector + ' .gdm-authbtn').show();
        $(this.selector + ' .gdm-filelist').hide();
        $(this.selector + ' .gdm-thinking').hide();
    };

    GdmSimpleFileBrowser.prototype.displayMessage = function (html) {
        $(this.selector + ' .gdm-nextprev-div a').attr('disabled', 'disabled').hide();
        $(this.selector + ' .gdm-thinking-text').html(html);
        $(this.selector + ' .gdm-thinking').show();
    };

    GdmSimpleFileBrowser.prototype.startThinking = function () {
        $(this.selector + ' .gdm-nextprev-div a').attr('disabled', 'disabled').hide();
        $(this.selector + ' .gdm-thinking-text').html('Loading...');
        $(this.selector + ' .gdm-browsebox').hide();
        $(this.selector + ' .gdm-thinking').show();
    };

    GdmSimpleFileBrowser.prototype.stopThinking = function () {
        $(this.selector + ' .gdm-thinking').hide();
    };

    GdmSimpleFileBrowser.prototype.displayError = function (error) {

        if (error.errors && error.errors.length > 0) {
            var errorhtml = this.serviceHandler.getErrorHTML(error);
            this.displayMessage(errorhtml);
        }
    };

    GdmSimpleFileBrowser.prototype._gdmMakeListItem = function (links) {
        var attrs =
        {
            'href': links.viewer.url, 'class': "gdm-file-link",
            'gdm-data-id': links.id
        };

        var htmlItem = $('<div class="gdm-drivefile-div" />');

        var iconSpan = $('<span class="gdm-drivefile-icon" />')
            .append($(links.icon.url ? '<img src="' + links.icon.url + '" width="16" height="16" '
            + (links.icon.color ? ' style="background-color: ' + links.icon.color + '" ' : '') + ' />'
                : '<span style="width: 16px; height: 16px; background-color: ' + links.icon.color + '" />'));
        var titleSpan = $('<span class="gdm-drivefile-title" />')
            .append($('<a />', attrs)
                .text(links.title));

        htmlItem.append(iconSpan);
        htmlItem.append(titleSpan);

        return htmlItem;
    };

    GdmSimpleFileBrowser.prototype.startQuery = function(current_search_query, pageToken) {
        this.current_search_query = current_search_query;
        this.startThinking();
        var self = this;
        this.serviceHandler.makeAPICall(current_search_query, pageToken,
            function(linkslist, current_search_query, thisPageToken, newNextPageToken, newPrevPageToken) {
                // Disable OK button
                if (self.options.onFileSelected) {
                    self.options.onFileSelected('');
                }

                if (linkslist.hasOwnProperty('error')) {
                    self.displayError(linkslist.error);
                } else {
                    self.displayFiles(linkslist, current_search_query, thisPageToken, newNextPageToken, newPrevPageToken);
                }
            });
    };

    GdmSimpleFileBrowser.prototype.displayFiles = function (linkslist, current_search_query, thisPageToken, nextPageToken, prevPageToken) {
        this.stopThinking();
        $('.gdm-nextprev-div a').removeAttr('disabled');

        var fileslist = document.createDocumentFragment();

        if (linkslist.length > 0) {
            for (var i = 0; i < linkslist.length; ++i) {
                fileslist.appendChild(this._gdmMakeListItem(linkslist[i]).get(0));
            }
        }
        else {
            var htmlItem = $('<div class="gdm-nofiles-div" />');
            var spanItem = $('<span class="gdm-drivefile-title">No matching Drive files found</span>');

            if (current_search_query != '') {
                var self = this;
                spanItem.append(' (<a href="#" class="gdm-search-clear">Clear search</a>)');
                spanItem.find('.gdm-search-clear').on('click', function (e) {
                    if (self.options.onClearSearchClicked) {
                        self.options.onClearSearchClicked(e);
                    }
                });
            }

            htmlItem.append(spanItem);

            fileslist.appendChild(htmlItem.get(0));
        }

        $(this.selector + ' .gdm-filelist').empty().append(fileslist).show()
            .find('div.gdm-drivefile-div').on('click', this.fileClicked);

        var self = this;
        // next and prev buttons
        if (nextPageToken) {
            $('.gdm-next-link').show().off('click').click(function (event) {
                self.startQuery(self.current_search_query, nextPageToken);
            });
        }
        else {
            $('.gdm-next-link').hide();
        }

        if (thisPageToken) {
            $('.gdm-prev-link').show().off('click').click(function (event) {
                self.startQuery(self.current_search_query, prevPageToken);
            });
        }
        else {
            $('.gdm-prev-link').hide();
        }
    };

    GdmSimpleFileBrowser.prototype.fileClicked = function (event) {
        var elt = $(event.delegateTarget);
        var id = undefined;

        if (elt.hasClass('gdm-selected')) {
            elt.removeClass('gdm-selected');
        }
        else {
            $('.gdm-drivefile-div.gdm-selected').removeClass('gdm-selected');
            elt.addClass('gdm-selected');

            var anchor = elt.find('span.gdm-drivefile-title a');

            id = anchor.attr('gdm-data-id');
        }

        if (this.options.onFileSelected) {
            this.options.onFileSelected(id);
        }

        event.preventDefault();
        return false;
    };

    return GdmSimpleFileBrowser;
})(jQuery);

