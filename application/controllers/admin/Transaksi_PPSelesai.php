<?php

class Transaksi_PPSelesai extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'pp') {
            $this->session->set_userdata('menu_sparepart', 'pp');
            $this->session->set_userdata('kode_menu', 'T0026');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'TGL_DIMINTA', 'NA_BRG', 'NOTES', 'OK');
    var $column_search = array('NO_BUKTI', 'TGL', 'TGL_DIMINTA', 'NA_BRG', 'NOTES', 'OK');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'IN_CNC',
        );
        $filter = array('BL_CNC');
        $this->db->select('*');
        $this->db->from('pp');
        $this->db->where($where);
        $this->db->or_where_in('TYP', $filter);
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP <>' => 'BOR_CNC',
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
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PPSelesai/update/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PPSelesai/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pp->NA_BRG;
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = date("d-m-Y", strtotime($pp->TGL_DIMINTA));
            $row[] = $pp->NOTES;
            $row[] = $pp->OK;
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

    public function index_Transaksi_PPSelesai()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi PP Selesai');
        $where = array(
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP <>' => 'BOR_CNC',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PPSelesai/Transaksi_PPSelesai', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update($id)
    {
        $q1 = "SELECT pp.NO_ID as ID,
                pp.DEVISI AS DEVISI,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.NOTES AS NOTES,
                pp.NA_BRG AS NA_BRG,
                pp.DR AS DR,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.VAL AS VAL,
                pp.OK AS OK,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SERI AS SERI,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.HARGA AS HARGA,
                ppd.TOTAL AS TOTAL,
                ppd.KET AS KET
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data['cnc'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PPSelesai/Transaksi_PPSelesai_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'NA_BRG' => $this->input->post('NA_BRG', TRUE),
            'DR' => $this->input->post('DR', TRUE),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'OK' => '1',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT pp.NO_ID as ID,
                pp.DEVISI AS DEVISI,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.NOTES AS NOTES,
                pp.NA_BRG AS NA_BRG,
                pp.DR AS DR,
                pp.TOTAL_QTY AS TOTAL_QTY,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NA_BHN AS NA_BHN,
                ppd.SERI AS SERI,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.HARGA AS HARGA,
                ppd.TOTAL AS TOTAL,
                ppd.KET AS KET
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $NA_BHN = $this->input->post('NA_BHN');
        $SERI = $this->input->post('SERI');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $HARGA = str_replace(',', '', $this->input->post('HARGA', TRUE));
        $TOTAL = str_replace(',', '', $this->input->post('TOTAL', TRUE));
        $KET = $this->input->post('KET');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'SERI' => $SERI[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'HARGA' => str_replace(',', '', $HARGA[$URUT]),
                    'TOTAL' => str_replace(',', '', $TOTAL[$URUT]),
                    'KET' => $KET[$URUT],
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'LOGISTIK' => '0',
                    'SUB' => $this->session->userdata['sub'],
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'ppd');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'ppd');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'SERI' => $SERI[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'HARGA' => str_replace(',', '', $HARGA[$i]),
                    'TOTAL' => str_replace(',', '', $TOTAL[$i]),
                    'KET' => $KET[$i],
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'LOGISTIK' => '0',
                    'SUB' => $this->session->userdata['sub'],
                    'DR' => $this->session->userdata['dr'],
                    'PER' => $this->session->userdata['periode'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('ppd', $datad);
            }
            $i++;
        }
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PPSelesai/index_Transaksi_PPSelesai');
    }

    public function delete($id)
    {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pp');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'ppd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PPSelesai/index_Transaksi_PPSelesai');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp', 'ppd');
        redirect('admin/Transaksi_PPSelesai/index_Transaksi_PPSelesai');
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
