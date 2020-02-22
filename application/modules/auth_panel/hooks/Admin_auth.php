<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_auth {
	function __construct()
    {
        $this->CI =& get_instance();

        if(!isset($this->CI->session)){  //Check if session lib is loaded or not
              $this->CI->load->library('session');  //If not loaded, then load it here
        }
    }
	
	public function index() {
		$perm =  $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();

		if($this->CI->router->fetch_module() == 'auth_panel') {
			$session_userdata = $this->CI->session->userdata();
			
			if(isset($session_userdata['active_backend_user_id'])) {
				$active_user_id = $session_userdata['active_backend_user_id'];
				$query = $this->CI->db->query("SELECT bup.id FROM `backend_user_permission` as bup WHERE bup.permission_perm = '$perm' ");

				//SELECT pg.permission_fk_id FROM backend_user_role_permissions as burps left join permission_group as pg on pg.id = burps.permission_group_id where burps.user_id = 23
				$result = $query->row_array();
				//print_r($result);die;
			  	$user_permsn = 	$this->CI->db->query("SELECT pg.permission_fk_id FROM backend_user_role_permissions as burps left join permission_group as pg on pg.id = burps.permission_group_id where burps.user_id = $active_user_id ");

				$user_p_result =  $user_permsn->row_array();
				//echo "<pre>";print_r($user_p_result);die;
				$user_p_result = explode(',',$user_p_result['permission_fk_id']);

				if($result['id'] != "" && !in_array($result['id'],$user_p_result)) {
					redirect(site_url('auth_panel/Auth_panel_ini/not_authorize'));
				}
			}
		}
	}
}