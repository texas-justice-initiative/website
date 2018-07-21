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

  <div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
      // Bring in data from WordPress page
      while ( have_posts() ) :
       the_post();
       get_template_part( 'template-parts/content', 'page' );
      endwhile;
    ?>

<div id="js-TJIChartView" class="row tji-chartview">
  <!-- Loader will be cleared out when the data fetch completes -->
  <div class="tji-chartview__loader"></div>
</div>

</main></div>

<aside id="secondary">
  <div id="js-TJIChartViewFilters"></div>
</aside>

<!-- JS Dependencies to build charts -->
<!-- Any dependencies added here should be added to the Dependencies comment block of the appropriate JS file -->
<script src="/wp-content/themes/tji/js/papaparse.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script src="https://cdn.rawgit.com/emn178/Chart.PieceLabel.js/master/build/Chart.PieceLabel.min.js"></script>
<script src="/wp-content/themes/tji/js/auto-complete.min.js"></script>
<script>
  // See js/tji-datasets-explore.js
  jQuery(function(){
    var chartView = new TJIChartView({
      compressed_data_json_url: '/cdr_compressed.json',
      complete_data_csv_url: '/cdr_full.csv',
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
      charts_elt_selector: '#js-TJIChartView',  
      filters_elt_selector: '#js-TJIChartViewFilters',  
      chart_wrapper_template: '<div class="col-sm-12 col-lg-6" />',  
      chartview_description_template: '<div class="col-sm-12" />',
    });
  })
</script>

<?php
  
get_footer();
