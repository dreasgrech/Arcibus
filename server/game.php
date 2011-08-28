<?php
class PlayerStartData {
	public $position;
	public $turningPoint;
	public function __construct($position, $turningPoint) {
		$this->position = $position;
		$this->turningPoint = $turningPoint;
	}

}

class Game {
	public $players = array();
	public $ingamePlayers = array();
	public $isInProgress;
	private $startingPositions = array();

	public function __construct() {
		$this->isInProgress = false;
		$this->startingPositions[] = new PlayerStartData(new Vector2(320, 270), new Vector2(360, 270)); // Player 1
		$this->startingPositions[] = new PlayerStartData(new Vector2(481, 270), new Vector2(441, 270)); // Player 2
		$this->startingPositions[] = new PlayerStartData(new Vector2(320, 335), new Vector2(360, 335)); // Player 3
		$this->startingPositions[] = new PlayerStartData(new Vector2(481, 335), new Vector2(441, 335)); // Player 4
	}

	public function start($users) {
		for ($i = 0; $i < count($users); ++$i) {
			$user = $users[$i];
			$newPlayer = new Player($user, $i + 1, $this->startingPositions[$i]->position, $this->startingPositions[$i]->turningPoint);
			$this->addPlayer($newPlayer);
			$user->inGame = true;
		}

		$that = $this;
		$this->iteratePlayers(function ($player) use($that) {
			$startMessage = new StartGameMessage($that, $player->number, $player->turningPoint);
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

	public function handleUserCMD($player, $data) {
		$player->handleUserKeys($data->keys);
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
