<?php
if (!defined('BASEPATH'))    exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Abhishek Jaiswal  //
//   date : 18 / sept / 2018    //
//                              //    
//////////////////////////////////

class CategoriesModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    /* get category list */
    function get_category(){
        $url=base_url('images/category/');
        $this->db->select('category_id, category_name, CONCAT("'.$url.'",category_logo) as category_logo, category_creation_time, category_status');
        $this->db->where('category_status', 0);
        $q = $this->db->get('category');
        return $q->result_array();
    }
    /* end get category list */
    
    function get_subcategories($category_id,$table,$last_count){
        $search=$this->input->post('search');
        $url=base_url('images/sub_category/');
        $this->db->select('*, concat("'.$url.'",logo)logo'); 
        $this->db->where('category_id', 0);
        if($this->input->post('search')!=''){
            $this->db->where('category_name like "%'.$search.'%"',NULL);
        }
        $this->db->where('status', 0);
        $this->db->limit(10,$last_count);
        $q = $this->db->get($table);
        
        return $q->result_array();
    }
    
    function get_subcategories_child($category_id,$table,$last_count){
        $search=$this->input->post('search');
        $url=base_url('images/sub_category/');
        $this->db->select('*, concat("'.$url.'",logo)logo'); 
        $this->db->where('category_id', $category_id);
        if($this->input->post('search')!=''){
            $this->db->where('category_name like "%'.$search.'%"',NULL);
        }
        $this->db->where('status', 0);
        $this->db->limit(10,$last_count);
        $q = $this->db->get($table);
        
        return $q->result_array();
    }

    /* get sub_category list */
    function get_subcategory_list($category_id){
        $search=$this->input->post('search');
        $url=base_url('images/sub_category/');
        $this->db->select('category_id, sub_category_id, sub_category_name, concat("'.$url.'",sub_category_logo)sub_category_logo, sub_category_creation_time, sub_category_update_time, sub_category_status');
        if($this->input->post('search')!=''){
            $this->db->where('sub_category_name like "%'.$search.'%"',NULL);
        }
        $this->db->where('category_id', $category_id);
        $this->db->where('sub_category_status', 0);
        if ($this->input->post('last_count') != "") {
            $this->db->limit(20, $this->input->post('last_count'));
        } else {
            $this->db->limit(20);
        }
        $q = $this->db->get('sub_category_list');
        return $q->result_array();
    }
    /* get sub_category list */

    /* start static content */
    function get_static_content($sub_cat_id){
    	//$this->db->select('id, title, content, files, created_date, update_date, cat_id, sub_cat_id');
        $this->db->select('*');
        $this->db->from('static_content');
        $this->db->join('sub_category_list', 'sub_category_list.sub_category_id = static_content.sub_category_id');
    	$this->db->where('static_content.sub_category_id', $sub_cat_id);
        if ($this->input->post('last_count') != "") {
            $this->db->limit(20, $this->input->post('last_count'));
        } else {
            $this->db->limit(20);
        }
    	$q = $this->db->get();
    	return $q->row_array();
    }
    
    function get_rss_link($root_id,$cat_id){
    	$this->db->where('root_id', $root_id);
        $this->db->where('category_id', $cat_id);
        if ($this->input->post('last_count') != "") {
            $this->db->limit(20, $this->input->post('last_count'));
        } else {
            $this->db->limit(20);
        }
    	$q = $this->db->get('rss_link');
    	return $q->row_array();
    }
    /*get files for sub category*/
    function get_sub_category_files($sub_category_id){
        $result=array();
        $url = base_url('images/static_content/');
        $this->db->select('id, sub_category_id , file_name');
        $this->db->where('sub_category_id', $sub_category_id);
        $q = $this->db->get('page_file')->row_array();
        if(!empty($q)){
            $res=json_decode($q['file_name']);
            $i=0;
            foreach($res as $r){
                $result[$i]['id']=$q['id'];
                $result[$i]['sub_category_id']=$q['sub_category_id'];
                $result[$i]['file_name']=$url.$r;
                $i++;
            }
            //$result=$q;
        }else{
            //$result=json_decode('{}');
        }
        //echo $this->db->last_query();
        //pre($result);die;
        return $result;
    }
    /* end static content*/

    /* start  child category */
    function get_child_category($sub_category_id){
        $search=$this->input->post('search');
        $this->db->select('*');
        $this->db->where('sub_category_id', $sub_category_id);
        if($this->input->post('search')!=''){
            $this->db->where('child_name like "%'.$search.'%"',NULL);
        }
        $q = $this->db->get('child_category');
        return $q->result_array();
    }
        
    /* child static content*/
    function get_child_static_content($child_id){
        $this->db->select('*')->from('child_static_content');
        $this->db->join('child_category', 'child_category.child_id = child_static_content.child_id');
        $this->db->where('child_static_content.child_id', $child_id);
        $q = $this->db->get();
        return $q->row_array();
    }
    /*get files for sub category*/
    function get_child_files($child_id){
        $url = base_url('images/child_static_content/');
        $this->db->select('id, child_id , CONCAT("'.$url.'",file_name) as file_name');
        //$this->db->where('child_id', $child_id);
        $q = $this->db->get('child_page_file');
        return $q->result_array();
    }
    /*end child static content*/  
}
