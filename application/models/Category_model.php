<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Category_model extends CI_Model
{

    public function getPopular()
    {
        return []; 

        $this->db->select('tag.*,count(pht.post_id) as total');
        $this->db->join('post_has_tag pht', 'ON tag.id = pht.tag_id');
        $this->db->group_by('pht.tag_id');
        $this->db->order_by('total', 'desc');
        $this->db->limit(5, 0);

        $q = $this->db->get('tag');

        return $q->result_array();
    }

    public function add($tag)
    {
        $data = array(
            'name' => $tag,
            'friendly_url' => $this->_cleanTag($tag),
        );
        

        $this->db->set($data);
        return $this->db->insert('tag');
    }

    public function getTotal()
    {
        

        return $this->db->count_all('tag');
    }

    protected function _cleanTag($tag)
    {
        $tag = str_replace(' ', '-', $tag);
        $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
        $tag = strtr($tag, $unwanted_array);
        $tag = strtolower($tag);

        return $tag;
    }

    public function getList(){
        
        $this->db->order_by('name','ASC');
        $q = $this->db->get('category');

        $r = $q->result_array();

        return $r;
    }

    public function addToPost($postId,$tags){
        $cont = 0;
        $data = array_map(function($tag) use ($postId,&$cont){
            $r = array('post_id' => $postId,'tag_id' => $tag, 'main' => $cont == 0);
            $cont++;
            return $r;
        },$tags);

        
        return $this->db->insert_batch('post_has_tag',$data);
    }
}
