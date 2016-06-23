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
 * CodeIgniter String Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/string_helper.html
 */

// ------------------------------------------------------------------------

/*MySQL string*/
if ( ! function_exists('mysql_string'))
{
	function mysql_string($str)
	{
	    return htmlspecialchars( trim($str) ,ENT_QUOTES );
	} 
}

/*MySQL string fckeditor*/
if ( ! function_exists('mysql_string_fck'))
{
	function mysql_string_fck($str)
	{			
		$str=preg_replace('/([\\\]+)/','',$str);
		
		$str = trim($str);
		$str=preg_replace('/^\<br\ \/\>$/','',$str);
		
		//$str=preg_replace('/^([a-z]+)([^\>\<]*)\>/','<$1$2>',$str);
		//$str=preg_replace('/\<\/([a-z]+)$/','</$1>',$str);
		
		$str = str_replace(array("\'","'","`",'"'), array("&#39;","&#39;","\`",'\"'), $str);
		
		return $str;
	} 
}

/*MySQL string trim http://*/
if ( ! function_exists('mysql_string_http'))
{
	function mysql_string_http($str)
	{
	    return htmlspecialchars( preg_replace('/^http\:\/\//i','', trim( $str ) )  ,ENT_QUOTES );
	} 
}

if(!function_exists('empty_fck_text'))
{
    function empty_fck_text($str)
    {
    	
         $str = strip_tags($str,'<img>');
         $str = trim(str_replace("&nbsp;","",$str));
         return (empty($str));
    }
}

/**
 * Trim Slashes
 *
 * Removes any leading/traling slashes from a string:
 *
 * /this/that/theother/
 *
 * becomes:
 *
 * this/that/theother
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('trim_slashes'))
{
	function trim_slashes($str)
	{
	    return trim($str, '/');
	} 
}
	
// ------------------------------------------------------------------------

/**
 * Strip Slashes
 *
 * Removes slashes contained in a string or in an array
 *
 * @access	public
 * @param	mixed	string or array
 * @return	mixed	string or array
 */	
if ( ! function_exists('strip_slashes'))
{
	function strip_slashes($str)
	{
		if (is_array($str))
		{	
			foreach ($str as $key => $val)
			{
				$str[$key] = strip_slashes($val);
			}
		}
		else
		{
			$str = stripslashes($str);
		}
	
		return $str;
	}
}

// ------------------------------------------------------------------------

/**
 * Strip Quotes
 *
 * Removes single and double quotes from a string
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('strip_quotes'))
{
	function strip_quotes($str)
	{
		return str_replace(array('"', "'"), '', $str);
	}
}

// ------------------------------------------------------------------------

/**
 * Quotes to Entities
 *
 * Converts single and double quotes to entities
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('quotes_to_entities'))
{
	function quotes_to_entities($str)
	{	
		return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $str);
	}
}

// ------------------------------------------------------------------------
/**
 * Reduce Double Slashes
 *
 * Converts double slashes in a string to a single slash,
 * except those found in http://
 *
 * http://www.some-site.com//index.php
 *
 * becomes:
 *
 * http://www.some-site.com/index.php
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('reduce_double_slashes'))
{
	function reduce_double_slashes($str)
	{
		return preg_replace("#([^:])//+#", "\\1/", $str);
	}
}
	
// ------------------------------------------------------------------------

/**
 * Reduce Multiples
 *
 * Reduces multiple instances of a particular character.  Example:
 *
 * Fred, Bill,, Joe, Jimmy
 *
 * becomes:
 *
 * Fred, Bill, Joe, Jimmy
 *
 * @access	public
 * @param	string
 * @param	string	the character you wish to reduce
 * @param	bool	TRUE/FALSE - whether to trim the character from the beginning/end
 * @return	string
 */	
if ( ! function_exists('reduce_multiples'))
{
	function reduce_multiples($str, $character = ',', $trim = FALSE)
	{
		$str = preg_replace('#'.preg_quote($character, '#').'{2,}#', $character, $str);

		if ($trim === TRUE)
		{
			$str = trim($str, $character);
		}
    
		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Create a Random String
 *
 * Useful for generating passwords or hashes.
 *
 * @access	public
 * @param	string 	type of random string.  Options: alunum, numeric, nozero, unique
 * @param	integer	number of characters
 * @return	string
 */
if ( ! function_exists('random_string'))
{	
	function random_string($type = 'alnum', $len = 8)
	{					
		switch($type)
		{
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
		
					switch ($type)
					{
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}

					$str = '';
					for ($i=0; $i < $len; $i++)
					{
						$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
					}
					return $str;
			  break;
			case 'unique' : return md5(uniqid(mt_rand()));
			  break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Alternator
 *
 * Allows strings to be alternated.  See docs...
 *
 * @access	public
 * @param	string (as many parameters as needed)
 * @return	string
 */	
if ( ! function_exists('alternator'))
{
	function alternator()
	{
		static $i;	

		if (func_num_args() == 0)
		{
			$i = 0;
			return '';
		}
		$args = func_get_args();
		return $args[($i++ % count($args))];
	}
}

// ------------------------------------------------------------------------

/**
 * Repeater function
 *
 * @access	public
 * @param	string
 * @param	integer	number of repeats
 * @return	string
 */	
if ( ! function_exists('repeater'))
{
	function repeater($data, $num = 1)
	{
		return (($num > 0) ? str_repeat($data, $num) : '');
	} 
}



/*HTML encode*/
if ( ! function_exists('HTML_decode'))
{
	function HTML_decode($str)
	{		
			$code=array('&#39;','&#039;','&quot;','&amp;','&lt;','&gt;','&euro;','&lsquo;','&rsquo;','&ldquo;','&rdquo;','&ndash;','&mdash;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&uuml;','&yacute;','&thorn;','&yuml;','&OElig;','&oelig;','&#372;','&#374','&#373','&#375;','&sbquo;','&#8219;','&bdquo;','&hellip;','&trade;','&#9658;','&bull;','&rarr;','&diams;','&asymp;');
						
			$decode=array("'","'",'"','&','<','>','€','‘','’','“','”','–','—','¡','¢','£','¤','¥','¦','§','¨','©','ª','«','¬','®','¯','°','±','²','³','´','µ','¶','·','¸','¹','º','»','¼','½','¾','¿','À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','×','Ø','Ù','Ú','Û','Ü','Ý','Þ','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','÷','ø','ù','ú','û','ü','ü','ý','þ','ÿ','Œ','œ','Ŵ','Ŷ','ŵ','ŷ','‚','‛','„','…','™','►','•','→','♦','≈');
								
				
				for($i=0;$i<count($code);$i++){
					$code[$i]=preg_replace('/\&/','\&',$code[$i]);
					$code[$i]=preg_replace('/\;/','\;',$code[$i]);
					$code[$i]=preg_replace('/\#/','\#',$code[$i]);
					
					$mask='/'.$code[$i].'/';
					
					$str=preg_replace($mask,$decode[$i],$str);
				}		
			
		return $str;
	} 
}


/*xml string*/
if ( ! function_exists('xml_string'))
{
	function xml_string($str)
	{		
			
			$code=array('<em>','</em>','<strong>','</strong>');						
						
			$decode=array('<i>','</i>','<b>','</b>');
								
				
				for($i=0;$i<count($code);$i++){
					$code[$i]=preg_replace('/\</','\<',$code[$i]);
					$code[$i]=preg_replace('/\>/','\>',$code[$i]);
					$code[$i]=preg_replace('/\//','\/',$code[$i]);
					
					$mask='/'.$code[$i].'/';
					
					$str=preg_replace($mask,$decode[$i],$str);
				}
				
			$str=preg_replace('/\<meta(.[^\<\>]+)?\>/','',$str);
			$str=preg_replace('/\<\!-\-(.[^\!]*)\-\-\>/','',$str);
			$str=preg_replace('/\<span(.[^\<\>]+)?\>/','',$str);	
			$str=preg_replace('/\<\/span\>/','',$str);
			$str=preg_replace('/\<div(.[^\<\>]+)?\>/','',$str);	
			$str=preg_replace('/\<\/div\>/','',$str);	
			$str=preg_replace('/\<p(.[^\<\>]+)?\>/','',$str);	
			$str=preg_replace('/\<\/p\>/','',$str);	
			$str=preg_replace("/[\n\r]+/",'',$str);		
			
		return $str;
	} 
}



/*lightgallery_index string*/
if ( ! function_exists('lightgallery_index'))
{
	function lightgallery_index($str)
	{		
			
			$code=array(0,1,2,3,4,5,6,7,8,9);						
						
			$decode=array('a','b','c','d','e','f','g','h','i','j');
								
				
				for($i=0;$i<count($code);$i++){					
					$mask='/'.$code[$i].'/';
					
					$str=preg_replace($mask,$decode[$i],$str);
				}	
			
		return $str;
	} 
}



if ( ! function_exists('set_price_val'))
{
	function set_price_val($str,$sep1='.',$sep2='')
	{			
		return number_format($str,2,$sep1,$sep2);
	} 
}


/* debuger */
if ( ! function_exists('debug'))
{
	function debug($arr)
	{
		$CI =& get_instance();

		return $CI->global_model->debuger($arr);
	}
}

if ( ! function_exists('highlight_search_result'))
{
   function highlight_search_result($haystack, $needle, $tag = 'var',$tag_class='') {
		$stopwords = array(); // TODO
		$needle = preg_split('/[^a-zа-яA-ZА-Я0-9]+/u', $needle);
	
		if (empty($needle)) {
			return $haystack;
		}
		if (strlen($haystack) == 0) {
			return false;
		}
		$hl = '<' . $tag . (($tag_class!="")?('  class="'.$tag_class.'"'):''). '>\1</' . $tag . '>';  // highlight
		$pattern = '/(%s)/iu';
		foreach ($needle as $v) {
			// limit (3) should be equal to mysql variable 'ft_min_word_len'
			if (strlen(trim($v)) == 0 || in_array($v, $stopwords) || strlen($v) < 3) {
				continue; //  no empty words or stopwords
			}
			$qv = preg_quote($v); // regex quote
			$regex = sprintf($pattern, $qv);
	
			$haystack = preg_replace($regex, $hl, $haystack);
		}
		
		return $haystack;
	}
}	

if ( ! function_exists('get_search_cutted_text'))
{	
    function get_search_cutted_text($text, $highlight = '', $length = 800) {
		// let's find from the given strings needed one to return
		if (is_array($text)) {
			for ($i = 0; $i < count($text);$i++) {
				if (mb_strpos($text[$i], $highlight) > 0) {
					$rText = $text[$i];
					break;
				} else {
					$rText = $text[$i];
				}
			}
		} else {
			$rText = $text;
		}
	
		// and let us cut this shit as smart as we can
		$highlight = preg_split('/[^a-zа-яA-ZА-Я0-9]+/u', $highlight);
		foreach ($highlight as $word) {
			if (empty($return)) {
				$return = excerpt_text(strip_tags($rText), $word, ceil($length / 2));
			} else {
				break;
			}
		}
		if (empty($return)) {
			$return = truncate_text(strip_tags($rText), $length);
		}
		
		return $return;
	}	
}
	
if ( ! function_exists('truncate_text'))
{
 function truncate_text($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
{
  if ($text == '')
  {
    return '';
  }

  $mbstring = extension_loaded('mbstring');
  if($mbstring)
  {
   $old_encoding = mb_internal_encoding();
   @mb_internal_encoding(mb_detect_encoding($text));
  }
  $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
  $substr = ($mbstring) ? 'mb_substr' : 'substr';

  if ($strlen($text) > $length)
  {
    $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
    if ($truncate_lastspace)
    {
      $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
    }
    $text = $truncate_text.$truncate_string;
  }

  if($mbstring)
  {
   @mb_internal_encoding($old_encoding);
  }

  return $text;
}	
}

if ( ! function_exists('excerpt_text'))
{	
function excerpt_text($text, $phrase, $radius = 100, $excerpt_string = '...', $excerpt_space = false)
{
  if ($text == '' || $phrase == '')
  {
    return '';
  }

  $mbstring = extension_loaded('mbstring');
  if($mbstring)
  {
    $old_encoding = mb_internal_encoding();
    @mb_internal_encoding(mb_detect_encoding($text));
  }
  $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
  $strpos = ($mbstring) ? 'mb_strpos' : 'strpos';
  $strtolower = ($mbstring) ? 'mb_strtolower' : 'strtolower';
  $substr = ($mbstring) ? 'mb_substr' : 'substr';

  $found_pos     = $strpos($strtolower($text), $strtolower($phrase));
  $return_string = '';
  if ($found_pos !== false)
  {
    $start_pos = max($found_pos - $radius, 0);
    $end_pos = min($found_pos + $strlen($phrase) + $radius, $strlen($text));
    $excerpt = $substr($text, $start_pos, $end_pos - $start_pos);
    $prefix = ($start_pos > 0) ? $excerpt_string : '';
    $postfix = $end_pos < $strlen($text) ? $excerpt_string : '';

    if ($excerpt_space)
    {
      // only cut off at ends where $exceprt_string is added
      if($prefix)
      {
        $excerpt = preg_replace('/^(\S+)?\s+?/', ' ', $excerpt);
      }
      if($postfix)
      {
        $excerpt = preg_replace('/\s+?(\S+)?$/', ' ', $excerpt);
      }
    }

    $return_string = $prefix.$excerpt.$postfix;
  }

  if($mbstring)
  {
   @mb_internal_encoding($old_encoding);
  }
  return $return_string;
}
}	

/* End of file string_helper.php */
/* Location: ./system/helpers/string_helper.php */