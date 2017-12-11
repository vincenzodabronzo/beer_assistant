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
    	var t = 2000;

		$(document).ready(function() {


			$('#temp_select_group').append($('<option>', {
			    value: '100.0' ,
			    text: '100.0 \xB0C'
			}));
			
			for (i = 0; i < 100; i++) { 
				max_value = 100-i;
				
				for (j = 0; j <= 5; j+=5) { 
					max_value_decimal = 5-j;
					
    				$('#temp_select_group').append($('<option>', {
    				    value: max_value+'.'+ max_value_decimal ,
    				    text: max_value+'.'+ max_value_decimal +' \xB0C'
    				}));

				}
			}

			$('#temp_select_group').val( '67.0' );

			$( function() {
			    $( "#tabs" ).tabs();
			  } );
			
	        var gageValue = 0.0;

	        var g = new JustGage({
	          id: 'gauge',
	          value: gageValue,
	          min: 0,
	          max: 100,
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

				$('#update_temp').click( function(){
					$.ajax( "lib/mashingtemp_limits.php?id="+$('#batch_id').text()+"&upper_limit="+$('#temp_select_group').val() );
				});

				function endMashing() {
					$('#show_data').load('lib/update_mashing.php?id='+$('#batch_id').text()+'&end_mashing=1' );
					location.reload();
				}
			 
				function doUpdate() {
	                    <?php shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/py/mashing_control.py > /dev/null 2>/dev/null &"); ?> 
						$('#show_data').load('lib/update_mashing.php?id='+$('#batch_id').text());

						$('#target_temp_dashboard').text( $('#target_temp').text() );

						$('#starting_time_calendar').text( $('#starting_time').text() );
						$('#current_timestamp_calendar').text( $('#current_timestamp').text() );

						updateGage( Number($('#mashing_temp').text()) );
    					setTimeout(doUpdate, t);
			   }

				function updateGage(n) {
					  g.refresh(n);
				}

				   
				
				if ( $('#batch_id').text() == "0" ) {
					$('#update_temp').attr('disabled', 'disabled');
					$('#update_temp').text('Select Target temp');

					$('#play').attr('src', 'img/play.png');

					$('#heat_auto').attr('disabled', 'disabled');
					$('#heat_on').attr('disabled', 'disabled');
					$('#heat_off').attr('disabled', 'disabled');
					
					//$('#cool_auto').attr('disabled', 'disabled');
					
					$('#pump_on').attr('disabled', 'disabled');
					$('#pump_off').attr('disabled', 'disabled');
					
				} else {

					$('#receipe_info').hide();

					$('#update_temp').removeAttr('disabled');
					$('#update_temp').text('Click to update');

					$('#heat_auto').removeAttr('disabled');
					$('#heat_on').removeAttr('disabled');
					$('#heat_off').removeAttr('disabled');	
									
					// $('#cool_auto').removeAttr('disabled');
					$('#pump_on').removeAttr('disabled');
					$('#pump_off').removeAttr('disabled');

					$('#play').attr('src', 'img/shutdown.png');
					
					doUpdate();
				}


				$('#tab_help').hide();
				$('#tab_options').hide()
				$('#tab_calendar').hide();
				
				function manageTab(name) {
					$('#tab_'+name).toggle( "fade" );
					$('html, body').animate({ scrollTop: $('#tab_'+name).offset().top }, 'slow');
				}


				document.getElementById("play").addEventListener("click", function(){				
					if( $('#batch_id').text() == "0" ) {
    					$('#batch_title').load( 'lib/start_mashing.php?'+"receipe_name="+$('#receipe_name').val()+"&upper_limit="+$('#temp_select_group').val() );
    					location.reload()
					} else {
						endMashing(); 
						$('#receipe_info').show();
						$('#update_temp').attr('disabled', 'disabled');
						$('#update_temp').text('Select Target temp');
						
						$('#play').attr('src', 'img/play.png');
						$('#heat_auto').attr('disabled', 'disabled');
						$('#heat_on').attr('disabled', 'disabled');
						$('#heat_off').attr('disabled', 'disabled');
						
						//$('#cool_auto').attr('disabled', 'disabled');
						$('#pump_on').attr('disabled', 'disabled');
						$('#pump_off').attr('disabled', 'disabled');
					}
				});
				
				document.getElementById("help").addEventListener("click", function(){
				    manageTab("help");
				});
				document.getElementById("options").addEventListener("click", function(){
				    manageTab("options");
				});
				document.getElementById("calendar").addEventListener("click", function(){
				    manageTab("calendar");
				});
				
				
		});
    </script>
    
	<title>Mashing</title>
</head>

<body>

<div id="maincontainer">

	<br>
	<div id="topsection">
		<div class="innertube">
    		<div id="batch_title">
    			<?php
    			 $step = "mashing";
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
   		 Target temperature <div id="target_temp_dashboard" style="display: inline">67.0</div>&nbsp;&deg;C 
	</div>
	
	<br>
    <br>
    
	
	<div id="command">
		<img id="play" src="img/play.png">
		<img id="options" src="img/options.png">
		<img id="calendar" src="img/calendar.png">
		<img id="chart" src="img/chart.png" style="display: none;">
		<img id="share" src="img/share.png" style="display: none;">
		<img id="help" src="img/help.png">
	</div>
	
	

	<br>
	<br>
	


      
   <!--    <div id="tabs-1">--> 
      
      <div id="tab_options">
      	<div id="m_select" class="m_select">
       			<label for="temp_select_group">Target temp</label>
        		<select id="temp_select_group">
        		</select>
        		&nbsp;&nbsp;&nbsp;
        		<button id="update_temp" data-role="button">Update Target temp</button>
	    </div>

			<fieldset id="heat_group">
				<legend>Heater management: </legend>
    			<div class="control-group">
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=NULL&id='+$('#batch_id').text()+'&step=mashing&device=heater' );">
                        Auto
                            <input type="radio" id="heat_auto" name="radio_heat" checked="checked" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=1&id='+$('#batch_id').text()+'&step=mashing&device=heater' );">
                        On
                            <input type="radio" id="heat_on" name="radio_heat" disabled="disabled" />
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=0&id='+$('#batch_id').text()+'&step=mashing&device=heater' );">
                        Off
                            <input type="radio" id="heat_off" name="radio_heat" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                </div>
			</fieldset>
			
			<fieldset id="pump_group">
				<legend>Pump management: </legend>
    			<div class="control-group">
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=1&id='+$('#batch_id').text()+'&step=mashing&device=pump' );">
                        On
                            <input type="radio" id="pump_on" name="radio_pump" disabled="disabled" />
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=0&id='+$('#batch_id').text()+'&step=mashing&device=pump' );">
                        Off
                            <input type="radio" id="pump_off" name="radio_pump" checked="checked" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                </div>
			</fieldset>   
      </div>

	<div id="tab_calendar">
    	Last temperature reading at:
        <div id="current_timestamp_calendar">--</div>
        Fermentation started at:
        <div id="starting_time_calendar">--</div>
	</div>

      <div id="tab_help">
      	<?php 
      	 include_once 'vocabulary/en_mashing.php';
      	 echo $mashing_info;
      	?>  
      </div>
    </div>
    
    



<br><br>
<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank"><img id="logo" src="img/logo.png"></a></div>
<br><br>

<!-- Hidden variables  --> 

<div class="data" id="show_data" style="display: none;">
    Temperature &deg;C: 
    <div id="mashing_temp">0.0</div>
    Target temp &deg;C: 
    <div id="target_temp">0.0</div>
    Collected at:
    <div id="current_timestamp">--</div>
    Heat:
    <div id="heat">--</div>
    Cool:
    <div id="pump">--</div>
    Starting time at:
    <div id="starting_time">--</div>
    
 </div>
 


</body>

</html>