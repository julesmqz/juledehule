<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rest extends CI_Controller
{
    protected $_response = array('msg' => 'No message', 'status' => 200);

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tag_model', 'mtags');
        $this->load->model('post_model', 'mpost');
        $this->load->model('social_model', 'msocial');
    }

    public function addTag()
    {
        $tag = $this->input->post('tag');
        if (!is_null($tag) && !empty($tag)) {
            $r = $this->mtags->add($tag);
            if ($r) {
                $this->_response['status'] = 200;
                $this->_response['msg'] = 'Saved';
            } else {
                $this->_response['status'] = 200;
                $this->_response['msg'] = 'Could not save';
            }
        } else {
            $this->_response['status'] = 401;
            $this->_response['msg'] = 'Cannot access without posting something';
        }

        echo json_encode($this->_response);
    }

    public function dashboard()
    {
        $this->_response['status'] = 200;
        $this->_response['msg'] = 'Here have some data';
        $this->_response['data'] = array(
            'tags' => $this->mtags->getTotal(), 
            'posts' => $this->mpost->getTotal(), 
            'slider' => 9, 
            'footer' => 1
        );

        echo json_encode($this->_response);
    }

    public function getTags(){
        $this->_response['status'] = 200;
        $this->_response['msg'] = 'Here have some data';
        $this->_response['data'] = $this->mtags->getAll();

        echo json_encode($this->_response);
    }
}
