<?php
include 'socketserver/websocketserver.php';
include 'logger.php';
include 'player.php';
include 'message.php';

error_reporting(E_ALL);

		//system("cecho {0C}abc{#}");
		system("echoc 0 14 yes");

//echo PHP_EOL;

$address = '192.168.1.5';
$port = 8000;

$logger = new Logger();

$hooks = array(
	'onClientConnect' => function ($s, $socket) use ($logger) {
		$ip = $s->getIPAddress($socket);
		$logger->logServerAction("New client connected: " . $ip);
	}, 
		'onClientHandshaked' => function ($s, $socket) {
			$newPlayer = new Player(Player::generatePlayerID());
			$welcomeMessage = new WelcomePlayerMessage($newPlayer->ID);
			$s->sendMessage($socket, $welcomeMessage->serialize());
		},
			'onClientDisconnect' => function ($s, $socket) {
				$ip = $s->getIPAddress($socket);
				echo "Client disconnected: " . $ip . PHP_EOL;
			}, 'onMessage' => function ($s, $message, $socket) {
				$ip = $s->getIPAddress($socket);
				echo sprintf("[%s] %s" . PHP_EOL, $ip, $message);
				$s->sendMessage($socket, "Thanks for the message");
			});

$server = new WebSocketServer($address, $port, $hooks);

?>
