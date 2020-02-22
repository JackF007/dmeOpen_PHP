<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('sendPushNotificationToFCMSever')){
   function sendPushNotificationToFCMSever($message, $notifyID) {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $apikey = 'AIzaSyBC0AFaG3i4LZKBPs0Fdg07Xx72heDP1bA';
        $fields = array(
            'to' => "allDevices",
            'priority' => 10,
            'notification' => array('title' => 'CodeCastra', 'body' =>  $message ,'sound'=>'Default','image'=>'Notification Image' )
            
        );
        $headers = array(
            'Authorization:key='.$apikey  ,
            'Content-Type:application/json'
        );  
         
            // Open connection  
        $ch = curl_init(); 
            // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // Execute post   
        $result = curl_exec($ch); 
            // Close connection      
        curl_close($ch);
        return $result;
    } 
}
 