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
 * @copyright  2011 Joffrey Jaffeux
 * @license    DBAD License
 */

class PostmarkInbound {

	public function __construct($json) 
	{
		$this->json = $this->get_json($json);
		$this->source = $this->json_to_array($json);
	}

	public function subject() 
	{
		return $this->source->Subject;
	}

	public function from() 
	{
		return str_replace('"', '', $this->source->From);
	}

	public function from_name()
	{
		return $this->from_name_and_email_parser(0);
	}

	public function from_email() 
	{
		return $this->from_name_and_email_parser(1);
	}

	// 0 name, 1 email
	private function from_name_and_email_parser($match = NULL)
	{
		if(preg_match('/^.+<(.+)>$/', $this->from(), $matches) AND $match !== NULL)
		{
			return trim(rtrim(strip_tags($matches[$match])));
		}

		return FALSE;
	}

	public function to()
	{
		return $this->source->To;
	}

	public function bcc()
	{
		return $this->source->Bcc;
	}

	public function cc()
	{
		return $this->source->Cc;
	}

	public function reply_to()
	{
		return $this->source->ReplyTo;
	}

	public function mailbox_hash()
	{
		return $this->source->MailboxHash;
	}

	public function tag()
	{
		return $this->source->Tag;
	}

	public function message_id()
	{
		return $this->source->MessageID;
	}

	public function text_body()
	{
		return $this->source->TextBody;
	}

	public function html_body()
	{
		return $this->source->HtmlBody;
	}

	public function headers($asked_name = 'Date')
	{
		foreach($this->source->Headers as $header) {
			if($header->Name == $asked_name) {
				return $header->Value;
			}
		}

		return $this->from();
	}

	public function attachments()
	{
		foreach ($this->source->Attachments as &$attachment) {
			$attachment = New Attachment($attachment);
		}

		return $this->source->Attachments;
	}

	public function has_attachment()
	{
		if( ! $this->attachments())
		{
			return FALSE;
		}

		return TRUE;
	}

	private function get_json($json) 
	{
		if (empty($json)) {
			throw new Exception('Posmark Inbound Error: you must provide json data');
		}

		return $json;
	}

	private function json_to_array() 
	{
		$source = json_decode($this->json, FALSE);

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

}

Class Attachment extends PostmarkInbound {

	public function __construct($attachment)
	{
		$this->attachment = $attachment;
	}

	public function name()
	{
		return $this->attachment->Name;
	}

	public function content_type()
	{
		return $this->attachment->ContentType;
	}

	public function content_length()
	{
		return $this->attachment->ContentLength;
	}

	public function read()
	{
		return base64_decode($this->attachment->Content);
	}

	public function download($dir = '', $allowed_content_types = array(), $max_content_length = '')
	{
		if(empty($dir)) {
			throw new Exception('Posmark Inbound Error: you must provide the upload path');
		}

		if( ! empty($max_content_length) AND $this->content_length() > $max_content_length) {
			throw new Exception('Posmark Inbound Error: the file size is over '.$max_content_length);
		}

		if( ! empty($allowed_content_types) AND ! in_array($this->content_type(), $allowed_content_types)) {
			throw new Exception('Posmark Inbound Error: the file type '.$this->content_type().' is not allowed');
		}

		return file_put_contents($dir . $this->name(), $this->read());
	}

}

/* End of file postmark_inbound.php */