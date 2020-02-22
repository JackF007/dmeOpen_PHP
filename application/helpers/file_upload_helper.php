<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('upload_image_base64')) {
    function upload_image_base64($array) { 
        $data       = str_replace(" ", "+", $array['image']);
        $data       = base64_decode($data);
        $im         = imagecreatefromstring($data);
        $fileName   = $array['name']; //rand(111, 999).time() . ".png";
        $imageName  = $array['path'] . $fileName;
        if ($im !== false) {
            imagepng($im, $imageName);
            imagedestroy($im);
        } else {
            echo 'An error occurred.';
        }
        return $fileName;
    }
}

if (!function_exists('upload_image')) {
	
    function upload_image($array) {
		
	$ci =& get_instance();
	$config['upload_path'] 	= $array['upload_path'];
	$config['allowed_types'] = 'jpg|png';
	$config['max_size'] 		= 1024 * 8;
	$config['encrypt_name'] 	= TRUE;
	$ci->load->library('upload', $config);
		if($ci->upload->do_upload($array['key'])){
			$uploadData = $ci->upload->data();
			$uploadedFile = $uploadData['file_name'];
			return $uploadedFile;
		}
		return false;
	}	
}



if (!function_exists('upload_multiple_images_for_app')) {
    function upload_multiple_images_for_app($files,$path) { pre($files); die;//pre($path); 
        $uploaded = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] == 0) {
                $file_name = trim($files["name"][$i]); //pre($file_name); 
                $target_file = $path . $file_name; //pre($target_file); 
                if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
                }
                $uploaded[] = $file_name;
            }
        } 
        return $uploaded;
    }
}

