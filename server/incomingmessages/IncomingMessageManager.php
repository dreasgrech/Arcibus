<?php

include 'iIncomingMessageHandler.php';
include 'IncomingMessageHandler.php';
include 'IntrodutionMessageHandler.php';

class IncomingMessageManager {

	private $handlers = array();
	private $server;

	public function __construct($server, $game) {
		$this->server = $server;
		$this->handlers["introduction"] = new IntrodutionMessageHandler($server, $game);
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
