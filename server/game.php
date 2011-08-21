<?php
class Game {
	public $players = array();
	public $ingamePlayers = array();
	public $isInProgress;

	public function __construct() {
		$this->isInProgress = false;
	}

	public function start($users) {
		$startMessage = new StartGameMessage();
		foreach($users as $user) {
			$newPlayer = new Player($user);
			$this->addPlayer($newPlayer);
			$user->inGame = true;

			$newPlayer->sendMessage($startMessage);
		}

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
		return $this->players[$socket->socket];
	}

	public function getPlayerFromID($id) {
		foreach($this->players as $player) {
			if ($player->ID == $id) {
				return $player;
			}
		}
		unset($player);
	}

	// Sends a message to every player
	public function broadcast($message) {
		$this->iteratePlayers(function ($player) use ($message) {
			$player->sendMessage($message);
		});
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

	private function iteratePlayers($callback) {
		foreach($this->players as $player) {
			$callback($player);
		}

		unset($player);
	}
}
?>
