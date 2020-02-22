<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sublevel extends MX_Controller {

	function __construct() 
	{
		parent::__construct();
        $this->form_validation->run($this);
         $this->load->model('Sublevel_Model');
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

//==================================================================================================//   


//=================================== CREATE LEVEL =================================================//

	public function create_sublevel($level='', $sublevel='') 
	{
		if($this->input->post())
		{
			$this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
			$this->form_validation->set_rules('syllabus_name', 'Syllabus Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			$this->form_validation->set_rules('level_name', ' Level Name', 'required|trim|htmlspecialchars|strip_tags|stripslashes|callback_validateLevel');
			$this->form_validation->set_rules('sublevel', 'Sub Level', 'required|trim|htmlspecialchars|strip_tags|stripslashes');
			
			if ($this->form_validation->run() != FALSE) 
			{
				$insetData = $this->Sublevel_Model->create_sublevel();
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
			 else {
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
                    $sublevels = $this->db->get_where('level', array('id'=>$id,'status'=>0))->result_array();
                     if($sublevels[0]['level_name'] == 'Elementary'){
			           	$sublevel = 'Level 1-3';
			           }else if($sublevels[0]['level_name'] == 'Basic'){
			           	$sublevel = 'Level 4-12';
			           }else if($sublevels[0]['level_name'] == 'Intermediate'){
			           	$sublevel = 'Level 13-21';
			           }else if($sublevels[0]['level_name'] == 'Advanced'){
			           	$sublevel = 'Level 22-25';
			           }
                }
                else{
                    $sublevel = "";
                }
               	$view_data = array();
				$view_data['page'] = 'create_sublevel';
				$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();
				$view_data['level'] = $level;
				$view_data['sublevel'] = $sublevel;
				$data['page_data'] = $this->load->view('sublevel/create_sublevel', $view_data, TRUE);
				echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
			} 
		}
		$view_data = array();
		$view_data['page'] = 'create_sublevel';
		$view_data['get_syllabus'] = $this->db->get_where('syllabus',array('status'=>'0'))->result_array();
		$view_data['level'] = $level;
		$view_data['sublevel'] = $sublevel;
		$data['page_data'] = $this->load->view('sublevel/create_sublevel', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//===============================================================================================//



//===============================Get level using AJAX============================================//

    public function get_level()
    {
        $syllabusId=$this->input->post('syllabus_name');
        if($syllabusId != '' && is_numeric($syllabusId)){
            $level = $this->db->get_where('level', array('syllabus_id'=>$syllabusId))->result_array();
            
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
            $level = $this->db->get_where('level', array('id'=>$levelId))->result_array();
            $result='';
           if($level[0]['level_name'] == 'Elementary'){
           	$result = 'Level 1-3';
           }else if($level[0]['level_name'] == 'Basic'){
           	$result = 'Level 4-12';
           }else if($level[0]['level_name'] == 'Intermediate'){
           	$result = 'Level 13-21';
           }else if($level[0]['level_name'] == 'Advanced'){
           	$result = 'Level 22-25';
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



//====================================== CALLBACK FOR CATEGORY ===================================//

	public function validateLevel($val)
	{
        if($val != '')
        {
            $syllabus_id = $this->input->post('syllabus_name');
            $level_id = $this->input->post('level_name');
            $categoryOld = $this->db->get_where('sub_level', array('syllabus_id'=>$syllabus_id, 'level_id'=>$level_id, 'status'=>'0'));
            $countcategory = $categoryOld->num_rows();
			if($countcategory == 0)
            {
                return true;
            }
          	else
          	{
                $this->form_validation->set_message('validateLevel', 'This Level is already Added.!');
                return false;
         	}
        }
        else
        {
          	$this->form_validation->set_message('validateLevel', 'Select Level.!');
          	return false;
        }	
    }

//==============================================================================================//    




//=================================== LEVEL LIST ===================================================//

	public function sublevel_list() 
	{
		$view_data['page'] = 'sublevel_list';
		$data['page_title'] = "Sub Level List";
		$data['page_data'] = $this->load->view('sublevel/sublevel_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

//==================================================================================================//   



//====================================== AJAX FOR LEVEL LIST =======================================//

    public function ajax_sublevel_list() 
    {
		$requestData = $_REQUEST;
		// print_r($requestData);
		$columns = array(
			0 => 'id',
			1 => 'syllabus_id',
			2 => 'level_id',
			3 => 'sub_level',
			4 => 'status'
			
		);

		$query = "SELECT count(id) as total FROM sub_level where status != 2";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
		$sql = "SELECT * , case status when '0' then 'Active' when '1' then 'Blocked' end as status FROM sub_level where status != 2 ";

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
			$sql.=" AND sub_level LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
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
		$levelData = $this->db->get_where('level', array('status'=>'0'))->result_array();
		$levelName = array_column($levelData, 'level_name', 'id');

		foreach ($result as $r) 
		{  
			$nestedData = array();
			$nestedData[] = $syllabusName[$r->syllabus_id];
			$nestedData[] = $levelName[$r->level_id];
			$nestedData[] = $r->sub_level;
			$nestedData[] = $r->status;
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
  

}
