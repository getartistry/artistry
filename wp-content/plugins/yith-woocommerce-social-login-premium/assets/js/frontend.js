jQuery(document).ready(function ($) {
    "use strict";

    $('.show-ywsl-box').on('click', function (e) {
        e.preventDefault();
        $('.ywsl-box').slideToggle();
    });

});

var href = window.location.href,
    path = window.location.origin + window.location.pathname,
    hash = href.replace(path, '');

if ((hash == '#') || (hash == '#_=_')) {
    history.replaceState(null, null, path);
}