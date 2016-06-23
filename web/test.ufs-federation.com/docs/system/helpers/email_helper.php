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
 * CodeIgniter Email Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/email_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Validate email address
 *
 * @access	public
 * @return	bool
 */	
if ( ! function_exists('valid_email'))
{
	function valid_email($address)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}

// ------------------------------------------------------------------------

/**
 * Send an email
 *
 * @access	public
 * @return	bool
 */	
if ( ! function_exists('send_email'))
{
	function send_email($recipient, $subject = 'Test email', $message = 'Hello World')
	{
		return mail($recipient, $subject, $message);
	}
}


// ------------------------------------------------------------------------

/**
 * Send an email (html format)
 *
 * @access	public
 * @return	bool
 */	

if ( ! function_exists('send_email_html'))
{
	function send_email_html($to, $subject = 'Test email', $message = 'Hello World', $from = 'local@localhost')
	{			
		// message
		$msg = '<html>'."\r\n";
		$msg .= '<head>'."\r\n";
		$msg .= '  <title>'.$subject.'</title>'."\r\n";
		$msg .= '</head>'."\r\n";
		$msg .= '<body>'."\r\n";
		$msg .= $message."\r\n";
		$msg .= '</body>'."\r\n";
		$msg .= '</html>'."\r\n";
		
		$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
	
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8; format=flowed'."\r\n";
		$headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
		
		// Additional headers
		$headers .= 'From: '.$from."\r\n";
		$headers .= 'Reply-To: '.$from."\r\n";
		$headers .= 'X-Mailer: PHP/'.phpversion()."\r\n";
		
		// Mail it
		return mail($to, $subject, $msg, $headers);
	}
}

if ( ! function_exists('send_email_htmltemplate'))
{
	function send_email_htmltemplate($to, $subject = 'Test email', $msg = 'Hello World', $from = 'local@localhost')
	{			
		
		$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
	
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8; format=flowed'."\r\n";
		$headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
		
		// Additional headers
		$headers .= 'From: '.$from."\r\n";
		$headers .= 'Reply-To: '.$from."\r\n";
		$headers .= 'X-Mailer: PHP/'.phpversion()."\r\n";
		
		// Mail it
		return mail($to, $subject, $msg, $headers);
	}
}


if ( ! function_exists('send_email_html_and_text'))
{
	function send_email_html_and_text($to, $subject = 'Test email', $message_html = 'Hello World', $message_text = 'Hello World', $from = 'local@localhost')
	{			
		
		$boundary = uniqid('MSG');
		$img_url_mask='/\/images\/userfiles\//';
		$img_url_replace='http://'.$_SERVER['HTTP_HOST'].'/images/userfiles/';
		
		// message
		$msg  = "This part of the E-mail should never be seen. If you are reading this, consider upgrading your e-mail client to a MIME-compatible client."; 
		
		$msg .= "\r\n\r\n--".$boundary."\r\n";
		
		$msg .= 'Content-type: text/plain; charset=utf-8'."\r\n\r\n";
		$msg .= stripslashes($message_text)."\r\n";
		
		$msg .= "\r\n\r\n--".$boundary."\r\n";
		
		$msg .= 'Content-type: text/html; charset=utf-8'."\r\n\r\n";;
		$msg .= preg_replace($img_url_mask,$img_url_replace,stripslashes($message_html))."\r\n";
		
		$msg .= "\r\n\r\n--".$boundary."--";
		
		$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
	
		// To send HTML mail, the Content-type header must be set
		$headers  = 'From: '.$from."\r\n";
		$headers .= 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-Type: multipart/alternative; boundary='.$boundary."\r\n";
		$headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
		
		// Mail it
		return mail($to, $subject, $msg, $headers);
	}
}

/**
 * Send an email (html mime mail)
 *
 * @access	public
 * @return	bool
 */	
if ( ! function_exists('send_mime_mail'))
{
	function send_mime_mail($to, $subject = 'Test email', $message = 'Hello World', $from='admin@localhost', $files='')
	{
	
	  require_once($_SERVER['DOCUMENT_ROOT'].'/html_mime_mail/htmlMimeMail.php');
	  
	  $file_dir=$_SERVER['DOCUMENT_ROOT'].'/upload/';
		
		$mail = new htmlMimeMail();
		
		// message
		$msg = '<html>'."\r\n";
		$msg .= '<head>'."\r\n";
		$msg .= '  <title>'.$subject.'</title>'."\r\n";
		$msg .= '</head>'."\r\n";
		$msg .= '<body>'."\r\n";
		$msg .= $message."\r\n";
		$msg .= '</body>'."\r\n";
		$msg .= '</html>'."\r\n";
		
		$mail->setHtml($msg);
		
		
			
			if(!empty($files)){
				foreach($files as $item){
					if(!empty($item)){
						$attachment = $mail->getFile($file_dir.$item);	
						$mail->addAttachment($attachment, $item);
					}
				}
			}
			
			$mail->setTextCharset('utf-8');
			$mail->setHtmlCharset('utf-8');
			$mail->setHeadCharset('utf-8');
		
			$mail->setFrom($from);
			$mail->setSubject($subject);
		
		// Mail it
		return $mail->send(array($to));
	}
}


/* End of file email_helper.php */
/* Location: ./system/helpers/email_helper.php */