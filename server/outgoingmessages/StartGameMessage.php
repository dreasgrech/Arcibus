<?php
class StartGameMessage extends OutgoingMessage {
	public function __construct() {
		parent::__construct("startgame");
	}
}
?>
