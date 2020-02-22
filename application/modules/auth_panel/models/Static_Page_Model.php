<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_Page_Model extends CI_Model{
   
    function __construct() 
    {
        parent::__construct();
       $this->load->library('upload');
    }

//=================================== GET ALL CATEGROY ==========================================// 

    public function get_static_data($id)
    {
      $result = $this->db->select('*')
                         ->where('id',$id)
                         ->get("static_content")->row_array();
        
      return $result;
    }

//===============================================================================================//

    
    //=================================== UPDATE CATEGORY ===========================================// 
    public function update_static($id,$input){
        if($id!=""){

            $this->db->where("id",$id);
            $this->db->update("static_content",$input);
        }else{
            $this->db->insert('static_content',$input);
        }
        return true;
    }
    
    public function update_static1($id)
    {  
     $category_name = str_replace(" ","_", $this->input->post('category_name'));
       
        if(!empty($_FILES['files']['name'])){
            $simage = $this->db->get_where('static_content', array('status'=>'0', 'id'=>$id))->row()->files;
            
//            echo "a.........".$simage;die;
            
            if(!empty($simage)){
                echo "b.........".$simage;
                $file = "images/static_content/".$simage;
             
                if (file_exists($file)){
                    
                    unlink("images/static_content/".$simage);
                    $data['files'] = $this->imageupload();  
                } else{
                    
                    $data['files'] = $this->imageupload();       
                }
            } else{
                echo "k.........";
                $data['files'] = $this->imageupload();    
                echo "c.........".$data['files']; 
            }                
        } else{
             
            $sname = $this->db->get_where('static_content', array('status'=>'0', 'id'=>$id))->row()->files;
            echo "d.........".$sname; 
            if(!empty($sname)){
                    echo "e.........".$sname; 
                $ext = pathinfo($sname, PATHINFO_EXTENSION);
                $imgName = str_replace(" ","_", $this->input->post('category_name'));
                $a = $imgName.".".$ext;
                $image_url = base_url('images/files/');
                rename($image_url.$sname, $image_url.$a);
                $data['files'] = $a;
            }
        }
           
        $data['content'] = $this->input->post('content');
        
        $this->db->where(array('id'=>$id, 'status'=>'0'));
        $result = $this->db->update("static_content",$data);
        return $result;
    }

//===============================================================================================//

//=================================Image Upload in Folder =======================================//

    public function imageupload(){
        
        $files = $_FILES['files']['name'];
        echo "f.........".$files; 
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        $syllabusName = str_replace(" ","_", $this->input->post(''));
        echo "g.........".$syllabusName; 
        $_FILES['files']['name'] = $syllabusName.'.'.$ext;
        echo "h.........".$_FILES['files']['name']; 
        $config['upload_path'] = './images/static_content/';
        $config['max_size'] = '1048576'; 
        $this->load->library('upload', $config);   
        $this->upload->initialize($config);
        if ($this->upload->do_upload('files')){  
            $files = $_FILES['files']['name'];
            echo "i.........". $files; 
            return $files;
        }
        else
        {
            
             $error = array('error' => $this->upload->display_errors(),'path'=>$this->upload->data('full_path'));
        
        }
    }

//================================================================================================//

    
    public function delete($id){
  
        $this->db->where(array('id'=>$id));
        $this->db->delete("static_content");

    }
    
    public function block($id,$status){
        $data=array('status'=>$status);
        $this->db->where(array('id'=>$id));
        $result=$this->db->update("static_content",$data);
        return $result;
        
    }
    public function unblock($id){
          $data=array('status'=>$status);
        $this->db->where(array('id'=>$id));
        $result=$this->db->update("static_content",$data);
        return $result;
    }
}