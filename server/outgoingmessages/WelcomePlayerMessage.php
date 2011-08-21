<?php
class WelcomePlayerMessage extends OutgoingMessage {
	public $game;
	public $user;

	public function __construct($game, $user) {
		parent::__construct("welcome");
		$this->game = $game;
		$this->user = $user;
	}

	public function __toString() {
		return $this->manuallyConstructMessage(array("ID"=>$this->user->ID, "nick"=>$this->user->nick));
	}
}
?>
