<?php

class User_model extends CI_model{
    public function ambil_data($id)
    {
        $this->db->where('USERNAME',$id);
        return $this->db->get('users')->row();
    }
}
