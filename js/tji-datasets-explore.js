
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
  this.groupBy = groupBy;
  this.chart = null;
  this.missing_data_label = missing_data_label || '(not given)';
  this.color_mapping = null;
  this.create(data);
}

TJIGroupByBarChart.prototype.type = 'bar';

TJIGroupByBarChart.prototype.color_palette = [COLOR_TJI_BLUE];

// Create the chart for the first time
TJIGroupByBarChart.prototype.create = function(data) {
  var that = this;
  var grouped = this.get_group_counts(data);

  // 'create' is only run with the full data set.
  // So we store our color mapping (e.g. male = blue, female = red)
  // so that if the user ends up filtering some of them out, we can
  // prevent chartjs from altering the label-color pairings.
  this.color_mapping = {};
  _.each(grouped.keys, function(k, idx) {
    that.color_mapping[k] = that.color_palette[idx % that.color_palette.length];
  });

  // Apply color mapping
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

// Update the chart with a new (filtered) dataset
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

// Return an 'options' object for the ChartJS constructor
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
    legend: {
      display: true,
      position: 'bottom',
      labels: {
        fontSize: 12,
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


// ********************************************************************
// * "Class" that creates and manages all charts and the filter panel.
// *
// * Constructor arguments:
// *   chart_configs: array of chart config objects,
// *                  e.g. [{type: 'bar', group_by:'year'}, ...]
// *   charts_elt_id: id of HTML element to put charts in
// *   filters_elt_id: id of HTML element to put filter checkboxes in
// *   chart_wrapper: HTML to wrap around each chart's canvas object
// *   count_template: HTML for the "showing this man records" element
// *                   at the top, containing a "{count}" placeholder somewhere
// *                   (which ChartView will replace with the record count).
// ********************************************************************


var ChartView = function(chart_configs, charts_elt_id, filters_elt_id, chart_wrapper, count_template){

  this.state = {
    data: null,
    active_filters: [],  // put active filters here
    charts: [],
    $count: null
  }

  this.chart_configs = chart_configs;
  this.charts_elt_id = charts_elt_id;
  this.filters_elt_id = filters_elt_id;
  this.chart_wrapper = chart_wrapper;
  this.count_template = count_template;
  this.filters = null;

  this.get_data();
}

ChartView.prototype.missing_data_label = '(not given)';

// Fetch CDR data from server and trigger the construction
// of the charts, filter panel, etc.
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

// Apply any data transformations necessary before beginning to build
// out the rest of the view.
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
    jQuery(that.chart_wrapper).append('<canvas id="'+id+'" height="1" width="1"/>').appendTo(that.charts_elt_id);
    
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

ChartView.prototype.attach_events = function() {
  var that = this;
  jQuery('#js-TJIfilters').on('change', function(e) {
    that.state.active_filters = jQuery(this).serializeArray();
    that.filter_data();
  })
}

// Called when the user changes any data filters.
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