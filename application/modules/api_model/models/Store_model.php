<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Store_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	public function get_store_detail($data){
		$store_id=$data['store_id'];
		$user_id=$data['user_id'];
		$result=array();

		//stores
		$result=$this->db->query("SELECT *,
								(select count(id) from store_reviews where store_id=$store_id) as reviews,
								(SELECT ROUND(AVG(rating),1) FROM store_reviews where store_id=$store_id) as
								avg_rating
								 from store_master 
								 where status=0 and id=$store_id 
								 ")->row_array(); 
		if($result){
			$bookmarked = $this->is_already_bookmarked(["user_id"=>$user_id,"store_id"=>$result['id']]);
			$result['is_bookmark']= '0';
			if($bookmarked){
				$result['is_bookmark']='1';
			}
		}

		//store_images
		$result['store_images']=$this->db->query("SELECT * from store_images where store_id=$store_id ")->result_array();

		//store_offers
		$result['offers']=$this->db->query("SELECT *,(price*(100-discount)/100) as discount_price from store_offers where status=0 and store_id=$store_id ")->result_array();

		//store_events 
		$result['events']=$this->db->query("SELECT * from store_events where status=0 and store_id=$store_id ")->result_array();

		//payement_options
		$payment_options = $result['payment_option'];
		$payment_options = explode(',', $payment_options); 
		foreach($payment_options as $payment_id){
			$payment=$this->db->query("SELECT * from store_payment_option where id=$payment_id ")->row_array(); 
			$payment_option[]=$payment;
			$result['payment_options']=$payment_option;
		}
		

		return $result;
	}

	public function is_already_bookmarked($data){
		$this->db->where('user_id',$data['user_id']);
		$this->db->where('store_id',$data['store_id']);
		$result = $this->db->get("bookmark")->row_array();
		if($result){
			return TRUE;
		}
		return FALSE;
	}

	
}
