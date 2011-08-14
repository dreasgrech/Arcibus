<?php
class Application {

	private $clients = array();

	public function addClient($client) {
		$this->clients[] = $client;
	}

	public function removeClient($client) {
		$key = array_search($client, $this->clients);
		if ($key) {
			unset($this->clients[$key]);
		}
	}

	public function onData($data/*, $client*/) {
		foreach($this->clients as $sendto) {
			$sendto->send($data);
		}
	}
}
?>
