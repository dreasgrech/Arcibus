<?php
include 'socketserver.php';
include 'websocketclient.php';
include 'handshake.php';

/*
 * This class contains an extra hook 'onClientHandshaked' which fires upon
 * a handshake is sucessfully sent to the client
 */
class WebSocketServer extends SocketServer {

	private $webSocketClients = array(); // holds a collection of WebSocketClient instances, and is needed to manage the handshake flag.

	public function __construct($address = 'localhost', $port = 8080, $hooks) {
		$webSocketClients = &$this->webSocketClients;

		$that = $this; // Since $this can't be used as a lexical variable, you need to do this workaround (https://bugs.php.net/bug.php?id=49543)
		$overriddenHooks = array(
			'onClientConnect' => function ($parent, $socket) use (&$webSocketClients, $hooks, &$that) {
				$webSocketClients[$socket] = new WebSocketClient($that, $socket);
				if ($hook = $hooks['onClientConnect']) {
					$hook($that, $webSocketClients[$socket]);
				}
			},
				'onMessage' => function ($parent, $message, $socket) use (&$webSocketClients, $hooks, &$that) {
					/*
					 * The first time a client sends a message, we must do the handshake.
					 */
					if ($webSocketClients[$socket]->handshaked && ($hook = $hooks['onMessage'])) {
						// Pass the message to the original onMessage callback that was passed in
						$message = $that->unwrapIncomingMessage($message);
						$hook($that, $message, $webSocketClients[$socket]);
						return;
					}

					//Do the handshake
					$handShakeObj = new Handshake($message);
					socket_write($socket, $handShakeObj->getHandshake()); 
					$webSocketClients[$socket]->handshaked = true;
					if ($hook = $hooks['onClientHandshaked']) {
						$hook($that, $webSocketClients[$socket]);
					}
				},
					'onClientDisconnect' => function ($parent, $socket) use (&$webSocketClients, $hooks, &$that) {
						$webSocket = $webSocketClients[$socket];
						unset($webSocketClients[$socket]);
						if ($hook = $hooks['onClientDisconnect']) {
							$hook($that, $webSocket);
						}
					},
						'onIteration' => function ($parent) use (&$that, $hooks) {
							if ($hook = $hooks['onIteration']) {
								$hook($that);
							}
						}
		);

		parent::__construct($address, $port, $overriddenHooks);
	}

	public function sendMessage($socket, $message) {
		/*
		 * This method is overridden because we need to wrap the message with 
		 * ASCII characters 0 (NULL) and 255 before transmitting it
		 */
		parent::sendMessage($socket->socket, $this->wrapOutgoingMessage($message));
	}

	public function broadcast($message) {
		foreach($this->webSocketClients as $webSocketClient) {
			$this->sendMessage($webSocketClient, $message);
		}
	}

	/*
	 * Overriden to pass in the socket resource to the server
	 */
	public function getIPAddress($socket) {
		return parent::getIPAddress($socket->socket);
	}

	private function wrapOutgoingMessage($message) {
		return chr(0) . $message . chr(255);
	}

	/*
	 * The reason this method is public is because I'm passing $this (in the form of $that) to
	 * a closure in the constructor because I'm using it upon recieving a message and from $that,
	 * I can only access public members -_-
	 */
	public function unwrapIncomingMessage($message) {
		return substr($message, 1, strlen($message) - 2);
	}
}
?>
