<?php

function extract_email_address ($string) {
   //print_r($string);
  $start='<';
  $end='>';
$string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);

    
}
    

?>