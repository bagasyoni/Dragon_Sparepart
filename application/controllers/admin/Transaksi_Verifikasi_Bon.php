<?php

class Transaksi_Verifikasi_Bon extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'bon') {
			$this->session->set_userdata('menu_sparepart', 'bon');
			$this->session->set_userdata('kode_menu', 'T0008');
			$this->session->set_userdata('keyword_bl_bon', '');
			$this->session->set_userdata('order_bl_bon', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'KD_BAG', 'NM_BAG', 'DR', 'SUB');
    var $column_search = array('NO_BUKTI', 'KD_BAG', 'TGL', 'NM_BAG', 'DR', 'SP');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            // 'SUB' => $sub,
            'OK' => '0',
            'TTD2' => '',
        );
        $this->db->select('*');
        $this->db->from('bon');
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            // 'SUB' => $sub,
            'OK' => '0',
            'TTD2' => '',
        );
        $this->db->from('bon');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_bl_bon() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $bon) {
            $JASPER = "window.open('JASPER/" . $bon->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $bon->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_Bon/update/' . $bon->NO_ID) . '"> <i class="fa fa-edit"></i> Validasi</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $bon->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($bon->TGL));
            $row[] = $bon->NM_BAG;
            $row[] = $bon->DR;
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

    public function index_Transaksi_Verifikasi_Bon() {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Verifikasi Bon');
        $where = array(
            'DR' => $dr,
            // 'SUB' => $sub,
            'OK' => '0',
            'TTD2' => '',
        );
        $data['bon'] = $this->transaksi_model->tampil_data($where,'bon','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_Bon/Transaksi_Verifikasi_Bon', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update($id) {
        $q1="SELECT bon.NO_ID as ID,
                bon.NO_BUKTI AS NO_BUKTI,
                bon.PER AS PER,
                bon.TOTAL_QTY AS TOTAL_QTY,
                bon.VERIFIKASI_PO_SP AS VERIFIKASI_PO_SP,
                bon.TTD2,

                bond.NO_ID AS NO_ID,
                bond.REC AS REC,
                bond.KD_BHN AS KD_BHN,
                bond.NA_BHN AS NA_BHN,
                bond.NOTES AS NOTES,
                bond.NOTES AS TIPE,
                bond.QTY AS QTY
            FROM bon,bond 
            WHERE bon.NO_ID=$id 
            AND bon.NO_ID=bond.NO_ID 
            ORDER BY bond.REC";
        $data['verifikasi_bon']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_Bon/Transaksi_Verifikasi_Bon_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi($ID) {}

    public function verifikasi_bl_bon($NO_ID) {
        $sub = $this->session->userdata['sub'];
        $username = $this->session->userdata['username'];
        $datah = array(
            'TTD2' => $sub,
            'TTD2_USR' => $username,
            'TTD2_SMP' => date("Y-m-d h:i a"),
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
		$this->transaksi_model->update_data($where,$datah,'bon');
		$this->session->set_flashdata('pesan',
			'<div class="alert alert-success alert-dismissible fade show" role="alert"> Data Succesfully Verified.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>');
        redirect('admin/Transaksi_Verifikasi_Bon/index_Transaksi_Verifikasi_Bon');
    }

    public function delete($id) {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'bon');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'bond');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Verifikasi_Bon/index_Transaksi_Verifikasi_Bon');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('bon', 'bond');
        redirect('admin/Transaksi_Verifikasi_Bon/index_Transaksi_Verifikasi_Bon');
    }

    function JASPER($id) {
        $CI = &get_instance();
        $CI->load->database();
        $servername = $CI->db->hostname;
        $username = $CI->db->username;
        $password = $CI->db->password;
        $database = $CI->db->database;
        $conn = mysqli_connect($servername, $username, $password, $database);
        error_reporting(E_ALL);
        ob_start();
        include_once('phpjasperxml/class/tcpdf/tcpdf.php');
        include_once("phpjasperxml/class/PHPJasperXML.inc.php");
        include_once("phpjasperxml/setting.php");
        $PHPJasperXML = new \PHPJasperXML();
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Verifikasi_Bon.jrxml");
        $no_id = $id;
        $query = "SELECT bon.no_id as ID,
                bon.no_sp AS MODEL,
                bon.perke AS PERKE,
                bon.tgl_sp AS TGL_SP,
                bon.nodo AS NODO,
                bon.tgldo AS TGLDO,
                bon.tlusin AS TLUSIN,
                bon.tpair AS TPAIR,

                bond.no_id AS NO_ID,
                bond.rec AS REC,
                CONCAT(bond.article,' - ',bond.warna) AS ARTICLE,
                bond.size AS SIZE,
                bond.golong AS GOLONG,
                bond.sisa AS SISA,
                bond.lusin AS LUSIN,
                bond.pair AS PAIR,
                CONCAT(bond.kodecus,' - ',bond.nama) AS KODECUS,
                bond.kota AS KOTA
            FROM bon,bond 
            WHERE bon.no_id=$id 
            AND bon.no_id=bond.id 
            ORDER BY bond.rec";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "KDMTS" => $row1["KDMTS"],
                "MODEL" => $row1["MODEL"],
                "TGL_SP" => $row1["TGL_SP"],
                "KODECUS" => $row1["KODECUS"],
                "ARTICLE" => $row1["ARTICLE"],
                "LUSIN" => $row1["LUSIN"],
                "PAIR" => $row1["PAIR"],
                "REC" => $row1["REC"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }

}