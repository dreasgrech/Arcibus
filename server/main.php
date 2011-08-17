<?php
include 'socketserver/websocketserver.php';
include 'logger.php';
include 'player.php';
include 'message.php';
include 'game.php';

error_reporting(E_ALL);

$address = '192.168.1.5';
$port = 8000;

$logger = new Logger();
$game = new Game();

$hooks = array(
	'onClientConnect' => function ($s, $socket) use ($logger) {
		$ip = $s->getIPAddress($socket);
		$logger->logServerAction("New client connected: " . $ip);
	}, 
		'onClientHandshaked' => function ($s, $socket) use (&$game, $logger) {
			$newPlayer = new Player($s, Player::generatePlayerID(), $socket);
			$game->addPlayer($newPlayer);

			//$welcomeMessage = new WelcomePlayerMessage($newPlayer->ID);
			//$s->sendMessage($socket, $welcomeMessage->serialize());
		},
			'onClientDisconnect' => function ($s, $socket) use ($logger, &$game) {
				$ip = $s->getIPAddress($socket);
				$logger->logServerAction("Client disconnected: " . $ip);
				$player = $game->getPlayerFromSocket($socket);
				$game->removePlayer($player);
			}, 'onMessage' => function ($s, $message, $socket) use (&$game) {
				$ip = $s->getIPAddress($socket);
				echo sprintf("[%s] %s" . PHP_EOL, $ip, $message);
				$s->sendMessage($socket, "Thanks for the message");
			});

$server = new WebSocketServer($address, $port, $hooks);

?>
