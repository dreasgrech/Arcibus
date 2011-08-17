<?php
class Game {
	private $players = array();
	public function __construct() {

	}

	public function addPlayer($player) {
		$this->players[$player->socket] = $player;

		$welcomeMessage = new WelcomePlayerMessage($player->ID);
		$player->sendMessage($welcomeMessage);
	}

	public function removePlayer($player) {
		unset($this->players[$player->socket]);
	}

	public function handleMessage($message) {

	}

	public function getPlayerFromSocket($socket) {
		return $this->players[$socket];
	}
}
?>
