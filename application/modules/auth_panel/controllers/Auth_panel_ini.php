<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_panel_ini extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('template');
	}

  public function  auth_ini(){
      /* default template path */
      if(!defined('AUTH_TEMPLATE')){
        define("AUTH_TEMPLATE", "auth_panel/template/");
      }

      /* default template conatant name  */
      if(!defined('AUTH_DEFAULT_TEMPLATE')){
        define("AUTH_DEFAULT_TEMPLATE","auth_panel/template/call_default_template");
      }

      /* default auth panel assets path  */
      if(!defined('AUTH_PANEL_URL')){
        define("AUTH_PANEL_URL",base_url().'index.php/auth_panel/');
      }

      /* default auth files assets path */
      if(!defined('AUTH_ASSETS')){
        define("AUTH_ASSETS", base_url()."auth_panel_assets/");
      }

       $active_backend_user_flag =  $this->session->userdata('active_backend_user_flag');
       $active_backend_user_id   =  $this->session->userdata('active_backend_user_id');

      /* if ajax request and session is not set
      if ($this->input->is_ajax_request() && $active_backend_user_flag != True ){
        echo json_encode(array('status'=>false,'error_code'=>10001,'message'=>'Authentication Failure'));
        die;
      }
      */

      if(!$this->input->is_ajax_request() && $active_backend_user_flag != True ){
        redirect(site_url('auth_panel/login/index'));
        die;
      }


      if(!defined('WEB_PANEL_URL')){
        define("WEB_PANEL_URL",base_url().'index.php/web_panel/');
      }

    }

    public function not_authorize() {
      modules::run('auth_panel/auth_panel_ini/auth_ini');
      $data['page_data'] = $this->load->view('template/not_authorize',array(), TRUE);
      echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
}
