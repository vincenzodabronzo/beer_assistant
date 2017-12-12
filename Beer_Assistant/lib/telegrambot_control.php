<?php
 ini_set('display_errors', 'On');

// Command: getinfo, 1, 0
$id = "1";
$checked = "";

if ($_GET["command"] != "") {
    $command = $_GET["command"];
}

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

if ($command == "1" || $command == "0") {
    $dbconn->query("UPDATE system_config SET telegram = ".$command." WHERE system_config.id = ".$id.";");
    echo '
        Telegram bot:
        <div id="telegram_bot">'.$command.'</div>'
     ;
} else if ($command == "loadvariables" ) {
    $result = $dbconn->query("SELECT system_config.id, system_config.telegram FROM system_config WHERE system_config.id = ".$id.";");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            if ($row['telegram'] == "1") {
                $checked = 'checked';
            }
        }
    }
} else if ($command == "remove") {
    $rm_token = $_GET["token"];
    $rm_userid = $_GET["userid"];
    
    $dbconn->query(" DELETE FROM system_config_telegram_gatekeeper WHERE token='".$rm_token."' AND user_id='".$rm_userid."'; ");

}


?>