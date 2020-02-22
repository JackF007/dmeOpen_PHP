<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function send_welcome_email($data){

		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.gmail.com',
			'smtp_port' => 587,
			'smtp_user' => 'donotreply@emedicoz.com',
			'smtp_pass' => 'emed@321',
		);

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from("donotreply@emedicoz.com", 'eMedicoz!');
		$this->email->to($data['email']);
		$this->email->subject('Welcome to eMedicoz!');
		$this->email->message($this->load->view('emailer/welcome_new_registration', $data, True));
		$this->email->set_mailtype("html");
		$this->email->send();

	}
}	