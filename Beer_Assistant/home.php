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

			function removeUser(token, userid) {
				alert(token+" "+userid);
			}
			
			document.getElementById("options").addEventListener("click", function(){
			    manageTab("options");
			});
			
			document.getElementById("telegram_bot_input").addEventListener("change", function(){
				var active = "0";
				if ( $( '#telegram_bot_input' ).is( ":checked" ) ) {
					active = "1";
				}
				$.ajax( "lib/telegrambot_control.php?command="+active );
			});

			document.getElementById("add_new_user").addEventListener("click", function(){
                $.ajax( "lib/telegrambot_control.php?command=add&token="+$('#token').val()+"&userid="+$('#userid').val());
                location.reload();
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
            
            		<div style="display: none;">
                    <br><b>Bot activation</b><br><br><label class="switch" id="telegram_bot_activation" >
                      <input type="checkbox" id="telegram_bot_input"  <?php $command = "loadvariables"; include "lib/telegrambot_control.php"; echo $checked; ?> >
                      <span class="slider round"></span>
                    </label>
                    </div>
                    
                    <br><br><b>Authorized users</b>:
                    <div id="authorized_users"><?php include "lib/load_telegram_users.php"; ?></div>
                    
                    <br><br><b>Add new user</b>:
                    <br>
                    <div > <div>Token</div> <input type="text" id="token"><div>User Id</div><input type="text" id="userid"><br><img id="add_new_user" class="add_user" src="img/add.png"></div>
                    
                    
                    
                </div>
				</fieldset>   
			
		</div>
		
		<!--         -->
		

    
 </div>
		

	<div class="data" id="show_data" style="display: none;"  >
    </div>           

</body>

</html>