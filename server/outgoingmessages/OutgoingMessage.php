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
		$data["action"] = $this->action;
		return $this->constructJSONObject($data);
	}

	/*
	 * Had to change this method's access modifier from protected to public because so that this 
	 * method is able to be called from within a closure, because PHP won't allow me to call 
	 * non-public methods from within an object passed into the closure via use().
	 *
	 * The support for closures without lexical scoping in PHP is really starting to annoy me.
	 */
	public function constructJSONObject ($data) {
		return $this->construct($data, function ($key, $value) {
			$json = '"' . $key . '" : ';
			if (!(strcmp($value[0],"[") && strcmp($value[strlen($value) - 1],"]"))) { // check to see if the current $value is not a JSON array, because if it is, we don't want to surround it with "
				return $json .= $value;
			}

			return $json .= '"' . $value . '"';
		}, "{", "}");
	}

	protected function constructJSONArray($elements, $callback) {
		return $this->construct($elements, function ($key, $value) use ($callback) {
			return $callback($key, $value);
		}, "[", "]");
	}

	private function construct($elements, $enumerationCallback, $openingBrace, $closingBrace) {
		$serialized = array();
		$serialized[] = $openingBrace;

		foreach($elements as $key => $value) {
			$serialized[] = $enumerationCallback($key, $value) . ",";
		}

		/* 
		 * Since a foreach loop in PHP keeps reference to the last enumerated object, in this case
		 * a key and value pair, we need to unset them manually.  What's up with that?
		 */
		unset($key);
		unset($value);

		$string = implode("", $serialized);
		if (count($elements) > 0) {
			$string = $this->removeLastCharacter($string); // the last character in this case is the trailing comma
		}

		$string .= $closingBrace;

		return $string;
	}

	private function removeLastCharacter ($str) { // This function shouldn't be in this class, but anyways...
		return substr($str, 0, -1);
	}
}
?>
