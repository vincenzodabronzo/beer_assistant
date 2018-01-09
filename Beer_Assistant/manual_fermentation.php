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
     
      <!--
     <script type="text/javascript" src="js/moment.min.js"></script>
     <script type="text/javascript" src="js/moment-with-locales.min.js"></script>
	-->
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	    
    <script type="text/javascript">

    	//  microsec interval
    	var t = 2000;

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

			for (i = 0; i < 16; i++) { 	
    				$('#upperb_select_group').append($('<option>', {
    				    value: i,
    				    text: i+' mins'
    				}));
    				
    				$('#lowerb_select_group').append($('<option>', {
    				    value: i,
    				    text: i+' mins'
    				}));
			}

			$('#min_select_group').append($('<option>', {
			    value: '40.0' ,
			    text: '40.0 \xB0C'
			}));

			$('#max_select_group').val( '25.0' );
			$('#min_select_group').val( '18.0' );
			$('#upperb_select_group').val( '8' );
			$('#lowerb_select_group').val( '8' );
			

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
			 

	        
	       

				$('#update_temp').click( function(){
					$.ajax( "lib/fermentationtemp_limits.php?id="+$('#batch_id').text()+"&upper_limit="+$('#max_select_group').val()+"&lower_limit="+$('#min_select_group').val() );
				});

				function endFermentation() {
					$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text()+'&end_fermentation=1' );
					location.reload();
				}
			 
				function doUpdate() {
	                    <?php shell_exec("python py/fermentation_control.py > /dev/null 2>/dev/null &"); ?> 
						$('#show_data').load('lib/update_fermentation.php?id='+$('#batch_id').text());
						$('#max_temp_dashboard').text( $('#max_temp').text() );
						$('#min_temp_dashboard').text( $('#min_temp').text() );

						$('#starting_time_calendar').text( $('#starting_time').text() );
						$('#current_timestamp_calendar').text( $('#current_timestamp').text() );

						updateGage( Number($('#fermentation_temp').text()) );
    					setTimeout(doUpdate, t);
			   }

				function updateGage(n) {
					  g.refresh(n);
				}

				
				if ( $('#batch_id').text() == "0" ) {
					$('#update_temp').attr('disabled', 'disabled');
					$('#update_temp').text('Select Max and Min');

					$('#play').attr('src', 'img/play.png');

					$('#heat_auto').attr('disabled', 'disabled');
					$('#heat_on').attr('disabled', 'disabled');
					$('#heat_off').attr('disabled', 'disabled');
					$('#cool_auto').attr('disabled', 'disabled');
					$('#cool_on').attr('disabled', 'disabled');
					$('#cool_off').attr('disabled', 'disabled');
					
				} else {

					$('#batch_name_container').html( $('#batch_name_loaded').text() );
					
					$('#receipe_info').hide();
					$('#update_temp').removeAttr('disabled');
					$('#update_temp').text('Click to update');
					$('#heat_auto').removeAttr('disabled');
					$('#heat_on').removeAttr('disabled');
					$('#heat_off').removeAttr('disabled');					
					$('#cool_auto').removeAttr('disabled');
					$('#cool_on').removeAttr('disabled');
					$('#cool_off').removeAttr('disabled');

					$('#play').attr('src', 'img/shutdown.png');
					
					doUpdate();
				}


				$('#tab_help').hide();
				$('#tab_options').hide();
				$('#tab_calendar').hide();
				
				function manageTab(name) {
					$('#tab_'+name).toggle( "fade" );
					$('html, body').animate({ scrollTop: $('#tab_'+name).offset().top }, 'slow');
				}


				document.getElementById("play").addEventListener("click", function(){				
					if( $('#batch_id').text() == "0" ) {

						var batch_name = $('#batch_name').val().replace(/\s/g, "%20");

    					$('#batch_title').load( 'lib/start_fermentation.php?'+"receipe_name="+batch_name+"&upper_limit="+$('#max_select_group').val()+"&lower_limit="+$('#min_select_group').val() );
    					location.reload()
					} else {
						endFermentation(); 
						$('#receipe_info').show();
						$('#update_temp').attr('disabled', 'disabled');
						$('#update_temp').text('Select Max and Min');
						$('#play').attr('src', 'img/play.png');
						$('#heat_auto').attr('disabled', 'disabled');
						$('#heat_on').attr('disabled', 'disabled');
						$('#heat_off').attr('disabled', 'disabled');
						$('#cool_auto').attr('disabled', 'disabled');
						$('#cool_on').attr('disabled', 'disabled');
						$('#cool_off').attr('disabled', 'disabled');
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
    
	<title>Fermentation</title>
</head>

<body>

<div id="header">Fermentation</div>
<br>

<div id="maincontainer">

	<br>
	<div id="topsection">
		<div class="innertube">
    		<div id="batch_title">
    			<?php
    			 $step = "fermentation";
    			 include_once 'lib/get_open_batch.php';
    			?>
    		</div>
		</div>
	</div>
	
	<div class="wrapper">
      <div class="box">
        <div id="gauge" class="gauge"></div>
      </div>
    </div>
    
    
    <div id="batch_name_container">Name: <input id="batch_name" type="text" maxlength="255"></div>
    <br>
    
    <div id="m_select" class="m_select">
   		 Max <div id="max_temp_dashboard" style="display: inline">25.0</div>&nbsp;&deg;C 
    	&nbsp;&nbsp;&nbsp;
    	Min <div id="min_temp_dashboard" style="display: inline">18.0</div>&nbsp;&deg;C 
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
       			<label for="max_select_group">Max</label>
        		<select id="max_select_group">
        		</select>
        		&nbsp;&nbsp;&nbsp;
        		<label for="min_select_group">Min</label>
        		<select id="min_select_group">
        		</select>
        		<button id="update_temp" data-role="button">Update Max and Min</button>
	    </div>
	    
	    <div id="b_select" class="b_select">
       			<label for="upperb_select_group">Upper buffer</label>
        		<select id="upperb_select_group">
        		</select>
        		&nbsp;&nbsp;&nbsp;
        		<label for="lowerb_select_group">Lower buffer</label>
        		<select id="lowerb_select_group">
        		</select>
        		<button id="update_boundaries" data-role="button">Update Boundaries</button>
	    </div>

			<fieldset id="heat_group">
				<legend>Heater management: </legend>
    			<div class="control-group">
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=NULL&id='+$('#batch_id').text()+'&step=fermentation&device=heater' );">
                        Auto
                            <input type="radio" id="heat_auto" name="radio_heat" checked="checked" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=1&id='+$('#batch_id').text()+'&step=fermentation&device=heater' );">
                        On
                            <input type="radio" id="heat_on" name="radio_heat" disabled="disabled" />
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=0&id='+$('#batch_id').text()+'&step=fermentation&device=heater' );">
                        Off
                            <input type="radio" id="heat_off" name="radio_heat" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                </div>
			</fieldset>
			
			<fieldset id="cool_group">
				<legend>Cooler management: </legend>
    			<div class="control-group">
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=NULL&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
                        Auto
                            <input type="radio" id="cool_auto" name="radio_cool" checked="checked" disabled="disabled"/>
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=1&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
                        On
                            <input type="radio" id="cool_on" name="radio_cool" disabled="disabled" />
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/device_control.php?command=0&id='+$('#batch_id').text()+'&step=fermentation&device=cooler' );">
                        Off
                            <input type="radio" id="cool_off" name="radio_cool" disabled="disabled"/>
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
      	 include_once 'vocabulary/en_fermentation.php';
      	 echo $info;
      	?>  
      </div>
    </div>
    
    
<br><br>
<div id="footer"><a href="home.php"><img id="home" src="img/home.png"></a></div>
<br><br>


<br><br>
<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank"><img id="logo" src="img/logo.png"></a></div>
<br><br>

<!-- Hidden variables --> 

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
 


</body>

</html>