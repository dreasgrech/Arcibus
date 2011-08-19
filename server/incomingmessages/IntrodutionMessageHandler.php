<?php
/*
 * This is the first message the client sends (after the handshake)
 *
 * The player introduces himself in this message with his nickname, and then 
 * a Player instance is created and added to game.
 *
 */
class IntrodutionMessageHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	private $game;

	public function __construct($server, $game) {
		parent::__construct($server);
		$this->game = $game;
	}

	public function handle($socket, $message) {
		$newPlayer = new Player($this->server, $socket, Player::generatePlayerID(), $message->nick);
		$this->game->addPlayer($newPlayer);

		//$welcomeMessage = new WelcomePlayerMessage($this->game, $newPlayer->ID);
		//$socket->send($welcomeMessage->serialize());

		//$pl = new PlayerListMessage($this->game->players);
		//$socket->send($pl->serialize());
	}
}
?>
