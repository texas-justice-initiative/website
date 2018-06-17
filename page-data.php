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
	var COLOR_TJI_BLUE = '#0B5D93';
	var COLOR_TJI_RED = '#CE2727';
	var COLOR_TJI_DEEPBLUE = '#252939';
	var COLOR_TJI_PURPLE = '#4D3B6B';
	var COLOR_TJI_YELLOW = '#F1AB32';
	var COLOR_TJI_TEAL = '#50E3C2';
	var COLOR_TJI_DEEPRED = '#872729';
	var COLOR_TJI_DEEPPURPLE = '#2D1752';

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
				    update_charts(data_cdr);
			  },
			  error: function(err) {
				  console.log("...data fetch failed! Error:", err);
			  }
			});
	});

	function update_charts(data){
		make_chart_1(data);
		make_chart_2(data);
	}

	function make_chart_1(data) {
		var ctx = document.getElementById("chart1").getContext('2d');
		var grouped = _.groupBy(data, 'year');
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

	function make_chart_2(data) {
		var ctx = document.getElementById("chart2").getContext('2d');
		var grouped = _.groupBy(data, 'race');
		delete grouped[null];
		var keys = _.sortBy(_.keys(grouped));
		var values = _.map(keys, function(k){ return grouped[k].length});
		var RACE_COLORS = {
			'WHITE': COLOR_TJI_BLUE,
			'BLACK': COLOR_TJI_RED,
			'HISPANIC': COLOR_TJI_PURPLE,
			'OTHER': COLOR_TJI_DEEPBLUE,
		}
		var colors = _.map(keys, function(k) { return RACE_COLORS[k]});
		var myChart = new Chart(ctx, {
		    type: 'doughnut',
		    data: {
		    	labels: keys,
		    	datasets:[
		    		{
			    		data: values,
			    		backgroundColor: colors,
			    		fill: false,
			    		lineTension: 0.1
		    		}
		    	]
		    },
		    options: {
		    	title: {
		    		display: true,
		    		text: "Custodial Deaths by Race",
		    		fontSize: 36,
		    	},
    			legend: {
    				display: true,
    				position: 'left',
    				labels: {
    					fontSize: 18,
    				}
    			},
    		},
		});
	}

</script>


<p class="count-summary">
<span id='cdr-total-count'>...</span>
total deaths in police custody since 2006
</p>

<div class="chart-container">
	<canvas id="chart1"></canvas>
</div>

<div class="chart-container">
	<canvas id="chart2"></canvas>
</div>

</main></div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<?php
	
get_footer();
