<?php
    // ini_set('display_errors', 'On');
    
    $receipe_name = "nuovo nome";

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO mashing_config(id, starting_time, pump_recirculation) VALUES ( '".$id."', CURRENT_TIMESTAMP, '0' ) " );
    $dbconn->query("INSERT INTO mashing_step(id, target_temp, minutes, step_number) VALUES ( '".$id."', '68.0', '60', '1' ) " );
    
    echo    '(Receipe name)<div id="batch_id">'. $id .'</div> ';
    
    header('Location: '.$_SERVER['PHP_SELF'].'/beer_assistant/Beer_Assistant/mash_data.php');
    
?>