<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        $this->load->model('Registration_model');
        $this->load->model('User_model');
        $this->load->helper('cookie');
        $this->load->helper('custom');
        //$this->load->library('Facebook');  
        //$this->load->model('User_model');		
    }

    public function index() {
        $data = [];
        if ($this->input->post()) {
            $input = $this->input->post();
            if ($this->input->post('first_name') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your first name'));
                die;
            }
            if ($this->input->post('last_name') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your last name'));
                die;
            }
            if ($this->input->post('mobile') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your Mobile numebr'));
                die;
            }
            if (strlen($this->input->post('mobile')) < '10') {
                echo json_encode(array('status' => false, 'message' => 'Enter mobile number atleast 10 character'));
                die;
            }
            if ($this->input->post('password') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter password'));
                die;
            }
            if (strlen($this->input->post('password')) < '6') {
                echo json_encode(array('status' => false, 'message' => 'Enter password atleast 6 character'));
                die;
            }
            if ($this->input->post('email') != '') {
                if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(array('status' => false, 'message' => 'Please enter an valid email'));
                    die;
                }
            }

            $this->db->group_start()
                    ->or_where('mobile', $this->input->post('mobile'))
                    ->or_where('email', $this->input->post('email'))
                    ->group_end();
            // $this->db->Where('password', md5($this->input->post('password')));
            $result = $this->db->get('users')->row_array();
            //pre($result); die;
            if ($result) {
                echo json_encode(array('status' => false, 'message' => 'User Alreardy Exist'));
                die;
            } else {
                $otp = rand(1000,9999);
                $mobile=$this->input->post('mobile');
                $name=$this->input->post('first_name');
                $msg='Hello ' .$name. ',Your otp is '.$otp.' for registration verification';
                $sms_url = 'https://smsleads.in/pushsms.php?username=animesh&password=animesh&sender=FITIND&numbers='.$mobile.'&message='.$msg;
                $sms_url = preg_replace("/ /", "%20", $sms_url);
                @file_get_contents($sms_url);
                echo json_encode(array('status' => true, 'message' => 'OTP has been sent','otp'=>$otp));
                        die;
                // $data = array(
                //     'name' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                //     'email' => $this->input->post('email'),
                //     'mobile' => $this->input->post('mobile'),
                //     'password' => md5($this->input->post('password')),
                //     'user_type' => $this->input->post('user_type')
                // );
                // $insert = $this->User_model->insert_user($data);
                // if ($insert) {
                //     $this->db->group_start()
                //             ->or_where('mobile', $this->input->post('mobile'))
                //             ->or_where('email', $this->input->post('email'))
                //             ->group_end();
                //     $this->db->Where('password', md5($this->input->post('password')));
                //     $result = $this->db->get('users')->row_array();

                //     if (!empty($result)) {
                //         $newdata = array(
                //             'active_frontent_user_flag' => True,
                //             'active_user_id' => $result['id'],
                //             'user_type' => $result['user_type'],
                //             'active_user_data' => $result
                //         );

                //         $this->session->set_userdata($newdata);
                //         echo json_encode(array('status' => true, 'message' => 'Registered Successfully'));
                //         die;
                //     }
                // }
            }
        }
        //$this->load->view('registration', $data);
    }

    public function register_after_verification(){
        //print_r($_POST); die;
        if($this->input->post('otp')==$this->input->post('enter_received_otp_new'))
        {
            $data = array(
                        'name' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile'),
                        'password' => md5($this->input->post('password')),
                        'user_type' => $this->input->post('user_type')
                    );
            $insert = $this->User_model->insert_user($data);
            if ($insert) {
                        $this->db->group_start()
                                ->or_where('mobile', $this->input->post('mobile'))
                                ->or_where('email', $this->input->post('email'))
                                ->group_end();
                        $this->db->Where('password', md5($this->input->post('password')));
                        $result = $this->db->get('users')->row_array();

                        if (!empty($result)) {
                            $newdata = array(
                                'active_frontent_user_flag' => True,
                                'active_user_id' => $result['id'],
                                'user_type' => $result['user_type'],
                                'active_user_data' => $result
                            );

                            $this->session->set_userdata($newdata);
                            echo json_encode(array('status' => true, 'message' => 'Registered Successfully'));
                            die;
                        }
                    }
         }
         else{
            echo json_encode(array('status' => false, 'message' => 'Please Enter Correct OTP'));
                            die;
         }
    }
    public function register_profile() {
        $data = [];
        if ($this->input->post()) {
            $input = $this->input->post();
            $input['created_on'] = $input['modified_on'] = date('Y-m-d H:i:s');
            //pre($input);die;
            if (isset($input['is_class'])) {
                $input['owner_id'] = $this->session->userdata['active_user_data']['id'];
                $this->db->insert('gym', $input);
            }
            if (isset($input['professional_type'])) {
                $input['user_id'] = $this->session->userdata['active_user_data']['id'];
                $this->db->insert('professional_profile', $input);
            }
            echo json_encode(array('status' => true, 'message' => 'Created Successfully'));
            //$this->load->view('Home', $data);      
        }
    }

    public function create_profile() {
        $data = [];
        if ($this->input->post()) {
            $input = $this->input->post(); //pre($input); die;
            if (isset($input['is_class'])) {
                $input['created_on'] = $input['modified_on'] = date('Y-m-d H:i:s');
                $input['owner_id'] = $this->session->userdata['active_user_data']['id'];
                $this->db->insert('gym', $input);
            }
            if (isset($input['professional_type'])) {
                $input['creation_time'] = $input['updation_time'] = date('Y-m-d H:i:s');
                $input['user_id'] = $this->session->userdata['active_user_data']['id'];
                //pre($input); die;
                $this->db->insert('professional_profile', $input);
            }
            echo json_encode(array('status' => true, 'message' => 'Created Successfully', 'type' => $this->input->post('professional_type')));
        }
    }

    public function forgot_password() {
        if ($this->input->post()) {
            if ($this->input->post('username') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your Email/Mobile'));
                die;
            }
            $input['username'] = $this->input->post('username');
            $user = $this->user_exist($input);
            //pre($user);die;
            if (!empty($user)) {
                $length = 6;
                $otp = $this->otp($length);
                //$otp = 1234;
                $mobile = $user['mobile'];
                $name = $user['name'];
                $msg='Hello ' .$name. ',Your otp is '.$otp.' for reset password';
                $sms_url = 'https://smsleads.in/pushsms.php?username=animesh&password=animesh&sender=FITIND&numbers='.$mobile.'&message='.$msg;
                $sms_url = preg_replace("/ /", "%20", $sms_url);
                @file_get_contents($sms_url);
                
                $temp_otp = array('user_id' => $user['id'], 'otp' => $otp);
                $this->session->set_userdata("verify_otp",$temp_otp);
                //echo json_encode(array('status' => true, 'otp' => $otp, 'message' => $temp_otp['user_id']));
                echo json_encode(array('status' => true));
                die;
            } else {
                echo json_encode(array('status' => false, 'message' => 'Wrong Crendential!'));
                die;
            }
        }
    }

    function user_exist($data) { //pre($data);
        if (isset($data['username'])) {
            $this->db->where('mobile', $data['username']);
            $this->db->or_where('email', $data['username']);
        }
        $user = $this->db->get('users')->row_array();
        return $user;
    }
    
    function otp($length) {
        $chars = "1234567890";
        $otp = substr(str_shuffle($chars), 2, $length);
        return $otp;
    }
    
    
    public function otp_verification() {
        if ($this->input->post()) {
            if ($this->input->post('otp_value') == $this->session->userdata('verify_otp')['otp']) {
                $this->db->Where('id', $this->session->userdata('verify_otp')['user_id']);
                $result = $this->db->get('users')->row_array();
                echo json_encode(array('status' => true, 'id' => $result['id']));
            }
            else{
                echo json_encode(array('status' => false, 'message' => 'Wrong OTP entered'));
                die;
            }
        }
    }
    
    
    
    public function reset_password(){
        if ($this->input->post()) {
            if ($this->input->post('otp_pass') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your new password'));
                die;
            }
            if (strlen($this->input->post('otp_pass')) < '5') {
                echo json_encode(array('status' => false, 'message' => 'Enter password atleast 6 character'));
                die;
            }
            if ($this->input->post('otp_c_pass') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your confirm password'));
                die;
            }
            
            if ($this->input->post('otp_pass') == $this->input->post('otp_c_pass')) {
                $data['password'] = md5($this->input->post('otp_pass'));
                //pre($this->session->userdata('verify_otp')['user_id']); die;
                $this->db->Where('id', $this->session->userdata('verify_otp')['user_id']);
                //pre($data); die;
                $result = $this->db->update('users',$data);
                echo json_encode(array('status' => true, 'id' => "Password has been successfully updated"));
            }
            else{
                echo json_encode(array('status' => false, 'message' => 'Your confirm password does not matched!'));
                die;
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(site_url('web_panel/home'));
    }

}
