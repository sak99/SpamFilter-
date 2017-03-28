<?php
$to = "";
$subject = "The mail subject goes here";
$content= "And this is the mail content!";
$headers = "From:Me@Mywebsite.Com\r\n";

mail($to,$subject,$content,$headers);
?>	