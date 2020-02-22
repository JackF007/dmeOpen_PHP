<?php

        //////////////////////////////////
        //                              //
        //   Mod by : Abhishek Jaiswal  //
        //   date : 14 / sept / 2018    //
        //                              //
        //////////////////////////////////
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('aul');
        $this->load->helper("array");
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->helper("services");
        $this->load->helper("sms");
        $this->load->library('session');
        $this->load->library('email');
    }

    /*  Start USer Registration   */

    public function registration() {
        $input = $this->validate_registration();
        $user = $this->User_model->get_users(array("email" => $input['email']));
        if ($user) {
            return_data(false, "This email id is already registered with this app", blank_json());
        }
        $user = $this->User_model->get_users(array("mobile" => $input['mobile']));
        if ($user) {
            return_data(false, "This mobile number is already registered with this app", blank_json());
        }
        if($input['login_type'] == 0){
        $input['password'] = md5($input['password']);
        }
        if ($input['login_type'] == 1) {
            $input['username']=$input['fb_id'];
            $input['social_type']=1;
            $input['is_social']=1;
        }
        if ($input['login_type'] == 2) {
            $input['username']=$input['g_id'];
            $input['social_type']=2;
            $input['is_social']=1;
        }
        
        $user = $this->User_model->registration($input);
        if ($user) {
            return_data(true, "Successful sign up", $user);
        }
        return_data(false, "Error, User could not register !!", blank_json());
    }

    private function validate_registration() {
        post_check();
        $this->form_validation->set_rules('login_type', 'login_type', 'required');
        $this->form_validation->set_rules('name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('email', 'User Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|required'); //country code added 15 oct 2018
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|is_unique[users.mobile]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');//add address
        $this->form_validation->set_rules('device_type', 'Device Id', 'trim|required');
        $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
        
        if ($this->input->post('login_type') == 0) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }

    /* End user registration   */


    /* Start Login  */

   
    public function login() {
        $res = json_decode("{}");
        
        $inputs = $this->validate_login();
        $user = $this->User_model->login($inputs);
        if ($user) { 
            if ($user['password'] != md5($inputs['password'])) {
                return_data(false, 'Wrong password',$res);
            }
            if ($user['status'] == 1) {
                return_data(false, 'Your account is disabled', $res);
            } else if ($user['status'] == 2) {
                return_data(false, 'Your account has been deleted.', $res);
            } else {
                    $this->User_model->blank_device_token($inputs['device_token']);
                    $this->User_model->updt_device_token_type($inputs['email'], $inputs['device_token'], $inputs['device_type']);
                    $user = $this->User_model->user_profile($user['id']);
                    return_data(true, 'User authentication successful.', $user);
                }
            } else {
            return_data(false, 'User authentication failed.', $res); 
        }
    }

    private function validate_login() {
        post_check();
        
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $this->form_validation->set_rules('device_token', 'Device token', 'required');
        $this->form_validation->set_rules('device_type', 'Device type', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }

    /* End Login  */




    /* Start Social Login  */

    public function social_registration($inputs) { //pre($inputs);
        if (isset($inputs['email']) && !empty($inputs['email'])) {
            $social_data['email'] = $inputs['email'];
            $exist = $this->User_model->get_users(array("email" => $inputs['email']));
        }
        if (!isset($exist) && isset($inputs['mobile']) && !empty($inputs['mobile'])) {
            $social_data['mobile'] = $inputs['mobile'];
            $exist = $this->User_model->get_users(array("mobile" => $inputs['mobile']));
        }
        $social_data['login_type'] = $inputs['login_type'];
        if ($inputs['login_type'] == 1) {
            $social_data['fb_id'] = $inputs['username'];
        } else if ($inputs['login_type'] == 2) {
            $social_data['g_id'] = $inputs['username'];
        }
        if (isset($exist) && !empty($exist)) {
            $social_data['id'] = $exist[0]['id'];
            $user = $this->User_model->update_user($social_data);
        } else {
            $user = $this->User_model->registration($social_data);
        }
        return $user;
        //$this->register_or_merge_social_user($exist,$inputs);
        //return $exist;
    }

    public function social_login() {
        $inputs = $this->validate_social_login();
        $user = $this->User_model->social_login($inputs);
        if ($user) { //pre($exist);pre($inputs);
            if ($user['status'] == 0) {
                return_data(false, 'Your account is disabled');
            } else if ($user['status'] == 2) {
                return_data(false, 'Your account has been deleted.');
            } else {
                $user = $this->User_model->user_profile($user['id']);
                return_data(true, 'User authentication successful.', $user);
            }
        } else {
            $user = $this->User_model->social_login_also($inputs);
            //$user = $this->social_registration($inputs);
            //return_data(false, 'not found',[]);
        }
        if ($user) {
            return_data(true, 'User authentication successful.', $user);
        }
        return_data(false, 'not found', []);
    }

    private function validate_social_login() {
        post_check();
        $this->form_validation->set_rules('login_type', 'login_type', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /* End social Login  */




    /*  Verify details   */

    public function verify_details() {
        $this->validate_verify_details();
        $exist = $this->User_model->get_users(["mobile" => $this->input->post('mobile')]);
        if ($exist) {
            return_data(false, 'Some one already using the same phone number in DAPS !', array());
        } else {
            $exist = $this->User_model->get_users(["email" => $this->input->post('email')]);
            if ($exist) {
                return_data(false, 'Some one already using the same phone number in DAPS !', array());
            }
        }
        //$return['otp'] = 1234;         
        $return['otp'] = rand(1000, 9999);
        //$this->session->set_userdata('otp',$return['otp']);
        $sent = send_sms(array("mobile" => $this->input->post('mobile'), "message" => "Your OTP for DAPS is " . $return['otp']));
        $sent = mail("meittech10@gmail.com", "Test subject", "Test message");
        return_data(true, 'OTP sent successfully.', $return);
    }

    private function validate_verify_details() {
        post_check();
        $this->form_validation->set_rules('email', 'User Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|is_unique[users.mobile]');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
    }

    public function change_mobile_number() {
        $this->validate_change_mobile_number();
        $exist = $this->User_model->get_users(["mobile" => $this->input->post('mobile')]);
        if ($exist) {
            return_data(false, 'Some one already using the same phone number in DAPS !', array());
        } else {
            //$return['otp'] = 1234;         
            $return['otp'] = rand(1000, 9999);
            //$this->session->set_userdata('otp',$return['otp']);
            $sent = send_sms(array("mobile" => $this->input->post('mobile'), "message" => "Your OTP for DAPS is " . $return['otp']));
            //$sent = mail("abhishek.jaiswal@appsquadz.com", "Test subject", $return['otp']);
            return_data(true, 'OTP sent successfully.', $return);
        }
    }

    private function validate_change_mobile_number() {
        post_check();
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|is_unique[users.mobile]');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
    }

    /*  End Verify details   */



    /*  Update user  */

    public function update_user() {
        $input = $this->validate_update_user();
        $log = "Time: " . date('Y-m-d, H') . PHP_EOL;
        $log = $log . "url: " . base_url("update_user") . PHP_EOL;
        $log = $log . "Request " . json_encode($input) . PHP_EOL;
        file_put_contents('logs/update_user.txt', $log, FILE_APPEND);
        $input = $this->validate_update_user();
        $profile = $this->User_model->get_users(array("id" => $input['id']));
        if ($profile) {
            if (isset($input['doctor_profile'])) {
                $doctor_profile = elements(array("specialization_id", "sub_specialization_id", "total_experience", "city", "profile_address", "currently_practicing", "main_stream_id", "consultation_fee", "bio", "user_id", "registration_no", "council_by", "registration_doc"), json_decode($input['doctor_profile'], true), $input['id']);
                $this->Doctor_model->create_doctor_profile($doctor_profile);
                unset($input['doctor_profile']);
                $input['professional_details'] = 1;
            }if (isset($input['qualification'])) {
                $this->Doctor_model->add_qualification(array("user_id" => $input['id'], "qualifications" => $input['qualification']));
                unset($input['qualification']);
                $input['qualification_details'] = 1;
            }
            if (isset($input['patient_profile'])) {
                $user_profile = elements(array("user_id", "height", "weight", "blood_group"), json_decode($input['patient_profile'], true));
                $user_profile['user_id'] = $input['id'];
                $this->User_model->create_user_profile($user_profile);
                unset($input['patient_profile']);
            }
            if (isset($input['profile_image']) and ! empty($input['profile_image'])) {  //die('working');
            }
            if (isset($input['password'])) {
                $input['password'] = md5($input['password']);
            }
            $edited = $this->User_model->update_user($input);
            $profile = $this->User_model->user_profile($input['id']);
            return_data(true, "User details edited successfully", $profile);
        }
        return_data(false, 'user not exit.', array());
    }

    private function validate_update_user() {
        post_check();
        $this->form_validation->set_rules('id', 'user id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
        return $this->input->post();
    }

    /*  End update user  */

    /*  forget password  */

    public function forget_password(){
        $input = $this->validate_forget_password();
        $res = $this->User_model->login(["email" => $this->input->post('email')]);
            if($res){
                $return['email'] = $res['email'];
                //$return['code'] = $this->input->post('code');
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => '',
                    'smtp_port' => 587,
                    'smtp_user' => '',
                    'smtp_pass' => '',
                );
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                $this->email->from("webmaster@giacomo.com", 'GAYCOMO');
                $this->email->to($return['email']);
                $this->email->subject('Reset password!');
                $this->email->message('Please use this secure code to change your password '.$this->input->post('code'));
                $this->email->set_mailtype("html");
                $this->email->send();
                return_data(true, 'Message sent successfully.', $return);
            }
         return_data(false, 'User not exist.', blank_json());
    }
       
    private function validate_forget_password() {
        post_check();
        $this->form_validation->set_rules('email', 'Email Id', 'required|valid_email');
        //$this->form_validation->set_rules('link_url', 'Link Url', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }       
        return $this->input->post();
    }

    private function valid_url($str)
    {
       if(filter_var($str, FILTER_VALIDATE_URL)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_user_password(){
        $input = $this->validate_update_user_password();
        $res = $this->User_model->updt_usr_pass(["email" => $this->input->post('email'), "password" => $this->input->post('password')]);
        if($res){
            return_data(true, "Successful Password Reset", $res);
        }
        return_data(false, "Error, Password can not be reset!!", blank_json());
    }

    private function validate_update_user_password(){
        post_check();
        $this->form_validation->set_rules('email', 'Email Id', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }
    /*  End forget password  */


     // public function forget_password() {
    //     $input = $this->validate_forget_password();
    //     $exist = $this->User_model->login(["email" => $this->input->post('email')]);
    //     if ($exist) {
    //         $return['id'] = $exist['id'];
    //         $return['otp'] = rand(1000, 9999);
    //         if ($exist['email'] == $this->input->post('email')) {
    //             $headers = "From: webmaster@gyacomo.com" . "\r\n";
    //             $sent = mail($exist['email'], "Your OTP", "Your OTP is " . $return['otp'], $headers);
    //         }
    //         return_data(true, 'OTP sent successfully.', $return);
    //     }
    //     return_data(false, 'User not exist.', array());
    // }
    /*  Send otp    */

    public function send_otp() {
        $this->validate_send_otp();
        $return['otp'] = rand(1000, 9999);
        //$return['id'] = $this->User_model->user_profile($document);
        $sent = send_sms(array("mobile" => $this->input->post('mobile'), "message" => "Your OTP for DAPS is " . $return['otp']));
        if ($sent) {
            return_data(true, 'OTP sent successfully.', $return);
        } else {
            return_data(false, 'Try again.', array());
        }
    }

    private function validate_send_otp() {
        post_check();
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], array(), $error);
        }
    }

    /* end Send otp  */

    public function user_list() {
        $input = $this->input->post();
        $users = $this->User_model->get_users($input);
        return_data(true, 'User list displayed successfully.', $users);
    }

    public function user_profile() {
        $input = $this->input->post();
        $users = $this->User_model->user_profile($input['id']);
        return_data(true, 'User list displayed successfully.', $users);
    }

    /*  reset password  */

    public function reset_password() {
        if (isset($_POST['email'])) {
            if ($_POST['email'] == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter email address.'));
                die;
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(array('status' => false, 'message' => 'Please enter valide email address.'));
                die;
            }
            $this->db->Where("email", $_POST['email']);
            $result = $this->db->get('backend_user')->row();
            if (empty($result)) {
                echo json_encode(array('status' => false, 'message' => 'Email address does not exist.'));
                die;
            } else {
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => '',
                    'smtp_port' => 587,
                    'smtp_user' => '',
                    'smtp_pass' => '',
                );
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                $this->email->from("donotreply@repl.com", 'REPL!');
                $this->email->to($_POST['email']);
                $this->email->subject('REPL agent change password!');
                $this->email->message('Click here to reset your password = ' . base_url('index.php/auth_panel/Login/reset_password_page'));
                $this->email->set_mailtype("html");
                $this->email->send();

                echo json_encode(array('status' => true, 'message' => 'Process successfully done , check your email.'));
                die;
            }
        }
    }

    /*  End reset password  */

    function random_string() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZA';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $randstring . $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public function my_docs() {
        $inputs = $this->input->post();
        $documents = $this->Appointment_model->get_docs($inputs);
        if ($documents) {
            foreach ($documents as $docs) {
                $doctor = $this->User_model->user_profile($docs['doctor_id']);
                $patient = $this->User_model->user_profile($docs['patient_id']);
                $docs['doctor_name'] = $doctor['name'];
                $docs['patient_name'] = $patient['name'];
                $mil = $docs['created'];
                $seconds = $mil / 1000;
                $datetime =  date("d-m-Y H:i:s", $seconds);
                $date = explode(" ", $datetime);
                $docs['date'] = $date[0];
                $docs['time'] = $date[1];
                $final[] = $docs;
            }
        }
        if ($final) {
            return_data(true, 'Documents list displayed successfully.', $final);
        } else {
            return_data(false, 'Documents list displayed fail.', array());
        }
    }

    /* user feedback*/
    function feedback(){
        post_check();
        $this->form_validation->set_rules('email', 'Email Id', 'required');
        $this->form_validation->set_rules('feedback_msg', 'Message', 'required|trim');
        $this->form_validation->set_rules('rating', 'Rating', 'required|numeric|less_than_equal_to[5]|greater_than_equal_to[0]');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if($error){
            return_data(false, array_values($error)[0], blank_json());
        }else{
            $inputs = $this->input->post();
            $this->User_model->save_feedback($inputs);
            return_data(true, 'Feedback Successfully submitted', blank_json());
        }

    }
    /* end user feedback */

}
