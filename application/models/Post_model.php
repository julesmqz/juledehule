<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Post_model extends CI_Model
{
    protected $_itemsPerPage = 6;

    public function getMainSlider()
    {
        

        $this->db->select('post.friendly_url as link,post.title,image');
        $this->db->from('slider');
        $this->db->join('post', 'slider.post_id = post.id');
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
        

        $this->db->select('post.*,post.friendly_url as pretty_url,category.friendly_url as category_url,category.name as category,image as main_img');
        $this->db->from('post');
        $this->db->join('category','category.id = post.category_id');
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
        

        $this->db->from('post');
        $this->db->join('category','category.id = post.category_id');

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
        

        $this->db->from('post');

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

    public function searchByCat($cat, $nrPage)
    {
        return $this->getPage($nrPage, ['category.friendly_url' => $cat]);
    }

    public function getTotalPagesByCat($cat)
    {
        return $this->getTotalPages(['category.friendly_url' => $cat]);
    }

    public function searchByString($string, $nrPage)
    {
        return $this->getPage($nrPage, ['category.name' => $string, 'post.title' => $string, 'post.summary' => $string], true, true);
    }

    public function getTotalPagesByString($string)
    {
        return $this->getTotalPages(['category.name' => $string, 'post.title' => $string, 'post.summary' => $string], true, true);
    }

    public function changeItemsPerPage($nr)
    {
        $this->_itemsPerPage = $nr;
    }

    public function getByUrl($url)
    {
        

        $this->db->select('post.*,post.friendly_url as pretty_url,category.friendly_url as category_url,category.name as category,image as main_img');
        $this->db->from('post');
        $this->db->join('category','category.id = post.category_id');
        $this->db->where('post.friendly_url', $url);
        $q = $this->db->get();

        $row = $q->first_row('array');

        if (isset($row)) {
            return $row;
        }

        return [];

    }

    public function getPrevNextPost($date, $direction = '>')
    {
        

        $this->db->select('"" as oclass,post.title as otitle,post.friendly_url as opretty_url,image as omain_img');
        $this->db->from('post');
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
        

        $this->db->set($data);
        $this->db->insert('post');
        return $this->db->insert_id();
    }
}
