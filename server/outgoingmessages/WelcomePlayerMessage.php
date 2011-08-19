<?php
class WelcomePlayerMessage extends OutgoingMessage {
	public $game;
	public $player;

	public function __construct($game, $player) {
		parent::__construct("welcome");
		$this->game = $game;
		$this->player = $player;
	}

	public function serialize() {
		return $this->manuallyConstructMessage(array("ID"=>$this->player->ID, "nick"=>$this->player->nick));
	}
}
?>
