<?php

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT tg.token, tg.user_id FROM system_config_telegram_gatekeeper AS tg;");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
        echo    '<div id="'.$row["token"].$row["userid"].'"><div>&nbsp;Token</div><div>&nbsp;'.$row["token"].'</div><div>&nbsp;User Id</div><div>&nbsp;'.$row["user_id"].'</div><div>&nbsp;<img id="img'.$row["token"].$row["userid"].'" src="img/remove.png"></div></div>
                    <script>
                    	document.getElementById("img'.$row["token"].$row["userid"].'").addEventListener("click", function(){
			             removeUser(\''.$row["token"].'\', \''.$row["userid"].'\');
			             });
                    </script>';
    }
} else echo "(none)";


?>