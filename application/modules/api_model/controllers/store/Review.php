<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('review_model');
		$this->load->helper("services");
		$this->load->helper("message_sender");
		$this->load->library('session');
	}

	public function upload_file($key){ //print_r($_FILES);
		if($_FILES){
			if ($_FILES[$key]["size"] > 1000000*50) {
			    return array( 'status'=>false,'message'=>'Sorry, your file is too large. size should below 50mb');
			}
			if($key=='image'){
				$file_path = $_SERVER['DOCUMENT_ROOT'].'/klosebuyer/uploads/reviews_images/'.$_FILES[$key]["name"];
			}
			if(move_uploaded_file($_FILES[$key]["tmp_name"], $file_path)){
				return base_url().'uploads/reviews_images/'.$_FILES[$key]["name"];
			}else{
				return array('status'=>false,'message'=>'Server issue not able to upload file.');
			}
			
		}else{
			return array('status'=>false,'message'=>'Not able to upload file.');
		}
	}

	public function add_review(){ //store_id,user_id,image,review
		$this->validate_add_review();
		if(isset($_FILES['image']['name'])!=''){
			$image=$this->upload_file('image');
		}else{
			$image='';
		}
		$insert_data=array(
					'store_id'=>$this->input->post('store_id'),
					'user_id'=>$this->input->post('user_id'),
					'review'=>$this->input->post('review'),
					'title'=>$this->input->post('title'),
					'rating'=>$this->input->post('rating'),
					'image' => $image,
					'creation_time'=> milliseconds()
					);
		$result = $this->review_model->add_review($insert_data);
		if($result){
			return_data(true,'Review added successfully.',array());
		}
		return_data(false,'failed.',array());
	}


	private function validate_add_review(){
		
		post_check();

		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		$this->form_validation->set_rules('review','review', 'trim|required');
		$this->form_validation->set_rules('title','title', 'trim|required');
		$this->form_validation->set_rules('rating','rating', 'trim|required');
		/* parent comment id  if key not found */

		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	public function get_reviews(){ //store_id,user_id,image,review
		$this->validate_get_review();
		$result = $this->review_model->get_reviews($this->input->post());
		return_data(true,'Success.',$result);
	}

	private function validate_get_review(){
		
		post_check();

		$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		/* parent comment id  if key not found */

		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	

	

}