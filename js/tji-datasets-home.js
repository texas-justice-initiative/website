// MAIN AJAX CALL TO ENDPOINT
jQuery.ajax({
  url: 'https://api.data.world/v0/sql/tji/deaths-in-custody',
  headers: {
    'Authorization': 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwcm9kLXVzZXItY2xpZW50OmplbnVkYW4iLCJpc3MiOiJhZ2VudDpqZW51ZGFuOjo4ZTA1MjQ2NS0yNTY1LTRkMTEtODQ3Yy02OTEwNmU4NWI2MDUiLCJpYXQiOjE1MjU2MDg3NDIsInJvbGUiOlsidXNlcl9hcGlfYWRtaW4iLCJ1c2VyX2FwaV9yZWFkIiwidXNlcl9hcGlfd3JpdGUiXSwiZ2VuZXJhbC1wdXJwb3NlIjp0cnVlfQ.gKvhAUP3P4ob2jybpUEumhyZ_4Wugdh7S13Zc0Yn4pTPBBreXceww0b-hPdr_bLMYPOTIl9J4aoUesEP0_04mA',
    },
  data: {
    query: 'SELECT cleaned_custodial_death_reports.death_date_and_time FROM cleaned_custodial_death_reports'
  },
  type: "GET",
  success: function (data) {
		
	  var deaths = [];
	  for (var i=0; i<data.length; i++) {
		  year = data[i].death_date_and_time.slice(0,4);

		  if ( year >= 2005 ) {
			  deaths.push(year);
		  }
		}
	  document.querySelector(".tji-query").innerHTML = deaths.length;
  },
  failure: function () {
		console.log("Failed to retrieve data from data.world.");
  }
});

//TOGGLE CHECKBOXES
jQuery(document).ready(function($) {
  $("#filter-section input:checkbox").prop("checked", true);

  $('.js-filter-input-all').click(function() {
      var $checkboxes = $(this).parent().parent().parent().find('input[type=checkbox]');
      $checkboxes.prop('checked', $(this).is(':checked'));
  }); 
});