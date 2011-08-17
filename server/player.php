<?php
class Player {
	public $ID;

	public function __construct($id) {
		$this->ID = $id;
	}

	public static function generatePlayerID() {
		return Player::getGUID();
	}

	private static function getGUID() {
		return trim(com_create_guid(), '{}');
	}
}
?>
