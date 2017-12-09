<!-- prevedere meccanismo di gestione della concorrenza per i radio button aggiornati da piu interfacce -->
<!-- quando si termina script da un dispositivo, tutti gli altri collegati non effettuando il refresh della pagina, continuano a visualizzar l'ultimo valore in doupdate -->

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!--[if lt IE 9]><script src="/js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
    
     <link href="css/manual_fermentation.css" rel="stylesheet" type="text/css">
     <link href="css/jquery-ui.css" rel="stylesheet" type="text/css">
     <link href="css/gauge.css" rel="stylesheet" type="text/css">

     
     <script type="text/javascript" src="js/raphael-2.1.4.min.js"></script>
     <script type="text/javascript" src="js/justgage.js"></script>
     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript" src="js/jquery-ui.min.js"></script>
     <script type="text/javascript" src="js/moment.min.js"></script>
     <script type="text/javascript" src="js/moment-with-locales.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	    
    <script type="text/javascript">

    	//  microsec interval
    	var t = 1000;    	

		$(document).ready(function() {


			$('#max_select_group').append($('<option>', {
			    value: '40.0' ,
			    text: '40.0 \xB0C'
			}));
			
			for (i = 0; i < 40; i++) { 
				max_value = 39-i;
				
				for (j = 0; j <= 5; j+=5) { 
					max_value_decimal = 5-j;
					
    				$('#max_select_group').append($('<option>', {
    				    value: max_value+'.'+ max_value_decimal ,
    				    text: max_value+'.'+ max_value_decimal +' \xB0C'
    				}));
    				$('#min_select_group').append($('<option>', {
    				    value: i+'.'+j,
    				    text: i+'.'+j+' \xB0C'
    				}));
				}
			}

			$('#min_select_group').append($('<option>', {
			    value: '40.0' ,
			    text: '40.0 \xB0C'
			}));

			$('#max_select_group').val( '25.0' );
			$('#min_select_group').val( '18.0' );

			$( function() {
			    $( "#tabs" ).tabs();
			  } );
			
	        var gageValue = 0.0;

	        var g = new JustGage({
	          id: 'gauge',
	          value: gageValue,
	          min: 0,
	          max: 40,
	          title: "Temperature",
	          label: "(beer)",
	          symbol: '\xB0C',
	          decimals: 1,
	          pointer: true,
	          pointerOptions: {
	            toplength: -15,
	            bottomlength: 10,
	            bottomwidth: 12,
	            color: '#8e8e93',
	            stroke: '#ffffff',
	            stroke_width: 2,
	            stroke_linecap: 'round'
	          },
	          gaugeWidthScale: 0.6,      
	          counter: true
	        });

			// var elem = document.querySelector('.js-switch');
	    	// var init = new Switchery(elem);


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
    		

			 
				$('#start').click( function(){
					$('#batch_title').load( 'lib/start_fermentation.php?'+"receipe_name="+$('#receipe_name').val()+"&upper_limit="+$('#max_select_group').val()+"&lower_limit="+$('#min_select_group').val() );
					location.reload();					     
			   });
				   
				$('#stop').click( function(){    
					endFermentation(); 
					$(this).hide();
					$('#start').show();
					$('#receipe_info').show();
					$('#update_temp').hide();
				});
				
				$('#update_temp').click( function(){
					$.ajax( "lib/fermentationtemp_limits.php?id="+$('#batch_id').text()+"&upper_limit="+$('#max_select_group').val()+"&lower_limit="+$('#min_select_group').val() );
				});

				function endFermentation() {
					$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text()+'&end_fermentation=1' );
					location.reload();
				}
			 
				function doUpdate() {

	                    <?php shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/py/fermentation_control.py > /dev/null 2>/dev/null &"); ?> 

						$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text());

						$('#max_temp_dashboard').text( $('#max_temp').text() );
						$('#min_temp_dashboard').text( $('#min_temp').text() );

						var temperature = Number( $('#fermentation_temp').text() );
						updateGage(temperature);
  
    					setTimeout(doUpdate, t);
			   }

				function updateGage(n) {
					  g.refresh(n);
				}

				   
				
				if ( $('#batch_id').text() == "0" ) {
					$('#stop').hide();
					$('#update_temp').hide();
					
				} else {
					$('#start').hide();
					$('#receipe_info').hide();
					$('#update_temp').show();
					doUpdate();
				}
				


				
		});
    </script>
    
	<title>Fermentation</title>
</head>

<body>

<div id="maincontainer">

	<br>
	<div id="topsection">
		<div class="innertube">
    		<div id="batch_title">
    			<?php
    			 $step = "fermentation";
    			 include 'lib/get_open_batch.php';
    			?>
    		</div>
		</div>
	</div>
	
	<div class="wrapper">
      <div class="box">
        <div id="gauge" class="gauge"></div>
      </div>
    </div>
    <br>
    
    <div id="m_select" class="m_select">
   		 Max <div id="max_temp_dashboard" style="display: inline">0.0</div>&nbsp;&deg;C 
    	&nbsp;&nbsp;&nbsp;
    	Min <div id="min_temp_dashboard" style="display: inline">0.0</div>&nbsp;&deg;C 
	</div>
	
	<br>
    <br>
    
	
	<div id="command">
		<button id="start" data-role="button">Start Fermentation</button>
		<button id="stop" data-role="button">End</button>
	</div>

	<br>
	<br>
	

    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Options</a></li>
        <li><a href="#tabs-2">Info</a></li>
      </ul>
      <div id="tabs-1">
      
      	<div id="m_select" class="m_select">
       			<label for="max_select_group">Max</label>
        		<select id="max_select_group">
        		</select>
        		&nbsp;&nbsp;&nbsp;
        		<label for="min_select_group">Min</label>
        		<select id="min_select_group">
        		</select>
        		<button id="update_temp" data-role="button">Update values</button>
	    </div>
	    
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
		</div>
	    
      </div>

      <div id="tabs-2">
      	<?php 
      	 include_once 'vocabulary/en_fermentation.php';
      	 echo $info;
      	?>  
      </div>
    </div>

</div>

<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank">https://github.com/vincenzodabronzo/beer_assistant</a></div>

<!--   --> 

<div class="data" id="show_data" style="display: none;">
    Temperature &deg;C: 
    <div id="fermentation_temp">0.0</div>
    Max temp &deg;C: 
    <div id="max_temp">0.0</div>
    Min temp &deg;C: 
    <div id="min_temp">0.0</div>
    Collected at:
    <div id="current_timestamp">--</div>
    Heat:
    <div id="heat">--</div>
    Cool:
    <div id="cool">--</div>
    Starting time at:
    <div id="starting_time">--</div>
 </div>
 
<div class="control-group">
    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=NULL&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
        Auto
            <input type="radio" name="radio" checked="checked" />
        <div class="control_indicator"></div>
    </label>
    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=1&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
        On
            <input type="radio" name="radio" />
        <div class="control_indicator"></div>
    </label>
    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=0&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
        Off
            <input type="radio" name="radio" />
        <div class="control_indicator"></div>
    </label>
</div>

</body>

</html>