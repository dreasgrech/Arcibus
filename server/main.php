<?php
error_reporting(E_ALL);

/*function __autoload($className){
	echo $className;
	require_once $className.'.php';
}*/

include 'socketserver/websocketserver.php';
include 'JSONConstruction.php';
include 'StringUtils.php';
include 'logger.php';
include 'player.php';
include 'outgoingmessages/OutgoingMessage.php';
include 'outgoingmessages/WelcomePlayerMessage.php';
include 'outgoingmessages/ChatMessage.php';
include 'outgoingmessages/PlayerListMessage.php';
include 'outgoingmessages/StartGameMessage.php';
include 'incomingmessages/incomingmessagemanager.php';
include 'vector2.php';
include 'game.php';

$address = '192.168.1.5';
$port = 8000;

$logger = new Logger();
$game = new Game();

$messageHandler = new IncomingMessageManager(&$game);

$hooks = array(
	'onClientConnect' => function ($s, $socket) use ($logger) {
		$ip = $s->getIPAddress($socket);
		$logger->logServerAction("New client connected: " . $ip);
	}, 
		'onClientHandshaked' => function ($s, $socket) use (&$game, $logger) { // At this point, the client is not a player yet
			$ip = $s->getIPAddress($socket);
			$logger->logServerAction("Handshaked with " . $ip);

			$playerListMessage = new PlayerListMessage($game->players);
			$socket->send($playerListMessage);
		},
			'onClientDisconnect' => function ($s, $socket) use ($logger, &$game) {
				//$ip = $s->getIPAddress($socket);
				//$logger->logServerAction("Client disconnected: " . $ip);
				if ($game->isPlayer($socket)) {
					$player = $game->getPlayerFromSocket($socket);
					$game->removePlayer($player);

					$playerListMessage = new PlayerListMessage($game->players);
					$s->broadcast($playerListMessage);
				}
			}, 'onMessage' => function ($s, $message, $socket) use (&$game, &$messageHandler, $logger) {
				$data = json_decode($message);
				$ip = $s->getIPAddress($socket);
				$logger->logMessageRecieved($ip, $message);
				$messageHandler->handleMessage($socket, $data);
			},
				'onIteration' => function ($s) use (&$game, $logger) {
					if ($game->numberOfReadyPlayers() == 4 && !$game->isInProgress) {
						$logger->logServerAction("Starting the game");
						$startMessage = new StartGameMessage();
						$s->broadcast($startMessage);
						$game->start();
					}
				});
$server = new WebSocketServer($address, $port, $hooks); 
$messageHandler->start(&$server);

$server->startListening(); //This needs to be the last because of the blocking loop
?>

