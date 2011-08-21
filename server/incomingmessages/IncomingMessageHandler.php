<?php
class IncomingMessageHandler {
	protected $server;
	protected $game;
	protected $userList;

	public function __construct($server, $game, $userList) {
		$this->server = $server;
		$this->game = $game;
		$this->userList = $userList;
	}
}
?>
