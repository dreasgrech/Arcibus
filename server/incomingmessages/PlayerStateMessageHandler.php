<?php
class PlayerStateMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromSocket($socket);
		$player->position = $message->position;
		var_dump($message->position);
	}
}
?>
