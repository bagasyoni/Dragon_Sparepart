<?php

class Transaksi_PesananLBBA extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
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
        if ($this->session->userdata['menu_sparepart'] != 'pp') {
            $this->session->set_userdata('menu_sparepart', 'pp');
            $this->session->set_userdata('kode_menu', 'T0034');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'DEVISI', 'KET', 'PESAN');
    var $column_search = array('NO_BUKTI', 'TGL', 'DEVISI', 'KET', 'PESAN');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'FLAG' => 'PP',
            'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_LBBA',
        );
        $this->db->select('*');
        $this->db->from('pp');
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
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'FLAG' => 'PP',
            'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_LBBA',
        );
        $this->db->from('pp');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_pp()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $pp) {
            $JASPER = "window.open('JASPER/" . $pp->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $pp->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananLBBA/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananLBBA/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananLBBA/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = date("d-m-Y", strtotime($pp->TGL_DIMINTA));
            $row[] = $pp->DEVISI;
            $row[] = $pp->ARTICLE;
            $row[] = $pp->PESAN;
            $row[] = $pp->JO;
            $row[] = $pp->FLAG3;
            $row[] = $pp->GAMBAR1;
            $row[] = $pp->VAL;
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

    public function index_Transaksi_PesananLBBA()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'FLAG' => 'PP',
            'SUB' => 'MB',
            // 'FLAG2' => 'SP',
            // 'TYP' => 'RND_LBBA',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE SUB='MB' AND PER='$per' AND FLAG='PP' AND FLAG2='SP'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 3, 4);
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 4, "0", STR_PAD_LEFT);
        $tahun = substr($this->session->userdata['periode'], -4);
        if (substr($this->session->userdata['periode'], 0, 2) == 1) {
            $romawi = 'I';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 2) {
            $romawi = 'II';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 3) {
            $romawi = 'III';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 4) {
            $romawi = 'IV';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 5) {
            $romawi = 'V';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 6) {
            $romawi = 'VI';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 7) {
            $romawi = 'VII';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 8) {
            $romawi = 'VIII';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 9) {
            $romawi = 'IX';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 10) {
            $romawi = 'X';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 11) {
            $romawi = 'XI';
        }
        if (substr($this->session->userdata['periode'], 0, 2) == 12) {
            $romawi = 'XII';
        }
        // PP / NOMER / DR / BULAN / TAHUN / CNC
        // $bukti = 'PP' . '/' . $urut . '/' . "LB" . '/' . $dr . '/' . $romawi . '/' . $tahun;
        $bukti = 'PP' . '/' . $urut . '/' . $dr . '/' . "MB" .  '/' . $romawi . '/' . $tahun;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = 'IMG'.time().$_FILES['name'];
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
		}

        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'FLAG3' => $this->input->post('FLAG3', TRUE),
            'GAMBAR1' => $this->upload->data('file_name'),
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'TYP' => 'RND_LBBA',
            'SUB' => 'MB',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pp', $datah);
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananLBBA/index_Transaksi_PesananLBBA');
    }

    public function update($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pp');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'TGL' => $r['TGL'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'DEVISI' => $r['DEVISI'],
            'ARTICLE' => $r['ARTICLE'],
            'PESAN' => $r['PESAN'],
            'JO' => $r['JO'],
            'FLAG3' => $r['FLAG3'],
            'GAMBAR1' => $r['GAMBAR1'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = 'IMG'.time().$_FILES['name'];
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
            $GAMBAR1 = $this->input->post('G1', TRUE);
			$this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_form', $error);
		}else{
            $G1 = $this->input->post('G1', TRUE);
            unlink(FCPATH."gambar/".$G1);
            $data = array('upload_data' => $this->upload->data());
            $GAMBAR1 = $this->upload->data('file_name');
			// $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'FLAG3' => $this->input->post('FLAG3', TRUE),
            'GAMBAR1' => $GAMBAR1,
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananLBBA/index_Transaksi_PesananLBBA');
    }

    public function validasi($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pp');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'TGL' => $r['TGL'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'DEVISI' => $r['DEVISI'],
            'ARTICLE' => $r['ARTICLE'],
            'PESAN' => $r['PESAN'],
            'JO' => $r['JO'],
            'FLAG3' => $r['FLAG3'],
            'GAMBAR1' => $r['GAMBAR1'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_validasi', $data);
        $this->load->view('templates_admin/footer');
    }

    public function validasi_aksi()
    {

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
		$config['max_size']             = 1000;
		$config['max_width']            = 3024;
		$config['max_height']           = 3680;
        $new_name = time().$_FILES['name'];
        $config['file_name']            = $new_name; 

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananLBBA/Transaksi_PesananLBBA', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'PESAN' => $this->input->post('PESAN', TRUE),
            'JO' => $this->input->post('JO', TRUE),
            'FLAG3' => $this->input->post('FLAG3', TRUE),
            'GAMBAR1' => "IMG".$this->upload->data('file_name'),
            'VAL' => '1',
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Validasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananLBBA/index_Transaksi_PesananLBBA');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->transaksi_model->hapus_data($where, 'pp');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananLBBA/index_Transaksi_PesananLBBA');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp');
        redirect('admin/Transaksi_PesananLBBA/index_Transaksi_PesananLBBA');
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

    public function getDataAjax_dragon()
    {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_DEV, NM_DEV AS DEVISI, NAMA, AREA
            FROM rn_dev
            WHERE FLAG='CNC' AND (AREA LIKE '%$search%' OR NM_DEV LIKE '%$search%')
            GROUP BY AREA
            ORDER BY AREA LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['AREA'],
                'text' => $row['AREA'],
                'DR' => $row['AREA'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_PPStok.jrxml");
        $no_id = $id;
        $query = "SELECT pp.no_id as ID,
                pp.no_sp AS MODEL,
                pp.perke AS PERKE,
                pp.tgl_sp AS TGL_SP,
                pp.nodo AS NODO,
                pp.tgldo AS TGLDO,
                pp.tlusin AS TLUSIN,
                pp.tpair AS TPAIR,

                ppd.no_id AS NO_ID,
                ppd.rec AS REC,
                CONCAT(ppd.article,' - ',ppd.warna) AS ARTICLE,
                ppd.size AS SIZE,
                ppd.golong AS GOLONG,
                ppd.sisa AS SISA,
                ppd.lusin AS LUSIN,
                ppd.pair AS PAIR,
                CONCAT(ppd.kodecus,' - ',ppd.nama) AS KODECUS,
                ppd.kota AS KOTA
            FROM pp,ppd 
            WHERE pp.no_id=$id 
            AND pp.no_id=ppd.id 
            ORDER BY ppd.rec";
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
