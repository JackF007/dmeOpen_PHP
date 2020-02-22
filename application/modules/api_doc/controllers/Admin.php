<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends MX_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('form_validation', 'uploads');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        $this->load->helper('common_helper');
        $this->load->helper('custom_helper');
        $this->load->helper('push_helper');
        $this->load->model('Admin_model');
        //$this->load->model('Users_model');
        $this->load->helper('cookie');
        $this->form_validation->CI = & $this;
        if ($this->router->fetch_method() != "index") {//pre($this->session->userdata['id']);
            if (empty($this->session->userdata['backend_user'])) { 
               redirect('admin_panel/Admin');
            }
        }
    }
    ////////////////////////////////////////
    //                                    //
    //      Modified by: Harish Kumar     //
    //      Created On: 24feb-2018        //
    //                                    //
    ////////////////////////////////////////
    

    function set_login_cookie($input) {
        if (isset($input['remember']) and $input['remember'] == "Remember Me") {
            $this->input->set_cookie('user_name', $input['remember'], time() + 86500, '', '/');
            $this->input->set_cookie('user_password', $input['password'], time() + 86500, '', '/');
        }
        return true;
    }
    
    public function index() {
        //pre($this->session->userdata['backend_user']['id']); die;
        if (isset($this->session->userdata['backend_user']['user_type'])) { //die('working');
            $data = array('title' => 'Dashboard', 'pageTitle' => 'Dashboard');
            redirect('admin_panel/Admin/dashboard');die;
        }
        if ($this->input->post()) { //pre($this->input->post()); die;
            $this->form_validation->set_rules('username', 'User Name', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == TRUE) {
                $this->set_login_cookie($this->input->post());
                $row = $this->Admin_model->is_login_success($this->input->post());
                if ($row) { //die('working');  
                    $mySession = array('id' => $row->id, 'name' => $row->name, 'email' => $row->email, 'user_type' => $row->user_type, 'profile_image' => $row->profile_image);
                    // if($row->user_type==1){
                    //     $this->db->where('fk_owner_id',$row->id);
                    //     $owner_rest = $this->db->get('restaurants')->row_array();
                    //     if($owner_rest){
                    //         //die('wrong');
                    //         $mySession['rest_id'] = $owner_rest['id'];
                    //     }else{
                    //         $this->session->set_flashdata('error_message', 'No restaurant');
                    //         redirect('admin_panel/Admin','refresh'); die;
                    //     }                        
                    // }
                    $this->session->set_userdata(array("backend_user"=>$mySession));
                    redirect('admin_panel/Admin/dashboard'); die;
                }else{
                    $this->session->set_flashdata('error_message', 'Wrong username and password');
                }
            }
        }
        $data = array('title' => 'Admin Portal Login Page');
        $this->load->view('login/login', $data);
    }
   
    public function dashboard() {        
        $filter['id'] = [];
        $data = array('title' => 'Dashboard', 'pageTitle' => 'Dashboard');
        $data['users'] = $this->Users_model->count_users();
        $data['provider'] = $this->Users_model->count_providers();
        $data['property_agent'] = $this->Users_model->count_property_agent();
        if($this->session->userdata['backend_user']['user_type']==2){
            $filter['id'] = $this->session->userdata['backend_user']['id'];
        }
        $data['property'] = $this->Users_model->count_property($filter);
        $data['sale_type'] = $this->Users_model->count_property_sale_type($filter);
        
        //pre($data['sale_type']); die;
        $this->load->view('dashboard', $data);
    }

     public function logout() {
        $this->session->unset_userdata();
        $this->session->sess_destroy();
        redirect('admin_panel/Admin');
    }

    public function editProfileDetails() {
        $obj = new admin_model;
        if (isset($_GET['zxcvbnm'])) {
            $userId = base64_decode($_GET['zxcvbnm']);  //echo $userId;
            $data = array('title' => 'Edit Profile Details', 'pageTitle' => 'Edit Profile Details');
            $data['user_type'] = $this->session->userdata['backend_user']['userType'];
           if ($data['user_type'] == 10) {//die('1');
                //$data['user_type'] = 10;
                $data['user'] = $this->db->select('*')->from('schl_superAdmin')->where(array('id' => $userId))->get()->row();
            }else{
                $data['user'] = $this->db->select('*')->from('schl_users')->where(array('id' => $userId))->get()->row();
            }
            // pre($data);
            $this->load->view('editProfileDetails', $data);
            if ($_POST) {
                $this->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
                //$this->form_validation->set_rules('username','User Name','required');
                $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == TRUE) {
                    $name = $_POST['name'];
                    //$username 	        = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $filename = $_FILES['profileimage']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $profileImage = '';

                    if (in_array($ext, array('png', 'jpeg', 'jpg', 'PNG', 'JPEG', 'JPG'))) {
                        $profileImage = md5(time()) . '.' . $ext;
                        move_uploaded_file($_FILES['profileimage']['tmp_name'], getcwd() . '/profileImages/' . $profileImage);

                        $filepath = $_SERVER['DOCUMENT_ROOT'] . "/schoolPTM/profileImages/" . $profileImage;
                        $thumbpath = $_SERVER['DOCUMENT_ROOT'] . "/schoolPTM/profileImages/thumbnails/" . $profileImage;
                        $thumbnail_width = 400;
                        $thumbnail_height = 400;
                        $thumb = $obj->createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background = false);
                    }
                    if (empty($profileImage)) {
                        if ($data['user_type'] == 1) {
                            $a = $this->db->select('*')->from('users')->where(array('id' => $userId))->get()->row();
                        } else {
                            $a = $this->db->select('*')->from('superAdmin')->where(array('id' => $userId))->get()->row();
                        }
                        $profileImage = $a->profileImage;
                    }
                    $updateArray = array(
                        'name' => $name,
                        //'username'=>$username,
                        'email' => $email,
                        'password' => $password,
                        'profileImage' => $profileImage,
                    );
                    //$where = array('id'=>$userId);
                    $this->db->where('id', $userId);
                    if ($data['user_type'] == 1) {
                        $result = $this->db->update('users', $updateArray);
                    } else {
                        $result = $this->db->update('superAdmin', $updateArray);
                    }
                    //$result = $this->admin_model->update_record_by_id('schl_superAdmin', $updateArray, $where);
                    if ($result == TRUE) {
                        $this->session->set_flashdata('success_message', 'Profile has been updated  successfully');
                        redirect('dashboard');
                    }
                }
            }
        }
    }

}
