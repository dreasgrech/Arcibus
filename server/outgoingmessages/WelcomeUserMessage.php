<?php
class WelcomeUserMessage extends OutgoingMessage {
	public $user;

	public function __construct($user) {
		parent::__construct("welcome");
		$this->user = $user;
	}

	public function __toString() {
		return $this->manuallyConstructMessage(array(
			"ID"=>$this->user->ID,
			"nick"=>$this->user->nick
		));
	}
}
?>
