<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function registration($inputs) { //pre($data);		
        //$data['creation_time']  = milliseconds();
        //$inputs['created'] = $inputs['modified'] = time();
        $this->db->insert('users', $inputs);
        $profile = $this->user_profile($this->db->insert_id());
        return $profile;
    }

    public function update_user($inputs) { //pre($inputs);
        //$data['created']  = milliseconds();
        $inputs['modified'] = milliseconds();
        $this->db->where('id', $inputs['id']);
        $this->db->update('users', $inputs);
        $profile = $this->user_profile($inputs['id']);
        return $profile;
    }
    function updt_usr_pass($data){
        $this->db->where('email', $data['email']);
        $pass = array('password' => md5($data['password']));
        $this->db->update('users', $pass);
        $ar = $this->db->affected_rows();
       
        if($ar > 0){
            $profile = $this->login(['email' => $data['email']]);
            return $profile;
        }else{
            return FALSE;
        }
    }

    public function create_user_profile($document) {
        $this->db->where('user_id', $document['user_id']);
        $user_details = $this->db->get('user_details')->row_array();
        if ($user_details) {
            $this->db->where('user_id', $document['user_id']);
            $this->db->update('user_details', $document);
            $id = $user_details['id'];
        } else {
            //pre($document); die;
            $this->db->insert('user_details', $document);
            $id = $this->db->insert_id();
        }
        $profile = $this->user_profile($id);
        return $profile;
    }

    private function check_profile_image($id) {
        $this->db->where('id', $id);
        $user = $this->db->get('users')->row_array();
        if (!empty($user)) {
            if ($user['user_type'] == 0) {
                if (strpos($user['profile_image'], 'http') === false) {
                    return base_url() . 'uploads/profile_images/' . $user['profile_image'];
                }
                return $user['profile_image'];
            }
            if ($user['user_type'] == 1) {
                if (strpos($user['doctor_image'], 'http') === false) {
                    return base_url() . 'uploads/profile_images/' . $user['doctor_image'];
                }
                return $user['doctor_image'];
            }
        } else {
            return base_url() . 'uploads/profile_images/default_image.png';
        }
    }

    function get_user_short_details($id) {
        $this->db->select("id,name,profile_image");
        $this->db->where('id', $id);
        $user = $this->db->get('users')->row_array();
        $user['profile_image'] = $this->check_profile_image($user['id']);
        $final[] = $user;
        return $final[0];
    }

    public function user_profile($id) { //pre($id);	
        $this->db->where('id', $id);
        $user = $this->db->get('users')->row_array();
        if($user['profile_picture'] != ""){
            $path = base_url("uploads/profile_photo/");
            $user['profile_picture'] = $path.$user['profile_picture'];
        }
        return $user;
    }

    function login($document) {
        $this->db->where('email', $document['email']);
        $user = $this->db->get('users')->row_array();
        if ($user) {
            return $this->user_profile($user['id']);
        }
        return false;
    }

   
    function updt_device_token_type($email, $device_token, $device_type){
        $data = array('device_token' => $device_token, 'device_type' => $device_type);
        $this->db->where('email', $email);
        $this->db->update('users', $data);
    }
    function blank_device_token($device_token){
        $data = array('device_token' => "");
        $this->db->where('device_token', $device_token);
        $this->db->update('users', $data);
    }

    function social_login($document) {
       
        if($document['login_type']==1){
            $this->db->where('fb_id',$document['fb_id']);
        }
        if($document['login_type']==2){
            $this->db->where('g_id',$document['g_id']);
        }
        return $this->db->get('users')->row_array(); 
    }

    function social_login_also($document) {
        //if ((isset($document['email']) && !empty($document['email'])) OR ( isset($document['mobile']) && !empty($document['mobile']))) {
        if ((isset($document['username']) && !empty($document['username']))) {
            if (isset($document['username']) && !empty($document['username'])) {
                $this->db->where('email', $document['username']);
            } else {
                $this->db->where('mobile', $document['username']);
            }
            return $this->db->get('users')->row_array();
        }
        return false;
    }

    function get_users($document) { //pre($document);
        if (isset($document['id']) and ! empty($document['id'])) {
            $this->db->where('id', $document['id']);
        }
        if (isset($document['g_id']) and ! empty($document['g_id'])) {
            $this->db->where('g_id', $document['g_id']);
        }
        if (isset($document['fb_id']) and ! empty($document['fb_id'])) {
            $this->db->where('fb_id', $document['fb_id']);
        }
        if (isset($document['email']) and ! empty($document['email'])) {
            $this->db->where('email', $document['email']);
        }
        if (isset($document['mobile']) and ! empty($document['mobile'])) {
            $this->db->where('mobile', $document['mobile']);
        }
        if (isset($document['user_type']) and ! empty($document['user_type'])) {
            $this->db->where('user_type', $document['user_type']);
        }
        if (isset($document['device_token']) and ! empty($document['device_token'])) {
            $this->db->where('device_token', $document['device_token']);
        }
        $users = $this->db->get('users')->result_array();
        return $users;
    }
    /* saving feedback */
    function save_feedback($inputs){
        $data = array(
            'email' => $inputs['email'],
            'feedback_msg' => $inputs['feedback_msg']
        );
        $this->db->insert('feedback', $data);

        $updateData = array(
                        'rating' => $inputs['rating']
                    );
        $this->db->where('email', $inputs['email']);
        $this->db->update('users', $updateData);
    }
    /*end of saving feedback*/

}
