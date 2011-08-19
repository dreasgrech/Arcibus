<?php
class PlayerListMessage extends OutgoingMessage {
	public $playerList;

	public function __construct($list) {
		parent::__construct("playerlist");
		$this->playerList = $list;
	}

	public function serialize() { 
		$that = $this;
		$players = $this->constructJSONArray($this->playerList,  function ($key, $value) use ($that) {
			$player = $key;
			return $that->constructJSONObject(array("nick"=>$player->nick));
		});
		echo $players;
		return $this->manuallyConstructMessage(array("playerList"=>$players));
	}
}
?>
