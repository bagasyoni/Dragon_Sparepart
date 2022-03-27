<?php

class Transaksi_model extends CI_Model {

    public function tampil($table, $order, $limit, $start, $keyword = null, $where) {
        if ($keyword) {
            $this->db->like($order, $keyword);
            $this->db->where($where);
            return $this->db->get($table, $limit, $start)->result();
        } else {
            $this->db->where($where);
            $this->db->order_by($order, 'ASC');
            return $this->db->get($table, $limit, $start)->result();
        }
    }

    public function tampil_total($table, $order, $keyword = null, $where) {
        if ($keyword) {
            $this->db->where($where);
            $this->db->like($order, $keyword);
            return $this->db->get($table)->num_rows();
        } else {
            $this->db->where($where);
            $this->db->order_by($order, 'ASC');
            return $this->db->get($table)->num_rows();
        }
    }

    public function tampil_data($where, $table, $order) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, 'asc');
        return $this->db->get();
    }

    public function input_datah($table, $datah) {
        $this->db->insert($table, $datah);
    }

    public function tampil_id($table, $bukti) {
        $q1 = "select max(NO_ID) as NO_ID from $table where NO_BUKTI = ? group by NO_BUKTI";
        return $this->db->query($q1, array($bukti));
    }


    public function tampil_idPO($table, $bukti) {
        $q1 = "select max(NO_ID) as NO_ID from $table where NO_PO = ? group by NO_PO";
        return $this->db->query($q1, array($bukti));
    }

    public function input_datad($table, $datad)
    {

        $this->db->insert($table, $datad);
    }

    public function edit_data($q1) {
        return $this->db->query($q1);
    }

    public function update_data($where, $data, $table) {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function hapus_data($where, $table) {
        $this->db->where($where);
        $this->db->delete($table);
    }

    function remove_checked($table, $tabled) {
        $delete = $this->input->post('check');
        for ($i = 0; $i < count($delete); $i++) {
            // $nobkt = $data[0]->NO_BUKTI; 
            $data = $this->db->query("select NO_BUKTI FROM " . $table . " where no_id=$delete[$i]")->result();
            // $this->db->query("call ".$table."del('".$data[0]->NO_BUKTI."')");  
            $this->db->where('no_id', $delete[$i]);
            $this->db->delete($table);
            $this->db->where('id', $delete[$i]);
            $this->db->delete($tabled);
        }
    }

}