<?php

ini_set('display_errors', 'On');

//setting header to json
header('Content-Type: application/json');

//database
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'pi');
define('DB_PASSWORD', 'raspberry');
define('DB_NAME', 'dbeer');

$id = $_GET["id"];
$step = $_GET["step"];

//get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(!$mysqli){
	die("Connection failed: " . $mysqli->error);
}

echo '"SELECT timestamp, temperature FROM '.$step.'_temp WHERE id='.$id;

//query to get data from the table
$query = sprintf("SELECT timestamp, temperature FROM ".$step."_temp WHERE id=".$id);

//execute query
$result = $mysqli->query($query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

//free memory associated with result
$result->close();

//close connection
$mysqli->close();

//now print the data
print json_encode($data);