<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if(!function_exists('milliseconds')){
    function milliseconds() {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) *  1000 + ((int)round($mt[0] * 1000));
        
    }
}

if (!function_exists('pre')) {

    function pre($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

}

if(!function_exists('is_valid_rss_url')){
    function is_valid_rss_url($url){
        if (!isset($rawFeed))
        {
                $rawFeed = file_get_contents($url);
        }

        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($rawFeed);
        $xml1 = explode("\n", $rawFeed);

        if (!$doc) {
            $errors = libxml_get_errors();
        }
        if(empty($errors)){
            $xml = new SimpleXmlElement($rawFeed);
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
if (!function_exists('is_json')) {

    function is_json($string, $return_data = false) {
        $data = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
    }

}


if (!function_exists('send_sms')) {

    function send_sms($data) { //mobile and message. 
        //echo "working"; die; 
        return true;
        /*
          $mobile = $data['mobile'];
          $message = $data['message'];
          require_once(APPPATH . 'third_party/twilio-php/Services/Twilio.php');
          $account_sid = 'ACa7cc64d4263494e79217bf0b65427092';
          $auth_token = '262b0bf82523d785055239f9b7c5d252';
          $client = new Services_Twilio($account_sid, $auth_token); //echo  $mobile;
          $message = $client->account->messages->create(array(
          'To' => $mobile,
          'From' => "+18583338842",
          'Body' => $message));
          if ($message->sid != '') {
          return true;
          } else {
          return false;
          }
         */
    }

}

if (!function_exists('check_inputs')) {

    function check_inputs() {
        //pre($this->input->post());
        $json = file_get_contents('php://input');
        if ($json) {
            return ((array) json_decode($json));
        } else if ($_POST) {
            //echo'dddd'.pre($_POST);
            return $_POST;
        } else {
            //pre($_POST);
            echo json_encode(array("status" => false, "message" => "Invalid Input", "Result" => array()));
        }
    }

}

if (!function_exists('create_log_file')) {

    function create_log_file($data) { //mobile and message.  
        $log = "Time: " . date('Y-m-d, H:i:s') . PHP_EOL;
        $log = $log . "url: " . base_url($data['url']) . PHP_EOL;
        $log = $log . "Request " . json_encode($data['request']) . PHP_EOL;
        $log = $log . "Responce " . json_encode(array("Result" => $data['response'])) . PHP_EOL . PHP_EOL;
        file_put_contents('logs/' . $data['url'] . '.txt', $log, FILE_APPEND);
    }

}

if (!function_exists('return_data')) {

    function return_data($status = false, $mesage = "", $result = array()) { //mobile and message.         
        echo json_encode(array("status" => $status, "message" => $mesage, "Result" => $result));
        die;
    }

}


if (!function_exists('file_curl_contents')) {

    function file_curl_contents($document) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $document['file_url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        unset($document['file_url']);
        //pre($document); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $document);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        //pre($server_output); 
        curl_close($ch);
        $data = json_decode($server_output, true);
        //pre($data); 
        return $data;
    }

}