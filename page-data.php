<?php
/**
 * The template for displaying explore the data pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Texas_Justice_Initiative
 */

get_header();

?>
<div id="js-TJIChartView" class="tji-chartview-wrapper">
  <div class="tji-chartview-content">
    <main id="main" class="site-main">

      <?php
        // Bring in data from WordPress page
        while ( have_posts() ) :
         the_post();
         get_template_part( 'template-parts/content', 'page' );
        endwhile;
      ?>

    <div id="js-TJIChartViewCharts" class="tji-chartview">
    </div>

    </main>
  </div>

  <aside class="tji-chartview-controls">
    <div id="js-chartview-controls-toggle" class="tji-chartview-controls__toggle"><span>&larr;</span><h4>Chart Filters</h4></div>
    <div id="js-TJIChartViewFilters"></div>
  </aside>

  <?php 
    $signupdonate_id = 'js-TJIChartViewModal';
    require get_template_directory() . '/inc/template/form-signupdonate.php' 
  ?>
</div>
<!-- JS Dependencies to build charts -->
<!-- Any dependencies added here should be added to the Dependencies comment block of the appropriate JS file -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/papaparse.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/chartjs-plugin-labels.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/auto-complete.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/tji-formmodal-whoami-signup-donate.js"></script>

<script>
  // See js/tji-datasets-explore.js
  jQuery(function(){
    var chartView = new TJIChartView({
      datasets: [{
        name: 'deaths in custody',
        description: "All deaths in custody in Texas since 2005, as reported to the Office of the Attorney General.",
        urls: {
          compressed: '<?php echo get_template_directory_uri(); ?>/data/cdr_compressed.json',
          full: '<?php echo get_template_directory_uri(); ?>/data/cdr_full.csv',
        },
        chart_configs: [
          {type: 'bar', group_by: 'year'},
          {type: 'doughnut', group_by: 'race'},
          {type: 'doughnut', group_by: 'sex'},
          {type: 'doughnut', group_by: 'manner_of_death'},
          {type: 'doughnut', group_by: 'age_group', sort_by: {column: 'key', direction: 'asc'}},
          {type: 'doughnut', group_by: 'type_of_custody'},
          {type: 'doughnut', group_by: 'death_location_type'},
          {type: 'doughnut', group_by: 'means_of_death'},
        ],
        filter_configs: [
          {'name': 'year'},
          {'name': 'race'},
          {'name': 'sex'},
          {'name': 'manner_of_death'},
          {'name': 'age_group'},
          {'name': 'type_of_custody'},
          {'name': 'death_location_type'},
          {'name': 'means_of_death'},
          {'name': 'agency_name', 'type': 'autocomplete'},
          {'name': 'death_location_county', 'type': 'autocomplete'},
        ],
      }, {
        name: 'civilians shot by officers',
        description: "Shootings involving Texas law enforcement since Sept. 2015, as reported to the Office of the Attorney General.",
        urls: {
          compressed: '<?php echo get_template_directory_uri(); ?>/data/ois_compressed.json',
          full: '<?php echo get_template_directory_uri(); ?>/data/ois_full.csv',
        },
        chart_configs: [
          {type: 'bar', group_by: 'year'},
          {type: 'doughnut', group_by: 'civilian_race'},
          {type: 'doughnut', group_by: 'civilian_gender'},
          {type: 'doughnut', group_by: 'civilian_died'},
          {type: 'doughnut', group_by: 'deadly_weapon'},
        ],
        filter_configs: [
          {'name': 'year'},
          {'name': 'civilian_race'},
          {'name': 'civilian_gender'},
          {'name': 'civilian_died'},
          {'name': 'deadly_weapon'},
          {'name': 'agency_name', 'type': 'autocomplete'},
          {'name': 'incident_county', 'type': 'autocomplete'},
        ],
      }, {
        name: 'OIS - officers shot by civilians',
        description: "Shootings that injured or killed Texas law enforcement officers since Sept. 2015, as reported to the Office of the Attorney General.",
        urls: {
          compressed: '<?php echo get_template_directory_uri(); ?>/data/ois_officers_compressed.json',
          full: '<?php echo get_template_directory_uri(); ?>/data/ois_officers_full.csv',
        },
        chart_configs: [
          {type: 'bar', group_by: 'year'},
          {type: 'doughnut', group_by: 'officer_race'},
          {type: 'doughnut', group_by: 'officer_gender'},
          {type: 'doughnut', group_by: 'officer_harm'},
          {type: 'doughnut', group_by: 'civilian_harm'},
        ],
        filter_configs: [
          {'name': 'year'},
          {'name': 'officer_race'},
          {'name': 'officer_harm'},
          {'name': 'civilian_harm'},
          {'name': 'agency_name', 'type': 'autocomplete'},
          {'name': 'incident_county', 'type': 'autocomplete'},
        ],
      }],
      view_elt_selector: '#js-TJIChartView',
      charts_elt_selector: '#js-TJIChartViewCharts',  
      filters_elt_selector: '#js-TJIChartViewFilters',  
      modal_elt_selector: '#js-TJIChartViewModal',
      chart_wrapper_template: '<div class="tji-chart col-xs-12 col-md-6 col-lg-4" />',  
      chartview_charts_template: '<div class="row"/>',
      chartview_summary_template: '<div />',
    });
  })
</script>
<!-- JS Dependencies for newsletter signup and donate functionality -->
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<?php
  
get_footer();
