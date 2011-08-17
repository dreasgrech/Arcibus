<?php
class Message {
	public $action;

	public function __construct($action) {
		$this->action = $action;
	}

	public function serialize() {
		return json_encode(get_object_vars($this));
	}
}

class PlayerMoveMessage extends Message {
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

class WelcomePlayerMessage extends Message {
	public $playerID;

	public function __construct($playerID) {
		parent::__construct("welcome");
		$this->playerID = $playerID;
	}
}

class PlayerListMessage extends Message {
	public $list;

	public function __construct($list) {
		parent::__construct("playerlist");
		$this->list = $list;
	}
}
?>
