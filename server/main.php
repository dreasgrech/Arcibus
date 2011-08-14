<?php
include 'server.php';

error_reporting(E_ALL);

$server = new Server('192.168.1.5', 8000);
$server->run();

?>
