<?php

$code = $_GET['code'];
if( $code ){
    $options = array(
        'client_id' => 'ee182ed46ad8493fb804a9b41f843d0e',
        'client_secret' => 'e7f36c104ac44fd58003ad321841938b',
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'http://jh.localhost/instalog.php',
        'code' => $code,
    );
    
    $s = curl_init();
    
    curl_setopt($s, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
    curl_setopt($s, CURLOPT_TIMEOUT, 60);
    curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($s,CURLOPT_POST,true); 
    curl_setopt($s,CURLOPT_POSTFIELDS,$options); 
    
    $res = curl_exec($s);
    $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
    curl_close($s);
    
    print_r(json_decode($res));

    // Access token
    // 1507875.ee182ed.49af7d691d814d3ab222da444054147a

    //https://api.instagram.com/v1/users/self/media/recent/?access_token=1507875.ee182ed.49af7d691d814d3ab222da444054147a&count=12
}


// curl -F 'client_id=CLIENT_ID' \
//     -F 'client_secret=CLIENT_SECRET' \
//     -F 'grant_type=authorization_code' \
//     -F 'redirect_uri=AUTHORIZATION_REDIRECT_URI' \
//     -F 'code=CODE' \
//     https://api.instagram.com/oauth/access_token
