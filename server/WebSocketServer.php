<?php
include 'socketserver.php';

// TODO: put these classes in seperate files

class WebSocketClient {
	public $handshaked;
	public $socket;

	public function __construct($socket) {
		$this->handshaked = false;
		$this->socket = $socket;
	}
}

class Handshake { 
	private $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function getHandshake() {
		$data = $this->data;

		$lines = preg_split("/\r\n/", $data);

		if (! preg_match('/\AGET (\S+) HTTP\/1.1\z/', $lines[0], $matches)) { //TODO: work on this one
			//$this->log('Invalid request: ' . $lines[0]);
			//socket_close($this->socket);
			//return false;
			echo "something wrong => " . $lines[0] . PHP_EOL;
			return;
		}

		$path = $matches[1];

		foreach ($lines as $line) {
			$line = chop($line);
			if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
				$headers[$matches[1]] = $matches[2];
			}
		}

		$key3 = '';
		preg_match("#\r\n(.*?)\$#", $data, $match) && $key3 = $match[1];

		$origin = $headers['Origin'];
		$host = $headers['Host'];


		$status = '101 Web Socket Protocol Handshake';
		if (array_key_exists('Sec-WebSocket-Key1', $headers)) {
			// draft-76
			$def_header = array(
				'Sec-WebSocket-Origin' => $origin,
				'Sec-WebSocket-Location' => "ws://{$host}{$path}"
			);
			$digest = $this->securityDigest($headers['Sec-WebSocket-Key1'], $headers['Sec-WebSocket-Key2'], $key3);
		} else {
			// draft-75
			$def_header = array(
				'WebSocket-Origin' => $origin,
				'WebSocket-Location' => "ws://{$host}{$path}"  
			);
			$digest = '';
		}
		$header_str = '';
		foreach ($def_header as $key => $value) {
			$header_str .= $key . ': ' . $value . "\r\n";
		}

		$upgrade = "HTTP/1.1 ${status}\r\n" .
			"Upgrade: WebSocket\r\n" .
			"Connection: Upgrade\r\n" .
			"${header_str}\r\n$digest";

		return $upgrade;
	}

	private function securityDigest($key1, $key2, $key3)
	{
		return md5(
			pack('N', $this->keyToBytes($key1)) .
			pack('N', $this->keyToBytes($key2)) .
			$key3, true);
	}

	/**
	 * WebSocket draft 76 handshake by Andrea Giammarchi
	 * see http://webreflection.blogspot.com/2010/06/websocket-handshake-76-simplified.html
	 */
	private function keyToBytes($key)
	{
		return preg_match_all('#[0-9]#', $key, $number) && preg_match_all('# #', $key, $space) ?
			implode('', $number[0]) / count($space[0]) :
			'';
	}
}

class WebSocketServer extends Server {

	private $webSocketClients = array();

	public function __construct($address = 'localhost', $port = 8080, $hooks) {
		$webSocketClients = $this->webSocketClients;

		//TODO: Override the disconnect hook to remove the clients from the $webSocketClients array

		$overriddenHooks = array(
			'onClientConnect' => function ($parent, $socket) use (&$webSocketClients, $hooks) {
				$webSocketClients[$socket] = new WebSocketClient($socket);
				if ($hook = $hooks['onClientConnect']) {
					$hook($parent, $socket);
				}
			},
				'onMessage' => function ($parent, $message, $socket) use (&$webSocketClients, $hooks) {
					if ($webSocketClients[$socket]->handshaked && ($hook = $hooks['onMessage'])) {
						$hook($parent, $message, $socket);
						return;
					}

					//Do the handshake
					$handShakeObj = new Handshake($message);
					socket_write($socket, $handShakeObj->getHandshake());
					$webSocketClients[$socket]->handshaked = true;
				}
		);

		parent::__construct($address, $port, $overriddenHooks);
	}

	public function sendMessage($socket, $message) {
		$message = chr(0) . $message . chr(255);
		parent::sendMessage($socket, $message);
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
	}, 'onMessage' => function ($s, $message, $socket) {
		$ip = $s->getIPAddress($socket);
		echo sprintf("[%s] %s" . PHP_EOL, $ip, $message);
		$s->sendMessage($socket, "Thanks for the message");
	});

$server = new WebSocketServer($address, $port, $hooks);

?>
