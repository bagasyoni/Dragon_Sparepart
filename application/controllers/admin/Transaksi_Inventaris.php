<?php

class Transaksi_Inventaris extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'sp_bagian') {
			$this->session->set_userdata('menu_sparepart', 'sp_bagian');
			$this->session->set_userdata('kode_menu', 'T0011');
			$this->session->set_userdata('keyword_sp_bagian', '');
			$this->session->set_userdata('order_sp_bagian', 'no_id');
        }
    }

    var $column_order = array(null, null, null, 'kode', 'bagian', 'bagian');
    var $column_search = array('kode', 'bagian', 'bagian');
    var $order = array('kode' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            'dr' => $dr,
            'per' => $per,
        );
        $this->db->select('*');
        $this->db->from('sp_bagian');
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
        }
        if (isset($_POST['order'])) {
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
        $per = $this->session->userdata['periode'];
        $where = array(
            'dr' => $dr,
            'per' => $per,
        );
        $this->db->from('sp_bagian');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_sp_bagian() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $sp_bagian) {
            $JASPER = "window.open('JASPER/" . $sp_bagian->no_id . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $sp_bagian->no_id . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Inventaris/update/' . $sp_bagian->no_id) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Inventaris/delete/' . $sp_bagian->no_id) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="no_id" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $sp_bagian->kode;
            $row[] = $sp_bagian->nama;
            $row[] = $sp_bagian->bagian;
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

    public function index_Transaksi_Inventaris() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $this->session->set_userdata('judul', 'Transaksi Inventaris');
        $where = array(
            'dr' => $dr,
            'per' => $per,
        );
        $data['sp_bagian'] = $this->transaksi_model->tampil_data($where,'sp_bagian','no_id')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Inventaris/Transaksi_Inventaris', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input() {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Pemesanan_Proyek/Transaksi_Pemesanan_Proyek_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $flag = 'PESAN'; 
        $dr = $this->session->userdata['dr']; 
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $xx = $this->db->query("CALL NO_BUKTI_PEMESANAN_SPAREPART('SPAREPART_$flag','pemesanan','$flag','$per','$dr','$sub')")->result();
        mysqli_next_result($this->db->conn_id);
        $bukti = $xx[0]->BUKTIX;
        $datah = array(
            'flag' => 'PESAN',
            'logistik' => '0',
            'no_bukti' => $bukti, 
            'ket' => $this->input->post('KET',TRUE),
            'tgl' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'total_qty' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'sp' => $this->session->userdata['sub'],
            'dr' => $this->session->userdata['dr'],
            'per' => $this->session->userdata['periode'],
            'usrnm' => $this->session->userdata['username'],
            'i_tgl' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('sp_pp',$datah);
        $ID= $this->db ->query("SELECT MAX(no_id) AS no_id FROM sp_pp WHERE no_bukti = '$bukti' AND dr='$dr' AND per='$per' GROUP BY no_bukti")->result();
        $REC = $this->input->post('REC');
        $KD_BRG = $this->input->post('KD_BRG');
        $NA_BRG = $this->input->post('NA_BRG');
        $TIPE = $this->input->post('TIPE');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $DEVISI = $this->input->post('DEVISI');
        $KET1 = $this->input->post('KET1');
        $TGL_DIMINTA = date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA',TRUE)));
        $SISA = str_replace(',','',$this->input->post('SISA',TRUE));
        $URGENT = str_replace(',','',$this->input->post('URGENT',TRUE));
        $i = 0;
        foreach($REC as $a) {
            $datad = array(
                'id' => $ID[0]->no_id,
                'no_bukti' => $bukti,
                'tgl' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                'flag' => 'PESAN',
                'logistik' => '0',
                'rec' => $REC[$i],
                'kd_brg' => $KD_BRG[$i],
                'na_brg' => $NA_BRG[$i],
                'tipe' => $TIPE[$i],
                'qty' => str_replace(',','',$QTY[$i]),
                'satuan' => $SATUAN[$i],
                'devisi' => $DEVISI[$i],
                'ket1' => $KET1[$i],
                'tgl_diminta' => date("Y-m-d", strtotime($TGL_DIMINTA[$i])),
                'sisa' => str_replace(',','',$SISA[$i]),
                'urgent' => str_replace(',','',$URGENT[$i]),
                'usrnm' => $this->session->userdata['username'],
                'dr' => $this->session->userdata['dr'],
                'sp' => $this->session->userdata['sub'],
                'i_tgl' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('sp_ppd',$datad);
            $i++;
        }
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Pemesanan_Proyek/index_Transaksi_Pemesanan_Proyek'); 
    }

    public function update($id) {
        $q1="SELECT sp_pp.no_id as ID,
                sp_pp.no_bukti AS NO_BUKTI,
                sp_pp.ket AS KET,
                sp_pp.tgl AS TGL,
                sp_pp.total_qty AS TOTAL_QTY,

                sp_ppd.no_id AS NO_ID,
                sp_ppd.rec AS REC,
                sp_ppd.kd_brg AS KD_BRG,
                sp_ppd.na_brg AS NA_BRG,
                sp_ppd.tipe AS TIPE,
                sp_ppd.qty AS QTY,
                sp_ppd.satuan AS SATUAN,
                sp_ppd.devisi AS DEVISI,
                sp_ppd.ket1 AS KET1,
                sp_ppd.tgl_diminta AS TGL_DIMINTA,
                sp_ppd.sisa AS SISA,
                sp_ppd.urgent AS URGENT
            FROM sp_pp,sp_ppd 
            WHERE sp_pp.no_id=$id 
            AND sp_pp.no_id=sp_ppd.id 
            ORDER BY sp_ppd.rec";
        $data['transaksi_pemesanan_proyek']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Pemesanan_Proyek/Transaksi_Pemesanan_Proyek_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $datah = array(
            'flag' => 'PESAN',
            'logistik' => '0',
            'no_bukti' => $this->input->post('NO_BUKTI',TRUE), 
            'ket' => $this->input->post('KET',TRUE),
            'tgl' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'total_qty' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'sp' => $this->session->userdata['sub'],
            'dr' => $this->session->userdata['dr'],
            'e_pc' => $this->session->userdata['username'],
            'e_tgl' => date("Y-m-d h:i a")
        );
        $where = array(
            'no_id' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'sp_pp');
        $id = $this->input->post('ID', TRUE);
        $q1="SELECT sp_pp.no_id as ID,
                sp_pp.no_bukti AS NO_BUKTI,
                sp_pp.ket AS KET,
                sp_pp.tgl AS TGL,
                sp_pp.total_qty AS TOTAL_QTY,

                sp_ppd.no_id AS NO_ID,
                sp_ppd.rec AS REC,
                sp_ppd.kd_brg AS KD_PEG,
                sp_ppd.na_brg AS NM_PEG,
                sp_ppd.tipe AS TIPE,
                sp_ppd.qty AS QTY,
                sp_ppd.satuan AS SATUAN,
                sp_ppd.devisi AS DEVISI,
                sp_ppd.ket1 AS KET1,
                sp_ppd.tgl_diminta AS TGL_DIMINTA,
                sp_ppd.sisa AS SISA,
                sp_ppd.urgent AS URGENT
            FROM sp_pp,sp_ppd 
            WHERE sp_pp.no_id=$id 
            AND sp_pp.no_id=sp_ppd.id 
            ORDER BY sp_ppd.rec";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BRG = $this->input->post('KD_BRG');
        $NA_BRG = $this->input->post('NA_BRG');
        $TIPE = $this->input->post('TIPE');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $DEVISI = $this->input->post('DEVISI');
        $KET1 = $this->input->post('KET1');
        $TGL_DIMINTA = date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA',TRUE)));
        $SISA = str_replace(',','',$this->input->post('QTY',TRUE));
        $URGENT = str_replace(',','',$this->input->post('URGENT',TRUE));
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'flag' => 'PESAN',
                    'logistik' => '0',
                    'no_bukti' => $this->input->post('NO_BUKTI'),
                    'tgl' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'rec' => $REC[$URUT],
                    'kd_brg' => $KD_BRG[$URUT],
                    'na_brg' => $NA_BRG[$URUT],
                    'tipe' => $TIPE[$URUT],
                    'qty' => str_replace(',','',$QTY[$URUT]),
                    'satuan' => $SATUAN[$URUT],
                    'devisi' => $DEVISI[$URUT],
                    'ket1' => $KET1[$URUT],
                    'tgl_diminta' => date("Y-m-d", strtotime($TGL_DIMINTA[$URUT])),
                    'sisa' => str_replace(',','',$SISA[$URUT]),
                    'urgent' => str_replace(',','',$URGENT[$URUT]),
                    'dr' => $this->session->userdata['dr'],
                    'sp' => $this->session->userdata['sub'],
                    'e_pc' => $this->session->userdata['username'],
                    'e_tgl' => date("Y-m-d h:i a")
                );
                $where = array(
                    'no_id' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'sp_ppd');
            } else {
                $where = array(
                    'no_id' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'sp_ppd');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'flag' => 'PESAN',
                    'logistik' => '0',
                    'id' => $this->input->post('ID', TRUE),
                    'no_bukti' => $this->input->post('NO_BUKTI'),
                    'tgl' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'rec' => $REC[$i],
                    'kd_brg' => $KD_BRG[$i],
                    'na_brg' => $NA_BRG[$i],
                    'tipe' => $TIPE[$i],
                    'qty' => str_replace(',','',$QTY[$i]),
                    'satuan' => $SATUAN[$i],
                    'devisi' => $DEVISI[$i],
                    'ket1' => $KET1[$i],
                    'tgl_diminta' => date("Y-m-d", strtotime($TGL_DIMINTA[$i])),
                    'sisa' => str_replace(',','',$SISA[$i]),
                    'urgent' => str_replace(',','',$URGENT[$i]),
                    'dr' => $this->session->userdata['dr'],
                    'sp' => $this->session->userdata['sub'],
                    'e_pc' => $this->session->userdata['username'],
                    'e_tgl' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('sp_ppd', $datad);
            }
            $i++;
        }
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Pemesanan_Proyek/index_Transaksi_Pemesanan_Proyek');
    }

    public function delete($id) {
        $where = array('no_id' => $id);
        $this->transaksi_model->hapus_data($where, 'sp_pp');
        $where = array('id' => $id);
        $this->transaksi_model->hapus_data($where, 'sp_ppd');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Pemesanan_Proyek/index_Transaksi_Pemesanan_Proyek');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('sp_pp', 'sp_ppd');
        redirect('admin/Transaksi_Pemesanan_Proyek/index_Transaksi_Pemesanan_Proyek');
    }

    function filter_kd_bag() {
        $kd_bag = $this->input->get('kd_bag');
        $q1 = "SELECT kd_peg AS KD_PEG, 
                nm_peg AS NM_PEG
            FROM hrd_peg WHERE kd_bag='$kd_bag' AND aktif='1' ORDER BY kd_peg ";
        $q2 = $this->db->query($q1);
        if($q2->num_rows() > 0){
            foreach($q2->result() as $row){
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    public function getDataAjax_Barang() {
        $dr = $this->session->userdata['dr'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT no_id, kd_brg, na_brg, satuan, dr
            FROM sp_barang
            WHERE dr='$dr' AND (kd_brg LIKE '%$search%' OR na_brg LIKE '%$search%' OR satuan LIKE '%$search%') 
            ORDER BY kd_brg LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['kd_brg'],
                'text' => $row['kd_brg'],
                'kd_brg' => $row['kd_brg'] . " - " . $row['na_brg']. " - " . $row['satuan']. " - " . $row['dr'],
                'na_brg' => $row['na_brg'],
                'satuan' => $row['satuan'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
}