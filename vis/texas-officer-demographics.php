<?php
	
	/* 
		TJI Visualization charts 
	*/
	
//$vizChart = get_post_meta( $post->ID, 'tji_viz_chart', true );

//echo sprintf('<img src="%s" alt="Section Visualization">',$vizChart);

$chart1_url = get_template_directory_uri() . "/img/charts/ois_which_officers_age_donut.png";
$chart2_url = get_template_directory_uri() . "/img/charts/ois_which_officers_race_donut.png";
$chart3_url = get_template_directory_uri() . "/img/charts/ois_which_officers_gender_donut.png";
?>

<div class="tji-chart-full">
	<a href="<?php echo $chart1_url; ?>" target="_blank"><img src="<?php echo $chart1_url; ?>"></a>
	<span class="chart-expand"><a href="<?php echo $chart1_url; ?>" target="_blank">Click to expand <i class="fas fa-search"></i></a></span></div>
<div class="tji-chart-full">
	<a href="<?php echo $chart2_url; ?>" target="_blank"><img src="<?php echo $chart2_url; ?>"></a>
	<span class="chart-expand"><a href="<?php echo $chart3_url; ?>" target="_blank">Click to expand <i class="fas fa-search"></i></a></span>
</div>
<div class="tji-chart-full">
	<a href="<?php echo $chart3_url; ?>" target="_blank"><img src="<?php echo $chart3_url; ?>"></a>
	<span class="chart-expand"><a href="<?php echo $chart3_url; ?>" target="_blank">Click to expand <i class="fas fa-search"></i></a></span>
</div>