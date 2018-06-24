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


<script>
  // See js/tji-datasets-explore.js
  jQuery(function(){
    var chartView = new ChartView([
      {type: 'bar', group_by:'year'},
      {type: 'doughnut', group_by:'race'},
      {type: 'doughnut', group_by:'sex'},
      {type: 'doughnut', group_by:'manner_of_death'},
      {type: 'doughnut', group_by:'age_group'},
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
