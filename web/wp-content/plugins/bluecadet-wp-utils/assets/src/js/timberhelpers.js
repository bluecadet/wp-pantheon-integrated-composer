(function($) {

  $(document).ready(function() {

      $('.ksm-esque__toggle').on('click', function() {
        var $btn = $(this);
        var $parent = $btn.closest('.ksm-esque__item');
        var $list = $parent.find('.ksm-esque__sub-content:first');

        if ($list.hasClass('open')) {
          $list.removeClass('open');
          $btn.html('+');
        } else {
          $list.addClass('open');
          $btn.html('-');
        }
      });
  });
})(jQuery);
