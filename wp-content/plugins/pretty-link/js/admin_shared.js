function toggle_iphone_instructions() {
  jQuery('.iphone_instructions').slideToggle();
}

jQuery(document).ready(function($) {
  $(".prli-social-button-checkboxes").sortable();

  if ($.fn.magnificPopup) {
    $('.prli-update').magnificPopup({
      delegate: '.prli-image-popup',
      type: 'image',
      mainClass: 'mfp-prli',
      gallery: {
        enabled: true
      }
    });
  }
});

