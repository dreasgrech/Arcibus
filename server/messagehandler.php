<?php
interface iIncomingMessageHandler {
	public function handle($socket, $message);
}

class IncomingMessageHandler {
	protected $server;

	public function __construct($server) {
		$this->server = $server;
	}
}

class IntrodutionMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	private $game;

	public function __construct($server, $game) {
		parent::__construct($server);
		$this->game = $game;
	}

	public function handle($socket, $message) {
		$socket->send("Hello " . count($this->game->players));
	}
}

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
