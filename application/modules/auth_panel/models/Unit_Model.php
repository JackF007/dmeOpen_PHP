<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_Model extends CI_Model{

    function __construct() 
    {
        parent::__construct();
    }

//=================================== CREATE UNIT =======================================//

    public function create_unit()
    {             
        $data['syllabus_id'] = $this->input->post('syllabus_name');
        $data['level_id'] =  $this->input->post('level_name');
        $data['sub_level_id'] =   $this->input->post('sublevel_name');
        $data['unit_name'] =   ucfirst($this->input->post('unit'));
        $data['status'] = '0';
        $data['created_datetime'] = date('Y-m-d H:i:s');
        $result = $this->db->insert("units",$data);
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
