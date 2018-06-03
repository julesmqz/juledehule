<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Post_model extends CI_Model
{
    protected $_itemsPerPage = 6;

    public function getMainSlider()
    {
        $this->load->database(ENVIRONMENT);

        $this->db->select('post.pretty_url as link,post.title,image.path as image');
        $this->db->from('slider');
        $this->db->join('post', 'slider.post_id = post.id');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->limit(5, 0);
        $this->db->order_by('slider.position', 'ASC');

        $q = $this->db->get();

        $r = $q->result_array();

        return $r;

        return array(
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera', 'link' => '/el-escultor-de-madera.html'],
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera 1', 'link' => '/el-escultor-de-madera.html'],
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera 2', 'link' => '/el-escultor-de-madera.html'],
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera 3', 'link' => '/el-escultor-de-madera.html'],
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera 4', 'link' => '/el-escultor-de-madera.html'],
            ['image' => '/assets/img/entries/ian-schneider-91717.jpg', 'title' => 'El escultor de madera 5', 'link' => '/el-escultor-de-madera.html'],
        );
    }

    public function getPage($nrPage, $search = [], $useLike = false, $useOr = false)
    {
        $this->load->database(ENVIRONMENT);

        $this->db->select('post.*,tag.friendly_url,tag.name as main_tag,image.path as main_img');
        $this->db->from('post');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->join('post_has_tag as pht', 'post.id = pht.post_id AND pht.main');
        $this->db->join('tag', 'pht.tag_id = tag.id');
        $this->db->limit($this->_itemsPerPage, ($nrPage - 1) * $this->_itemsPerPage);
        $this->db->order_by('post.created_on', 'DESC');

        if (count($search) > 0) {
            $cont = 0;
            foreach ($search as $k => $v) {
                if ($cont == 0) {
                    if ($useLike) {
                        $this->db->like($k, $v);
                    } else {
                        $this->db->where($k, $v);
                    }
                } else {
                    if ($useOr && $useLike) {
                        $this->db->or_like($k, $v);
                    } elseif ($useOr) {
                        $this->db->or_where($k, $v);
                    } elseif ($useLike) {
                        $this->db->like($k, $v);
                    } else {
                        $this->db->where($k, $v);
                    }
                }
                $cont++;
            }

        }

        $q = $this->db->get();

        $r = $q->result_array();

        return $r;
    }

    public function getTotalPages($search = [], $useLike = false, $useOr = false)
    {
        $this->load->database(ENVIRONMENT);

        $this->db->from('post');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->join('post_has_tag as pht', 'post.id = pht.post_id AND pht.main');
        $this->db->join('tag', 'pht.tag_id = tag.id');

        if (count($search) > 0) {
            $cont = 0;
            foreach ($search as $k => $v) {
                if ($cont == 0) {
                    if ($useLike) {
                        $this->db->like($k, $v);
                    } else {
                        $this->db->where($k, $v);
                    }
                } else {
                    if ($useOr && $useLike) {
                        $this->db->or_like($k, $v);
                    } elseif ($useOr) {
                        $this->db->or_where($k, $v);
                    } elseif ($useLike) {
                        $this->db->like($k, $v);
                    } else {
                        $this->db->where($k, $v);
                    }
                }
                $cont++;
            }

        }

        return ceil($this->db->count_all_results()/$this->_itemsPerPage);
    }

    public function getTotal(){
        $this->load->database(ENVIRONMENT);

        $this->db->from('post');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->join('post_has_tag as pht', 'post.id = pht.post_id AND pht.main');
        $this->db->join('tag', 'pht.tag_id = tag.id');

        return $this->db->count_all_results();
    }

    public function searchByTag($tag, $nrPage)
    {
        return $this->getPage($nrPage, ['tag.friendly_url' => $tag]);
    }

    public function getTotalPagesByTag($tag)
    {
        return $this->getTotalPages(['tag.friendly_url' => $tag]);
    }

    public function searchByString($string, $nrPage)
    {
        return $this->getPage($nrPage, ['tag.name' => $string, 'post.title' => $string, 'post.summary' => $string], true, true);
    }

    public function getTotalPagesByString($string)
    {
        return $this->getTotalPages(['tag.name' => $string, 'post.title' => $string, 'post.summary' => $string], true, true);
    }

    public function changeItemsPerPage($nr)
    {
        $this->_itemsPerPage = $nr;
    }

    public function getByUrl($url)
    {
        $this->load->database(ENVIRONMENT);

        $this->db->select('post.*,tag.friendly_url,tag.name as main_tag,image.path as main_img');
        $this->db->from('post');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->join('post_has_tag as pht', 'post.id = pht.post_id AND pht.main');
        $this->db->join('tag', 'pht.tag_id = tag.id');
        $this->db->where('pretty_url', $url);
        $q = $this->db->get();

        $row = $q->first_row('array');

        if (isset($row)) {
            return $row;
        }

        return [];

    }

    public function getPrevNextPost($date, $direction = '>')
    {
        $this->load->database(ENVIRONMENT);

        $this->db->select('"" as oclass,post.title as otitle,post.pretty_url as opretty_url,image.path as omain_img');
        $this->db->from('post');
        $this->db->join('image', 'post.id = image.post_id AND image.main');
        $this->db->where('created_on ' . $direction, $date);
        $this->db->order_by('post.created_on', $direction == '>' ? 'ASC' : 'DESC');
        $this->db->limit(1, 0);

        $q = $this->db->get();

        $row = $q->first_row('array');

        if (isset($row)) {
            return $row;
        }

        return ['otitle' => null,'opretty_url' => '#N','omain_img' => null,'oclass' => 'd-none'];
    }

    public function add($post){
        $data = $post;
        $data['updated_on'] = date('Y-m-d H:i:s');
        $this->load->database(ENVIRONMENT);

        $this->db->set($data);
        $this->db->insert('post');
        return $this->db->insert_id();
    }
}
