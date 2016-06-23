<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Convert Reserved XML characters to Entities
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('xml_convert'))
{
	function xml_convert($str)
	{
		$temp = '__TEMP_AMPERSANDS__';

		// Replace entities to temporary markers so that 
		// ampersands won't get messed up
		$str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
		$str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
	
		$str = str_replace(array("&","<",">","\"", "'", "-"),
						   array("&amp;", "&lt;", "&gt;", "&quot;", "&#39;", "&#45;"),
						   $str);

		// Decode the temp markers back to entities		
		$str = preg_replace("/$temp(\d+);/","&#\\1;",$str);
		$str = preg_replace("/$temp(\w+);/","&\\1;", $str);
		
		return $str;
	}
}




/* RSS */

if ( ! function_exists('rss_startElement'))
{

	function rss_startElement($parser, $name, $attrs) {
		global $rss_channel, $rss_currently_writing, $rss_main;
		switch($name) {
			case "RSS":
			case "RDF:RDF":
			case "ITEMS":
				$rss_currently_writing = "";
				break;
			case "CHANNEL":
				$rss_main = "CHANNEL";
				break;
			case "IMAGE":
				$rss_main = "IMAGE";
				$rss_rss_channel["IMAGE"] = array();
				break;
			case "ITEM":
				$rss_main = "ITEMS";
				break;
			default:
				$rss_currently_writing = $name;
				break;
		}
	}
	
}



if ( ! function_exists('rss_endElement'))
{	
	
	function rss_endElement($parser, $name) {
		global $rss_channel, $rss_currently_writing, $rss_item_counter;
		$rss_currently_writing = "";
		if ($name == "ITEM") {
			$rss_item_counter++;
		}
	}
	
}


if ( ! function_exists('rss_characterData'))
{	
	
	function rss_characterData($parser, $data) {
		global $rss_channel, $rss_currently_writing, $rss_main, $rss_item_counter;
		if ($rss_currently_writing != "") {
			switch($rss_main) {
				case "ITEMS":
					if (isset($rss_channel[$rss_main][$rss_item_counter][$rss_currently_writing])) {
						$rss_channel[$rss_main][$rss_item_counter][$rss_currently_writing] .= $data;
					} else {
						$rss_channel[$rss_main][$rss_item_counter][$rss_currently_writing] = $data;
					}
					break;
			}
		}
	}
	
}



if ( ! function_exists('rss_string'))
{	
	
	function rss_string($url){
	
		   $str=file($url);
		   $result='';
			   foreach($str as $item){
				  $result.=$item;
			   }
		   return $result;
	  
	}
	
}



/* End of file xml_helper.php */
/* Location: ./system/helpers/xml_helper.php */