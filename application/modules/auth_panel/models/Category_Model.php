<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_Model extends CI_Model{

    function __construct()
    {
        parent::__construct();
       $this->load->library('upload');
    }

//=================================== CREATE CATEGORY ==========================================//

    public function create_category($id)
    {
        $files = $_FILES['sub_category_logo']['name'];
        $ext = pathinfo($files, PATHINFO_EXTENSION);
        if(!empty($files)){
            $syllabusName = str_replace(" ","_", $this->input->post('sub_category_name'));
            $_FILES['sub_category_logo']['name']= $syllabusName.'.'.$ext;
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
            $sub_category_logo = $_FILES['sub_category_logo']['name'];
            $data['sub_category_logo'] = $sub_category_logo;
        }

      $data['category_id'] = $id;
      $data['sub_category_name'] = $this->input->post('sub_category_name');
      $result = $this->db->insert("sub_category_list",$data);
      return $result;
    }

//===============================================================================================//

//=================================== GET ALL CATEGROY ==========================================//

    public function get_category_data($id)
    {

      $result = $this->db->select('*')
                         ->where('category_id',$id)
                         ->get("category")->row_array();
  //  print_r($result);die();
      return $result;
    }

//===============================================================================================//

    //=================================== GET SUBCATEGROY ==========================================//

    public function get_subcategory_data($id)
    {

      $result = $this->db->select('*')
                         ->where('sub_category_id',$id)
                         ->get("sub_category_list")->row_array();
  //  print_r($result);die();
      return $result;
    }

//===============================================================================================//


//=================================== UPDATE CATEGORY ===========================================//

    public function update_category($id,$cat_id)
    {

     $sub_category_name = str_replace(" ","_", $this->input->post('sub_category_name'));
        if(!empty($_FILES['sub_category_logo']['name'])){
            $simage = $this->db->get_where('sub_category_list', array('sub_category_status'=>'0', 'sub_category_id'=>$id))->row()->sub_category_logo;
            if(!empty($simage)){
                $file = getcwd()."/images/sub_category/".$simage;
                if (file_exists($file)){
                    unlink($file);
                    $data['sub_category_logo'] = $this->imageupload();
                } else{
                    $data['sub_category_logo'] = $this->imageupload();
                }
            }
        } else{
            $sname = $this->db->get_where('sub_category_list', array('sub_category_status'=>'0','category_id'=>$cat_id, 'sub_category_id'=>$id))->row()->sub_category_logo;
            if(!empty($sname)){
                $ext = pathinfo($sname, PATHINFO_EXTENSION);
                $syllabusName = str_replace(" ","_", $this->input->post('sub_category_name'));
                $a = $syllabusName.".".$ext;
                $image_url = base_url('images/sub_category');
                rename($image_url.$sname, $image_url.$a);
                $data['sub_category_logo'] = $a;
            }
        }

        $data['sub_category_name'] = $this->input->post('sub_category_name');

        $this->db->where(array('sub_category_id'=>$id,'category_id'=>$cat_id, 'sub_category_status'=>'0'));
        $result = $this->db->update("sub_category_list",$data);

        return $result;
    }

//===============================================================================================//

//=================================Image Upload in Folder =======================================//

    public function imageupload(){

        $files = $_FILES['sub_category_logo']['name'];

        $config['upload_path'] = './images/sub_category/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '1048576';
        $config['remove_spaces'] = true;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('sub_category_logo')){
            $sub_category_logo = $this->upload->data('file_name');
            return $sub_category_logo;
        }
        else
        {
             $error = array('error' => $this->upload->display_errors(),'path'=>$this->upload->data('full_path'));
        }
    }

//================================================================================================//

//=================================== DELETE CATEGORY ===========================================//

    public function delete_category($cat_id,$sub_cat_id)
    {
      $res=false;
      $this->db->where('sub_category_id',$sub_cat_id);
      $sub_cat_del=$this->db->delete('sub_category_list');
      if($sub_cat_del){
        $this->db->where('sub_category_id',$sub_cat_id);
        $this->db->delete('static_content');
        $res=true;
      }
      return true;
    }

//===============================================================================================//

//=================================== BLOCK CATEGORY ===========================================//

    public function block_category($id,$status)
    {

      $data = array('sub_category_status' =>$status);
      $this->db->where('sub_category_id',$id);
      $result = $this->db->update("sub_category_list",$data);

        //block child static
        $data = array('status' =>$status);
      $this->db->where('sub_category_id',$id);
      $result = $this->db->update("static_content",$data);


    }

//===============================================================================================//

    public function get_title($sub_cat){
      $result = $this->db->get_where('sub_category_list',array('sub_category_id'=>25))->row('sub_category_name');
      //echo $result;die;
      return $result;
    }


    // public function get_static_data($id){
    //   $result=[];
    // //  $i;
    //
    //   //$this->db->select('*');
    //   //$this->db->join('page_file','static_content.sub_category_id=page_file.sub_category_id');
    //                     // $this->db->join('static_content', 'static_content.sub_category_id = page_file.sub_category_id');
    //   //$this->db->where('static_content.sub_category_id',$id);
    //   $result =$this->db->get_where("static_content",array('sub_category_id'=>$id))->row_array();
    //   $this->db->select('file_name');
    //   $this->db->where('sub_category_id',$id);
    //   $result['image']=$this->db->get('page_file')->result_array();
    //     //$i++;
    //
    //
    //         return $result;
    // }
    // public function get_static_content($sub_cat){
    //     $this->db->where('sub_category_id',$sub_cat);
    //     $res=$this->db->get('static_content');
    //     //pre($res);die;
    //     //echo $res->num_rows();
    //     return $res->row_array();
    // }


    public function get_static_content($sub_cat)
    {

      $this->db->select('*');
      $this->db->from('page_file');
      $this->db->join('static_content', 'static_content.sub_category_id = page_file.sub_category_id');
      $this->db->where('static_content.sub_category_id', $sub_cat);
      $res = $this->db->get();
      // $this->db->where('sub_category_id',$sub_cat);
      // $res=$this->db->get('static_content');
      // pre($res);die;
      //echo $this->db->last_query();
       return $res->row_array();

    }
    
    public function get_rss_content($sub_cat)
    {

       $this->db->where('sub_category_id',$sub_cat);
       $res=$this->db->get('static_content');
      // pre($res);die;
      //echo $this->db->last_query();
       return $res->row_array();

    }
    
    public function get_rss_contents($root_id,$cat_id)
    {

       $this->db->where('root_id',$root_id);
       $this->db->where('category_id',$cat_id);
       $res=$this->db->get('rss_link');
      // pre($res);die;
      //echo $this->db->last_query();
       return $res->row_array();

    }


    public function insert_data($data){
    // pre($data);die;
		$result = $this->db->insert("admin_post",$data);
    $id['feed_id'] =$this->db->insert_id();
    return $id;
	}

  public function insert_data1($data){
      $array['feed_id'] =$data['feed_id'];
      $array['name'] =implode(",",$data['name']);
      $result = $this->db->insert("open_data_files",$array);
    return  $result;
}

public function get_static_content1($child_id)
{
    $this->db->select('*');
    $this->db->from('child_page_file');
    $this->db->join('child_static_content', 'child_static_content.child_id = child_page_file.child_id');
    $this->db->where('child_static_content.child_id', $child_id);
    $res = $this->db->get();
    return $res->row_array();
}


public function all_data($data){

    $array['sub_category_id'] =$data['sub_category_id'];
    $array['category_id'] =$data['category_id'];
    $array['child_name'] =$data['child_name'];
    $array['child_logo'] =$data['child_logo'];
  
$result = $this->db->insert("child_category",$array);
return $result;
}

}
