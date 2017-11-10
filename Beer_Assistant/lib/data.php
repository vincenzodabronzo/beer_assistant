<?php

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    #$result = $dbconn->query("SELECT temperature FROM temp_mashing", $query);
    $result = $dbconn->query("SELECT * FROM temp_mashing ORDER BY timestamp DESC LIMIT 1", $query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo $row['temperature'] . '<br>' . $row['timestamp'];
        }
    }
    
    
    
    
    // Function: Draw line on image
    
    function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
    {
        /* this way it works well only for orthogonal lines
         imagesetthickness($image, $thick);
         return imageline($image, $x1, $y1, $x2, $y2, $color);
         */
        if ($thick == 1) {
            return imageline($image, $x1, $y1, $x2, $y2, $color);
        }
        $t = $thick / 2 - 0.5;
        if ($x1 == $x2 || $y1 == $y2) {
            return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
        }
        $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
        $a = $t / sqrt(1 + pow($k, 2));
        $points = array(
            round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
            round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
            round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
            round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
        );
        imagefilledpolygon($image, $points, 4, $color);
        return imagepolygon($image, $points, 4, $color);
    }
    
    
?>