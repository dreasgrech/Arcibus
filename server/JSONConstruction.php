<?php
class JSONConstruction {

	public function constructJSONObject ($data) {
		return JSONConstruction::construct($data, function ($key, $value) {
			$json = '"' . $key . '" : ';

			// check to see if the current $value is not a JSON array or object, 
			// because if it is, we don't want to surround it with ".
			$firstCharacter = $value[0];
			$lastCharacter = $value[strlen($value) - 1];

			if (
				!(strcmp($firstCharacter,"[") && strcmp($lastCharacter,"]")) || // ARRAY
				!(strcmp($firstCharacter,"{") && strcmp($lastCharacter,"}"))    // OBJECT
			) { 
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
