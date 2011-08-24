<?php
class Handshake {
	/*
	 * TODO: sec-websocket-version is not used in draft 76 so we can use this header to switch between drafts if necessery.
	 */
	private $request;
	private $magicString = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";

	public function __construct($request) {
		$this->request = $request;
	}

    private function http_parse_headers( $header )
    {
		// http://php.net/manual/en/function.http-parse-headers.php
        $retVal = array();
        $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
        foreach( $fields as $field ) {
            if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
                $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
                if( isset($retVal[$match[1]]) ) {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                } else {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
        return $retVal;
    }

	private function constructResponse($accept) {
		$headers = "HTTP/1.1 101 Switching Protocols\r\n";
		$headers .= "Upgrade: websocket\r\n";
		$headers .= "Connection: Upgrade\r\n";
		$headers .= "Sec-WebSocket-Accept: $accept\r\n\r\n";
		return $headers;
	}

	public function __toString() {
		$request = $this->request;
		$requestLines = $this->http_parse_headers($request);
		var_dump($requestLines);
		$accept = $requestLines["Sec-Websocket-Key"] . $this->magicString;
		//$accept = "dGhlIHNhbXBsZSBub25jZQ==" . $this->magicString;
		$accept = sha1($accept, true);
		$accept = base64_encode($accept);

		$response = $this->constructResponse($accept);
		var_dump($response);
		return $response;
	}
}
?>
