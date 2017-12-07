<?php
    // ini_set('display_errors', 'On');
    
$receipe_name = $_GET["receipe_name"];
    $upper_limit = $_GET["upper_limit"];
    $lower_limit = $_GET["lower_limit"];

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO fermentation_config(id, starting_time) VALUES ( '".$id."', CURRENT_TIMESTAMP ) " );
    $dbconn->query("INSERT INTO fermentation_step(id, temp_min, temp_max, step_number) VALUES ( '".$id."', '".$lower_limit."', '1' ) " );
    
    echo    '(Receipe name)<div id="batch_id">'. $id .'</div> ';
    
?>