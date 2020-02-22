<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MX_Controller {

	function __construct() {
		parent::__construct();
        
        $this->load->model('Feedback_Model');
		modules::run('auth_panel/auth_panel_ini/auth_ini');
		
	}
   public function index(){
       $view_data['page'] = 'feedback';
       $data['page_data']  = $this->load->view('feedback/feedback',$view_data,TRUE);
        $data['page_title'] = "User Feedback";
        echo modules::run('auth_panel/template/call_default_template', $data);
    }
    public function get_feed(){
        $email = $this->input->post('feedback_email');
        $view_data['feedback'] =$this->Feedback_Model->get_feed($email);         
        $data['page_data']  = $this->load->view('feedback/feedback',$view_data,TRUE);
        echo modules::run('auth_panel/template/call_default_template', $data);
    }
    
//================================ AJAX FOR Feed LIST=======================================//
    public function ajax_feedback() 

    {
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'id',
			1 => 'name',
            3 => 'email',
            4 => 'total_feedback'
			
		);
		$query = "SELECT count(distinct feedback.email) as total FROM feedback";
      
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
        
		$sql = "SELECT distinct feedback.email,feedback.id,users.name,count(feedback.email) as total_feedback FROM feedback left join users ON feedback.email = users.email where 1 ";
        
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{
			$sql.=" AND id = '" . $requestData['columns'][0]['search']['value'] . "' ";
		}
		if (!empty($requestData['columns'][1]['search']['value']))
		{ 
			$sql.=" AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
        if (!empty($requestData['columns'][2]['search']['value']))
		{ 
			$sql.=" AND email LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}
        
		
		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query);
		$sql.=" ORDER BY " .$columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  
		$result = $this->db->query($sql)->result();
		
		$data = array();
		foreach ($result as $r) 
		{  
			$nestedData = array();
			$nestedData[] = $r->id ;   
			$nestedData[] = $r->name;    
			$nestedData[] = $r->email;
			$nestedData[] = $r->total_feedback;
			
			$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "Feedback/view_feed?email=" . $r->email . "'>view</a>&nbsp;";
			
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
    
    public function view_feed(){
       
        $view_data['email'] = $this->input->get('email');
        $data['page_data']   = $this->load->view('feedback/view_feed' ,$view_data,TRUE);
        echo modules::run('auth_panel/template/call_default_template', $data);
    }
    //================================ AJAX FOR View Feed =======================================//
    public function ajax_view_feed() 
    {
        $email = $this->input->get('email');
		$requestData = $_REQUEST;
		$columns = array(
			0 => 'id',
			1 => 'feedback_msg',
            2 => 'rating',

		);
		$query = "SELECT distinct count(email) as total FROM feedback where email= '.$email.' ";
      
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;
        
		$sql = "SELECT feedback.id,feedback.feedback_msg,users.rating FROM feedback left join users ON feedback.email = users.email where 1 ";
        
		if (!empty($requestData['columns'][0]['search']['value'])) 
		{
			$sql.=" AND id = '" . $requestData['columns'][0]['search']['value'] . "' ";
		}
		if (!empty($requestData['columns'][1]['search']['value']))
		{ 
			$sql.=" AND feedback_msg LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
        
        
		
		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query);
		$sql.=" ORDER BY " .$columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  
		$result = $this->db->query($sql)->result();
		
		$data = array();
        $id=1;
		foreach ($result as $r) 
		{  
			$nestedData = array();
			$nestedData[] = $id++;   
			$nestedData[] = $r->feedback_msg ;   
			$nestedData[] = $r->rating;
			
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

}
    ?>