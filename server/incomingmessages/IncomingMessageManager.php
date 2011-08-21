<?php

include 'iIncomingMessageHandler.php';
include 'IncomingMessageHandler.php';

include 'IntrodutionMessageHandler.php';
include 'ChatMessageHandler.php';
include 'ReadySignalHandler.php';

class IncomingMessageManager {

	private $handlers = array();
	private $server;
	private $game;

	public function __construct($game) {
		$this->game = $game;
	}

	public function start($server) {
		$this->server = $server;

		$this->handlers["introduction"] = new IntrodutionMessageHandler($server, $this->game);
		$this->handlers["chat"] = new ChatMessageHandler($server, $this->game);
		$this->handlers["ready"] = new ReadySignalHandler($server, $this->game);
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
