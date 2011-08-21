<?php
class ReadySignalHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$player = $this->game->getPlayerFromSocket($socket);
		$player->ready = true;

		$playerListMessage = new PlayerListMessage($this->game->players);
		$this->server->broadcast($playerListMessage);
	}
}
?>
