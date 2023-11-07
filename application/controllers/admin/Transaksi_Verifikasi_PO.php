<?php

class Transaksi_Verifikasi_PO extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'po') {
            $this->session->set_userdata('menu_sparepart', 'po');
            $this->session->set_userdata('kode_menu', 'ST0003');
            $this->session->set_userdata('keyword_po', '');
            $this->session->set_userdata('order_po', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'TOTAL_QTY', 'NETT');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'TOTAL_QTY', 'NETT');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'FLAG2' => 'NB',
            'DR' => $dr,
            'OK' => '0',
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
        $dr = $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'FLAG2' => 'NB',
            'DR' => $dr,
            'OK' => '0',
        );
        $this->db->from('po');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_po()
    {
        //asli
        // <a class="dropdown-item" href="' . site_url('admin/Transaksi_PO_NonBahan/delete/' . $po->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
        //
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $po) {
            $JASPER = "window.open('JASPER/" . $po->NO_ID . "','', 'width=' + screen.availWidth / 1.1 + ',height=' + screen.availHeight / 1.1);";
           //batal validasi 
            // $btn='';
            // if ($po->TTD1==1){
            //     $btn='
            //     <a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_PO/btlupdate/' . $po->NO_ID) . '"> <i class="fa fa-edit"></i> Batal Validasi</a>';
            // }
            
            // if($this->session->userdata['level']=='BL2' || $this->session->userdata['level']=='BL8'){
            //     $hiden = '<a hidden class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_PO/delete/' . $po->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>';
            // }else{
            //     $hiden = '<a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_PO/delete/' . $po->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>';
            // }
            //batas 
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $po->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #01BAEF;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Verifikasi_PO/update/' . $po->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a name="NO_BUKTI" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';

        //'.$btn.' tambahan untuk batal validasi

            $row[] = $no . ".";
            $row[] = $po->NO_BUKTI;
            $row[] = $po->KODES;
            $row[] = $po->NAMAS;
            $row[] = $po->DR;
            $row[] = date('d-m-Y', strtotime($po->TGL));
            $row[] = date('d-m-Y', strtotime($po->JTEMPO));
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

    public function index_Transaksi_Verifikasi_PO()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO');
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        // $per = $this->session->userdata['periode'];
        $per = date("m/Y", strtotime($this->input->post('TGL', TRUE)));
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM po WHERE PER='$per' AND FLAG2='NB'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 0, 4);
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 4, "0", STR_PAD_LEFT);
        $tahun = substr($per, -4);
        $bulan = substr($per, 0, 2);
        if ($bulan == 1) { $romawi = 'I'; }
        if ($bulan == 2) { $romawi = 'II'; }
        if ($bulan == 3) { $romawi = 'III'; }
        if ($bulan == 4) { $romawi = 'IV'; }
        if ($bulan == 5) { $romawi = 'V'; }
        if ($bulan == 6) { $romawi = 'VI'; }
        if ($bulan == 7) { $romawi = 'VII'; }
        if ($bulan == 8) { $romawi = 'VIII'; }
        if ($bulan == 9) { $romawi = 'IX'; }
        if ($bulan == 10) { $romawi = 'X'; }
        if ($bulan == 11) { $romawi = 'XI'; }
        if ($bulan == 12) { $romawi = 'XII'; }
        // $bukti=$flag.$flag2.'-'.'DR'.$dr.'-'.$tahun.$bulan.'-'.$urut;
        $bukti = $urut . '/' . 'PO' . '/' . $romawi . '/' . $tahun;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'KODES' => $this->input->post('KODES', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'DR' => $this->input->post('DR', TRUE),
            'KD_BAG' => $this->input->post('KD_BAG', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
            'KOTA' => $this->input->post('KOTA', TRUE),
            'ALAMAT' => $this->input->post('ALAMAT', TRUE),
            'KURS' => $this->input->post('KURS', TRUE),
            'RATE' => $this->input->post('RATE', TRUE),
            'AN' => $this->input->post('AN', TRUE),
            'PROD' => "-",
            'BD' => $this->input->post('BD', TRUE),
            'NOTESBL' => $this->input->post('NOTESBL', TRUE),
            'NOTESKRM' => $this->input->post('NOTESKRM', TRUE),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'PKP' => str_replace(',', '', $this->input->post('PKP', TRUE)),
            'TOTAL_QTYPP' => str_replace(',', '', $this->input->post('TOTAL_QTYPP', TRUE)),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'TOTAL' => str_replace(',', '', $this->input->post('TTOTAL', TRUE)),
            'PPN' => str_replace(',', '', $this->input->post('PPN', TRUE)),
            'NETT' => str_replace(',', '', $this->input->post('NETT', TRUE)),
            'NOPOPPC' => str_replace(',', '', $this->input->post('NOPOPPC', TRUE)),
            'FLAG' => 'PO',
            'FLAG2' => 'NB',
            // 'PER' =>  $this->session->userdata['periode'],
            'PER' =>  date("m/Y", strtotime($this->input->post('TGL', TRUE))),
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('po', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM po WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $NO_PP = $this->input->post('NO_PP') ?? " ";
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $SATUANPP = $this->input->post('SATUANPP');
        $SATUAN = $this->input->post('SATUAN');
        $QTYPP = str_replace(',', '', $this->input->post('QTYPP', TRUE));
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $HARGA = str_replace(',', '', $this->input->post('HARGA', TRUE));
        $TOTAL = str_replace(',', '', $this->input->post('TOTAL', TRUE));
        $DEVISI = $this->input->post('DEVISI');
        $DRD = $this->input->post('DRD');
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
                'KODES' => $this->input->post('KODES', TRUE),
                'NAMAS' => $this->input->post('NAMAS', TRUE),
                'REC' => $REC[$i],
                'NO_PP' => $NO_PP[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'SATUANPP' => $SATUANPP[$i],
                'QTYPP' => str_replace(',', '', $QTYPP[$i]),
                'SATUAN' => $SATUAN[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SISA' => str_replace(',', '', $SISA[$i]),
                'HARGA' => str_replace(',', '', $HARGA[$i]),
                'TOTAL' => str_replace(',', '', $TOTAL[$i]),
                'DEVISI' => $DEVISI[$i],
                'DR' => $DRD[$i],
                'FLAG' => 'PO',
                'FLAG2' => 'NB',
                // 'PER' => $this->session->userdata['periode'],
                'PER' =>  date("m/Y", strtotime($this->input->post('TGL', TRUE))),
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('pod', $datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM po WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL po_ins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    public function update($id)
    {
        $q1 = "SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.KODES AS KODES,
                po.DR AS DR,
                po.NAMAS AS NAMAS,
                DATE_FORMAT(po.TGL,'%d-%m-%Y') AS TGL,
                po.JTEMPO AS JTEMPO,
                po.KOTA AS KOTA,
                po.ALAMAT AS ALAMAT,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.AN AS AN,
                po.ATT AS ATT,
                po.PROD AS PROD,
                po.OK AS OK,
                po.BD AS BD,
                po.NOTESBL AS NOTESBL,
                po.NOTESKRM AS NOTESKRM,
                po.NOTESKURS AS NOTESKURS,
                po.NOPOPPC AS NOPOPPC,
                po.UANG AS UANG,
                po.NOTES AS NOTES,
                po.PKP AS PKP,
                po.TOTAL_QTYPP AS TOTAL_QTYPP,
                po.TOTAL_QTY AS TOTAL_QTY,
                po.TOTAL AS TTOTAL,
                po.TOTAL AS TOTAL,
                po.PPN AS PPN,
                po.NETT AS NETT,
                po.TTD1 AS TTD1,
                po.TTD2 AS TTD2,
                po.TTD3 AS TTD3,
                po.TTD4 AS TTD4,
                po.TTD5 AS TTD5,
                po.TTD6 AS TTD6,
                po.CEO AS CEO,
                CONCAT(po.TTD1_USR,'  ',date_format(po.TTD1_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD1_SMP,
                CONCAT(po.TTD2_USR,'  ',date_format(po.TTD2_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD2_SMP,
                CONCAT(po.TTD3_USR,'  ',date_format(po.TTD3_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD3_SMP,
                CONCAT(po.TTD4_USR,'  ',date_format(po.TTD4_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD4_SMP,
                CONCAT(po.TTD5_USR,'  ',date_format(po.TTD5_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD5_SMP,
                CONCAT(po.TTD6_USR,'  ',date_format(po.TTD6_SMP,'%d/%m/%Y %h:%i:%s')) AS TTD6_SMP,
                CONCAT(po.TTDCEO_USR,'  ',date_format(po.TTDCEO_SMP,'%d/%m/%Y %h:%i:%s')) AS TTDCEO_SMP,

                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.NO_PP AS NO_PP,
                pod.KET AS KET,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.SATUANPP AS SATUANPP,
                pod.QTYPP AS QTYPP,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY,
                pod.SISA AS SISA,
                pod.HARGA AS HARGA,
                pod.DEVISI AS DEVISI,
                pod.SUBDIV AS SUB,
                pod.DR AS DRD
            FROM po,pod 
            WHERE po.no_id=$id 
            AND po.NO_ID=pod.ID 
            ORDER BY pod.REC";
        $data['verifikasi_po'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'KODES' => $this->input->post('KODES', TRUE),
            'DR' => $this->input->post('DR', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
            'KOTA' => $this->input->post('KOTA', TRUE),
            'ALAMAT' => $this->input->post('ALAMAT', TRUE),
            'KURS' => $this->input->post('KURS', TRUE),
            'RATE' => $this->input->post('RATE', TRUE),
            'AN' => $this->input->post('AN', TRUE),
            'PROD' => $this->input->post('PROD', TRUE),
            'BD' => $this->input->post('BD', TRUE),
            'NOTESBL' => $this->input->post('NOTESBL', TRUE),
            'NOTESKRM' => $this->input->post('NOTESKRM', TRUE),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'PKP' => str_replace(',', '', $this->input->post('PKP', TRUE)),
            'TOTAL_QTYPP' => str_replace(',', '', $this->input->post('TOTAL_QTYPP', TRUE)),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'TOTAL' => str_replace(',', '', $this->input->post('TTOTAL', TRUE)),
            'PPN' => str_replace(',', '', $this->input->post('PPN', TRUE)),
            'NETT' => str_replace(',', '', $this->input->post('NETT', TRUE)),
            'NOPOPPC' => str_replace(',', '', $this->input->post('NOPOPPC', TRUE)),
            'FLAG' => 'PO',
            'FLAG2' => 'NB',
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM po WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL po_del('" . $no_bukti . "')");
        $this->transaksi_model->update_data($where, $datah, 'po');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.KODES AS KODES,
                po.DR AS DR,
                po.NAMAS AS NAMAS,
                DATE_FORMAT(po.TGL,'%d-%m-%Y') AS TGL,
                po.JTEMPO AS JTEMPO,
                po.KOTA AS KOTA,
                po.ALAMAT AS ALAMAT,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.AN AS AN,
                po.NOTESBL AS NOTESBL,
                po.NOTESKRM AS NOTESKRM,
                po.NOTES AS NOTES,
                po.PKP AS PKP,
                po.TOTAL_QTYPP AS TOTAL_QTYPP,
                po.TOTAL_QTY AS TOTAL_QTY,
                po.TOTAL AS TTOTAL,
                po.PPN AS PPN,
                po.NETT AS NETT,

                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.NO_PP AS NO_PP,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.SATUANPP AS SATUANPP,
                pod.QTYPP AS QTYPP,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY,
                pod.SISA AS SISA,
                pod.HARGA AS HARGA,
                pod.TOTAL AS TOTAL,
                pod.DEVISI AS DEVISI,
                pod.DR AS DRD
            FROM po,pod 
            WHERE po.no_id=$id 
            AND po.NO_ID=pod.ID 
            ORDER BY pod.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $NO_PP = $this->input->post('NO_PP');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $SATUANPP = $this->input->post('SATUANPP');
        $QTYPP = str_replace(',', '', $this->input->post('QTYPP', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $HARGA = str_replace(',', '', $this->input->post('HARGA', TRUE));
        $TOTAL = str_replace(',', '', $this->input->post('TOTAL', TRUE));
        $DEVISI = $this->input->post('DEVISI');
        $DRD = $this->input->post('DRD');
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
                    'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
                    'KODES' => $this->input->post('KODES', TRUE),
                    'NAMAS' => $this->input->post('NAMAS', TRUE),
                    'REC' => $REC[$URUT],
                    'NO_PP' => $NO_PP[$URUT],
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'SATUANPP' => $SATUANPP[$URUT],
                    'QTYPP' => str_replace(',', '', $QTYPP[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SISA' => str_replace(',', '', $SISA[$URUT]),
                    'HARGA' => str_replace(',', '', $HARGA[$URUT]),
                    'TOTAL' => str_replace(',', '', $TOTAL[$URUT]),
                    'DEVISI' => $DEVISI[$URUT],
                    'DR' => $DRD[$URUT],
                    'FLAG' => 'PO',
                    'FLAG2' => 'NB',
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'pod');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'pod');
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
                    'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
                    'KODES' => $this->input->post('KODES', TRUE),
                    'NAMAS' => $this->input->post('NAMAS', TRUE),
                    'REC' => $REC[$i],
                    'NO_PP' => $NO_PP[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'SATUANPP' => $SATUANPP[$i],
                    'QTYPP' => str_replace(',', '', $QTYPP[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SISA' => str_replace(',', '', $SISA[$i]),
                    'HARGA' => str_replace(',', '', $HARGA[$i]),
                    'TOTAL' => str_replace(',', '', $TOTAL[$i]),
                    'DEVISI' => $DEVISI[$i],
                    'DR' => $DRD[$i],
                    'FLAG' => 'PO',
                    'FLAG2' => 'NB',
                    // 'PER' => $this->session->userdata['periode'],
                    'PER' =>  date("m/Y", strtotime($this->input->post('TGL', TRUE))),
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('pod', $datad);
            }
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM po WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL po_ins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> Bukti ' . $this->input->post('NO_MANUAL') . $this->input->post('NO_BUKTI') . ' Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    public function delete($id)
    {
        $data = $this->db->query("SELECT NO_BUKTI FROM po WHERE NO_ID='$id'")->result();
        $this->db->query("CALL po_del('" . $data[0]->NO_BUKTI . "')");
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'po');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pod');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked_transaksi_surat_jalan('po', 'pod');
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    public function getDataAjax_sup()
    {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT NO_ID, KODES, NAMAS, ALAMAT, KOTA, NOTBAY, KONTAK, AKTIF, CASE WHEN PKP = '1' THEN '(PKP)' ELSE '(NON PKP)' END AS PKP2, PKP
            FROM sup
            WHERE FLAG2='NB' AND (KODES LIKE '%$search%' OR NAMAS LIKE '%$search%' OR ALAMAT LIKE '%$search%' OR KOTA LIKE '%$search%')
            AND AKTIF='1'
            ORDER BY KODES LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['KODES'],
                'text' => $row['KODES'],
                'KODES' => $row['KODES'] . " - " . $row['NAMAS'] . " - " . $row['ALAMAT'] . " - " . $row['KOTA'] . " - " . $row['PKP2'],
                'NAMAS' => $row['NAMAS'],
                'ALAMAT' => $row['ALAMAT'],
                'KOTA' => $row['KOTA'],
                'KONTAK' => $row['KONTAK'],
                'AKTIF' => $row['AKTIF'],
                'PKP' => $row['PKP'],
                'NOTBAY' => $row['NOTBAY'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_no_pp()
    {
        $dr = $this->input->post('dr');
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT ppd.NO_ID, ppd.NO_BUKTI AS NO_PP,
                pp.SUB AS SUB,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.SATUAN AS SATUANPP,
                (ppd.QTY-ppd.KIRIM) AS QTYPP,
                ppd.SATUAN AS SATUAN,
                ppd.DR AS DRD,
                (ppd.QTY-ppd.KIRIM) AS QTY
            FROM pp, ppd
            WHERE pp.NO_ID = ppd.id /*AND ppd.DR = '$dr'*/ AND ppd.FLAG2='NB' AND ppd.PILIH=1 AND ppd.QTY - ppd.KIRIM <> 0 AND (ppd.NO_BUKTI LIKE '%$search%' OR ppd.KD_BHN LIKE '%$search%' OR ppd.NA_BHN LIKE '%$search%' )
            ORDER BY ppd.NO_BUKTI LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NO_PP'],
                'text' => $row['NO_PP'],
                'NO_PP' => $row['NO_PP'] . " - " . $row['KD_BHN'] . " - " . $row['NA_BHN'] . " - " . $row['QTYPP'],
                'SUB' => $row['SUB'],
                'KD_BHN' => $row['KD_BHN'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUANPP' => $row['SATUANPP'],
                'QTYPP' => $row['QTYPP'],
                'SATUAN' => $row['SATUAN'],
                'DRD' => $row['DRD'],
                'QTY' => $row['QTY'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_no_pp2()
    {
        // $KODES = $this->input->get('KODES');
        $filtercari = "";
        $CARI = $this->input->get('cari');
        if ($CARI) $filtercari = " AND (ppd.NO_BUKTI like '%".$CARI."%' OR ppd.NA_BHN like '%".$CARI."%' OR CONCAT(ppd.NO_BUKTI,'-',ppd.REC) like '%".$CARI."%')";
        $q1 = "SELECT ppd.NO_ID, concat(ppd.NO_BUKTI,'-',ppd.REC) AS NO_PP,
                pp.SUB AS SUB,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.SATUAN AS SATUANPP,
                ppd.QTY AS QTYPP,
                ppd.SATUAN AS SATUAN,
                ppd.DR AS DRD,
                (ppd.QTY-ppd.KIRIM) AS QTY,
                ppd.SISA
            FROM pp, ppd
            WHERE pp.NO_ID = ppd.id AND ppd.FLAG='PP' 
            AND ppd.PILIH=1 
            AND ppd.QTY - ppd.KIRIM <> 0  $filtercari
            ORDER BY ppd.NO_BUKTI, ppd.REC";
        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    public function historypo()
    {

        $filterbukti = "";
        $filterpp = "";
        $filtersupplier = "";
        $filterna_bhn = "";
        $filterkd_bhn = "";

        $BUKTI = $this->input->get('bukti');
        $PP = $this->input->get('pp');
        $SUPPLIER = $this->input->get('supplier');
        $NA_BHN = $this->input->get('na_bhn');
        $KD_BHN = $this->input->get('kd_bhn');

        if($BUKTI) $filterbukti = "AND pod.NO_BUKTI like '%".$BUKTI."%'";
        if($PP) $filterpp = "AND pod.NO_PP like '%".$PP."%'";
        if($SUPPLIER) $filtersupplier = "AND pod.NAMAS like '%".$SUPPLIER."%'";
        if($NA_BHN) $filterna_bhn = "AND pod.NA_BHN like '%".$NA_BHN."%'";
        if($KD_BHN) $filterkd_bhn = "AND pod.KD_BHN like '%".$KD_BHN."%'";
        
        $q1 = "SELECT pod.NO_ID, 
                pod.NO_PP,
                pod.NO_BUKTI,
                DATE_FORMAT(po.TGL,'%d-%m-%Y') AS TGL,
                pod.KD_BHN,
                pod.NA_BHN,
                po.NAMAS,
                pod.QTY,
                pod.HARGA,
                pod.TOTAL
            FROM po,pod 
            WHERE pod.ID=po.NO_ID $filterbukti $filterpp $filtersupplier $filterna_bhn $filterkd_bhn
            ORDER BY NO_BUKTI";
        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    public function getDataAjax_no_pp3()
    {
        $filter_NO_PP = '';
        $NO_PP = $this->input->get('NO_PP');
        foreach ($NO_PP as $value) {
            $filter_NO_PP .= "'$value',";
        }
        $filter_NO_PP = substr($filter_NO_PP, 0, -1);
        $q1 = "SELECT ppd.NO_ID, ppd.NO_BUKTI AS NO_PP,
                pp.SUB AS SUB,
                pp.KD_BAG AS KD_BAG,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.SATUAN AS SATUANPP,
                ppd.QTY AS QTYPP,
                ppd.SATUAN AS SATUAN,
                ppd.DR AS DRD,
                (ppd.QTY-ppd.KIRIM) AS QTY
            FROM pp, ppd
            WHERE pp.NO_ID = ppd.id AND ppd.FLAG='PP' 
            -- AND ppd.PILIH=1 
            AND ppd.QTY - ppd.KIRIM <> 0 
            AND concat(ppd.NO_BUKTI,'-',ppd.REC) IN ($filter_NO_PP)
            ORDER BY ppd.NO_BUKTI, ppd.REC";
        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }
    
    public function getDataAjax_kd_bhn()
    {
        $dr = $this->input->post('dr');
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_BHN, NA_BHN, SUB, SATUAN
            FROM bhn 
            WHERE (KD_BHN LIKE '%$search%' OR NA_BHN LIKE '%$search%')
            ORDER BY KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['KD_BHN'],
                'text' => $row['NA_BHN'],
                'KET_BHN' => $row['KD_BHN'] . " - " . $row['NA_BHN'],
                'SUB' => $row['SUB'],
                'KD_BHN' => $row['KD_BHN'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUANPP' => $row['SATUAN'],
                'SATUAN' => $row['SATUAN'],
                'DRD' => $dr,
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function verifikasi_ttd1()
    {
        $val = $this->input->get('VAL');
        $NO_ID = $this->input->get('NO_ID');
        $datah = array(
            'TTD'.$val => 1,
            'PIN'.$val => $this->session->userdata['pin'],
            'TTD'.$val.'_USR' => $this->session->userdata['username'],
            'TTD'.$val.'_SMP' => date("Y-m-d h:i:s")
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($where, $datah, 'po');
        $datahd = array(
            'TTD'.$val => 1,
            'PIN'.$val => $this->session->userdata['pin'],
            'TTD'.$val.'_USR' => $this->session->userdata['username'],
            'TTD'.$val.'_SMP' => date("Y-m-d h:i:s")
        );
        $whered = array(
            'ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'pod');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> Berhasil Validasi '.$val.'.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>'
        );
        // redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
        redirect(site_url('admin/Transaksi_Verifikasi_PO/update/' . $NO_ID));
    }

    function modal_budget()
    {
        $DR = $this->input->get('DR');
        $per = $this->session->userdata['periode'];
        $bulan = substr($per, 0, 2);
        $yer = substr($per, 3, 7);

        $q1 = "SELECT LM$bulan AS BUDGET FROM budgetd WHERE DR='$DR' AND JENIS='NB' AND YER='$yer'";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    function JASPER($no_id)
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Verifikasi_PO.jrxml");
        $id = $no_id;
        $query = "SELECT po.NO_BUKTI AS NO_BUKTI, 
                po.NOTESBL AS NOTESBL,
                DATE_FORMAT(po.TGL,'%d-%m-%Y') AS TGL,
                po.NOTESKRM AS NOTESKRM,
                po.NAMAS AS NAMAS, 
                po.ALAMAT AS ALAMAT, 
                po.KOTA AS KOTA, 
                pod.NO_PP AS NO_PP,
                '-' AS ATT,
                '-' AS TELEPON,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.NETT AS NETT,
                po.PPN AS PPN,
                po.TTD1_USR AS TTD1_USR,
                po.TTD2_USR AS TTD2_USR,
                po.TTD3_USR AS TTD3_USR,
                po.TTD4_USR AS TTD4_USR,
                po.TTD5_USR AS TTD5_USR,

                pod.REC AS REC,
                pod.NA_BHN AS NA_BHN,
                pod.QTY AS QTY,
                pod.SATUAN AS SATUAN,
                pod.HARGA AS HARGA,
                pod.TOTAL AS TOTAL
            FROM po, pod
            WHERE po.NO_ID = pod.ID
            AND po.NO_ID = '$id'
            ORDER BY pod.REC";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "TGL" => $row1["TGL"],
                "NOTESBL" => $row1["NOTESBL"],
                "NOTESKRM" => $row1["NOTESKRM"],
                "NAMAS" => $row1["NAMAS"],
                "ALAMAT" => $row1["ALAMAT"],
                "KOTA" => $row1["KOTA"],
                "NO_PP" => $row1["NO_PP"],
                "ATT" => $row1["ATT"],
                "TELPON" => $row1["TELPON"],
                "KURS" => $row1["KURS"],
                "RATE" => $row1["RATE"],
                "NETT" => $row1["NETT"],
                "PPN" => $row1["PPN"],
                "TTD1_USR" => $row1["TTD1_USR"],
                "TTD2_USR" => $row1["TTD2_USR"],
                "TTD3_USR" => $row1["TTD3_USR"],
                "TTD4_USR" => $row1["TTD4_USR"],
                "TTD5_USR" => $row1["TTD5_USR"],

                "REC" => $row1["REC"],
                "NA_BHN" => $row1["NA_BHN"],
                "QTY" => $row1["QTY"],
                "SATUAN" => $row1["SATUAN"],
                "HARGA" => $row1["HARGA"],
                "TOTAL" => $row1["TOTAL"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
        // $datah = array(
        //     'POSTED' => "1"
        // );
        // $where = array(
        //     'NO_BUKTI' => "$no_b"
        // );
        // $this->transaksi_model->update_data($where, $datah, 'po');
    }

    //batal validasi
    public function btlupdate($id)
    {
        $q1 = "SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.KODES AS KODES,
                po.DR AS DR,
                po.NAMAS AS NAMAS,
                DATE_FORMAT(po.TGL,'%d-%m-%Y') AS TGL,
                po.JTEMPO AS JTEMPO,
                po.KOTA AS KOTA,
                po.ALAMAT AS ALAMAT,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.AN AS AN,
                po.PROD AS PROD,
                po.BD AS BD,
                po.NOTESBL AS NOTESBL,
                po.NOTESKRM AS NOTESKRM,
                po.NOTESKURS AS NOTESCTK,
                po.NOTES AS NOTES,
                po.PKP AS PKP,
                po.TOTAL_QTYPP AS TOTAL_QTYPP,
                po.TOTAL_QTY AS TOTAL_QTY,
                po.TOTAL AS TTOTAL,
                po.TOTAL AS TOTAL,
                po.PPN AS PPN,
                po.NETT AS NETT,
                po.TTD1 AS TTD1,
                po.TTD2 AS TTD2,
                po.TTD3 AS TTD3,
                po.TTD4 AS TTD4,
                po.TTD5 AS TTD5,
                po.TTD6 AS TTD6,

                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.NO_PP AS NO_PP,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.SATUANPP AS SATUANPP,
                pod.QTYPP AS QTYPP,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY,
                pod.SISA AS SISA,
                pod.HARGA AS HARGA,
                pod.DEVISI AS DEVISI,
                pod.SUBDIV AS SUB,
                pod.DR AS DRD
            FROM po,pod 
            WHERE po.no_id=$id 
            AND po.NO_ID=pod.ID 
            ORDER BY pod.REC";
        $data['po_nonbahan'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Verifikasi_PO/Transaksi_Verifikasi_PO_update_BatalVal', $data);
        $this->load->view('templates_admin/footer');
    }

    public function btlval($NO_ID)
    {
        $xx = $this->db->query("SELECT NO_BUKTI as BUKTIX from po where NO_ID='$NO_ID'")->result();
        $bukti = $xx[0]->BUKTIX;

         $datah = array(
            'TTD1' => 0,
            'TTD2' => 0,
            'TTD3' => 0,
            'TTD4' => 0,
            'TTD5' => 0,
            'TTD6' => 0,
            'PIN1' => '',
            'PIN2' => '',
            'PIN3' => '',
            'PIN4' => '',
            'PIN5' => '',
            'PIN6' => '',
            'TTD1_USR' => '',
            'TTD2_USR' => '',
            'TTD3_USR' => '',
            'TTD4_USR' => '',
            'TTD5_USR' => '',
            'TTD6_USR' => '',
            'TTD1_SMP' => '2001-01-01',
            'TTD2_SMP' => '2001-01-01',
            'TTD3_SMP' => '2001-01-01',
            'TTD4_SMP' => '2001-01-01',
            'TTD5_SMP' => '2001-01-01',
            'TTD6_SMP' => '2001-01-01',
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($where, $datah, 'po');
        $datahd = array(
            'TTD1' => 0,
            'TTD2' => 0,
            'TTD3' => 0,
            'TTD4' => 0,
            'TTD5' => 0,
            'TTD6' => 0,
            'PIN1' => '',
            'PIN2' => '',
            'PIN3' => '',
            'PIN4' => '',
            'PIN5' => '',
            'PIN6' => '',
            'TTD1_USR' => '',
            'TTD2_USR' => '',
            'TTD3_USR' => '',
            'TTD4_USR' => '',
            'TTD5_USR' => '',
            'TTD6_USR' => '',
            'TTD1_SMP' => '2001-01-01',
            'TTD2_SMP' => '2001-01-01',
            'TTD3_SMP' => '2001-01-01',
            'TTD4_SMP' => '2001-01-01',
            'TTD5_SMP' => '2001-01-01',
            'TTD6_SMP' => '2001-01-01',
        );
        $whered = array(
            'ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'pod');
        $this->update($NO_ID);        

        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Batal Validasi Bukti ' . $bukti . ' Berhasil, Tolong Ulangi Validasi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Verifikasi_PO/index_Transaksi_Verifikasi_PO');
    }

    function cek_budget()
    {
        $ID = $this->input->get('ID');
        // $JEN = $this->input->get('JENIS');
        // var_dump($ID);
        // $JENIS = substr($JEN, 0, 2);
        $DR = $this->input->get('DR');
        // if ($DR == 'I') {
        //     $DR = '1';
        // } else if ($DR == 'II') {
        //     $DR = '2';
        // } else if ($DR == 'III') {
        //     $DR = '3';
        // }
        $per = $this->session->userdata['periode'];
        $bulan = substr($per, 0, 2);
        $yer = substr($per, 3, 7);
        // $JENIS = $JENIS . $DR;

        $dataNETT = $this->db->query("SELECT NETT FROM po WHERE NO_ID='$ID'")->result();
        $NETT = $dataNETT[0]->NETT ?? 0;
        $q1 = "SELECT (AK$bulan - $NETT) AS BUDGET FROM budgetd WHERE DR='$DR' AND JENIS='NB' AND YER='$yer'";

        $q2 = $this->db->query($q1);
        $hasil=[];
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }
}
