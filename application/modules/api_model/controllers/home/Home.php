	<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('home_model');
		$this->load->helper("services");
		$this->load->library('session');
	}

	public function get_category_list(){ //user_id
		$this->validate_category_list();
		$user_id=$this->input->post('user_id');
		if($user_id){
			$result=$this->home_model->get_category_list($this->input->post());
			return_data(true,'Success.',$result);
		}else{
			return_data(false,'User ID required.',array());
		}
		
	}

	public function get_sub_category_list(){ //user_id,category_id
		$this->validate_sub_category_list();
		$user_id=$this->input->post('user_id');
		if($user_id){
			$result=$this->home_model->get_sub_category_list($this->input->post());
			return_data(true,'Success.',$result);
		}else{
			return_data(false,'User ID required.',array());
		}
		
	}

	private function validate_sub_category_list(){
		post_check();
		$this->form_validation->set_rules('category_id','category_id', 'trim|required');
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	private function validate_category_list(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	public function get_banner(){ //user_id,
		$this->validate_category_list();
		$user_id=$this->input->post('user_id');
		if($user_id){
			$result=$this->home_model->get_advertisement_banner($this->input->post());
			return_data(true,'Success.',$result);
		}else{
			return_data(false,'User ID required.',array());
		}		
	}

	public function offer_list_by_category(){ //user_id,category_id
		$this->validate_offer_list_by_category();
		$result=$this->home_model->get_offer_list_by_category($this->input->post());
		if($result){
			return_data(true,'Success.',$result);
		}
		
	}
	
	public function get_stores(){ //user_id,category_id
		$this->validate_get_stores();
		$result=$this->home_model->get_stores($this->input->post());
		if($result){
			foreach($result as $r){
				$this->db->select("count(id) as county");
				$this->db->where('store_id',$r['store_id']);
				$reviwes = $this->db->get('store_reviews')->row()->county;
				
				$this->db->select("avg(rating) as county");
				$this->db->where('store_id',$r['store_id']);
				$rating = $this->db->get('store_reviews')->row()->county;
				
				
				$this->db->where('store_id',$r['store_id']);
				$offer = $this->db->get('store_offers')->row_array();
				if($offer){
					
					$r['item']="";
					$r['type']="";
					$r['product']="";
					$r['reviews']=$reviwes;
					$r['discount_price']=$offer['price']-(($offer['price']*$offer['discount'])/100);
					$r['description']=$offer['description'];
					$r['price']=$offer['price'];
					$r['discount']=$offer['discount'];
					$r['valid_for']=$offer['valid_for'];
					
					$r['category']=$offer['category'];
					$r['sub_category']=$offer['sub_category'];
					$r['title']=$offer['title'];
					
					$r['tags']=$offer['tags'];
					$r['"start_date']=$offer['start_date'];
					$r['end_date']=$offer['end_date'];
					$r['creation_time']=$offer['creation_time'];
					$r['status']=$offer['status'];
					
					
					$r['rating']=$rating;
				}else{
					$r['item']=$offer['item'];
					$r['description']=$offer['description'];
					$r['price']=$offer['price'];
					$r['discount']=$offer['discount'];
					$r['valid_for']=$offer['valid_for'];
					$r['type']=$offer['type'];
					$r['category']=$offer['category'];
					$r['sub_category']=$offer['sub_category'];
					$r['title']=$offer['title'];
					$r['product']=$offer['product'];
					$r['tags']=$offer['tags'];
					$r['"start_date']=$offer['start_date'];
					$r['end_date']=$offer['end_date'];
					$r['creation_time']=$offer['creation_time'];
					$r['status']=$offer['status'];
					$r['reviews']=$offer['reviews'];
					$r['discount_price']=$offer['discount_price'];
					$r['rating']=$offer['rating'];
					
				}
				//pre();
				
				$f['']=$r;
			}
			
			return_data(true,'Success.',$r);
		}
		return_data(false,'failure.',array());
	}
	
	private function validate_get_stores(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('category_id','category_id', 'trim|required');
		//$this->form_validation->set_rules('type','type', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	} 


	public function events_list(){ //user_id
		$this->validate_events_list();
		$result=$this->home_model->get_events_list($this->input->post());
		if($result){
			return_data(true,'Success.',$result);
		}
		
	}

	private function validate_events_list(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	public function get_event(){ //user_id,event_id
		$this->validate_get_event();
		$result=$this->home_model->get_event($this->input->post());
		if($result){
			return_data(true,'Success.',$result);
		}
		
	}

	private function validate_get_event(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('event_id','event_id', 'trim|required');
		//$this->form_validation->set_rules('type','type', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	private function validate_offer_list_by_category(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('category_id','category_id', 'trim|required');
		//$this->form_validation->set_rules('type','type', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	//offer filter
	public function get_offer_list_filter(){ //user_id,category,sub_category,sort_by(discount,date,rating),type(offer or event)
		$this->validate_get_offer_list_filter();
		$result =  $this->home_model->offer_and_event_filter($this->input->post());
		if($result){
			return_data(true,'Success.',$result);
		}
		
	}

	private function validate_get_offer_list_filter(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('type','type', 'trim|required');
		//$this->form_validation->set_rules('category','category', 'trim|required');
		//$this->form_validation->set_rules('sub_category','sub_category', 'trim|required');
		$sort_defination = array('rating','date','discount');
		if(!in_array($this->input->post('sort_by'),$sort_defination) ){
			$_POST['sort_by'] = "date";
		}
				
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}	
	}

	//get offers and events by store_id

	public function get_offer_or_event_list_by_store_id(){
		$this->validate_get_offer_or_event_list_by_store_id();
		$result =  $this->home_model->get_offer_or_event_list_by_store_id($this->input->post());
		return_data(true,'Success.',$result);
		
	}
	
	private function validate_get_offer_or_event_list_by_store_id(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('store_id','store_id', 'trim|required');
		$this->form_validation->set_rules('type','offer type', 'trim|required');
		$this->form_validation->set_rules('category','category', 'trim|required');
		$this->form_validation->set_rules('sub_category','Sub category', 'trim|required');	
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}	
	}

	//search stores

	public function search_stores(){
		$this->validate_search_stores();
		$result =  $this->home_model->get_search_stores($this->input->post());
		return_data(true,'Success.',$result);
		
	}

	private function validate_search_stores(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		if($this->input->post('lat') == "" || !array_key_exists('lat',$_POST)){
			$_POST['lat'] = "";
		}
		if($this->input->post('long') == "" || !array_key_exists('long',$_POST)){
			$_POST['long'] = "";
		}
		if($this->input->post('category') == "" || !array_key_exists('category',$_POST)){
			$_POST['category'] = "";
		}	
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}	
	}


}

