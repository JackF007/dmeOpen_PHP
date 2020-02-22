<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('store_model');
		$this->load->helper("services");
		$this->load->library('session');
	}

	public function get_store_detail(){ //user_id,store_id
		$this->validate_get_store_detail();
		$user_id=$this->input->post('user_id');
		if($user_id){
			$result=$this->store_model->get_store_detail($this->input->post());
			return_data(true,'Success.',$result);
		}else{
			return_data(false,'User ID required.',array());
		}
		
	}


	private function validate_get_store_detail(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}



}

