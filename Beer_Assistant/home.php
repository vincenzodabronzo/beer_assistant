<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

     <script type="text/javascript" src="js/jquery.min.js"></script>
	
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link href="css/home.css" rel="stylesheet" type="text/css">
	 
	     <script type="text/javascript">

		$(document).ready(function() {
			
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
				<legend>Telegram Bot </legend>
    			<div class="control-group">
            
                    <br><br>Bot activation<br><br><label class="switch" id="telegram_bot_activation" >
                      <input type="checkbox" id="telegram_bot_input"  <?php $command = "loadvariables"; include "lib/telegrambot_control.php"; echo $checked; ?> >
                      <span class="slider round"></span>
                    </label>
                    
                    <br><br>Authorized users:
                    <br>
                    <div id="authorized_users"></div>
                    <div id="add_new_user"> Token: <input type="text" id="token">&nbsp;User id <input type="text" id="userid">&nbsp;&nbsp;&nbsp;<img src="img/add.png"></div>
                    
                    
                    
                </div>
				</fieldset>   
			
		</div>
		
		<!--         -->
		

    
 </div>
		

	<div class="data" id="show_data" style="display: none;"  >



    </div>


</body>

</html>