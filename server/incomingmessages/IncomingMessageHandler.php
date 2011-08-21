<?php
class IncomingMessageHandler {
	protected $server;
	protected $game;

	public function __construct($server, $game) {
		$this->server = $server;
		$this->game = $game;
	}
}
?>
