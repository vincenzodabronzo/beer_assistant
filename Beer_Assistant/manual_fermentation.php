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
     <link href="css/switchery.min.css" rel="stylesheet" type="text/css">
     <link href="css/gauge.css" rel="stylesheet" type="text/css">
     
     <script type="text/javascript" src="js/switchery.min.js"></script>
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

			var elem = document.querySelector('.js-switch');
	    	var init = new Switchery(elem);


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
    
    <div id="m_select" class="m_select">
   		<label for="max_select_group">Max</label>
    	<select id="max_select_group">
    	</select>
    	&nbsp;&nbsp;&nbsp;
    	<label for="min_select_group">Min</label>
    	<select id="min_select_group">
    	</select>
	</div>
	
	<div id="command">
		<button id="start" data-role="button">Start Fermentation</button>
		<button id="stop" data-role="button">End</button>
		<button id="options" data-role="button">Options</button>
	</div>
    

    <div id="contentwrapper">
        <div id="contentcolumn">
        	<div class="innertube"><b>Information: </b></div>
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
			</div>
		</div>
	</div>
	

	
	<div id="footer"><a href="https://github.com/vincenzodabronzo/beer_assistant" target="_blank">https://github.com/vincenzodabronzo/beer_assistant</a></div>

    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Options</a></li>
        <li><a href="#tabs-2">Devices</a></li>
        <li><a href="#tabs-3">Info</a></li>
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
	    </div>
	    <button id="update_temp" data-role="button">Update values</button>
      </div>
      <div id="tabs-2">
        <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
      </div>
      <div id="tabs-3">
        <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
        <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
      </div>
    </div>

</div>
<br><br>
<input type="checkbox" class="js-switch" checked />
<br><br>

</body>

</html>