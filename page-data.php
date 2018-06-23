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

	var COLOR_MISSING_DATA = '#AAAAAA'

	var TJIChart = function(elt_id, title, groupBy, data) {
		this.elt_id = elt_id;
		this.groupBy = groupBy;
		this.title = title;
		this.chart = null;
		this.create(data);
	}

	TJIChart.prototype.type = 'bar';

	TJIChart.prototype.groupby_colormaps = {
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
		'year': {
			2005: COLOR_MISSING_DATA,
			'default': COLOR_TJI_BLUE
		},
		'default': [
			COLOR_TJI_BLUE, COLOR_TJI_RED, COLOR_TJI_DEEPBLUE, COLOR_TJI_PURPLE,
			COLOR_TJI_YELLOW, COLOR_TJI_TEAL, COLOR_TJI_DEEPRED, COLOR_TJI_DEEPPURPLE,
		]
	}

	TJIChart.prototype.create = function(data) {
		var grouped = this.get_group_counts(data);
		var colors = this.get_group_colors(grouped.keys);
		this.chart = new Chart(document.getElementById(this.elt_id).getContext('2d'), {
	    type: this.type,
	    data: {
	    	labels: grouped.keys,
	    	datasets:[
	    		{
		    		data: grouped.counts,
		    		fill: false,
		    		backgroundColor: colors,
		    		lineTension: 0.1
	    		}
	    	]
	    },
	    options: this.get_options()
		});
	}

	TJIChart.prototype.update = function(data) {
		var grouped = this.get_group_counts(data);
		this.chart.data.datasets[0].data = grouped.counts;
		this.chart.data.labels = grouped.keys;
		this.chart.update();
	}

	TJIChart.prototype.get_options = function() {

		var options = {
    	title: {
    		display: true,
    		text: this.title,
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
		return _.extend(options, this.get_options_overrides());
	}

	TJIChart.prototype.get_options_overrides = function() {
		return {};
	}

	TJIChart.prototype.get_group_colors = function(keys) {
		var colormap = this.groupby_colormaps[this.groupBy];
		if (!colormap) return this.groupby_colormaps['default'];
		return _.map(keys, function(k) {
			return colormap[k] ? colormap[k] : colormap['default'];
		})
	}

	TJIChart.prototype.get_group_counts = function(data) {
		data = _.filter(data, this.groupBy);
		var grouped = _.groupBy(data, this.groupBy);
		var keys = _.sortBy(_.keys(grouped));
		var counts = _.map(keys, function(k){ return grouped[k].length});
		return {
			keys: keys,
			counts: counts
		};
	}

	var TJIDoughnutChart = function(elt_id, title, groupBy, data) {
		TJIChart.call(this, elt_id, title, groupBy, data);
	}

	TJIDoughnutChart.prototype = Object.create(TJIChart.prototype);
	TJIDoughnutChart.prototype.constructor = TJIDoughnutChart;
	TJIDoughnutChart.prototype.type = 'doughnut';
	TJIDoughnutChart.prototype.get_options_overrides = function() {
		return {
			scales: {},
			legend: {
				display: true,
				position: 'left',
				labels: {
					fontSize: 18,
				}
			},
		};
	}

	// Fetch the CDR data, store in global variable 
	jQuery(document).ready(function() {

		var data_cdr;
		var charts;

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
			_.each(charts, function(chart){
				chart.update(data_filtered);
			})
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
				    charts = make_charts(data_cdr);
			  },
			  error: function(err) {
				  console.log("...data fetch failed! Error:", err);
			  }
			});
	});

	function make_charts(data){
		var charts = [];
		charts.push(new TJIChart('chart1', 'Some Chart', 'year', data));
		charts.push(new TJIDoughnutChart('chart3', 'Some Chart', 'race', data));
		charts.push(new TJIDoughnutChart('chart4', 'Some Chart', 'sex', data));
		charts.push(new TJIDoughnutChart('chart5', 'Some Chart', 'manner_of_death', data));
		charts.push(new TJIDoughnutChart('chart2', 'Some Chart', 'age_group', data));
		return charts;
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
