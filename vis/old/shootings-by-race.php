<?php
	
	/* 
		This visualization shows the racial breakdown of shootings
		for those in custody as well as police officers. 
	*/
	
?>

<!-- Container for visualization -->
<div id="deaths-by-race"></div>

<!--
<script type="text/javascript">
	// MAIN AJAX CALL TO ENDPOINT
	jQuery.ajax({
	  url: 'https://api.data.world/v0/sql/tji/officer-involved-shootings',
	  headers: {
	    'Authorization': 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwcm9kLXVzZXItY2xpZW50OmplbnVkYW4iLCJpc3MiOiJhZ2VudDpqZW51ZGFuOjo4ZTA1MjQ2NS0yNTY1LTRkMTEtODQ3Yy02OTEwNmU4NWI2MDUiLCJpYXQiOjE1MjU2MDg3NDIsInJvbGUiOlsidXNlcl9hcGlfYWRtaW4iLCJ1c2VyX2FwaV9yZWFkIiwidXNlcl9hcGlfd3JpdGUiXSwiZ2VuZXJhbC1wdXJwb3NlIjp0cnVlfQ.gKvhAUP3P4ob2jybpUEumhyZ_4Wugdh7S13Zc0Yn4pTPBBreXceww0b-hPdr_bLMYPOTIl9J4aoUesEP0_04mA',
	    },
	  data: {
	    query: 'SELECT shot_civilians.civilian_race, shot_civilians.civilian_died FROM shot_civilians'
	  },
	  type: "GET",
	  success: function (data) {	  
		},
		complete: function(data) {
		  
		  var civilianDeaths = [];
		  				  
		  //Loop through dataset and create a racial breakdown
		  
		  for (var i = 0; i < data.responseJSON.length; i++) {
			  if (data.responseJSON[i].civilian_died == true) {
				  data.responseJSON[i].civilian_died = "Killed";
			  } else {
				  data.responseJSON[i].civilian_died = "Injured";
			  }
				civilianDeaths.push({
					Race: data.responseJSON[i].civilian_race,
					Died: data.responseJSON[i].civilian_died,
					Type: "Civilian",
					Total: 1			
				});
		  }
			
			jQuery.ajax({
			  url: 'https://api.data.world/v0/sql/tji/officer-involved-shootings',
			  headers: {
			    'Authorization': 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwcm9kLXVzZXItY2xpZW50OmplbnVkYW4iLCJpc3MiOiJhZ2VudDpqZW51ZGFuOjo4ZTA1MjQ2NS0yNTY1LTRkMTEtODQ3Yy02OTEwNmU4NWI2MDUiLCJpYXQiOjE1MjU2MDg3NDIsInJvbGUiOlsidXNlcl9hcGlfYWRtaW4iLCJ1c2VyX2FwaV9yZWFkIiwidXNlcl9hcGlfd3JpdGUiXSwiZ2VuZXJhbC1wdXJwb3NlIjp0cnVlfQ.gKvhAUP3P4ob2jybpUEumhyZ_4Wugdh7S13Zc0Yn4pTPBBreXceww0b-hPdr_bLMYPOTIl9J4aoUesEP0_04mA',
			    },
			  data: {
			    query: 'SELECT shot_officers.officer_race, shot_officers.officer_died FROM shot_officers'
			  },
			  type: "GET",
			  success: function (data) {

				  var officerDeaths = [];
				  				  
				  //Loop through dataset and create a racial breakdown
				  
				  for (var i = 0; i < data.length; i++) {
					  if (data[i].officer_died == true) {
						  data[i].officer_died = "Killed";
					  } else {
						  data[i].officer_died = "Injured";
					  }
						officerDeaths.push({
							Race: data[i].officer_race,
							Died: data[i].officer_died,
							Type: "Officer",
							Total: 1			
						});
				  }
				  
				  var totalDeaths = civilianDeaths.concat(officerDeaths);
					
					//Pass data to vega for charting
					
			    var vlSpec = {
			      "$schema": "https://vega.github.io/schema/vega-lite/v2.0.json",
			      "description": "A simple bar chart with embedded data.",
			      "width": 1000,
			      "height": 100,
			      "mark": "bar",
			      "data": {
			        "values": totalDeaths
			      },
			      "encoding": {
				      "row": {
					      "field": "Race", "type": "ordinal", "title": ""
				      },
			        "x": {
				        "field": "Total", "type": "quantitative",
				        "aggregate": "sum"
				      },
			        "y": {
				        "field": "Type", "type": "nominal", "title": ""
				      },
			        "color": {
				        "field": "Died", "type": "nominal",
				        "legend": {
					        "title": ""
				        }
				      }
			      }
		    	}
				  
			    vegaEmbed("#deaths-by-race", vlSpec);		
			  }
			});		  
	  },
	  failure: function () {
			console.log("Failed to retrieve data from data.world.");
	  }
	});
</script>
-->