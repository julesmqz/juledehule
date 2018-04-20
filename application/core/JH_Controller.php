<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JH_Controller extends CI_Controller {

    protected $_siteTitle = 'Default';
    protected $_contentFragment = 'home.html';
    protected $_contentData = array();
    protected $_customScripts = array();
    protected $_dataPagination = array();
    protected $_maxPaginationShow = 5;
    protected $_versionScripts = '';
    protected $_shareMetadata = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tag_model', 'mtags');
        $this->load->model('post_model', 'mpost');
        $this->load->model('social_model', 'msocial');
        $this->_versionScripts = md5(date('Ymd').'2');
    }

	protected function _loadView($omitSidebar = false, $omitSlider = false)
    {
        $data['main_title'] = $this->_siteTitle;
        $data['main_description'] = 'Jule de Hule es un espacio de exploración y experimentación donde su autor puede expresar sus opiniones sin miedo.';
        $data['content'] = $this->_loadContent($omitSidebar);
        $data['main_slider'] = $this->_loadMainSlider($omitSlider);
        $data['instagram_footer'] = $this->_loadInstagramFooter();
        $data['custom_scripts'] = $this->_loadCustomScripts();
        $data['hash'] = $this->_versionScripts;
        $data['assets_url'] = ENVIRONMENT == 'production' ? 'http://assets.juledehule.com.mx' : '/assets';
        $data['share_metadata'] = $this->_shareMetadata;

        $this->parser->parse('template/main.html', $data);

    }

    protected function _loadContent($omitSidebar)
    {
        $data = $this->_contentData;
        $data['sidebar'] = $this->_loadSidebar($omitSidebar);
        $data['pagination'] = $this->_loadPagination();

        $content = $this->parser->parse("fragment/{$this->_contentFragment}", $data, true);

        return $content;
    }

    protected function _loadSidebar($omit)
    {
        if ($omit) {
            return null;
        } else {
            $data['tags'] = $this->mtags->getPopular();
            $sidebar = $this->parser->parse('fragment/sidebar.html', $data, true);

            return $sidebar;
        }
    }

    protected function _loadMainSlider($omit)
    {
        if ($omit) {
            return null;
        } else {
            $data['slider'] = $this->mpost->getMainSlider();
            $mainSlider = $this->parser->parse('fragment/slider.html', $data, true);

            return $mainSlider;
        }
    }

    protected function _loadInstagramFooter()
    {
        $data['pics'] = $this->msocial->getInstagramPics();
        $instaFoot = $this->parser->parse('fragment/instagram_footer.html', $data, true);

        return $instaFoot;
    }

    protected function _loadCustomScripts()
    {
        $todayHash = md5(date('Ymd'));
        $assetsUrl = ENVIRONMENT == 'production' ? 'http://assets.juledehule.com.mx' : '/assets';
        $scriptTag = '<script type="text/javascript" src="'.$assetsUrl.'/js/{script}.min.js?'.$todayHash.'"></script>';
        $scripts = '';
        foreach ($this->_customScripts as $script) {
            $scripts .= str_replace('{script}', $script, $scriptTag);
        }

        return $scripts;
    }

    protected function _loadPagination()
    {
        if( count( $this->_dataPagination ) > 0 ){
            $pagination = $this->parser->parse('fragment/pagination.html', $this->_dataPagination, true);
        }else{
            $pagination = null;
        }
        

        return $pagination;
    }

    protected function _preparePaginationData($currPage,$urlTemplate,$totalPages,$showPages){
        $pages = [];
        if( $showPages ){
            if( $currPage == 1){
                $initPage = $currPage;
                $limit = $currPage + $this->_maxPaginationShow;
            }else if( $currPage == $totalPages){
                $initPage = $currPage - $this->_maxPaginationShow;
                $limit = $currPage;
            }else{
                $initPage = $currPage - round($this->_maxPaginationShow/2);
                $limit = $currPage + round($this->_maxPaginationShow/2);
            }

            if( $limit > $totalPages){
                $limit = $totalPages;
            }

            if( $initPage < 1){
                $initPage = 1;
            }

            for( $i = $initPage; $i <= $limit; $i++){
                $pages[] = array(
                    'text' => $i,
                    'classes' => $i == $currPage ? 'disabled' : '',
                    'url' => $urlTemplate.'/'.$i
                );
            }
        }
        $this->_dataPagination = array(
			'pages' => $pages,
			'first_url' => $urlTemplate.'/1',
			'first_text' => 'Primera página',
			'first_classes' => $currPage == 1 ? 'disabled' : '',
			'last_url' => $urlTemplate.'/'.$totalPages,
			'last_text' => 'Última página',
			'last_classes' => ($currPage + 1) >= $totalPages ?	'disabled' : '' 
		);
    }
}
