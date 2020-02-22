<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Bookmark_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	public function add_bookmark($data){
		$store_id=$data['store_id'];
		$event_id=$data['event_id'];
		$user_id=$data['user_id'];
		$result=array();
		$is_bookmarked=$this->is_already_bookmarked($data);
		if($is_bookmarked>0){
			$update_data=array( 'store_id' => $store_id,
								'event_id' => $event_id,
								'user_id' => $user_id,
								'creation_time' => milliseconds()
								);
			$this->db->where('user_id',$user_id);
			if($store_id==''){
			$this->db->where('event_id',$event_id);
			}
			if($event_id==''){
				$this->db->where('store_id',$store_id);
			}
			$result = $this->db->update('bookmark',$update_data);
			$return_id = $this->db->affected_rows();
		}else{
			$insert_data=array( 'store_id' => $store_id,
								'event_id' => $event_id,
								'user_id' => $user_id,
								'creation_time' => milliseconds()
								);
			$result = $this->db->insert('bookmark',$insert_data);
			$return_id = $this->db->insert_id();
		}
		return $return_id;
	}

	public function is_already_bookmarked($data){
		$store_id=$data['store_id'];
		$event_id=$data['event_id'];
		$user_id=$data['user_id'];
		$this->db->where('user_id',$user_id);
		if($store_id==''){
			$this->db->where('event_id',$event_id);
		}
		if($event_id==''){
			$this->db->where('store_id',$store_id);
		}$store_id=$data['store_id'];
		$event_id=$data['event_id'];
		$num=$this->db->get('bookmark')->num_rows();
		return $num;
	}

	public function get_bookmark($data){
		$user_id=$data['user_id'];
		$result=array();
		$result=$this->db->query("SELECT b.*,
								 se.title event_title,
								 se.description event_description,
								 se.image event_image,
								 se.vanue event_vanue,
								 se.time event_time,
								 se.tags event_tags,
								 se.address event_address,
								 sm.name store_name,
								 sm.description store_description,
								 sm.thumbnail store_image,
								 sm.address store_address,
								 sm.rating store_rating,
								 sm.reviews store_reviews,
								 sm.store_type store_type,
								case 
									when b.event_id=0 then 'store' 
									when b.store_id=0 then 'event' 
								END as type from bookmark as b 
								left join store_master as sm on sm.id=b.store_id
								left join store_events as se on se.id=b.event_id
								where b.status=0 and b.user_id=$user_id 
								order by b.creation_time desc ")->result_array(); 
		return $result;
	}

	public function remove_from_bookmarks($data){
		$store_id=$data['store_id'];
		$event_id=$data['event_id'];
		$user_id=$data['user_id'];
		if($event_id==''){
			$this->db->where("user_id",$data['user_id']);
			$this->db->where("store_id",$data['store_id']);
			$this->db->delete("bookmark");
		}		
		if($store_id==''){
			$this->db->where("user_id",$data['user_id']);
			$this->db->where("event_id",$data['event_id']);
			$this->db->delete("bookmark");
		}	
	}

	
}
