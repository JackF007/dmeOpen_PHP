<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        //$this->load->model('Home_model');
        $this->load->helper('cookie');
        $this->load->helper('custom');
        //$this->load->library('Facebook');  
        //$this->load->model('User_model');		
    }

    public function index() {

        $data = [];
        if ($this->input->post()) {
            if ($this->input->post('username') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter your email/Mobile numebr'));
                die;
            }
            //if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
               // echo json_encode(array('status' => false, 'message' => 'Please enter an valid email'));
                //die;
            //}
            if ($this->input->post('password') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter password'));
                die;
            }

            $this->db->group_start()
                    ->or_where('mobile', $this->input->post('username'))
                    ->or_where('email', $this->input->post('username'))
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
                echo json_encode(array('status' => true, 'message' => ''));
                die;
            } else {
                echo json_encode(array('status' => false, 'message' => 'Wrong username or password !'));
                die;
            }
			
        }
        $this->load->view('login', $data);
    }

    public function signup() {
        $data = [];
        if ($this->input->post()) {
            if ($this->input->post('username') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter name'));
                die;
            }
            if ($this->input->post('email') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter an email'));
                die;
            }
            if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
                echo json_encode(array('status' => false, 'message' => 'Please enter an valid email'));
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
            if ($this->input->post('c_password') == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter confirm password'));
                die;
            }
            if ($this->input->post('password') != $this->input->post('c_password')) {
                echo json_encode(array('status' => false, 'message' => 'Your confirm password not matched!'));
                die;
            }
            
//            captacha   //
            
//            if ($this->input->post('user_response') == '') {
//                echo json_encode(array('status' => false, 'message' => 'Please check the captcha'));
//                die;
//            }

//                    $recaptchaResponse = trim($this->input->post('user_response'));
//                    $userIp=$this->input->ip_address();
//                    $secret='6LdQET4UAAAAAEh0A-oWSKJ9ZWGJTAmCELuT48SC';
//                    $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response;=".$recaptchaResponse."&remoteip;=".$userIp;
//                    
//                    $response = file_get_contents($url);
//                    $response = json_decode($response, true);
//                    //pre($response); 
//                    if($response['success'] === false){
//                        echo json_encode(array('status'=>false,'message'=>'Captacha test fail'));die;
//                    }

            $this->db->select('email');
            $this->db->Where('email', $this->input->post('email'));
            $result = $this->db->get('users')->row_array();
            
            if ($result['email'] == $this->input->post('email')) {
                echo json_encode(array('status' => false, 'message' => 'Email already exist choose another'));
                die;
            }

            $insert_data = array(
                'name' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'login_type' => '0',
                'user_type' => '0',
                'status' => '1',
                'create_date' => date("Y-m-d h:i:s")
            );

            $this->db->insert('users', $insert_data);
            $insert_id = array();
            $insert_id['id'] = $this->db->insert_id();
            
            $this->db->where('id', $insert_id['id']);
            $result = $this->db->get('users')->row_array();

            if (!empty($result)) {
                $newdata = array(
                    'active_frontent_user_flag' => True,
                    'active_user_id' => $result['id'],
                    'user_type' => $result['user_type'],
                    'active_user_data' => $result
                );

                $this->session->set_userdata($newdata);
                echo json_encode(array('status' => true, 'message' => ''));
                die;
            } 
            redirect('index.php/web_panel/Home');
        }
        $this->load->view('signup', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(site_url('web_panel/home'));
    }

}
