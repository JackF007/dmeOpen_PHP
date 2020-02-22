<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slides_Model extends CI_Model{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper('image_upload');
        $this->load->helper('file_upload');
    }

//=================================== CREATE UNIT =======================================//

    public function create_slides()
    {
        if(!empty($_FILES['imageWithQuestion']['name'])){
            $path = './uploads/questionsImages/';
            $data['question_image'] = upload_image(array('upload_path'=>$path, 'key'=>'imageWithQuestion'));
        } 
        if(!empty($_FILES['imageWithOption1']['name'])){
            $path1 = './uploads/optionsImages/';
            $data['option1_image'] = upload_imageOp1(array('upload_path'=>$path1, 'key'=>'imageWithOption1'));
        } 
        if(!empty($_FILES['imageWithOption2']['name'])){
            $path2 = './uploads/optionsImages/';
            $data['option2_image'] = upload_imageOp1(array('upload_path'=>$path2, 'key'=>'imageWithOption2'));
        } 
        if(!empty($_FILES['imageWithOption3']['name'])){
            $path3 = './uploads/optionsImages/';
            $data['option3_image']  = upload_imageOp1(array('upload_path'=>$path3, 'key'=>'imageWithOption3'));
        } 
        if(!empty($_FILES['imageWithOption4']['name'])){
            $path4 = './uploads/optionsImages/';
            $data['option4_image'] = upload_imageOp1(array('upload_path'=>$path4, 'key'=>'imageWithOption4'));
        }
        if($this->input->post('textWithQuestion') != ""){
            $data['question_text'] = $this->input->post('textWithQuestion');  
        }
        if($this->input->post('textOnlyQuestion') != ""){ 
            $data['question_text'] = $this->input->post('textOnlyQuestion');   
        }
        if($this->input->post('textWithOption1') != ""){ 
            $data['option1_text'] = $this->input->post('textWithOption1');   
        }
        if($this->input->post('textWithOption2') != ""){ 
            $data['option2_text'] = $this->input->post('textWithOption2');   
        }
        if($this->input->post('textWithOption3') != ""){ 
            $data['option3_text'] = $this->input->post('textWithOption3');   
        }
        if($this->input->post('textWithOption4') != ""){ 
            $data['option4_text'] = $this->input->post('textWithOption4');   
        }
        if($this->input->post('textOnlyOption1') != ""){ 
            $data['option1_text'] = $this->input->post('textOnlyOption1');   
        }
        if($this->input->post('textOnlyOption2') != ""){ 
            $data['option2_text'] = $this->input->post('textOnlyOption2');   
        }
        if($this->input->post('textOnlyOption3') != ""){ 
            $data['option3_text'] = $this->input->post('textOnlyOption3');   
        }
        if($this->input->post('textOnlyOption4') != ""){ 
            $data['option4_text'] = $this->input->post('textOnlyOption4');   
        }

        $data['syllabus_id'] = $this->input->post('syllabus_name');
        $data['level_id'] =  $this->input->post('level_name');
        $data['sub_level_id'] =   $this->input->post('sublevel_name');
        $data['unit_id'] =   $this->input->post('unit_name');
        $data['question_type'] =   $this->input->post('question');
        $data['option_type'] = $this->input->post('options');
        $data['answer_text'] =   $this->input->post('Answer');
        $data['status'] = '0';
        $data['creation_time'] = date('Y-m-d H:i:s');
        $result = $this->db->insert("slides",$data);
        return $result;
    }

//=================================================================================================//

//====================================== GET UNIT DATA ==========================================//

    public function get_unit_data($id)
    {
        $result = $this->db->select('*')
                       ->where('id',$id)
                       ->get("units")->row_array();
        return $result; 
    }

//================================================================================================//  

//====================================== UPDATE UNIT ============================================//

    public function update_unit($id)
    {
        $data['unit_name'] =   ucfirst($this->input->post('unit'));
        $this->db->where(array('id'=>$id, 'status'=>0));
        $result = $this->db->update("units",$data);
        return $result;
    }

//================================================================================================//  

//====================================== DELETE UNIT ============================================//

    public function delete_unit($id)
    {
        $data = array('status' =>2);
        $this->db->where('id',$id);
        $result = $this->db->update("units",$data);
    }

//================================================================================================//  

//======================================= BLOCK UNIT ============================================//

    public function block_unit($id,$status)
    {
        $data = array('status' =>$status);
        $this->db->where('id',$id);
        $result = $this->db->update("units",$data);
    }

//================================================================================================//  

}
