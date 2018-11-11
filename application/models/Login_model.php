<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Login_model extends CI_Model
{

    public function getId( $data )
    {
        $this->db->select('id');
        $q = $this->db->get_where('user',['email' => $data['email'], 'password' => $data['password']]);

        if($q->num_rows() > 0){
            return $q->result_array()[0]['id'];
        }

        return 0;
    }
}
