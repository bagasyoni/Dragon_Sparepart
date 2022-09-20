<?php

class Transaksi_Verifikasi_PO extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'po') {
			$this->session->set_userdata('menu_sparepart', 'po');
			$this->session->set_userdata('kode_menu', 'T0008');
			$this->session->set_userdata('keyword_po', '');
			$this->session->set_userdata('order_po', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'DR', 'TGL', 'JTEMPO', 'NOTES');
    var $column_search = array( 'NO_BUKTI', 'DR', 'TGL', 'JTEMPO', 'NOTES');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata('periode');
        // $sub = $this->session->userdata('sub');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '0',
        );
        $this->db->select('*');
        $this->db->from('po');
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
        $sub = $this->session->userdata('sub');
        $per = $this->session->userdata('periode');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '0',
        );
        $this->db->from('po');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_bl_po() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $po) {
            $JASPER = "window.open('JASPER/" . $po->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $po->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_PO/update/' . $po->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $po->NO_BUKTI;
            $row[] = $po->KODES;
            $row[] = $po->NAMAS;
            $row[] = $po->DR;
            $row[] = date("d-m-Y", strtotime($po->TGL));
            $row[] = date("d-m-Y", strtotime($po->JTEMPO));
            $row[] = $po->NOTES;
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

    public function index_Transaksi_Verifikasi_PO() {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata('sub');
        $per = $this->session->userdata('per');
        $this->session->set_userdata('judul', 'Transaksi Verifikasi PO');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '0',
        );
        $data['po'] = $this->transaksi_model->tampil_data($where,'po','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update($id) {
        $q1="SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.TGL AS TGL,
                po.JTEMPO AS JTEMPO,
                '-' AS UANG,
                po.KURS AS KURS,
                po.PROD AS PROD,
                po.NOTESBL AS NOTES_BYR,
                po.NOTESKRM AS NOTES_KRM,
                '-' AS NOTE_KURS,
                po.KODES AS KODES,
                po.NAMAS AS NAMAS,
                '-' AS ATT,
                po.TOTAL_QTY AS TOTAL_QTY,
                '-' AS NOPOPPC,
                '-' AS VERIFIKASI_PO_SP,
                
                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.KET AS KET,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY              
            FROM po,pod 
            WHERE po.NO_ID=$id 
            AND po.NO_ID=pod.ID 
            ORDER BY pod.REC";
        $data['verifikasi_po']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi($ID) {}

    public function verifikasi_bl_po($NO_ID) {
        $datah = array(
            'VERIFIKASI_PO_SP' =>'1',
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
		$this->transaksi_model->update_data($where,$datah,'po');
		$datahd = array(
            'VERIFIKASI_PO_SP' =>'1',
        );
        $whered = array(
            'ID' => "$NO_ID"
        );
		$this->transaksi_model->update_data($whered,$datahd,'pod');
		$this->session->set_flashdata('pesan',
			'<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>');
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    public function delete($id) {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'po');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pod');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('po', 'pod');
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }
}