jQuery.noConflict();
jQuery(document).ready(function($) {
    var gallery = $('.gallery');
    var isDown = false;
    var startX;
    var scrollLeft;

    $(gallery).mousedown(function(event) {
        isDown = true;
        startX = event.pageX - gallery.offsetLeft;
        scrollLeft = gallery.offsetLeft;
        $(gallery).addClass('active');
    });

    $(gallery).mouseleave(function(event) {
        isDown = false;
        $(gallery).removeClass('active');
    });

    $(gallery).mouseup(function(event) {
        isDown = false;
        $(gallery).removeClass('active');
    });

    $(gallery).mousemove(function(event) {
        if( !isDown ) return;
        event.preventDefault();
        const SCROLL_SPEED = 3;
        const x = event.pageX * SCROLL_SPEED;
        const walk = (x - startX) * SCROLL_SPEED;
        gallery.scrollLeft = scrollLeft - walk;
    });
});