<?php

class Acc_model extends CI_Model{

    public function tampil_data() {
        return $this->db->get('account');
    }

    public function input_data($data)  {
        $this->db->insert('account',$data);
    }
    
    public function edit_data($where,$table) {
        return $this->db->get_where($table,$where);
    }

    public function update_data($where,$data,$table) {
        $this->db->where($where);
        $this->db->update($table,$data);
    }

    public function hapus_data($where,$table) {
        $this->db->where($where);
        $this->db->delete($table);
    }

    function remove_checked() {
		$delete = $this->input->post('check');
		for ($i=0; $i < count($delete) ; $i++) { 
			$this->db->where('no_id', $delete[$i]);
			$this->db->delete('account');
		}
	}

}
