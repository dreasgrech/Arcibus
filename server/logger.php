<?php
class Logger {

	public function logServerAction($message) {
		$message = "[" . $message . "]";
		return $this->echoWithEOL($message);
	}

	public function logMessageRecieved($ip, $message) {
		 $this->echoWithEOL(sprintf("[%s] %s", $ip, $message));
	}

	private function echoWithEOL($message) {
		echo $message . PHP_EOL;
	}
}
?>
