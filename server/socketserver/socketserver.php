<?php

class SocketServer {
	private $address, $port;

	private $masterSocket;
	private $sockets = array();

	private $onClientConnect, $onClientDisconnect;
	protected $hooks;

	public function __construct($address = 'localhost', $port = 8080, $hooks) {
		/*
		 * Available hooks:
		 * * onClientConnect($this, $socket) => when a new client connects
		 * * onClientDisconnect($this, $socket) => when an existing client disconnects
		 * * onMessage($this, $message, $socket) => when a message is recieved from a client
		 */

		$this->address = $address;
		$this->port = $port;
		$this->masterSocket = $this->createMasterSocket();
		$this->sockets[] = $this->masterSocket;
		$this->hooks = $hooks;
	}

	/*
	 * Returns the IP address of the given socket
	 */
	public function getIPAddress($socket) {
		socket_getpeername($socket, $addr);
		return $addr;
	}

	/*
	 * Sends a message to all connected clients
	 */
	public function broadcast($message) {
		foreach ($this->socket as $socket) {
			$this->sendMessage($socket, $message);
		}
	}

	/*
	 * Sends a message to the socket
	 */
	public function sendMessage($socket, $message) {
		socket_write($socket, $message, strlen($message));
	}

	public function numberOfClients() {
		return count($this->sockets) - 1; // decrease by 1 because you don't want to include the master socket
	}

	private function getSpecifiedHook($name) {
		if (isset($this->hooks[$name])) {
			return $this->hooks[$name];
		}
	}

	private function invokeHook($name) {
		$args = func_get_args();
		array_shift($args); // remove $name from the arguments list
		$args = array_merge(array($this), $args); // $this will always be the first argument to a hook invocation, so add it as the first element of the arguments array
		if ($hook = $this->getSpecifiedHook($name)) {
			call_user_func_array($hook, $args);
		}
	}

	private function createMasterSocket() {
		// Create the master socket that will be used to listen to incoming connections
		if (($master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
			die("Master socket could not be created => " . socket_strerror($master));
		}

		socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1);

		// Bind the master socket to the given address:port
		if (!($bindSucess = socket_bind($master, $this->address, $this->port))) {
			die(sprintf("Master socket could not be bound to %s:%s => %s", $this->address, $this->port, socket_strerror($bindSucess)));
		}
		return $master;
	}

	public function startListening() {
		/*
		 * Start listening on the master socket
		 * Note, this function does not return.
		 */

		if (!($listenSuccess = socket_listen($this->masterSocket))) {
			die(sprintf("Couldn't start listening on %s:%s => %s", $this->address, $this->port, socket_strerror($listenSuccess)));
		}

		for(;;) {
			$changedSockets = $this->sockets;

			// Wait until one of the sockets in $changedSockets changes [sends something] (not sure if this comment is accurate)
			socket_select($changedSockets, $write = NULL, $except = NULL, 0); 

			foreach($changedSockets as $socket) {
				if ($socket == $this->masterSocket) {
					// Current socket is the master socket, so check for incoming connections
					$newClient = socket_accept($this->masterSocket);
					if ($newClient < 0) {
						// Failed to accept the new connection
						continue;
					}

					$this->sockets[] = $newClient; // TODO: use the sockets array as a hash instead.
					$this->invokeHook('onClientConnect', $newClient);

					continue;
				}

				if (socket_recv($socket, $data, 2048, 0) == 0) {
					// If the recieved data is 0, it means a disconnect message so remove the socket from the array
					$socketArrayIndex = array_search($socket, $this->sockets);
					unset($this->sockets[$socketArrayIndex]);
					$this->invokeHook('onClientDisconnect', $socket);
					socket_close($socket);
					continue;
				}

				$this->invokeHook('onMessage', $data, $socket);
			}

			$this->invokeHook('onIteration');
		}
	}
}
