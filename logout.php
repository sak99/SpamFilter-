<?php 
session_start();

// kill the session
session_destroy();

// redirect back to website home
header("Location: final.php");

      
?>
