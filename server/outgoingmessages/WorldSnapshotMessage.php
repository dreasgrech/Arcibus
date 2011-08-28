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

			$velocity = JSONConstruction::constructJSONObject(array(
				"x"=>$player->velocity->x,
				"y"=>$player->velocity->y
			));

			$list[$player->ID] = JSONConstruction::constructJSONObject(array("ID" => $player->ID, "position" => $position, "velocity" => $velocity));
		}

		unset($player);

		$players = JSONConstruction::constructJSONObject($list);

		return $this->manuallyConstructMessage(array(
			"players" => $players
		));
	}
}
?>
