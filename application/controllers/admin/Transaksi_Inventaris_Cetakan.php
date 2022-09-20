<?php


class Transaksi_Inventaris_Cetakan extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'sp_invenc') {
			$this->session->set_userdata('menu_sparepart', 'sp_invenc');
			$this->session->set_userdata('kode_menu', 'M0004');
			$this->session->set_userdata('keyword_sp_invenc', '');
			$this->session->set_userdata('order_sp_invenc', 'NO_ID');
        }
    }
    var $column_order = array(null, null, null, 'CETAK', 'NAMA', 'KODE', 'NO_URUT', 'DR');
    var $column_search = array('CETAK', 'NAMA', 'KODE', 'NO_URUT', 'DR');
    var $order = array('CETAK' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $where = array(
            'DR' => $dr,
            'FLAG' => 'INVC',
        );
        $this->db->select('*');
        $this->db->from('sp_invenc');
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
            'DR' => $dr,
            'FLAG' => 'INVC',
        );
        $this->db->from('sp_invenc');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_sp_invenc() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $sp_invenc) {
            $JASPER = "window.open('JASPER/" . $sp_invenc->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $sp_invenc->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Inventaris_Cetakan/update/' . $sp_invenc->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Inventaris_Cetakan/delete/' . $sp_invenc->NO_ID) . '"  onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $sp_invenc->CETAK;
            $row[] = $sp_invenc->NAMA;
            $row[] = $sp_invenc->KODE;
            $row[] = $sp_invenc->NO_URUT;
            $row[] = $sp_invenc->DR;
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

    public function index_Transaksi_Inventaris_Cetakan() {
        $data['sp_invenc'] = $this->master_model->tampil_data('sp_invenc', 'NO_ID')->result();
        $this->session->set_userdata('judul', 'Transaksi Inventaris Cetakan');
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Inventaris_Cetakan/Transaksi_Inventaris_Cetakan', $data);
        $this->load->view('templates_admin/footer');
    }

    public function getOrder() {
        $data['orderBy'] = $this->input->get('order');
        $this->session->set_userdata('order_sp_invenc', $data['orderBy']);
    }

    public function input() {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Inventaris_Cetakan/Transaksi_Inventaris_Cetakan_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $data = array(
            'CETAK' => $this->input->post('CETAK',TRUE),
            'NAMA' => $this->input->post('NAMA',TRUE),
            'KODE' => $this->input->post('KODE',TRUE),
            'NO_URUT' => $this->input->post('NO_URUT',TRUE),
            'N1' => $this->input->post('N1',TRUE),
            'J1' => $this->input->post('J1',TRUE),
            'N2' => $this->input->post('N2',TRUE),
            'J2' => $this->input->post('J2',TRUE),
            'N3' => $this->input->post('N3',TRUE),
            'J3' => $this->input->post('J3',TRUE),
            'N4' => $this->input->post('N4',TRUE),
            'J4' => $this->input->post('J4',TRUE),
            'N5' => $this->input->post('N5',TRUE),
            'J5' => $this->input->post('J5',TRUE),
            'N6' => $this->input->post('N6',TRUE),
            'J6' => $this->input->post('J6',TRUE),
            'N7' => $this->input->post('N7',TRUE),
            'J7' => $this->input->post('J7',TRUE),
            'N8' => $this->input->post('N8',TRUE),
            'J8' => $this->input->post('J8',TRUE),
            'N9' => $this->input->post('N9',TRUE),
            'J9' => $this->input->post('J9',TRUE),
            'N10' => $this->input->post('N10',TRUE),
            'J10' => $this->input->post('J10',TRUE),
            'N11' => $this->input->post('N11',TRUE),
            'J11' => $this->input->post('J11',TRUE),
            'N12' => $this->input->post('N12',TRUE),
            'J12' => $this->input->post('J12',TRUE),
            'N13' => $this->input->post('N13',TRUE),
            'J13' => $this->input->post('J13',TRUE),
            'N14' => $this->input->post('N14',TRUE),
            'J14' => $this->input->post('J14',TRUE),
            'N15' => $this->input->post('N15',TRUE),
            'J15' => $this->input->post('J15',TRUE),
            'N16' => $this->input->post('N16',TRUE),
            'J16' => $this->input->post('J16',TRUE),
            'N17' => $this->input->post('N17',TRUE),
            'J17' => $this->input->post('J17',TRUE),
            'N18' => $this->input->post('N18',TRUE),
            'J18' => $this->input->post('J18',TRUE),
            'N19' => $this->input->post('N19',TRUE),
            'J19' => $this->input->post('J19',TRUE),
            'N20' => $this->input->post('N20',TRUE),
            'J20' => $this->input->post('J20',TRUE),
            'N21' => $this->input->post('N21',TRUE),
            'J21' => $this->input->post('J21',TRUE),
            'N22' => $this->input->post('N22',TRUE),
            'J22' => $this->input->post('J22',TRUE),
            'N23' => $this->input->post('N23',TRUE),
            'J23' => $this->input->post('J23',TRUE),
            'N24' => $this->input->post('N24',TRUE),
            'J24' => $this->input->post('J24',TRUE),
            'JUMLAH' => $this->input->post('JUMLAH',TRUE),
            'KET1' => $this->input->post('KET1',TRUE),
            'KET2' => $this->input->post('KET2',TRUE),
            'KET3' => $this->input->post('KET3',TRUE),
            'FLAG' => 'INVC',
            'DR' => $this->session->userdata['dr'],
            'USRNM' => $this->session->userdata['username'],
            'I_TGL' => date('Y-m-d H:i:s'),
        );
        $this->master_model->input_data('sp_invenc',$data);
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Inventaris_Cetakan/index_Transaksi_Inventaris_Cetakan');
    }

    public function update($no_id) {
        $where = array('NO_ID' => $no_id);
        $ambildata = $this->master_model->edit_data($where,'sp_invenc');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'CETAK' => $r['CETAK'],
            'NAMA' => $r['NAMA'],
            'KODE' => $r['KODE'],
            'NO_URUT' => $r['NO_URUT'],
            'N1' => $r['N1'],
            'J1' => $r['J1'],
            'N2' => $r['N2'],
            'J2' => $r['J2'],
            'N3' => $r['N3'],
            'J3' => $r['J3'],
            'N4' => $r['N4'],
            'J4' => $r['J4'],
            'N5' => $r['N5'],
            'J5' => $r['J5'],
            'N6' => $r['N6'],
            'J6' => $r['J6'],
            'N7' => $r['N7'],
            'J7' => $r['J7'],
            'N8' => $r['N8'],
            'J8' => $r['J8'],
            'N9' => $r['N9'],
            'J9' => $r['J9'],
            'N10' => $r['N10'],
            'J10' => $r['J10'],
            'N11' => $r['N11'],
            'J11' => $r['J11'],
            'N12' => $r['N12'],
            'J12' => $r['J12'],
            'N13' => $r['N13'],
            'J13' => $r['J13'],
            'N14' => $r['N14'],
            'J14' => $r['J14'],
            'N15' => $r['N15'],
            'J15' => $r['J15'],
            'N16' => $r['N16'],
            'J16' => $r['J16'],
            'N17' => $r['N17'],
            'J17' => $r['J17'],
            'N18' => $r['N18'],
            'J18' => $r['J18'],
            'N19' => $r['N19'],
            'J19' => $r['J19'],
            'N20' => $r['N20'],
            'J20' => $r['J20'],
            'N21' => $r['N21'],
            'J21' => $r['J21'],
            'N22' => $r['N22'],
            'J22' => $r['J22'],
            'N23' => $r['N23'],
            'J23' => $r['J23'],
            'N24' => $r['N24'],
            'J24' => $r['J24'],
            'JUMLAH' => $r['JUMLAH'],
            'KET1' => $r['KET1'],
            'KET2' => $r['KET2'],
            'KET3' => $r['KET3'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Inventaris_Cetakan/Transaksi_Inventaris_Cetakan_update',$data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $no_id = $this->input->post('NO_ID');
        $data = array(
            'CETAK' => $this->input->post('CETAK',TRUE),
            'NAMA' => $this->input->post('NAMA',TRUE),
            'KODE' => $this->input->post('KODE',TRUE),
            'NO_URUT' => $this->input->post('NO_URUT',TRUE),
            'N1' => $this->input->post('N1',TRUE),
            'J1' => $this->input->post('J1',TRUE),
            'N2' => $this->input->post('N2',TRUE),
            'J2' => $this->input->post('J2',TRUE),
            'N3' => $this->input->post('N3',TRUE),
            'J3' => $this->input->post('J3',TRUE),
            'N4' => $this->input->post('N4',TRUE),
            'J4' => $this->input->post('J4',TRUE),
            'N5' => $this->input->post('N5',TRUE),
            'J5' => $this->input->post('J5',TRUE),
            'N6' => $this->input->post('N6',TRUE),
            'J6' => $this->input->post('J6',TRUE),
            'N7' => $this->input->post('N7',TRUE),
            'J7' => $this->input->post('J7',TRUE),
            'N8' => $this->input->post('N8',TRUE),
            'J8' => $this->input->post('J8',TRUE),
            'N9' => $this->input->post('N9',TRUE),
            'J9' => $this->input->post('J9',TRUE),
            'N10' => $this->input->post('N10',TRUE),
            'J10' => $this->input->post('J10',TRUE),
            'N11' => $this->input->post('N11',TRUE),
            'J11' => $this->input->post('J11',TRUE),
            'N12' => $this->input->post('N12',TRUE),
            'J12' => $this->input->post('J12',TRUE),
            'N13' => $this->input->post('N13',TRUE),
            'J13' => $this->input->post('J13',TRUE),
            'N14' => $this->input->post('N14',TRUE),
            'J14' => $this->input->post('J14',TRUE),
            'N15' => $this->input->post('N15',TRUE),
            'J15' => $this->input->post('J15',TRUE),
            'N16' => $this->input->post('N16',TRUE),
            'J16' => $this->input->post('J16',TRUE),
            'N17' => $this->input->post('N17',TRUE),
            'J17' => $this->input->post('J17',TRUE),
            'N18' => $this->input->post('N18',TRUE),
            'J18' => $this->input->post('J18',TRUE),
            'N19' => $this->input->post('N19',TRUE),
            'J19' => $this->input->post('J19',TRUE),
            'N20' => $this->input->post('N20',TRUE),
            'J20' => $this->input->post('J20',TRUE),
            'N21' => $this->input->post('N21',TRUE),
            'J21' => $this->input->post('J21',TRUE),
            'N22' => $this->input->post('N22',TRUE),
            'J22' => $this->input->post('J22',TRUE),
            'N23' => $this->input->post('N23',TRUE),
            'J23' => $this->input->post('J23',TRUE),
            'N24' => $this->input->post('N24',TRUE),
            'J24' => $this->input->post('J24',TRUE),
            'JUMLAH' => $this->input->post('JUMLAH',TRUE),
            'KET1' => $this->input->post('KET1',TRUE),
            'KET2' => $this->input->post('KET2',TRUE),
            'KET3' => $this->input->post('KET3',TRUE),
            'DR' => $this->session->userdata['dr'],
			'FLAG' => 'INVC',
            'E_PC' => $this->session->userdata['username'],
			'E_TGL' => date('Y-m-d H:i:s'),
        );
        $where = array(
            'NO_ID' => $no_id
        );
        $this->master_model->update_data($where,$data,'sp_invenc');
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Inventaris_Cetakan/index_Transaksi_Inventaris_Cetakan');
    }

    public function delete($no_id) {
        $where = array('NO_ID' => $no_id) ;
        $this->master_model->hapus_data($where,'sp_invenc');
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Inventaris_Cetakan/index_Transaksi_Inventaris_Cetakan');
    }

    function delete_multiple() {
        $this->master_model->remove_checked('sp_invenc');
        redirect('admin/Transaksi_Inventaris_Cetakan/index_Transaksi_Inventaris_Cetakan');
    }

    function JASPER($id)
    {
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Inventaris_Cetakan.jrxml");
        $cetak_1 = $this->input->post('CETAK_1');
        $dr = $this->session->userdata['dr'];
        $query = "SELECT * FROM sp_invenc WHERE NO_ID=$id";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "CETAK" => $row1["CETAK"],
                "NAMA" => $row1["NAMA"],
                "KODE" => $row1["KODE"],
                "N1" => $row1["N1"],
                "J1" => $row1["J1"],
                "N2" => $row1["N2"],
                "J2" => $row1["J2"],
                "N3" => $row1["N3"],
                "J3" => $row1["J3"],
                "N4" => $row1["N4"],
                "J4" => $row1["J4"],
                "N5" => $row1["N5"],
                "J5" => $row1["J5"],
                "N6" => $row1["N6"],
                "J6" => $row1["J6"],
                "N7" => $row1["N7"],
                "J7" => $row1["J7"],
                "N8" => $row1["N8"],
                "J8" => $row1["J8"],
                "N9" => $row1["N9"],
                "J9" => $row1["J9"],
                "N10" => $row1["N10"],
                "J10" => $row1["J10"],
                "N11" => $row1["N11"],
                "J11" => $row1["J11"],
                "N12" => $row1["N12"],
                "J12" => $row1["J12"],
                "N13" => $row1["N13"],
                "J13" => $row1["J13"],
                "N14" => $row1["N14"],
                "J14" => $row1["J14"],
                "N15" => $row1["N15"],
                "J15" => $row1["J15"],
                "N16" => $row1["N16"],
                "J16" => $row1["J16"],
                "N17" => $row1["N17"],
                "J17" => $row1["J17"],
                "N18" => $row1["N18"],
                "J18" => $row1["J18"],
                "N19" => $row1["N19"],
                "J19" => $row1["J19"],
                "N20" => $row1["N20"],
                "J20" => $row1["J20"],
                "KET1" => $row1["KET1"],
                "KET2" => $row1["KET2"],
                "KET3" => $row1["KET3"],
                "JUMLAH" => $row1["JUMLAH"],
                "DR" => $row1["DR"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}