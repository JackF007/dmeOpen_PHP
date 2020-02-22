<?php


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
# sample 

if (!function_exists('pre')) {

	function pre($array) {
		
	}

}

*/

/*helper for making blank json*/
if(!function_exists('blank_json')){
	function blank_json(){
		return json_decode('{}');
	}
}


if (!function_exists('pre')) {
	function pre($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}


if (!function_exists('return_data')) {
	function return_data($status=false,$message="",$data = array(),$error=array()){
		echo json_encode(array('status'=>$status,'message'=>$message,'data' => $data ,'error'=>$error)); 
		die;	
	}
}


if (!function_exists('post_check')){
	function post_check() {
		if ($_SERVER['REQUEST_METHOD'] != 'POST'){
			echo json_encode(array('status'=>false,'message'=>"Invalid input parameter.Please use post method.",'data' => array() ,'error'=>array())); 
		die;	
		}
	}
}

if (!function_exists('milliseconds')){
	function milliseconds() {
	    $mt = explode(' ', microtime());
	    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}
}

if(!function_exists('is_comma_seprated')){
	function is_comma_seprated($string = "" , $return = "" ){
		
		if ($string != "" && count(explode(",",$string)) > 0) {
			if($return === True){
				return explode(',',$string);
			}
  			return true;
		}else{
			if($return === false){
				return array();
			}
			return false;			
		}
	}
}

//get_user_basic_data
if (!function_exists('services_helper_user_basic')) {
	function services_helper_user_basic($user_id) {
		$CI = & get_instance();
		$CI->db->where('id', $user_id);
		return $CI->db->get('users')->row_array();  
	}
}

if (!function_exists('is_email_exist')) {
	function is_email_exist($email) {
		$CI = & get_instance();
		$CI->db->where('email', $email);
		$exist =$CI->db->get('client_master')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('email_primary', $email);
		$exist =$CI->db->get('company_master')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('email', $email);
		$exist =$CI->db->get('company_branch')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('email', $email);
		$exist= $CI->db->get('company_contact_person_master')->row_array();
		if($exist){
			return $exist;
		}
		return false;
		
	}
}

if (!function_exists('is_phone_exist')) {
	function is_phone_exist($phone) {
		$CI = & get_instance();
		$CI->db->where('phone', $phone);
		$CI->db->or_where("Concat(phone_cc,phone)",$phone);
		$exist =$CI->db->get('client_master')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('phone_primary', $phone);
		$CI->db->or_where("Concat(phone_cc_primary,phone_primary)",$phone);
		$exist =$CI->db->get('company_master')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('phone', $phone);
		$CI->db->or_where("Concat(country_code,phone)",$phone);
		$exist =$CI->db->get('company_branch')->row_array();
		if($exist){
			return $exist;
		}
		$CI->db->where('phone_cc_primary', $phone);
		$CI->db->or_where("Concat(phone_cc_primary,phone_cc_primary)",$phone);
		$exist =$CI->db->get('company_contact_person_master')->row_array();
		if($exist){
			return $exist;
		}
		return false;
	}
}