<?php
/*
 * This is the first message the client sends (after the handshake)
 *
 * The player introduces himself in this message with his nickname, and then 
 * a Player instance is created and added to game.
 *
 */
class IntrodutionMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$sanitizedNick = strip_tags($message->nick); // strip HTML from the input (because of the evil users)
		$newPlayer = new Player($this->server, $socket, Player::generatePlayerID(), $sanitizedNick);
		$this->game->addPlayer($newPlayer);

		$welcomeMessage = new WelcomePlayerMessage($this->game, $newPlayer);
		$socket->send($welcomeMessage);

		$playerListMessage = new PlayerListMessage($this->game->players);
		$this->server->broadcast($playerListMessage);

	}
}
?>
