<?php
include 'color.php';

/*
 * Console_Color documentation: http://pear.php.net/manual/en/package.console.console-color.intro.php
 */

class Logger {

	public function logServerAction($message) {
		//$this->echoWithEOL(chr(27) . "[" . "31 " . $message);
		//$this->cmdColor("HELLO");
		system("color 4");
		echo "nice";
		system("color");
	}

	private function echoWithEOL($message) {
		echo $message . PHP_EOL;
	}


	private function cmdColor($text, $color = "32"){
		$color = strtolower($color);
		switch($color){
		case "30":
			case "black":
				$prefx = "\033[30m";
				break;
			case "31":
				case "red":
					$prefx = "\033[31m";
					break;
				case "32":
					case "green":
						$prefx = "\033[32m";
						break;
					case "33":
						case "yellow":
							$prefx = "\033[33m";
							break;
						case "34":
							case "blue":
								$prefx = "\033[34m";
								break;
							case "35":
								case "magenta":
									$prefx = "\033[35m";
									break;
								case "36":
									case "cyan":
										$prefx = "\033[36m";
										break;
									case "37":
										case "white":
											$prefx = "\033[37m";
											break;		
		}
		print $prefx.$text."\33[0m";
	}
}
?>
