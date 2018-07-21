
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
// * Constructor arguments as properties of props object:
// *   $container: jQuery object to contain chart
// *   group_by: column to group by
// *   sort_by: object to determine the order that data is displayed
// *           ex. {column: 'key', direction: 'asc'}
// *           The default is to order by keys alphabetically
// *   missing_data_label: stand-in label for records missing
// *                       the group_by column.
// *   data: list of objects representing records
// *
// * Dependencies: chart.js, Chart.PieceLabel, jQuery, lodash
// *******************************************************************


var TJIGroupByBarChart = function(props) {
  this.$container = props.$container;
  this.group_by = props.group_by;
  this.sort_by = props.sort_by || {column: 'key', direction: 'asc'};
  this.missing_data_label = props.missing_data_label || '(not given)';
  this.colors = null;
  this.chart = null;
  this.ordered_keys = null;
  this.create(props.data);
}

TJIGroupByBarChart.prototype.type = 'bar';

TJIGroupByBarChart.prototype.color_palette = [COLOR_TJI_BLUE];

// Create the chart for the first time.
// This also permanently sets the legend and color mapping.
TJIGroupByBarChart.prototype.create = function(data) {
  var that = this;

  var grouped = this.get_sorted_group_counts(data);

  // Persist key order on initial render with full data set
  // so legend always includes all values and the colors stay consistent
  // after filters have been selected
  this.ordered_keys = grouped.keys;
  this.colors = _.map(grouped.keys, function(k, i) {
    return that.color_palette[i % that.color_palette.length];
  })
  // Persist max grouped count to set y axis max value
  this.count_max = _.max(grouped.counts)

  var $canvas = jQuery('<canvas class="tji-chart__canvas" height="1" width="1"/>');
  // Build our (custom) legend.
  var $legend = this.create_legend();
  var $title = jQuery('<div class="tji-chart__title">' + this.group_by.replace(/_/g, " ") + '</div>');

  this.$container.append([$title, $canvas, $legend]);

  // Build the chart
  this.chart = new Chart($canvas, {
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
}

TJIGroupByBarChart.prototype.destroy = function() {
  this.chart.destroy();
  this.$container.remove();
}

TJIGroupByBarChart.prototype.create_legend = function() {
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
          suggestedMax: this.count_max,
          min: 0,
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

// Group the data by the group_by class property
// Determine record counts for each grouping value
// Order the groupings by the sort_by class property
// the sorting will impact the colors assigned to each group
// Returns an object with two lists:
//   {
//     keys: [list of sorted, unique group_by values],
//     counts: [list of number of records for each group_by value]
//   }
// keys and counts have corresponding indexes
TJIGroupByBarChart.prototype.get_sorted_group_counts = function(data) {
  data = _.filter(data, this.group_by);
  var collection = [];
  var grouped = _
    .chain(data)
    .groupBy(this.group_by)
    .forIn(function(v,k){
      collection.push({
        key: k,
        count: v.length,
      });
    })
    .value();

  if (!this.ordered_keys) {
    collection = _.orderBy(collection, [this.sort_by.column], [this.sort_by.direction]);
  }

  var keys = this.ordered_keys || _.map(collection, 'key');
  return {
    keys: keys,
    counts: _.map(keys, function(k){ return (grouped[k]||[]).length; }),
  }; 
}


// ********************************************************************
// * Extends TJIGroupByBarChart... but for doughnuts.
// *   sort_by: object to determine the order that data is displayed
// *           ex. {column: 'key', direction: 'asc'}
// *           For doughnuts, this impacts the data's color mapping
// *           The default is to order by largest percentage to smallest
// ********************************************************************


var TJIGroupByDoughnutChart = function(props) {
  props.sort_by = props.sort_by || {column: 'count', direction: 'desc'};
  TJIGroupByBarChart.call(this, props);
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

TJIGroupByDoughnutChart.prototype.create_legend = function() {
  var that = this;
  var colormap = {};
  _.map(this.ordered_keys, function(key, i) { colormap[key] = that.colors[i] });
  var keys_sorted = _.sortBy(this.ordered_keys);

  // Move the special "missing data" label to the last position
  var missing_data_index = keys_sorted.indexOf(this.missing_data_label);
  if(~missing_data_index){
    keys_sorted.splice(missing_data_index, 1);
    keys_sorted.push(this.missing_data_label);
  }  

  var $legend = jQuery('<div class="tji-chart__legend"/>');
  var legend_items = [];
  _.map(keys_sorted, function(key){
    legend_items.push(jQuery('<div class="tji-chart__legend-item"><span style="background-color:' + colormap[key] + '"></span>' + key + '</div>'));
  });
  return $legend.append(legend_items);
}


// ********************************************************************
// * "Class" that creates and manages all charts and the filter panel.
// *
// * Constructor arguments as properties of props object:
// *   compressed_data_json_url: URL location of the abridged,
// *       compressed dataset. This dataset has only the subset
// *       of columns that we use in drawing charts.
// *       See the comments in this notebook for the expected format:
// *       https://github.com/texas-justice-initiative/data-processing/blob/master/data_cleaning/create_datasets_for_website.ipynb
// *   chart_configs: array of chart config objects,
// *                  e.g. [{type: 'bar', group_by:'year'}, ...]
// *   filter_configs: array of chart filters config objects,
// *                  e.g. [{'name': 'agency_county', 'type': 'autocomplete'}, ...]
// *   charts_elt_selector: selector of HTML element to put charts in
// *   filters_elt_selector: selector of HTML element to put filters in
// *   chartview_summary_template: HTML to wrap the area where the data set summary shows
// *       where we'll put the record count, download button, data set selector and data set description
// *   chart_wrapper_template: HTML to wrap around each chart's canvas object
// *
// * Dependencies: TJIGroupByBarChart, TJIGroupByDoughnutChart
// *       jQuery, lodash, papaparse,
// *       auto-complete.min.js - https://github.com/Pixabay/JavaScript-autoComplete
// ********************************************************************


var TJIChartView = function(props){

  var that = this;

  this.state = {
    active_dataset_index: null,
    filtered_record_indices: null,
    active_filters: [],
  }

  this.datasets = props.datasets;

  this.ui = {
    $chartview_charts: jQuery(props.charts_elt_selector),
    $charts: null,
    $chartview_filters: jQuery(props.filters_elt_selector),
    $form: null,
    $summary: null,
    $download: null,
    $record_count: null,
    $loader: null,
  }

  this.templates = {
    chart_wrapper_template: props.chart_wrapper_template,
    chartview_charts_template: props.chartview_charts_template,
    chartview_summary_template: props.chartview_summary_template,
  }

  this.components = {
    charts: [],
    autocompletes: [],
  }

  this.create_chartview_DOM();

  this.set_active_dataset(0);

  this.datasets[this.state.active_dataset_index].fetch_data
    .done(function(){
      that.ui.$summary.prependTo(that.ui.$chartview_charts);
      that.attach_events();
    });
}

TJIChartView.prototype.missing_data_label = '(not given)';

TJIChartView.prototype.create_chartview_DOM = function() {

  this.ui.$loader = jQuery('<div />', {
    class: 'tji-chartview__loader'
  }).appendTo(this.ui.$chartview_charts);

  this.ui.$form = jQuery('<form />', {
    class: 'tji-chart-filters',
  }).appendTo(this.ui.$chartview_filters);

  this.ui.$charts = jQuery(this.templates.chartview_charts_template)
    .addClass('tji-chartview__charts')
    .appendTo(this.ui.$chartview_charts);

  this.ui.$record_count = jQuery('<span />', {
    class: 'tji-chartview__record-count'
  });
  
  this.ui.$download = jQuery('<button class="tji-btn-primary tji-chartview__download-button" disabled> <i class="fas fa-download"></i> Download</button>');
  
  this.ui.$summary = jQuery(this.templates.chartview_summary_template)
    .addClass('tji-chartview__summary')
    .append(this.ui.$record_count, this.ui.$download)
}

TJIChartView.prototype.attach_events = function() {
  var that = this;
  this.ui.$form.on('change', function(e) {
    that.state.active_filters = that.ui.$form.serializeArray();
    that.filter_data();
    that.update_charts();
  }).on('submit', function(e){
    e.preventDefault();
  })
  this.ui.$download.on('click', function() {
    that.download();
  });
}

TJIChartView.prototype.set_active_dataset = function(index) {
  var that = this;
  this.state.active_dataset_index = index;
  
  this.ui.$loader.show();

  if(!this.datasets[index].fetch_data) {
    this.get_data(this.datasets[index]);
  }
  this.datasets[index].fetch_data
    .done(function(){
      // When a data set is selected, include all data in initial rendering.
      that.state.filtered_record_indices = _.times(that.datasets[index].chart_data.length);
      that.state.active_filters = [];
      that.ui.$loader.hide(); 
      that.create_filters();
      that.create_charts();
      that.update_chartview_summary();
    });
}

// Returns a promise such that synchronis functionality can be 
// triggered once compressed data download is completed and transformed
// also triggers fetch of full csv data set 
TJIChartView.prototype.get_data = function(dataset) {
  var that = this;
  dataset.fetch_data = jQuery.getJSON(dataset.urls.compressed)
    .done(function(chart_data) {
      dataset.chart_data = chart_data;
      that.decompress_data(dataset);
      that.transform_data(dataset);
      that.get_complete_data(dataset);
    })
    .fail(function(jqxhr, textStatus, error){
      console.log('Error fetching data from: ' + compressed_data_json_url, error);
    })
}

TJIChartView.prototype.get_complete_data = function(dataset) {
  var that = this;
  Papa.parse(dataset.urls.full, {
    download: true,
    header: true,
    skipEmptyLines: true,
    complete: function(results) {
      dataset.complete_data = results.data;
      if (dataset.complete_data.length !== dataset.chart_data.length) {
        console.log(
          "Error: complete dataset does not have the same number of records "
          + "as the abdridged dataset. Disabling download."
        );
      } else {
        // The download button stays disabled and until this data is ready.
        // Let's bring her back online now.
        that.ui.$download.prop("disabled", false);
      }
    },
    error: function(error) {
      console.log('Error fetching data from: ' + dataset.urls.full, error);
    }
  });
}

/* The data is compressed into a small json object for fast page loading,
 * which we decompress here for convenient manipulation in this app.
 * 
 * Currently, the data is compressed by this script in our data-processing repo:
 * https://github.com/texas-justice-initiative/data-processing/blob/master/data_cleaning/create_datasets_for_website.ipynb
 * 
 * See that file for an explanation of the compression and examples.
 */
TJIChartView.prototype.decompress_data = function(dataset) {
  var that = this;
  var data = dataset.chart_data;
  var meta = data.meta;
  // We want a list of json objects, one per record. We will build these
  // out incrementally.
  var new_data = [];
  _.times(meta.num_records, function(){ new_data.push({}); });
  // Build up records, column by column.
  _.each(data.records, function(values, column) {
      var lookup  = meta.lookups[column] || {};
      _.each(values, function(v, idx) {
          new_data[idx][column] = lookup[v];
      });
  });
  dataset.chart_data = new_data;
}

// Apply any data transformations necessary before beginning to build
// out the rest of the view.
TJIChartView.prototype.transform_data = function(dataset) {

  var that = this;

  _.each(dataset.chart_data, function(record, id) {
    // Create age group buckets 
    //<-- NOTE FOR EVERETT - HOW DO YOU WANT TO HANDLE DATA SETS WE DON'T WANT AGE GROUPS? 
    //OR WHERE THE AGE GROUP COLUMN NAME IS DIFFERENT
    if (record['age_at_time_of_death'] < 0 ||
        record['age_at_time_of_death'] === undefined ||
        record['age_at_time_of_death'] === null) {
      record['age_group'] = null;
    } else {
      age_decade = Math.floor(record['age_at_time_of_death'] / 10) * 10
      if (age_decade > 59) {
        record['age_group'] = '60+'
      } else {
        record['age_group'] = age_decade + '-' + (age_decade + 9)
      }
    }

    _.each(record, function(value, key) {
      // Replace missing values with a special label value
      if (value === undefined || value === null || value === '') {
        record[key] = that.missing_data_label;
      }
      // Convert everything to string, so the filters can match correctly.
      record[key] = '' + record[key];
    });
  });
}

TJIChartView.prototype.destroy_filters = function() {
  _.each(this.components.autocompletes, function(autocomplete){
    autocomplete.destroy();
  });
  this.components.autocompletes.length = 0;
  this.ui.$form.empty();
}

TJIChartView.prototype.create_filters = function() {
  var that = this;
  
  this.destroy_filters();
  
  // Generate filter values
  var dataset = this.datasets[this.state.active_dataset_index];
  var filters = []
  
  // Get a sorted list of all the unique values for each column
  _.each(dataset.filter_configs, function(filter_config) {
    var values = _.filter(_.sortBy(_.uniq(_.map(dataset.chart_data, filter_config.name))));
    if (~values.indexOf(that.missing_data_label)) {
      values.splice(values.indexOf(that.missing_data_label), 1);
      values.push(that.missing_data_label);
    }
    filter_config.values = values;
  });

  // Build out filters
  var fieldsets = [];
  _.each(dataset.filter_configs, function(filter_config) {
    var fieldset;
    switch(filter_config.type) {
      case 'autocomplete': 
        fieldset = that.create_filter_autocomplete(filter_config);
        break;
      case 'checkbox':
      default:
        fieldset = that.create_filter_checkboxes(filter_config);
        break;
    }
    fieldsets.push(fieldset);
  });
  this.ui.$form.append(fieldsets);

  this.state.active_filters = this.ui.$form.serializeArray();    
}

TJIChartView.prototype.create_filter_checkboxes = function(filter) {
  var that = this;
  var fieldset = jQuery('<fieldset><legend>' + filter.name.replace(/_/g, " ") + '<i class="fas fa-caret-down"></i></legend></fieldset>');
  _.each(filter.values, function(v) {
    fieldset.append(that.create_filter_checkbox(filter.name, v));
  });
  return fieldset;
}

TJIChartView.prototype.create_filter_checkbox = function(name, value, id) {
  var input = jQuery('<input/>', {
      class: "tji-chart-filters__checkbox",
      type: "checkbox",
      checked: "checked",
      id: 'TJIChartView__filter-' + name + '-' + value,
      name: name,
      value: value,
    });
  var label = jQuery('<label/>', {
    for: 'TJIChartView__filter-' + name + '-' + value,
  }).text(value);
  var container = jQuery('<div/>', {
      id: id,
      class: "tji-chart-filters__filter",
  }).append(input, label);
  return container;
}

TJIChartView.prototype.create_filter_autocomplete = function(filter) {
  var that = this;
  var fieldset = jQuery('<fieldset><legend>' + filter.name.replace(/_/g, " ") + '<i class="fas fa-caret-down"></i></legend></fieldset>');
  var input = jQuery('<input/>', {
    class: "tji-chart-filters__text",
    type: "text",
  });
  var auto_complete_list = jQuery('<div />', {
    class: "tji-chart-filters__autocomplete-list"
  });

  jQuery('<div/>',{
    class: "tji-chart-filters__autocomplete",
  }).append(input, auto_complete_list).appendTo(fieldset);
  
  var onSelect = function(event, term) {
    event.preventDefault();
    event.stopPropagation();
    var id = 'TJIChartView__filtercontainer-' + filter.name + '-' + term;
    auto_complete_list.find('#'+id).remove();
    auto_complete_list.prepend(that.create_filter_checkbox(filter.name, term, id));
    input.val('');
    that.ui.$form.trigger('change');
  }
  
  var auto_complete = new autoComplete({
      selector: input[0],
      minChars: 1,
      delay: 150, 
      source: function(term, suggest){
          term = term.toUpperCase();
          suggest(_.filter(filter.values, function(v){
            return ~v.toUpperCase().indexOf(term);
          }));
      },
      onSelect: function(event, term, item) {
        onSelect(event, term);
      }
  });

  input.on('keypress', function(event){
    // If the user hits ENTER key after typing a valid item, add it to the filter.
    if(event.which !== 13) return;
    var term = input.val().toUpperCase();
    var isMatch = ~filter.values.indexOf(term);
    if(!isMatch) return; //TODO: maybe offer some user-affordance when the value they searched doesn't match
    onSelect(event, term);
    jQuery('.autocomplete-suggestions').hide();
  });

  this.components.autocompletes.push(auto_complete);
  return fieldset;
}

TJIChartView.prototype.destroy_charts = function() {
  _.each(this.components.charts, function(chart){
    chart.destroy();
  });
  this.components.charts.length = 0;
  this.ui.$charts.empty();
}

TJIChartView.prototype.create_charts = function() {
  var that = this;
  
  this.destroy_charts();
  
  var dataset = this.datasets[this.state.active_dataset_index];
  _.each(dataset.chart_configs, function(config){
    var $container = jQuery(that.templates.chart_wrapper_template)
      .addClass('tji-chart')
      .appendTo(that.ui.$charts);
    var chart_constructor;
    switch(config.type) {
      case 'doughnut': 
        chart_constructor = TJIGroupByDoughnutChart;
        break;
      case 'bar':
      default:
        chart_constructor = TJIGroupByBarChart;
        break;
    }
    that.components.charts.push(
      new chart_constructor({
        $container: $container, 
        group_by: config.group_by, 
        sort_by: config.sort_by,
        missing_data_label: that.missing_data_label,
        data: dataset.chart_data,
      })
    );    
  });
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

  var dataset = this.datasets[this.state.active_dataset_index];
  var filtered_indices = [];
  _.each(dataset.chart_data, function(record, idx) {
    for (filter in grouped_filters) {
      if (record[filter] && !~grouped_filters[filter].indexOf(record[filter])) return;
    }
    filtered_indices.push(idx);
  });

  this.state.filtered_record_indices = filtered_indices;
}

TJIChartView.prototype.update_charts = function() {
  this.update_chartview_summary();
  var that = this;
  var dataset = this.datasets[this.state.active_dataset_index];
  var filtered_data = _.map(that.state.filtered_record_indices, function(idx) {
    return dataset.chart_data[idx];
  })
  _.each(this.components.charts, function(chart){
    chart.update(filtered_data);
  })
}

TJIChartView.prototype.update_chartview_summary = function() {
  this.ui.$record_count.text(this.state.filtered_record_indices.length + ' records');
}

TJIChartView.prototype.download = function() {
  // Download complete records for the data the user is currently viewing.
  var that = this;
  var dataset = this.datasets[this.state.active_dataset_index];
  var filtered_complete_records = [];
  _.each(this.state.filtered_record_indices, function(idx) {
    filtered_complete_records.push(dataset.complete_data[idx]);
  });
  
  // Convert these JSON records into CSV text using Papa
  var csvData = Papa.unparse(filtered_complete_records);
  var filename = filtered_complete_records.length == dataset.complete_data.length ? "tji_data.csv" : "tji_data_filtered.csv";

  // Start the download.
  // Note: this solution is taken from https://stackoverflow.com/a/24922761
  // (after trying many different ways to accomplish this)

  var blob = new Blob([csvData], { type: "text/csv;charset=utf-8;" });
  if (navigator.msSaveBlob) {  // IE 10+
      navigator.msSaveBlob(blob, filename);
  } else {
      var link = document.createElement("a");
      if (link.download !== undefined) {  // feature detection
          // Browsers that support HTML5 download attribute
          var url = URL.createObjectURL(blob);
          link.setAttribute("href", url);
          link.setAttribute("download", filename);
          link.style.visibility = "hidden";
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
      }
  }
}