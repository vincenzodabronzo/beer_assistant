<?php
// ini_set('display_errors', 'On');

// Command: getinfo, 1, 0
$command = $_GET["command"];
$id = "1";

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
} else if ($command == "getinfo" ) {
    $dbconn->query("SELECT system_config.id, system_config.telegram WHERE system_config.id = ".$id.";");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo '
                Telegram bot:
                <div id="telegram_bot">'.$row['telegram'].'</div>'
                   ;
        }
    }
}

?>