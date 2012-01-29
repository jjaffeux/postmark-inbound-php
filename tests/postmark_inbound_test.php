<?php
 
class PostmarkInbound_test extends \Enhance\TestFixture {
	public function setUp() {
		$this->inbound = new PostmarkInbound(file_get_contents(dirname(__FILE__).'/fixtures/valid_http_post.json'));
	}
	
	public function tearDown() {
		if(file_exists(dirname(__FILE__).'/chart.png')) {
			unlink(dirname(__FILE__).'/chart.png');
		}

		if(file_exists(dirname(__FILE__).'/chart2.png')) {
			unlink(dirname(__FILE__).'/chart2.png');
		}
	}
	
	public function should_have_a_subject() {
		\Enhance\Assert::areIdentical('Hi There', $this->inbound->subject());
	}

	public function should_have_a_bcc() {
		\Enhance\Assert::areIdentical('FBI <hi@fbi.com>', $this->inbound->bcc());
	}

	public function should_have_a_cc() {
		\Enhance\Assert::areIdentical('Your Mom <hithere@hotmail.com>', $this->inbound->cc());
	}

	public function should_have_a_reply_to() {
		\Enhance\Assert::areIdentical('new-comment+sometoken@yeah.com', $this->inbound->reply_to());
	}

	public function should_have_a_mailbox_hash() {
		\Enhance\Assert::areIdentical('moitoken', $this->inbound->mailbox_hash());
	}

	public function should_have_a_tag() {
		\Enhance\Assert::areIdentical('yourit', $this->inbound->tag());
	}

	public function should_have_a_message_id() {
		\Enhance\Assert::areIdentical('a8c1040e-db1c-4e18-ac79-bc5f64c7ce2c', $this->inbound->message_id());
	}

	public function should_be_from_someone() {
		\Enhance\Assert::areIdentical('Bob Bobson <bob@bob.com>', $this->inbound->from());
	}

	public function should_pull_out_the_from_email() {
		\Enhance\Assert::areIdentical('bob@bob.com', $this->inbound->from_email());
	}

	public function should_pull_out_the_from_name() {
		\Enhance\Assert::areIdentical('Bob Bobson', $this->inbound->from_name());
	}

	public function should_have_a_html_body() {
		\Enhance\Assert::areIdentical('<p>We no speak americano</p>', $this->inbound->html_body());
	}

	public function should_have_a_text_body() {
		\Enhance\Assert::areIdentical("\nThis is awesome!\n\n", $this->inbound->text_body());
	}

	public function should_be_to_someone() {
		\Enhance\Assert::areIdentical('api-hash@inbound.postmarkapp.com', $this->inbound->to());
	}

	public function default_header_should_have_date() {
		\Enhance\Assert::areIdentical('Thu, 31 Mar 2011 12:01:17 -0400', $this->inbound->headers());
	}

	public function should_have_header_date() {
		\Enhance\Assert::areIdentical('Thu, 31 Mar 2011 12:01:17 -0400', $this->inbound->headers("Date"));
	}

	public function should_have_header_mime_version() {
		\Enhance\Assert::areIdentical('1.0', $this->inbound->headers("MIME-Version"));
	}

	public function should_have_header_received_spf() {
		\Enhance\Assert::areIdentical('None (no SPF record) identity=mailfrom; client-ip=209.85.212.52; helo=mail-vw0-f52.google.com; envelope-from=bob@bob.com; receiver=4e8d6dec234dd90018e7bfd2b5d79107@inbound.postmarkapp.com', $this->inbound->headers("Received-SPF"));
	}

	public function should_have_two_attachments() {
		\Enhance\Assert::areIdentical(2, count($this->inbound->attachments()->attachments));
	}

	public function should_have_attachment() {
		\Enhance\Assert::areIdentical(TRUE, $this->inbound->has_attachments());
	}
	
	public function attachment_should_have_content_length() {
		foreach($this->inbound->attachments() as $a) {
			\Enhance\Assert::isNotNull($a->content_length());
		}
	}

	public function attachment_should_have_content_type() {
		foreach($this->inbound->attachments() as $a) {
			\Enhance\Assert::isNotNull($a->content_type());
		}
	}

	public function attachment_should_have_name() {
		foreach($this->inbound->attachments() as $a) {
			\Enhance\Assert::isNotNull($a->name());
		}
	}

	public function attachment_should_download() {
		$attachments = $this->inbound->attachments();
		foreach($attachments as $a) {
			$a->download(dirname(__FILE__).'/');
		}

		\Enhance\Assert::isTrue(file_exists(dirname(__FILE__).'/chart.png'));
		\Enhance\Assert::isTrue(file_exists(dirname(__FILE__).'/chart2.png'));
	}

	public function should_return_first_attachment() {
		$attachments = $this->inbound->attachments();
		$first_attachment = $attachments->get(0);

		\Enhance\Assert::areIdentical('chart.png', $first_attachment->name());
		\Enhance\Assert::areIdentical('image/png', $first_attachment->content_type());
		\Enhance\Assert::areIdentical(2000, $first_attachment->content_length());

		$first_attachment->download(dirname(__FILE__).'/');
		\Enhance\Assert::isTrue(file_exists(dirname(__FILE__).'/chart.png'));
	}

	public function should_return_second_attachment() {
		$attachments = $this->inbound->attachments();
		$second_attachment = $attachments->get(1);

		\Enhance\Assert::areIdentical('chart2.png', $second_attachment->name());
		\Enhance\Assert::areIdentical('image/png', $second_attachment->content_type());
		\Enhance\Assert::areIdentical(1000, $second_attachment->content_length());

		$second_attachment->download(dirname(__FILE__).'/');
		\Enhance\Assert::isTrue(file_exists(dirname(__FILE__).'/chart2.png'));
	}

	public function souldnt_retourn_third_attachment() {
		$attachments = $this->inbound->attachments();
		$third_attachment = $attachments->get(2);

		\Enhance\Assert::isFalse($third_attachment);
	}

}