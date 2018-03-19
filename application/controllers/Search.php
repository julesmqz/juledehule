<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search extends JH_Controller
{
	protected $_siteTitle = 'Search';
	
	public function __construct(){
		parent::__construct();
		$this->mpost->changeItemsPerPage(20);
		$this->_customScripts[] = 'search';
		$this->_contentFragment = 'search.html';
	}

    public function index()
    {
        $this->load->view('splash');
	}
	
	public function value($string,$page=1)
	{
		$this->load->helper('url');
		$values = explode(':',$string);
		if( count($values) > 0 && method_exists($this,$values[0]) && $values[0] != 'value'){
			redirect('/busqueda/'.$values[0].'/'.$values[1]);
		}

		$string = str_replace('-',' ',$string);

		$posts = $this->mpost->searchByString($string, $page);
        foreach ($posts as &$post) {
            $co = $post['created_on'];
            $uo = $post['updated_on'];
            $post['created_on'] = date('d/M/Y', strtotime($co));
            $post['created_on_utc'] = date('d-m-Y H:i:s', strtotime($co));
            $post['updated_on'] = date('d/M/Y', strtotime($uo));
            $post['updated_on_utc'] = date('d-m-Y H:i:s', strtotime($uo));
        }

		$this->_preparePaginationData($page,'/busqueda/texto/'.$string,$this->mpost->getTotalPagesByString($string),true);
        $this->_contentData = array(
			'posts' => $posts,
			'search_value' => $string
		);
		$this->_shareMetadata = array(
			'url' => 'http://www.juledehule.com.mx/busqueda/texto/'.$string,
			'type' => 'search',
			'description' => 'Resultados de la búsqueda para '.$string,
			'image' => 'http://assets.juledehule.com.mx/img/logo_vertical.png',
			'title' => 'Resultados de la búsqueda para '.$string,
		);
		
		$this->_loadView(false, true);
	}

    public function tag($tag, $page = 1)
    {
		$this->_preparePaginationData($page,'/busqueda/tag/'.$tag,$this->mpost->getTotalPagesByTag($tag),true);
		
		$posts = $this->mpost->searchByTag($tag, $page);
        foreach ($posts as &$post) {
            $co = $post['created_on'];
            $uo = $post['updated_on'];
            $post['created_on'] = date('d/M/Y', strtotime($co));
            $post['created_on_utc'] = date('d-m-Y H:i:s', strtotime($co));
            $post['updated_on'] = date('d/M/Y', strtotime($uo));
            $post['updated_on_utc'] = date('d-m-Y H:i:s', strtotime($uo));
        }
		
		$this->_contentData = array(
			'posts' => $posts,
			'search_value' => 'tag:'.$tag
		);

		$this->_shareMetadata = array(
			'url' => 'http://www.juledehule.com.mx/busqueda/tag/'.$tag,
			'type' => 'search',
			'description' => 'Resultados de la búsqueda por tag '.$tag,
			'image' => 'http://assets.juledehule.com.mx/img/logo_vertical.png',
			'title' => 'Resultados de la búsqueda por tag '.$tag,
		);

        $this->_loadView(false, true);
    }
}
