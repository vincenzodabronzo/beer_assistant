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

			function removeUser(token, userid) {
				alert(token+" "+userid);
			}
			
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
            
                    <br><br><b>Bot activation</b><br><br><label class="switch" id="telegram_bot_activation" >
                      <input type="checkbox" id="telegram_bot_input"  <?php $command = "loadvariables"; include "lib/telegrambot_control.php"; echo $checked; ?> >
                      <span class="slider round"></span>
                    </label>
                    
                    <br><br><b>Authorized users</b>:
                    <div id="authorized_users"><div id="authorized_users"><?php include "lib/load_telegram_users.php"; ?></div>
                    
                    <br><br><b>Add new user</b>:
                    <br>
                    <div id="add_new_user"> <div>Token</div> <input type="text" id="token"><div>User Id</div><input type="text" id="userid"><br><img src="img/add.png"></div>
                    
                    
                    
                </div>
				</fieldset>   
			
		</div>
		
		<!--         -->
		

    
 </div>
		

	<div class="data" id="show_data" style="display: none;"  >
    </div>

<div id="authorized_users"><div id="authorized_users"><div id="458737458:AAHskrQVsMN32bBeexZcruDK3x9hz8vmhaY"><div>&nbsp;Token</div><div>&nbsp;458737458:AAHskrQVsMN32bBeexZcruDK3x9hz8vmhaY</div><div>&nbsp;User Id</div><div>&nbsp;114104929</div><div>&nbsp;<img id="remove_user" src="img/remove.png" onclick="javascript: removeUser('458737458:AAHskrQVsMN32bBeexZcruDK3x9hz8vmhaY','114104929');"></div></div></div>
                    

</body>

</html>