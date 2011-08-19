<?php
class WelcomePlayerMessage extends OutgoingMessage {
	public $game;
	public $playerID;

	public function __construct($game, $playerID) {
		parent::__construct("welcome");
		$this->game = $game;
		$this->playerID = $playerID;
	}

	public function serialize() {
		return $this->manuallyConstructMessage(array("ID"=>$this->playerID));
	}
}
?>
