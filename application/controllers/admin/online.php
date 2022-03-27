<?php

class Online extends CI_Controller
{
    public function in()
    {
        $user = $this->session->userdata['username'];
        $this->db->query("UPDATE users set tg_in = DATE_ADD(NOW(), INTERVAL 10 second) where username='$user'");
        $this->db->query("DELETE FROM tgx WHERE DATE_ADD( ONLINEX, INTERVAL 20 second)<NOW();");
    }

    public function cek_bkby()
    {
        $user = $this->session->userdata['username'];
        $this->db->query("UPDATE tgx set ONLINEX = DATE_ADD(NOW(), INTERVAL 20 second) where USRNM='$user'");
    }
}
