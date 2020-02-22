<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slides extends MX_Controller {

	function __construct() 
	{
		parent::__construct();
        $this->form_validation->run($this);
		$this->load->helper('aul');
		$this->load->model('Slides_Model');
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


//=================================== CREATE SLIDES =================================================//

	public function create_slides($level='', $sublevel='', $unit=''){
		if($this->input->post()){

		$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
		$this->form_validation->set_rules('Answer', 'Answer', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			if ($this->form_validation->run() != FALSE) {

			    $insetData = $this->Slides_Model->create_slides();
				if ($insetData == true) {
					$data['page_toast'] = 'Unit created successfully.';
					$data['page_toast_type'] = 'success';
					$data['page_toast_title'] = 'Action performed.';
					if($this->input->post('syllabus_name') != ''){
	                    $id=$this->input->post('syllabus_name');
	                    $level = $this->db->get_where('level', array('syllabus_id'=>$id,'status'=>0))->result_array();
	                }else{
	                    $level = array();
	                }//
	                // level
	                if($this->input->post('level_name') != ''){
	                    $id=$this->input->post('level_name');
	                    $sublevel = $this->db->get_where('sub_level', array('level_id'=>$id,'status'=>0))->result_array();   
	                }else{
	                    $sublevel = array();
	                }//
	                // for unit
	                if($this->input->post('sublevel_name') != ''){
	                    $id=$this->input->post('sublevel_name');
	                    $unit = $this->db->get_where('units', array('sub_level_id'=>$id,'status'=>0))->result_array();   
	                }else{
	                    $unit = array();
	                }//
				} else {
					$data['page_toast'] = 'Unit can not be created.';
					$data['page_toast_type'] = 'error';
					$data['page_toast_title'] = 'Action performed.';
				}
			}else{
				// for syllabus
				if($this->input->post('syllabus_name') != ''){
                    $id=$this->input->post('syllabus_name');
                    $level = $this->db->get_where('level', array('syllabus_id'=>$id,'status'=>0))->result_array();
                } else{
                    $level = array();
                }
                //
                // for level
                if($this->input->post('level_name') != ''){
                    $id=$this->input->post('level_name');
                    $sublevel = $this->db->get_where('sub_level', array('level_id'=>$id,'status'=>0))->result_array();   
                }else{
                    $sublevel = array();
                }
                //
                // for unit
                if($this->input->post('sublevel_name') != ''){
                    $id=$this->input->post('sublevel_name');
                    $unit = $this->db->get_where('units', array('sub_level_id'=>$id,'status'=>0))->result_array();   
                    
                }else{
                    $unit = array();
                    
                }
                //
			}
			 
		}
		$view_data = array();
		$view_data['page'] = 'create_slides';
		$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();
		$view_data['level'] = $level;
		$view_data['sublevel'] = $sublevel;
		$view_data['unit'] = $unit;
		$data['page_data'] = $this->load->view('slides/create_slides', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//===============================================================================================//



//===========================Get Unit Name using AJAX ===========================================//

    public function get_unit()
    {
        $sublevelId=$this->input->post('sublevel_name');
        if($sublevelId != '' && is_numeric($sublevelId)){
           $unit = $this->db->get_where('units', array('sub_level_id'=>$sublevelId,'status'=>0))->result_array();
            $i=1;
            $result='';
            foreach($unit as $units){
                $uid=$units['id'];
                $result.='<option value="'.$uid.'">'."Unit-".$i."-".$units['unit_name'].'</option>';
                $i++;
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



//=================================== SLIDES LIST ===================================================//

	public function slides_list() 
	{
		$view_data['page'] = 'slides_list';
		$data['page_title'] = "Slide List";
		$data['page_data'] = $this->load->view('slides/slides_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//==================================================================================================//   



//====================================== AJAX FOR UNIT LIST =======================================//

    public function ajax_slides_list() 
    {
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'id',
			1 => 'syllabus_id',
			2 => 'level_id',
			3 => 'sub_level_id',
			4 => 'unit_id',
			7 => 'question_text',
			17 => 'answer_text',
			18 => 'status'
		);

		$query = "SELECT count(id) as total FROM slides where status != 2";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
		$sql = "SELECT * , case status when '0' then 'Active' when '1' then 'Blocked' end as status FROM slides where status != 2 ";
			 
		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{   
			$syllabusData = $this->db->select('id')->from('syllabus')->where("syllabus_name LIKE '".$requestData['columns'][0]['search']['value']."%'")->where("status", 0)->get()->row('id');
			$sql.=" AND syllabus_id = '" .$syllabusData. "' ";
		}
		// if (!empty($requestData['columns'][1]['search']['value'])) 
		// {   
		// 	$levelData = $this->db->select('id')->from('level')->where("level_name LIKE '".$requestData['columns'][1]['search']['value']."%'")->where("status", 0)->get()->row('id');
		// 	$sql.=" AND level_id = '9' ";
		// }
		// if (!empty($requestData['columns'][2]['search']['value'])) 
		// {   
		// 	$sublevelData = $this->db->select('id')->from('sub_level')->where("sub_level LIKE '".$requestData['columns'][2]['search']['value']."%'")->where("status", 0)->get()->result_array();
		// 	foreach($sublevelData as $data){
		// 		$sql.=" AND sub_level_id = '" .$data['id']. "' ";
		// 	}
		// }
		// if (!empty($requestData['columns'][3]['search']['value'])) 
		// {   
		// 	$unitData = $this->db->select('id')->from('units')->where("unit_name LIKE '".$requestData['columns'][0]['search']['value']."%'")->where("status", 0)->get()->result_array();;
		// 	foreach($unitData as $data){
		// 		$sql.=" AND unit_id = '" .$data['id']. "' OR ";
		// 	}
		// 	$sql.=rtrim($sql,"OR");
		// }
		if (!empty($requestData['columns'][4]['search']['value'])) 
		{  
			$sql.=" having question_text LIKE '" . $requestData['columns'][4]['search']['value'] . "%'";
		}
		if (!empty($requestData['columns'][5]['search']['value'])) 
		{  
			$sql.=" having answer_text LIKE '" . $requestData['columns'][5]['search']['value'] . "%'";
		}
		if (!empty($requestData['columns'][6]['search']['value'])) 
		{  
			$sql.=" having status LIKE '" . $requestData['columns'][6]['search']['value'] . "%'";
		}
		 // echo $sql;exit;
		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query); 
		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  //adding length
		$result = $this->db->query($sql)->result();
		// print_r($result);exit;
		$data = array();

		$syllabusData = $this->db->get_where('syllabus', array('status'=>'0'))->result_array();
		$syllabusName = array_column($syllabusData, 'syllabus_name', 'id');

		$levelData = $this->db->get_where('level', array('status'=>'0'))->result_array();
		$levelName = array_column($levelData, 'level_name', 'id');

		$sublevelData = $this->db->get_where('sub_level', array('status'=>'0'))->result_array();
		$sublevelName = array_column($sublevelData, 'sub_level', 'id');

		$unitData = $this->db->get_where('units', array('status'=>'0'))->result_array();
		$unitName = array_column($unitData, 'unit_name', 'id');
		
		foreach ($result as $r) 
		{  
			$nestedData = array();
			$nestedData[] = $syllabusName[$r->syllabus_id];
			$nestedData[] = $levelName[$r->level_id];
			$nestedData[] = $sublevelName[$r->sub_level_id];
			$nestedData[] = $unitName[$r->unit_id];
			if($r->question_type == 1){
				$questions = $r->question_text."<br><br><img src='".base_url('uploads/questionsImages/'.$r->question_image)."' width='100px' height='70px' /><br>";
			}else{
				$questions = $r->question_text;
			}
			$nestedData[] = $questions;
			$nestedData[] = $r->answer_text;
			$nestedData[] = $r->status;
			if ($r->status == 'Active'){
				$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "unit/edit_unit/" . $r->id . "'>Edit</a>&nbsp;"
				. "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "unit/delete_unit/" . $r->id . "'>Delete</a>&nbsp;".
				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "unit/block_unit/" . $r->id . "/1'>Block</a>";
			} else{
				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "unit/block_unit/" . $r->id . "/0'>Unblock</a>";
			}
			$nestedData[] = $action;
			$data[] = $nestedData;
		}
		// echo "<pre>";
		// print_r($data);exit;
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
