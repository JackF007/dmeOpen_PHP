<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_uploader extends MX_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper("services");
	}

	public function upload_file(){
		if($_FILES){
			if ($_FILES["file"]["size"] > 1000000*50) {
			    return_data(false,'Sorry, your file is too large. size should below 50mb',array());
			}
			//file name 
			$filename = time().rand().$_FILES["file"]["name"];
			$file_path = $_SERVER['DOCUMENT_ROOT'].'/survey/images/'.$filename;

			if(move_uploaded_file($_FILES['file']["tmp_name"], $file_path)){
				//echo "file uploaded";
			}else{
				return_data(false,'Server issue not able to upload file.',array());
			}
			
		}else{
			return_data(false,'Not able to upload file.',array());
		}
	}
}
