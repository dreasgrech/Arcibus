<?php

error_reporting(E_ALL);

class Server {
	private $address, $port;

	private $masterSocket;
	private $sockets = array();

	private $onClientConnect, $onClientDisconnect;
	private $hooks;

	public function __construct($address = 'localhost', $port = 8080, $hooks) {
		$this->address = $address;
		$this->port = $port;
		$this->masterSocket = $this->createMasterSocket();
		$this->sockets[] = $this->masterSocket;
		$this->hooks = $hooks;

		$this->startListening(); // needs to be the last line because this method contains the infinite loop
	}

	public function getIPAddress($socket) {
		$addr = NULL;
		socket_getpeername($socket, $addr);
		return $addr;
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

	private function startListening() {
		// Start listening on the master socket
		if (!($listenSuccess = socket_listen($this->masterSocket))) {
			die(sprintf("Couldn't start listening on %s:%s => %s", $this->address, $this->port, socket_strerror($listenSuccess)));
		}

		for(;;) {
			$changedSockets = $this->sockets;

			// Wait until one of the sockets in $changedSockets changes [sends something] (not sure if this comment is accurate)
			socket_select($changedSockets, $write = NULL, $except = NULL, NULL); 

			foreach($changedSockets as $socket) {
				if ($socket == $this->masterSocket) { // Current socket is the master socket, so 
					$newClient = socket_accept($this->masterSocket);
					if ($newClient < 0) {
						// Failed to accept the new connection
						continue;
					}

					$this->invokeHook('onClientConnect', $newClient);

					$this->sockets[] = $newClient;
					continue;
				}

				if (socket_recv($socket, $data, 2048, 0) == 0) {
					// If the recieved data is 0, it means a disconnect message so remove the socket from the array
					$this->invokeHook('onClientDisconnect', $socket);
					$socketArrayIndex = array_search($socket, $this->sockets);
					unset($this->sockets[$socketArrayIndex]);
					socket_close($socket);
					continue;
				}

				echo $data . PHP_EOL;
			}
		}
	}
}

$address = '192.168.1.5';
$port = 8000;

$hooks = array(
	'onClientConnect' => function ($s, $socket) {
		$ip = $s->getIPAddress($socket);
		echo "New client connected: " . $ip . PHP_EOL;
	}, 'onClientDisconnect' => function ($s, $socket) {
		$ip = $s->getIPAddress($socket);
		echo "Client disconnected: " . $ip . PHP_EOL;
	});

$server = new Server($address, $port, $hooks);

/*
$sock = socket_create(AF_INET, SOCK_STREAM, 0);

socket_bind($sock, $address, $port) or die("Couldn't bind to address");
socket_listen($sock);

$client = socket_accept($sock);
$input = socket_read($client, 1024); // reads from the accepted socket, 1024 bytes at a time

$output = "Thanks, " . $input . chr(0);
echo $output;

socket_write($client, $output);
socket_close($client);
socket_close($sock);
 */
?>
