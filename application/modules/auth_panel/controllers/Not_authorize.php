<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Not_authorize extends MX_Controller {

	function __construct() {
		parent::__construct();
		/* !!!!!! Warning !!!!!!!11
		 *  admin panel initialization
		 *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
		 */
		$this->load->helper('aul');
		modules::run('auth_panel/auth_panel_ini/auth_ini');
		$this->load->library('form_validation', 'uploads');
		$this->load->model("Category_list_model");

	}


	public function index() {
		$data['page_title'] = "not authorize";
		$view_data['page']  = 'Not_authorize';
		
		$data['page_data'] = $this->load->view('template/Not_authorize', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}
}
