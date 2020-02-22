<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Level extends MX_Controller {

	function __construct() 
	{
		parent::__construct();
        $this->form_validation->run($this);
        $this->load->model('Level_Model');
		$this->load->helper('aul');
		modules::run('auth_panel/auth_panel_ini/auth_ini');
	}



//=================================== BY DEFAULT FUNCTION =======================================//

	public function index($id='') 
	{
		$user_data = $this->session->userdata('active_user_data');
		$view_data['page'] = 'dashboard';
		$data['page_data']  = $this->load->view('admin/WELCOME_PAGE_SUPER_USER' ,$view_data, TRUE);
		$data['page_title'] = "welcome page";
		echo modules::run('auth_panel/template/call_default_template', $data);
	}

//===============================================================================================//   



//====================================== CREATE LEVEL =============================================//

	public function create_level() 
	{
		if($this->input->post()) 
		{
			$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
			$this->form_validation->set_rules('syllabus_name', 'Syllabus Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			$this->form_validation->set_rules('category_name', 'Category Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_validateCategory');
			if (!empty($_FILES['level_image']['name'])){
				$this->form_validation->set_rules('level_image', 'Level Image',  'callback_image');
	        } else{
	        	$this->form_validation->set_rules('level_image', 'Level Image',  'required');
	        }
	        
			if ($this->form_validation->run() != FALSE) 
			{
				$insetData = $this->Level_Model->create_level();
				if ($insetData == true) 
				{
					$data['page_toast'] = 'Level created successfully.';
					$data['page_toast_type'] = 'success';
					$data['page_toast_title'] = 'Action performed.';
				} 
				else 
				{
					$data['page_toast'] = 'Level can not be created.';
					$data['page_toast_type'] = 'error';
					$data['page_toast_title'] = 'Action performed.';
				}
			} 
		}
		$view_data = array();
		$view_data['page'] = 'create_level';
		$view_data['get_category'] = $this->db->get_where('category',array('status'=>'0'))->result_array();	
		$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();
		$data['page_data'] = $this->load->view('level/create_level', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//===============================================================================================//   



//================================= CALLBACK FOR CATEGORY ========================================//

	public function validateCategory($val)
	{
        if($val != '')
        {
            $syllabus_id = $this->input->post('syllabus_name');
            $category = explode("-", $this->input->post('category_name'));
            $category_id = $category[0];
            $categoryOld = $this->db->get_where('level', array('syllabus_id'=>$syllabus_id, 'category_id'=>$category_id, 'status'=>'0'));
            $countcategory = $categoryOld->num_rows();
			if($countcategory == 0)
            {
                return true;
            }
          	else
          	{
                $this->form_validation->set_message('validateCategory', 'This category is already Added.!');
                return false;
         	}
        }
        else
        {
          	$this->form_validation->set_message('validateCategory', 'Select Category.!');
          	return false;
        }	
    }

//==============================================================================================//  



//=============================== CALLBACK FOR IMAGE ===========================================//

	public function image()
	{
		$filetype = $_FILES['level_image']['type'];
        $allowed_ext = array('image/jpg','image/jpeg','image/png');
        if($_FILES['level_image']['size']>"1048576")
        {
            $this->form_validation->set_message('image', 'Image size is gretaer than 1MB. ');        
       		return false;
        }
        elseif(!in_array($filetype,$allowed_ext))
        {
            $this->form_validation->set_message('image', 'Only JPEG, JPG and PNG files are Allowed ');       
        	return false;
        }
        else
        {
            return true;    
        }                           
    }

//===============================================================================================//   



//================================ LEVEL LIST ===================================================//

	public function level_list() 
	{
		$view_data['page'] = 'level_list';
		$data['page_title'] = "Level List";
		$data['page_data'] = $this->load->view('level/level_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//===============================================================================================//   



//================================ AJAX FOR LEVEL LIST ==========================================//

    public function ajax_level_list() 
    {
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'syllabus_id',
			1 => 'level_name',
			2 => 'level_image',
			3 => 'status'
		);

		$query = "SELECT count(id) as total FROM level where status != 2";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
		$sql = "SELECT * , case status when '0' then 'Active' when '1' then 'Blocked' end as status FROM level where status != 2 ";
		$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
		$syllabusName = array_column($syllabusData, 'id', 'syllabus_name');
		 // print_r($syllabusName);exit;
		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{   
			$name = ucwords($requestData['columns'][0]['search']['value']);
			// echo $syllabusName[$name];exit;
			$sql.=" AND syllabus_id LIKE '" . $syllabusName[$name] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) 
		{   
			$sql.=" AND level_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][2]['search']['value'])) 
		{   
			$sql.=" AND level_image LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][3]['search']['value'])) 
		{  
			$sql.=" having status LIKE '" . $requestData['columns'][3]['search']['value'] . "%'";
		}

		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query); 
		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
		$result = $this->db->query($sql)->result();
		$data = array();
		$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
		$syllabusName = array_column($syllabusData, 'syllabus_name', 'id');
		$imageurl = base_url('web_assets/images/level_images/');

		foreach ($result as $r) 
		{  
			$nestedData = array();
			if(empty($r->level_image))
			{
				$levelimg = 'black-male-user-symbol.png';
			}else
			{
				$levelimg = $r->level_image;
			}
			$levelimage = "<img src='".$imageurl.$levelimg."' width='100px height='100px'>";
			$nestedData[] = $syllabusName[$r->syllabus_id];
			$nestedData[] = $r->level_name;
			$nestedData[] = $levelimage;
			$nestedData[] = $r->status;
			if ($r->status == 'Active') 
			{
				$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "level/edit_level/" . $r->id . "'>Edit</a>&nbsp;"
				. "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "level/delete_level/" . $r->id . "'>Delete</a>&nbsp;".
				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "level/block_level/" . $r->id . "/1'>Block</a>";
			} 
			else 
			{
				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "level/block_level/" . $r->id . "/0'>Unblock</a>";
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

//===============================================================================================//   



//================================= EDIT LEVEL ==================================================//

	public function edit_level($id = null) 
	{
		if (!$this->input->post()) 
		{
			$view_data['page'] = '';
			$view_data['level_data'] = $this->Level_Model->get_level_data($id);
			$view_data['get_category'] = $this->db->get_where('category',array('status'=>'0'))->result_array();	
			$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();			
			$data['page_data'] = $this->load->view('level/edit_level',$view_data, TRUE);
			echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
		} 
		else 
		{
			
			if ($this->input->post()) 
			{
				
				$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
				$this->form_validation->set_rules('syllabus_name', 'Syllabus Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
				$this->form_validation->set_rules('category_name', 'Category Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_validateCategoryEdit');
				
				if ($this->form_validation->run() == False) 
				{
					$view_data['page'] = '';
					$view_data['level_data'] = $this->Level_Model->get_level_data($id);
					$view_data['get_category'] = $this->db->get_where('category',array('status'=>'0'))->result_array();	
					$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();			
					$data['page_data'] = $this->load->view('level/edit_level',$view_data, TRUE);
					echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
				} 
				else
				{
					$id = $this->input->post('id');
					$update_data = $this->Level_Model->update_level($id);
					if ($update_data == true) 
					{
						$this->session->set_flashdata('success_message', 'Level has been Updated succssfully');
					} 
					else 
					{
						$this->session->set_flashdata('error_message', 'Level not Updated');
					}
					redirect(AUTH_PANEL_URL . 'level/level_list');
				}
			}
		}
	}

//=============================================================================================//   



//============================== CALLBACK FOR CATEGORY EDIT ===================================//

	public function validateCategoryEdit($val)
	{
      	if($val != '')
        {
        	$id = $this->input->post('id');
            $syllabus_id = $this->input->post('syllabus_name');
            $category = explode("-", $this->input->post('category_name'));
            $category_id = $category[0];
            $categoryCheck =  $this->db->get_where('level', array('syllabus_id'=>$syllabus_id, 'status'=>'0','id'=>$id));
            $categoryOld = $this->db->get_where('level', array('syllabus_id'=>$syllabus_id, 'category_id'=>$category_id, 'status'=>'0'));
            $countcategory = $categoryOld->num_rows();
            if($categoryCheck->row('category_id') == $category_id || $countcategory == 0)
            {
           		return true;
            }
            else
            {
            	$this->form_validation->set_message('validateCategoryEdit', 'This category is already Added.!');
            	return false;
          	}
        }
        else
        {
          $this->form_validation->set_message('validateCategoryEdit', 'Select Category.!');
          return false;
        }
    }

//==============================================================================================//   



//=============================== DELETE LEVEL =================================================//

	public function delete_level($id) 
	{
		$delete_level = $this->Level_Model->delete_level($id);
		if ($delete_level == true) 
		{
			$this->session->set_flashdata('success_message', 'Level has been Deleted succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Level not Deleted');
		}
		redirect(AUTH_PANEL_URL . 'level/level_list');
	}

//==============================================================================================//   



//================================ BLOCK LEVEL ==================================================//

	public function block_level($id, $status) 
	{
		$block_level = $this->Level_Model->block_level($id, $status);
		if ($block_level == true) 
		{
			$this->session->set_flashdata('success_message', 'Level has been Blocked succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Level not Blocked');
		}
		redirect(AUTH_PANEL_URL . 'level/level_list');
	}
	
//===============================================================================================//   

}
