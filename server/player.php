<?php
class Player {
	public $socket;
	public $position;

	private $user;
	public $ID;
	public $nick;

	public function __construct($user, $position) {
		$this->socket = $user->socket;
		$this->user = $user;
		$this->ID = $user->ID;
		$this->nick = $user->nick;
		$this->position = $position;
	}

	public function sendMessage($message) {
		$this->user->socket->send($message);
	}

	public function getNick() {
		return $this->user->nick;
	}

	public function moveLeft() {
		$this->position->x -= 1;
	}

	public function moveRight() {
		$this->position->x += 1;
	}
}
?>
