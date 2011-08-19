<?php
class StringUtils {
	public static function removeLastCharacter ($str) { // This function shouldn't be in this class, but anyways...
		return substr($str, 0, -1);
	}
}
?>
