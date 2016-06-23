<?php

function trim_utf8($s) {
	
	
	return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$s));
	//return trim(preg_replace('/[^\p{L}\s]/u','',$s));
}


function random_string_ex($length = 0, $chars="abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?") {

  $validCharacters = $chars;

  $validCharNumber = strlen($validCharacters);
  $result = "";

  for ($i = 0; $i < $length; $i++) {
  	$index = mt_rand(0, $validCharNumber - 1);
    $result .= $validCharacters[$index];
  }

  return $result;
}

function random_number($length = 0) {

	return random_string_ex($length, "0123456789");

}

function is_integer_ex($s) {
  return preg_match("/^[0-9]+$/",$s);
}
