<?php

class Master_model extends CI_Model
{

    // tambahan
    public function tampil($table, $order, $limit, $start, $keyword = null, $where = null)

    {
        if ($where) {
            $this->db->where($where);
        }

        if ($keyword) {
            $this->db->like($order, $keyword);
            return $this->db->get($table, $limit, $start)->result();
        } else {
            $this->db->order_by($order, 'ASC');
            return $this->db->get($table, $limit, $start)->result();
        }
    }

    public function tampil_total($table, $order, $keyword = null, $where = null)
    {
        if ($where) {
            $this->db->where($where);
        }

        if ($keyword) {
            $this->db->like($order, $keyword);
            return $this->db->get($table)->num_rows();
        } else {
            $this->db->order_by($order, 'ASC');
            return $this->db->get($table)->num_rows();
        }
    }
    ////////////////////////////////////////////////////////////////////////////////


    public function tampil_data($table, $order, $where = null)
    {
        if ($where) {
            $this->db->where($where);
        }
        $this->db->order_by($order, 'ASC');
        return $this->db->get($table);
    }

    public function input_data($table, $data)
    {

        $this->db->insert($table, $data);
    }

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    public function update_data($where, $data, $table)
    {

        $this->db->where($where);
        $this->db->update($table, $data);
    }


    public function hapus_data($where, $table)
    {

        $this->db->where($where);
        $this->db->delete($table);
    }


    function remove_checked($table) {
        $delete = $this->input->post('check');
        for ($i = 0; $i < count($delete); $i++) {
            $this->db->where('no_id', $delete[$i]);
            $this->db->delete($table);
        }
    }

    function remove_checked_article($table) {
        $delete = $this->input->post('check');
        for ($i = 0; $i < count($delete); $i++) {
            $this->db->where('row_id', $delete[$i]);
            $this->db->delete($table);
        }
    }

}
