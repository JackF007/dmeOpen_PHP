<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_Model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    public function get_feed($email)
    {
        $this->db->where('email',$email);
        return $query = $this->db->get('feedback')->result_array();
        
    }

    public function view_feed($email){
        $this->db->where('email',$email);
        return $query = $this->db->get('feedback')->result_array();
    }
}
?>