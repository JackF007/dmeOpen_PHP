<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Users_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/***
 *       _____                      _    _                  
 *      / ____|                    | |  | |                 
 *     | (___    __ _ __   __ ___  | |  | | ___   ___  _ __ 
 *      \___ \  / _` |\ \ / // _ \ | |  | |/ __| / _ \| '__|
 *      ____) || (_| | \ V /|  __/ | |__| |\__ \|  __/| |   
 *     |_____/  \__,_|  \_/  \___|  \____/ |___/ \___||_|   
 *                                                          
 *                                                          
 */

	public function save_user($data){
		//echo '<pre>'; print_r($data); die;
		$data['creation_time'] = milliseconds();
		$this->db->insert('users', $data);
		$user_id = $this->db->insert_id();
		return $user_id;
	}

	public function update_user($data){
		$data['creation_time'] = milliseconds();
		$this->db->where('id',$data['id']);
		$result=$this->db->update('users',$data);
		if($result){
			return true;	
		}else{
			return false;
		}
	}
	/***
 *       _____        _     _    _                  
 *      / ____|      | |   | |  | |                 
 *     | |  __   ___ | |_  | |  | | ___   ___  _ __ 
 *     | | |_ | / _ \| __| | |  | |/ __| / _ \| '__|
 *     | |__| ||  __/| |_  | |__| |\__ \|  __/| |   
 *      \_____| \___| \__|  \____/ |___/ \___||_|   
 *                                                  
 *                                                  
 */
	public function get_user($id){
		$this->db->where("id",$id);
		return $this->db->get('users')->row_array();
	}

	public function check_email_exist($data){
		$id=$data['id'];
		$email=$data['email'];
		if($email!=''){
			$query = $this->db->query("SELECT `id`,`email` 
									   FROM `users` 
									   WHERE `id`!='$id' AND `email`='$email'");
			return $query->num_rows();
		}else{
			return 0;
		}
	}

	public function is_email_exist($data){
		$email=$data['email'];
		if($email!=''){
			$query = $this->db->query("SELECT `id`,`email` 
									   FROM `users` 
									   WHERE `email`='$email'");
			return $query->row_array();
		}else{
			return false;
		}
	}

	public function check_mobile_exist($data){
		$id=$data['id'];
		$mobile=$data['mobile'];
		$query = $this->db->query("SELECT `id`,`mobile` 
								   FROM `users` 
								   WHERE `id`!='$id' AND `mobile`='$mobile'");
		return $query->num_rows();
	}

/***
 *       _____        _      _____             _                      _    _                  
 *      / ____|      | |    / ____|           | |                    | |  | |                 
 *     | |  __   ___ | |_  | |     _   _  ___ | |_  ___   _ __ ___   | |  | | ___   ___  _ __ 
 *     | | |_ | / _ \| __| | |    | | | |/ __|| __|/ _ \ | '_ ` _ \  | |  | |/ __| / _ \| '__|
 *     | |__| ||  __/| |_  | |____| |_| |\__ \| |_| (_) || | | | | | | |__| |\__ \|  __/| |   
 *      \_____| \___| \__|  \_____|\__,_||___/ \__|\___/ |_| |_| |_|  \____/ |___/ \___||_|   
 *                                                                                            
 *                                                                                            
 */

	public function get_social_user($info){
		//$this->db->where('social_type',$info['social_type']);
		//$this->db->where('social_tokken',$info['social_tokken']);
		//$this->db->where('mobile',$info['mobile']);
		$this->db->or_where('email',$info['email']);
		return $this->db->get('users')->row_array();
	}

	public function is_user_social($info){
		$mobile=$info['mobile'];
		$query = $this->db->query("SELECT * FROM users where mobile='$mobile' 
		 							and is_social=1
		 							and password=''")->row_array();
		return $query;
	}

	public function get_custum_user($info){
		$mobile=$info['mobile'];
		$password=$info['password'];
		$query = $this->db->query("SELECT * FROM users where mobile='$mobile' 
		 							and password='$password'
		 							and password !='' ")->row_array();
		return $query;
	}

	public function update_device_tokken($info){
		$array  = array(
			"device_type"=>$info['device_type'],
			"device_tokken"=>$info['device_tokken']
			); 
		$this->db->where('id',$info['id']);
		$this->db->update('users',$array);
	}

	public function update_password($info){
		$update_data['password']=md5($info['password']);
		$update_data['id']=$info['user_id'];
		$this->db->where('id',$update_data['id']);
		$result=$this->db->update('users',$update_data);
		return $result;
	}

	

	public function get_user_from_email($info){
		$this->db->where("email",$info['email']);
		return $this->db->get('users')->row_array();
	}

}