<?php
class WebSocketClient {
	public $server;
	public $handshaked;
	public $socket;

	public function __construct($server, $socket) {
		$this->server = $server;
		$this->handshaked = false;
		$this->socket = $socket;
	}

	public function send($message) {
		return $this->server->sendMessage($this->socket, $message);
	}
}
?>
