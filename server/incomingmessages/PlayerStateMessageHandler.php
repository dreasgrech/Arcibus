<?php
class PlayerStateMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromSocket($socket);
		$this->game->handleUserCMD($player, $message);

		//$player->position = $message->position;
		//$player->velocity = $message->velocity;
	}
}
?>
