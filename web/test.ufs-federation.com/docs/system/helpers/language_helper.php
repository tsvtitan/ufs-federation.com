<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */	
if ( ! function_exists('lang'))
{
	function lang($line, $id = '')
	{
		$CI =& get_instance();
		$line = $CI->lang->line($line);

		if ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

if ( ! function_exists('dictionary'))
{
	function dictionary($line)
	{
		$CI =& get_instance();

		if(isset($CI->dictionary[$line]))
        {
            $line = $CI->dictionary[$line];
        }

		return $line;
	}
}

if ( ! function_exists('settings'))
{
	function settings($line)
	{
		$CI =& get_instance();

		if(isset($CI->settings[$line]))
        {
            $line = $CI->settings[$line];
        }

		return $line;
	}
}

if ( ! function_exists('is_active_settings'))
{
	function is_active_settings($line)
	{
		$CI =& get_instance();

		if(isset($CI->active_settings[$line]))
        {
            $line = $CI->active_settings[$line];
            if($line==1)return true;
        }

		return false;
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */