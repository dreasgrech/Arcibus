<?php
class Game {
	public $players = array();
	public $ingamePlayers = array();
	public $isInProgress;

	public function __construct() {
		$this->isInProgress = false;
	}

	public function start($users) {
		foreach($users as $user) {
			$position = new Vector2(rand(10,400), rand(10,400));
			$newPlayer = new Player($user, $position);
			$this->addPlayer($newPlayer);
			$user->inGame = true;
		}
		unset($user);

		$startMessage = new StartGameMessage($this);
		$this->iteratePlayers(function ($player) use($startMessage) {
			$player->sendMessage($startMessage);
		});

		$this->isInProgress = true;
	}

	public function handlePlayerMoved($player, $direction) {
		if ($direction === "left") {
			$player->moveLeft();
		} else {
			$player->moveRight();
		}
	}

	public function createSnapshot() {
		$snapshotMessage = new WorldSnapshotMessage($this);
		return $snapshotMessage;
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

	public function iteratePlayers($callback) {
		foreach($this->players as $player) {
			$callback($player);
		}

		unset($player);
	}
}
?>
