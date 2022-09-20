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
            'FLAG' => 'SP',
            'FLAG2' => 'LN',
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
            'FLAG' => 'SP',
            'FLAG2' => 'LN',
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
            $row[] = date("d-m-Y", strtotime($stocka->TGL));
            $row[] = $stocka->NOTES;
            $row[] = $stocka->TOTAL_QTY_AK;
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
            'FLAG' => 'SP',
            'FLAG2' => 'LN',
        );
        $data['stocka'] = $this->transaksi_model->tampil_data($where,'stocka','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Koreksi_Stok/Transaksi_Koreksi_Stok', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input() {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM stocka WHERE PER='$per' and DR='$dr' AND FLAG='SP' AND FLAG2='LN'")->result();
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
        $bukti = 'LN' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Koreksi_Stok/Transaksi_Koreksi_Stok_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $dr = $this->session->userdata['dr']; 
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM stocka WHERE PER='$per' AND DR='$dr' AND FLAG='SP' AND FLAG2='LN'")->result();
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
        // $bukti = 'LN' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        // $this->session->set_userdata('bukti', $bukti);
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $datah = array(
            'FLAG' => 'SP',
            'FLAG2' => 'LN',
            'ATK' => '0',
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE), 
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'TOTAL_QTY_AK' => str_replace(',','',$this->input->post('TOTAL_QTY_AK',TRUE)),
            'SUB' => $this->session->userdata['sub'],
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
        $RAK = $this->input->post('RAK');
        $i = 0;
        foreach($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->no_id,
                'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                'FLAG' => 'SP',
                'FLAG2' => 'LN',
                'ATK' => '0',
                'REC' => $REC[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'QTY' => str_replace(',','',$QTY[$i]),
                'QTY_AK' => str_replace(',','',$QTY_AK[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET1' => $KET1[$i],
                'RAK' => $RAK[$i],
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'DR' => $this->session->userdata['dr'],
                'I_TGL' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('stockad',$datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM stocka WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_stockains('" . $no_bukti . "')");
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
                stockad.RAK AS RAK,
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
        $bukti = $this->input->post('NO_BUKTI');
        $datah = array(
            'FLAG' => 'SP',
            'FLAG2' => 'LN',
            'ATK' => '0',
            'NO_BUKTI' => $this->input->post('NO_BUKTI',TRUE),             
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'TOTAL_QTY_AK' => str_replace(',','',$this->input->post('TOTAL_QTY_AK',TRUE)),
            'PER' => $this->session->userdata['periode'],
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM stocka WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_stockadel('" . $no_bukti . "')");
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
                stockad.RAK AS RAK,
                stockad.QTY AS QTY,
                stockad.QTY_AK AS QTY_AK,
                stockad.SATUAN AS SATUAN,
                stockad.KET1 AS KET1
            FROM stocka,stockad 
            WHERE stocka.NO_ID=$id 
            AND stocka.NO_ID=stockad.ID
            ORDER BY stockad.REC";        
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $QTY_AK = str_replace(',','',$this->input->post('QTY_AK',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $RAK = $this->input->post('RAK');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'FLAG' => 'SP',
                    'FLAG2' => 'LN',
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
                    'RAK' => $RAK[$URUT],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
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
                    'FLAG' => 'SP',
                    'FLAG2' => 'LN',
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
                    'RAK' => $RAK[$i],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('stockad', $datad);
            }
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM stocka WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_stockains('" . $no_bukti . "')");
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
        $data = $this->db->query("SELECT NO_BUKTI FROM stocka WHERE NO_ID='$id'")->result();
        $this->db->query("CALL spp_stockadel('" . $data[0]->NO_BUKTI . "')");
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
        $per = substr($this->session->userdata['periode'], 0, 2);
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT bhn.no_id, bhn.KD_BHN AS kd_bhn, bhn.NA_BHN AS na_bhn, bhn.SATUAN AS satuan, bhnd.AW$per AS qty, bhnd.RAK as rak 
            FROM bhn, bhnd
            WHERE bhn.kd_bhn=bhnd.kd_bhn AND bhn.DR = '$dr' AND bhn.FLAG = 'SP' AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%' OR bhnd.RAK LIKE '%$search%')
            GROUP BY bhnd.kd_bhn
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['kd_bhn'],
                'text' => $row['kd_bhn'],
                'kd_bhn' => $row['kd_bhn'] . " - " . $row['na_bhn']. " - " . $row['rak']. " - " . $row['satuan']. " - " . $row['qty'],
                'rak' => $row['rak'],
                'na_bhn' => $row['na_bhn'],
                'satuan' => $row['satuan'],
                'qty' => $row['qty'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
}