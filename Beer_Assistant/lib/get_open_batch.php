<?php
// ini_set('display_errors', 'On');

include_once 'vocabulary/en/en_fermentation.php';

if($_GET["step"] != "") {
    $step = $_GET["step"];
}

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$result = $dbconn->query("SELECT c.id, ba.name FROM ".$step."_config AS c INNER JOIN batch AS ba ON ba.id = c.id WHERE c.ending_time is NULL ORDER BY c.id DESC LIMIT 1");

if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()){
        echo $no_fermentation_quote[array_rand($no_fermentation_quote)].'<div id="batch_id" style="display: none;">'. $row['id'] .'</div> ';
    } 
} else {
    echo $no_fermentation_quote[array_rand($no_fermentation_quote)].'<br><br><div id="batch_id" style="display: none;">0</div> ';
}

?>