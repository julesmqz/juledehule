<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends JH_Controller
{
    protected $_siteTitle = 'Login';

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/home
     *    - or -
     *         http://example.com/index.php/home/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/home/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->_logout();
        $post = $this->input->post(null, true);
        if (count($post) > 0) {
            $id = $this->_login($post);
            if( $this->session->user > 0 ){
                redirect('admin/categories');
            }
        }

        $output = array('output' => $this->parser->parse('template/login.html', [], true));
        $this->_loadAdmin($output);
    }
}
