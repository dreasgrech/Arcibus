<?php
class Logger {

	public function logServerAction($message) {
		return $this->echoWithEOL($message);
	}

	private function echoWithEOL($message) {
		echo $message . PHP_EOL;
	}
}
?>
