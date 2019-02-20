<?php
	/* Template Name: Tableau Page */
?>

<!DOCTYPE html>
<html>

<head>
    <title>Basic Embed</title>

    <script type="text/javascript"
	    src="https://public.tableau.com/javascripts/api/tableau-2.min.js"></script>
    <script type="text/javascript">
        function initViz() {
            var containerDiv = document.getElementById("vizContainer"),
                url = "http://public.tableau.com/views/RegionalSampleWorkbook/Storms",
                options = {
                    hideTabs: true,
                    onFirstInteractive: function () {
                        console.log("Run this code when the viz has finished loading.");
                    }
                };

            var viz = new tableau.Viz(containerDiv, url, options);
            // Create a viz object and embed it in the container div.
        }
    </script>
</head>

<body onload="initViz();">
    <div id="vizContainer" style="width:800px; height:700px;"></div>
</body>

</html>
