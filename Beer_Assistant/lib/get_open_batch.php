<?php
// ini_set('display_errors', 'On');

if($step=="fermentation") {
    include_once 'vocabulary/en/en_fermentation.php';
} else if ($step=="mashing") {
    include_once 'vocabulary/en/en_mashing.php';
}

$display = ' style="display: none;"';
// $display="";

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
        
        if($step=="fermentation") {
            echo '<b>'.$fermentation_quote[array_rand($fermentation_quote)].'</b><br><br><div id="batch_id" '.$display.'>'. $row['id'] .'</div> ';
        } else if ($step=="mashing") {
            echo '<b>'.$mashing_quote[array_rand($mashing_quote)].'</b><br><br><div id="batch_id" '.$display.'">'. $row['id'] .'</div> ';
        }
        
    } 
} else {
    
    if($step=="fermentation") {
        echo '<b>'.$no_fermentation_quote[array_rand($no_fermentation_quote)].'</b><br><br><div id="batch_id" '.$display.'">0</div> ';
    } else if ($step=="mashing") {
        echo '<b>'.$no_mashing_quote[array_rand($no_mashing_quote)].'</b><br><br><div id="batch_id" '.$display.'">0</div> ';
    }
       
    
}

?>