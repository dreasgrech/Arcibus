<?php
class PlayerListMessage extends OutgoingMessage {
	public $playerList;

	public function __construct($list) {
		parent::__construct("playerlist");
		$this->playerList = $list;
	}

	public function __toString() { 
		$players = JSONConstruction::constructJSONArray($this->playerList,  function ($key, $value) {
			return JSONConstruction::constructJSONObject(array("nick"=>$value->nick));
		});
		return $this->manuallyConstructMessage(array("playerList"=>$players));
	}
}
?>
