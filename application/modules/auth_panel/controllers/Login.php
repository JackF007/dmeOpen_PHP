<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation', 'uploads');
        $this->load->model('Backend_user');
        $this->load->helper('email');
    }

    public function email_exists($key) {
        if ($key != '') {
            $result = $this->Backend_user->email_exists($key);
            if ($result == FALSE) {
                $this->form_validation->set_message('email_exists', 'Sorry, This email does not exist.');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function index() {

        if ($this->session->userdata('active_backend_user_flag') && $this->session->userdata('active_backend_user_flag')) {
            redirect(site_url('auth_panel/admin/index'));
            die;
        }
        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters(' ', ' ');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_exists|trim');
            if ($this->form_validation->run() == FALSE) {
                
            }

            $this->db->Where("email", $this->input->post('email'));
            $this->db->Where("password", md5($this->input->post('password')));
            $this->db->Where("status", '0');
            $result = $this->db->get('backend_user')->row();
            if (!empty($result)) {
                /*
                 * setting session according to auth_panle_ini file in controller master file of panel
                 */

                $newdata = array(
                    'active_backend_user_flag' => True,
                    'active_backend_user_id' => $result->id,
                    'active_user_data' => $result
                );

                $this->session->set_userdata($newdata);
                redirect(site_url('auth_panel/admin/index'));
                die;
            }
        }

        $this->load->view('login/login');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(site_url('auth_panel/login/index'));
    }

    public function reset_password_page() {
        $this->load->view('login/forget_password');
    }

    public function forget_password() {
        //echo "<pre>";print_r($_POST);die;

        if (!isset($_POST['post_type'])) {

            if ($_POST['email'] == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter email address.'));
                die;
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(array('status' => false, 'message' => 'Please enter valid email address.'));
                die;
            }
            $this->db->Where("email", $_POST['email']);
            $result = $this->db->get('backend_user')->row();
            if (empty($result)) {
                echo json_encode(array('status' => false, 'message' => 'Email address does not exist.'));
                die;
            } else {
                $tokken = rand(1000, 999999) . '_' . time();
                /* ####### Update tokken of backend user ########### */
                $this->db->where('email', $_POST['email'])
                        ->update('backend_user', array('tokken' => $tokken));
                ##### sedn sms ##############
                //print_r($result); die;
                $mobile = $result->mobile;
                $msg = 'Your tokken for change password is = ' . $tokken;

                $url = 'http://www.hinditsolution.in/shn/api/pushsms.php?usr=617856&key=010vM0Zp00WBetHH7L674YwcdF5t7F&sndr=CCRIPL&ph=' . $mobile . '&text=' . $msg . '&rpt=1';
                $url = preg_replace("/ /", "%20", $url);
                file_get_contents($url);
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL,$url);
                // curl_setopt($ch, CURLOPT_POST, 1);
                // curl_setopt($ch, CURLOPT_POSTFIELDS,array());
                // // receive server response
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // $server_output = curl_exec ($ch);
                // curl_close ($ch);
                ###############
                // 	$config = array(
                // 	'protocol' => 'smtp',
                // 	'smtp_host' => 'smtp.gmail.com',
                // 	'smtp_port' => 587,
                // 	'smtp_user' => '',
                // 	'smtp_pass' => '',
                // );
                // $this->load->library('email', $config);
                // $this->email->set_newline("\r\n");
                // $this->email->from("donotreply@repl.com", 'REPL!');
                // $this->email->to($_POST['email']);
                // $this->email->subject('REPL admin change password!');
                // $this->email->message('Your tokken for change password is = '.$tokken);
                // $this->email->set_mailtype("html");
                // $this->email->send();

                echo json_encode(array('status' => true, 'post_type' => '', 'message' => 'Process successfully done , check your email.'));
                die;
            }
        } else {
            if ($_POST['tokken'] == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter tokken.'));
                die;
            }
            if ($_POST['new_pwd'] == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter new password.'));
                die;
            }
            if ($_POST['cnf_pwd'] == '') {
                echo json_encode(array('status' => false, 'message' => 'Please enter confirm password.'));
                die;
            }
            if ($_POST['new_pwd'] != $_POST['cnf_pwd']) {
                echo json_encode(array('status' => false, 'message' => 'Password does not match.'));
                die;
            }

            $result = $this->db->where('email', $_POST['email'])
                    ->where('tokken', $_POST['tokken'])
                    ->update('backend_user', array('password' => md5($_POST['new_pwd']), 'tokken' => ''));
            if ($this->db->affected_rows()) {
                echo json_encode(array('status' => true, 'post_type' => $_POST['post_type'], 'message' => 'Password change successfully please login.'));
                die;
            } else {
                echo json_encode(array('status' => false, 'message' => 'Tokken does not correct'));
                die;
            }
        }
    }

}
