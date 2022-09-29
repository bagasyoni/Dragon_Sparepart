<?php

class Transaksi_Validasi_LPB extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'beli') {
            $this->session->set_userdata('menu_sparepart', 'beli');
            $this->session->set_userdata('kode_menu', 'T0003');
            $this->session->set_userdata('keyword_beli', '');
            $this->session->set_userdata('order_beli', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'TGL', 'NO_BUKTI', 'TGL', 'NAMAS', 'TTD2');
    var $column_search = array('TGL', 'NO_BUKTI', 'TGL', 'NAMAS', 'TTD2');
    var $order = array('NO_ID' => 'desc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            // 'SP' => 'LPB',
            'OK<>' => '1'
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            // 'SP' => 'LPB',
        );
        $this->db->from('beli');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_beli()
    {
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
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Validasi_LPB/update/' . $beli->NO_ID) . '"> <i class="fa fa-edit"></i> Validasi</a>
                            </div>
                            </div>';
            // <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
            $row[] = $no . ".";
            $row[] = date("d-m-Y", strtotime($beli->TGL));
            $row[] = $beli->NO_BUKTI;
            $row[] = $beli->NAMAS;
            $row[] = $beli->DR;
            $ok = $beli->OK;
            if ($ok == 1) {
                $keterangan = 'STOK';
            } elseif ($ok == 2) {
                $keterangan = 'NON STOK';
            } else {
                $keterangan = '-';
            }
            $row[] = $keterangan;
            $val = $beli->VAL;
            if ($val == 1) {
                $status = 'VALIDASI';
            } else {
                $status = 'BELUM VALIDASI';
            }
            $row[] = $status;
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

    public function index_Transaksi_Validasi_LPB()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Validasi LPB');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            // 'SP' => 'LPB',
        );
        $data['beli'] = $this->transaksi_model->tampil_data($where, 'beli', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Validasi_LPB/Transaksi_Validasi_LPB', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update($id)
    {
        $q1 = "SELECT beli.NO_ID as ID,
                beli.NO_BUKTI,
                beli.NAMAS,
                beli.TGL,
                beli.PIN2,
                beli.TOTAL_QTY,
                beli.VAL,
                beli.TTD1,
                beli.TTD2,
                beli.TTD3,
                beli.TTD4,
                beli.TTD5,
                beli.TTD6,
                beli.OK,

                belid.NO_ID,
                belid.REC,
                belid.OK,
                belid.KD_BHN,
                belid.NA_BHN,
                belid.RAK,
                belid.SISA AS QTY,
                belid.SATUAN,
                belid.SAT_BL,
                belid.QTY,
                belid.QTY_BL,
                belid.VAL
            FROM beli, belid 
            WHERE beli.NO_ID = $id 
            AND beli.NO_ID = belid.ID 
            ORDER BY belid.REC";
        $data['Validasi_LPB'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Validasi_LPB/Transaksi_Validasi_LPB_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTYPP' => str_replace(',', '', $this->input->post('TOTAL_QTYPP', TRUE)),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'beli');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT beli.NO_ID as ID,
                beli.NO_BUKTI,
                beli.NAMAS,
                beli.TGL,
                beli.PIN2,
                beli.TOTAL_QTY,
                beli.VAL,
                beli.TTD1,
                beli.TTD2,
                beli.TTD3,
                beli.TTD4,
                beli.TTD5,
                beli.TTD6,
                beli.OK,

                belid.NO_ID,
                belid.REC,
                belid.OK,
                belid.KD_BHN,
                belid.NA_BHN,
                belid.RAK,
                belid.SISA AS QTY,
                belid.SATUAN,
                belid.SAT_BL,
                belid.QTY,
                belid.QTY_BL,
                belid.VAL
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
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $QTY_BL = str_replace(',', '', $this->input->post('QTY_BL', TRUE));
        $SAT_BL = $this->input->post('SAT_BL');
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
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
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'RAK' => $RAK[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'QTY_BL' => str_replace(',', '', $QTY_BL[$URUT]),
                    'SAT_BL' => $SAT_BL[$URUT],
                    'SISA' => str_replace(',', '', $SISA[$URUT]),
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
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
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'RAK' => $RAK[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'QTY_BL' => str_replace(',', '', $QTY_BL[$i]),
                    'SAT_BL' => $SAT_BL[$i],
                    'SISA' => str_replace(',', '', $SISA[$i]),
                    'QTY_BL' => str_replace(',', '', $QTY_BL[$i]),
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
                );
                $this->transaksi_model->input_datad('belid', $datad);
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
        redirect('admin/Transaksi_Validasi_LPB/index_Transaksi_Validasi_LPB');
    }

    public function verifikasi_pin($ID)
    {
        $pin = $this->session->userdata['pin'];
        $username = $this->session->userdata['username'];
        $datah = array(
            'VAL' => '1',
            'PIN2' => $pin,
            'TTD2' => '1',
            'TTD2_USR' => $username,
            'TTD2_SMP' => date("Y-m-d h:i a"),
            'OK' => '2',
        );
        $where = array(
            'NO_ID' => "$ID"
        );
        $this->transaksi_model->update_data($where, $datah, 'beli');

        $datahd = array(
            'VAL' => '1',
            'PIN2' => $pin,
            'TTD2' => '1',
            'TTD2_USR' => $username,
            'TTD2_SMP' => date("Y-m-d h:i a"),
            'OK' => '2',
        );
        $whered = array(
            'ID' => "$ID"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'belid');

        // $bukti = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM beli WHERE NO_ID='$ID'")->result();
        // $no_bukti = $bukti[0]->BUKTIX;
        // $this->db->query("CALL spp_beliins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-warning alert-dismissible fade show" role="alert"> Data Succesfully Verified.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Validasi_LPB/update/' . $ID);
        // $ok = $this->db->query("SELECT OK FROM beli WHERE NO_ID='$ID'")->result();
        // $ok2 = $ok[0]->OK;
        // if($ok2 == 0){
        //     $bukti = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM beli WHERE NO_ID='$ID'")->result();
        //     $no_bukti = $bukti[0]->BUKTIX;
        //     $this->db->query("CALL spp_beliins('" . $no_bukti . "')");
        //     $this->session->set_flashdata(
        //         'pesan',
        //         '<div class="alert alert-warning alert-dismissible fade show" role="alert"> Data Succesfully Verified.
        //             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //                 <span aria-hidden="true">&times;</span>
        //             </button> 
        //         </div>'
        //     );
        //     redirect('admin/Transaksi_Validasi_LPB/index_Transaksi_Validasi_LPB');
        // }else{
        //     $this->session->set_flashdata(
        //         'pesan',
        //         '<div class="alert alert-warning alert-dismissible fade show" role="alert"> Data Succesfully Verified.
        //             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //                 <span aria-hidden="true">&times;</span>
        //             </button> 
        //         </div>'
        //     );
        //     redirect('admin/Transaksi_Validasi_LPB/index_Transaksi_Validasi_LPB');
        // }
    }

    public function getDataAjax_bhn()
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
        $results = $this->db->query("SELECT bhnd.NO_ID, bhnd.KD_BHN, bhnd.NA_BHN, bhn.SATUAN, bhnd.RAK
            FROM bhn, bhnd
            WHERE bhn.KD_BHN = bhnd.KD_BHN AND bhnd.DR = '$dr' AND bhnd.FLAG = 'SP' AND (bhnd.KD_BHN LIKE '%$search%' OR bhnd.NA_BHN LIKE '%$search%' OR bhn.SATUAN LIKE '%$search%' OR bhnd.RAK LIKE '%$search%')
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['RAK'],
                'text' => $row['RAK'],
                'KD_BHN' => $row['KD_BHN'] . " - " . $row['NA_BHN'] . " - " . $row['SATUAN'] . " - " . $row['RAK'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUAN' => $row['SATUAN'],
                'RAK' => $row['RAK'],
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Validasi_LPB.jrxml");
        $no_id = $id;
        $query = "SELECT beli.NO_ID as ID,
            beli.NO_BUKTI AS NO_BUKTI,
            beli.KET AS KET,
            beli.TGL AS TGL,
            beli.PIN1 AS PIN1,
            beli.TOTAL_QTYPP AS TOTAL_QTYPP,
            beli.TOTAL_QTY AS TOTAL_QTY,
            beli.VAL AS VAL,
            beli.TTD1 AS TTD1,
            beli.TTD2 AS TTD2,
            beli.TTD3 AS TTD3,
            beli.TTD4 AS TTD4,
            beli.TTD5 AS TTD5,
            beli.TTD6 AS TTD6,

            belid.NO_ID AS NO_ID,
            belid.REC AS REC,
            belid.VAL AS VAL,
            belid.KD_BHN AS KD_BHN,
            belid.NA_BHN AS NA_BHN,
            belid.RAK AS RAK,
            belid.QTYPP AS QTYPP,
            belid.SATUANPP AS SATUANPP,
            belid.SISA AS QTY,
            belid.SATUAN AS SATUAN
        FROM beli, belid 
        WHERE beli.NO_ID = $id 
        AND beli.NO_ID = belid.ID 
        ORDER BY belid.REC";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "KET" => $row1["KET"],
                "PIN1" => $row1["PIN1"],
                "TOTAL_QTYPP" => $row1["TOTAL_QTYPP"],
                "TOTAL_QTY" => $row1["TOTAL_QTY"],
                "VAL" => $row1["VAL"],
                "TTD1" => $row1["TTD1"],
                "TTD2" => $row1["TTD2"],
                "TTD3" => $row1["TTD3"],
                "TTD4" => $row1["TTD4"],
                "TTD5" => $row1["TTD5"],
                "TTD6" => $row1["TTD6"],
                "NO_ID" => $row1["NO_ID"],
                "REC" => $row1["REC"],
                "VAL" => $row1["VAL"],
                "KD_BHN" => $row1["KD_BHN"],
                "NA_BHN" => $row1["NA_BHN"],
                "RAK" => $row1["RAK"],
                "QTYPP" => $row1["QTYPP"],
                "SATUANPP" => $row1["SATUANPP"],
                "QTY" => $row1["QTY"],
                "SATUAN" => $row1["SATUAN"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }

    function prev()
    {
        $ID = $this->input->get('ID');

        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];

        $q1 = " SELECT NO_ID FROM beli WHERE NO_ID<'$ID' AND FLAG = 'BL' AND FLAG2 = 'SP' AND PER='$per' AND DR='$dr' AND SUB='$sub' AND OK<>'1' ORDER BY NO_BUKTI ASC LIMIT 1";

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

        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];

        $q1 = " SELECT NO_ID FROM beli WHERE NO_ID>'$ID' AND FLAG = 'BL' AND FLAG2 = 'SP' AND PER='$per' AND DR='$dr' AND SUB='$sub' AND OK<>'1' ORDER BY NO_BUKTI ASC LIMIT 1";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }
}
