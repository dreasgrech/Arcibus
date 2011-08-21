<?php
class WorldSnapshotMessage extends OutgoingMessage {
	private $game;

	public function __construct($game) {
		parent::__construct("snapshot");
		$this->game = $game;
	}

	public function __toString() {
		$list = array();
		foreach($this->game->players as $player) {
			$position = JSONConstruction::constructJSONObject(array(
				"x"=>$player->position->x,
				"y"=>$player->position->y
			));

			$list[$player->ID] = JSONConstruction::constructJSONObject(array("ID" => $player->ID, "position" => $position));
		}

		unset($player);

		$players = JSONConstruction::constructJSONObject($list);

		/*
		$players = JSONConstruction::constructJSONArray($this->game->players,  function ($key, $value) {
			$position = JSONConstruction::constructJSONObject(array(
				"x"=>$value->position->x,
				"y"=>$value->position->y
			));
			return JSONConstruction::constructJSONObject(array(
				"ID" => $value->ID,
				"position" => $position
			));
		});

		 */

		return $this->manuallyConstructMessage(array(
			"players" => $players
		));
	}
}
?>
