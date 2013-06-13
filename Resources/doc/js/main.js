$(document).ready(function() {
    $('#nav-wrapper').height($("#nav").height());
    $('#nav').affix({
        offset: $('#nav').position()
    });
});