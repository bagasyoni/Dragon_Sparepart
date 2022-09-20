<?php

class Transaksi_Retur_Bon extends CI_Controller {

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
        if ($this->session->userdata['menu_sparepart'] != 'pakai') {
			$this->session->set_userdata('menu_sparepart', 'pakai');
			$this->session->set_userdata('kode_menu', 'T0006');
			$this->session->set_userdata('keyword_pakai', '');
			$this->session->set_userdata('order_pakai', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'KP',
            'FLAG2' => 'SP'
        );
        $this->db->select('*');
        $this->db->from('pakai');
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
            'FLAG' => 'KP',
            'FLAG2' => 'SP'
        );
        $this->db->from('pakai');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_sp_pakai() {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $pakai) {
            $JASPER = "window.open('JASPER/" . $pakai->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $pakai->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Retur_Bon/update/' . $pakai->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Retur_Bon/delete/' . $pakai->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pakai->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pakai->TGL));
            $row[] = $pakai->NOTES;
            $row[] = $pakai->SUB;
            $row[] = $pakai->DR;
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

    public function index_Transaksi_Retur_Bon() {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Retur Bon');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'KP',
            'FLAG2' => 'SP'
        );
        $data['pakai'] = $this->transaksi_model->tampil_data($where,'pakai','NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Retur_Bon/Transaksi_Retur_Bon', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input() {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM beli WHERE PER='$per' AND SP='$sub' AND FLAG='PK' AND FLAG2='SP'")->result();
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
        $bukti = 'PK' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Retur_Bon/Transaksi_Retur_Bon_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi() {
        $dr = $this->session->userdata['dr']; 
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pakai WHERE PER='$per' AND SUB='$sub' AND FLAG='PK' AND FLAG2='SP'")->result();
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
        // $bukti = 'PK' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $bukti = $this->input->post('NO_BUKTI', TRUE);
        $datah = array(
            'FLAG' => 'KP',
            'FLAG2' => 'SP',
            'SUB' => $sub,
            'NO_BUKTI' => $bukti, 
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pakai',$datah);
        $ID= $this->db ->query("SELECT MAX(no_id) AS no_id FROM pakai WHERE NO_BUKTI = '$bukti' AND DR='$dr' AND PER='$per' GROUP BY NO_BUKTI")->result();
        $NO_BUKTI = $this->input->post('NO_BUKTI');
        $REC = $this->input->post('REC');
        $RAK = $this->input->post('RAK');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $KET2 = $this->input->post('KET2');
        $i = 0;
        foreach($REC as $a) {
            $datad = array(
                'id' => $ID[0]->no_id,
                'NO_BUKTI' => $NO_BUKTI,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                'FLAG' => 'PK',
                'FLAG2' => 'SP',
                'REC' => $REC[$i],
                'RAK' => $RAK[$i],
                'NA_BHN' => $NA_BHN[$i],
                'QTY' => str_replace(',','',$QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET1' => $KET1[$i],
                'KET2' => $KET2[$i],
                'USRNM' => $this->session->userdata['username'],
                'PER' => $this->session->userdata['periode'],
                'DR' => $this->session->userdata['dr'],
                'SUB' => $this->session->userdata['sub'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('pakaid',$datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI as BUKTIX from pakai where NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        // $this->db->query("CALL SP_BARANGPAKAI_POSTED('" .$no_bukti."')");
        $this->session->set_flashdata('pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Retur_Bon/index_Transaksi_Retur_Bon'); 
    }

    public function update($id) {
        $q1="SELECT pakai.NO_ID as ID,
                pakai.NO_BUKTI AS NO_BUKTI,
                pakai.NOTES AS NOTES,
                pakai.TGL AS TGL,
                pakai.TOTAL_QTY AS TOTAL_QTY,

                pakaid.NO_ID AS NO_ID,
                pakaid.REC AS REC,
                pakaid.RAK AS RAK,
                pakaid.NA_BHN AS NA_BHN,
                pakaid.QTY AS QTY,
                pakaid.SATUAN AS SATUAN,
                pakaid.KET1 AS KET1,
                pakaid.KET2 AS KET2
            FROM pakai,pakaid 
            WHERE pakai.NO_ID=$id 
            AND pakai.NO_ID=pakaid.ID 
            ORDER BY pakaid.REC";
        $data['transaksi_retur_bon']= $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Retur_Bon/Transaksi_Retur_Bon_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi() {
        $datah = array(
            'FLAG' => 'KP',
            'FLAG2' => 'SP',
            'NO_BUKTI' => $this->input->post('NO_BUKTI',TRUE),             
            'NOTES' => $this->input->post('NOTES',TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
            'TOTAL_QTY' => str_replace(',','',$this->input->post('TOTAL_QTY',TRUE)),
            'USRNM' => $this->session->userdata['username'],
            'PER' => $this->session->userdata['periode'],
            'DR' => $this->session->userdata['dr'],
            'SUB' => $this->session->userdata['sub'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'pakai');
        $id = $this->input->post('ID', TRUE);
        $q1="SELECT pakai.NO_ID as ID,
                pakai.NO_BUKTI AS NO_BUKTI,
                pakai.NOTES AS NOTES,
                pakai.TGL AS TGL,
                pakai.TOTAL_QTY AS TOTAL_QTY,

                pakaid.NO_ID AS NO_ID,
                pakaid.REC AS REC,
                pakaid.RAK AS RAK,
                pakaid.NA_BHN AS NA_BHN,
                pakaid.QTY AS QTY,
                pakaid.SATUAN AS SATUAN,
                pakaid.KET1 AS KET1,
                pakaid.KET2 AS KET2
            FROM pakai,pakaid 
            WHERE pakai.NO_ID=$id 
            AND pakai.NO_ID=pakaid.ID 
            ORDER BY pakaid.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $RAK = $this->input->post('RAK');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',','',$this->input->post('QTY',TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $KET2 = $this->input->post('KET2');
        $jum = count($data);
        $ID = array_column($data, 'NO_ID');
        $jumy = count($NO_ID);
        $i = 0;
        while ($i < $jum) {
            if (in_array($ID[$i], $NO_ID)) {
                $URUT = array_search($ID[$i], $NO_ID);
                $datad = array(
                    'FLAG' => 'KP',
                    'FLAG2' => 'SP',
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$URUT],
                    'RAK' => $RAK[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'QTY' => str_replace(',','',$QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'KET2' => $KET2[$URUT],
                    'USRNM' => $this->session->userdata['username'],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SUB' => $this->session->userdata['sub'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $where = array(
                    'NO_ID' => $NO_ID[$URUT]
                );
                $this->transaksi_model->update_data($where, $datad, 'pakaid');
            } else {
                $where = array(
                    'NO_ID' => $ID[$i]
                );
                $this->transaksi_model->hapus_data($where, 'pakaid');
            }
            $i++;
        }
        $i = 0;
        while ($i < $jumy) {
            if ($NO_ID[$i] == "0") {
                $datad = array(
                    'FLAG' => 'KP',
                    'FLAG2' => 'SP',
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL',TRUE))),
                    'REC' => $REC[$i],
                    'RAK' => $RAK[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'QTY' => str_replace(',','',$QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET1' => $KET1[$i],
                    'KET2' => $KET2[$i],
                    'USRNM' => $this->session->userdata['username'],
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SUB' => $this->session->userdata['sub'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('pakaid', $datad);
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
        redirect('admin/Transaksi_Retur_Bon/index_Transaksi_Retur_Bon');
    }

    public function delete($id) {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pakai');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pakaid');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_Retur_Bon/index_Transaksi_Retur_Bon');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('pakai', 'pakaid');
        redirect('admin/Transaksi_Retur_Bon/index_Transaksi_Retur_Bon');
    }

    public function getDataAjax_Bahan() {
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
        $results = $this->db->query("SELECT NO_ID, KD_BHN AS KD_BHN, NA_BHN AS NA_BHN, RAK AS RAK
            FROM bhnd
            WHERE DR = '$dr' AND SUB = '$sub' AND (KD_BHN LIKE '%$search%' OR NA_BHN LIKE '%$search%' OR RAK LIKE '%$search%')
            GROUP BY KD_BHN
            ORDER BY KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['RAK'],
                'text' => $row['RAK'],
                'KD_BHN' => $row['RAK'] . " - " . $row['NA_BHN'],
                'NA_BHN' => $row['NA_BHN'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

}