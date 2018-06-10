


// MAIN AJAX CALL TO ENDPOINT


//TOGGLE CHECKBOXES
jQuery(document).ready(function($) {
  $("#filter-section input:checkbox").prop("checked", true);

  $('.js-filter-input-all').click(function() {
      var $checkboxes = $(this).parent().parent().parent().find('input[type=checkbox]');
      $checkboxes.prop('checked', $(this).is(':checked'));
  }); 
});