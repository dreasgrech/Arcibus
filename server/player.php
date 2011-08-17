<?php
class Player {
	public $server;
	public $ID;
	public $socket;

	public function __construct($server, $id, $socket) {
		$this->server = $server;
		$this->ID = $id;
		$this->socket = $socket;
	}

	public function sendMessage($message) {
		$this->server->sendMessage($this->socket, $message->serialize());
	}

	public static function generatePlayerID() {
		return Player::getGUID();
	}

	private static function getGUID() {
		return trim(com_create_guid(), '{}');
	}


}
?>
