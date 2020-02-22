
<?php
defined('BASEPATH') OR exit('No direct script BasePath allowed');
class Static_Page extends MX_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model('Static_Page_Model');
        $this->form_validation->run($this);
		$this->load->helper('aul');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->helper('custom');
    }
  public  function index()
    {
        
    }
    
//    function create_static_page(){
//        $data['page_data'] = $this->load->view('static_page/create_static', $view_data=array(), TRUE);
//		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);		
//    }
//    function ajax_create_static_page(){
//    	// storing  request (ie, get/post) global array to a variable
//		$requestData = $_REQUEST;
//
//		$columns = array(
//			// datatable column index  => database column name
//			0 => 'id',
//			1 => 'name',
//			2 => 'email',
//			3 => 'phone',
//			4 => 'enquiry',			
//			5 => 'create_date'
//		);
//
//		$query = "SELECT count(id) as total
//								FROM qa_general_enquiries
//								";
//		$query = $this->db->query($query);
//		$query = $query->row_array();
//		$totalData = (count($query) > 0) ? $query['total'] : 0;
//		$totalFiltered = $totalData;
//
//		$sql = "SELECT * 
//					 FROM qa_general_enquiries as cm 					 
//					 WHERE  1 = 1 ";
//
//		// getting records as per search parameters
//		if (!empty($requestData['columns'][0]['search']['value'])) {   //name
//			$sql.=" AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
//		}
//		if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
//			$sql.=" AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
//		}
//		if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
//			$sql.=" AND email LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
//		}		
//		if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
//			$sql.=" AND phone LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
//		}
//		if (!empty($requestData['columns'][4]['search']['value']) || $requestData['columns'][4]['search']['value'] == 0) {  //salary
//			$sql.=" AND enquiry LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
//		}	
//		if (!empty($requestData['columns'][5]['search']['value']) || $requestData['columns'][5]['search']['value'] == 0 ) {  //salary
//			$sql.=" AND create_date LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
//		}	
//		$query = $this->db->query($sql)->result();
//
//		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//
//		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
//
//		$result = $this->db->query($sql)->result();
//		$data = array();
//		$id=1;
//		foreach ($result as $r) {  // preparing an array
//			$nestedData = array();
//			$nestedData[] = $id++;
//			// $nestedData[] = $r->id;
//			$nestedData[] = $r->name;
//			$nestedData[] = $r->email;
//			$nestedData[] = $r->phone;
//			$nestedData[] = $r->enquiry; 
//			$nestedData[] = $r->create_date;									
//			//$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "course_product/course/edit_course_page?course_id=" . $r->id . "'>Edit</a>";			
//			//$nestedData[] = $action;
//			$data[] = $nestedData;
//		}
//
//		$json_data = array(
//			"draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
//			"recordsTotal" => intval($totalData), // total number of records
//			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
//			"data" => $data   // total data array
//		);
//
//		echo json_encode($json_data);  // send data as json format				      
//    }
    public function static_page_list(){
        $view_data['email'] = $this->input->get('');    //empty data
        $data['page_data']   = $this->load->view('static_page/list_static' ,$view_data,TRUE);
        echo modules::run('auth_panel/template/call_default_template', $data);
    }
   public function ajax_static_page_list(){
       	// storing  request (ie, get/post) global array to a variable
		$requestData = $_REQUEST;

		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'sub_category_name',
			2 => 'content',
			3 => 'files',
			4 => 'update_date'
			
		);

		$query = "SELECT count(id) as total	FROM static_content";
		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;

		$sql = "SELECT static_content.id,static_content.status,static_content.content,static_content.files,static_content.update_date,sub_category_list.sub_category_name FROM static_content left join sub_category_list on static_content.sub_cat_id = sub_category_list.sub_category_id WHERE 1 = 1";

		// getting records as per search parameters
		if (!empty($requestData['columns'][0]['search']['value'])) {   //name
			$sql.=" AND id = '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
			$sql.=" AND sub_category_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
			$sql.=" AND content LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
		}		
		if (!empty($requestData['columns'][4]['search']['value'])) {  //salary
			$sql.=" AND update_date LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
		}
			
		$query = $this->db->query($sql)->result();

		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

		$result = $this->db->query($sql)->result();
		$data = array();
       
       $d=array();  
       $i = $requestData['start'];
		foreach ($result as $r) {  // preparing an array
            $i++;
			$nestedData = array();
			$nestedData[] = $i;
			$nestedData[] = $r->sub_category_name;
			$nestedData[] = $r->content;
            $d1=explode(',',$r->files);
            $image = "";
            foreach($d1 as $d)
            {
                if($d=='')//if no image
                {}
                else{
                $image = $image."<img width='50px' height='50px' alt='' src='".base_url("images/static_content/".$d)."'>";
                }
            }
            $nestedData[] = $image;
         	$nestedData[] = $r->update_date;
            
            if($r->status == '0'){
			$action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "Static_Page/edit_page/" . $r->id . "'>Edit</a>&nbsp;"
				. "<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "Static_Page/delete_page/" . $r->id . "'>Delete</a>&nbsp;".
				"<a class='btn-xs btn  bold btn-warning' href='" . AUTH_PANEL_URL . "Static_Page/block_page/" . $r->id . "/1'>Block</a>";
			}
			else 
			{
				$action = "<a class='btn-xs bold btn btn-success' href='" . AUTH_PANEL_URL . "Static_Page/block_page/" . $r->id . "/0'>Unblock</a>";
			}
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
    
    
//=================================== EDIT Subcategory ==========================================//     

    public function edit_page($id = null) {
        
        
        $input = $this->input->post();
        if(!empty($id) && !$input){
            $view_data['static_data'] = $this->Static_Page_Model->get_static_data($id);
        }
        if($input){
            if(!empty($id)){
        //=====================       
//                var file = [];
//                var file_count = document.getElementById('exampleInputEmail1').files.length;
//                echo
//                for(let i=0;i<file_count;i++)
//                    { 
//              file[i] = document.getElementById('exampleInputEmail1').files[i];
//                }
//            $config['upload_path'] = 'web_assets/upload/';
//                $this->load->library('upload', $config);
//            $len = count($_FILES);
         //=============================
              
                
                if($_FILES['files']['name'])
                { 
                    $exist_files = $this->db->query("select files from static_content where id=".$id)->row()->files;
                    foreach($_FILES as $file){
//                if(file_exists($image_name)){
//                        unlink($image_name);
//                    }
                        $ext = explode(".",$_FILES['files']['name']);
                        $ext = end($ext);
                        $image = time().".".$ext;
                        if($exist_files){
                            $exist_files = $exist_files.",".$image;
                        }else{
                            $exist_files = $image;
                        }
                        $path =getcwd()."/images/static_content/".$image;
                        
                        move_uploaded_file($image, $path);
                    }
                    $input["files"] = $exist_files;
                }
                $update_data = $this->Static_Page_Model->update_static($id,$input);
                if ($update_data) 
                {
                    $this->session->set_flashdata('success_message', 'User has been Updated succssfully');
                }
                else 
                {
                    $this->session->set_flashdata('error_message', 'User not Updated');
                }
                redirect(AUTH_PANEL_URL . 'Static_Page/static_page_list');
            }else{
                if($_FILES['files']['name'])
                { 
                    $input["files"] = "";
                    foreach($_FILES['files']['name'] as $file){
                        $ext = explode(".",$file);
                        $ext = end($ext);
                        $image = time().".".$ext;
                        $input["files"] = ",".$image;
                        $path = getcwd()."images/static_content/".$image;
                        move_uploaded_file($image, $path);
                    }
                }
                $input['content'] = $input;
                $update_data = $this->Static_Page_Model->update_static($id,$input);
                if ($update_data) 
                {
                    $this->session->set_flashdata('success_message', 'User has been Updated succssfully');
                }
                else 
                {
                    $this->session->set_flashdata('error_message', 'User not Updated');
                }
                redirect(AUTH_PANEL_URL . 'Static_Page/static_page_list');
            }
        }
        $view_data[] = "";
        $data['page_data'] = $this->load->view('static_page/edit_static',$view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
	public function edit_page1($id = null) 
	{
     
		if (!$this->input->post()) 
		{

			$view_data['page'] = '';
			$view_data['static_data'] = $this->Static_Page_Model->get_static_data($id);
			$data['page_data'] = $this->load->view('static_page/edit_static',$view_data, TRUE);
			echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
		} 
		else 
		{
            $this->form_validation->set_error_delimiters('<div style="color:red;font-size:15px;">', '</div>');
            $this->form_validation->set_rules('static_content', 'Content', 'required|trim|htmlspecialchars|strip_tags');

            if (empty($_FILES['files']['name']))
            { 
                $view_data['page'] = '';
                $view_data['static_data'] = $this->Static_Page_Model->get_static_data($id);
                $data['page_data'] = $this->load->view('static_page/edit_static',$view_data, TRUE);
                echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
            } 
            else 
            {
                $id = $this->input->post('id');
                $update_data = $this->Static_Page_Model->update_static($id);
                if ($update_data == true) 
                {
                    $this->session->set_flashdata('success_message', 'User has been Updated succssfully');
                }
                else 
                {
                    $this->session->set_flashdata('error_message', 'User not Updated');
                }
                redirect(AUTH_PANEL_URL . 'Static_Page/static_page_list');
            }
		}
	}

//==================================================================================================//   
    public function delete_page($id){
        
		$delete_static = $this->Static_Page_Model->delete($id);
        
		if($delete_static == true) 
		{
			$this->session->set_flashdata('success_message', 'Static has been Deleted succssfully');
		} 
		else 
		{
			$this->session->set_flashdata('error_message', 'Static not Deleted');
		}
		redirect(AUTH_PANEL_URL . 'Static_Page/static_page_list');        
    }
    
    public function block_page($id, $status){
      	$update_data = $this->Static_Page_Model->block($id, $status);
		if($update_data == true) 
		{
            
			$this->session->set_flashdata('success_message', 'Static Page has been Blocked succssfully');
		} 
		else 
		{
            
			$this->session->set_flashdata('error_message', 'Static Page not Blocked');
		}
		redirect(AUTH_PANEL_URL . 'Static_Page/static_page_list');  
        $this->Static_Page_Model->block($id);
       
    }
   
}
