<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model_auth {
	function __construct()
    {
        $this->CI =& get_instance();
    }
	
	public function index() {
		$perm =  $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();

		if($this->CI->router->fetch_module() == 'data_model') {
			
			/*if($this->CI->router->fetch_class() == "post_like"){
				$header = getallheaders();
				if(array_key_exists('dams_auth_basic', getallheaders())){
					$data =  (array)json_decode($header['dams_auth_basic']);
					$info['user_id'] = ($this->key_check('user_id',$data))?$data['user_id']:"";
				    $info['device_type'] = ($this->key_check('device_type',$data))?$data['device_type']:""; // 1 for androiod 2 for ios 
				    $info['device_id'] = ($this->key_check('device_id',$data))?$data['device_id']:""; 
				    $info['device_tokken'] = ($this->key_check('device_tokken',$data))?$data['device_tokken']:""; 
				    $info['session_id'] = ($this->key_check('session_id',$data))?$data['session_id']:""; 
				    $info['setup_version'] = ($this->key_check('setup_version',$data))?$data['setup_version']:""; 
				    $info['device_info'] = ($this->key_check('device_info',$data))?$data['device_info']:""; 
				   
				    if(!$data){
				    	echo json_encode(array("auth_code"=>100000,"message"=>"API Basic Authentication Failure."));
				    	die;
				    }
				    //check session tokken in database w.r.t user_id in db 
				    $this->CI->db->where('user_id',$info['user_id']);
				    $this->CI->db->where('session_id',$info['session_id']);
				    $out = $this->CI->db->get('user_active_session')->row_array();
				    if(!$out){
				    	echo json_encode(array("auth_code"=>100100,"message"=>"User Session Expire."));
				    	die;
				    }

				    // check version 
			    	$this->CI->db->where('id',1);
					$version = $this->CI->db->get('version_control')->row();
					if($info['device_type'] == 1 && $info['setup_version'] != $version->android){
						echo json_encode(array("auth_code"=>100102,"message"=>"Please update your app from store."));
			    		die;
					}

					if($info['device_type'] == 2 && $info['setup_version'] != $version->ios){
						echo json_encode(array("auth_code"=>100102,"message"=>"Please update your app from store."));
			    		die;
					}
				}else{
					echo json_encode(array("auth_code"=>100000,"message"=>"API Basic Authentication Failure."));
				    die;
				}
			}*/
		}
	}

	private function key_check($name,$array){
		if(!array_key_exists($name, $array) OR $array[$name] == "" ){
			echo json_encode(array("auth_code"=>100000,"message"=>"The key $name missing or is blank.API Basic Authentication Failure."));
	    	die;
		}
		return true;
	}
}