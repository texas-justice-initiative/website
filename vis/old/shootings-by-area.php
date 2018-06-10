<?php
	
	/* 
		This visualization shows the racial breakdown of shootings
		for those in custody as well as police officers. 
	*/
	
?>

<canvas id="shootingsArea" width="400" height="400"></canvas>

<script>
	var primaryBlue = "#0B5D93",
			secondaryBlue = "#094D79",
			primaryRed = "#CE2727"
			secondaryRed = "#9B1D1D";
	
	var sqlQuery = "WITH civilians AS (SELECT REPLACE(LOWER(incident_county), ' ', '_') AS county, count(*) as civilians_shot FROM shot_civilians GROUP BY county), officers AS (SELECT REPLACE(LOWER(incident_county), ' ', '_') AS county, count(*) as officers_shot FROM shot_officers GROUP BY county), countySize AS (SELECT * FROM tji.auxiliary_datasets.population_estimates_july_1_2017_v2017), estimates AS (SELECT county, COALESCE(civilians_shot, 0) AS civilians_shot, COALESCE(officers_shot, 0) AS officers_shot, CASE WHEN countySize.population < 2500 THEN 'small' WHEN countySize.population > 50000 THEN 'large' ELSE 'medium' END AS size FROM civilians FULL JOIN officers USING (county) FULL JOIN countySize USING (county)) SELECT size, SUM(civilians_shot) AS Civilians, SUM(officers_shot) AS Officers FROM estimates WHERE size != '' GROUP by size";

	// MAIN AJAX CALL TO ENDPOINT
	jQuery.ajax({
	  url: 'https://api.data.world/v0/sql/tji/officer-involved-shootings',
	  headers: {
	    'Authorization': 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwcm9kLXVzZXItY2xpZW50OmplbnVkYW4iLCJpc3MiOiJhZ2VudDpqZW51ZGFuOjo4ZTA1MjQ2NS0yNTY1LTRkMTEtODQ3Yy02OTEwNmU4NWI2MDUiLCJpYXQiOjE1MjU2MDg3NDIsInJvbGUiOlsidXNlcl9hcGlfYWRtaW4iLCJ1c2VyX2FwaV9yZWFkIiwidXNlcl9hcGlfd3JpdGUiXSwiZ2VuZXJhbC1wdXJwb3NlIjp0cnVlfQ.gKvhAUP3P4ob2jybpUEumhyZ_4Wugdh7S13Zc0Yn4pTPBBreXceww0b-hPdr_bLMYPOTIl9J4aoUesEP0_04mA',
	    },
	  data: {
	    query: sqlQuery
	  },
	  type: "GET",
	  success: function (data) {	
			console.log(data);
			
			/* Redundancy in case data is not always pulled in the same order with SQL query */
			var shootings = [
				{ type: "Civilians", small: 0, medium: 0, large: 0	},
				{ type: "Officers", small: 0, medium: 0, large: 0	}
			];
			
			for ( var i = 0; i < data.length; i++ ) {
				if ( data[i].size == "small" ) {
					shootings[0].small += data[i].Civilians;
					shootings[1].small += data[i].Officers;
				} else if ( data[i].size == "medium" ) {
					shootings[0].medium += data[i].Civilians;
					shootings[1].medium += data[i].Officers;
				} else if ( data[i].size == "large" ) {
					shootings[0].large += data[i].Civilians;
					shootings[1].large += data[i].Officers;
				}
			}
			
			console.log(shootings);

			var barChartData = {
					labels: [['Urban Areas','50,000+'], 'Urban Clusters', ['Rural Areas','<2,500'] ],
					datasets: [{
						label: 'Civilian Shootings',
						backgroundColor: primaryRed,
						stack: 'Stack 0',
						data: [
							shootings[0].large,
							shootings[0].medium,
							shootings[0].small
						]
					}, {
						label: 'Officer Shootings',
						backgroundColor: secondaryRed,
						stack: 'Stack 1',
						data: [
							shootings[1].large,
							shootings[1].medium,
							shootings[1].small
						]
					}]
				};
	
			var ctx = document.getElementById('shootingsArea').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'horizontalBar',
				data: barChartData,
				options: {
					title: {
						display: false,
						text: 'TX Officer Involved Shootings Since Sept. 2015',
						fontSize: 18
					},
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					scales: {
						xAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Shooting Incidents Since Sept. 2015',
								fontSize: 18,
								fontColor: primaryBlue,
								padding: 10							
							}
						}],
						yAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Shooting Distribution by Population',
								fontSize: 18,
								fontColor: primaryBlue,
								padding: 10							
							}
						}]
					},
				}
			});  
			
	  },
	  failure: function () {
			console.log("Failed to retrieve dataset.");
	  }
	});	// End Civilians AJAX
</script>