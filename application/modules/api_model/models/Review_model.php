<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Review_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	
	

	public function add_review($data){ //store_id,user_id,review,image
		$store_id=$data['store_id'];
		$this->db->insert('store_reviews',$data);
    	$insert_id = $this->db->insert_id();
    	//update rating and reviews count in store_master
    	$sql="SELECT ROUND(AVG(rating),1) as rating,count(id) as reviews FROM store_reviews where store_id=$store_id";
    	$result=$this->db->query($sql)->row_array();
    	if($result){
	    	$update_data=array('rating'=>$result['rating'],'reviews'=>$result['reviews']);
	    	$this->db->where('id',$store_id);
	    	$this->db->update('store_master',$update_data);
	    }
    	return $insert_id;
	}

	public function get_reviews($data){ //store_id
		$store_id=$data['store_id'];
		$sql ="SELECT sr.*,u.name as username,u.profile_picture as profile_picture from store_reviews as sr 
				join users as u on u.id=sr.user_id where sr.store_id=$store_id";
		$result=$this->db->query($sql)->result_array();
    	return $result;
	}

	

}