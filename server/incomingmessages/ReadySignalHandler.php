<?php
class ReadySignalHandler extends IncomingMessageHandler implements iIncomingMessageHandler{

	public function handle($socket, $message) {
		$user = $this->userList->getUserFromSocket($socket);
		$user->ready = true;

		$userListMessage = new UserListMessage($this->userList->users);
		$this->server->broadcast($userListMessage);
	}
}
?>
