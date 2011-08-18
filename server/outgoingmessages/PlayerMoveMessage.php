<?php
class PlayerMoveMessage extends OutgoingMessage {
	public $playerID;
	public $x;
	public $y;

	public function __construct($playerID, $x, $y) {
		parent::__construct("playermove");
		$this->playerID = $playerID;
		$this->x = $x;
		$this->y = $y;
	}
}
?>
