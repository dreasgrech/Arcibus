<?php
include 'connection.php';
include 'application.php';
include 'message.php';

class Server {

	public $clients = array();
	private $allSockets = array();
	private $master;
	private $application;
	public $players = array();

	public function __construct($host = 'localhost', $port = 8000, $max = 100) {
		ob_implicit_flush(true);
		$this->application = new Application();
		$this->createSocket($host, $port);
		echo $this->master;
	}

	public function run() {
		while (true) {
			$changed_sockets = $this->allSockets;
			@socket_select($changed_sockets, $write = NULL, $except = NULL, 1);

			foreach($changed_sockets as $socket) {
				if ($socket == $this->master) {
					$this->log('Socket is master');
					if (($ressource = socket_accept($this->master)) < 0) {
						$this->log('Socket error: ' . socket_strerror(socket_last_error($ressource)));
						continue;
					} else {
						$this->log('New connection');

						$newPlayer =  new Player(trim(com_create_guid(), '{}'));
						$this->players[] = $newPlayer;

						$client = new Connection($this, $ressource, $this->application, $newPlayer);
						$this->clients[$ressource] = $client;
						$this->allSockets[] = $ressource;
						$this->application->onData("New connection");
					}
				} else {
					$client = $this->clients[$socket];
					$bytes = @socket_recv($socket, $data, 4096, 0);
					if ($bytes == 0) { 
						$this->log("Disconnected");
						$client->onDisconnect();
						unset($this->clients[$socket]);
						$index = array_search($socket, $this->allSockets);
						unset($this->allSockets[$index]);
						unset($client);

					} else {
						$trimmed = substr($data, 1,-1); // for some reason, the data recieved contains single leading and trailing whitespace characters which trim() won't remove!
						$dec = json_decode($trimmed);
						print_r($dec);
						$this->log("Recieved: " . $data);
						$client->onData($data);

						//$playerMoveMessage = new PlayerMoveMessage(
					}
				}
			}
		}
	}

	private function createSocket($host, $port) {
		if (($this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
			die("socket_create() failed, reason: " . socket_strerror($this->master));
		}

		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);

		if (($ret = socket_bind($this->master, $host, $port)) < 0) {
			die("socket_bind() failed, reason: " . socket_strerror($ret));
		}

		$this->log("Socket bound to {$host}:{$port}.");

		if (($ret = socket_listen($this->master, 5)) < 0) {
			die("socket_listen() failed, reason: " . socket_strerror($ret));
		}

		$this->log('Start listening on Socket.');

		$this->allSockets[] = $this->master;
	}

	public function log ($msg) {
		echo $msg . PHP_EOL;
	}
}
?>
