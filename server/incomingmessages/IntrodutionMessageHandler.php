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
		$newUser = $this->userList->addUser($socket, User::generateUserID(), $sanitizedNick);

		$welcomeMessage = new WelcomePlayerMessage($this->game, $newUser);
		$socket->send($welcomeMessage);

		$userListMessage = new UserListMessage($this->userList->users);
		$this->server->broadcast($userListMessage);

	}
}
?>
