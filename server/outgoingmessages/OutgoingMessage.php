<?php
class OutgoingMessage {
	public $action;

	public function __construct($action) {
		$this->action = $action;
	}

	public function serialize() {
		return json_encode(get_object_vars($this));
	}

	protected function manuallyConstructMessage($data) {
		$jsonMessage = array();
		$jsonMessage[] = "{";
		$jsonMessage[] = $this->serializeKeyValue("action", $this->action) . ",";

		foreach($data as $key => $value) {
			$jsonMessage[] = $this->serializeKeyValue($key, $value) . ",";
		}

		$message = $this->removeLastCharacter(implode("", $jsonMessage)) . "}";
		return $message;
	}

	private function serializeKeyValue($key, $value) {
		return '"' . $key . '" : "' . $value . '"';
	}

	private function removeLastCharacter ($str) { // This function shouldn't be in this class, but anyways...
		return substr($str, 0, -1);
	}
}
?>
