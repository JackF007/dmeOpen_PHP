<?php

function send_sms($inputs) { //die('working62');
    $url = "http://appsquadz.online/daps/awssms/index.php?mobile=".$inputs['mobile']."&message=".urlencode($inputs['message']);
    file_get_contents($url);
    
    return true;
}


