jQuery.noConflict();
jQuery(document).ready(function($) {
    $('.kdi_carousel--map button').each(function(index) {
        $(this).click(function(event) {
            $('.kdi_carousel--map button').each(function() {
                $(this).removeClass('active');
            })

            let percen = index * 33;
            $('.kdi_carousel--wrapper_inner').css('transform' , `translateX(-${percen}%)`);

            $(this).addClass('active');
        });
    });

});