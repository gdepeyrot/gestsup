<?php
################################################################################
# @Name : stat_pie.php
# @Description : display pies statistics
# @Call : /stat.php
# @Parameters : unit, values, names, libchart
# @Author : Flox
# @Create : 06/10/2012
# @Update : 22/12/2016
# @Version : 3.1.15
################################################################################
?>
<script type="text/javascript">
	$(function () {
    var chart;
    $(document).ready(function() {
    	
    	// Radialize the colors
		// Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function(color) {
		    // return {
		        // radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        // stops: [
		            // [0, color],
		            // [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        // ]
		    // };
		// });
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: '<?php echo $container; ?>',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
				backgroundColor:'#EEE'
				
            },
			// exporting: {
                         // url: 'http://export.highcharts.com/index-utf8-encode.php'
                      // },
            title: {
                text: '<?php echo $libchart; ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
				percentageDecimals: 1
            	
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                         style: {
                            width: '100px'
                                 },
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#ccc',
                       // distance: -1,
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' % <br /> ('+ this.point.y +' <?php echo $unit; ?>)';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '<?php echo T_('Répartition'); ?>',
                data: [
                   <?php
					for($i=0;$i<sizeof($values);$i++) 
					{ 
						$k=sizeof($values);
						$k=$k-1;
						if ($i==$k) echo "['$xnom[$i]', $values[$i]]"; else echo "['$xnom[$i]', $values[$i]],";
					} 
					?>
                ]
            }]
        });
    });
    
});
</script>
