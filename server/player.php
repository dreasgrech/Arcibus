<?php
class Player {
	public $server;
	public $ID;
	public $socket;

	public $nick;
	public $position;

	public function __construct($server, $socket, $id, $nick) {
		$this->server = $server;
		$this->socket = $socket;
		$this->ID = $id;
		$this->nick = $nick;
	}

	public function sendMessage($message) {
		$this->socket->send($message->serialize());
	}

	public static function generatePlayerID() {
		return Player::getGUID();
	}

	private static function getGUID() {
		return trim(com_create_guid(), '{}');
	}


}
?>
