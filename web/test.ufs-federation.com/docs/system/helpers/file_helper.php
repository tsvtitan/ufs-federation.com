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
 * CodeIgniter File Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/file_helper.html
 */

// ------------------------------------------------------------------------


if ( ! function_exists('get_remote_file_size'))
{
    function get_remote_file_size($url, $readable = true){
       $parsed = parse_url($url);
       $host = $parsed["host"];
       $fp = @fsockopen($host, 80, $errno, $errstr, 20);
       if(!$fp) return false;
       else {
           @fputs($fp, "HEAD $url HTTP/1.1\r\n");
           @fputs($fp, "HOST: $host\r\n");
           @fputs($fp, "Connection: close\r\n\r\n");
           $headers = "";
           while(!@feof($fp))$headers .= @fgets ($fp, 128);
       }
       @fclose ($fp);
       $return = false;
       $arr_headers = explode("\n", $headers);
       foreach($arr_headers as $header) {
                // follow redirect
                $s = 'Location: ';
                if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) {
                    $url = trim(substr($header, strlen($s)));
                    return get_remote_file_size($url, $readable);
                }

                // parse for content length
           $s = "Content-Length: ";
           if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) {
               $return = trim(substr($header, strlen($s)));
               break;
           }
       }
       return $return;
    }
}


if ( ! function_exists('upload_file'))
{
	//============================================
	// upload file 
	//============================================
	function upload_file($newfilename='', $delfile='', $directory='upload', $prefix='file_', $subdir=''){
	
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	
	 if(empty($newfilename['name'])){ return $delfile; }
		
	  if(!empty($delfile)){	@unlink($dir.$delfile); }

		 $mb_max = 32; //MAX FILE SIZE
         ini_set('post_max_size',$mb_max.'M');
         ini_set('upload_max_filesize',$mb_max.'M');
                 
		 if(!is_dir($dir)) mkdir($dir, 0777);
	
		 if (!empty($newfilename['name']))   /*UPLOAD*/
 		    {
		         $filename = strtolower($newfilename['name']);
		         $filetype = explode(".", $filename);
		         $type = $filetype[count($filetype) - 1];
				 $filename_new = $prefix.preg_replace('/[^a-z0-9\_\-]+/','-',rtrim($filename,'.'.$type)).'.'.$type;
				 
				$x=1;
				while(file_exists($dir.$filename_new)){
					$filename_new = $prefix.preg_replace('/[^a-z0-9\_\-]+/','-',rtrim($filename,'.'.$type)).'_'.$x.'.'.$type;
					$x++;
				}
	
		         if (is_uploaded_file($newfilename['tmp_name']))
		         {
					 if ($newfilename['size']<(1024*1024*$mb_max))
					 {
						 if (file_exists($dir.$filename_new)){ unlink($dir.$filename_new); }
						 
						if (!(move_uploaded_file($newfilename['tmp_name'],$dir.$filename_new)))
						{echo "<script>alert(\"Error uploading !\");</script>"; die(); }
						else{ 
							return $filename_new;
					   }
					 }
					 else{ echo "<script>alert(\"Too big file size !\");</script>"; die(); }
				}
				else{ echo "<script>alert(\"Error uploading !\");</script>"; die(); }
		   }
	}
	//============================================
}


if ( ! function_exists('upload_file_pdf'))
{
	//============================================
	// upload file pdf
	//============================================
	function upload_file_pdf($newfilename='', $delfile='', $directory='upload', $prefix='file_', $subdir=''){
	
	 $dir=$_SERVER['DOCUMENT_ROOT'].$subdir.'/'.$directory.'/';
	
	 if(empty($newfilename['name'])){ return $delfile; }
		
	  if(!empty($delfile)){ @unlink($dir.$delfile); }

		 $mb_max = 32; //MAX FILE SIZE
         ini_set('post_max_size',$mb_max.'M');
         ini_set('upload_max_filesize',$mb_max.'M');
                 
		 if(!is_dir($dir)) mkdir($dir, 0777);
	
		 if (!empty($newfilename['name']))   /*UPLOAD*/
 		    {
		         $filename = strtolower($newfilename['name']);
		         $filetype = explode(".", $filename);
		         $type = $filetype[count($filetype) - 1];
				 $filename_new = $prefix.preg_replace('/[^a-z0-9\_\-]+/','-',rtrim($filename,'.'.$type)).'.'.$type;
				 
				$x=1;
				while(file_exists($dir.$filename_new)){
					$filename_new = $prefix.preg_replace('/[^a-z0-9\_\-]+/','-',rtrim($filename,'.'.$type)).'_'.$x.'.'.$type;
					$x++;
				}	
	
		         if (is_uploaded_file($newfilename['tmp_name']))
		         {
                                 if ($newfilename['size']<(1024*1024*$mb_max))
                                 {
                                        if ($type=='pdf')
                                        {
                                                if (file_exists($dir.$filename_new)){ unlink($dir.$filename_new); }

                                                        if (!(move_uploaded_file($newfilename['tmp_name'],$dir.$filename_new)))
                                                        { echo "<script>alert(\"Error uploading !\");</script>"; die(); }
                                                        else{
                                                                return $filename_new;
                                                        }
                                        }
                                        else{ echo "<script>alert(\"Incorrect file type!\");</script>"; die(); }
                                 }
                                 else{ echo "<script>alert(\"Too big file size !\");</script>"; die(); }
                        }
                        else{ echo "<script>alert(\"Error is not uploaded !\");</script>"; die(); }
		   }
	}
	//============================================
}

/**
 * Read File
 *
 * Opens the file specfied in the path and returns it as a string.
 *
 * @access	public
 * @param	string	path to file
 * @return	string
 */	
if ( ! function_exists('read_file'))
{
	function read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}
	
		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);		
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}
		
		flock($fp, LOCK_SH);
	
		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Write File
 *
 * Writes data to the file specified in the path.
 * Creates a new file if non-existent.
 *
 * @access	public
 * @param	string	path to file
 * @param	string	file data
 * @return	bool
 */	
if ( ! function_exists('write_file'))
{
	function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE)
	{
		if ( ! $fp = @fopen($path, $mode))
		{
			return FALSE;
		}
		
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);	

		return TRUE;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Delete Files
 *
 * Deletes all files contained in the supplied directory path.
 * Files must be writable or owned by the system in order to be deleted.
 * If the second parameter is set to TRUE, any directories contained
 * within the supplied base directory will be nuked as well.
 *
 * @access	public
 * @param	string	path to file
 * @param	bool	whether to delete any directories found in the path
 * @return	bool
 */	
if ( ! function_exists('delete_files'))
{
	function delete_files($path, $del_dir = FALSE, $level = 0)
	{	
		// Trim the trailing slash
		$path = preg_replace("|^(.+?)/*$|", "\\1", $path);
		
		if ( ! $current_dir = @opendir($path))
			return;
	
		while(FALSE !== ($filename = @readdir($current_dir)))
		{
			if ($filename != "." and $filename != "..")
			{
				if (is_dir($path.'/'.$filename))
				{
					delete_files($path.'/'.$filename, $del_dir, $level + 1);
				}
				else
				{
					unlink($path.'/'.$filename);
				}
			}
		}
		@closedir($current_dir);
	
		if ($del_dir == TRUE AND $level > 0)
		{
			@rmdir($path);
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Get Filenames
 *
 * Reads the specified directory and builds an array containing the filenames.  
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */	
if ( ! function_exists('get_filenames'))
{
	function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE)
	{
		static $_filedata = array();
				
		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			
			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
				{
					 get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{
			
					$_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Get Directory File Information
 *
 * Reads the specified directory and builds an array containing the filenames,  
 * filesize, dates, and permissions
 *
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */	
if ( ! function_exists('get_dir_file_info'))
{
	function get_dir_file_info($source_dir, $include_path = FALSE, $_recursion = FALSE)
	{
		$_filedata = array();
		$relative_path = $source_dir;
				
		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			while (FALSE !== ($file = readdir($fp)))
			{
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
				{
					 get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{
					$_filedata[$file] = get_file_info($source_dir.$file);
					$_filedata[$file]['relative_path'] = $relative_path;
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
* Get File Info
*
* Given a file and path, returns the name, path, size, date modified
* Second parameter allows you to explicitly declare what information you want returned
* Options are: name, server_path, size, date, readable, writable, executable, fileperms
* Returns FALSE if the file cannot be found.
*
* @access    public
* @param    string    path to file
* @param    mixed    array or comma separated string of information returned
* @return    array
*/    
if ( ! function_exists('get_file_info'))
{
    function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date'))
    {

        if ( ! file_exists($file))
        {
            return FALSE;
        }

        if (is_string($returned_values))
        {
            $returned_values = explode(',', $returned_values);
        }

        foreach ($returned_values as $key)
        {
            switch ($key)
            {
                case 'name':
                    $fileinfo['name'] = substr(strrchr($file, '/'), 1);
                    break;
                case 'server_path':
                    $fileinfo['server_path'] = $file;
                    break;
                case 'size':
                    $fileinfo['size'] = filesize($file);
                    break;
                case 'date':
                    $fileinfo['date'] = filectime($file);
                    break;
                case 'readable':
                    $fileinfo['readable'] = is_readable($file);
                    break;
                case 'writable':
                    // There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
                    $fileinfo['writable'] = is_writable($file);
                    break;
                case 'executable':
                    $fileinfo['executable'] = is_executable($file);
                    break;
                case 'fileperms':
                    $fileinfo['fileperms'] = fileperms($file);
                    break;
            }
        }

        return $fileinfo;
    }
}

// --------------------------------------------------------------------

/**
 * Get Mime by Extension
 *
 * Translates a file extension into a mime type based on config/mimes.php. 
 * Returns FALSE if it can't determine the type, or open the mime config file
 *
 * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
 * It should NOT be trusted, and should certainly NOT be used for security
 *
 * @access	public
 * @param	string	path to file
 * @return	mixed
 */	
if ( ! function_exists('get_mime_by_extension'))
{
	function get_mime_by_extension($file)
	{
		$extension = substr(strrchr($file, '.'), 1);
	
		global $mimes;
	
		if ( ! is_array($mimes))
		{
			if ( ! require_once(APPPATH.'config/mimes.php'))
			{
				return FALSE;
			}
		}

		if (array_key_exists($extension, $mimes))
		{
			if (is_array($mimes[$extension]))
			{
				// Multiple mime types, just give the first one
				return current($mimes[$extension]);
			}
			else
			{
				return $mimes[$extension];
			}
		}
		else
		{
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Symbolic Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * standard symbolic notation representing that value
 *
 * @access	public
 * @param	int
 * @return	string
 */	
if ( ! function_exists('symbolic_permissions'))
{
	function symbolic_permissions($perms)
	{	
		if (($perms & 0xC000) == 0xC000)
		{
			$symbolic = 's'; // Socket
		}
		elseif (($perms & 0xA000) == 0xA000)
		{
			$symbolic = 'l'; // Symbolic Link
		}
		elseif (($perms & 0x8000) == 0x8000)
		{
			$symbolic = '-'; // Regular
		}
		elseif (($perms & 0x6000) == 0x6000)
		{
			$symbolic = 'b'; // Block special
		}
		elseif (($perms & 0x4000) == 0x4000)
		{
			$symbolic = 'd'; // Directory
		}
		elseif (($perms & 0x2000) == 0x2000)
		{
			$symbolic = 'c'; // Character special
		}
		elseif (($perms & 0x1000) == 0x1000)
		{
			$symbolic = 'p'; // FIFO pipe
		}
		else
		{
			$symbolic = 'u'; // Unknown
		}

		// Owner
		$symbolic .= (($perms & 0x0100) ? 'r' : '-');
		$symbolic .= (($perms & 0x0080) ? 'w' : '-');
		$symbolic .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$symbolic .= (($perms & 0x0020) ? 'r' : '-');
		$symbolic .= (($perms & 0x0010) ? 'w' : '-');
		$symbolic .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		// World
		$symbolic .= (($perms & 0x0004) ? 'r' : '-');
		$symbolic .= (($perms & 0x0002) ? 'w' : '-');
		$symbolic .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

		return $symbolic;		
	}
}

// --------------------------------------------------------------------

/**
 * Octal Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * a three character string representing the file's octal permissions
 *
 * @access	public
 * @param	int
 * @return	string
 */	
if ( ! function_exists('octal_permissions'))
{
	function octal_permissions($perms)
	{
		return substr(sprintf('%o', $perms), -3);
	}
}


if ( ! function_exists('get_filesize_kb'))
{
	function get_filesize_kb($file)
	{
		$tmp_size = '0 Кб';
		$size     = get_file_info($file);
		
		if(isset($size['size'])){
			$tmp_size = round($size['size']/1024,2).' Кб'; 
		}
		
		return $tmp_size;
	}
}

/* End of file file_helper.php */
/* Location: ./system/helpers/file_helper.php */