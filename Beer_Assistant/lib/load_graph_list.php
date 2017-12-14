<?php 

// ini_set('display_errors', 'On');

if($_GET["step"] != "") {
    $step = $_GET["step"];
}

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}


$result = $dbconn->query("SELECT b.id, b.name, c.starting_time FROM  ".$step."_config AS c INNER JOIN batch AS b on b.id=c.id WHERE c.ending_time IS NOT NULL ORDER BY c.starting_time DESC");


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()){
        echo    ' <li id="'.$row['id'].'" class="list_item" onclick="getChartData('.$row['id'].', \''.$step.'\')"><br>'.$row['starting_time'].'<br>'.$row['name'].'</li>';
    }
} else {
    echo '(No Graphs available)' ;
}

?>