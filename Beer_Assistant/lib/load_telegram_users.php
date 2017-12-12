<?php

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT tg.token, tg.user_id FROM system_config_telegram_gatekeeper AS tg;");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
        $token = $row["token"];
        $userid = $row["user_id"];
        
        echo    '<div id="'.$token.$userid.'"><div>&nbsp;Token</div><div>&nbsp;'.$token.'</div><div>&nbsp;User Id</div><div>&nbsp;'.$userid.'</div><div>&nbsp;<img id="img'.$token.$userid.'" class="remove_user" src="img/remove.png"></div></div>
                    <script>
                    	document.getElementById("img'.$token.$userid.'").addEventListener("click", function(){
                            $.ajax( "lib/telegrambot_control.php?command=remove&token='.$token.'&userid='.$userid.'");
                            $(\'#'.$token.$userid.'\').hide();
			             });
                    </script>';
    }
} else echo "(none)";


?>