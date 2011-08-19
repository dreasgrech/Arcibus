<?php
class JSONConstruction {

	public function constructJSONObject ($data) {
		return JSONConstruction::construct($data, function ($key, $value) {
			$json = '"' . $key . '" : ';
			if (!(strcmp($value[0],"[") && strcmp($value[strlen($value) - 1],"]"))) { // check to see if the current $value is not a JSON array, because if it is, we don't want to surround it with "
				return $json .= $value;
			}

			return $json .= '"' . $value . '"';
		}, "{", "}");
	}

	public static function constructJSONArray($elements, $callback) {
		return JSONConstruction::construct($elements, function ($key, $value) use ($callback) {
			return $callback($key, $value);
		}, "[", "]");
	}
	private static function construct($elements, $enumerationCallback, $openingBrace, $closingBrace) {
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
			$string = StringUtils::removeLastCharacter($string); // the last character in this case is the trailing comma
		}

		$string .= $closingBrace;

		return $string;
	}


}
?>
