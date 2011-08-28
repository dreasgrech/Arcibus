<?php

include 'iIncomingMessageHandler.php';
include 'IncomingMessageHandler.php';

include 'IntrodutionMessageHandler.php';
include 'ChatMessageHandler.php';
include 'ReadySignalHandler.php';
include 'PlayerMovedMessageHandler.php';
include 'PlayerStateMessageHandler.php';

class IncomingMessageManager {

	private $handlers = array();
	private $server;
	private $game;
	private $userList;

	public function __construct($game, $userList) {
		$this->game = $game;
		$this->userList = $userList;
	}

	public function start($server) {
		$this->server = $server;

		$this->handlers["introduction"] = new IntrodutionMessageHandler($server, $this->game, $this->userList);
		$this->handlers["chat"] = new ChatMessageHandler($server, $this->game, $this->userList);
		$this->handlers["ready"] = new ReadySignalHandler($server, $this->game, $this->userList);
		$this->handlers["moved"] = new PlayerMovedMessageHandler($server, $this->game, $this->userList);
		$this->handlers["usercmd"] = new PlayerStateMessageHandler($server, $this->game, $this->userList);

	}

	public function handleMessage($socket, $message) {
		if (isset($this->handlers[$message->action])) {
			$this->handlers[$message->action]->handle($socket, $message);
		} else {
			echo "Invalid action => " . $message->action . PHP_EOL; // TODO: use logger
		}
	}
}
?>
