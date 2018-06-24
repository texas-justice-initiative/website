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
  //WRITE documentation for how you expect people to use these classes

  var COLOR_TJI_BLUE = '#0B5D93';
  var COLOR_TJI_RED = '#CE2727';
  var COLOR_TJI_DEEPBLUE = '#252939';
  var COLOR_TJI_PURPLE = '#4D3B6B';
  var COLOR_TJI_YELLOW = '#F1AB32';
  var COLOR_TJI_TEAL = '#50E3C2';
  var COLOR_TJI_DEEPRED = '#872729';
  var COLOR_TJI_DEEPPURPLE = '#2D1752';

  var COLOR_INCOMPLETE_YEARS = '#AAAAAA'

  var TJIGroupByBarChart = function(elt_id, groupBy, data, missing_data_label) {
    this.elt_id = elt_id;
    this.groupBy = groupBy;
    this.chart = null;
    this.missing_data_label = missing_data_label || '(not given)';
    this.color_mapping = null;
    this.create(data);
  }

  TJIGroupByBarChart.prototype.type = 'bar';

  TJIGroupByBarChart.prototype.color_palette = [COLOR_TJI_BLUE];

  TJIGroupByBarChart.prototype.create = function(data) {
    var that = this;
    var grouped = this.get_group_counts(data);

    // 'create' is only run with the full data set.
    // So we store our color mapping (e.g. "Male" = blue) so that
    // subsequent filterings and changes don't alter the color
    // associated with males.
    this.color_mapping = {};
    _.each(grouped.keys, function(k, idx) {
      that.color_mapping[k] = that.color_palette[idx % that.color_palette.length];
    });
    var colors = _.map(grouped.keys, function(k) {
      return that.color_mapping[k]
    })

    // Build the chart
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
    var that = this;
    var grouped = this.get_group_counts(data);
    this.chart.data.datasets[0].data = grouped.counts;
    this.chart.data.labels = grouped.keys;
    
    this.chart.data.datasets[0].backgroundColor = _.map(grouped.keys, function(k) {
      return that.color_mapping[k]
    });
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

  TJIGroupByBarChart.prototype.get_group_counts = function(data) {
    data = _.filter(data, this.groupBy);
    var grouped = _.groupBy(data, this.groupBy);
    var keys = _.sortBy(_.keys(grouped));
    if (keys.indexOf(this.missing_data_label) !== -1) {
      keys.splice(keys.indexOf(this.missing_data_label), 1);
      keys.push(this.missing_data_label);
    }
    var counts = _.map(keys, function(k){ return grouped[k].length});
    return {
      keys: keys,
      counts: counts
    };
  }

  var TJIGroupByDonutChart = function(elt_id, groupBy, data, missing_data_label) {
    TJIGroupByBarChart.call(this, elt_id, groupBy, data, missing_data_label);
  }
  TJIGroupByDonutChart.prototype = Object.create(TJIGroupByBarChart.prototype);
  TJIGroupByDonutChart.prototype.constructor = TJIGroupByDonutChart;
  TJIGroupByDonutChart.prototype.type = 'doughnut';
  TJIGroupByDonutChart.prototype.color_palette = [
    COLOR_TJI_BLUE, COLOR_TJI_RED, COLOR_TJI_DEEPBLUE, COLOR_TJI_PURPLE,
    COLOR_TJI_YELLOW, COLOR_TJI_TEAL, COLOR_TJI_DEEPRED, COLOR_TJI_DEEPPURPLE,
  ];
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

  var ChartView = function(chart_configs, charts_elt_id, filters_elt_id, chart_template, count_template){

    this.state = {
      data: null,
      active_filters: [], //put active filters here
      charts: [],
      $count: null
    }

    this.chart_configs = chart_configs;
    this.charts_elt_id = charts_elt_id;
    this.filters_elt_id = filters_elt_id;
    this.chart_template = chart_template;
    this.count_template = count_template;
    this.filters = null;

    this.get_data();
  }

  ChartView.prototype.missing_data_label = '(not given)';

  ChartView.prototype.get_data = function() {
    var that = this;
    jQuery.getJSON('/cleaned_custodial_death_reports.json')
      .done(function(data){
        that.state.data = data;
        that.parse_data();
        that.create_charts();
        that.create_filter_panel();
        that.attach_events();
      })
      .fail(function(e){
        console.log('error fetching data from: ' + this.state.url, e);
      })
  }

  ChartView.prototype.parse_data = function() {

    var that = this;
    var column_whitelist = [
      'year', 'race', 'sex', 'manner_of_death', 'age_group',
      'type_of_custody', 'death_location_type', 'means_of_death',
    ]

      // Create new columns

    _.each(this.state.data, function(data_row, i) {
      // Build column for year
      data_row['year'] = data_row['death_date'].substring(0, 4);

      // Create age group buckets
      if (data_row['age_at_time_of_death'] < 0) {
        data_row['age_group'] = null;
      } else {
        age_decade = Math.floor(data_row['age_at_time_of_death'] / 10) * 10
        if (age_decade > 59) {
          data_row['age_group'] = '60+'
        } else {
          data_row['age_group'] = age_decade + '-' + (age_decade + 9)
        }
      }

      _.each(column_whitelist, function(key) {
        if (data_row[key] === undefined || data_row[key] === null || data_row[key] === '') {
          data_row[key] = that.missing_data_label;
        }
      });
    });

    // Generate filter values

    filters = []
    _.each(column_whitelist, function(column) {
      // Get a sorted list of all the unique values for this column
      values = _.filter(_.sortBy(_.uniq(_.map(that.state.data, column))), _.identity);
      if (values.indexOf(that.missing_data_label) !== -1) {
        values.splice(values.indexOf(that.missing_data_label), 1);
        values.push(that.missing_data_label);
      }
      filters.push({
        key: column,
        values: values
      });
    });
    this.filters = filters;
  }

  ChartView.prototype.create_filter_panel = function() {
    var $filters = jQuery('<form id="js-TJIfilters" />');
    _.each(this.filters, function(f) {
      var fieldset = jQuery('<fieldset><legend>' + f.key.replace(/_/g, " ") + '</legend></fieldset>');
      _.each(f.values, function(v) {
        var input = jQuery('<input/>', {
          type: "checkbox",
          checked: "checked",
          id: v,
          name: f.key,
          value: v,
        });
        var label = jQuery('<label/>', {
          for: v,
        }).text(v);
        fieldset.append(jQuery('<div></div>').append(input, label));
      });
      $filters.append(fieldset);
    });
    jQuery(this.filters_elt_id).append($filters);
    this.state.active_filters = jQuery(this).serializeArray();  
  }

  ChartView.prototype.create_charts = function() {
    var that = this;
    this.state.$count = jQuery(this.count_template.replace('{count}', this.state.data.length)).prependTo(this.charts_elt_id);
    _.each(this.chart_configs, function(config, i){
      var id = 'tjichart_' + i;
      jQuery(that.chart_template).append('<canvas id="'+id+'"/>').appendTo(that.charts_elt_id);
      
      var chart_constructor;
      switch(config.type) {
        case 'donut': 
          chart_constructor = TJIGroupByDonutChart;
          break;
        case 'bar':
          chart_constructor = TJIGroupByBarChart;
          default:
          break;
      }
      that.state.charts.push(
        new chart_constructor(id, config.group_by, that.state.data, that.missing_data_label)
      );
    });
  }

  ChartView.prototype.attach_events = function() {
    var that = this;
    jQuery('#js-TJIfilters').on('change', function(e) {
      that.state.active_filters = jQuery(this).serializeArray();
      that.filter_data();
    })
  }

  ChartView.prototype.filter_data = function() {
    var grouped_filters = [];
    _.map(this.state.active_filters, function(filter) {
      if(grouped_filters[filter.name]) {
        grouped_filters[filter.name].push(filter.value);
      } else {
        grouped_filters[filter.name] = [filter.value]
      }
    });

    var data = _.filter(this.state.data, function(val){
      for (filter in grouped_filters) {
        if (val[filter] && grouped_filters[filter].indexOf(val[filter]) === -1 ) return false;
      }
      return true;
    })

    this.update_charts(data);
  }

  ChartView.prototype.update_charts = function(data) {
    this.state.$count.text(data.length + ' records');
    _.each(this.state.charts, function(chart){
      chart.update(data);
    })
  }

  jQuery(function(){
    var chartView = new ChartView([
      {type: 'bar', group_by:'year'},
      {type: 'donut', group_by:'race'},
      {type: 'donut', group_by:'sex'},
      {type: 'donut', group_by:'manner_of_death'},
      {type: 'donut', group_by:'age_group'},
    ], '#js-TJIChartView', '#secondary', '<div class="col-sm-12 col-lg-6" />', '<div class="col-sm-12">{count} records</div>');
  })

</script>

<div id="js-TJIChartView" class="row">
</div>

</main></div>

<aside id="secondary">
</aside>


<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdn.rawgit.com/emn178/Chart.PieceLabel.js/master/build/Chart.PieceLabel.min.js"></script>

<?php
  
get_footer();
