<?php
class Game {
	public $players = array();
	public function __construct() {

	}

	public function addPlayer($player) {
		$this->players[$player->socket->socket] = $player;

	}

	public function removePlayer($player) {
		unset($this->players[$player->socket->socket]);
	}

	public function isPlayer($socket) {
		return isset($this->players[$socket->socket]);
	}

	public function getPlayerFromSocket($socket) {
		echo "Players: ".count($this->players).PHP_EOL;
		return $this->players[$socket->socket];
	}

	// Sends a message to every player
	public function broadcast($message) {
		foreach($this->players as $player) {
			$player->sendMessage($message);
		}
		unset($player);
	}

	public function broadcastExcept($message, $exceptPlayer) {
		foreach($this->players as $player) {
			if ($player == $exceptPlayer) {
				continue;
			}

			$player->sendMessage($message);
		}
		unset($player);
	}
}
?>
