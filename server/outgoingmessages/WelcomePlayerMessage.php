<?php
class WelcomePlayerMessage extends OutgoingMessage {
	public $playerID;

	public function __construct($playerID) {
		parent::__construct("welcome");
		$this->playerID = $playerID;
	}
}
?>
