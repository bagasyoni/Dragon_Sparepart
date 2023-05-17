<?php

class Transaksi_Verifikasi_SPIK extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!isset($this->session->userdata['username'])) {
            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Anda Belum Login
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
            );
            redirect('admin/auth');
        }
        if ($this->session->userdata['menu_sparepart'] != 'ppc_spik') {
            $this->session->set_userdata('menu_sparepart', 'ppc_spik');
            $this->session->set_userdata('kode_menu', 'T0036');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'KET');
    var $column_search = array('NO_BUKTI', 'TGL', 'KET');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            // 'DR' => $dr,
            'TUJUAN' => $dr,
            // 'PER' => $per,
            // 'SUB' => 'PSL',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'VAL' => '0',
            // 'TYP' => 'RND_PISAU',
        );
        $this->db->select('*');
        $this->db->from('ppc_spik');
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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $where = array(
            // 'DR' => $dr,
            'TUJUAN' => $dr,
            // 'PER' => $per,
            // 'SUB' => 'PSL',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'VAL' => '0',
            // 'TYP' => 'RND_PISAU',
        );
        $this->db->from('ppc_spik');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_order()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $ppc_spik) {
            $JASPER = "window.open('JASPER/" . $ppc_spik->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $ppc_spik->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_SPIK/update/' . $ppc_spik->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $ppc_spik->no_bukti;
            $row[] = $ppc_spik->no_order;
            $row[] = date("d-m-Y", strtotime($ppc_spik->tgl));
            $row[] = $ppc_spik->gambar1;
            $row[] = $ppc_spik->dr;
            $row[] = $ppc_spik->tujuan;
            // $row[] = "<img src='/Dragon_Sparepart_baru/gambar/pesananpisausample/$ppc_spik->GAMBAR' width='auto' height='120'>";
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

    public function index_Transaksi_Verifikasi_SPIK()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $this->session->set_userdata('judul', 'Transaksi Verifikasi Order Sample');
        $where = array(
            // 'DR' => $dr,
            'PER' => $per,
            // 'SUB' => 'PSL',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'VAL' => '0',
            // 'TYP' => 'RND_PISAU',
        );
        $data['ppc_spik'] = $this->transaksi_model->tampil_data($where, 'ppc_spik', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_SPIK/Transaksi_Verifikasi_SPIK', $data);
        $this->load->view('templates_admin/footer');
    }

    public function delete($id)
    {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'ppc_spik');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'ppc_spikd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Verifikasi_SPIK/index_Transaksi_Verifikasi_SPIK');
    }

    // function delete_multiple()
    // {
    //     $this->transaksi_model->remove_checked('ppc_spik', 'ppc_spikd');
    //     redirect('admin/Transaksi_PesananPisau/index_Transaksi_Verifikasi_SPIK');
    // }

    public function update($id)
    {
        $q1 = "SELECT ppc_spik.NO_ID as ID,
                ppc_spik.NO_BUKTI AS NO_BUKTI,
                ppc_spik.NO_ORDER AS NO_ORDER,
                ppc_spik.TGL AS TGL,
                ppc_spik.DR AS DR,
                ppc_spik.TUJUAN AS TUJUAN,
                
                ppc_spikd.NO_ID AS NO_ID,
                ppc_spikd.REC AS REC,
                ppc_spikd.GAMBAR1 AS GAMBAR1,
                ppc_spikd.MODEL AS MODEL
        FROM ppc_spik, ppc_spikd 
        WHERE ppc_spik.NO_ID=$id 
        AND ppc_spik.NO_ID=ppc_spikd.ID 
        ORDER BY ppc_spikd.REC";

        // SELECT ppc_spik.NO_ID as ID,
        //         ppc_spik.NO_BUKTI AS NO_BUKTI,
        //         ppc_spik.TGL AS TGL,
        //         ppc_spik.TGL_DIMINTA AS TGL_DIMINTA_H,
        //         ppc_spik.KODE_DEVISI AS KODE_DEVISI,
        //         ppc_spik.KET AS KET,
        //         ppc_spik.JENIS_SAMPLE AS JENIS_SAMPLE,
        //         ppc_spik.JENIS_ORDER AS JO,
        //         ppc_spik.TUJUAN AS TS,
        //         -- ppc_spik.TOTAL_QTY AS TOTAL_QTY,
        //         -- ppc_spik.VAL AS VAL,
                
        //         ppc_spikd.NO_ID AS NO_ID,
        //         ppc_spikd.REC AS REC,
        //         ppc_spikd.ARTICLE AS NA_BHN,
        //         ppc_spikd.SIZE AS SIZE,
        //         ppc_spikd.QTY AS QTY,
        //         ppc_spikd.SATUAN AS SATUAN,
        //         ppc_spikd.KET AS KET1,
        //     FROM ppc_spik,ppc_spikd 
        //     WHERE ppc_spik.NO_ID=$id 
        //     AND ppc_spik.NO_ID=ppc_spikd.ID 
        //     ORDER BY ppc_spikd.REC";
        $data['rnd'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_SPIK/Transaksi_Verifikasi_SPIK_update', $data);
        $this->load->view('templates_admin/footer');
    }

    function validasi()
    {
        $delete = $this->input->post('check');
        for ($i = 0; $i < count($delete); $i++) {
            $this->db->query("UPDATE ppc_spik SET VAL=1 where no_id=$delete[$i]");
        }
        redirect('admin/Transaksi_Verifikasi_SPIK/index_Transaksi_Verifikasi_SPIK');
    }

    public function getDataAjax_bhn()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT bhn.NO_ID, bhn.KD_BHN, bhn.NA_BHN, bhn.SATUAN
            FROM bhn, bhnd
            WHERE bhn.KD_BHN=bhnd.KD_BHN AND bhnd.FLAG='SP' AND bhnd.DR = '$dr' AND bhnd.SUB='$sub' AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%')
            GROUP BY bhn.KD_BHN
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['KD_BHN'],
                'text' => $row['KD_BHN'],
                'KD_BHN' => $row['KD_BHN'] . " - " . $row['NA_BHN'] . " - " . $row['SATUAN'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUAN' => $row['SATUAN'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_dr()
    {
        $dr = $this->session->userdata['dr'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_DEV, NM_DEV, FLAG, NAMA, AREA
            FROM rn_dev
            WHERE (KD_DEV LIKE '%$search%' OR NM_DEV LIKE '%$search%')
            AND KD_DEV='$dr'
            ORDER BY KD_DEV 
            LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NM_DEV'],
                'text' => $row['NM_DEV'],
                'DR' => $row['NM_DEV'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function upload_image()
    {
		$config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;

        $this->load->library('GAMBAR', $config);
 
		if ( ! $this->upload->do_upload('GAMBAR')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananPisau/Transaksi_PesananPisau_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananPisau/index_Transaksi_PesananPisau', $data);
		}
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Validasi_OrderSample.jrxml");
        $no_id = $id;
        $query = "SELECT ppc_spik.NO_ID as ID,
                ppc_spik.NO_BUKTI AS NO_BUKTI,
                date_format(ppc_spik.TGL,'%d-%m-%Y') AS TGL,
                date_format(ppc_spik.TGL_DIMINTA,'%d-%m-%Y') AS TGL_DIMINTA,
                date_format(ppc_spik.TGL_SELESAI,'%d-%m-%Y') AS TGL_SELESAI,
                ppc_spik.KODE_DEVISI AS KODE_DEVISI,
                ppc_spik.KET AS KET,
                ppc_spik.JENIS_SAMPLE AS JENIS_SAMPLE,
                ppc_spik.JENIS_ORDER AS JENIS_ORDER,
                ppc_spik.TUJUAN AS TUJUAN,
                ppc_spik.TOTAL_QTY AS TOTAL_QTY,
                ppc_spik.VAL AS VAL,
                ppc_spik.NM_TTD1 AS TTD1_USR, 
                ppc_spik.NM_TTD2 AS TTD2_USR, 
                ppc_spik.NM_TTD3 AS TTD3_USR,
                ppc_spik.TG_TTD1 AS TTD1_SMP,
                ppc_spik.TG_TTD2 AS TTD2_SMP,
                ppc_spik.TG_TTD3 AS TTD3_SMP,
                
                ppc_spikd.NO_ID AS NO_ID,
                ppc_spikd.REC AS REC,
                ppc_spikd.ARTICLE AS ARTICLE,
                ppc_spikd.WARNA AS WARNA,
                ppc_spikd.OUTSOLE AS OUTSOLE,
                ppc_spikd.SIZE AS SIZE,
                ppc_spikd.JENIS AS JENIS,
                ppc_spikd.QTY AS QTY,
                ppc_spikd.SATUAN AS SATUAN,
                ppc_spikd.KET AS KET2
        FROM ppc_spik, ppc_spikd 
        WHERE ppc_spik.NO_ID=$id 
        AND ppc_spik.NO_ID=ppc_spikd.ID 
        ORDER BY ppc_spikd.REC";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "TGL" => $row1["TGL"],
                "TGL_DIMINTA" => $row1["TGL_DIMINTA"],
                "TGL_SELESAI" => $row1["TGL_SELESAI"],
                "KET" => $row1["KET"],
                "JENIS_SAMPLE" => $row1["JENIS_SAMPLE"],
                "JENIS_ORDER" => $row1["JENIS_ORDER"],
                "REC" => $row1["REC"],
                "TUJUAN" => $row1["TUJUAN"],
                "RND" => $row1["TTD1_USR"],
                "GM" => $row1["TTD2_USR"],
                "MARKET" => $row1["TTD3_USR"],
                "TG_RND" => $row1["TTD1_SMP"],
                "TG_GM" => $row1["TTD2_SMP"],
                "TG_MARKET" => $row1["TTD3_SMP"],
                "ARTICLE" => $row1["ARTICLE"],
                "WARNAC" => $row1["WARNA"],
                "OUTSOLE" => $row1["OUTSOLE"],
                "SIZE" => $row1["SIZE"],
                "JENIS" => $row1["JENIS"],
                "QTY" => $row1["QTY"],
                "KET2" => $row1["KET2"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
