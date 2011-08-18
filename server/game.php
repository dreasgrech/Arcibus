<?php
class Game {
	public $players = array();
	public function __construct() {

	}

	public function addPlayer($player) {
		$this->players[$player->socket->socket] = $player;

		$welcomeMessage = new WelcomePlayerMessage($player->ID);
		$player->sendMessage($welcomeMessage);
		$playerListMessage = new PlayerListMessage($this->players);
	}

	public function removePlayer($player) {
		unset($this->players[$player->socket->socket]);
	}

	public function handleMessage($message) {

	}

	public function getPlayerFromSocket($socket) {
		return $this->players[$socket->socket];
	}
}
?>
