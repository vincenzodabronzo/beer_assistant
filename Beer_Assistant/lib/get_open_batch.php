<?php
// ini_set('display_errors', 'On');

$step = $_GET["step"];

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT c.id FROM ".$step."_config AS c WHERE c.ending_time is NULL ORDER BY c.id DESC LIMIT 1");

if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()){
        echo    '(Receipe name)<div id="batch_id">'. $row['id'] .'</div> ';
        // shell_exec("python py/mashtune_heat.py > /dev/null 2>/dev/null &");
    } 
} else {
    echo    '(No open batch found - Start process)<div id="batch_id">0</div> ';
}

?>