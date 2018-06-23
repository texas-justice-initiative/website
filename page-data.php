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
    //  the_post();

    //  get_template_part( 'template-parts/content', 'page' );

    // endwhile; // End of the loop.
    ?>


<script>

  //TODO: make color constant object
  //chartview should accept charts config object
  //  id: for where charts need to go
  //  charts: [column1, col2, ...]
  //  template for chart -- or set default in ChartView prototype.chartTemplate
  //build dom objects for charts 
  //chartview should accept filter config object
  //  id: for where filters need to go
  //  filters: [{race: [{black: 'BLACK'}, {white: 'WHITE'}, ...]}, {sex: [{'male':'m'}, ...]}]
  //  template for filter -- or set default in ChartView prototype.filterTemplate
  //build dom objects for filters - should come from ChartView.template
  //WRITE documentation for how you expect people to use these classes

  var COLOR_TJI_BLUE = '#0B5D93';
  var COLOR_TJI_RED = '#CE2727';
  var COLOR_TJI_DEEPBLUE = '#252939';
  var COLOR_TJI_PURPLE = '#4D3B6B';
  var COLOR_TJI_YELLOW = '#F1AB32';
  var COLOR_TJI_TEAL = '#50E3C2';
  var COLOR_TJI_DEEPRED = '#872729';
  var COLOR_TJI_DEEPPURPLE = '#2D1752';

  var COLOR_MISSING_DATA = '#AAAAAA'

  var TJIGroupByBarChart = function(elt_id, groupBy, data) {
    this.elt_id = elt_id;
    this.groupBy = groupBy;
    this.chart = null;
    this.create(data);
  }

  TJIGroupByBarChart.prototype.type = 'bar';

  TJIGroupByBarChart.prototype.groupby_colormaps = {
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

  TJIGroupByBarChart.prototype.create = function(data) {
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

  TJIGroupByBarChart.prototype.update = function(data) {
    var grouped = this.get_group_counts(data);
    this.chart.data.datasets[0].data = grouped.counts;
    this.chart.data.labels = grouped.keys;
    this.chart.update();
  }

  TJIGroupByBarChart.prototype.get_options = function() {
    var options = {
      title: {
        display: true,
        text: "By " + this.groupBy.replace(/_/g, " "),  // Convert underscores to spaces
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
      }
    }
    return _.extend(options, this.get_options_overrides());
  }

  TJIGroupByBarChart.prototype.get_options_overrides = function() {
    return {};
  }

  TJIGroupByBarChart.prototype.get_group_colors = function(keys) {
    var colormap = this.groupby_colormaps[this.groupBy];
    if (!colormap) return this.groupby_colormaps['default'];
    return _.map(keys, function(k) {
      return colormap[k] ? colormap[k] : colormap['default'];
    })
  }

  TJIGroupByBarChart.prototype.get_group_counts = function(data) {
    data = _.filter(data, this.groupBy);
    var grouped = _.groupBy(data, this.groupBy);
    var keys = _.sortBy(_.keys(grouped));
    var counts = _.map(keys, function(k){ return grouped[k].length});
    return {
      keys: keys,
      counts: counts
    };
  }

  var TJIGroupByDonutChart = function(elt_id, groupBy, data) {
    TJIGroupByBarChart.call(this, elt_id, groupBy, data);
  }
  TJIGroupByDonutChart.prototype = Object.create(TJIGroupByBarChart.prototype);
  TJIGroupByDonutChart.prototype.constructor = TJIGroupByDonutChart;
  TJIGroupByDonutChart.prototype.type = 'doughnut';
  TJIGroupByDonutChart.prototype.get_options_overrides = function() {
    return {
      scales: {},
      legend: {
        display: true,
        position: 'left',
        labels: {
          fontSize: 18,
        }
      },
      pieceLabel: {
        mode: function (args) {
          return args.percentage + '%';
        },
        precision: 0,
        showZero: true,
        fontSize: 10,
        fontColor: '#fff',
        // available value is 'default', 'border' and 'outside'
        position: 'default'
      }
    };
  }

  var ChartView = function(url){

    this.state = {
      data: null,
      filters: [],
      charts: [],
      url: url,
    }

    this.initialize();
  }

  ChartView.prototype.get_data = function() {
    var that = this;
    jQuery.getJSON(this.state.url)
      .done(function(data){
        that.state.data = data;
        that.parse_data()
        that.create_charts();
      })
      .fail(function(e){
        console.log('error fetching data from: ' + this.state.url, e);
      })
  }

  ChartView.prototype.parse_data = function() {
    _.each(this.state.data, function(data_row) {
      //build column for year
      data_row['year'] = parseInt(data_row['death_date'].substring(0, 4));
      if (data_row['age_at_time_of_death'] < 0) {
        data_row['age_group'] = undefined;
      } else {
        //create age group buckets
        age_decade = Math.floor(data_row['age_at_time_of_death'] / 10) * 10
        if (age_decade > 59) {
          data_row['age_group'] = '60+'
        } else {
          data_row['age_group'] = age_decade + '-' + (age_decade + 9)
        }
      }
    });
  }

  ChartView.prototype.create_charts = function() {
    this.state.charts.push(new TJIGroupByBarChart('chart1', 'year', this.state.data));
    this.state.charts.push(new TJIGroupByDonutChart('chart2', 'race', this.state.data));
    this.state.charts.push(new TJIGroupByDonutChart('chart3', 'sex', this.state.data));
    this.state.charts.push(new TJIGroupByDonutChart('chart4', 'manner_of_death', this.state.data));
    this.state.charts.push(new TJIGroupByDonutChart('chart5', 'age_group', this.state.data));
  }

  ChartView.prototype.attach_events = function() {
    var that = this;
    jQuery('#js-filters').on('change', function(e) {
      that.state.filters = jQuery(this).serializeArray();
      that.filter_data();
    })
  }

  ChartView.prototype.filter_data = function() {
    var grouped_filters = [];
    _.map(this.state.filters, function(filter) {
      if(grouped_filters[filter.name]) {
        grouped_filters[filter.name].push(filter.value);
      } else {
        grouped_filters[filter.name] = [filter.value]
      }
    });

    var data = _.filter(this.state.data, function(val){
      for (filter in grouped_filters) {
        if (grouped_filters[filter].indexOf(val[filter]) === -1 ) return false;
      }
      return true;
    })

    this.update_charts(data);
  }

  ChartView.prototype.update_charts = function(data) {
    _.each(this.state.charts, function(chart){
      chart.update(data);
    })
  }

  ChartView.prototype.initialize = function() {
    this.attach_events();
    this.get_data();
  }

  jQuery(function(){
    var chartView = new ChartView('/cleaned_custodial_death_reports.json');
  })

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
<script src="https://cdn.rawgit.com/emn178/Chart.PieceLabel.js/master/build/Chart.PieceLabel.min.js"></script>

<?php
  
get_footer();
