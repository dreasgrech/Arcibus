<?php
/*
 * A user is a client who has succesfully registered using his name in the server.
 */
class User {
	public $socket;
	public $ID;
	public $nick;
	public $ready = false;
	public $inGame = false;

	public function __construct($socket, $ID, $nick) {
		$this->socket = $socket;
		$this->ID = $ID;
		$this->nick = $nick;
	}

	public static function generateUserID() {
		return User::getGUID();
	}

	private static function getGUID() {
		return trim(com_create_guid(), '{}');
	}
}
?>
