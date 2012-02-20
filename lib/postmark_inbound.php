<?php

/**
 * This is a simple API wrapper for Postmark Inbound Hook (http://developer.postmarkapp.com/developer-inbound.html)
 *
 * Basic Usage:
 * 
 *     $inbound = New PostmarkInbound(file_get_contents('php://input'));
 * 	OR for local testing
 *     $inbound = New PostmarkInbound(file_get_contents(/path/to/file.json'));
 * 
 * @package    PostmarkInbound
 * @author     Joffrey Jaffeux
 * @copyright  2012 Joffrey Jaffeux
 * @license    DBAD License
 */

class PostmarkInbound {

	public static $source = array();
	public static $json = '';

	public function __construct($json) {
		if(empty($json)) {
			throw new Exception('Posmark Inbound Error: you must provide json data');
		}

		self::$json = $json;
		self::$source = $this->json_to_array(self::$json);
	}

	public static function json() {
		return self::$json;
	}

	public static function source() {
		return self::$source;
	}

	private function json_to_array() {
		$source = json_decode(self::$json, FALSE);

		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $source;
			break;
			case JSON_ERROR_SYNTAX:
				throw new Exception('Posmark Inbound Error: json format is invalid');
			break;
			default:
				throw new Exception('Posmark Inbound Error: json error');
			break;
		}
	}

	public function subject() {
		return self::source()->Subject;
	}

	public function from() {
		return str_replace('"', '', self::source()->From);
	}

	public function from_name() {
		$name = $this->from_name_and_email_parser(0);
		
		if(filter_var($name, FILTER_VALIDATE_EMAIL)) {
			return FALSE;
		}
		else {
			return $name;
		}
	}

	public function from_email() 
	{
		$email =  $this->from_name_and_email_parser(1);

		if( ! empty($email) AND filter_var($email, FILTER_VALIDATE_EMAIL)) {
			
			return $email;
		}
		else {
			return FALSE;
		}
	}

	// 0 name, 1 email
	private function from_name_and_email_parser($match = NULL) {
		if(preg_match('/^.+<(.+)>$/', $this->from(), $matches) AND $match !== NULL) {
			return trim(rtrim(strip_tags($matches[$match])));
		} 
		else {
			return trim(rtrim(strip_tags($this->from())));
		}
	}

	public function to() {
		return self::source()->To;
	}

	public function bcc() {
		return self::source()->Bcc;
	}

	public function cc() {
		return self::source()->Cc;
	}

	public function reply_to() {
		return self::source()->ReplyTo;
	}

	public function mailbox_hash() {
		return self::source()->MailboxHash;
	}

	public function tag() {
		return self::source()->Tag;
	}

	public function message_id() {
		return self::source()->MessageID;
	}

	public function text_body() {
		return self::source()->TextBody;
	}

	public function html_body() {
		return self::source()->HtmlBody;
	}

	public function spam($name = 'X-Spam-Status') {
		foreach(self::source()->Headers as $sections => $values) {
			foreach($values as $key => $value) {
				if($key == $name) {
					return $value;
				}
			}
		}

		return FALSE;
	}

	public function headers($name = 'Date') {
		foreach(self::source()->Headers as $header) {
			if(isset($header->Name) AND $header->Name == $name) {
				return $header->Value;
			}
		}

		return FALSE;
	}

	public function attachments() {
		return New Attachments(self::source()->Attachments);
	}

	public function has_attachments() {
		if( ! $this->attachments()) {
			return FALSE;
		}

		return TRUE;
	}

}

Class Attachments extends PostmarkInbound implements Iterator{

	public function __construct($attachments) {
		$this->attachments = $attachments;
		$this->position = 0;
	}

	function get($key) {
		$this->position = $key;

		if( ! empty($this->attachments[$key])) {
			return New Attachment($this->attachments[$key]);
		} else {
			return FALSE;
		}
	}

	function rewind() {
		$this->position = 0;
	}

	function current() {
		return New Attachment($this->attachments[$this->position]);
	}

	function key() {
		return $this->position;
	}

	function next() {
		++$this->position;
	}

	function valid() {
		return isset($this->attachments[$this->position]);
	}

}

Class Attachment extends Attachments {

	public function __construct($attachment) {
		$this->attachment = $attachment;
	}

	public function name() {
		return $this->attachment->Name;
	}

	public function content_type() {
		return $this->attachment->ContentType;
	}

	public function content_length() {
		return $this->attachment->ContentLength;
	}

	public function read() {
		return base64_decode($this->attachment->Content);
	}
	
	/**
	 * download
	 *
	 * @param array $options 
	 * @return integer | FALSE
	 *
	 * List of options :
	 * 
	 * directory, path where you want to save the file
	 * allowed_content_type, optionnal, if you want to restrict download to certain file types, see
	 * http://developer.postmarkapp.com/developer-build.html#attachments for a list
	 * max_content_length, optionnal, if you want to restrict filesize
	 *
	 */
	public function download($options = array()) {
		if(empty($options['directory'])) {
			throw new Exception('Posmark Inbound Error: you must provide the upload path');
		}

		if( ! empty($options['max_content_length']) AND $this->content_length() > $options['max_content_length']) {
			throw new Exception('Posmark Inbound Error: the file size is over '.$options['max_content_length']);
		}

		if( ! empty($options['allowed_content_types']) AND ! in_array($this->content_type(), $options['allowed_content_types'])) {
			throw new Exception('Posmark Inbound Error: the file type '.$this->content_type().' is not allowed');
		}

		if(file_put_contents($options['directory'] . $this->name(), $this->read()) === FALSE) {
			throw new Exception('Posmark Inbound Error: cannot save the file, check path and rights');
		}
	}

}
/* End of file postmark_inbound.php */