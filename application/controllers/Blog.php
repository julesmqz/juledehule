<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends JH_Controller
{
    protected $_siteTitle = 'Blog';

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
    public function index($page = 1)
    {
        $this->_siteTitle = 'Inicio';
        $this->_customScripts[] = 'mainSlider';
        $this->_preparePaginationData($page, '/inicio/index', $this->mpost->getTotalPages(), true);
        $posts = $this->mpost->getPage($page);
        foreach ($posts as &$post) {
            $co = $post['created_on'];
            $uo = $post['updated_on'];
            $post['created_on'] = date('d/M/Y', strtotime($co));
            $post['created_on_utc'] = date('d-m-Y H:i:s', strtotime($co));
            $post['updated_on'] = date('d/M/Y', strtotime($uo));
            $post['updated_on_utc'] = date('d-m-Y H:i:s', strtotime($uo));
        }
        $this->_contentData = array('posts' => $posts);
        $this->_shareMetadata[] = array(
            'url' => 'http://www.juledehule.com.mx/inicio',
            'type' => 'blog',
            'description' => 'Poemas, entradas y pensamientos importantes para el autor. Todo subjetivo ¿Encontró lo que buscaba, jóven?',
            'image' => 'http://assets.juledehule.com.mx/img/logo_vertical.png',
            'title' => 'Inicio. Un espacio para la experimentación y exploración',
        );

        $this->_loadView();
    }

    public function post($url = '')
    {
        $this->_contentFragment = 'post.html';
        $post = $this->mpost->getByUrl($url);
        $prevPost = $this->mpost->getPrevNextPost($post['created_on'],'<');
        $nextPost = $this->mpost->getPrevNextPost($post['created_on']);

        $co = $post['created_on'];
        $uo = $post['updated_on'];
        $post['created_on'] = date('d/M/Y', strtotime($co));
        $post['created_on_utc'] = date('d-m-y H:i:s', strtotime($co));
        $post['updated_on'] = date('d/M/Y', strtotime($uo));
        $post['updated_on_utc'] = date('d-m-y H:i:s', strtotime($uo));
        $post['share_desc'] = str_replace('-','+',$post['pretty_url']);
        $post['share_metadata'] = str_replace('-','+',$post['pretty_url']);

        $post['prevPost'][] = $prevPost;
        $post['nextPost'][] = $nextPost;
        $this->_contentData['entry'][] = $post;


        if (!isset($this->_contentData['entry'][0]['title'])) {
            show_404();
        } else {
            $this->_shareMetadata[] = array(
                'url' => 'http://www.juledehule.com.mx/'.$post['pretty_url'].'.html',
                'type' => 'article',
                'description' => strip_tags($post['summary']),
                'image' => $post['main_img'],
                'title' => $post['title'],
            );
            $this->_siteTitle = $post['title'];
            $this->_loadView(true, true);
        }

    }
}
