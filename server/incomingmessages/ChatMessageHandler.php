<?php

class ChatMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	private $game;

	public function __construct($server, $game) {
		parent::__construct($server);
		$this->game = $game;
	}

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromID($message->ID);
		$chatMessage = strip_tags($message->chat); // Sanitize the chat message to remove html tags evil users WILL send.
		$chatMessage = new ChatMessage($player->nick, $chatMessage);
		$this->server->broadcast($chatMessage);
	}
}
?>
