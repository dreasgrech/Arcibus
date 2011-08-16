<?php
class WebSocketClient {
	public $handshaked;
	public $socket;

	public function __construct($socket) {
		$this->handshaked = false;
		$this->socket = $socket;
	}
}
?>
