<?php 
    ini_set('display_errors', 'On');
    require_once 'lib/telegrambot_control.php?command=loadvariables';
    
?>
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

			/*
			alert($( '#telegram_bot' ).text());
		    $('#show_data').load( 'lib/telegrambot_control.php?command=getinfo' );
			alert($( '#telegram_bot' ).text());
			if ( $( '#telegram_bot' ).text()=="1" ) {
				$( '#telegram_bot_input' ).attr('checked', true);
				$( '#test_radio' ).attr('checked', true);
			} 
			*/
			
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
				$('#show_data').load( 'lib/telegrambot_control.php?command='+active );
				
			});


			
	});
</script>
	
	<title>Main menu</title>
</head>

<body>

<div id="maincontainer">

<?php include_once 'lib/telegrambot_control.php?command=getinfo'; ?>


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
				<legend>Telegram Bot </legend>
    			<div class="control-group">
            
                    Bot activation<br><label class="switch" id="telegram_bot_activation" >
                      <input type="checkbox" id="telegram_bot_input"  <?php echo $checkbox; ?> >
                      <span class="slider round"></span>
                    </label>
                    
                </div>
				</fieldset>   
			
		</div>
		
		<!--    style="display: none;"      -->
		

    
 </div>
		

	<div class="data" id="show_data" >
		Telegram bot:
                <div id="telegram_bot">--</div>
    </div>

<input type="checkbox" id="testradio" >
<?php echo $checkbox; echo $prova; ?>

</body>

</html>