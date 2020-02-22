<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Level_Model extends CI_Model{

    function __construct() 
    {
        parent::__construct();
    }



//=================================== CREATE LEVEL =======================================//

    public function create_level()
    {

        $files = $_FILES['level_image']['name'];
        $category = explode("-", $this->input->post('category_name'));
        $category_id = $category[0];
        $category_name = $category[1];
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        if(!empty($files))
        {

            $_FILES['level_image']['name']= $this->input->post('syllabus_name')."-".$category_name.'.'.$ext;
            $_FILES['level_image']['type']= $_FILES['level_image']['type'];
            $_FILES['level_image']['tmp_name']= $_FILES['level_image']['tmp_name'];
            $_FILES['level_image']['error']= $_FILES['level_image']['error'];
            $_FILES['level_image']['size']= $_FILES['level_image']['size'];
            $config['upload_path'] = './web_assets/images/level_images/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '1048576';
            $config['remove_spaces'] = true;
            $config['overwrite'] = false;
            $config['max_width'] = '';
            $config['max_height'] = '';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload('level_image');
            $level_image = $_FILES['level_image']['name'];
            $data['level_image'] = $level_image;
        }
        $data['syllabus_id'] = $this->input->post('syllabus_name');
        $data['category_id'] = $category_id;
        $data['level_name'] = $category_name;
        $data['status'] = '0';
        $data['created_datetime'] = date('Y-m-d H:i:s');
        $result = $this->db->insert("level",$data);
        return $result;
    }

//===========================================================================================//   



//====================================== GET LEVEL DATA =====================================//

    public function get_level_data($id)
    {
        $result = $this->db->select('*')
                       ->where('id',$id)
                       ->get("level")->row_array();
        return $result; 
    }

//================================================================================================//  



//====================================== UPDATE LEVEL ============================================//

    public function update_level($id)
    {
        $category = explode("-", $this->input->post('category_name'));
        $category_id = $category[0];
        $category_name = $category[1];
        if(!empty($_FILES['level_image']['name'])){
            $limage = $this->db->get_where('level', array('status'=>'0', 'id'=>$id))->row()->level_image;
            if(!empty($limage)){
                $file = "web_assets/images/level_images/".$limage;
                if(file_exists($file)){
                    unlink("web_assets/images/level_images/".$limage);
                    $data['level_image'] = $this->imageupload($category_name);
                } else{ 
                    $data['level_image'] = $this->imageupload($category_name); 
                }
            } else{   
                $data['level_image'] = $this->imageupload($category_name);  
            }                   
        } else{
            $lname = $this->db->get_where('level', array('status'=>'0', 'id'=>$id))->row()->level_image;
            if(!empty($lname)){
                $ext = pathinfo($lname, PATHINFO_EXTENSION);
                $category_name = str_replace(" ","_", $category_name);
                $a = $this->input->post('syllabus_name')."-".$category_name.'.'.$ext;
                rename('web_assets/images/level_images/'.$lname, 'web_assets/images/level_images/'.$a);
                $data['level_image'] = $a;  
            }
        }

        $data['syllabus_id'] = $this->input->post('syllabus_name');
        $data['category_id'] =  $category_id;
        $data['level_name'] = $category_name;
        $data['updated_datetime'] = date('Y-m-d H:i:s');
        $this->db->where('id',$id);
        $result = $this->db->update("level",$data);
        return $result;
    }

//================================================================================================//  




//=================================Image Upload in Folder =======================================//

    public function imageupload($category_name)
    {
        $files = $_FILES['level_image']['name'];
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        $category_name = str_replace(" ","_", $category_name);
        $_FILES['level_image']['name'] = $this->input->post('syllabus_name')."-".$category_name.'.'.$ext;
        $config['upload_path'] = './web_assets/images/level_images/';
        $config['allowed_types'] = 'jpg|png|jpeg'; 
        $config['max_size'] = '1048576';  
        $this->load->library('upload', $config);           
        if ($this->upload->do_upload('level_image'))
        {  
            $level_image = $_FILES['level_image']['name'];
            return $level_image;
        }
    }

//================================================================================================//




//====================================== DELETE LEVEL ============================================//

    public function delete_level($id)
    {
        $data = array('status' =>2);
        $this->db->where('id',$id);
        $result = $this->db->update("level",$data);
    }

//================================================================================================//  




//======================================= BLOCK LEVEL ============================================//

    public function block_level($id,$status)
    {
        $data = array('status' =>$status);
        $this->db->where('id',$id);
        $result = $this->db->update("level",$data);
    }

//================================================================================================//  

}
