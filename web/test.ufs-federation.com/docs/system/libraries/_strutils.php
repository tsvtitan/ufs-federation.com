<?php

function trim_utf8($s) {
	
	
	return trim(preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$s));
	//return trim(preg_replace('/[^\p{L}\s]/u','',$s));
}
