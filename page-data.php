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

<div id="primary" class="content-area tji-chartview-content">
  <main id="main" class="site-main">

    <?php
      // Bring in data from WordPress page
      while ( have_posts() ) :
       the_post();
       get_template_part( 'template-parts/content', 'page' );
      endwhile;
    ?>

  <div id="js-TJIChartView" class="tji-chartview">
  </div>

  </main>
</div>

<aside id="secondary" class="tji-chartview-controls">
  <div id="js-chartview-controls-toggle" class="tji-chartview-controls__toggle"><span>←</span></div>
  <div id="js-TJIChartViewFilters"></div>
</aside>

<!-- JS Dependencies to build charts -->
<!-- Any dependencies added here should be added to the Dependencies comment block of the appropriate JS file -->
<script src="<?php echo get_template_directory_uri(); ?>/js/papaparse.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdn.rawgit.com/emn178/Chart.PieceLabel.js/master/build/Chart.PieceLabel.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/auto-complete.min.js"></script>
<script>
  // See js/tji-datasets-explore.js
  jQuery(function(){
    var chartView = new TJIChartView({
      datasets: [{
        name: 'deaths in custody',
        description: "All deaths in custody in Texas since 2005, as reported to the Office of the Attorney General.",
        urls: {
          compressed: '/cdr_compressed.json',
          full: '/cdr_full.csv',
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
          {'name': 'agency_county', 'type': 'autocomplete'},
          {'name': 'death_location_county', 'type': 'autocomplete'},
        ],
      }, {
        name: 'officer involved shootings',
        description: "Shootings involving Texas law enforcement since Sept. 2015, as reported to the Office of the Attorney General.",
        urls: {
          compressed: '/ois_compressed.json',
          full: '/ois_full.csv',
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
          {'name': 'agency_county', 'type': 'autocomplete'},
          {'name': 'incident_county', 'type': 'autocomplete'},
        ],
      }],
      charts_elt_selector: '#js-TJIChartView',  
      filters_elt_selector: '#js-TJIChartViewFilters',  
      chart_wrapper_template: '<div class="tji-chart col-xs-12 col-md-6 col-lg-4" />',  
      chartview_charts_template: '<div class="row"/>',
      chartview_summary_template: '<div />',
    });
  })
</script>

<?php
  
get_footer();
