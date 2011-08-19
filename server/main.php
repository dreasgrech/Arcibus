<?php
error_reporting(E_ALL);

/*function __autoload($className){
	echo $className;
	require_once $className.'.php';
}*/

include 'socketserver/websocketserver.php';
include 'logger.php';
include 'player.php';
include 'outgoingmessages/OutgoingMessage.php';
include 'outgoingmessages/WelcomePlayerMessage.php';
include 'outgoingmessages/PlayerListMessage.php';
include 'vector2.php';
include 'incomingmessages/incomingmessagemanager.php';
include 'game.php';


$address = '192.168.1.5';
$port = 8000;

$logger = new Logger();
$game = new Game();

$messageHandler = NULL;

$hooks = array(
	'onClientConnect' => function ($s, $socket) use ($logger) {
		$ip = $s->getIPAddress($socket);
		$logger->logServerAction("New client connected: " . $ip);
	}, 
		'onClientHandshaked' => function ($s, $socket) use (&$game, $logger) {
			$ip = $s->getIPAddress($socket);
			$logger->logServerAction("Successfully handshaked with " . $ip);

			$playerListMessage = new PlayerListMessage($game->players);
			$socket->send($playerListMessage->serialize());
		},
			'onClientDisconnect' => function ($s, $socket) use ($logger, &$game) {
				//$ip = $s->getIPAddress($socket);
				//$logger->logServerAction("Client disconnected: " . $ip);
				//$player = $game->getPlayerFromSocket($socket);
				//$game->removePlayer($player);
			}, 'onMessage' => function ($s, $message, $socket) use (&$game, &$messageHandler) {
				$data = json_decode($message);
				$ip = $s->getIPAddress($socket);
				echo sprintf("[%s] %s" . PHP_EOL, $ip, $message);
				$messageHandler->handleMessage($socket, $data);
			});
$server = NULL;
$messageHandler = new IncomingMessageManager(&$server, &$game);
$server = new WebSocketServer($address, $port, $hooks); //This needs to be the last because of the blocking loop
?>
