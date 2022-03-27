<?php

class Font_model extends CI_Model{

    // ambil font berdasarkan id user
    public function get_font($id) {
        $query =  $this->db->select('FONT')
        ->where('ID_USER', $id)
        ->limit(1)
        ->get('font')
        ->row();
        return $query->FONT;
    }

    // ambil id font berdasarkan id user
    public function get_id($id){
        $query =  $this->db->select('ID_FONT')
        ->where('ID_USER', $id)
        ->limit(1)
        ->get('font')
        ->row();
        return $query->ID_FONT;
    }

    // ambil ukuran berdasarkan id user
    public function get_size($id){
        $query =  $this->db->select('SIZE')
        ->where('ID_USER', $id)
        ->limit(1)
        ->get('font')
        ->row();
        return $query->SIZE;
    }
    // ambil font berdasarkan id user
    // public function get_theme($id) {
    //     $query =  $this->db->select('THEME')
    //     ->where('ID_USER', $id)
    //     ->limit(1)
    //     ->get('FONT')
    //     ->row();
    //     return $query->THEME;
    // }
    // update data font dan sesi
    public function update($id, $data){
        $this->session->unset_userdata('font');
        $this->db->where('ID_FONT', $id);
        $this->db->update('FONT', $data);
        $this->session->unset_userdata('size_font');
        $sess_data['font']      = $data['FONT'];
        $sess_data['size_font'] = $data['SIZE'];
        // $sess_data['theme'] = $data['THEME'];
        $this->session->set_userdata($sess_data);
    }
}