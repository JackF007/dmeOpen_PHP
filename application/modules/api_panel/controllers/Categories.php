<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//////////////////////////////////
//                              //
//   Author : Shashank Mishra   //
//   date : 18 / sept / 2018    //
//                              //
//////////////////////////////////


class Categories extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper("aul");
        $this->load->helper("array");
        $this->load->model("CategoriesModel");
        $this->load->helper("services");
        $this->load->library(array("session", "rssparser", "form_validation"));
    }

    /* categories list */

    function categories_list() {
        $res = $this->CategoriesModel->get_category();
        if ($res) {
            return_data(true, "categories list!", $res);
        }
        return_data(false, "Categories list not found!!", blank_json());
    }

    /* end categories list */

    /* sub category list */
    
    function get_sub_category() {
        if(!isset($_POST['category_id'])){
            echo json_encode(array('status'=>false, 'message'=>"category_id field is required!!", 'data'=>blank_json()));
            die;
        }
        $category_id=$this->input->post('category_id');
        if(isset($_POST['last_count'])){
           $last_count = $_POST['last_count'];
        }else{
            $last_count = 0;
        }
        if($category_id==1 || $category_id==2 || $category_id==3 || $category_id==4){
            if($category_id==1){
                $table='fic_category';
            }
            if($category_id==2){
                $table='comm_category';
            }
            if($category_id==3){
                $table='competition_category';
            }
            if($category_id==4){
                $table='opendata_category';
            }
            $res = $this->CategoriesModel->get_subcategories($category_id,$table,$last_count);
            if (!empty($res)) {
                $i=0;
                foreach($res as $r){
                    $res[$i]['root_id']=$category_id;
                    $i++;
                }
                echo json_encode(array('status'=>true, 'message'=>"Sub Categories list!", 'data'=>$res));
                die;
            }
            echo json_encode(array('status'=>false, 'message'=>"Sub Categories list not found!!", 'data'=>array()));
            die;
            
        }else{
            echo json_encode(array('status'=>false, 'message'=>"category_id is not valid!!", 'data'=>blank_json()));
        }
    }
    
    function get_sub_category_child() {
        if(!isset($_POST['root_id'])){
            echo json_encode(array('status'=>false, 'message'=>"root_id field is required!!", 'data'=>blank_json()));
            die;
        }
        if(!isset($_POST['category_id']) || $_POST['category_id']==''){
            echo json_encode(array('status'=>false, 'message'=>"category_id field is required!!", 'data'=>blank_json()));
            die;
        }
        $root_id=$_POST['root_id'];
        $category_id=$this->input->post('category_id');
         if(isset($_POST['last_count'])){
           $last_count = $_POST['last_count'];
        }else{
            $last_count = 0;
        }
        if($root_id==1 || $root_id==2 || $root_id==3 || $root_id==4){
            if($root_id==1){
                $table='fic_category';
            }
            if($root_id==2){
                $table='comm_category';
            }
            if($root_id==3){
                $table='competition_category';
            }
            if($root_id==4){
                $table='opendata_category';
            }
            $res = $this->CategoriesModel->get_subcategories_child($category_id,$table,$last_count);
            if (!empty($res)) {
                $i=0;
                foreach($res as $r){
                    $res[$i]['root_id']=$root_id;
                    $i++;
                }
                echo json_encode(array('status'=>true, 'message'=>"Sub Categories child list!",'data'=> $res));
                die;
            }
            echo json_encode(array('status'=>false, 'message'=>"Sub Categories child not found!!", 'data'=>array()));
            die;
            
        }else{
            echo json_encode(array('status'=>false, 'message'=>"root_id is not valid!!", 'data'=>blank_json()));
            die;
        }
    }

    function sub_category_list() {
        $input = $this->validate_sub_category_list();
        $res = $this->CategoriesModel->get_subcategory_list($input['category_id']);
        if ($res) {
            return_data(true, "Sub Categories list!", $res);
        }
        return_data(false, "Sub Categories list not found!!", blank_json());
    }

    private function validate_sub_category_list() {
        post_check();
        $this->form_validation->set_rules("category_id", "Category Id", "trim|required");
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }

    /* end sub category list */

    /* child category list */

    function child_category() {
        $input = $this->validate_child_category();
        $res = $this->CategoriesModel->get_child_category($input['sub_cat_id']);
        if ($res) {
            return_data(true, 'Child categories Found!', $res);
        }
        return_data(false, 'Child Categories not found!', array());
    }

    private function validate_child_category() {
        post_check();
        $this->form_validation->set_rules('sub_cat_id', 'Sub Category Id', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        }
        return $this->input->post();
    }

    /* end child category list */

    /* show static pages */

    function static_pages() {
        post_check();
        $this->form_validation->set_rules('sub_cat_id', 'Sub Category Id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        } else {
            $res = $this->CategoriesModel->get_static_content($this->input->post('sub_cat_id'));
            $files = $this->CategoriesModel->get_sub_category_files($this->input->post('sub_cat_id'));

            if ($res) {
                $res = array(
                    'sub_cat_id' => $res['sub_category_id'],
                    'title' => $res['sub_category_name'],
                    'content' => $res['content'],
                    'created_date' => $res['created_date'],
                    'update_date' => $res['update_date'],
                    'files' => $files
                );
                return_data(true, 'Static Page available!', $res);
            } else {
                return_data(false, 'Static Page not found!', blank_json());
            }
        }
    }

    function get_rss_data1() {
        post_check();
        $this->form_validation->set_rules('sub_cat_id', 'Sub Category Id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        } else {
            $res = $this->CategoriesModel->get_static_content($this->input->post('sub_cat_id'));

            if ($res) {
                $res = array(
                    'sub_cat_id' => $res['sub_category_id'],
                    'title' => $res['sub_category_name'],
                    'content' => $res['content'],
                    'created_date' => $res['created_date'],
                    'update_date' => $res['update_date']
                );
                if ($res['content'] != '') {
                    $this->rssparser->set_feed_url($res['content']);  // get feed
                    $this->rssparser->set_cache_life(30);                       // Set cache life time in minutes
                    $rss = $this->rssparser->getFeed(6);
                    $res['data'] = $rss;
                } else {
                    $res['data'] = blank_json();
                }
                return_data(true, 'Static Page available!', $res['data']);
            } else {
                return_data(false, 'Static Page not found!', blank_json());
            }
        }
    }
    function get_rss_data() {
        post_check();
        $blank=[];
        $this->form_validation->set_rules('root_id', 'root_id', 'required');
        $this->form_validation->set_rules('category_id', 'category_id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], $blank);
        } else {
            $res = $this->CategoriesModel->get_rss_link($this->input->post('root_id'),$this->input->post('category_id'));

            if ($res) {
                
                if ($res['link'] != '') {
                    $this->rssparser->set_feed_url($res['link']);  // get feed
                    $this->rssparser->set_cache_life(30);                       // Set cache life time in minutes
                    $rss = $this->rssparser->getFeed(6);
                    $res['data'] = $rss;
                } else {
                    $res['data'] = $blank;
                }
                return_data(true, 'RSS data available!', $res['data']);
            } else {
                return_data(false, 'RSS data not found!', $blank);
            }
        }
    }

    /* end static pages */

    /* start child static page */

    function child_static_page() {
        post_check();
        $this->form_validation->set_rules('child_id', 'Child Id', 'required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            return_data(false, array_values($error)[0], blank_json(), $error);
        } else {
            $res = $this->CategoriesModel->get_child_static_content($this->input->post('child_id'));
            $files = $this->CategoriesModel->get_child_files($this->input->post('child_id'));
            if ($res) {
                $res = array(
                    'child_id' => $res['child_id'],
                    'title' => $res['child_name'],
                    'content' => $res['child_content'],
                    'created_date' => $res['created_date'],
                    'update_date' => $res['update_date'],
                    'files' => $files
                );
                return_data(true, 'Child Static Page available!', $res);
            } else {
                return_data(false, 'Child Static Page not found!', blank_json());
            }
        }
    }

    /* end child static page */
}
