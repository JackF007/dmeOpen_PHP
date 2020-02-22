<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('users_model');
		$this->load->helper("services");
		$this->load->helper("message_sender");
		$this->load->library('session');
	}

	public function upload_file($key){ //print_r($_FILES);
		if($_FILES){
			if ($_FILES[$key]["size"] > 1000000*50) {
			    return array( 'status'=>false,'message'=>'Sorry, your file is too large. size should below 50mb');
			}
			if($key=='profile_picture'){
				$file_path = $_SERVER['DOCUMENT_ROOT'].'/klosebuyer/uploads/profile_image/'.$_FILES[$key]["name"];
			}
			if(move_uploaded_file($_FILES[$key]["tmp_name"], $file_path)){
				return base_url().'uploads/profile_image/'.$_FILES[$key]["name"];
			}else{
				return array('status'=>false,'message'=>'Server issue not able to upload file.');
			}
			
		}else{
			return array('status'=>false,'message'=>'Not able to upload file.');
		}
	}

/***
 *       _____  _                             
 *      / ____|(_)                            
 *     | (___   _   __ _  _ __   _   _  _ __  
 *      \___ \ | | / _` || '_ \ | | | || '_ \ 
 *      ____) || || (_| || | | || |_| || |_) |
 *     |_____/ |_| \__, ||_| |_| \__,_|| .__/ 
 *                  __/ |              | |    
 *                 |___/               |_|    
 */

	public function index(){ //mobile,password,is_social,device_type,device_tokken,email,name

		$this->validate_registration();
		$_POST['password'] =  md5($this->input->post('password')); 
		$_POST['name'] = ucwords(strtolower($this->input->post('name')));
		$_POST['email'] = strtolower($this->input->post('email'));
		$user_data = $this->input->post();
		$return_id =  $this->users_model->save_user($user_data);
		if($return_id){
			return_data(true,'Registration successful.',$this->users_model->get_user($return_id));
		}
		return_data(false,'Registration Failed.',array());
	}

	private function validate_registration(){

		post_check();
		$this->form_validation->set_message('is_unique', '%s already exist.');
		$this->form_validation->set_rules('name','name', 'trim|required');
		$this->form_validation->set_rules('email','Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('mobile','Mobile', 'trim|required|is_unique[users.mobile]');
		$this->form_validation->set_rules('is_social','is_social', 'trim|required');
		$this->form_validation->set_rules('device_type','device_type', 'trim|required');

		//if($this->input->post('is_social') == 1){
			//$this->form_validation->set_rules('social_type','social_type', 'trim|required');
			//$this->form_validation->set_rules('social_tokken','social_tokken', 'trim|required');	
			//$_POST['password'] = ""; 	
		//}else{
			$this->form_validation->set_rules('password','password', 'trim|required');
			$_POST['social_type'] = "";
		//}

		if($this->input->post('device_type') == 1 || $this->input->post('device_type') == 2 ){
			$this->form_validation->set_rules('device_tokken','device_tokken', 'trim|required');
		}	

		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}

	}

	public function is_email_exist(){ //email

		$this->validate_email_exist();
		$result = $this->users_model->is_email_exist($this->input->post());
		if($result){
			$user_details=$this->users_model->get_user($result['id']);
			return_data(true,'Email already Exist',$user_details);
		}
			return_data(false,'email not exist.',array());
	}

	private function validate_email_exist(){

		post_check();
		//$this->form_validation->set_message('is_unique', '%s number already registered');
		$this->form_validation->set_rules('email','email', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}

	}

	public function signup_otp(){ //mobile,c_code

		$this->validate_signup_otp();
		$user_data = $this->input->post();
		$user_data['otp'] = (string)rand(1001, 9999);
        $msg = "Dear User, Your OTP for Klose Buyer user mobile verification is " . $user_data['otp'];
		$sent_sms = $this->send_sms($user_data['mobile']);
		if($sent_sms){
			return_data(true,'OTP has been sent',$user_data);
		}
		return_data(false,'Send otp failure.',array());
	}

	private function validate_signup_otp(){

		post_check();
		$this->form_validation->set_message('is_unique', '%s number already registered');
		$this->form_validation->set_rules('mobile','Mobile', 'trim|required|is_unique[users.mobile]');
		$this->form_validation->set_rules('c_code','Country Code', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}

	}

	public function device_type_check($str){
		if ($str == '0' or $str == '1' or $str == '2' ){
			return TRUE;
		}else{
			$this->form_validation->set_message('device_type_check', 'The %s field can only have value 0 or 1 or 2');
			return FALSE;
		}
	}	


	public function update_profile(){
		$this->validate_update_profile();
		$data =  $this->input->post();

		if(isset($_FILES['profile_picture'])) {
			$data['profile_picture']=$this->upload_file('profile_picture');
		}
		$data['otp']=(string)rand(1001,9999);
		$msg = "Dear User, Your OTP for Klosebuyer mobile verification is " . $data['otp'];
		//check mobile and email registered with other user
		$check_email_exist=$this->users_model->check_email_exist(array('id'=>$data['id'],'email'=>$data['email']));		
		if($check_email_exist!=''){
			return_data(false,'This email is already registerd with other user please use different email.',array());	
		}
		$check_mobile_exist=$this->users_model->check_mobile_exist(array('id'=>$data['id'],'mobile'=>$data['mobile']));
		if($check_mobile_exist!=''){
			return_data(false,'This mobile number is already registerd with other user please use different mobile number.',array());	
		}
		$user_detail=$this->users_model->get_user($data['id']);
		if($user_detail['mobile']==$data['mobile']){
			unset($data['otp']);
			$result = $this->users_model->update_user($data);
			if($result){
				$update_user_details=$this->users_model->get_user($data['id']);
				return_data(true,'Profile updated successfully.',$update_user_details);
			}
		}else{
			$send_otp = send_message_global($data['country_code'],$data['mobile'],$msg);
			///$this->users_model->update_user(array('id'=>$data['id'],'otp_verification'=>0));
			return_data(true,'OTP has been sent for mobile number verification',$data);
		}

	}

	private function validate_update_profile(){

		post_check();
		//$this->form_validation->set_message('is_unique', '%s already exist.');
		//$this->form_validation->set_rules('username','username', 'trim|required');
		//$this->form_validation->set_rules('email','Email', 'trim|required|valid_email');
		//$this->form_validation->set_rules('mobile','Mobile', 'trim|required');
		//$this->form_validation->set_rules('gender','Gender', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}
	}

	public function otp_verification(){ //id , otp_verification
		if($this->input->post('id')){
			$result=$this->users_model->update_user($this->input->post());
			if($result){
					$user_detail=$this->users_model->get_user($this->input->post('id'));
					return_data(true,'User OTP verification successfull.',$user_detail);
				}else{
					return_data(false,'OTP Verification unsuccessful.',array());
			}
		}else{
			return_data(false,'User ID required.',array());
		}
	}
	/***
	 *      _                    _        
	 *     | |                  (_)       
	 *     | |      ___    __ _  _  _ __  
	 *     | |     / _ \  / _` || || '_ \ 
	 *     | |____| (_) || (_| || || | | |
	 *     |______|\___/  \__, ||_||_| |_|
	 *                     __/ |          
	 *                    |___/           
	 */

	public function login_authentication(){
		$this->validate_login_authentication();

		if($this->input->post('is_social') == 1){
			// check for social accounts 
			$array =  array(
				"social_type"=> $this->input->post('social_type'),
				"social_tokken"=> $this->input->post('social_tokken'),
				"email"=> $this->input->post('email')
				);
			if($find_user =  $this->users_model->get_social_user($array)){

				if($this->input->post('device_type') == 1 || $this->input->post('device_type') == 2 ){
					if($find_user['status'] == 0) {
					} elseif($find_user['status'] == 1) {
						return_data(false,'Your account has been disabled');
					}else{
						return_data(false,'Your account has been deleted.');
					}

					$tokken   =array(
						"id"=>$find_user['id'],
						"device_type" => $this->input->post('device_type'), 
						"device_tokken"=> $this->input->post('device_tokken')
						); 
					$this->users_model->update_device_tokken($tokken);
				}

				return_data(true,'User authentication successful.',$this->users_model->get_user($find_user['id']));
			}else{
				//return_data(false,'User authentication failed.');
				$return_id =  $this->users_model->save_user($this->input->post());
				if($return_id){
					return_data(true,'Social Registration successful.',$this->users_model->get_user($return_id));
				}
			}

		}else{
			$data = array(
				"mobile"=>$this->input->post("mobile"),
				"password"=>$this->input->post("password")
				);
			$record = $this->users_model->get_custum_user($data);
			if($record){
				if($record['status'] == 0) {
					if($this->input->post('device_type') == 1 || $this->input->post('device_type') == 2 ){
						$tokken   =array(
							"id"=>$record['id'],
							"device_type" => $this->input->post('device_type'), 
							"device_tokken"=> $this->input->post('device_tokken')
							); 
						$this->users_model->update_device_tokken($tokken);
					}
					return_data(true,'User authentication successful.',$this->users_model->get_user($record['id']));
				} elseif($record['status'] == 1) {
					return_data(false,'Your account has been disabled');
				}else{
					return_data(false,'Your account has been deleted.');
				}
			}else{
				$record = $this->users_model->is_user_social($data);
				if($record['social_type']==1){
					return_data(false,'You have previouly loged in from Facebook.');
				}
				if($record['social_type']==2){
					return_data(false,'You have previouly loged in from Gmail.');
				}else{
					return_data(false,'User authentication failed.');
				}
			}
		}
        
	}

	private function validate_login_authentication(){

		post_check();

		if($this->input->post('is_social') == 1){
			$this->form_validation->set_rules('social_type','social_type', 'trim|required');
			$this->form_validation->set_rules('social_tokken','social_tokken', 'trim|required');
			$this->form_validation->set_rules('email','email', 'trim|required');		
		}else{
			$this->form_validation->set_rules('mobile','mobile', 'trim|required');
			$this->form_validation->set_rules('password','password', 'trim|required');
			if($this->input->post('password') != ""){ 
				$_POST['password'] = md5($this->input->post('password'));
			}	
		}

		if($this->input->post('device_type') == 1 || $this->input->post('device_type') == 2 ){
			//$this->form_validation->set_rules('device_tokken','device_tokken', 'trim|required');
		}	

		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}

	}

	public function send_sms($data) { //mobile and message.  
        $mobile = $data;
        if($mobile){
        	return true;
        }else{
        	return false;
        }
        
    }

	public function forget_password(){ //mobile
		if(isset($_POST['mobile']))
		{
			if($_POST['mobile'] == '') {
				return_data(false,'Please enter mobile number.',array());
			}
			$this->db->Where("mobile", $_POST['mobile']);
			$result = $this->db->get('users')->row_array();
			//print_r($result);
			if(empty($result)) {
				return_data(false,'Mobile number does not exist.',array());
			}else{
				$user_data['otp'] = (string)rand(1001, 9999);
				$user_data['user_id'] = $result['id'];
				$user_data['c_code'] = $result['c_code'];
				$user_data['mobile'] = $result['mobile'];
		        $msg = "Dear User, Your OTP for forget password is " . $user_data['otp'];
				$sent_sms = $this->send_sms($user_data['mobile']);
				if($sent_sms){
					return_data(true,'OTP has been sent for forget password',$user_data);
				}
				return_data(false,'Send otp failure.',array());
			}
		}
		return_data(false,'Please enter mobile number.',array());
	
	}

	public function update_password(){
		$data=$this->input->post();
		$this->validate_update_password();
		$result=$this->users_model->update_password($data);
		if($result){
			return_data(true,'Password has been updated successfully',array());
		}
		return_data(false,'failed to update password.',array());
	}


	public function get_active_user(){

		$this->validate_get_active_user();
		$id = $this->input->post("user_id");
		return_data(true,'User Data.',$this->users_model->get_user($id) );
	}


	public function validate_get_active_user(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}		
	}

	public function validate_update_password(){
		post_check();
		$this->form_validation->set_rules('user_id','user_id', 'trim|required');
		$this->form_validation->set_rules('password','password', 'trim|required');
		$this->form_validation->run();
		$error = $this->form_validation->get_all_errors();

		if($error){
			return_data(false,array_values($error)[0],array(),$error);
		}		
	}

	

}