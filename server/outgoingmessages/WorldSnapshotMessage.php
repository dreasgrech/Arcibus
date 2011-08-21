<?php
class WorldSnapshotMessage extends OutgoingMessage {
	private $game;

	public function __construct($game) {
		$this->game = $game;
	}

	public function __toString() {

	}
}
?>
