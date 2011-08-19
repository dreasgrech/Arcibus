<?php
class OutgoingMessage {
	public $action;

	public function __construct($action) {
		$this->action = $action;
	}

	public function __toString() {
		return json_encode(get_object_vars($this));
	}

	protected function manuallyConstructMessage($data) {
		$data["action"] = $this->action;
		return JSONConstruction::constructJSONObject($data);
	}

}
?>
