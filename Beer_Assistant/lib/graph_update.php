<?php
    ini_set('display_errors', '1');
    
    // header("Content-type: graph/png");
    // $string = $_GET['text'];
    $string = 'enzo';
    $im     = imagecreatefromjpeg("../img/graph.jpg");
    $orange = imagecolorallocate($im, 220, 210, 60);
    // $px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
    imagestring($im, 3, 10, 9, $string, $orange);
    
    //header('Content-Type: image/jpeg');
    imagejpeg($im, '../img/enzo.jpg');
    echo "<img src=\"../img/enzo.jpg\">";
    
    imagedestroy($im);

?>