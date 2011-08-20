<?php
class Game {
	public $players = array();
	public $isInProgress;

	public function __construct() {
		$this->isInProgress = false;
	}

	public function start() {
		$this->isInProgress = true;
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

	public function getPlayerFromID($id) {
		foreach($this->players as $player) {
			if (strcmp($player->ID, $id) >= 0) {
				return $player;
			}
		}
		unset($player);
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
