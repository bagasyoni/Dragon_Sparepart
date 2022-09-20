<?php

class Transaksi_Koreksi_Stok extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'stocka') {
			$this->session->set_userdata('menu_sparepart', 'stocka');
			$this->session->set_userdata('kode_menu', 'T0009');
			$this->session->set_userdata('keyword_stocka', '');
			$this->session->set_userdata('order_stocka', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'TOTAL_QTY');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'TOTAL_QTY');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'FLAG' => 'LN',
        );
        $this->db->select('*');
        $this->db->from('stocka');
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
            'DR' => $dr,
            'PER' => $per,
            'FLAG' => 'LN',
        );
        $this->db->from('stocka');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_stocka() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $stocka) {
            $JASPER = "window.open('JASPER/" . $stocka->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $stocka->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Koreksi_Stok/update/' . $stocka->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Koreksi_Stok/delete/' . $stocka->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $stocka->NO_BUKTI;
            $row[] = $stocka->TGL;
            $row[] = $stocka->NOTES;
            $row[] = $stocka->TOTAL_QTY;
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

    public function index_Transaksi_Koreksi_Stok() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $this->session->set_userdata('judul', 'Transaksi Koreksi Stok');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'FLAG' => 'LN',
        );
        $data['stocka'] = $this->transaksi_model->tampil_data($where,'stocka','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Koreksi_Stok/Transaksi_Koreksi_Stok', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input() {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Koreksi_Stok/Transaksi_Koreksi_Stok_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $flag = 'LN'; 
        $dr = $this->session->userdata['dr']; 
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $xx = $this->db->query("CALL NO_BUKTI_KARTU_STOK_SPAREPART('SPAREPART_$flag','kartustok','$flag','$per','$dr','$sub')")->result();
        mysqli_next_result($this->db->conn_id);
        $bukti = $xx[0]->BUKTIX;
        $datah = array(
            'FLAG' => 'LN',
            'ATK' => '0',
            'NO_BUKTI' => $bukti, 
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'TOTAL_QTY_AK' => str_replace(',','',$this->input->post('TOTAL_QTY_AK',TRUE)),
            'SP' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'I_TGL' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('stocka',$datah);
        $ID= $this->db ->query("SELECT MAX(no_id) AS no_id FROM stocka WHERE no_bukti = '$bukti' AND dr='$dr' AND per='$per' GROUP BY no_bukti")->result();
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $QTY_AK = str_replace(',','',$this->input->post('QTY_AK',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $i = 0;
        foreach($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->no_id,
                'NO_BUKTI' => $bukti,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                'FLAG' => 'LN',
                'ATK' => '0',
                'REC' => $REC[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'QTY' => str_replace(',','',$QTY[$i]),
                'QTY_AK' => str_replace(',','',$QTY_AK[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET1' => $KET1[$i],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'DR' => $this->session->userdata['dr'],
                'SP' => $this->session->userdata['sub'],
                'I_TGL' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('stockad',$datad);
            $i++;
        }
        // $xx = $this->db->query("SELECT NO_BUKTI as BUKTIX from stocka where NO_BUKTI='$bukti'")->result();
        // $no_bukti = $xx[0]->BUKTIX;
        // $this->db->query("CALL SP_KOREKSISTOK('" .$no_bukti."')");
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Koreksi_Stok/index_Transaksi_Koreksi_Stok'); 
    }

    public function update($id) {
        $q1="SELECT stocka.NO_ID as ID,
                stocka.NO_BUKTI AS NO_BUKTI,
                stocka.NOTES AS NOTES,
                stocka.TGL AS TGL,
                stocka.TOTAL_QTY AS TOTAL_QTY,
                stocka.TOTAL_QTY_AK AS TOTAL_QTY_AK,

                stockad.NO_ID AS NO_ID,
                stockad.REC AS REC,
                stockad.KD_BHN AS KD_BHN,
                stockad.NA_BHN AS NA_BHN,
                stockad.QTY AS QTY,
                stockad.QTY_AK AS QTY_AK,
                stockad.SATUAN AS SATUAN,
                stockad.KET1 AS KET1
            FROM stocka,stockad 
            WHERE stocka.NO_ID=$id 
            AND stocka.NO_ID=stockad.id 
            ORDER BY stockad.rec";
        $data['transaksi_koreksi_stok']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Koreksi_Stok/Transaksi_Koreksi_Stok_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $NO_BUKTI= $this->input->post('NO_BUKTI');
        $datah = array(
            'FLAG' => 'LN',
            'ATK' => '0',
            'NO_BUKTI' => $this->input->post('NO_BUKTI',TRUE),             
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'TOTAL_QTY_AK' => str_replace(',','',$this->input->post('TOTAL_QTY_AK',TRUE)),
            'PER' => $this->session->userdata['periode'],
            'SP' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'E_PC' => $this->session->userdata['username'],
            'E_TGL' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'stocka');
        $id = $this->input->post('ID', TRUE);
        $q1="SELECT stocka.NO_ID as ID,
                stocka.NO_BUKTI AS NO_BUKTI,
                stocka.NOTES AS NOTES,
                stocka.TGL AS TGL,
                stocka.TOTAL_QTY AS TOTAL_QTY,
                stocka.TOTAL_QTY_AK AS TOTAL_QTY_AK,

                stockad.NO_ID AS NO_ID,
                stockad.REC AS REC,
                stockad.KD_BHN AS KD_BHN,
                stockad.NA_BHN AS NA_BHN,
                stockad.QTY AS QTY,
                stockad.QTY_AK AS QTY_AK,
                stockad.SATUAN AS SATUAN,
                stockad.KET1 AS KET1
            FROM stocka,stockad 
            WHERE stocka.NO_ID=$id 
            AND stocka.NO_ID=stockad.id 
            ORDER BY stockad.rec";        
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $QTY_AK = str_replace(',','',$this->input->post('QTY_AK',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'FLAG' => 'LN',
                    'ATK' => '0',
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$URUT],
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'QTY' => str_replace(',','',$QTY[$URUT]),
                    'QTY_AK' => str_replace(',','',$QTY_AK[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SP' => $this->session->userdata['sub'],
                    'E_PC' => $this->session->userdata['username'],
                    'E_TGL' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'stockad');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'stockad');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'FLAG' => 'LN',
                    'ATK' => '0',
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'QTY' => str_replace(',','',$QTY[$i]),
                    'QTY_AK' => str_replace(',','',$QTY_AK[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET1' => $KET1[$i],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SP' => $this->session->userdata['sub'],
                    'E_PC' => $this->session->userdata['username'],
                    'E_TGL' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('stockad', $datad);
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
        redirect('admin/Transaksi_Koreksi_Stok/index_Transaksi_Koreksi_Stok');
    }

    public function delete($id) {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'stocka');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'stockad');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Koreksi_Stok/index_Transaksi_Koreksi_Stok');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('stocka', 'stockad');
        redirect('admin/Transaksi_Koreksi_Stok/index_Transaksi_Koreksi_Stok');
    }

    public function getDataAjax_Bahan() {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT bhn.no_id, bhn.KD_BHN AS kd_bhn, bhn.NA_BHN AS na_bhn, bhn.SATUAN AS satuan, bhnd.AW01 AS qty 
            FROM bhn, bhnd
            WHERE bhn.kd_bhn=bhnd.kd_bhn AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%')
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['kd_bhn'],
                'text' => $row['kd_bhn'],
                'kd_bhn' => $row['kd_bhn'] . " - " . $row['na_bhn']. " - " . $row['satuan']. " - " . $row['qty'],
                'na_bhn' => $row['na_bhn'],
                'satuan' => $row['satuan'],
                'qty' => $row['qty'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Bon_Pemakaian.jrxml");
        $no_id = $id;
        $query = "SELECT sp_pakai.no_id as ID,
                sp_pakai.no_sp AS MODEL,
                sp_pakai.perke AS PERKE,
                sp_pakai.tgl_sp AS TGL_SP,
                sp_pakai.nodo AS NODO,
                sp_pakai.tgldo AS TGLDO,
                sp_pakai.tlusin AS TLUSIN,
                sp_pakai.tpair AS TPAIR,

                sp_pakaid.no_id AS NO_ID,
                sp_pakaid.rec AS REC,
                CONCAT(sp_pakaid.article,' - ',sp_pakaid.warna) AS ARTICLE,
                sp_pakaid.size AS SIZE,
                sp_pakaid.golong AS GOLONG,
                sp_pakaid.sisa AS SISA,
                sp_pakaid.lusin AS LUSIN,
                sp_pakaid.pair AS PAIR,
                CONCAT(sp_pakaid.kodecus,' - ',sp_pakaid.nama) AS KODECUS,
                sp_pakaid.kota AS KOTA
            FROM sp_pakai,sp_pakaid 
            WHERE sp_pakai.no_id=$id 
            AND sp_pakai.no_id=sp_pakaid.id 
            ORDER BY sp_pakaid.rec";
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