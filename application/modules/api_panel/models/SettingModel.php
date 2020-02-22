<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   date : 17 / OCT / 2018     //
//   mod : 01/ NOV /2018        //
//                              //                              
//////////////////////////////////
class SettingModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function updte_user_data($input) {
        $update_data = $input;
        if (empty($input['profile_picture'])) {
            unset($input['profile_picture']);
        } else {
            $update_data['profile_picture'] = $input['profile_picture'];
        }
        $update_data['update_date'] = milliseconds();

//        $update_data = array(
//            'name' => $input['name'],
//            'user_name' => $input['user_name'],
//            'email' => $input['email'],
//            'address' => $input['address'],
//            'country_code' => $input['country_code'],
//            'mobile' => $input['mobile'],
//            'gender' => $input['gender'],
//            'description' => $input['description'],
//            'dob' => $input['dob'],
//            'update_date' => milliseconds()
//        );

        $this->db->where('id', $input['id']);
        $res = $this->db->update('users', $update_data);
        return $res;
    }

    public function get_user_profile($user_id) {
        $this->db->where('id', $user_id);
        $q = $this->db->get('users');
        $res = $q->row_array();
        if ($res) {
            if ($res['profile_picture'] != "") {
                $path = base_url("uploads/profile_photo/");
                $res['profile_picture'] = $path . $res['profile_picture'];
            }
            return $res;
        }
    }

}
