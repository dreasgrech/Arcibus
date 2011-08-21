<?php
class PlayerMovedMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromSocket($socket);
		$this->game->handlePlayerMoved($player, $message->direction);
	}
}
?>
