<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends MX_Controller {

	function __construct() 
	{
		parent::__construct();
        $this->form_validation->run($this);
		$this->load->helper('aul');
		$this->load->model('Unit_Model');
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

//==================================================================================================//   


//=================================== CREATE UNIT =================================================//

	public function create_unit($level='', $sublevel='') 
	{
		if($this->input->post())
		{	
			$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
			$this->form_validation->set_rules('syllabus_name', 'Syllabus Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			$this->form_validation->set_rules('level_name', ' Level Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			$this->form_validation->set_rules('sublevel_name', 'Sub Level Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			$this->form_validation->set_rules('unit', 'Unit', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_checkUnit');
			if ($this->form_validation->run() != FALSE) {

				$insetData = $this->Unit_Model->create_unit();
				if ($insetData == true) 
				{
					$data['page_toast'] = 'Unit created successfully.';
					$data['page_toast_type'] = 'success';
					$data['page_toast_title'] = 'Action performed.';
					if($this->input->post('syllabus_name') != '')
	                {
	                    $id=$this->input->post('syllabus_name');
	                    $level = $this->db->get_where('level', array('syllabus_id'=>$id,'status'=>0))->result_array();
	                }
	                else{
	                    $level = array();
	                }
	                
	                if($this->input->post('level_name') != ''){
	                    $id=$this->input->post('level_name');
	                    $sublevel = $this->db->get_where('sub_level', array('level_id'=>$id,'status'=>0))->result_array();   
	                }
	                else{
	                    $sublevel = array();
	                }
				} 
				else 
				{
					$data['page_toast'] = 'Unit can not be created.';
					$data['page_toast_type'] = 'error';
					$data['page_toast_title'] = 'Action performed.';
				}
			}else{
				if($this->input->post('syllabus_name') != '')
                {
                    $id=$this->input->post('syllabus_name');
                    $level = $this->db->get_where('level', array('syllabus_id'=>$id,'status'=>0))->result_array();
                }
                else{
                    $level = array();
                }
                
                if($this->input->post('level_name') != ''){
                    $id=$this->input->post('level_name');
                    $sublevel = $this->db->get_where('sub_level', array('level_id'=>$id,'status'=>0))->result_array();   
                }
                else{
                    $sublevel = array();
                }
			}
			 
		}
		$view_data = array();
		$view_data['page'] = 'create_unit';
		$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();
		$view_data['level'] = $level;
		$view_data['sublevel'] = $sublevel;
		$data['page_data'] = $this->load->view('unit/create_unit', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//===============================================================================================//



//===============================Get level using AJAX============================================//

    public function get_level()
    {
        $syllabusId=$this->input->post('syllabus_name');
        if($syllabusId != '' && is_numeric($syllabusId)){
            $level = $this->db->get_where('level', array('syllabus_id'=>$syllabusId,'status'=>'0'))->result_array();
            
            $result='';
            foreach($level as $levels){
                $lid=$levels['id'];
                $result.='<option value="'.$lid.'">'.$levels['level_name'].'</option>';
            }
            $json=array('login'=>true,'data'=>$result);
        }
        else{
            $json=array('login'=>false,'message'=>'Your session is expired, please login once again.!');
        }    
        $output=json_encode($json);
        echo $output;
        die;
    }
//===============================================================================================//



//===========================Get Sub level using AJAX ===========================================//

    public function get_sublevel()
    {
        $levelId=$this->input->post('level_name');
        if($levelId != '' && is_numeric($levelId)){
           $sublevel = $this->db->get_where('sub_level', array('level_id'=>$levelId,'status'=>'0'))->result_array();
            
            $result='';
            foreach($sublevel as $sublevels){
                $lid=$sublevels['id'];
                $result.='<option value="'.$lid.'">'.$sublevels['sub_level'].'</option>';
            }
            $json=array('login'=>true,'data'=>$result);
        }
        else{
            $json=array('login'=>false,'message'=>'Your session is expired, please login once again.!');
        }    
        $output=json_encode($json);
        echo $output;
        die;
    }

//================================================================================================//



//====================================== CALLBACK FOR UNIT ===================================//

	public function checkUnit($val)
	{
        if($val != '')
        {
            $unit = $this->input->post('unit');
            $sublevelId = $this->input->post('sublevel_name');
            $unitOld = $this->db->get_where('units', array('unit_name'=>$unit, 'sub_level_id'=>$sublevelId, 'status'=>'0'));
            $countUnit = $unitOld->num_rows();
			if($countUnit == 0)
            {
                return true;
            }
          	else
          	{
                $this->form_validation->set_message('checkUnit', 'This Unit is already Added.!');
                return false;
         	}
        }
        else
        {
          	$this->form_validation->set_message('checkUnit', 'Enter Unit Name...!!!');
          	return false;
        }	
    }

//==============================================================================================//    




//=================================== UNIT LIST ===================================================//

	public function unit_list() 
	{
		$view_data['page'] = 'unit_list';
		$data['page_title'] = "Unit List";
		$data['page_data'] = $this->load->view('unit/unit_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//==================================================================================================//   



//====================================== AJAX FOR UNIT LIST =======================================//

    public function ajax_unit_list() 
    {
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'id',
			1 => 'syllabus_id',
			2 => 'level_id',
			3 => 'sub_level_id',
			4 => 'unit_name',
			5 => 'status'
		);

		$query = "SELECT count(id) as total FROM units where status != 2";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
		$sql = "SELECT * , case status when '0' then 'Active' when '1' then 'Blocked' end as status FROM units where status != 2 ";
		$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
		$syllabusName = array_column($syllabusData, 'id', 'syllabus_name');
		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{   
			$sql.=" AND syllabus_id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) 
		{   
			$sql.=" AND level_id LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][2]['search']['value'])) 
		{   
			$sql.=" AND sub_level_id LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][3]['search']['value'])) 
		{   
			$sql.=" AND unit_name LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][4]['search']['value'])) 
		{  
			$sql.=" having status LIKE '" . $requestData['columns'][4]['search']['value'] . "%'";
		}

		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query); 
		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
		$result = $this->db->query($sql)->result();
		$data = array();

		$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
		$syllabusName = array_column($syllabusData, 'syllabus_name', 'id');

		$levelData = $this->db->get_where('level', array('status'=>'0'))->result_array();
		$levelName = array_column($levelData, 'level_name', 'id');

		$sublevelData = $this->db->get_where('sub_level', array('status'=>'0'))->result_array();
		$sublevelName = array_column($sublevelData, 'sub_level', 'id');
		foreach ($result as $r) 
		{  
			$nestedData = array();
			$nestedData[] = $syllabusName[$r->syllabus_id];
			$nestedData[] = $levelName[$r->level_id];
			$nestedData[] = $sublevelName[$r->sub_level_id];
			$nestedData[] = $r->unit_name;
			$nestedData[] = $r->status;
			if ($r->status == 'Active') 
			{
				$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "unit/edit_unit/" . $r->id . "'>Edit</a>&nbsp;"
				. "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "unit/delete_unit/" . $r->id . "'>Delete</a>&nbsp;".
				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "unit/block_unit/" . $r->id . "/1'>Block</a>";
			} 
			else 
			{
				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "unit/block_unit/" . $r->id . "/0'>Unblock</a>";
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

//==============================================================================================//   
  


//================================= EDIT UNIT ==================================================//

	public function edit_unit($id = null) 
	{
		if (!$this->input->post()) 
		{
			$view_data['page'] = '';
			$view_data['unit_data'] = $this->Unit_Model->get_unit_data($id);

			$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
			$view_data['syllabusName'] = array_column($syllabusData, 'syllabus_name', 'id');

			$levelData = $this->db->get_where('level', array('status'=>'0'))->result_array();
			$view_data['levelName'] = array_column($levelData, 'level_name', 'id');

			$sublevelData = $this->db->get_where('sub_level', array('status'=>'0'))->result_array();
			$view_data['sublevelName'] = array_column($sublevelData, 'sub_level', 'id');

			$data['page_data'] = $this->load->view('unit/edit_unit',$view_data, TRUE);
			echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
		} 
		else 
		{
			if ($this->input->post()) 
			{
				$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
				$this->form_validation->set_rules('unit', 'Unit Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_validateUnitEdit');
				
				if ($this->form_validation->run() == False) 
				{
					$view_data['page'] = '';
					$view_data['unit_data'] = $this->Unit_Model->get_unit_data($id);

					$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
					$view_data['syllabusName'] = array_column($syllabusData, 'syllabus_name', 'id');

					$levelData = $this->db->get_where('level', array('status'=>'0'))->result_array();
					$view_data['levelName'] = array_column($levelData, 'level_name', 'id');

					$sublevelData = $this->db->get_where('sub_level', array('status'=>'0'))->result_array();
					$view_data['sublevelName'] = array_column($sublevelData, 'sub_level', 'id');		
					$data['page_data'] = $this->load->view('unit/edit_unit',$view_data, TRUE);
					echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
				} 
				else
				{
					$id = $this->input->post('unit_id');
					$update_data = $this->Unit_Model->update_unit($id);
					if ($update_data == true) 
					{
						$this->session->set_flashdata('success_message', 'Unit has been Updated succssfully');
					} 
					else 
					{
						$this->session->set_flashdata('error_message', 'Unit not Updated');
					}
					redirect(AUTH_PANEL_URL . 'unit/unit_list');
				}
			}
		}
	}

//=============================================================================================//   



//============================== CALLBACK FOR CATEGORY EDIT ===================================//

	public function validateUnitEdit($val)
	{
      	if($val != '')
        {
        	// all id's
            $id = $this->input->post('unit_id');
            $syllabus_id = $this->input->post('syllabus_name');
            $level_id = $this->input->post('level_name');
			$sub_level_id = $this->input->post('sublevel_name');
			//=======//
            $unitCheck =  $this->db->get_where('units', array('syllabus_id'=>$syllabus_id,  'level_id'=>$level_id, 'sub_level_id'=>$sub_level_id, 'unit_name'=>$val, 'id'=>$id, 'status'=>'0'));
            $unitOld = $this->db->get_where('units', array('syllabus_id'=>$syllabus_id, 'level_id'=>$level_id, 'sub_level_id'=>$sub_level_id, 'unit_name'=>$val, 'status'=>'0'));
            $countunit = $unitOld->num_rows();
            if($unitCheck->row('unit_name') == $val || $countunit == 0)
            {
           		return true;
            }
            else
            {
            	$this->form_validation->set_message('validateUnitEdit', 'This Unit is already Added.!');
            	return false;
          	}
        }
        else
        {
          $this->form_validation->set_message('validateUnitEdit', 'Enter Unit Name...!!!');
          return false;
        }
    }

//==============================================================================================//   



//=============================== DELETE LEVEL =================================================//

	public function delete_unit($id) 
	{
		$delete_unit = $this->Unit_Model->delete_unit($id);
		if ($delete_unit == true) 
		{
			$this->session->set_flashdata('success_message', 'Unit has been Deleted succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Unit not Deleted');
		}
		redirect(AUTH_PANEL_URL . 'unit/unit_list');
	}

//==============================================================================================//   



//================================ BLOCK LEVEL ==================================================//

	public function block_unit($id, $status) 
	{
		$block_unit = $this->Unit_Model->block_unit($id, $status);
		if ($block_unit == true) 
		{
			$this->session->set_flashdata('success_message', 'Unit has been Blocked succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Unit not Blocked');
		}
		redirect(AUTH_PANEL_URL . 'unit/unit_list');
	}
	
//===============================================================================================//  

}
