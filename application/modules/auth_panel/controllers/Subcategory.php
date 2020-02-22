<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategory extends MX_Controller {

	function __construct() 
	{
		parent::__construct();
		$this->form_validation->run($this);
		$this->load->helper('aul');
		$this->load->model('Subcategory_Model');
		modules::run('auth_panel/auth_panel_ini/auth_ini');
	}


//=================================== BY DEFAULT FUNCTION  =======================================//

	public function index($id='') 
	{
		$user_data = $this->session->userdata('active_user_data');
		$view_data['page'] = 'dashboard';
		$data['page_data']  = $this->load->view('admin/WELCOME_PAGE_SUPER_USER' ,$view_data, TRUE);
		$data['page_title'] = "welcome page";
		echo modules::run('auth_panel/template/call_default_template', $data);
	}

//===============================================================================================//   



//====================================== CREATE Subcategory =========================================//

	public function create_subcategory() 
	{
		if($this->input->post())
		{
			$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
			$this->form_validation->set_rules('sub_category_name', 'Subcategory Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_subcategoryCheckCreate');
			if(!empty($_FILES['sub_category_logo']['name'])){
				$this->form_validation->set_rules('sub_category_logo', 'Subcategory Logo',  'callback_image');
	        } else{
	        	$this->form_validation->set_rules('sub_category_logo', 'Subcategory Logo',  'required');
	        }
			if($this->form_validation->run() != FALSE) 
			{
				$insetData = $this->Subcategory_Model->create_subcategory();
				if ($insetData == true) 
				{
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
		$view_data['page'] = 'create_subcategory';
		$data['page_data'] = $this->load->view('subcategory/create_subcategory', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//==============================================================================================//   



//=================================== CALLBACK FOR IMAGE =========================================//

	public function image()
	{
        $filetype = $_FILES['sub_category_logo']['type'];
        $allowed_ext = array('image/jpg','image/jpeg','image/png');
        if($_FILES['sub_category_logo']['size']>"1048576")
        {
            $this->form_validation->set_message('image', 'Image size is gretaer than 1MB. ');        
       		return false;
        }
        elseif(!in_array($filetype,$allowed_ext))
        {
            $this->form_validation->set_message('image', 'Only JPEG ,JPG and PNG files are Allowed ');       
        	return false;
        }
        else
        {
            return true;    
        }                           
    }

//============================================================================================//   



//========================== CALLBACK FOR Subcategory CREATE ====================================//

    public function subcategoryCheckCreate($val)
	{
        if($val != '')
        {
            	
          	$subcategoryCheck = $this->db->get_where('sub_category_list', array('sub_category_name'=>$val, 'sub_category_status'=>'0'));
           	$countsubcategory = $subcategoryCheck->num_rows();
            if($countsubcategory == 0)
            {
               return true;
            }
            else
            {
               $this->form_validation->set_message('subcategoryCheckCreate', 'This Subcategory is already Added.!');
               return false;
            }
        }
        else
        {
          $this->form_validation->set_message('subcategoryCheckCreate', 'Enter Subcategory.!');
          return false;
        }
    }

//==============================================================================================//    



//=================================== Subcategory LIST ===============================================//

	public function subcategory_list() 
	{
        
		$view_data['page'] = 'subcategory_list';
		$data['page_title'] = "Subcategory List";
		$data['page_data'] = $this->load->view('subcategory/subcategory_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//==============================================================================================//   



//================================ AJAX FOR Subcategory LIST=======================================//

    public function ajax_subcategory_list() 
    {
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'sub_category_id',
			1 => 'sub_category_name',
            3 => 'sub_category_creation_time',
            4 => 'sub_category_update_time'
			
		);
		$query = "SELECT count(sub_category_id) as total FROM sub_category_list";
      
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
        
		$sql = "SELECT * FROM sub_category_list where 1";
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{
			$sql.=" AND sub_category_id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value']))
		{ 
			$sql.=" AND sub_category_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
        if (!empty($requestData['columns'][3]['search']['value']))
		{ 
			$sql.=" AND sub_category_creation_time LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}
        if (!empty($requestData['columns'][4]['search']['value']))
		{ 
			$sql.=" AND sub_category_update_time LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
		}
		
		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query);
		$sql.=" ORDER BY " .$columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  
		$result = $this->db->query($sql)->result();
		$imageurl = base_url('images/sub_category/');
		$data = array();
		foreach ($result as $r) 
		{  
			$nestedData = array();
			if(empty($r->sub_category_logo))
			{
				$subcategorylogo = 'black-male-user-symbol.png';
			}
			else
			{
				$subcategorylogo = $r->sub_category_logo;
			}
			$subcategoryimage = "<img src='".$imageurl.$subcategorylogo."' width='100px height='100px'>";

			$nestedData[] = $r->sub_category_id ;   
			$nestedData[] = $r->sub_category_name;    
			$nestedData[] = $subcategoryimage;
			$nestedData[] = $r->sub_category_creation_time;
			$nestedData[] = $r->sub_category_update_time;
			$nestedData[] = $r->sub_category_status;
            
			if($r->sub_category_status == '0'){
			$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "Subcategory/edit_subcategory/" . $r->sub_category_id . "'>Edit</a>&nbsp;"
				. "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "Subcategory/delete_subcategory/" . $r->sub_category_id . "'>Delete</a>&nbsp;".
				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "Subcategory/block_subcategory/" . $r->sub_category_id . "/1'>Block</a>";
			}
			else 
			{
				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "Subcategory/block_subcategory/" . $r->sub_category_id . "/0'>Unblock</a>";
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

//============================================================================================//   



//=================================== EDIT Subcategory ==========================================//     

	public function edit_subcategory($id = null) 
	{
     
		if (!$this->input->post()) 
		{

			$view_data['page'] = '';
			$view_data['subcategory_data'] = $this->Subcategory_Model->get_subcategory_data($id);
			$data['page_data'] = $this->load->view('subcategory/edit_subcategory',$view_data, TRUE);
			echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
		} 
		else 
		{
			if($this->input->post()) 
			{	
				$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
				$this->form_validation->set_rules('sub_category_name', 'Subcategory Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_subcategoryCheck');
				if (!empty($_FILES['sub_category_logo']['name']))
				{
	            	$this->form_validation->set_rules('sub_category_logo', 'Subcategory Image',  'callback_image');
	        	}
				if($this->form_validation->run() == False) 
				{
					$view_data['page'] = '';
					$view_data['subcategory_data'] = $this->Subcategory_Model->get_subcategory_data($id);
					$data['page_data'] = $this->load->view('subcategory/edit_subcategory',$view_data, TRUE);
					echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
				} 
				else 
				{
					$id = $this->input->post('id');
					$update_data = $this->Subcategory_Model->update_subcategory($id);
					if ($update_data == true) 
					{
						$this->session->set_flashdata('success_message', 'User has been Updated succssfully');
					}
					else 
					{
						$this->session->set_flashdata('error_message', 'User not Updated');
					}
					redirect(AUTH_PANEL_URL . 'Subcategory/subcategory_list');
				}
			}
		}
	}

//==================================================================================================//   


//================================= CALLBACK FOR Subcategory EDIT==================================//

    public function subcategoryCheck($val)
	{
      	if($val != '')
        {
            $flag=0;
        	
            $subcategoryOld = $this->db->get_where('sub_category_list', array('sub_category_status'=>'0'))->result_array();
            
//            print_r($subcategoryOld);die;
            foreach($subcategoryOld as $d)
            {
                
                if($d['sub_category_name']==$val)
                { 
                    $flag=1;
                }
               
            }
          	//$subcategoryCheck = $this->db->get_where('sub_category_list', array('sub_category_name'=>$val, 'sub_category_status'=>'0'));
    	    //$countsubcategory = $subcategoryCheck->num_rows();
                //echo $subcategoryOld->row('sub_category_name');die;
	        if($flag==1)
	        {
	          	return true;
	        }
	        else 
	        {
	          	$this->form_validation->set_message('subcategoryCheck', 'This Subcategory is already Added.!');
	           	return false;
	        }
        }
        else
        {
          $this->form_validation->set_message('subcategoryCheck', 'Enter Subcategory.!');
          return false;
        }
    }


//===============================================================================================// 



//=================================== DELETE Subcategory =============================================//

	public function delete_subcategory($id) 
	{
		$delete_subcategory = $this->Subcategory_Model->delete_subcategory($id);
		if($delete_subcategory == true) 
		{
			$this->session->set_flashdata('success_message', 'Subcategory has been Deleted succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Subcategory not Deleted');
		}
		redirect(AUTH_PANEL_URL . 'Subcategory/subcategory_list');
	}

//===============================================================================================//  



//======================================= BLOCK Subcategory ===========================================//

	public function block_subcategory($id, $status) 
	{
		$delete_user = $this->Subcategory_Model->block_subcategory($id, $status);
		if($update_data == true) 
		{
			$this->session->set_flashdata('success_message', 'Subcategory has been Blocked succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Subcategory not Blocked');
		}
		redirect(AUTH_PANEL_URL . 'Subcategory/subcategory_list');
	}

//===============================================================================================//   

}
