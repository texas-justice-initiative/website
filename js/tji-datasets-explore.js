
//TODO: make color constant object

var COLOR_TJI_BLUE = '#0B5D93';
var COLOR_TJI_RED = '#CE2727';
var COLOR_TJI_DEEPBLUE = '#252939';
var COLOR_TJI_PURPLE = '#4D3B6B';
var COLOR_TJI_YELLOW = '#F1AB32';
var COLOR_TJI_TEAL = '#50E3C2';
var COLOR_TJI_DEEPRED = '#872729';
var COLOR_TJI_DEEPPURPLE = '#2D1752';


// *******************************************************************
// * "Class" for a single-variable bar chart
// *
// * Shows counts of records grouped by a particular column.
// *
// * Constructor arguments:
// *   elt_id: id of HTML canvas element to build Chart on
// *   groupBy: column to group by
// *   data: list of objects representing records
// *   missing_data_label: stand-in label for records missing
// *                       the groupBy column.
// *******************************************************************


var TJIGroupByBarChart = function(elt_id, groupBy, data, missing_data_label) {
  this.elt_id = elt_id;
  this.$root = $(elt_id);
  this.groupBy = groupBy;
  this.colors = null;
  this.chart = null;
  this.missing_data_label = missing_data_label || '(not given)';
  this.ordered_keys = null;
  this.create(data);
}

TJIGroupByBarChart.prototype.type = 'bar';

TJIGroupByBarChart.prototype.color_palette = [COLOR_TJI_BLUE];

// Create the chart for the first time.
// This also permanently sets the legend and color mapping.
TJIGroupByBarChart.prototype.create = function(data) {
  var root_elt_selector = '#' + this.elt_id;
  var canvas_id = this.elt_id + '-canvas'
  var components = []
  jQuery('<div class="tji-chart-title">' + this.groupBy.replace(/_/g, " ") + '</div>').appendTo(root_elt_selector);
  jQuery('<canvas class="tji-chart-canvas" height="1" width="1"/>').attr('id', canvas_id).appendTo(root_elt_selector);

  var that = this;
  var grouped = this.get_sorted_group_counts(data);

  this.colors = _.map(grouped.keys, function(k, i) {
    return that.color_palette[i % that.color_palette.length];
  })

  // 'create' is only run with the full data set.
  // While the user may later filter out certain values
  // (e.g. a particular race or gender), we want to continue
  // to show every possible value in the legend, and keep
  // the color mapping the same. Hence we store the full set
  // of groupBy keys now, in their sorted order.
  this.ordered_keys = grouped.keys

  // Build the chart
  this.chart = new Chart(document.getElementById(canvas_id).getContext('2d'), {
    type: this.type,
    data: {
      labels: grouped.keys,
      datasets:[
        {
          data: grouped.counts,
          fill: false,
          backgroundColor: this.colors,
          lineTension: 0.1
        }
      ]
    },
    options: this.get_options()
  });

  // Build our (custom) legend.
  this.buildLegend(grouped.keys);
}

TJIGroupByBarChart.prototype.buildLegend = function(keys) {
}

// Update the chart with a new (filtered) dataset
TJIGroupByBarChart.prototype.update = function(data) {
  var grouped = this.get_sorted_group_counts(data);
  this.chart.data.datasets[0].data = grouped.counts;
  this.chart.update();
}

// Return an 'options' object for the ChartJS constructor
TJIGroupByBarChart.prototype.get_options = function() {
  var options = {
    title: {
      display: false,
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

// Subclasses should override this to alter options as needed
TJIGroupByBarChart.prototype.get_options_overrides = function() {
  return {};
}

// Group the data by the groupBy key, and count how many records
// have each key. Returns an object with two lists:
//   {
//     keys: [list of sorted, unique groupby keys],
//     counts: [list of number of records for each key]
//   }
TJIGroupByBarChart.prototype.get_sorted_group_counts = function(data) {
  data = _.filter(data, this.groupBy);
  var grouped = _.groupBy(data, this.groupBy);
  var keys;
  if (this.ordered_keys) {
    keys = this.ordered_keys
  } else {
    var keys = _.sortBy(_.keys(grouped));
    // Move the special "missing data" label to the last position
    if (keys.indexOf(this.missing_data_label) !== -1) {
      keys.splice(keys.indexOf(this.missing_data_label), 1);
      keys.push(this.missing_data_label);
    }
  }
  var counts = _.map(keys, function(k){ return (grouped[k] || []).length});
  return {
    keys: keys,
    counts: counts
  };
}


// ********************************************************************
// * Extends TJIGroupByBarChart... but for doughnuts.
// ********************************************************************


var TJIGroupByDoughnutChart = function(elt_id, groupBy, data, missing_data_label) {
  TJIGroupByBarChart.call(this, elt_id, groupBy, data, missing_data_label);
}
TJIGroupByDoughnutChart.prototype = Object.create(TJIGroupByBarChart.prototype);
TJIGroupByDoughnutChart.prototype.constructor = TJIGroupByDoughnutChart;
TJIGroupByDoughnutChart.prototype.type = 'doughnut';
TJIGroupByDoughnutChart.prototype.color_palette = [
  COLOR_TJI_BLUE, COLOR_TJI_RED, COLOR_TJI_DEEPBLUE, COLOR_TJI_PURPLE,
  COLOR_TJI_YELLOW, COLOR_TJI_TEAL, COLOR_TJI_DEEPRED, COLOR_TJI_DEEPPURPLE,
];
TJIGroupByDoughnutChart.prototype.get_options_overrides = function() {
  return {
    scales: {},
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

TJIGroupByDoughnutChart.prototype.buildLegend = function(keys) {
  var legend = jQuery('<div class="tji-chart-legend"/>').attr('id', this.legend_elt_id).appendTo('#' + this.elt_id);
  var that = this;
  _.each(keys, function(k, idx) {
    jQuery('<span class="tji-legend-item" style="background-color:' + that.colors[idx] + '">' + k + '</div>').appendTo(legend);
  })
}


// ********************************************************************
// * "Class" that creates and manages all charts and the filter panel.
// *
// * Constructor arguments:
// *   chart_configs: array of chart config objects,
// *                  e.g. [{type: 'bar', group_by:'year'}, ...]
// *   charts_elt_id: id of HTML element to put charts in
// *   filters_elt_id: id of HTML element to put filter checkboxes in
// *   chart_wrapper: HTML to wrap around each chart's canvas object
// *   record_count_template: HTML for the "showing this man records" element
// *       at the top, containing a "{count}" placeholder somewhere
// *       (which TJIChartView will replace with the record count).
// ********************************************************************


var TJIChartView = function(chart_configs, charts_elt_id, filters_elt_id,
                            chart_wrapper, record_count_template){

  this.state = {
    data: null,
    active_filters: [],
    charts: [],
    $record_count: null
  }

  this.chart_configs = chart_configs;
  this.charts_elt_id = charts_elt_id;
  this.filters_elt_id = filters_elt_id;
  this.chart_wrapper = chart_wrapper;
  this.record_count_template = record_count_template;
  this.filters = null;

  this.get_data();
}

TJIChartView.prototype.missing_data_label = '(not given)';
TJIChartView.prototype.filter_columns = [
  'year', 'race', 'sex', 'manner_of_death', 'age_group',
  'type_of_custody', 'death_location_type', 'means_of_death',
];

// Fetch CDR data from server and trigger the construction
// of the charts, filter panel, etc.
TJIChartView.prototype.get_data = function() {
  var that = this;
  var url = '/cdr_compressed.json';
  jQuery.getJSON(url)
    .done(function(data){
      jQuery(that.charts_elt_id).empty();
      that.state.data = data;
      that.decompress_data();
      that.transform_data();
      that.create_charts();
      that.create_filter_panel();
      that.attach_events();
    })
    .fail(function(jqxhr, textStatus, error){
      console.log('error fetching data from: ' + url, error);
    })
}

/* The data is compressed into a small json object for fast page loading,
 * which we decompress here for convenient manipulation in this app.
 * 
 * Currently, the data is compressed by this script in our data-processing repo:
 * https://github.com/texas-justice-initiative/data-processing/blob/master/data_cleaning/create_compressed_cdr_for_website.ipynb 
 * 
 * See that file for an explanation of the compression and examples.
 */
TJIChartView.prototype.decompress_data = function() {
  var that = this;
  var meta = this.state.data.meta;
  var records = this.state.data.records;
  // We want a list of json objects, one per record. We will build these
  // out incrementally.
  var new_data = [];
  _.times(this.state.data['meta']['num_records'], function(){ new_data.push({}); });
  _.each(records, function(values, column) {
      var lookup  = meta.lookups[column] || {};
      _.each(values, function(v, idx) {
          new_data[idx][column] = lookup[v];
      });
  });
  this.state.data = new_data;
}

// Apply any data transformations necessary before beginning to build
// out the rest of the view.
TJIChartView.prototype.transform_data = function() {

  var that = this;

  _.each(this.state.data, function(data_row, i) {
    // Create age group buckets
    if (data_row['age_at_time_of_death'] < 0 ||
        data_row['age_at_time_of_death'] === undefined ||
        data_row['age_at_time_of_death'] === null) {
      data_row['age_group'] = null;
    } else {
      age_decade = Math.floor(data_row['age_at_time_of_death'] / 10) * 10
      if (age_decade > 59) {
        data_row['age_group'] = '60+'
      } else {
        data_row['age_group'] = age_decade + '-' + (age_decade + 9)
      }
    }

    _.each(data_row, function(value, key) {
      // Replace missing values with a special label value
      if (value === undefined || value === null || value === '') {
        data_row[key] = that.missing_data_label;
      }
      // Convert everything to string, so the filters can match correctly.
      data_row[key] = '' + data_row[key];
    });
  });
}

TJIChartView.prototype.create_filter_panel = function() {

  // Generate filter values

  this.filters = []
  var that = this

  // Get a sorted list of all the unique values for each column
  _.each(this.filter_columns, function(column) {
    values = _.filter(_.sortBy(_.uniq(_.map(that.state.data, column))), _.identity);
    if (values.indexOf(that.missing_data_label) !== -1) {
      values.splice(values.indexOf(that.missing_data_label), 1);
      values.push(that.missing_data_label);
    }
    that.filters.push({
      key: column,
      values: values
    });
  });

  // Build out checkboxes n stuff

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

TJIChartView.prototype.create_charts = function() {
  var that = this;
  this.update_record_count(this.state.data);
  _.each(this.chart_configs, function(config, i){
    var id = 'tji-chart-wrapper-' + i;
    $wrapper = jQuery(that.chart_wrapper).attr("id", id).appendTo(that.charts_elt_id);
    
    var chart_constructor;
    switch(config.type) {
      case 'doughnut': 
        chart_constructor = TJIGroupByDoughnutChart;
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

TJIChartView.prototype.attach_events = function() {
  var that = this;
  jQuery('#js-TJIfilters').on('change', function(e) {
    that.state.active_filters = jQuery(this).serializeArray();
    that.filter_data();
  })
}

// Called when the user changes any data filters.
TJIChartView.prototype.filter_data = function() {
  // Create a mapping from filter name to active values.
  // E.g. "race" to ["WHITE", "HISPANIC"] if those are
  // the only boxes checked.
  var grouped_filters = {}
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

TJIChartView.prototype.update_charts = function(data) {
  this.update_record_count(data);
  _.each(this.state.charts, function(chart){
    chart.update(data);
  })
}

TJIChartView.prototype.update_record_count = function(data) {
  if (this.state.$record_count) jQuery(this.state.$record_count).remove();
  this.state.$record_count = jQuery(this.record_count_template.replace('{count}', data.length)).prependTo(this.charts_elt_id);
}