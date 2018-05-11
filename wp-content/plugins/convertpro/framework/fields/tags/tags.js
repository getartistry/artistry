/*global Taggle*/
(function() {
    'use strict';
    var domains = ['google.com', 'facebook.com', 'yahoo.com'];

    jQuery(".cp-tags").tagit({
        availableTags: domains
    });

}());