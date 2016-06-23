<?php

class SFTP
{
	private $connection;
	private $sftp;

	public function __construct($host, $port=22) {

		$this->connection = @ssh2_connect($host, $port);
		if (! $this->connection)
			throw new Exception("Could not connect to $host on port $port");
	}
	
	public function __destruct() {
       unset($this->connection);		
	}

	public function login($username, $password) {
		
		if (! @ssh2_auth_password($this->connection, $username, $password))
			throw new Exception("Could not authenticate with username $username " . "and password $password");
		$this->sftp = @ssh2_sftp($this->connection);
		if (! $this->sftp)
			throw new Exception("Could not initialize SFTP subsystem");
	}

	public function uploadData($data, $remote_file) {
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');
		if (! $stream)
			throw new Exception("Could not open file: $remote_file");
		if (@fwrite($stream, $data) === false)
			throw new Exception("Could not send data");
		@fclose($stream);
	}
	
	public function uploadFile($local_file, $remote_file) {
		$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');
		if (! $stream)
			throw new Exception("Could not open file: $remote_file");
		$data_to_send = @file_get_contents($local_file);
		if ($data_to_send === false)
			throw new Exception("Could not open local file: $local_file");
		if (@fwrite($stream, $data_to_send) === false)
			throw new Exception("Could not send data from file: $local_file");
		@fclose($stream);
	}
	 
	private function pcgbasename($param, $suffix=null) {
		
		if ( $suffix ) {
			$tmpstr = ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
			if ( (strpos($param, $suffix)+strlen($suffix) )  ==  strlen($param) ) {
		  return str_ireplace( $suffix, '', $tmpstr);
			} else {
		  return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
			}
		} else {
			return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);
		}
	}	
	
	public function getFiles($remote_dir, $recurse=false) {
		
		$sftp = $this->sftp;
		$dir = "ssh2.sftp://$sftp$remote_dir";
		$tempArray = array();
		$handle = @opendir($dir);
		
		while (false !== ($file = @readdir($handle))) {
		  
		   if (substr("$file", 0, 1) != ".") {
		
		   	  $f = $remote_dir.DIRECTORY_SEPARATOR.$file;
			  if(is_dir($f)) {
			  	if ($recurse) {
			  		$temp = $this->getFiles("$remote_dir/$file", $recurse);
			  		foreach ($temp as $t) {
			  			$tempArray[]=$t;
			  		}
			  	}  
		      } else {
				$tempArray[]=$f;
		      }
		   }
		}
		@closedir($handle);
		return $tempArray;
	}

	public function receiveFile($remote_file, $local_file) {
			
	 	$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
		
		if (! $stream) 
		  throw new Exception("Could not open file: $remote_file");
		
		$contents = fread($stream, filesize("ssh2.sftp://$sftp$remote_file"));
		file_put_contents ($local_file, $contents);
		@fclose($stream);
	}		
			 
	public function receiveData($remote_file) {
			
	 	$sftp = $this->sftp;
		$stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
		
		if (! $stream) 
		  throw new Exception("Could not open file: $remote_file");
		
		$contents = fread($stream, filesize("ssh2.sftp://$sftp$remote_file"));
		@fclose($stream);
		
		return $contents; 
	}		
	
	public function deleteFile($remote_file) {
		
	  	$sftp = $this->sftp;
		unlink("ssh2.sftp://$sftp$remote_file");
	}
}