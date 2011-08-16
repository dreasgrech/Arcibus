<?php
class Handshake { 
	private $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function getHandshake() {
		// Source: https://github.com/nicokaiser/php-websocket/blob/master/server/lib/WebSocket/Connection.php

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
?>
