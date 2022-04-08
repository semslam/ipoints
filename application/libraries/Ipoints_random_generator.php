<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipoints_random_generator{
 
		public function ipoints_random_bytes()
		{
			try {
			$string = random_bytes(32);
			} catch (TypeError $e) {
				// Well, it's an integer, so this IS unexpected.
				die("An unexpected error has occurred"); 
			} catch (Error $e) {
				// This is also unexpected because 32 is a reasonable integer.
				die("An unexpected error has occurred");
			} catch (Exception $e) {
				// If you get this message, the CSPRNG failed hard.
				die("Could not generate a random string. Is our OS secure?");
			}

			var_dump(bin2hex($string));
	 
		}
	 
		public function ipoints_random_integers($x =100000,$y=9999900)
		{
			try {
				$int = random_int($x, $y);
			} catch (TypeError $e) {
				// Well, it's an integer, so this IS unexpected.
				die("An unexpected error has occurred"); 
			} catch (Error $e) {
				// This is also unexpected because 0 and 255 are both reasonable integers.
				die("An unexpected error has occurred");
			} catch (Exception $e) {
				// If you get this message, the CSPRNG failed hard.
				die("Could not generate a random int. Is our OS secure?");
			}
			var_dump($int); 
		}

}