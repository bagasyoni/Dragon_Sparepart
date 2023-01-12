<?php

class Transaksi_LPBBengkel extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'beli') {
			$this->session->set_userdata('menu_sparepart', 'beli');
			$this->session->set_userdata('kode_menu', 'T0004');
			$this->session->set_userdata('keyword_beli', '');
			$this->session->set_userdata('order_beli', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'TGL', 'NO_BUKTI', 'TGL', 'NAMAS', 'TTD2');
    var $column_search = array('TGL', 'NO_BUKTI', 'TGL', 'NAMAS', 'TTD2');
    var $order = array('NO_ID' => 'desc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'ATK' => '0',
        );
        $this->db->select('*');
        $this->db->from('beli');
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'ATK' => '0',
        );
        $this->db->from('beli');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_beli() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $beli) {
            $JASPER = "window.open('JASPER/" . $beli->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $beli->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_LPBBengkel/update/' . $beli->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_LPBBengkel/delete/' . $beli->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $beli->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($beli->TGL));
            $row[] = $beli->NAMAS;
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

    public function index_Transaksi_LPBBengkel() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi LPB Bengkel');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'ATK' => '0',
        );
        $data['beli'] = $this->transaksi_model->tampil_data($where,'beli','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_LPBBengkel/Transaksi_LPBBengkel', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input() {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM beli WHERE PER='$per' AND SP='$sub' AND FLAG='BL' AND FLAG2='SP'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 3, 7);
        $value22 = STRVAL($value11) . 1;
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
        // BL / NOMER / DR / BULAN / TAHUN / SP
        $bukti = 'SP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_LPBBengkel/Transaksi_LPBBengkel_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM beli WHERE PER='$per' AND SP='$sub' AND FLAG='BL' AND FLAG2='SP'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 3, 7);
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
        // BL / NOMER / DR / BULAN / TAHUN / SP
        // $bukti = 'SP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('beli', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM beli WHERE NO_BUKTI='$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $RAK = $this->input->post('RAK');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET = $this->input->post('KET');
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'REC' => $REC[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'RAK' => $RAK[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET' => $KET[$i],
                'FLAG' => 'BL',
                'FLAG2' => 'SP',
                'SUB' => $this->session->userdata['sub'],
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('belid', $datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM beli WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_beliins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_LPBBengkel/index_Transaksi_LPBBengkel');
    }

    public function update($id) {
        $q1="SELECT beli.NO_ID as ID,
                beli.NO_BUKTI AS NO_BUKTI,
                beli.NAMAS AS NAMAS,
                beli.TGL AS TGL,
                beli.TOTAL_QTY AS TOTAL_QTY,
                beli.PIN1 AS PIN1,
                beli.TTD2 AS TTD2,

                belid.NO_ID AS NO_ID,
                belid.REC AS REC,
                belid.KD_BHN AS KD_BHN,
                belid.NA_BHN AS NA_BHN,
                belid.RAK AS RAK,
                belid.QTY AS QTY,
                belid.SATUAN AS SATUAN,
                belid.KET AS KET
            FROM beli, belid 
            WHERE beli.NO_ID = $id 
            AND beli.NO_ID = belid.ID 
            ORDER BY belid.REC";
        $data['lpb_bengkel']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_LPBBengkel/Transaksi_LPBBengkel_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $bukti = $this->input->post('NO_BUKTI');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI',TRUE), 
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'ATK' => '0',
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM beli WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_belidel('" . $no_bukti . "')");
        $this->transaksi_model->update_data($where, $datah, 'beli');
        $id = $this->input->post('ID', TRUE);
        $q1="SELECT beli.NO_ID as ID,
                beli.NO_BUKTI AS NO_BUKTI,
                beli.NAMAS AS NAMAS,
                beli.TGL AS TGL,
                beli.TOTAL_QTY AS TOTAL_QTY,
                beli.TTD1 AS TTD1,

                belid.NO_ID AS NO_ID,
                belid.REC AS REC,
                belid.KD_BHN AS KD_BHN,
                belid.NA_BHN AS NA_BHN,
                belid.RAK AS RAK,
                belid.SISA AS QTY,
                belid.SATUAN AS SATUAN,
                belid.KET AS KET
            FROM beli, belid 
            WHERE beli.NO_ID = $id 
            AND beli.NO_ID = belid.ID 
            ORDER BY belid.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $RAK = $this->input->post('RAK');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $SATUAN = $this->input->post('SATUAN');
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
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$URUT],
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'RAK' => $RAK[$URUT],
                    'QTY' => str_replace(',','',$QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET' => $KET[$URUT],
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
                    'ATK' => '0',
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'belid');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'belid');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'RAK' => $RAK[$i],
                    'QTY' => str_replace(',','',$QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET' => $KET[$i],
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
                    'ATK' => '0',
                );
                $this->transaksi_model->input_datad('belid', $datad);
            }
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM beli WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_beliins('" . $no_bukti . "')");
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_LPBBengkel/index_Transaksi_LPBBengkel');
    }

    public function delete($id) {
        $data = $this->db->query("SELECT NO_BUKTI FROM beli WHERE NO_ID='$id'")->result();
        $this->db->query("CALL spp_belidel('" . $data[0]->NO_BUKTI . "')");
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'beli');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'belid');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_LPBBengkel/index_Transaksi_LPBBengkel');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('beli', 'belid');
        redirect('admin/Transaksi_LPBBengkel/index_Transaksi_LPBBengkel');
    }

    function filter_bl() {}

    public function getDataAjax_bhn() {
        $dr = $this->session->userdata['dr'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT bhn.NO_ID AS NO_ID, bhn.KD_BHN AS KD_BHN, bhn.NA_BHN AS NA_BHN, bhn.SATUAN AS SATUAN, bhnd.RAK AS RAK
            FROM bhn, bhnd
            WHERE bhn.KD_BHN = bhnd.KD_BHN AND bhn.DR = '$dr' AND bhn.FLAG = 'SP' AND bhnd.sub='SP' AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%' OR bhn.SATUAN LIKE '%$search%' OR bhnd.RAK LIKE '%$search%')
            GROUP BY bhnd.KD_BHN 
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['KD_BHN'],
                'text' => $row['KD_BHN'],
                'KD_BHN' => $row['KD_BHN'] . " - " . $row['NA_BHN'] . " - " . $row['RAK'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUAN' => $row['SATUAN'],
                'RAK' => $row['RAK'],
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_LPBBengkel.jrxml");
        $no_id = $id;
        $query = "SELECT beli.no_id as ID,
                beli.no_sp AS MODEL,
                beli.perke AS PERKE,
                beli.tgl_sp AS TGL_SP,
                beli.nodo AS NODO,
                beli.tgldo AS TGLDO,
                beli.tlusin AS TLUSIN,
                beli.tpair AS TPAIR,

                belid.no_id AS NO_ID,
                belid.rec AS REC,
                CONCAT(belid.article,' - ',belid.warna) AS ARTICLE,
                belid.size AS SIZE,
                belid.golong AS GOLONG,
                belid.sisa AS SISA,
                belid.lusin AS LUSIN,
                belid.pair AS PAIR,
                CONCAT(belid.kodecus,' - ',belid.nama) AS KODECUS,
                belid.kota AS KOTA
            FROM beli,belid 
            WHERE beli.no_id=$id 
            AND beli.no_id=belid.id 
            ORDER BY belid.rec";
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

    function prev()
    {
        $ID = $this->input->get('ID');
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];


        $q1 = " SELECT NO_ID FROM beli WHERE NO_ID<'$ID' AND FLAG = 'BL' AND FLAG2 = 'SP' AND PER='$per' AND ATK = '0' AND DR = '$dr' SUB = '$sub' ORDER BY NO_ID DESC LIMIT 1";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    function next()
    {
        $ID = $this->input->get('ID');
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];

        $q1 = " SELECT NO_ID FROM beli WHERE NO_ID>'$ID' AND FLAG = 'BL' AND FLAG2 = 'SP' AND PER='$per' AND ATK = '0' AND DR = '$dr' SUB = '$sub' ORDER BY NO_ID LIMIT 1";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

}