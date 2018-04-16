<?php

class Insta_crawler extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load('instagram', true, true);
    }
    public function index()
    {
        $this->_writeConfig();
    }

    public function getAccessToken()
    {
        $s = curl_init();

        $insta_config = $this->config->item('instagram');

        curl_setopt($s, CURLOPT_URL, 'https://api.instagram.com/oauth/access_token');
        curl_setopt($s, CURLOPT_TIMEOUT, 60);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, $insta_config);

        $res = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);

        print_r(json_decode($res));
    }

    public function downloadPics(){
        $this->load->model('social_model', 'msocial');
        $s = curl_init();
        $insta_config = $this->config->item('instagram');
        $url = $insta_config['api_uri'].'users/self/media/recent?access_token='.$insta_config['access_token'].'&count=100';

        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_TIMEOUT, 60);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);

        //echo $url;
        //print_r(json_decode($res));

        $res = json_decode($res);

        $pics = array();
        foreach( $res->data as $d){
            if( $d->type == 'image'){
                $pics[] = array(
                    'id' => $d->id,
                    'uploaded_on' => $d->created_time,
                    'image' => $d->images->low_resolution->url,
                    'link' => $d->link
                );
            }
        }

        $this->msocial->insertInstaPics($pics);

        echo json_encode(array('status' => 200, 'url' => $url,'msg' => 'Saved latest instagram pics'));

    }

    protected function _writeConfig()
    {
        $insta_config = $this->config->item('instagram');
        $string_config = '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n";
        foreach ($insta_config as $k => $v) {
            $string_config .= '$config["' . $k . '"] = "' . $v . '"' . "\n";
        }

        //echo $string_config;
    }
}
