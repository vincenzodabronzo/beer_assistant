<?php

    $id = $_GET["id"];
    $upper_limit = $_GET["upper_limit"];
    $lower_limit = $_GET["lower_limit"];
    
    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    
    $dbconn->query("UPDATE fermentation_step SET temp_max = ".$upper_limit.", temp_min = ".$lower_limit."  WHERE fermentation_step.id = ".$id.";");


?>
