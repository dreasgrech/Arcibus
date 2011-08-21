<?php

class ChatMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromSocket($socket);
		$chatMessage = strip_tags($message->chat); // Sanitize the chat message to remove html tags evil users WILL send.
		$chatMessage = new ChatMessage($player->nick, $chatMessage);
		$this->server->broadcast($chatMessage);
	}
}
?>
