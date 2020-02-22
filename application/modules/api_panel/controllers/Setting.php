<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   date : 17 / OCT / 2018     //
//   Mode : 31 / Oct /2018      //
//                              //                            
//////////////////////////////////

class Setting extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper("aul");
        $this->load->helper("array");
        $this->load->library("form_validation");
        $this->load->model("OpenDataModel");
        $this->load->helper("services");
        $this->load->helper("image_upload_helper");
        $this->load->library("session");
        $this->load->model('SettingModel');
    }

    function update_user_data() {
        $log = "Time: " . date('Y-m-d, H') . PHP_EOL;
        $log = $log . "Request " . json_encode($this->input->post()) . PHP_EOL;
        if (isset($_FILES)) {
            $log = $log . "Request " . json_encode($_FILES) . PHP_EOL;
        }
        file_put_contents('logs/logs.txt', $log, FILE_APPEND);

        $res = $this->validate_update_user_data();
        if ($res) {
            $input = $this->input->post();
            if (!empty($_FILES['profile_picture']['name'])) {
                $upload_data = array(
                    'key' => 'profile_picture',
                    'upload_path' => './uploads/profile_photo/'
                );
                $file_name = upload_image($upload_data);
                $input['profile_picture'] = $file_name;
            }
            $result = $this->SettingModel->updte_user_data($input);
            if ($result) {
                $user_data = $this->SettingModel->get_user_profile($input['id']);
                return_data(true, 'Successfully! Updated user profile.', $user_data);
            } else {
                return_data(false, 'Error! While updating your profile.');
            }
        }
    }

    function validate_update_user_data() {
        post_check();
        $form_valid = $this->form_validation;
        $form_valid->set_rules('id', 'user_id', 'trim|required');
        $form_valid->set_rules('name', 'name', 'trim');
        $form_valid->set_rules('user_name', 'user_name', 'trim');
        $form_valid->set_rules('email', 'email', 'trim');
        $form_valid->set_rules('address', 'address', 'trim');
        $form_valid->set_rules('country_code', 'country_code', 'trim');
        $form_valid->set_rules('mobile', 'mobile', 'trim');
        $form_valid->set_rules('gender', 'gender', 'trim');
        $form_valid->set_rules('description', 'description', 'trim');
        $form_valid->set_rules('dob', 'DOB', 'trim');
        $form_valid->run();
        $error = $form_valid->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], $error);
        }
        return TRUE;
    }

    public function get_user_profile() {
        $input = $this->validate_get_user_profile();
        $res = $this->SettingModel->get_user_profile($input['user_id']);
        if ($res) {
            return_data(true, 'User Profile Found', $res);
        }
        return_data(false, 'No data found');
    }

    private function validate_get_user_profile() {
        $this->form_validation->set_rules('user_id', 'user_id', 'required|trim');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(true, array_values($error)[0], $error);
        }return $this->input->post();
    }

//    private function update_data(){
//        post_check();        
//    }
}
