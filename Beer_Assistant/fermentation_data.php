<!-- prevedere meccanismo di gestione della concorrenza per i radio button aggiornati da piu interfacce -->
<!-- quando si termina script da un dispositivo, tutti gli altri collegati non effettuando il refresh della pagina, continuano a visualizzar l'ultimo valore in doupdate -->

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]><script src="/js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
    
     <link href="css/fermentation_data.css" rel="stylesheet" type="text/css">
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

			// Check for open batch (if yes, collect graph data and hide "start fermentation")
			// $('#batch_title').load( 'lib/get_open_batch.php?step=fermentation' );

			// RADIO BUTTON HANDLING  ------------------------------
			$('#heat_auto').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=NULL&id="+$('#batch_id').text()+"&step=fermentation&device=heater" )
                }
   			});
    		$('#heat_on').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=1&id="+$('#batch_id').text()+"&step=fermentation&device=heater" )
                }
    		});
    		$('#heat_off').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=0&id="+$('#batch_id').text()+"&step=fermentation&device=heater" )
                }
    		});
			// RADIO BUTTON HANDLING  ------------------------------
			$('#cool_auto').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=NULL&id="+$('#batch_id').text()+"&step=fermentation&device=cooler" )
                }
   			});
    		$('#cool_on').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=1&id="+$('#batch_id').text()+"&step=fermentation&device=cooler" )
                }
    		});
    		$('#cool_off').click(function () {
                if ($(this).is(':checked')) {
                    $.ajax( "lib/device_control.php?command=0&id="+$('#batch_id').text()+"&step=fermentation&device=cooler" )
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
					$('#batch_title').load( 'lib/start_fermentation.php?'+"&receipe_name="+$('#receipe_name').val()+"&upper_limit="+$('#temp_upper_limit').val()+"&lower_limit="+$('#temp_lower_limit').val() );
					location.reload();					     
			   });
				   
				$('#stop').click( function(){
					update_graph = false;      
					endFermentation(); 
					$(this).hide();
					$('#start').show();
				});
				
				$('#update_temp').click( function(){
					$.ajax( "lib/fermentationtemp_limits.php?id="+$('#batch_id').text()+"&upper_limit="+$('#temp_upper_limit').val()+"&lower_limit="+$('#temp_lower_limit').val() );
				});

				function endFermentation() {
					$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text()+'&end_fermentation=1' );
					location.reload();
				}
			 
				function doUpdate() {

	                    <?php shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/py/fermentation_control.py > /dev/null 2>/dev/null &"); ?> 

						
						$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text());
						
						if(data.length > n-1){
							data.shift();
						}

						var y = $('#fermentation_temp').text();
    					var x = (new Date()).getTime();    					
    					
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
				
				if ( $('#batch_id').text() == "0" ) {
					$('#stop').hide();
					
				} else {
					$('#start').hide();
					$('#receipe_info').hide();
					doUpdate();
				}
		});
    </script>
    
	<title>Fermentation</title>
</head>

<body>
    <!--   <div id="myChart" style="height:400px; width:100%; "></div> -->


<div id="maincontainer">

	<div id="topsection">
		<div class="innertube">
	
		<h1 id="batch_title">
			<?php
			 $step = "fermentation";
			 include 'lib/get_open_batch.php';
			?>
		</h1>
		</div>
	
	</div>

    <div id="contentwrapper">
        <div id="contentcolumn">
        	<div class="innertube"><b>Information: </b></div>
        	<fieldset id="receipe_info">
                    <legend>Receipe details: </legend>
                    <label for="receipe_name">Receipe name</label>
                    <input type="text" name="receipe_info" id="receipe_name" maxlength="255" value="(receipe name)"><br>         
			</fieldset>
        </div>
    </div>

    <div id="leftcolumn">
    	<div class="innertube"><b>Status: <em>20%</em></b>
    	    <div class="data" id="show_data">
                Temperature &deg;C: 
                <div id="fermentation_temp">0.0</div>
                Max temp &deg;C: 
                <div id="max_temp">0.0</div>
                Min temp &deg;C: 
                <div id="max_temp">0.0</div>
                Collected at:
                <div id="current_timestamp">--</div>
                Heat:
                <div id="heat">--</div>
                Cool:
                <div id="cool">--</div>
                Starting time at:
                <div id="starting_time">--</div>
            </div>
    	</div>
    </div>

	<!-- prevedere meccanismo di gestione della concorrenza per i radio button aggiornati da piu interfacce -->

    <div id="rightcolumn">
		<div class="innertube"><b>Devices: <em>20%</em></b>
			<div class="device" id="show_devices">
				<fieldset id="heat_group">
                    <legend>Heater management: </legend>
                    <label for="heat_auto">Auto</label>
                    <input type="radio" name="heat_group" id="heat_auto" checked="checked">
                    <label for="heat_on">ON</label>
                    <input type="radio" name="heat_group" id="heat_on">
                    <label for="heat_off">OFF</label>
                    <input type="radio" name="heat_group" id="heat_off">
				</fieldset>
				<fieldset id="cool_group">
                    <legend>Cooler management: </legend>
                    <label for="cool_auto">Auto</label>
                    <input type="radio" name="cool_group" id="cool_auto" checked="checked">
                    <label for="cool_on">ON</label>
                    <input type="radio" name="cool_group" id="cool_on">
                    <label for="cool_off">OFF</label>
                    <input type="radio" name="cool_group" id="cool_off">
				</fieldset>

				<fieldset id="target_temp_group">
                    <legend>Temperature management: </legend>
                    <label for="temp_upper_limit">Temp max &deg;C</label>
                    <input type="text" name="target_temp_group" id="temp_upper_limit" maxlength="5" size="5" value="22.5"> (Example <b>22.5</b>)<br>
                    <label for="temp_lower_limit">Temp min &deg;C</label>
                    <input type="text" name="target_temp_group" id="temp_lower_limit" maxlength="5" size="5" value="20.0"> (Example <b>20.0</b>)<br>
                    <button id="update_temp" data-role="button">Update temp limits</button>                   
				</fieldset>
			</div>
		</div>
	</div>
	
	<div id="myChart"></div>
	
	<div id="command">
		<button id="start" data-role="button">Start Fermentation</button>
		<button id="stop" data-role="button">End</button>
		<button id="option" data-role="button">Options</button>
	</div>
	
	<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank">https://github.com/vincenzodabronzo/beer_assistant</a></div>

</div>


</body>

</html>