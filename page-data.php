<?php
/**
 * The template for displaying explore the data pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Texas_Justice_Initiative
 */

get_header();

// Jen's filter sidebar
// get_template_part( 'template-parts/filter-panel' );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		// Bring in data from WordPress page
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

<script>
jQuery(document).ready(function() {
	jQuery.ajax({
		  url: 'https://api.data.world/v0/sql/tji/deaths-in-custody',
		  headers: {
		    'Authorization': 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJwcm9kLXVzZXItY2xpZW50OmplbnVkYW4iLCJpc3MiOiJhZ2VudDpqZW51ZGFuOjo4ZTA1MjQ2NS0yNTY1LTRkMTEtODQ3Yy02OTEwNmU4NWI2MDUiLCJpYXQiOjE1MjU2MDg3NDIsInJvbGUiOlsidXNlcl9hcGlfYWRtaW4iLCJ1c2VyX2FwaV9yZWFkIiwidXNlcl9hcGlfd3JpdGUiXSwiZ2VuZXJhbC1wdXJwb3NlIjp0cnVlfQ.gKvhAUP3P4ob2jybpUEumhyZ_4Wugdh7S13Zc0Yn4pTPBBreXceww0b-hPdr_bLMYPOTIl9J4aoUesEP0_04mA',
		    },
		  data: {
		    query: 'SELECT COUNT(*) FROM cleaned_custodial_death_reports'
		  },
		  type: "GET",
		  success: function getCustodialDeathsTotal(data) {
				let custodialDeathCount = data[0].count.toLocaleString();
			    document.getElementById("cdr-total-count").innerHTML = custodialDeathCount;
		  },
		  failure: function () {
			  console.log("Ajax request failed!")
		  }
		});
	
});
</script>


<div class="count-summary">TOTAL DEATHS IN POLICE CUSTODY SINCE 2005:<br> 
	<span id="cdr-total-count"></span>
</div>;

	<!-- Visualization code or link to source can go here -->
	<div class="chart-container">
  		<canvas id="explore-page-chart"></canvas>
  	</div>
	<script>
	var ctx = document.getElementById("explore-page-chart").getContext('2d');
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
	        datasets: [{
	            label: '# of Votes',
	            data: [12, 19, 3, 5, 2, 3],
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255,99,132,1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});
	</script>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	
get_footer();
