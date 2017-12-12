<?php

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT tg.token, tg.user_id FROM system_config_telegram_gatekeeper AS tg;");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
        echo    '<div id='.$row["token"].$row["userid"].'><div>&nbsp;Token</div><div>&nbsp;'.$row["token"].'</div><div>&nbsp;User Id</div><div>&nbsp;'.$row["user_id"].'</div><div>&nbsp;<img id="remove_user" src="img/remove.png" onclick="removeUser(\' '.$row["token"].' \',\'\' '.$row["user_id"].' \')"></div></div>';
    }
} else echo "(none)";


?>