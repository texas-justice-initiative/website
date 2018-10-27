// <!-- Fetch JSON data for dynamic slider numbers -->
jQuery(document).ready(function() {
  jQuery.getJSON("/data/cdr_compressed.json", function (cdrData) {
    var cdrStartingYear = cdrData.meta.lookups.year[0];
    jQuery("#js-cdr-year").html(cdrStartingYear);
    var cdrTotalRecords = cdrData.meta.num_records;
    jQuery("#js-cdr-total").html(cdrTotalRecords.toLocaleString('en'));
  });
  jQuery.getJSON("/data/ois_compressed.json", function (oisData) {
    var oisStartingYear = oisData.meta.lookups.year[0];
    jQuery("#js-ois-year").html(oisStartingYear);
    var oisTotalRecords = oisData.meta.num_records;
    jQuery("#js-ois-total").html(oisTotalRecords.toLocaleString('en'));
  });
  jQuery.getJSON("/data/ois_officers_compressed.json", function (officersData) {
    var officersStartingYear = officersData.meta.lookups.year[0];
    jQuery("#js-officers-year").html(officersStartingYear);
    var officersTotalRecords = officersData.meta.num_records;
    jQuery("#js-ois-officers-total").html(officersTotalRecords.toLocaleString('en'));
  });
});
