<?php
function social_login_authUrl() {
    $CI =& get_instance();
    $CI->load->library('Facebook');
    include_once APPPATH . "libraries/google-api-php-client/Google_Client.php";
    include_once APPPATH . "libraries/google-api-php-client/contrib/Google_Oauth2Service.php";

    //create google login and registration authentication url..
    $clientId = '660527758915-f8dn9hdbvl61qgg57uls69g6k94832fj.apps.googleusercontent.com';
    $clientSecret = 'r2v53qZLBm9dbtAqQM77EI_I';
    $redirectUrl = site_url('web_panel/google_authentication');
    $gClient = new Google_Client();
    $gClient->setApplicationName('Login to codexworld.com');
    $gClient->setClientId($clientId);
    $gClient->setClientSecret($clientSecret);
    $gClient->setRedirectUri($redirectUrl);
    $google_oauthV2 = new Google_Oauth2Service($gClient);
    $data['google_authUrl'] = $gClient->createAuthUrl();
    //create facebook login and registration authentication url..
    $data['facebook_authUrl'] = $CI->facebook->login_url();
    return ($data) ? $data : false;
}
