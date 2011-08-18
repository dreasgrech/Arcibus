<?php
class PlayerListMessage extends OutgoingMessage {
	public $playerList;

	public function __construct($list) {
		parent::__construct("playerlist");
		$this->playerlist = $list;
	}

	public function serialize() { 
		return $this->manuallyConstructMessage(array("list"=>count($this->playerlist)));
	}
}
?>
