
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
// *   above_charts_template: HTML to wrap the area above the rendered charts,
// *       where we'll put the record count and download button.
// *   chart_wrapper_template: HTML to wrap around each chart's canvas object
// *   record_count_template: HTML for the "showing this many records" element
// *       at the top, containing a "{count}" placeholder somewhere
// *       (which TJIChartView will replace with the record count).
// *
// * Dependencies: TJIGroupByBarChart, TJIGroupByDoughnutChart
// *       jQuery, lodash, auto-complete.min.js - https://github.com/Pixabay/JavaScript-autoComplete
// ********************************************************************


var TJIChartView = function(props){
  this.state = {
    data: null,
    record_id_field: null,
    filtered_data: null,
    complete_data: null,
    active_filters: [],
    charts: [],
    $above_charts_area: null,
    $download: null,
    $record_count: null
  }

  this.chart_configs = props.chart_configs;
  this.filter_configs = props.filter_configs;
  this.charts_elt_selector = props.charts_elt_selector;
  this.filters_elt_selector = props.filters_elt_selector;
  this.chart_wrapper_template = props.chart_wrapper_template;
  this.above_charts_template = props.above_charts_template;
  this.record_count_template = props.record_count_template;
  this.$form = null;
  this.autocompletes = [];

  this.get_data(props.compressed_data_json_url, props.complete_data_csv_url);
}

TJIChartView.prototype.missing_data_label = '(not given)';

// Fetch CDR data from server and trigger the construction
// of the charts, filter panel, etc.
TJIChartView.prototype.get_data = function(compressed_data_json_url, complete_data_csv_url) {
  var that = this;
  jQuery.getJSON(compressed_data_json_url)
    .done(function(data){
      jQuery(that.charts_elt_selector).empty();
      that.state.data = data;
      that.decompress_data();
      that.transform_data();
      that.state.filtered_data = that.state.data;

      // Prepare HTML contents
      jQuery(that.charts_elt_id).empty();
      that.create_above_charts_area();
      that.create_charts();
      that.create_filter_panel();
      that.attach_events();
      that.get_complete_data(complete_data_csv_url);
    })
    .fail(function(jqxhr, textStatus, error){
      console.log('Error fetching data from: ' + compressed_data_json_url, error);
    })
}


TJIChartView.prototype.get_complete_data = function(url) {
  var that = this;
  Papa.parse(url, {
    download: true,
    header: true,
    skipEmptyLines: true,
    complete: function(results) {
      that.state.complete_data = results.data;
      // The download button stays disabled and faded until this data is ready.
      // Let's enable and fade it back into view now.
      that.state.$download.prop("disabled", false);
      that.state.$download.animate({
        opacity: 1.0,
      }, 1000);
    },
    error: function(error) {
      console.log('Error fetching data from: ' + url, error);
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
TJIChartView.prototype.decompress_data = function() {
  var that = this;
  var data = this.state.data;
  var meta = data.meta;
  var records = data.records;
  // We want a list of json objects, one per record. We will build these
  // out incrementally.
  var new_data = [];
  _.times(data['meta']['num_records'], function(){ new_data.push({}); });
  // Unique IDs for each record are stored in the 'meta' area
  this.state.record_id_field = data['meta']['record_ids']['field_name'];
  _.each(data['meta']['record_ids']['values'], function(value, idx) {
    new_data[idx][that.state.record_id_field] = value;
  });
  // Build up records, column by column.
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


TJIChartView.prototype.create_above_charts_area = function() {
  this.state.$above_charts_area = jQuery(this.above_charts_template).prependTo(this.charts_elt_selector)
  this.update_record_count();
  this.state.$download = jQuery(' <button class="download-button" disabled> <i class="fas fa-download"></i> Download</button>');
  this.state.$download.appendTo(this.state.$above_charts_area)
}


TJIChartView.prototype.create_filter_panel = function() {
  var that = this;
  
  // Generate filter values
  var filters = []
  
  // Get a sorted list of all the unique values for each column
  _.each(this.filter_configs, function(filter_config) {
    var values = _.filter(_.sortBy(_.uniq(_.map(that.state.data, filter_config.name))), _.identity);
    // Move the special "missing data" label to the last position
    if (~values.indexOf(that.missing_data_label)) {
      values.splice(values.indexOf(that.missing_data_label), 1);
      values.push(that.missing_data_label);
    }
    filter_config.values = values;
  });

  // Build out filters
  var fieldsets = [];
  _.each(that.filter_configs, function(filter_config) {
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
  
  this.$form = jQuery('<form />', {
    class: 'tji-chart-filters',
  }).append(fieldsets);
  this.state.active_filters = this.$form.serializeArray();  
  jQuery(this.filters_elt_selector).append(this.$form);
  
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
  fieldset.append(jQuery('<div/>',{
    class: "tji-chart-filters__autocomplete",
  }).append(input));
  
  var $auto_complete = jQuery('<div />', {
    class: "tji-chart-filters__autocomplete-list"
  }).insertAfter(input);
  
  var onSelect = function(event, term) {
    event.preventDefault();
    event.stopPropagation();
    var id = 'TJIChartView__filtercontainer-' + filter.name + '-' + term;
    $auto_complete.find('#'+id).remove();
    $auto_complete.prepend(that.create_filter_checkbox(filter.name, term, id));
    input.val('');
    that.$form.trigger('change');
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

  this.autocompletes.push({
    widget: auto_complete,
    jquery: $auto_complete,
  });
  return fieldset;
}

TJIChartView.prototype.create_charts = function() {
  var that = this;

  this.update_record_count(this.state.data);
  _.each(this.chart_configs, function(config){
    var $container = jQuery(that.chart_wrapper_template).appendTo(that.charts_elt_selector);
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
    that.state.charts.push(
      new chart_constructor({
        $container: $container, 
        group_by: config.group_by, 
        sort_by: config.sort_by,
        missing_data_label: that.missing_data_label,
        data: that.state.data,
      })
    );    
  });
}


// $container: jQuery object to contain chart
// *   groupBy: column to group by
// *   missing_data_label: stand-in label for records missing
// *                       the groupBy column.
// *   data: list of objects representing records


TJIChartView.prototype.attach_events = function() {
  var that = this;
  this.$form.on('change', function(e) {
    that.state.active_filters = that.$form.serializeArray();
    that.filter_data();
    that.update_charts();
    that.update_record_count();
  }).on('submit', function(e){
    e.preventDefault();
  })
  this.state.$download.on('click', function() {
    that.download();
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

  //if value doesn't match any selected filters reject it
  var data = _.reject(this.state.data, function(val){
    for (filter in grouped_filters) {
      if (!~grouped_filters[filter].indexOf(val[filter])) return true;
    }
    return false;
  })

  this.state.filtered_data = data;
}

TJIChartView.prototype.update_charts = function() {
  var that = this;
  _.each(this.state.charts, function(chart){
    chart.update(that.state.filtered_data);
  })
}

TJIChartView.prototype.update_record_count = function() {
  if (this.state.$record_count) jQuery(this.state.$record_count).remove();
  this.state.$record_count = jQuery(this.record_count_template.replace('{count}', this.state.filtered_data.length)).prependTo(this.state.$above_charts_area);
}

TJIChartView.prototype.download = function() {
  // Download all the data the user is currently viewing (that is,
  // with their current filters applied).

  // Note: This gets tricky because we want to download
  // not only the fields available in the charts, but ALL
  // fields (columns) in the complete dataset.

  // Make note of the ids of our current (filtered) records.
  var ids_to_keep = {}
  var that = this;
  _.each(this.state.filtered_data, function(record) {
    ids_to_keep[record[that.state.record_id_field]] = true;
  });

  // Pull out the matching complete-dataset records.
  var filtered_complete_records = _.filter(this.state.complete_data, function(record) {
    return ids_to_keep[record[that.state.record_id_field]] !== undefined;
  });
  
  // Convert these JSON records into CSV text using Papa
  var csvData = Papa.unparse(filtered_complete_records);
  var filename = filtered_complete_records.length == this.state.complete_data.length ? "tji_data.csv" : "tji_data_filtered.csv";

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