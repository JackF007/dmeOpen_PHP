<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subcategory_Model extends CI_Model{
   
    function __construct(){
        parent::__construct();
    }
    
//=================================== CREATE SYLLABUS =======================================//

    
    public function create_subcategory(){
        $files = $_FILES['sub_category_logo']['name'];
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        if(!empty($files)){
            $subcategoryName = str_replace(" ","_", $this->input->post('sub_category_name'));
            $_FILES['sub_category_logo']['name']= $subcategoryName.'.'.$ext;
            $_FILES['sub_category_logo']['type']= $_FILES['sub_category_logo']['type'];
            $_FILES['sub_category_logo']['tmp_name']= $_FILES['sub_category_logo']['tmp_name'];
            $_FILES['sub_category_logo']['error']= $_FILES['sub_category_logo']['error'];
            $_FILES['sub_category_logo']['size']= $_FILES['sub_category_logo']['size'];
            $config['upload_path'] = './images/sub_category/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '1048576';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload('sub_category_logo');
            $subcategory_image = $_FILES['sub_category_logo']['name'];
            $data['sub_category_logo'] = $subcategory_image;
        }
        $data['sub_category_name'] = $this->input->post('sub_category_name');
     
        $result = $this->db->insert("sub_category_list",$data);
        return $result;
    }

//=====================================================================================================//   

//====================================== GET SYLLABUS DATA ===============================================//
    
    public function get_subcategory_data($id){
        $result = $this->db->select('*')
                           ->where('sub_category_id',$id)
                           ->get("sub_category_list")->row_array();
        return $result;  
    }

//=====================================================================================================//  

//====================================== UPDATE SYLLABUS =================================================//

    
    
    public function update_subcategory($id){
        $subcategory_name = str_replace(" ","_", $this->input->post('sub_category_name'));
        if(!empty($_FILES['sub_category_logo']['name'])){
            $simage = $this->db->get_where('sub_category_list', array('sub_category_status'=>'0', 'sub_category_id'=>$id))->row()->sub_category_logo;
            if(!empty($simage)){
                $file = "images/sub_category/".$simage;
                if (file_exists($file)){
                    unlink("images/sub_category/".$simage);
                    $data['sub_category_logo'] = $this->imageupload($sub_category_name);  
                } else{
                    $data['sub_category_logo'] = $this->imageupload($sub_category_name);       
                }
            } else{                   
                $data['sub_category_logo'] = $this->imageupload($subcategory_name);    
            }                
        } else{
            $sname = $this->db->get_where('sub_category_list', array('sub_category_status'=>'0', 'sub_category_id'=>$id))->row()->sub_category_logo;
            if(!empty($sname)){
                $ext = pathinfo($sname, PATHINFO_EXTENSION);
                $subcategoryName = str_replace(" ","_", $this->input->post('sub_category_name'));
                $a = $subcategoryName.".".$ext;
                $image_url = base_url('images/sub_category/');
                rename($image_url.$sname, $image_url.$a);
                $data['sub_category_logo'] = $a;
            }
        }
        $data['sub_category_name'] = $this->input->post('sub_category_name');
        
        $this->db->where(array('sub_category_id'=>$id, 'sub_category_status'=>'0'));
        $result = $this->db->update("sub_category_list",$data);
        return $result;
    }

//=====================================================================================================//  

//=================================Image Upload in Folder =======================================//

    public function imageupload($category_name){
        $files = $_FILES['sub_category_logo']['name'];
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        $subcategoryName = str_replace(" ","_", $this->input->post('sub_category_name'));
        $_FILES['sub_category_logo']['name'] = $subcategoryName.'.'.$ext;
        $config['upload_path'] = './images/sub_category/';
        $config['allowed_types'] = 'jpg|png|jpeg'; 
        $config['max_size'] = '1048576'; 
        $this->load->library('upload', $config);           
        $this->upload->initialize($config);
        if ($this->upload->do_upload('sub_category_logo')){  
            $sub_category_logo = $_FILES['sub_category_logo']['name'];
            return $sub_category_logo;
        }
    }

//================================================================================================//

//====================================== DELETE SYLLABUS =================================================//

    public function delete_subcategory($id){

        $this->db->where('sub_category_id',$id);
        $result = $this->db->delete("sub_category_list");
    }

//=====================================================================================================//  

//====================================== BLOCK SYLLABUS =================================================//

    public function block_subcategory($id,$status){
        $data = array('sub_category_status' =>$status);
        $this->db->where('sub_category_id',$id);
        $result = $this->db->update("sub_category_list",$data);
    }

//=====================================================================================================//  

}
