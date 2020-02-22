<?php
if(!function_exists(base_url)){
    function base_url($url){
        if($url!=''){
            $url='http://13.232.34.21/privacy_policy/'.$url.'.php';
        }else{
            $url='http://13.232.34.21/privacy_policy/'.$url;
        }
        return $url;
    }
}