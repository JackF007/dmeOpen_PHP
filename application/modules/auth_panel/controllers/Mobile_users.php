<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile_users extends MX_Controller {

	function __construct() {
		parent::__construct();
		modules::run('auth_panel/auth_panel_ini/auth_ini');
		$this->load->model('Mobile_users_model');
		$this->load->library('form_validation', 'uploads');
	}


	public function mobile_users_list() {
		$view_data['page'] = 'mobile_users_list';
		$data['page_data'] = $this->load->view('mobile_users/all_user', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}


	public function ajax_all_mobile_user_list() {

		$requestData = $_REQUEST;
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'email',
			3 => 'mobile',
			4 => 'status',
			5 => 'created',
		);
		$where="";
		$query = "SELECT count(id) as total FROM users where status != 2 $where";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;

		$sql = "SELECT id,name,email,mobile,DATE_FORMAT(FROM_UNIXTIME(created), '%d-%m-%Y %h:%i:%s') as created, case status when '1' then 'Active' when '0' then 'Disable' end as status FROM  users  where status != 2 $where";

		if (!empty($requestData['columns'][0]['search']['value'])) {   //name
			$sql.=" AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
			$sql.=" AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
			$sql.=" AND email LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
			$sql.=" AND mobile LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}
		if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "" ) {  
			$sql.=" AND status LIKE ". $requestData['columns'][4]['search']['value'];
		}
		$query = $this->db->query($sql)->result();
		$totalFiltered = count($query); 
		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  
		$result = $this->db->query($sql)->result();
		$data = array();

		foreach ($result as $r) {  
			$nestedData = array();
			$nestedData[] = $r->id;
			$nestedData[] = $r->name;
			$nestedData[] = $r->email;
			$nestedData[] = $r->mobile;
			$nestedData[] = ($r->status == 'Active')?'<span class="btn btn-xs bold btn-success">Active</span>':'<span class="btn btn-sm btn-danger">Disabled</span>';
			$nestedData[] = $r->created;
			$nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "mobile_users/user_profile/" . $r->id . "'>View</a>";
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


	public function user_profile($id) {
		// if($this->input->post()) {  

		// 	//$this->form_validation->set_rules('user_id', '', 'required');
		// 	$this->form_validation->set_rules('u_name', 'Beneficiary name', 'required');
		// 	$this->form_validation->set_rules('b_account', 'Beneficiary account', 'required');
		// 	$this->form_validation->set_rules('b_ifsc', 'Bank IFSC', 'required');
		// 	$this->form_validation->set_rules('b_name', 'Bank name', 'required');
		// 	$this->form_validation->set_rules('b_address', 'Bank address', 'required');
		// 	$this->form_validation->set_rules('r_sharing', 'Resource sharing', 'required');
		
			

		// 	if ($this->form_validation->run() == FALSE) { 
             
  //           } 
			
			
		// 	$update = array('u_name' => $this->input->post('u_name'),'b_account' => $this->input->post('b_account'),'b_ifsc' => $this->input->post('b_ifsc'),'b_name' => $this->input->post('b_name'),'b_address' => $this->input->post('b_address'),'r_sharing' => $this->input->post('r_sharing'));
		// 	//print_r($insert_data); die;
			
		// 	$this->db->where('user_id',$id);
  //           $this->db->update('course_instructor_information',$update);

		// 	$data['page_toast'] = 'File added successfully.';
		// 	$data['page_toast_type'] = 'success';
		// 	$data['page_toast_title'] = 'Action performed.';
		// }

		
		
		$view_data['user_data'] = $this->Mobile_users_model->get_user_profile($id);
		$view_data['page'] = 'all_user';
        $data['page_data'] = $this->load->view('mobile_users/user_profile', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);

	}

	public function delete_user($status,$id) {
		
		$status = $this->Mobile_users_model->update_user_status($status,$id);
		if($status == 'TRUE') {
			redirect('auth_panel/mobile_users/user_profile/'.$id);
		}
	}


	public function disable_user($status,$id) {
		$status = $this->Mobile_users_model->update_user_status($status,$id);
		
		if($status) {
			redirect('auth_panel/mobile_users/user_profile/'.$id);
		}
	}

	public function enable_user($status,$id) {
		$status = $this->Mobile_users_model->update_user_status($status,$id);
		
		if($status) {
			redirect('auth_panel/mobile_users/user_profile/'.$id);
		}
	}

	public function is_moderator($id) {
		if($id > 0) {
		$this->db->where('id',$id);
		$this->db->update('users',array('is_moderate'=>$_POST['modrator']));
		//creating backend log
		$user_details=$this->web_user_model->get_user_basic_detail($id);
			if($_POST['modrator']==1){
				backend_log_genration($this->session->userdata('active_backend_user_id'),
										'Assigned Modrator role to '. $user_details['name'].'.',
										'WEB USER');	
			}else{
				backend_log_genration($this->session->userdata('active_backend_user_id'),
										'Removed Modrator role from '. $user_details['name'].'.',
										'WEB USER');
			}
		
		}
		
		redirect('auth_panel/web_user/user_profile/'.$id);
	}
	

	
	
		public function is_instructor($id) { 
		if($id > 0) { //print_r($_POST); die;
			$this->db->where('id',$id);
			$update = $this->db->update('users',array('is_instructor'=>$_POST['instructor']));
			
			$is_instructor = $_POST['instructor'];
			
			$this->db->select("name,email,password");
			$this->db->where('id',$id);
			$user_details = $this->db->get('users')->row_array();
						
			$add_instructor = array('username'=>$user_details['name'],'email'=>$user_details['email'],'password'=>$user_details['password'],'creation_time'=>time(),'	instructor_id'=>$id,'status'=>0);
			
			if($_POST['instructor'] == 1){				
				$status = $this->web_user_model->add_instructor_id($id);
				
				$this->db->where('instructor_id',$id);
			    $instructor_details_backend_user = $this->db->get('backend_user')->row_array();
				if(empty($instructor_details_backend_user)){
					$add_instructor_to_backend_user = $this->db->insert("backend_user",$add_instructor);
				}elseif($instructor_details_backend_user['status'] == 1){
					$this->db->where('instructor_id',$id);
					$update = $this->db->update('backend_user',array('status'=>0));
				}
			}
			elseif($_POST['instructor'] == 0){								
				$this->db->where('instructor_id',$id);
				$update = $this->db->update('backend_user',array('status'=>1));
			}
		}
		
		redirect('auth_panel/web_user/user_profile/'.$id);
	}

	public function is_expert($id) { 
		if($id > 0) { //print_r($_POST); die;
			$this->db->where('id',$id);
			$update = $this->db->update('users',array('is_expert'=>$_POST['expert']));
			$user_details=$this->web_user_model->get_user_basic_detail($id);
			if($_POST['expert'] == 1){
				$status = $this->web_user_model->add_instructor_id($id);
				backend_log_genration($this->session->userdata('active_backend_user_id'),
										'Assigned Expert role to '. $user_details['name'].'.',
										'WEB USER');
			}else{
				backend_log_genration($this->session->userdata('active_backend_user_id'),
										'Removed Expert role from '. $user_details['name'].'.',
										'WEB USER');
			}						
		}
		
		redirect('auth_panel/web_user/user_profile/'.$id);
	}

	public function all_user_location() {
		
		$view_data['page'] = 'location';
		$data['page_data'] = $this->load->view('web_user/all_user_location', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}


	public function ajax_all_user_location() {

		// storing  request (ie, get/post) global array to a variable
		$requestData = $_REQUEST;

		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'name',
			2 => 'email',
			3 => 'country',
			4 => 'state',
			5 => 'city'
		);

		$query = "SELECT count(id) as total
									FROM user_registerd_location
									where 1 = 1"
									;
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;

		$sql = "SELECT url.*,u.name,u.email,u.creation_time FROM  user_registerd_location as url
				JOIN users as u ON url.user_id = u.id
				where 1 = 1 
				";
		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) {   //name
			$sql.=" AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
			$sql.=" AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		
		if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
			$sql.=" AND email LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}

		if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
			$sql.=" AND country LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}

		if (!empty($requestData['columns'][4]['search']['value'])) {  //salary
			$sql.=" AND state LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
		}

		if (!empty($requestData['columns'][5]['search']['value'])) {  //salary
			$sql.=" AND city LIKE '". $requestData['columns'][5]['search']['value'] . "%' ";
		}

		
		//echo $requestData['columns'][5]['search']['value'];
		$query = $this->db->query($sql)->result();

		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

		$result = $this->db->query($sql)->result();
	
		$data = array();

		foreach ($result as $r) {  // preparing an array
			$nestedData = array();
			$nestedData[] = $r->id;
			$nestedData[] = $r->name;
			$nestedData[] = $r->email;
			$nestedData[] = $r->country;
			$nestedData[] = $r->state;
			$nestedData[] =	$r->city;
			$nestedData[] = $r->latitude;
			$nestedData[] = $r->longitude;
			$nestedData[] = $r->ip_address;
			$nestedData[] = date("d-m-Y", $r->creation_time/1000);
			$nestedData[] = "<a class='btn-xs bold btn btn-info' href='#'>View</a>";
			$data[] = $nestedData;
		}

		$json_data = array(
			"draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($totalData), // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $data   // total data array
		);

		echo json_encode($json_data);  // send data as json format
	}
	/*	 * *******************End User************************* */
	
	public function ajax_instructor_ratings_list($id) { 
		// storing  request (ie, get/post) global array to a variable
		$requestData = $_REQUEST;
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'name',
			2 => 'rating',
			3 => 'text',
			4 => 'creation_time'
							
		);
			
		$query = "SELECT count(id) as total FROM course_instructor_rating";								
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;

		$sql = "SELECT cir.*,u.name,DATE_FORMAT(FROM_UNIXTIME(cir.creation_time/1000), '%d-%m-%Y %h:%i:%s') as creation_time  
								FROM course_instructor_rating as  cir
								 join users as u 
								on cir.user_id = u.id where instructor_id = $id";																
								
		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) {   //name
			$sql.=" AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
			$sql.=" AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
			$sql.=" AND rating LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
			$sql.=" AND text LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
		}
		
		if (!empty($requestData['columns'][4]['search']['value'])) {  //salary
			$sql.=" AND creation_time LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
		}
		
		
							
		$query = $this->db->query($sql)->result();

		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

		$result = $this->db->query($sql)->result();
		
		$data = array();
		foreach ($result as $r) {  // preparing an array
			$nestedData = array();		
	
			$star="";
			if($r->rating > 0 ){
				for($i=0;$i<$r->rating;$i++){
					$star .='<i class =  "fa fa-star text-danger"></i>';
				}
				if($i<5){$j = 5 - $i;for($i=0;$i<$j;$i++){$star .='<i class =  "fa fa-star-o text-danger"></i>';}}
			}
			
			$nestedData[] = $r->id;
			$nestedData[] = $r->name;
			$nestedData[] = $star;
			$nestedData[] = substr($r->text, 0, 30);
			$nestedData[] = $r->creation_time;
			$action = "<a class='btn-sm btn btn-success btn-xs bold' href='" . AUTH_PANEL_URL . "web_user/edit_instructor_rating/" . $r->id . "'>Edit</a>";	
			$action .= "<a class='btn-sm btn btn-danger btn-xs bold' href='" . AUTH_PANEL_URL . "web_user/delete_review/" . $r->id ."?instructor_id=".$r->instructor_id. "'>delete</a>";	
			$nestedData[] = $action;

			$data[] = $nestedData;
		}

		$json_data = array(
			"draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal" => intval($totalData), // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data" => $data   // total data array
		);

		echo json_encode($json_data);  // send data as json format
	}
	
	
	public function edit_instructor_rating($id){	
		$view_data['page']  = 'edit_instructor_rating';
		$data['page_title'] = "Edit Instructor Rating";
		if($this->input->post('update_instructor_review')) {
			/* handle submission */
			$this->update_instructor_review();
		}
		$view_data['instructor_rating_detail'] = $this->web_user_model->get_instructor_rating_details_by_id($id);			
		$data['page_data'] = $this->load->view('web_user/edit_instructor_details', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}
	
	private function update_instructor_review(){
		if($this->input->post()) {  
			$this->form_validation->set_rules('rating', 'Rating', 'required');
			$this->form_validation->set_rules('text', 'Review', 'required');		
			$review_id = $this->input->post('id');
			$user_id = $this->input->post('instructor_id');
			if ($this->form_validation->run() == FALSE) {   
             $error = validation_errors();
            }else{
				$update = array(			
					'rating' => $this->input->post('rating'),
					'text' => $this->input->post('text'),

				);
				$this->db->where('id',$review_id);
				$this->db->update('course_instructor_rating',$update);
				page_alert_box('success','Action performed','Review updated successfully');
			}
		}
			
	}
	
	public function delete_review($id) { 
		$user_id = $_GET['instructor_id']; 
		$status = $this->web_user_model->delete_review($id);
		page_alert_box('success','Action performed','Review deleted successfully');
		if($status) {
			redirect('auth_panel/web_user/user_profile/'.$user_id);
		}
	}
	
	
}
