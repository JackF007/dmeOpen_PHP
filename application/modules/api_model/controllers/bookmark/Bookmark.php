<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookmark extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('store_model');
		$this->load->model('bookmark_model');
		$this->load->helper("services");
		$this->load->library('session');
	}

	public function add_bookmark(){ //user_id,store_id,event_id
		$this->validate_add_bookmark();
		$result=$this->bookmark_model->add_bookmark($this->input->post());
		return_data(true,'Success.',$result);
		
	}


	private function validate_add_bookmark(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		if($this->input->post('store_id')==''){
			$this->form_validation->set_rules('event_id','event_id', 'trim|required');
		}else{
			$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		}
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	public function get_bookmark(){ //user_id
		$this->validate_get_bookmark();
		$result=$this->bookmark_model->get_bookmark($this->input->post());
		return_data(true,'Success.',$result);
		
	}


	private function validate_get_bookmark(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
	
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}
	public function remove_from_bookmarks(){

		$this->validate_remove_from_bookmarks();
		$this->bookmark_model->remove_from_bookmarks($this->input->post());
		return_data(true,'Post removed from bookmarks.',array());
	}

	private function validate_remove_from_bookmarks(){
		
		post_check();

		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		if($this->input->post('store_id')==''){
			$this->form_validation->set_rules('event_id','event_id', 'trim|required');
		}else{
			$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		}

		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}	



}

