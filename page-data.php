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
	
	var COLOR_TJI_BLUE = '#0B5D93';
	var COLOR_TJI_RED = '#CE2727';
	var COLOR_TJI_DEEPBLUE = '#252939';
	var COLOR_TJI_PURPLE = '#4D3B6B';
	var COLOR_TJI_YELLOW = '#F1AB32';
	var COLOR_TJI_TEAL = '#50E3C2';
	var COLOR_TJI_DEEPRED = '#872729';
	var COLOR_TJI_DEEPPURPLE = '#2D1752';
	var DEFAULT_PALETTE = [
		COLOR_TJI_BLUE, COLOR_TJI_RED, COLOR_TJI_DEEPBLUE, COLOR_TJI_PURPLE,
		COLOR_TJI_YELLOW, COLOR_TJI_TEAL, COLOR_TJI_DEEPRED, COLOR_TJI_DEEPPURPLE,
	]

	var category_colors = {
		'race': {
			'WHITE': COLOR_TJI_BLUE,
			'BLACK': COLOR_TJI_RED,
			'HISPANIC': COLOR_TJI_PURPLE,
			'OTHER': COLOR_TJI_DEEPBLUE,
		},
		'sex': {
			'M': COLOR_TJI_BLUE,
			'F': COLOR_TJI_RED,
		},
	}

	// Fetch the CDR data, store in global variable 
	jQuery(document).ready(function() {

		var data_cdr;

		jQuery('#js-filters').on('change', function(e) {
			var filters = jQuery(this).serializeArray();
			grouped_filters = [];
			_.map(filters, function(filter) {
				if(grouped_filters[filter.name]) {
					grouped_filters[filter.name].push(filter.value);
				} else {
					grouped_filters[filter.name] = [filter.value]
				}
			});
			var data_filtered = _.filter(data_cdr, function(val){
				for (filter in grouped_filters) {
					if (grouped_filters[filter].indexOf(val[filter]) === -1 ) return false;
				}
				return true;
			})
			update_charts(data_filtered);
		})

		console.log("Fetching data from TJI server...");
		jQuery.ajax({
			  // url: '/cdr_minimal.json',
			  url: '/cleaned_custodial_death_reports.json',
			  type: "GET",
			  dataType: 'json',
			  success: function getCustodialDeathsTotal(data) {
			  		console.log('...success!');
						data_cdr = data;
				    document.getElementById("cdr-total-count").innerHTML = data_cdr.length;
				    _.each(data_cdr, function(js) {
				    	js['year'] = parseInt(js['death_date'].substring(0, 4));
				    	if (js['age_at_time_of_death'] < 0) {
				    		js['age_group'] = undefined;
				    	} else {
					    	age_decade = Math.floor(js['age_at_time_of_death'] / 10) * 10
					    	if (age_decade > 59) {
					    		js['age_group'] = '60+'
					    	} else {
					    		js['age_group'] = age_decade + '-' + (age_decade + 9)
					    	}
					    }
				    })
				    update_charts(data_cdr);
			  },
			  error: function(err) {
				  console.log("...data fetch failed! Error:", err);
			  }
			});
	});

	function update_charts(data){
		chart_cdr_by_year(data, "chart1");
		chart_cdr_donut(data, "race", "chart2");
		chart_cdr_donut(data, "sex", "chart3");
		chart_cdr_donut(data, "manner_of_death", "chart4");
		chart_cdr_donut(data, "age_group", "chart5");
		// Deaths by Age, and Deaths by Agency
	}

	function chart_cdr_by_year(data, eltid) {
		var ctx = document.getElementById(eltid).getContext('2d');
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

	function chart_cdr_donut(data, column, eltid) {
		var ctx = document.getElementById(eltid).getContext('2d');
		var grouped = _.groupBy(data, column);
		delete grouped[null];
		var keys = _.sortBy(_.keys(grouped));
		var values = _.map(keys, function(k){ return grouped[k].length});
		var colors;
		if (category_colors[column] == undefined) {
			colors = DEFAULT_PALETTE;
		} else {
			colors = _.map(keys, function(k) { return category_colors[column][k]});
		}
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
		    		text: "Custodial deaths by " + column,
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
total deaths in police custody since 2005
</p>

<div class="row">
<div class="col-sm-12 col-lg-6">
	<canvas id="chart1"></canvas>
</div>

<div class="col-sm-12 col-lg-6">
	<canvas id="chart2"></canvas>
</div>

<div class="col-sm-12 col-lg-6">
	<canvas id="chart3"></canvas>
</div>

<div class="col-sm-12 col-lg-6">
	<canvas id="chart4"></canvas>
</div>

<div class="col-sm-12 col-lg-6">
	<canvas id="chart5"></canvas>
</div>

</div>

</main></div>

<aside id="secondary">
	<form id="js-filters">
			<fieldset>
				<legend>Sex</legend>
					<div>
          <input id="Male" type="checkbox" name="sex" value="M" checked>
          <label for="Male">Male</label>
        	</div>
        	<div>
          <input id="Female" type="checkbox" name="sex" value="F" checked>
          <label for="Female">Female</label>
        	</div>
 			</fieldset>
 			<fieldset>
				<legend>Race</legend>
					<div>
          <input id="Male1" type="checkbox" name="race" value="BLACK" checked>
          <label for="Male1">BLACK</label>
        	</div>
        	<div>
          <input id="Female1" type="checkbox" name="race" value="WHITE" checked>
          <label for="Female1">WHITE</label>
        	</div>
 			</fieldset>
	</form>
</aside>


<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<?php
	
get_footer();
