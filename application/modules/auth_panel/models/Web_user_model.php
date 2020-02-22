<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Web_user_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }

    //====================dashboard details=====================================
      public function user_count()
    {
    return $query = $this->db->get('users')->num_rows();
    }
    public function total_cat()
    {
        return $query = $this->db->get('category')->num_rows();
    }
    public function total_subcat()
    {
        return $query = $this->db->get('sub_category_list')->num_rows();
    }
    //====================End dashboard details=====================================
    
    
    
    public function get_user_basic_detail($id) {
		$this->db->where('id',$id);
		return $query = $this->db->get('users')->row_array();
		
	}
    public function get_user_profile($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('users')->row_array();
			
			//echo "<pre>";print_r($query);die;  
			return $query;  
    }
	
	
	public function get_user_instructor_details($id) {
				
		//$this->db->join('course_instructor_information', 'users.id = request_helping_hand.request_id');
		$this->db->where('user_id',$id);
		$query = $this->db->get('course_instructor_information')->row_array();
		  
			
		if($query){
		//echo "<pre>";print_r($query);die;  
		return $query; 
		}
		else{
			return false;
		}
    }
	
	public function get_instructor_rating_details_by_id($id) {
		$this->db->select("course_instructor_rating.*,users.name,DATE_FORMAT(FROM_UNIXTIME(course_instructor_rating.creation_time/1000), '%d-%m-%Y %h:%i:%s') as creation_time");
		$this->db->join('users','users.id=course_instructor_rating.user_id');
    	$this->db->where('course_instructor_rating.id',$id);
    	return $this->db->get('course_instructor_rating')->row_array();

    }


    public function update_user_status($status,$id) {
    	if($status == 'delete') {
    		$data=array('status'=>2);
    		$this->db->where('id',$id);
			return $this->db->update('users',$data);

    	} elseif($status == 'disable') {
    		$data=array('status'=>1);
    		$this->db->where('id',$id);
			return $this->db->update('users',$data);

    	} elseif($status == 'enable') {
    		$data=array('status'=>0);
    		$this->db->where('id',$id);
			return $this->db->update('users',$data);

    	}
    }
	
	public function add_instructor_id($id){  
		$data = array('user_id'=>$id);
		$this->db->where('user_id',$id);
		$checkStatus = $this->db->get('course_instructor_information')->row_array();
		if(empty($checkStatus)){		
			$result = $this->db->insert("course_instructor_information",$data);
		}
		else{
		
		}		
	}
	
	public function delete_review($id) {
		
    	$this->db->where('id',$id);
		$this->db->delete('course_instructor_rating');
    	return true;

    }


}