<?php
error_reporting(E_ALL);

include 'socketserver/websocketserver.php';
include 'JSONConstruction.php';
include 'StringUtils.php';
include 'logger.php';
include 'player.php';
include 'outgoingmessages/OutgoingMessage.php';
include 'outgoingmessages/WelcomeUserMessage.php';
include 'outgoingmessages/ChatMessage.php';
include 'outgoingmessages/UserListMessage.php';
include 'outgoingmessages/StartGameMessage.php';
include 'outgoingmessages/WorldSnapshotMessage.php';
include 'incomingmessages/incomingmessagemanager.php';
include 'vector2.php';
include 'game.php';
include 'user.php';
include 'UserList.php';

class Timer {

	public function hasMillisecondsPassedSince($secondsPassed, $since) {
		$now = $this->nowInMilliseconds();
		//echo $now - $since . PHP_EOL;
		//echo "$now $since $secondsPassed" . PHP_EOL;
		return ($now - $since) >= $secondsPassed;
	}

	public function nowInMilliseconds() {
		return round(microtime(true) * 1000);
	}
}


$timer = new Timer();
$lastSnapshot = $timer->nowInMilliseconds();

$address = '192.168.1.5';
$port = 8000;

$logger = new Logger();
$users = new UserList();

$game = new Game();
$messageHandler = new IncomingMessageManager(&$game, &$users);

$hooks = array(
	'onClientConnect' => function ($s, $socket) use ($logger) {
		$ip = $s->getIPAddress($socket);
		$logger->logServerAction("New client connected: " . $ip);
	}, 
		'onClientHandshaked' => function ($s, $socket) use (&$game, &$users, $logger) { // At this point, the client is not a user yet
			$ip = $s->getIPAddress($socket);
			$logger->logServerAction("Handshaked with " . $ip);

			$userListMessage = new UserListMessage($users->users);
			$socket->send($userListMessage);
		},
			'onClientDisconnect' => function ($s, $socket) use (&$game, &$users, $logger) {
				//$ip = $s->getIPAddress($socket);
				//$logger->logServerAction("Client disconnected: " . $ip);
				if ($users->isUser($socket)) {
					$user = $users->getUserFromSocket($socket);
					$users->removeUser($user);

					$userListMessage = new UserListMessage($users->users);
					$s->broadcast($userListMessage);
				}

				if ($game->isPlayer($socket)) {
					$player = $game->getPlayerFromSocket($socket);
					$game->removePlayer($player);
				}

			}, 'onMessage' => function ($s, $message, $socket) use (&$messageHandler, $logger) {
				$data = json_decode($message);
				$ip = $s->getIPAddress($socket);
				//$logger->logMessageRecieved($ip, $message);
				echo $message . PHP_EOL;
				$messageHandler->handleMessage($socket, $data);
			},
				'onIteration' => function ($s) use (&$game, &$users, $logger, $timer, &$lastSnapshot) {
					$available = $users->getReadyUsers(4);
					if (!$game->isInProgress && count($available) > 0) { // Ready to start the game because a game is not in progress and there are enough players to start a game
						$logger->logServerAction("Starting the game");

						$game->start($available);

						$userListMessage = new UserListMessage($users->users);
						$s->broadcast($userListMessage);
					}
/*
						if ($timer->hasSecondsPassedSince(500, $lastSnapshot)) {
							echo "hello" . PHP_EOL;
							$lastSnapshot = $timer->nowInMilliseconds();
						}
*/

					if ($game->isInProgress) {
						if ($timer->hasMillisecondsPassedSince(500, $lastSnapshot)) {
							//$logger->logServerAction("SNAPSHOT");
							$lastSnapshot = $timer->nowInMilliseconds();
							$snapshot = $game->createSnapshot();
							$game->iteratePlayers(function ($player) use ($snapshot) {
								$player->sendMessage($snapshot);
							});
						}
					}
				});
$server = new WebSocketServer($address, $port, $hooks); 
$messageHandler->start(&$server);

$server->startListening(); //This needs to be the last because of the blocking loop
?>

