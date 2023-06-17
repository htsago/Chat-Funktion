<?php
session_start();
$_SESSION = array(); 
session_destroy(); // Damit  löschen wir alle in Verbindung mit der aktuellen Session stehenden Daten
// Umleitung zu der Login Seite
header("Location: login.php");

?>
