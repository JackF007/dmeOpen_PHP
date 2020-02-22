<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('aul', 'custom'));
        $this->load->helper('file_upload');
        $this->load->model('Category_Model');
        $this->form_validation->run($this);
        $this->load->library("form_validation", 'uploads');
        $this->load->library('image_lib');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
    }

//=================================== DEFAULT FUNCTION ==========================================//

    public function index($id = '') {
        $user_data = $this->session->userdata('active_user_data');
        $view_data['page'] = 'subcategory';
        $data['page_data'] = $this->load->view('admin/WELCOME_PAGE_SUPER_USER', $view_data, TRUE);
        $data['page_title'] = "Sub Category";
        echo modules::run('auth_panel/template/call_default_template', $data);
    }

//==============================================================================================//
//=================================== CALLBACK FOR CATEGORY Name================================//

    public function subcategoryCheck($val) {
        if ($val != '') {
            $subcategoryCheck = $this->db->get_where('sub_category_list', array('sub_category_name' => $val, 'sub_category_status' => '0'));
            $countcategory = $subcategoryCheck->num_rows();
            if ($countcategory == 0) {
                return true;
            } else {
                $this->form_validation->set_message('subcategoryCheck', 'This Category is already Added.!');
                return false;
            }
        } else {
            $this->form_validation->set_message('subcategoryCheck', 'Enter Category.!');
            return false;
        }
    }

//=================================== CALLBACK FOR EDIT CATEGORY ===============================//

    public function subcategoryCheckEdit($val) {
        if ($val != '') {
            $flag = 0;
            $categoryold = $this->db->get_where('category', array('category_status' => '0'))->result_array();
            foreach ($categoryold as $d) {
                if ($d['sub_category_name'] == $val) {
                    $flag = 1;
                }
            }


            $subcategoryCheck = $this->db->get_where('sub_category_list', array('sub_category_name' => $val, 'sub_category_status' => '0'));

            $countcategory = $subcategoryCheck->num_rows();

            if ($flag == 1 || $countcategory == 0) {

                return true;
            } else {

                $this->form_validation->set_message('subcategoryCheckEdit', 'This Category is already Added.!');
                return false;
            }
        } else {

            $this->form_validation->set_message('subcategoryCheckEdit', 'Enter Category.!');
            return false;
        }
    }

    public function edit_category($id = null) {

        if (!$this->input->post()) {

            $view_data['page'] = '';
            $view_data['subcategory_data'] = $this->Category_Model->get_subcategory_data($id);
            $data['page_data'] = $this->load->view('category/edit_subcategory', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        } else {
            if ($this->input->post()) {
                $this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
                $this->form_validation->set_rules('sub_category_name', 'Subcategory Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
                if (!empty($_FILES['sub_category_logo']['name'])) {
                    $this->form_validation->set_rules('sub_category_logo', 'Subcategory Image', 'callback_image');
                }
                if ($this->form_validation->run() == False) {
                    $view_data['page'] = '';
                    $view_data['subcategory_data'] = $this->Category_Model->get_subcategory_data($id);
                    $data['page_data'] = $this->load->view('category/edit_subcategory', $view_data, TRUE);
                    echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
                } else {
                    $id = $this->input->post('id');
                    $cat_id = $this->input->post('cat_id');
                    $update_data = $this->Category_Model->update_category($id, $cat_id);
                    if ($update_data == true) {
                        $this->session->set_flashdata('success_message', 'Subcategory has been Updated succssfully');
                        page_alert_box('success', 'Success', 'SubCategory updated successfully');
                    } else {
                        $this->session->set_flashdata('error_message', 'Subcategory not Updated');
                        page_alert_box('danger', 'Error', 'SubCategory not updated');
                    }
                    redirect(AUTH_PANEL_URL . 'Category/categories/' . $cat_id);
                }
            }
        }
    }

//==============================================================================================//
//=================================== CALLBACK FOR IMAGE =========================================//

    public function image() {
        $filetype = $_FILES['sub_category_logo']['type'];
        $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png');

        if ($_FILES['sub_category_logo']['size'] > "1048576") {

            $this->form_validation->set_message('image', 'Image size is gretaer than 1MB. ');
            return false;
        } else if (!in_array($filetype, $allowed_ext)) {

            $this->form_validation->set_message('image', 'Only JPEG ,JPG and PNG files are Allowed ');
            return false;
        } else {

            return true;
        }
    }

//============================================================================================//
//=================================== CALLBACK FOR IMAGE =========================================//

    public function image_static() {
        $filetype = $_FILES['file_name']['type'];
        // print_r($filetype);die;
        $allowed_ext = array('image/jpg', 'image/jpeg', 'image/png');

        if ($_FILES['file_name']['size'] > "1048576") {

            $this->form_validation->set_message('image', 'Image size is gretaer than 1MB. ');
            return false;
        } else if (!in_array($filetype, $allowed_ext)) {

            $this->form_validation->set_message('image', 'Only JPEG ,JPG and PNG files are Allowed ');
            return false;
        } else {

            return true;
        }
    }

//============================================================================================//
//=================================== DELETE CATEGORY ==========================================//

    public function delete_category($cat_id, $sub_cat_id) {
        $delete_category = $this->Category_Model->delete_category($cat_id, $sub_cat_id);
        if ($delete_category == true) {
            $this->session->set_flashdata('success_message', 'SubCategory has been deleted succssfully');
            page_alert_box('success', 'Success', 'SubCategory deleted succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'SubCategory not Deleted');
            page_alert_box('danger', 'Error', 'SubCategory not deleted');
        }
        redirect(AUTH_PANEL_URL . 'category/categories/' . $cat_id);
    }

//==============================================================================================//
//=================================== BLOCK CATEGORY ==========================================//

    public function block_category($cat_id, $id, $status) {

        $block_category = $this->Category_Model->block_category($id, $status);
        if ($block_category == true) {
            $this->session->set_flashdata('success_message', 'SubCategory has been Blocked succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'SubCategory not Blocked');
        }
        redirect(AUTH_PANEL_URL . 'category/subcategory_list/' . $cat_id);
    }

//==============================================================================================//
//==============================================================================================//
    public function categories1($id) {

        $view_data['id'] = $id;
        $title = "";
        if ($id == 1) {
            $title = "Foreign Investment Calls";
        }
        if ($id == 2) {
            $title = "Communications";
        }
        if ($id == 3) {
            $title = "Competitions";
        }
        if ($id == 4) {
            $title = "Open Data";
        }
        $view_data['cat_id'] = $id;
        $view_data['title'] = $title;
        $view_data['page'] = 'subcategory_list';


        $data['page_data'] = $this->load->view('category/subcategory_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
    public function categories($id) {

        $view_data['id'] = $id;
        $title = "";
        if ($id == 1) {
            $view_data['title'] = "Foreign Investment Calls";
        }
        if ($id == 2) {
            $view_data['title'] = "Communications";
        }
        if ($id == 3) {
            $view_data['title'] = "Competitions";
        }
        if ($id == 4) {
            $view_data['title'] = "Open Data";
        }
        $view_data['category_id']=$id;
        
        $view_data['page'] = 'subcategory_list';
        $data['page_data'] = $this->load->view('category/comm_categories_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//==============================================================================================//
//=================================== Subcategory LIST ===============================================//
    public function sub_category($id='',$cat_id='') {

        $view_data['id'] = $id;
        $title = "";
        if ($id == 1) {
            $view_data['title'] = "Foreign Investment Calls";
        }
        if ($id == 2) {
            $view_data['title'] = "Communications";
        }
        if ($id == 3) {
            $view_data['title'] = "Competitions";
        }
        if ($id == 4) {
            $view_data['title'] = "Open Data";
        }
        $view_data['category_id']=$cat_id;
        $view_data['root_id']=$id;
        
        $view_data['page'] = 'subcategory_list';
        $data['page_data'] = $this->load->view('category/com_subcategories_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function subcategory_list($id) {

        $title = '';
        $view_data['page'] = 'subcategory_list';
        if ($id == 1) {
            $title = 'Foreign Investment';
        }
        if ($id == 2) {
            $title = 'Communications';
        }
        if ($id == 3) {
            $title = 'Competitions';
        }
        if ($id == 4) {
            $title = 'Open Data';
        }

        $view_data['title'] = $title;
        $view_data['id'] = $id;
        $data['page_data'] = $this->load->view('category/subcategory_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//==============================================================================================//
//=================================== AJAX FOR CATEGORY LIST ===================================//

    public function ajax_subcategory_list($id) {

        $requestData = $_REQUEST;

        $columns = array(
            // 0 => 'sub_category_id',
            0 => 'sub_category_name',
            1 => 'sub_category_creation_time',
            2 => 'sub_category_update_time'
        );
        $query = "SELECT count(category_id) as total FROM sub_category_list where category_id =" . $id;

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "select * from sub_category_list where category_id=" . $id;


        // if (!empty($requestData['columns'][0]['search']['value'])) {
        //     $sql .= " AND sub_category_id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        // }
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND sub_category_name LIKE '" . $requestData['columns'][0]['search']['value'] . "%'";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND sub_category_creation_time LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND sub_category_update_time LIKE '" . $requestData['columns'][2]['search']['value'] . "%'";
        }


        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        // $id = $requestData['start'];
        foreach ($result as $r) {

            $nestedData = array();
            // $nestedData[] = $id++;
            $nestedData[] = $r->sub_category_name;

            $nestedData[] = "<img src='" . base_url() . 'images/sub_category/' . $r->sub_category_logo . "' width='60px' height='60px'/>";

            $nestedData[] = $r->sub_category_creation_time;
            $nestedData[] = $r->sub_category_update_time;
            $nestedData[] = ($r->sub_category_status == 0 ? "<a title='Enable' class='btn btn-success btn-xs fa fa-check' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->category_id . "/" . $r->sub_category_id . "/1'> Enable </a>&nbsp;" : "<a title='Disable' class='btn btn-danger btn-xs fa fa-times' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->category_id . "/" . $r->sub_category_id . "/0'> Disable </a>&nbsp;");
            //  $nestedData[] = $r->sub_category_status;

            if ($r->category_id == 1) {
                $button = "<a class='btn-xs btn  bold btn-success' href='" . AUTH_PANEL_URL . "category/rss_feed/" . $r->sub_category_id . "/" . $r->category_id . "'>View</a>";
            } else if ($r->category_id == 2) {
                $button = "<a class='btn-xs btn  bold btn-success' href='" . AUTH_PANEL_URL . "category/static_page/" . $r->sub_category_id . "/" . $r->category_id . "'>View Static Page</a>";
            } else if ($r->category_id == 3) {
                $button = "<a class='btn-xs btn  bold btn-success' href='" . AUTH_PANEL_URL . "category/view_data1/" . $r->sub_category_id . "/" . $r->category_id . "/" . $r->sub_category_name . "'>View Subcategory</a>";
            }
            //if($r->sub_category_status == '0'){
            $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/edit_category/" . $r->sub_category_id . "'>Edit</a>&nbsp;"
                    . "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "category/delete_category/" . $r->category_id . "/" . $r->sub_category_id . "'>Delete</a>&nbsp;"
                    . $button;
//			}
//			else
//			{
//				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->sub_category_id . "/0'>Unblock</a>";
//			}

            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function block_backend_user($sub_category_id, $t, $sub_category_status) {

        $this->db->where('sub_category_id', $t);
        $this->db->update('sub_category_list', array('sub_category_status' => $sub_category_status));
        if ($this->db->affected_rows() == 1) {
            page_alert_box('success', 'Action Performed', 'User status change successfully.');
        } else {
            page_alert_box('danger', 'Action Performed', 'User status could not be updated.');
        }
        page_alert_box('success', 'Action Performed', 'User status change successfully.');
        // redirect(AUTH_PANEL_URL . 'Category/subcategory_list');
        redirect(AUTH_PANEL_URL . 'category/subcategory_list/' . $sub_category_id);
    }

    //=================================== CREATE SUBCATEGORY ==========================================//

    public function create_subcategory($id) {
        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
            $this->form_validation->set_rules('sub_category_name', 'Subcategory Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_subcategoryCheck');

            if (!empty($_FILES['sub_category_logo']['name'])) {
                $this->form_validation->set_rules('sub_category_logo', 'Logo Image', 'callback_image');
            } else {
                $this->form_validation->set_rules('sub_category_logo', 'Logo Image', 'required');
            }


            if ($this->form_validation->run() != FALSE) {
                $insetData = $this->Category_Model->create_category($id);

                if ($insetData == true) {
                    page_alert_box('success', 'Success', 'Subcategory created successfully');
                    redirect(AUTH_PANEL_URL . 'category/categories/' . $id);
                } else {
                    page_alert_box('danger', 'Error', 'Subcategory can not be created');
                }
            }
        }
        $view_data = array();
        $title = '';
        if ($id == 1) {
            $title = 'Foreign Investment';
        }
        if ($id == 2) {
            $title = 'Communications';
        }
        if ($id == 3) {
            $title = 'Competitions';
        }
        if ($id == 4) {
            $title = 'Open Data';
        }
        $view_data['title'] = $title;
        $view_data['id'] = $id;
        $view_data['page'] = 'create_subcategory';
        $data['page_data'] = $this->load->view('category/create_sub_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//==============================================================================================//
//==============================================================================================//


    public function static_page($sub_cat, $cat_id) {
        $view_data['page'] = 'static_page';
        $view_data['id'] = $sub_cat;
        $view_data['cat'] = $cat_id;

        $view_data['title'] = $this->Category_Model->get_title($sub_cat);
        $view_data['content'] = $this->Category_Model->get_static_content($sub_cat);

        // pre($view_data['content']);die('hell');
        $data['page_data'] = $this->load->view('category/subcategory_static_page', $view_data, TRUE);
        $data['page_title'] = "Static Page";
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function rss_feed($sub_cat, $cat_id) {
        $view_data['page'] = 'static_page';
        $view_data['id'] = $sub_cat;
        $view_data['cat'] = $cat_id;

        $view_data['title'] = $this->Category_Model->get_title($sub_cat);
        $view_data['content'] = $this->Category_Model->get_rss_content($sub_cat);

        // pre($view_data['content']);die('hell');
        $data['page_data'] = $this->load->view('category/add_rss_link', $view_data, TRUE);
        $data['page_title'] = "Static Page";
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    

    public function ajax_static_page($id) {

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'content',
        );
        $query = "SELECT count(sub_category_id) as total FROM static_content where sub_category_id =" . $id;
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "select static_content.id,static_content.sub_category_id,static_content.content,static_content.update_date,static_content.status FROM static_content left JOIN sub_category_list ON static_content.sub_category_id =sub_category_list.sub_category_id where sub_category_list.sub_category_id=" . $id;

        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND sub_category_id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " having content LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();

        $filessql = "SELECT page_file.file_name from page_file  JOIN  static_content on page_file.sub_category_id=static_content.sub_category_id WHERE static_content.sub_category_id = " . $id;
        // echo 'SELECT page_file.file_name from page_file JOIN static_content WHERE static_content.sub_category_id ="'.$id.'"';
        $files = $this->db->query($filessql)->result();
        $data = array();
        $id = 1;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $id++;
            $nestedData[] = $r->content;
            $image = "";
            if ($files) {
                foreach ($files as $file) {
                    $image = $image . "<img src='" . base_url() . '/images/static_content/' . $file->file_name . "' width='60px' height='60px'/>";
                }
            } else {
                $image = "<img src='" . base_url() . '/images/static_content/' . $file->file_name . "' width='60px' height='60px'/>";
            }
            $nestedData[] = $image;
            $nestedData[] = $r->update_date;
            $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/edit_static_page/" . $r->sub_category_id . "/" . $r->id . "'>Edit</a>";
            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function edit_static_page($id = null) {

        $data = [];
        if ($id != null) {

            // $this->db->where('id', $id);
            // $data['data'] = $this->db->get('financial_services')->row_array();

            $data['static_data'] = $this->db->get_where("static_content", array('sub_category_id' => $id))->row_array();
            $this->db->select('file_name');
            $this->db->where('sub_category_id', $id);
            $data['image'] = $this->db->get('page_file')->result_array();
        }
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $input = $this->input->post();

                unset($input['id']);

                if ($_FILES['file_name']['name']) {

                    // $new_name = time().".".pathinfo($_FILES['file_name']['name'],PATHINFO_EXTENSION);
                    //  print_r($new_name); die();
                    // $input['file_name'] = $new_name;
                    //print_r($input); die();
                    $upload_path = "./images/static_content/";
                    $configUpload['upload_path'] = "./images/static_content/";                 #the folder placed in the root of project
                    $configUpload['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
                    $config['remove_spaces'] = true;
                    $config['overwrite'] = false;

                    $configUpload['encrypt_name'] = false;                         #encrypt name of the uploaded fil

                    $this->load->library('upload', $configUpload);                  #init the upload class
                    if (!$this->upload->do_upload('uploadimage')) {
                        $uploadedDetails = $this->upload->display_errors();
                        // print_r($uploadedDetails); die('fdfijij');
                        $this->session->set_flashdata('upload_error', 'Image exceeds maximum allowed size!!');

                        $data['page_data'] = $this->load->view('category/edit_static_page', $data, TRUE);
                        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
                    } else {
                        $uploadedDetails = $this->upload->data();
                    }
                }
                $this->db->where('sub_category_id', $id);
                $insert = $this->db->update('static_content', $input);
                redirect(AUTH_PANEL_URL . 'Category/static_page');
            } else {
                $insert = $this->db->insert('financial_services', $input);
            }

            if ($insert) {
                redirect(AUTH_PANEL_URL . 'Category/static_page');
            }
        }
        $data['page_data'] = $this->load->view('category/edit_static_page', $data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_static_content() {
        //  pre($_REQUEST);die;

        $insert_array = $this->input->post();
        if (isset($_POST['add'])) {
            $count = count($_FILES['file']['name']);

            for ($i = 0; $i < $count; $i++) {
                $_FILES['img2']['name'] = $_FILES['file']['name'][$i];
                $_FILES['img2']['type'] = $_FILES['file']['type'][$i];
                $_FILES['img2']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                $_FILES['img2']['error'] = $_FILES['file']['error'][$i];
                $_FILES['img2']['size'] = $_FILES['file']['size'][$i];
                $config['upload_path'] = './images/static_content/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                $config['max_size'] = '0';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload');
                $this->upload->initialize($config);
                $this->upload->do_upload('img2');
                $data = $this->upload->data();
                $config1['image_library'] = 'gd2';
                $config1['source_image'] = './images/static_content/' . $data["file_name"];
                $config1['create_thumb'] = FALSE;
                $config1['maintain_ratio'] = FALSE;
                $config1['quality'] = '80%';
                $config1['new_image'] = './images/static_content/' . $data["file_name"];
                $this->load->library('image_lib', $config1);
                $this->image_lib->initialize($config1);
                $this->image_lib->resize();
                $image[] = $data['file_name'];
            }

            unset($insert_array['add']);
            unset($insert_array['cat']);
            $insert = $this->db->insert('static_content', $insert_array);
            $data = $this->db->get_where('static_content', array('id' => $this->db->insert_id()))->row_array();

            $insert_array1['sub_category_id'] = $data['sub_category_id'];
            // $insert_array1['file_name'] = implode(",",$image);
            $insert_array1['file_name'] = json_encode($image);
            $insert = $this->db->insert('page_file', $insert_array1);

            if ($this->db->affected_rows() == 1) {
                echo json_encode(array('status' => true, 'message' => 'Added'));
                page_alert_box('success', 'Success', 'Content added successfully');
            } else {
                echo json_encode(array('status' => false, 'message' => 'not  Added'));
                page_alert_box('danger', 'Error', 'Content can not be added');
            }
        }
        if (isset($_POST['edit'])) { {
                unset($insert_array['edit']);
                unset($insert_array['cat']);
                unset($insert_array['sub_cat']);
                if ($_FILES['img']['name'] != '') {

                    $_FILES['img2']['name'] = $_FILES['img']['name'];
                    $_FILES['img2']['type'] = $_FILES['img']['type'];
                    $_FILES['img2']['tmp_name'] = $_FILES['img']['tmp_name'];
                    $_FILES['img2']['error'] = $_FILES['img']['error'];
                    $_FILES['img2']['size'] = $_FILES['img']['size'];
                    $config['upload_path'] = './images/static_content/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                    $config['max_size'] = '0';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    $this->upload->do_upload('img2');
                    $data = $this->upload->data();
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = './images/static_content/' . $data["file_name"];
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['quality'] = '80%';
                    $config1['new_image'] = './images/static_content/' . $data["file_name"];
                    $this->image_lib->resize();
                    $image = $data['file_name'];

                    $this->db->where('sub_category_id', $this->input->post('sub_cat'));
                    $abc = $this->db->get('page_file')->row_array();

                    $data_array = json_decode($abc['file_name'], true);
                    array_push($data_array, $image);

                    $update_array = array('file_name' => json_encode($data_array));
                    $this->db->where('sub_category_id', $this->input->post('sub_cat'));
                    $update = $this->db->update('page_file', $update_array);
                }

                $this->db->where('sub_category_id', $this->input->post('sub_cat'));
                $update = $this->db->update('static_content', $insert_array);

                if ($update) {

                    page_alert_box('success', 'Success', 'Content updated successfully');
                    redirect(AUTH_PANEL_URL . 'Category/categories/1');
                } else {
                    page_alert_box('danger', 'Error', 'Content can not be updated');
                }
            }
            redirect('auth_panel/category/static_page/' . $this->input->post('sub_cat') . '/' . $this->input->post('cat'));
        }
    }

    public function add_rss_link() {
        if ($_POST['link'] == '' || filter_var($_POST['link'], FILTER_VALIDATE_URL) == FALSE || is_valid_rss_url($_POST['link']) == FALSE) {
            echo json_encode(array('status' => false, 'message' => 'Please enter a valid RSS URL'));
            die();
        }
        $insert_array = $this->input->post();
        if (isset($_POST['add'])) {
            unset($insert_array['add']);
            //unset($insert_array['cat']);

            //$insert_array['sub_category_id'] = $insert_array['sub_cat'];
            //unset($insert_array['sub_cat']);
            $insert_array['created']=time();
            $insert_array['updated']=time();
            $insert = $this->db->insert('rss_link', $insert_array);
            $data = $this->db->get_where('rss_link', array('id' => $this->db->insert_id()))->row_array();

            if ($this->db->affected_rows() == 1) {
                echo json_encode(array('status' => true, 'message' => 'Content added successfully', 'data' => AUTH_PANEL_URL . 'Category/categories/'.$insert_array['root_id']));
                page_alert_box('success', 'Success', 'Content added successfully');
                die;
            } else {
                echo json_encode(array('status' => false, 'message' => 'Content could not be added'));
                page_alert_box('danger', 'Error', 'Content could not be added');
                die;
            }
        }
        if (isset($_POST['edit'])) {
            //pre($_POST);die;
            unset($insert_array['edit']);
            $feed_id=$_POST['id'];
            unset($insert_array['id']);

            
            $insert_array['updated']=time();
            $this->db->where('id', $feed_id);
            $update = $this->db->update('rss_link', $insert_array);

            if ($update) {
                echo json_encode(array('status' => true, 'message' => 'Content updated successfully', 'data' => AUTH_PANEL_URL . 'Category/categories/'.$insert_array['root_id']));
                page_alert_box('success', 'Success', 'Content updated successfully');
                die;
            } else {
                echo json_encode(array('status' => false, 'message' => 'Content could not be updated'));
                page_alert_box('danger', 'Error', 'Content could not be updated');
                die;
            }
        }
        redirect('auth_panel/category/static_page/' . $this->input->post('sub_cat') . '/' . $this->input->post('cat'));
    }

    public function delete_image($id, $cat, $name) {
        //  pre($cat);die;
        $data = [];
        $this->db->select('file_name');
        $img_name = $this->db->get_where('page_file', array('sub_category_id' => $id))->row()->file_name;
        $file = json_decode($img_name, TRUE);
        $pos = array_search($name, $file);
        $del = array_splice($file, $pos, 1);
        $file2 = json_encode($file, TRUE);
        $this->db->where('sub_category_id', $id);
        $delete = $this->db->update('page_file', array('file_name' => $file2));
        unlink('./images/static_content/' . $del[0]);

        if ($delete == true) {
            page_alert_box('success', 'Done', 'Image has been Deleted successfully');
            redirect('auth_panel/Category/static_page/' . $id . '/' . $cat);
        } else {
            page_alert_box('danger', 'Failed', 'Image could not be Deleted');
        }
    }

    function open_data() {
        $data['page_data'] = $this->load->view('category/post_list', '', TRUE);

        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_post() {
        //  pre($_FILES);
        // pre($_REQUEST);
        error_reporting(0);
        $input = array();
        if ($this->input->post()) {
            $input = $this->input->post();
            //  && $_FILES['photo']['name']!='')
            if (isset($_FILES['photo'])) {

                $_FILES['img2']['name'] = $_FILES['photo']['name'];
                $_FILES['img2']['type'] = $_FILES['photo']['type'];
                $_FILES['img2']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $_FILES['img2']['error'] = $_FILES['photo']['error'];
                $_FILES['img2']['size'] = $_FILES['photo']['size'];
                $config['upload_path'] = './images/open_data_files/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                $config['max_size'] = '0';
                $this->load->library('upload');
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('img2')) {
                    echo json_encode(array('status' => false, 'message' => $this->upload->display_errors()));
                    die();
                }
                $data = $this->upload->data();
                $config1['image_library'] = 'gd2';
                $config1['source_image'] = './images/open_data_files/' . $data["file_name"];
                $config1['create_thumb'] = FALSE;
                $config1['maintain_ratio'] = FALSE;
                $config1['quality'] = '80%';
                $config1['new_image'] = './images/open_data_files/' . $data["file_name"];
                $this->image_lib->resize();
                $input['photo'] = $data['file_name'];
            }

            $data = $this->Category_Model->insert_data($input);

            if (isset($_FILES['file'])) {
                $main = $data['feed_id'];

                $count = count($_FILES['file']['name']);
                for ($i = 0; $i < $count; $i++) {
                    $_FILES['img2']['name'] = $_FILES['file']['name'][$i];
                    $_FILES['img2']['type'] = $_FILES['file']['type'][$i];
                    $_FILES['img2']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $_FILES['img2']['error'] = $_FILES['file']['error'][$i];
                    $_FILES['img2']['size'] = $_FILES['file']['size'][$i];
                    $config['upload_path'] = './images/open_data_files/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                    $config['max_size'] = '0';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('img2')) {
                        echo json_encode(array('status' => false, 'message' => $this->upload->display_errors()));
                        //echo $this->upload->display_errors();
                        die();
                    }
                    $data = $this->upload->data();
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = './images/open_data_files/' . $data["file_name"];
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['quality'] = '80%';
                    $config1['new_image'] = './images/open_data_files/' . $data["file_name"];
                    $this->image_lib->resize();
                    $image[] = $data['file_name'];
                }
                $res['feed_id'] = $main;
                $res['name'] = $image;

                $data = $this->Category_Model->insert_data1($res);
            }

            if ($data == true) {
                echo json_encode(array('status' => true, 'message' => 'Added'));
                die();
                page_alert_box('success', 'Post Added', 'Post Added Successfully.');
            } else {
                echo json_encode(array('status' => False, 'message' => 'Data not Added'));
                die();
                page_alert_box('danger', 'Failed', 'Brand Not Added.');
            }
        }

        $data['page_data'] = $this->load->view('category/tweet', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_services() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'post',
            1 => 'photo'
        );

        $query = "SELECT count(id) as total	FROM  admin_post";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM  admin_post WHERE 1";


        if (!empty($requestData['columns'][0]['search']['value'])) {  //experience
            $sql .= " AND post LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {  //experience
            $sql .= " AND photo LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();

        // $id = 1;
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            // $nestedData[] = $id++;
            $nestedData[] = $r->post;
            $path = base_url() . 'images/open_data_files/' . $r->photo;
            $nestedData[] = "<img class='task-thumb' src='$path'>";

            $data[] = $nestedData;
        }
//<a class='btn btn-warning view_data'id='$r->id'>View</a>
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

    public function add_hastag() {

        if ($this->input->post()) {
            $data = array(
                'tag' => $this->input->post('tag'));
            $this->db->insert(' post_tags', $data);
            redirect(AUTH_PANEL_URL . 'Category/add_hastag');
        }
        $data['page_data'] = $this->load->view('category/hastag', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function view_data1($id, $cat_id) {
        $sub_id['id'] = $id;
        $sub_id['c_id'] = $cat_id;
        $data['page_data'] = $this->load->view('category/comption', $sub_id, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_static_page1($id) {

        $requestData = $_REQUEST;

        $columns = array(
            // 0 => 'id',
            0 => 'child_name',
        );
        // $query = "SELECT count(sub_category_id) as total FROM child_category";
        $query = "SELECT count(sub_category_id) as total FROM sub_category_list where sub_category_id =" . $id;
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "select * from child_category where sub_category_id=" . $id;


        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND child_name LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND child_category.child_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);

        // $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();

        $data = array();
        // $id = 1;
        foreach ($result as $r) {
            $nestedData = array();
            // $nestedData[] = $id++;
            $nestedData[] = $r->child_name;
            $path = base_url() . 'images/child_logo/' . $r->child_logo;
            $nestedData[] = "<img class='task-thumb' src='$path'>";
            $nestedData[] = $r->child_creation_time;
            $nestedData[] = $r->child_update_time;
            $nestedData[] = ($r->child_status == 0 ? "<a title='Enable' class='btn btn-success btn-xs fa fa-check' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->category_id . "/" . $r->sub_category_id . "/1'> Enable </a>&nbsp;" : "<a title='Disable' class='btn btn-danger btn-xs fa fa-times' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->category_id . "/" . $r->sub_category_id . "/0'> Disable </a>&nbsp;");
            $action = "<a class='btn-xs btn  bold btn-success' href='" . AUTH_PANEL_URL . "category/static_page1/" . $r->child_id . "/" . $r->sub_category_id . "'>View static page</a>";
            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    public function static_page1($child_id) {

        $view_data['id'] = $child_id;
        $view_data['content'] = $this->Category_Model->get_static_content1($child_id);
        $data['page_data'] = $this->load->view('category/subcategory_comp', $view_data, TRUE);

        $data['page_title'] = "Static Page";
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_static_content_comp() {
        // die('cndkcndk');

        $insert_array = $this->input->post();
        //$insert_array['child_id']=$insert_array['cat'];

        if (isset($_POST['add'])) {
            if (isset($_FILES['file'])) {
                $count = count($_FILES['file']['name']);

                for ($i = 0; $i < $count; $i++) {
                    $_FILES['img2']['name'] = $_FILES['file']['name'][$i];
                    $_FILES['img2']['type'] = $_FILES['file']['type'][$i];
                    $_FILES['img2']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $_FILES['img2']['error'] = $_FILES['file']['error'][$i];
                    $_FILES['img2']['size'] = $_FILES['file']['size'][$i];
                    $config['upload_path'] = './images/child_static_content/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                    $config['max_size'] = '0';
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload');
                    $this->upload->initialize($config);
                    $this->upload->do_upload('img2');
                    $data = $this->upload->data();
                    $config1['image_library'] = 'gd2';
                    $config1['source_image'] = './images/child_static_content/' . $data["file_name"];
                    $config1['create_thumb'] = FALSE;
                    $config1['maintain_ratio'] = FALSE;
                    $config1['quality'] = '80%';
                    $config1['new_image'] = './images/child_static_content/' . $data["file_name"];
                    $this->load->library('image_lib', $config1);
                    $this->image_lib->initialize($config1);
                    $this->image_lib->resize();
                    $image[] = $data['file_name'];
                }
                $insert_array1['child_id'] = $insert_array['child_id'];
                // $insert_array1['file_name'] = implode(",",$image);
                $insert_array1['file_name'] = json_encode($image);
                $insert = $this->db->insert('child_page_file', $insert_array1);
            }

            unset($insert_array['add']);
            //unset($insert_array['cat']);
            $this->db->insert('child_static_content', $insert_array);
            $data = $this->db->get_where('child_static_content', array('child_id' => $insert_array['child_id']))->row_array();


            if ($this->db->affected_rows() == 1) {
                echo json_encode(array('status' => true, 'message' => 'Added'));
                page_alert_box('success', 'Success', 'Content added successfully');
            } else {
                echo json_encode(array('status' => false, 'message' => 'not  Added'));
                page_alert_box('danger', 'Error', 'Content can not be added');
            }
        }
        if (isset($_POST['edit'])) { {
                unset($insert_array['edit']);
                unset($insert_array['cat']);
                unset($insert_array['sub_cat']);

                $_FILES['img2']['name'] = $_FILES['img']['name'];
                $_FILES['img2']['type'] = $_FILES['img']['type'];
                $_FILES['img2']['tmp_name'] = $_FILES['img']['tmp_name'];
                $_FILES['img2']['error'] = $_FILES['img']['error'];
                $_FILES['img2']['size'] = $_FILES['img']['size'];
                $config['upload_path'] = './images/child_static_content/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
                $config['max_size'] = '0';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload');
                $this->upload->initialize($config);
                $this->upload->do_upload('img2');
                $data = $this->upload->data();
                $config1['image_library'] = 'gd2';
                $config1['source_image'] = './images/child_static_content/' . $data["file_name"];
                $config1['create_thumb'] = FALSE;
                $config1['maintain_ratio'] = FALSE;
                $config1['quality'] = '80%';
                $config1['new_image'] = './images/child_static_content/' . $data["file_name"];
                $this->image_lib->resize();
                $image = $data['file_name'];

                $this->db->where('child_id', $this->input->post('sub_cat'));
                $update = $this->db->update('child_static_content', $insert_array);

                $this->db->where('child_id', $this->input->post('sub_cat'));
                $abc = $this->db->get('child_page_file')->row_array();

                $data_array = json_decode($abc['file_name'], true);
                array_push($data_array, $image);

                $update_array = array('file_name' => json_encode($data_array));
                $this->db->where('child_id', $this->input->post('sub_cat'));
                $update = $this->db->update('child_page_file', $update_array);

                if ($this->db->affected_rows() == 1) {
                    //  redirect(AUTH_PANEL_URL . 'category/static_page');
                    page_alert_box('success', 'Success', 'Content updated successfully');
                } else {
                    page_alert_box('danger', 'Error', 'Content can not be updated');
                }
            }
            redirect('auth_panel/category/static_page1/' . $this->input->post('sub_cat') . '/' . $this->input->post('cat'));
        }
    }

    public function delete_image1($id, $cat, $name) {
        $data = [];
        $this->db->select('file_name');
        $img_name = $this->db->get_where('child_page_file', array('child_id' => $id))->row()->file_name;
        $file = json_decode($img_name, TRUE);
        $pos = array_search($name, $file);
        $del = array_splice($file, $pos, 1);
        $file2 = json_encode($file, TRUE);
        $this->db->where('child_id', $id);
        $delete = $this->db->update('child_page_file', array('file_name' => $file2));
        unlink('./images/child_static_content/' . $del[0]);

        if ($delete == true) {
            page_alert_box('success', 'Done', 'Image has been Deleted successfully');
            redirect('auth_panel/Category/static_page1/' . $id . '/' . $cat);
        } else {
            page_alert_box('danger', 'Failed', 'Image could not be Deleted');
        }
    }

    public function add_sub_copp($id, $cat_id) {
        $input = $this->input->post();
        $input['sub_category_id'] = $id;
        $input['category_id'] = $cat_id;

        if ($this->input->post()) {
            // $input['child_name'] = $this->input->post();

            $_FILES['img2']['name'] = $_FILES['child_logo']['name'];
            $_FILES['img2']['type'] = $_FILES['child_logo']['type'];
            $_FILES['img2']['tmp_name'] = $_FILES['child_logo']['tmp_name'];
            $_FILES['img2']['error'] = $_FILES['child_logo']['error'];
            $_FILES['img2']['size'] = $_FILES['child_logo']['size'];
            $config['upload_path'] = './images/child_logo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|docx|xlsx|csv';
            $config['max_size'] = '0';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload');
            $this->upload->initialize($config);
            $this->upload->do_upload('img2');
            $data = $this->upload->data();
            $config1['image_library'] = 'gd2';
            $config1['source_image'] = './images/child_logo/' . $data["file_name"];
            $config1['create_thumb'] = FALSE;
            $config1['maintain_ratio'] = FALSE;
            $config1['quality'] = '80%';
            $config1['new_image'] = './images/child_logo/' . $data["file_name"];
            $this->image_lib->resize();
            $input['child_logo'] = $data['file_name'];

            $data = $this->Category_Model->all_data($input);
            if ($data == true) {
                page_alert_box('success', 'Provider Added', 'Provider Added Successfully.');
                redirect('auth_panel/Category/view_data1/' . $id . '/' . $cat_id);
                // redirect('auth_panel/Category/static_page/'.$id.'/'.$cat);
            } else {

                page_alert_box('danger', 'Failed', 'Brand Not Added.');
                redirect(AUTH_PANEL_URL . 'Financial_services/index');
            }

            redirect(AUTH_PANEL_URL . 'Financial_services/index');
        }

        $data['page_data'] = $this->load->view('category/comption.php', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_hash_list() {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'tag',
        );
        $query = "SELECT count(id) as total FROM post_tags";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $query['total'] = count($query);
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * from post_tags WHERE 1";


        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND tag LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->tag;
            $nestedData[] = $r->created_date;
            $nestedData[] = $r->update_date;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }

    //===============================================================================//

    public function create_category($id = "",$sub_cat="") {
        $view_data = array();
        if($id==1){
        $table='fic_category';
        $view_data['title'] = 'Foreign Investment Calls';
        }
        if($id==2){
            $table='comm_category';
            $view_data['title'] = 'Communications';
        }
        if($id==3){
            $table='competition_category';
            $view_data['title'] = 'Competitions';
        }
        if($id==4){
            $table='opendata_category';
            $view_data['title'] = 'Open-Data';
        }
        if ($this->input->post()) {
            
            $this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
            $this->form_validation->set_rules('category_id', 'Parent category', 'required');
            $this->form_validation->set_rules('category_id', 'Parent category', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_subcategoryCheck');

//            if (!empty($_FILES['logo']['name'])) {
//                $this->form_validation->set_rules('logo', 'Logo Image', 'callback_image');
//            } else {
//                $this->form_validation->set_rules('logo', 'Logo Image', 'required');
//            }

            if ($this->form_validation->run() != FALSE) {
                $inputs = $this->input->post();
                pre($inputs);
                $update_id=$inputs['id'];
                unset($inputs['id']);
                unset($inputs['edit']);
                if (isset($inputs['edit'])) {
                    $inputs['modify_time'] = time();
                    $this->db->where('id', $update_id);
                    $this->db->update($table, $inputs);                   
                    page_alert_box('success', 'Success', 'Category update successfully');
                } else {
                unset($inputs['id']);               
                    if($inputs['category_id']!=0){
                        $this->db->where('id',$inputs['category_id']);
                        $this->db->update($table,array('have_child'=>1));
                    }
                    $inputs['created'] = $inputs['updated'] = time();
                    $this->db->insert($table, $inputs);
                    page_alert_box('success', 'Success', 'Category created successfully');
                }
                redirect(AUTH_PANEL_URL . 'category/categories/'.$id);
            }
        }
        if (!empty($sub_cat)) {
            $view_data['result'] = $this->db->get_where($table, ['id' => $sub_cat])->row_array();            
        }
        $view_data['table'] = $table;
        $view_data['page'] = 'create_comm_category';
        $view_data['root'] = $id;
        $view_data['parent'] = $sub_cat;
        $data['page_data'] = $this->load->view('category/create_comm_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function comm_categories() {
        $view_data['title'] = "Communications";
        $view_data['page'] = 'subcategory_list';
        $data['page_data'] = $this->load->view('category/comm_categories_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_comm_categories($id) {
        if($id==1){
            $table='fic_category';
        }
        if($id==2){
            $table='comm_category';
        }
        if($id==3){
            $table='competition_category';
        }
        if($id==4){
            $table='opendata_category';
        }
        

        $requestData = $_REQUEST;
        $where = "where category_id = 0";
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'category_name',
        );

        $query = "SELECT count($table.id) as total FROM $table $where";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT *
                FROM $table
                $where";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND category_name LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {   //name
            $sql .= " AND rss_link LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        $i = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$i;
            $nestedData[] = $r->category_name;
            $image = "";
            if ($r->logo) {
                $image = $image . "<img src='" . base_url() . '/images/static_content/' . $r->logo . "' width='60px' height='60px'/>";
            } else {
                $image = "";
            }
            $nestedData[] = $image;
            //$nestedData[] = $r->rss_link;
            $cats = [];
            if ($r->category_id != "0") {

                $this->db->where('id', $r->category_id);
                $cat_detail = $this->db->get('comm_category')->row_array();
                $category_heirarchy = $cat_detail['category_name'];
            } else {
                $category_heirarchy = "---";
            }
            $nestedData[] = "<b>" . $category_heirarchy . "</b>";
            $nestedData[] = date('d-M-Y', $r->created);
            $nestedData[] = date('d-M-Y', $r->updated);
            $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/create_category/" . $id . "/".$r->id."'>Edit</a>";
            if($r->have_child==1){
                $action .= " <a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/sub_category/" . $id . "/".$r->id."'>View Subcategory</a>";
            }else{
                $action .= " <a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/rss_link/" . $id . "/".$r->id."'>View RSS</a>";
            }
            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }
    public function ajax_comm_subcategories($id,$cat_id) {
        if($id==1){
            $table='fic_category';
        }
        if($id==2){
            $table='comm_category';
        }
        if($id==3){
            $table='competition_category';
        }
        if($id==4){
            $table='opendata_category';
        }
        

        $requestData = $_REQUEST;
        $where = "where $table.category_id = $cat_id";
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'category_name',
        );

        $query = "SELECT count($table.id) as total FROM $table $where";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT $table.*,rss_link.link
                FROM $table left join rss_link on $table.id=rss_link.category_id
                $where";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND category_name LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {   //name
            $sql .= " AND rss_link LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        //echo $this->db->last_query();
        $data = array();
        $i = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$i;
            $nestedData[] = $r->category_name;
            $image = "";
            if ($r->logo) {
                $image = $image . "<img src='" . base_url() . '/images/static_content/' . $r->logo . "' width='60px' height='60px'/>";
            } else {
                $image = "";
            }
            $nestedData[] = $image;
            $nestedData[] = $r->link;
            $cats = [];
            if ($r->category_id != "0") {

                $this->db->where('id', $r->category_id);
                $cat_detail = $this->db->get("$table")->row_array();
                $category_heirarchy = $cat_detail['category_name'];
            } else {
                $category_heirarchy = "None";
            }
            $nestedData[] = "<b>" . $category_heirarchy . "</b>";
            $nestedData[] = date('d-M-Y', $r->created);
            $nestedData[] = date('d-M-Y', $r->updated);
            $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/create_category/" . $id . "/".$r->id."'>Edit</a>";
            if($r->have_child==1){
                $action .= " <a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/sub_category/" . $id . "/".$r->id."'>View Subcategory</a>";
            }else{
                $action .= " <a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/rss_link/" . $id . "/".$r->id."'>View RSS</a>";
            }
            $nestedData[] = $action;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);
    }
    
    public function rss_link($root_id, $cat_id) {
        if($root_id==1){
            $table='fic_category';
            $view_data['title'] = 'Foreign Investment Calls';
        }
        if($root_id==2){
            $table='comm_category';        
            $view_data['title'] = 'Communications';        
        }
        if($root_id==3){
            $table='competition_category';
            $view_data['title'] = 'Competitions';
        }
        if($root_id==4){
            $table='opendata_category';
            $view_data['title'] = 'Open-Data';
        }
        $view_data['page'] = 'rss_link';
        $view_data['id'] = $root_id;
        $view_data['cat'] = $cat_id;

        
        $view_data['content'] = $this->Category_Model->get_rss_contents($root_id,$cat_id);
        $view_data['root_id']=$root_id;
        $view_data['cat_id']=$cat_id;

        $data['page_data'] = $this->load->view('category/add_rss_link', $view_data, TRUE);
        $data['page_title'] = "Static Page";
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function ajax_get_cat_hierarchy(){
        if($this->input->post('cat_id')){
            $cat_id = $this->input->post('cat_id');
            
            $this->db->where('id', $cat_id);
            $cat_detail = $this->db->get('comm_category')->row_array();
            echo $cat_detail['category_name'];
            
            //$cat_name[] = $cat_detail['category_name'];
        }
    }

    //==============================================================================//
}
