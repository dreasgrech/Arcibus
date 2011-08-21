<?php
class StartGameMessage extends OutgoingMessage {
	private $game;

	public function __construct($game) {
		parent::__construct("startgame");
		$this->game = $game;
	}

	public function __toString() {
		$players = JSONConstruction::constructJSONArray($this->game->players, function ($key, $value) {
			$position = JSONConstruction::constructJSONObject(array(
				"x"=>$value->position->x,
				"y"=>$value->position->y
			));

			$player = JSONConstruction::constructJSONObject(array(
				"ID"=>$value->ID,
				"nick"=>$value->nick,
				"position"=>$position
				));
			return $player;
		});

		return $this->manuallyConstructMessage(array(
			"players" => $players
		));
	}
}
?>
