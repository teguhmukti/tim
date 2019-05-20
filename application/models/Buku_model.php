<?php

class Buku_model extends CI_Model {

    public function tampilSemuaBrg()
    {
        return $this->db->get('t_buku')->result_array();
    }
}