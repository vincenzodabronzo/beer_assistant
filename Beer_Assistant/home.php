<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/home.css" rel="stylesheet" type="text/css">
	 
	     <script type="text/javascript">

    	//  microsec interval
    	var t = 2000;

		$(document).ready(function() {

			$('#show_data').load( 'lib/telegrambot_control.php?command=getinfo');
			
			if( $('#telegram_bot').text() =="1" ) {
				$('#telegram_bot_input').prop('checked', true);
			} else {
				$('#telegram_bot_input').prop('checked', false);
			}
			 
			$('#tab_options').hide();

			function manageTab(name) {
				$('#tab_'+name).toggle( "fade" );
				$('html, body').animate({ scrollTop: $('#tab_'+name).offset().top }, 'slow');
			}

			document.getElementById("options").addEventListener("click", function(){
			    manageTab("options");
			});

			
			document.getElementById("telegram_bot_input").addEventListener("change", function(){

				var active = "0";
				if ( $( '#telegram_bot_input' ).is( ":checked" ) ) {
					active = "1";
				}
				
				$.ajax( 'lib/telegrambot_control.php?command='+active );
				
			});
			
			
			
	});
</script>
	
	<title>Main menu</title>
</head>

<body>

<div id="maincontainer">


	<div id="command">
		<br>
    	<?php 
    	   include_once 'vocabulary/en/en_general.php';
    	   echo '<b>'.$mainmenu_quote[array_rand($mainmenu_quote)].'</b>';
    	?>
		<br>
	<br><br>
		<a href="manual_mashing.php"><img id="mashing" src="img/mashing_menu.jpg"></a>
		<a href="manual_fermentation.php"><img id="fermentation" src="img/fermentation_menu.jpg"></a>
		<br><br>
		<img id="options" src="img/options.png">
		<img id="share" src="img/share.png" style="display: none;">
		<img id="help" src="img/help.png" style="display: none;">
	</div>
	
	

	<br>
	<br>
	
	     <div id="tab_options">
	     
			<img id="telegram" src="img/telegram.png">
				<fieldset id="bot_group">
				<legend>Bot management: </legend>
    			<div class="control-group">
                    <label class="control control-radio" onclick="$.ajax( 'lib/telegrambot_control.php?command=1' );">
                        On
                      <input type="radio" id="bot_on" name="radio_bot" />
                        <div class="control_indicator"></div>
                    </label>
                    <label class="control control-radio" onclick="$.ajax( 'lib/telegrambot_control.php?command=0' );">
                        Off
                            <input type="radio" id="bot_off" name="radio_bot" />
                        <div class="control_indicator"></div>
                    </label>
                    
                    Bot activation<br><label class="switch" id="telegram_bot_activation">
                      <input type="checkbox" id="telegram_bot_input">
                      <span class="slider round"></span>
                    </label>
                    
                </div>
				</fieldset>   
			
		</div>
		
		<!--    style="display: none;"      -->
		

    
 </div>
		

	<div class="data" id="show_data" >
        Telegram bot: 
        <div id="telegram_bot">0</div>
    </div>



</body>

</html>