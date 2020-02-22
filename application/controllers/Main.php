<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

  public function index()
  {
    $this->load->view('image');
  }
  
  public function ajax_upload()  
  {  
    if(isset($_FILES["image_file"]["name"]))  
    {          
      $config['upload_path'] = './uploads/';  
      $config['allowed_types'] = 'jpg|jpeg|png|gif';  
      $this->load->library('upload'); 
      $this->upload->initialize($config); 
      $this->upload->do_upload('image_file');
      $data = $this->upload->data();
      $config1['image_library'] = 'gd2';  
      $config1['source_image'] = $data["full_path"];  
      $config1['create_thumb'] = TRUE;  
      $config1['maintain_ratio'] = TRUE;  
      $config1['quality'] = '100%';  
      $config1['width'] = '1349';  
      $config1['height'] = '500';  
      $this->load->library('image_lib',$config1);
      $this->image_lib->resize();
      if(!$this->image_lib->resize()){
           echo $this->image_lib->display_errors();  
      }    
    }  
  } 
}
