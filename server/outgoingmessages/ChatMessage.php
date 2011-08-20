<?php
class ChatMessage extends OutgoingMessage {

	public $nick;
	public $chatMessage;

	public function __construct($nick, $chatMessage) {
		parent::__construct("chat");
		$this->nick = $nick;
		$this->chatMessage = $chatMessage;
	}
}
?>
