<?php
class Player {
	public $socket;
	public $position;

	private $user;

	public function __construct($user) {
		$this->socket = $user->socket;
		$this->user = $user;
	}

	public function sendMessage($message) {
		$this->user->socket->send($message);
	}

	/*
	public function getNick() {
		return $this->user->nick;
	}
	 */
}
?>
