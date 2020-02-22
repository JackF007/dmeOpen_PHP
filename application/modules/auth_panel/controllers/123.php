<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('aul','custom'));
        $this->load->model('Category_Model');
        $this->form_validation->run($this);
        $this->load->library("form_validation", 'uploads');
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
//=================================== CALLBACK FOR CATEGORY ==========================================//

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

//==============================================================================================//
//=================================== EDIT CATEGORY ==========================================//
    // public function edit_category($id = null)
    // {
    //
	// 	if (!$this->input->post())
    // 	{
    //
	// 		$view_data['page'] = '';
    // 		$view_data['category_data'] = $this->Category_Model->get_category_data($id);
    //     // print_r($view_data);die;
    // 		$data['page_data'] = $this->load->view('category/edit_category',$view_data, TRUE);
    // 		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // 	}
    // 	else
    // 	{
    //
	// 		if ($this->input->post())
    // 		{
    //                   echo"1........";
    //
	// 			$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
    // 			$this->form_validation->set_rules('sub_category_name', 'Category Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
    //
  //           if(!empty($_FILES['sub_category_logo']['name'])){
    //               echo"2........";
    // 			$this->form_validation->set_rules('sub_category_logo', 'Logo Image',  'callback_image');
    //         } else{
    //               echo"3........";
    //         	$this->form_validation->set_rules('sub_category_logo', 'Logo Image',  'required');
    //         }
    // 			if($this->form_validation->run() == False)
    // 			{
    //               echo"4........";
    // 				$view_data['page'] = 'subcategory_list';
    // 				$view_data['category_data'] = $this->Category_Model->get_category_data($id);
    // 				$data['page_data'] = $this->load->view('category/edit_category',$view_data, TRUE);
    // 				echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // 			}
    // 			else
    // 			{
    //                   echo"5........";
    // 				$id = $this->input->post('id');
    //
	// 				$update_data = $this->Category_Model->update_category($id);
    // 				if ($update_data == true)
    // 				{
    //                       echo"6........";
    // 					$this->session->set_flashdata('success_message', 'Category has been Updated succssfully');
    // 				}
    // 				else
    // 				{
    //                       echo"7........";
    // 					$this->session->set_flashdata('error_message', 'Category not Updated');
    // 				}
    // 				// $view_data['page'] = 'subcategory_list';
    // 				// $data['page_data'] = $this->load->view('category/subcategory_list', $view_data, TRUE);
    // 				// echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // 				redirect(AUTH_PANEL_URL . 'category/categories/');
    // 			}
    // 		}
    // 	}
    // }
//==============================================================================================//
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
        // print_r($id);

        $data = [];
        if ($id != null) {

            $this->db->where('sub_category_id', $id);
            $data['data'] = $this->db->get('sub_category_list')->row_array();
            // print_r($data);die;
        }
        if ($this->input->post()) {
            if ($this->input->post('id')) {
                $input = $this->input->post();

                // print_r($_FILES['sub_category_logo']);die;//echo pathinfo($_FILES['uploadimage']['name'],PATHINFO_EXTENSION); die;
                if ($_FILES['sub_category_logo']) {

                    // print_r(($_FILES['sub_category_logo']));die;

                    $new_name = time() . "." . pathinfo($_FILES['sub_category_logo']['name'], PATHINFO_EXTENSION);
                    // print_r($new_name);die;
                    $input['sub_category_logo'] = $new_name;

                    //print_r($input); die();
                    $config['upload_path'] = './images/sub_category/';
                    $config['allowed_types'] = 'jpg|png|jpeg';
                    $config['max_size'] = '1048576';
                    $config['file_name']=$new_name;
                    $config['remove_spaces'] = true;
                    $config['overwrite'] = false;
                    $config['max_width'] = '';
                    $config['max_height'] = '';
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);             
                    #encrypt name of the uploaded fil

//                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('sub_category_logo')) {
                        $uploadedDetails = $this->upload->display_errors();
                        //pre($config);
                        //print_r($uploadedDetails);
                        //die('path');
                        $this->session->set_flashdata('upload_error', 'Image exceeds maximum allowed size!!');
                        $data['page_data'] = $this->load->view('category/edit_category', $data, TRUE);
                        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
                    } else {
                        $uploadedDetails = $this->upload->data();
                    }
                }
                //pre($input);
                $file_input=array(
                                  'sub_category_name'=>$input['sub_category_name'],
                                  'sub_category_logo'=>$input['sub_category_logo']);
                $this->db->where('sub_category_id', $id);
                $insert = $this->db->update('sub_category_list', $file_input);
                $cat_id = $this->db->get_where('sub_category_list',array('sub_category_id'=>$id))->row_array();
                // print_r($cat_id);die;
                // $this->categories($id);
                redirect(AUTH_PANEL_URL . 'category/categories/'.$cat_id['category_id']);
            }

        }
        $data['page_data'] = $this->load->view('category/edit_category', $data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
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
//=================================== DELETE CATEGORY ==========================================//

    public function delete_category($cat_id, $sub_cat_id) {
        $delete_category = $this->Category_Model->delete_category($cat_id, $sub_cat_id);
        if ($delete_category == true) {
            $this->session->set_flashdata('success_message', 'SubCategory has been Deleted succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'SubCategory not Deleted');
        }
        redirect(AUTH_PANEL_URL . 'category/subcategory_list');
    }

//==============================================================================================//
//=================================== BLOCK CATEGORY ==========================================//

    public function block_category($id, $status) {
        echo $status."..";die;
        $block_category = $this->Category_Model->block_category($id, $status);
        die;
        if ($block_category == true) {
            echo "ok";die;
            
            $this->session->set_flashdata('success_message', 'Category has been Blocked succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'Category not Blocked');
        }
        echo "out";die;
        redirect(AUTH_PANEL_URL . 'category/subcategory_list');
    }

//==============================================================================================//
//==============================================================================================//
    public function categories($id) {
      //  echo $id; die;
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
        $view_data['title'] = $title;
        $view_data['page'] = 'subcategory_list';
        $data['page_title'] = "SubCategory List";

        $data['page_data'] = $this->load->view('category/subcategory_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//==============================================================================================//
    
//=================================== AJAX FOR CATEGORY LIST ===================================//

    public function ajax_subcategory_list($id) {

        $requestData = $_REQUEST;

        $columns = array(
            0 => 'sub_category_id',
            1 => 'sub_sub_category_name',
            3 => 'sub_category_creation_time',
            4 => 'sub_category_update_time',
        );
        $query = "SELECT count(category_id) as total FROM sub_category_list where category_id =" . $id;

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "select * from sub_category_list where category_id=" . $id;


        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND sub_category_id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " having sub_sub_category_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " having category_creation_time LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {
            $sql .= " having category_update_time LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }



        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        $id = 1;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $id++;
            $nestedData[] = $r->sub_category_name;

            $nestedData[] = "<img src='" . base_url() . 'images/sub_category/' . $r->sub_category_logo . "' width='60px' height='60px'/>";

            $nestedData[] = $r->sub_category_creation_time;
            $nestedData[] = $r->sub_category_update_time;
          $nestedData[] = ($r->sub_category_status == 0 ? "<a title='Enable' class='btn btn-success btn-xs fa fa-check' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->sub_category_id . "/1'> Enable </a>&nbsp;" : "<a title='Disable' class='btn btn-danger btn-xs fa fa-times' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->sub_category_id . "/0'> Disable </a>&nbsp;");
          //  $nestedData[] = $r->sub_category_status;


            //if($r->sub_category_status == '0'){
            $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "category/edit_category/" . $r->sub_category_id . "'>Edit</a>&nbsp;"
                    . "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "category/delete_category/" . $r->category_id . "/" . $r->sub_category_id . "'>Delete</a>&nbsp;";
//				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "category/block_category/" . $r->sub_category_id . "/1'>Block</a>";
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
                    $data['page_toast'] = 'Subcategory created successfully.';
                    $data['page_toast_type'] = 'success';
                    $data['page_toast_title'] = 'Action performed.';
                } else {
                    $data['page_toast'] = 'Subcategory can not be created.';
                    $data['page_toast_type'] = 'error';
                    $data['page_toast_title'] = 'Action performed.';
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
}
