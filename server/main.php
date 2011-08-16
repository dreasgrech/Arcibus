<?php
include 'socketserver/websocketserver.php';

error_reporting(E_ALL);

$address = '192.168.1.5';
$port = 8000;

$hooks = array(
	'onClientConnect' => function ($s, $socket) {
		$ip = $s->getIPAddress($socket);
		echo "New client connected: " . $ip . PHP_EOL;
		echo sprintf("Total clients: %d" . PHP_EOL, $s->numberOfClients());
	}, 'onClientDisconnect' => function ($s, $socket) {
		$ip = $s->getIPAddress($socket);
		echo "Client disconnected: " . $ip . PHP_EOL;
	}, 'onMessage' => function ($s, $message, $socket) {
		$ip = $s->getIPAddress($socket);
		echo sprintf("[%s] %s" . PHP_EOL, $ip, $message);
		$s->sendMessage($socket, "Thanks for the message");
	});

$server = new WebSocketServer($address, $port, $hooks);

?>
