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
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Site URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array.
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->site_url($uri);
	}
}
	
// ------------------------------------------------------------------------

/**
 * Base URL
 *
 * Returns the "base_url" item from your config file
 *
 * @access	public
 * @return	string
 */	
if ( ! function_exists('base_url'))
{
	function base_url()
	{
		$CI =& get_instance();
		return $CI->config->slash_item('base_url');
	}
}
	
// ------------------------------------------------------------------------

/**
 * Index page
 *
 * Returns the "index_page" from your config file
 *
 * @access	public
 * @return	string
 */	
if ( ! function_exists('index_page'))
{
	function index_page()
	{
		$CI =& get_instance();
		return $CI->config->item('index_page');
	}
}
	
// ------------------------------------------------------------------------

/**
 * Anchor Link
 *
 * Creates an anchor based on the local URL.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */	
if ( ! function_exists('url'))
{
	function url($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;
	
		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}
	
		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}
	
// ------------------------------------------------------------------------

/**
 * Anchor Link - Pop-up version
 *
 * Creates an anchor based on the local URL. The link
 * opens a new window based on the attributes specified.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the link title
 * @param	mixed	any attributes
 * @return	string
 */
if ( ! function_exists('url_popup'))
{
	function url_popup($uri = '', $title = '', $attributes = FALSE)
	{	
		$title = (string) $title;
	
		$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
	
		if ($title == '')
		{
			$title = $site_url;
		}
	
		if ($attributes === FALSE)
		{
			return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
		}
	
		if ( ! is_array($attributes))
		{
			$attributes = array();
		}
		
		foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
		{
			$atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
		}

		return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parse_attributes($atts, TRUE)."');\">".$title."</a>";
	}
}
	
// ------------------------------------------------------------------------

/**
 * Mailto Link
 *
 * @access	public
 * @param	string	the email address
 * @param	string	the link title
 * @param	mixed 	any attributes
 * @return	string
 */
if ( ! function_exists('mailto'))
{
	function mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;
	
		if ($title == "")
		{
			$title = $email;
		}
	
		$attributes = _parse_attributes($attributes);
	
		return '<a href="mailto:'.$email.'"'.$attributes.'>'.$title.'</a>';
	}
}
	
	
// ------------------------------------------------------------------------

/**
 * Auto-linker
 *
 * Automatically links URL and Email addresses.
 * Note: There's a bit of extra code here to deal with
 * URLs or emails that end in a period.  We'll strip these
 * off and add them after the link.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the type: email, url, or both
 * @param	bool 	whether to create pop-up links
 * @return	string
 */
if ( ! function_exists('auto_link'))
{
	function auto_link($str, $type = 'both', $popup = FALSE)
	{
		if ($type != 'email')
		{		
			if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
			{
				$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
		
				for ($i = 0; $i < sizeof($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['6'][$i]))
					{
						$period = '.';
						$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
					}
			
					$str = str_replace($matches['0'][$i],
										$matches['1'][$i].'<a href="http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'"'.$pop.'>http'.
										$matches['4'][$i].'://'.
										$matches['5'][$i].
										$matches['6'][$i].'</a>'.
										$period, $str);
				}
			}
		}

		if ($type != 'url')
		{	
			if (preg_match_all("/([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
			{
				for ($i = 0; $i < sizeof($matches['0']); $i++)
				{
					$period = '';
					if (preg_match("|\.$|", $matches['3'][$i]))
					{
						$period = '.';
						$matches['3'][$i] = substr($matches['3'][$i], 0, -1);
					}
			
					$str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
				}
		
			}
		}
		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Prep URL
 *
 * Simply adds the http:// part if missing
 *
 * @access	public
 * @param	string	the URL
 * @return	string
 */
if ( ! function_exists('prep_url'))
{
	function prep_url($str = '')
	{
		if ($str == 'http://' OR $str == '')
		{
			return '';
		}
	
		if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
		{
			$str = 'http://'.$str;
		}
	
		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the separator: dash, or underscore
 * @return	string
 */
if ( ! function_exists('url_title'))
{
	function url_title($str, $translit=false)
	{
	  $str = strip_tags($str);

       if($translit==true){
	   	/* Translit url */
		
          $alphas=array(
						 'a'=>'а|А',
						 'b'=>'б|Б',
						 'v'=>'в|В',
						 'g'=>'г|Г',
						 'd'=>'д|Д',
						 'e'=>'е|Е',
						 'yo'=>'ё|Ё',
						 'zh'=>'ж|Ж',
						 'z'=>'з|З',
						 'i'=>'и|И',
						 'yi'=>'й|Й',
						 'k'=>'к|К',
						 'l'=>'л|Л',
						 'm'=>'м|М',
						 'n'=>'н|Н',
						 'o'=>'о|О',
						 'p'=>'п|П',
						 'r'=>'р|Р',
						 's'=>'с|С',
						 't'=>'т|Т',
						 'y'=>'у|У',
						 'f'=>'ф|Ф',
						 'x'=>'х|Х',
						 'ts'=>'ц|Ц',
						 'ch'=>'ч|Ч',
						 'sh'=>'ш|Ш',
						 'shc'=>'щ|Щ',
						 'u'=>'ы|Ы',
						 'ie'=>'э|Э',
						 'yu'=>'ю|Ю',
						 'ya'=>'я|Я'
               			);

               foreach($alphas as $id=>$value){
					 $mask='/('.$value.')/';
					 $str=preg_replace($mask,$id,$str);
               }
     	  }	
	
			$search		= '_';
			$replace	= '-';
		
		$trans = array(
						$search								=> $replace,
						"\s+"								=> $replace,
						"[^a-z0-9".$replace."]"				=> '',
						$replace."+"						=> $replace,
						$replace."$"						=> '',
						"^".$replace						=> ''
					   );

		$str = strtolower($str);
	
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#", $val, $str);
		}
	
		return trim(stripslashes($str));
	}
}
	
// ------------------------------------------------------------------------

/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access	public
 * @param	string	the URL
 * @param	string	the method: location or redirect
 * @return	string
 */
if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".site_url($uri));
				break;
			case 'js'	: echo("<script language=\"javascript\" type=\"text/javascript\">self.location.href='".site_url($uri)."';</script>");
				break;
			default			: header("Location: ".site_url($uri), TRUE, $http_response_code);
				break;
		}
		exit;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Parse out the attributes
 *
 * Some of the functions use this
 *
 * @access	private
 * @param	array
 * @param	bool
 * @return	string
 */
if ( ! function_exists('_parse_attributes'))
{
	function _parse_attributes($attributes, $javascript = FALSE)
	{
		if (is_string($attributes))
		{
			return ($attributes != '') ? ' '.$attributes : '';
		}

		$att = '';
		foreach ($attributes as $key => $val)
		{
			if ($javascript == TRUE)
			{
				$att .= $key . '=' . $val . ',';
			}
			else
			{
				$att .= ' ' . $key . '="' . $val . '"';
			}
		}
	
		if ($javascript == TRUE AND $att != '')
		{
			$att = substr($att, 0, -1);
		}
	
		return $att;
	}
}


/* End of file url_helper.php */
/* Location: ./system/helpers/url_helper.php */
?>