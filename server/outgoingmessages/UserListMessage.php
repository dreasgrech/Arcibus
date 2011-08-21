<?php
class UserListMessage extends OutgoingMessage {
	public $users;

	public function __construct($users) {
		parent::__construct("userlist");
		$this->users = $users;
	}

	public function __toString() {
		$users = JSONConstruction::constructJSONArray($this->users, function ($key, $value) {
			$jsonObj = JSONConstruction::constructJSONObject(array(
				"nick" => $value->nick,
				"ready" => $value->ready,
				"inGame" => $value->inGame
				));
			return $jsonObj;
		});

		return $this->manuallyConstructMessage(array(
			"users" => $users
		));
	}
}
?>
