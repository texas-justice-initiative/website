<?php
	
	/* 
		TJI Visualization charts 
	*/
	
//$vizChart = get_post_meta( $post->ID, 'tji_viz_chart', true );

//echo sprintf('<img src="%s" alt="Section Visualization">',$vizChart);

$chart1_url = get_template_directory_uri() . "/img/charts/cdr_jail_deaths_by_means_and_cell_type.png";
?>

<div class="tji-chart-full">
	<a href="<?php echo $chart1_url; ?>" target="_blank"><img src="<?php echo $chart1_url; ?>"></a>
	<span class="chart-expand"><a href="<?php echo $chart1_url; ?>" target="_blank">Click to expand <i class="fas fa-search"></i></a></span>
</div>