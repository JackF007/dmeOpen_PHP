<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sublevel_Model extends CI_Model{

    function __construct() 
    {
        parent::__construct();
    }

//=================================== CREATE LEVEL =======================================//

    public function create_sublevel()
    {
       
        $explode_level = explode(" ", $this->input->post('sublevel'));
        $level = explode("-", $explode_level[1]);
        for($i=$level[0];$i<=$level[1];$i++){
            $data['syllabus_id'] = $this->input->post('syllabus_name');
            $data['level_id'] =  $this->input->post('level_name');
            $data['sub_level'] =  "Level-".$i;
            $data['status'] = '0';
            $data['created_datetime'] = date('Y-m-d H:i:s');
            $result = $this->db->insert("sub_level",$data);
        }
       
        return $result;
    }

//===================================================================================================//   

}
