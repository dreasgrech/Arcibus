<?php
class UserList {
	public $users = array();

	public function addUser($socket, $ID, $nick) {
		$newUser = new User($socket, $ID, $nick);
		$this->users[$socket->socket] = $newUser;
		return $newUser;
	}

	public function getUserFromSocket($socket) {
		return $this->users[$socket->socket];
	}

	public function removeUser($user) {
		unset($this->users[$user->socket->socket]);
	}

	public function isUser($socket) {
		return isset($this->users[$socket->socket]);
	}

	public function getReadyUsers($number) {
		$availableUsers = $this->getAvailableUsers();
		$readyUsers = array();
		if ($number > count($availableUsers)) {
			return NULL; // There aren't enough players for a game at this point
		}

		for ($i = 0; $i < $number; $i++) {
			$readyUsers[] = &$availableUsers[$i];
		}

		return $readyUsers;
	}

	private function getAvailableUsers() {
		$available = array();
		$this->iterateUsers(function($user) use (&$available) {
			if (!$user->inGame && $user->ready) {
				$available[] = &$user;
			}
		});

		return $available;
	}

	private function iterateUsers($callback) {
		foreach($this->users as $user) {
			$callback($user);
		}

		unset($user);
	}
}
?>
