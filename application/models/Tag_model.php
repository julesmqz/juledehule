<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Tag_model extends CI_Model
{

    public function getPopular()
    {
        $this->load->database('default');

        $this->db->select('tag.*,count(pht.post_id) as total');
        $this->db->join('post_has_tag pht','ON tag.id = pht.tag_id');
        $this->db->group_by('pht.tag_id');
        $this->db->order_by('total', 'desc');
        $this->db->limit(5,0);

        $q = $this->db->get('tag');

        return $q->result_array();
    }
}
