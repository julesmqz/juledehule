<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Social_model extends CI_Model
{

    public function getInstagramPics()
    {
        $this->load->database(ENVIRONMENT);

        $this->db->order_by('uploaded_on', 'RANDOM');
        $this->db->limit(12,0);
        $query = $this->db->get('social_instagram_pics');

        return $query->result_array();
    }

    public function insertInstaPics($pics){
        $this->load->database(ENVIRONMENT);
        foreach( $pics as $pic){
            $this->db->replace('social_instagram_pics', $pic);
        }
    }
}
