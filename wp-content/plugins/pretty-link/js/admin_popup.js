jQuery(document).ready(function($) {
  $('.prli-popup.prli-auto-open').each( function() {
    var _this = this;

    $.magnificPopup.open({
      items: {
        src: _this,
        type: 'inline'
      },
      closeOnBgClick: false,
      closeBtnInside: false
    });
  });

  var prli_stop_popup = function(popup, cb) {
    var args = {
      action: 'prli_stop_popup',
      security: PrliPopup.security,
      popup: popup,
    };

    $.post(ajaxurl, args, cb, 'json')
      .fail(function(response) {
        alert(PrliPopup.error);
        $.magnificPopup.close();
      });
  };

  $('.prli-stop-popup').on('click', function(e) {
    e.preventDefault();

    var _this = this;
    var popup = $(this).data('popup');

    prli_stop_popup(popup, function(response) {
      $(_this).trigger('prli-popup-stopped',[popup]);
      $.magnificPopup.close();
      if(typeof $(_this).data('href') !== 'undefined') {
        window.location.href = $(_this).data('href');
      }
    });
  });

  var prli_delay_popup = function(popup, cb) {
    var args = {
      action: 'prli_delay_popup',
      security: PrliPopup.security,
      popup: popup
    };

    $.post(ajaxurl, args, cb, 'json')
      .fail(function(response) {
        alert(PrliPopup.error);
        $.magnificPopup.close();
      });
  };

  $('.prli-delay-popup').on('click', function(e) {
    e.preventDefault();

    var _this = this;
    var popup = $(this).data('popup');

    prli_delay_popup(popup, function(response) {
      $(_this).trigger('prli-popup-delayed',[popup]);
      $.magnificPopup.close();
      if(typeof $(_this).data('href') !== 'undefined') {
        window.location.href = $(_this).data('href');
      }
    });
  });
});

