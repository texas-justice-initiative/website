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
		// while ( have_posts() ) :
		// 	the_post();

		// 	get_template_part( 'template-parts/content', 'page' );

		// endwhile; // End of the loop.
		?>


<script>
	var data_cdr;
	var COLOR_TJI_BLUE = '#0B5D93'

	// Fetch the CDR data, store in global variable 
	jQuery(document).ready(function() {
		console.log("Fetching data from TJI server...");
		jQuery.ajax({
			  url: '/cdr_minimal.json',
			  type: "GET",
			  dataType: 'json',
			  success: function getCustodialDeathsTotal(data) {
			  		console.log('...success!');
					data_cdr = data;
				    document.getElementById("cdr-total-count").innerHTML = data_cdr.length;
				    make_chart_1();
			  },
			  error: function(err) {
				  console.log("...data fetch failed! Error:", err);
			  }
			});
	});

	function make_chart_1() {
		var ctx = document.getElementById("chart1").getContext('2d');
		var grouped = _.groupBy(data_cdr, 'year');
		var keys = _.sortBy(_.keys(grouped));
		var values = _.map(keys, function(k){ return grouped[k].length});
		var colors = [];
		for (i = 0; i < keys.length; ++i) {
			if (i > 0 && i < keys.length - 1) {
				colors.push(COLOR_TJI_BLUE);
			} else {
				colors.push(undefined);
			}
		}
		var myLineChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		    	labels: keys,
		    	datasets:[
		    		{
			    		data: values,
			    		fill: false,
			    		backgroundColor: colors,
			    		lineTension: 0.1
		    		}
		    	]
		    },
		    options: {
		    	title: {
		    		display: true,
		    		text: "Custodial Deaths by Year",
		    		fontSize: 36,
		    	},
    			legend: {
    				display: false
    			},
    			scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        },
    		}
		});
	}

</script>


<p class="count-summary">
<span id='cdr-total-count'>...</span>
total deaths in police custody since 2006
</p>

<canvas id="chart1" width="400" height="200"></canvas>

</main></div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<?php
	
get_footer();
