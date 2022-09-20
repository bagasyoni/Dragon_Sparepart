<?php


class Master_Inventori extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!isset($this->session->userdata['username'])) {
            $this->session->set_flashdata('pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Anda Belum Login
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            redirect('admin/auth');
        }
        if ($this->session->userdata['menu_sparepart'] != 'sp_inven') {
			$this->session->set_userdata('menu_sparepart', 'sp_inven');
			$this->session->set_userdata('kode_menu', 'M0003');
			$this->session->set_userdata('keyword_sp_inven', '');
			$this->session->set_userdata('order_sp_inven', 'no_id');
        }
    }
    var $column_order = array(null, null, null, 'no_bukti', 'kode', 'nama', 'bagian', 'j_barang', 'dr');
    var $column_search = array('no_bukti', 'kode', 'nama', 'bagian', 'j_barang', 'dr');
    var $order = array('no_id' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $where = array(
            'dr' => $dr,
        );
        $this->db->select('*');
        $this->db->from('sp_inven');
        $this->db->where($where);
        $i = 0;
        foreach ($this->column_search as $item) {
            if (@$_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        } if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $dr = $this->session->userdata['dr'];
        $where = array(
            'dr' => $dr,
        );
        $this->db->from('sp_inven');
        return $this->db->count_all_results();
    }

    function get_ajax_sp_inven() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        $dr = $this->session->userdata['dr'];
        foreach ($list as $sp_inven) {
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $sp_inven->no_id . "'>";
            if ($dr==='SUPER_ADMIN'){
                $row[] = '<div class="dropdown">
                            <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="' . site_url('admin/Master_Inventori/update/' . $sp_inven->no_id) . '"> <i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="' . site_url('admin/Master_Inventori/delete/' . $sp_inven->no_id) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>';
                } else {
                $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Master_Inventori/update/' . $sp_inven->no_id) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a hidden class="dropdown-item" href="' . site_url('admin/Master_Inventori/delete/' . $sp_inven->no_id) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>';
            }
            $row[] = $no . ".";
            $row[] = $sp_inven->no_bukti;
            $row[] = $sp_inven->kode;
            $row[] = $sp_inven->nama;
            $row[] = $sp_inven->bagian;
            $row[] = $sp_inven->j_barang;
            $row[] = $sp_inven->dr;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function index_Master_Inventori() {
        // $data['sp_inven'] = $this->master_model->tampil_data('sp_inven', 'no_id')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        // $this->load->view('admin/Master_Inventori/Master_Inventori', $data);
        $this->load->view('admin/Master_Inventori/Master_Inventori');
        $this->load->view('templates_admin/footer');
    }

    public function getOrder() {
        $data['orderBy'] = $this->input->get('order');
        $this->session->set_userdata('order_sp_inven', $data['orderBy']);
    }

    public function input() {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Inventori/Master_Inventori_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $data = array(
            'no_bukti' => $this->input->post('NO_BUKTI',TRUE),
            'kode' => $this->input->post('KODE',TRUE),
            'nama' => $this->input->post('NAMA',TRUE),
            'bagian' => $this->input->post('BAGIAN',TRUE),
            'j_barang' => $this->input->post('J_BARANG',TRUE),
            'merk' => $this->input->post('MERK',TRUE),
            'tgl_ma' => date("Y-m-d", strtotime($this->input->post('TGL_MA',TRUE))),
            'tgl_ke' => date("Y-m-d", strtotime($this->input->post('TGL_KE',TRUE))),
            'tgl_mutasi' => date("Y-m-d", strtotime($this->input->post('TGL_MUTASI',TRUE))),
            'jumlah' => str_replace(',','',$this->input->post('JUMLAH',TRUE)),
            'satuan' => $this->input->post('SATUAN',TRUE),
            'keter' => $this->input->post('KETER',TRUE),
            'tempat' => $this->input->post('TEMPAT',TRUE),
            'rec' => str_replace(',','',$this->input->post('REC',TRUE)),
            'kd_brg' => $this->input->post('KD_BRG',TRUE),
            'dr' => $this->session->userdata['dr'],
            'usrnm' => $this->session->userdata['username'],
            'i_tgl' => date('Y-m-d H:i:s'),
        );
        $this->master_model->input_data('sp_inven',$data);
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Master_Inventori/index_Master_Inventori');
    }

    public function update($no_id) {
        $where = array('no_id' => $no_id);
        $ambildata = $this->master_model->edit_data($where,'sp_inven');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['no_id'],
            'NO_BUKTI' => $r['no_bukti'],
            'KODE' => $r['kode'],
            'NAMA' => $r['nama'],
            'BAGIAN' => $r['bagian'],
            'J_BARANG' => $r['j_barang'],
            'MERK' => $r['merk'],
            'TGL_MA' => $r['tgl_ma'],
            'TGL_KE' => $r['tgl_ke'],
            'TGL_MUTASI' => $r['tgl_mutasi'],
            'JUMLAH' => $r['jumlah'],
            'SATUAN' => $r['satuan'],
            'KETER' => $r['keter'],
            'TEMPAT' => $r['tempat'],
            'REC' => $r['rec'],
            'KD_BRG' => $r['kd_brg'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Master_Inventori/Master_Inventori_update',$data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $no_id = $this->input->post('NO_ID');
        $data = array(
            'no_bukti' => $this->input->post('NO_BUKTI',TRUE),
            'kode' => $this->input->post('KODE',TRUE),
            'nama' => $this->input->post('NAMA',TRUE),
            'bagian' => $this->input->post('BAGIAN',TRUE),
            'j_barang' => $this->input->post('J_BARANG',TRUE),
            'merk' => $this->input->post('MERK',TRUE),
            'tgl_ma' => date("Y-m-d", strtotime($this->input->post('TGL_MA',TRUE))),
            'tgl_ke' => date("Y-m-d", strtotime($this->input->post('TGL_KE',TRUE))),
            'tgl_mutasi' => date("Y-m-d", strtotime($this->input->post('TGL_MUTASI',TRUE))),
            'jumlah' => str_replace(',','',$this->input->post('JUMLAH',TRUE)),
            'satuan' => $this->input->post('SATUAN',TRUE),
            'keter' => $this->input->post('KETER',TRUE),
            'tempat' => $this->input->post('TEMPAT',TRUE),
            'rec' => str_replace(',','',$this->input->post('REC',TRUE)),
            'kd_brg' => $this->input->post('KD_BRG',TRUE),
            'dr' => $this->session->userdata['dr'],
			'e_pc' => $this->session->userdata['username'],
			'e_tgl' => date('Y-m-d H:i:s'),
        );
        $where = array(
            'no_id' => $no_id
        );
        $this->master_model->update_data($where,$data,'sp_inven');
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Master_Inventori/index_Master_Inventori');
    }

    public function delete($no_id) {
        $where = array('no_id' => $no_id) ;
        $this->master_model->hapus_data($where,'sp_inven');
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Master_Inventori/index_Master_Inventori');
    }

    function delete_multiple() {
        $this->master_model->remove_checked('sp_inven');
        redirect('admin/Master_Inventori/index_Master_Inventori');
    }

}