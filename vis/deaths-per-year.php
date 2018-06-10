<?php
	
	/* 
		This visualization shows the racial breakdown of shootings
		for those in custody as well as police officers. 
	*/
	
?>

<script type="text/javascript">
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
		  var year = [];
		  var deathsPerYear = new Object();
		  				  
		  //Loop through dataset and create a count of deaths per year
		  for (var i=0; i<data.length; i++) {
			  year = data[i].death_date_and_time.slice(0,4);
			  if (deathsPerYear.hasOwnProperty(year) == false) {
				  deathsPerYear[year] = 1;
			  } else {
				  deathsPerYear[year] += 1;
			  }
		  }
		  
			//Pass data to vega for charting
	    var vlSpec = {
	      "$schema": "https://vega.github.io/schema/vega-lite/v2.0.json",
	      "description": "A simple bar chart with embedded data.",
	      "data": {
	        "values": []
	      },
	      "mark": "bar",
	      "encoding": {
	        "x": {"field": "Year", "type": "ordinal"},
	        "y": {"field": "Deaths", "type": "quantitative"}
	      }
	    }
		  for (year in deathsPerYear) {
			  if (year >= 2005) {
			  	vlSpec.data.values.push({"Year": year, "Deaths": deathsPerYear[year]});
			  }
		  }
		  console.log(vlSpec.data.values);
	    vegaEmbed("#vis", vlSpec);				  
		  
	  },
	  failure: function () {
			console.log("Failed to retrieve data from data.world.");
	  }
	});
</script>