<?php

    $id = $_GET["id"];
    $upper_limit = $_GET["upper_limit"];
    
    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    
    $dbconn->query("UPDATE mashing_step SET target_temp = ".$upper_limit."  WHERE mashing_step.id = ".$id.";");


?>
