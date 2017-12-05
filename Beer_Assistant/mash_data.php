<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]><script src="/js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
    
     <link href="css/mash_data.css" rel="stylesheet" type="text/css">
     <link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/moment-with-locales.min.js"></script>
    
    <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="js/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.pointLabels.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasAxisTickRenderer.js"></script>
    <script type="text/javascript" src="js/jqplot.canvasOverlay.js"></script>
    <script type="text/javascript" src="js/jqplot.highlighter.js"></script>
	<script type="text/javascript" src="js/jqplot.cursor.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
	
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    
    <script type="text/javascript">

    	//  microsec interval
    	var t = 1000;
    	
		
		$(document).ready(function() {

			var update_graph = false;

			// Check for open batch (if yes, collect graph data and hide "start mashing")
			$('#batch_title').load( 'lib/get_open_batch.php' );

			// RADIO BUTTON HANDLING  ------------------------------
			$('#heat_auto').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/heat_control.php?c=NULL&id="+$('#batch_id').text() )
                }
   			});
    		$('#heat_on').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/heat_control.php?c=1&id="+$('#batch_id').text() )
                }
    		});
    		$('#heat_off').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/heat_control.php?c=0&id="+$('#batch_id').text() )
                }
    		});
    		
    		$('#pump_on').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/pump_control.php?c=1&id="+$('#batch_id').text() )
                }
    		});
    		$('#pump_off').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/pump_control.php?c=0&id="+$('#batch_id').text() )
                }
    		});
    		
    		// END RADIO BUTTON HANDLING ------------------------------
    		
			
			var x = (new Date()).getTime(); // current time
			
			var n = 20;
			data = [];
			
			for(i=0; i<n; i++){  
			    data.push([x - (n-1-i)*t, 0]);  
			}   
			
			var options = {      
			      axes: {   	    
			         xaxis: {     	   
			        	tickRenderer:$.jqplot.CanvasAxisTickRenderer,
				        numberTicks: 10,            
			            renderer:$.jqplot.DateAxisRenderer,           
			            tickOptions:{
				            	formatString:'%H:%M:%S',
				            	// //labelPosition: 'middle', 
				                angle:-30
						},            
			            min : data[0][0],           
			            max: data[data.length-1][0],
			            label:'Time'	   
					}, 	    
					yaxis: {
						labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
			            min: -10, 
			            max: 120,
			            numberTicks: 14,   	        
			            tickOptions:{formatString:'%.1f'},
						label:'Temperature Celsius'   
					}      
			      },      
			      seriesDefaults: {   	    
			    	  pointLabels: {
		                    show: true
		                },
				         rendererOptions: { smooth: true}      
			      },
			      highlighter: {
			          show: true,
			          sizeAdjust: 7.5
			        },
			        cursor: {
			          show: false
			        }
			  };  
			 
			   var plot1 = $.jqplot ('myChart', [data],options); 
			 
				$('#start').click( function(){
					update_graph = true;

					// $('#batch_title').load( 'lib/start_mashing.php' );
					// doUpdate();
					
					$('#batch_title').load( 'lib/start_mashing.php' ).done(function() {
						  doUpdate() );
						});
	
					     
					$(this).hide();
					$('#stop').show();
			   });
				   
				$('#stop').click( function(){
					update_graph = false;      
					endMashing(); 
					$(this).hide();
					$('#start').show();
				});

				function endMashing() {
					$('#show_data').load('lib/end_mashing.php?id='+$('#batch_id').text());
				}
			 
				function doUpdate() {
					if (update_graph) {

						// var loc = window.location.pathname;
						// var dir = loc.substring(0, loc.lastIndexOf('/'));
						// var path = dir + '/py/temp_mashing_nosensor_v1_3.py';
						
						
    					<?php echo "/*"; echo shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/py/mashtune_heat.py 2>&1"); echo "*/"; ?> // error collection
	                    // < ?php shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/lib/mashtune_heat.py > /dev/null 2>/dev/null &"); ?> 
						// < ? php shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/lib/mashtune_heat.py > /dev/null 2>&1 &"); ?>
						
						$('#show_data').load('lib/data.php?id='+$('#batch_id').text());
						// $('#show_data').load('lib/data.php?id=3');
						
						if(data.length > n-1){
							data.shift();
						}

						var y = $('#mashing_temp').text();
    					var x = (new Date()).getTime();    					

						//var ts = $('#current_timestamp').text().split(/[- :]/);
        				// Apply each element to the Date function
       					// var x = ( new Date(Date.UTC(ts[0], ts[1]-1, ts[2], ts[3], ts[4], ts[5])) ).getTime();
       					// x = x - 3600000; // Timezone: subtract 1 Hour
    					
    					
    					data.push([x,y]);
    					if (plot1) {
    						plot1.destroy();
    					}
    					plot1.series[0].data = data; 
    					options.axes.xaxis.min = data[0][0];
    					options.axes.xaxis.max = data[data.length-1][0];
    					plot1 = $.jqplot ('myChart', [data],options);
    
    					setTimeout(doUpdate, t);
					}
			   }
				
				if ( $('#batch_id').text() == "0" ) {
					$('#stop').hide();
				} else {
					$('#start').hide();
					update_graph = true;
					doUpdate();
				}
		});
    </script>
    
	<title>Mashing</title>
</head>

<body>
    <!--   <div id="myChart" style="height:400px; width:100%; "></div> -->


<div id="maincontainer">

	<div id="topsection">
		<div class="innertube">
	
		<h1 id="batch_title">
			<?php 
			 include 'lib/get_open_batch.php';
			?>
		</h1>
		</div>
	
	</div>

    <div id="contentwrapper">
        <div id="contentcolumn">
        	<div class="innertube"><b>Manual mashing: </b></div>
        </div>
    </div>

    <div id="leftcolumn">
    	<div class="innertube"><b>Status: <em>20%</em></b>
    	    <div class="data" id="show_data">
                Temperature &deg;C: 
                <div id="mashing_temp">0.0</div>
                Collected at:
                <div id="current_timestamp">--</div>
                Starting time at:
                <div id="starting_time">--</div>
                Ending time at:
                <div id="ending_time">--</div>
            </div>
    	</div>
    </div>

    <div id="rightcolumn">
		<div class="innertube"><b>Devices: <em>20%</em></b>
			<div class="device" id="show_devices">
				<fieldset id="heat_group">
                    <legend>Heat activation: </legend>
                    <label for="radio-1">Auto</label>
                    <input type="radio" name="heat_group" id="heat_auto" checked="checked">
                    <label for="radio-2">ON</label>
                    <input type="radio" name="heat_group" id="heat_on">
                    <label for="radio-3">OFF</label>
                    <input type="radio" name="heat_group" id="heat_off">
				</fieldset>
				<fieldset id="pump_group">
                    <legend>Pump Control: </legend>
                    <label for="radio-1">ON</label>
                    <input type="radio" name="pump_group" id="pump_on">
                    <label for="radio-2">OFF</label>
                    <input type="radio" name="pump_group" id="pump_off" checked="checked">
				</fieldset>
			</div>
		</div>
	</div>
	
	<div id="myChart"></div>
	
	<div id="command">
		<button id="start" data-role="button">Start Mashing</button>
		<button id="stop" data-role="button">End</button>
		<button id="option" data-role="button">Options</button>
	</div>
	
	<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank">https://github.com/vincenzodabronzo/beer_assistant</a></div>

</div>


</body>

</html>