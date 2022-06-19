
jQuery.noConflict();
jQuery(document).ready(function($) {
  $('.kdi_wg_slider').each(function(index) {
    this.id = this.className + index;
    loadSlider(this.id);
  });

  function onSlideToggle(id, action, dot = 0) {
    var current = $(`#${id}`).data('current');
    var length = $(`#${id}`).data('length') - 1;

    var step;
    if( action == 'prev' ) {
      step = (current == 0) ? length : current - 1;
    } else if( action == 'next' ) {
      step = (current == length) ? 0 : current + 1;
    } else {
      step = dot;
    }

    var slides = $(`#${id} .kdi_slider-item`);
    $(slides[current]).removeClass('active');
    $(slides[step]).addClass('active');

    var dots = $(`#${id} .kdi_slider-dot`);
    $(dots[current]).removeClass('active');
    $(dots[step]).addClass('active');
    
    $(`#${id}`).data('current', step);
  }

  function loadSlider(id) {
    $(id).ready(function() {

      $(`#${id} .kdi_slider-prev`).click( () => onSlideToggle(id, 'prev') );
      $(`#${id} .kdi_slider-next`).click( () => onSlideToggle(id, 'next') );
      $(`#${id} .kdi_slider-dot`).each(function(index) {
        $(this).data('key', index);
        $(this).click(function() {
          var key = $(this).data('key');
          onSlideToggle(id, 'dot', key);
        });
      });
    })
  }
});