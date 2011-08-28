<?php
class Player {
	public $socket;
	public $number;
	public $turningPoint;
	public $position;
	public $velocity;

	private $user;
	public $ID;
	public $nick;

	public function __construct($user, $number, $position, $turningPoint) {
		$this->socket = $user->socket;
		$this->number = $number;
		$this->user = $user;
		$this->ID = $user->ID;
		$this->nick = $user->nick;
		$this->position = $position;
		$this->turningPoint = $turningPoint;
		$this->velocity = new Vector2(0,0);
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

	public function handleUserKeys($pressedKeys) {
		//TODO: work on this
		$left = $this->isKeyPressed($pressedKeys, 'left');
	       	$right = $this->isKeyPressed($pressedKeys, 'right');
	}

	private $keys = array('left'=>1, 'right'=>2);
	private function isKeyPressed($pressedKeys, $keyName) {
		return ($pressedKeys & $this->keys[$keyName]) === $this->keys[$keyName];
	}
}
?>
