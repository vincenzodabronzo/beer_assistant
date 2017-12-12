<?php

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT tg.token, tg.user_id FROM system_config_telegram_gatekeeper AS tg;");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
        echo    '<div id='.$row["token"].$row["userid"].'><div>Token</div><div>'.$row["token"].'</div><div>User Id</div><div>'.$row["user_id"].'</div><div><img src="/img/remove.png"></div></div>';
    }
} else echo "(none)";


?>