<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  if( !function_exists('page_alert_box') ) {

    function page_alert_box( $type = '',$title = '',$message = '') {
			$_SESSION['page_alert_box_type'] = $type;
			$_SESSION['page_alert_box_title'] = $title;
			$_SESSION['page_alert_box_message'] = $message;
    }
	}
	
	if (!function_exists('milliseconds')){
		function milliseconds() {
				$mt = explode(' ', microtime());
				return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
		}
	}

