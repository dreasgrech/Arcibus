<?php
class StartGameMessage extends OutgoingMessage {
	private $game;
	private $currentPlayerNumber;
	private $playerTurningPoint;

	public function __construct($game, $currentPlayerNumber, $playerTurningPoint) {
		parent::__construct("startgame");
		$this->game = $game;
		$this->currentPlayerNumber = $currentPlayerNumber;
		$this->playerTurningPoint = $playerTurningPoint;
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
		$turningPoint = JSONConstruction::constructJSONObject(array(
			"x"=>$this->playerTurningPoint->x,
			"y"=>$this->playerTurningPoint->y
		));

		return $this->manuallyConstructMessage(array(
			"playerNumber" => $this->currentPlayerNumber,
			"playerTurningPoint" => $turningPoint,
			"players" => $players
		));
	}
}
?>
