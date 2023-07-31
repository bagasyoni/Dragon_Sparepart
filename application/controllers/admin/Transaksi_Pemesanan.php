<?php

class Transaksi_Pemesanan extends CI_Controller
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
            $this->session->set_userdata('kode_menu', 'T0001');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
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
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            // 'TYP' => 'PEMESANAN',
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
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            // 'TYP' => 'PEMESANAN',
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
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Pemesanan/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_Pemesanan/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = $pp->NOTES;
            $row[] = $pp->SUB;
            $row[] = $pp->DR;
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

    public function index_Transaksi_Pemesanan()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Pemesanan');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            // 'TYP' => 'PEMESANAN',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Pemesanan/Transaksi_Pemesanan', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        // $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='PP' AND FLAG2='SP'")->result();
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='' AND FLAG2='NB' and DR='$dr'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 4, 3);
        $value22 = (float)STRVAL($value11) + 1;
        $urut = str_pad($value22, 3, "0", STR_PAD_LEFT);
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
        // PP / NOMER / DR / BULAN / TAHUN / SP
        $bukti = 'PP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Pemesanan/Transaksi_Pemesanan_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        // $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='PP' AND FLAG2='SP'")->result();
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='' AND FLAG2='NB'and DR='$dr'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 3, 3);
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 3, "0", STR_PAD_LEFT);
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
        // PP / NOMER / DR / BULAN / TAHUN / SP
        $bukti = 'PP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'NOTES' => $this->input->post('NOTES', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            'FLAG' => '',
            'FLAG2' => 'NB',
            'LOGISTIK' => '0',
            'TTD1' => '1',
            'TTD2' => '1',
            // 'TTD3' => '1',
            // 'TTD4' => '1',
            // 'TTD5' => '1',
            // 'TTD6' => '1',
            // 'TTD7' => '1',
            'TYP' => 'PEMESANAN',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pp', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM pp WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $NO_BON = $this->input->post('NO_BON');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $TIPE = $this->input->post('TIPE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $BILANGAN = $this->input->post('BILANGAN');
        $SATUAN = $this->input->post('SATUAN');
        $DEVISI = $this->input->post('DEVISI');
        $KET = $this->input->post('KET');
        $TGL_DIMINTA = $this->input->post('TGL_DIMINTA');
        $SISABON = str_replace(',', '', $this->input->post('SISABON', TRUE));
        $URGENT = str_replace(',', '', $this->input->post('URGENT', TRUE));
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'REC' => $REC[$i],
                'NO_BON' => $NO_BON[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'TIPE' => $TIPE[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SISA' => str_replace(',', '', $QTY[$i]),
                'SISA_BELI' => str_replace(',', '', $QTY[$i]),
                'BILANGAN' => strtoupper($BILANGAN[$i]),
                'SATUAN' => $SATUAN[$i],
                'DEVISI' => $DEVISI[$i],
                'KET' => $KET[$i],
                'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA[$i])),
                'SISABON' => str_replace(',', '', $SISABON[$i]),
                'URGENT' => isset($URGENT[$i]) ? $URGENT[$i] : 0,
                'FLAG' => 'PP',
                // 'FLAG2' => 'SP',
                'FLAG2' => 'NB',
                'LOGISTIK' => '0',
                'PILIH' => '0',
                'TYP' => 'PEMESANAN',
                'SUB' => $this->session->userdata['sub'],
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a"),
                'NO_TIKET' => $bukti . "_" . $KD_BHN[$i],
            );
            $this->transaksi_model->input_datad('ppd', $datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pp WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_ppins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
    }

    public function update($id)
    {
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.NOTES AS NOTES,
                pp.TGL AS TGL,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.TTD1 AS TTD1,
                pp.TTD2 AS TTD2,
                pp.TTD3 AS TTD3,
                pp.TTD4 AS TTD4,
                pp.TTD5 AS TTD5,
                pp.TTD6 AS TTD6,
                pp.TTD7 AS TTD7,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NO_BON AS NO_BON,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.TIPE AS TIPE,
                ppd.BILANGAN AS BILANGAN,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.DEVISI AS DEVISI,
                ppd.KET AS KET,
                ppd.TGL_DIMINTA AS TGL_DIMINTA,
                ppd.SISABON AS SISABON,
                ppd.URGENT AS URGENT,
                ppd.NO_TIKET AS NO_TIKET,
                ppd.PILIH AS PILIH
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data['pemesanan'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_Pemesanan/Transaksi_Pemesanan_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $bukti = $this->input->post('NO_BUKTI');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => '',
            // 'FLAG2' => 'SP',
            'FLAG2' => 'NB',
            'LOGISTIK' => '0',
            'TYP' => 'PEMESANAN',
            'TTD1' => '1',
            'TTD2' => '1',
            // 'TTD3' => '1',
            // 'TTD4' => '1',
            // 'TTD5' => '1',
            // 'TTD6' => '1',
            // 'TTD7' => '1',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pp WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_ppdel('" . $no_bukti . "')");
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.NOTES AS NOTES,
                pp.TGL AS TGL,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.TTD1 AS TTD1,
                pp.TTD2 AS TTD2,
                pp.TTD3 AS TTD3,
                pp.TTD4 AS TTD4,
                pp.TTD5 AS TTD5,
                pp.TTD6 AS TTD6,
                pp.TTD7 AS TTD7,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.NO_BON AS NO_BON,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.TIPE AS TIPE,
                ppd.QTY AS QTY,
                ppd.BILANGAN AS BILANGAN,
                ppd.SATUAN AS SATUAN,
                ppd.DEVISI AS DEVISI,
                ppd.KET AS KET,
                ppd.TGL_DIMINTA AS TGL_DIMINTA,
                ppd.SISABON AS SISABON,
                ppd.URGENT AS URGENT,
                ppd.NO_TIKET AS NO_TIKET,
                ppd.PILIH AS PILIH
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $NO_BON = $this->input->post('NO_BON');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $TIPE = $this->input->post('TIPE');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $BILANGAN = $this->input->post('BILANGAN');
        $SATUAN = $this->input->post('SATUAN');
        $DEVISI = $this->input->post('DEVISI');
        $KET = $this->input->post('KET');
        $TGL_DIMINTA = $this->input->post('TGL_DIMINTA');
        $SISABON = str_replace(',', '', $this->input->post('SISABON', TRUE));
        $URGENT =  $this->input->post('URGENT');
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
                    'NO_BON' => $NO_BON[$URUT],
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'TIPE' => $TIPE[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SISA' => str_replace(',', '', $QTY[$URUT]),
                    'SISA_BELI' => str_replace(',', '', $QTY[$URUT]),
                    'BILANGAN' => strtoupper($BILANGAN[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'DEVISI' => $DEVISI[$URUT],
                    'KET' => $KET[$URUT],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA[$URUT])),
                    'SISABON' => str_replace(',', '', $SISABON[$URUT]),
                    'URGENT' => isset($URGENT[$URUT]) ? $URGENT[$URUT] : 0,
                    'FLAG' => 'PP',
                    // 'FLAG2' => 'SP',
                    'FLAG2' => 'NB',
                    'LOGISTIK' => '0',
                    'TYP' => 'PEMESANAN',
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
                    'NO_BON' => $NO_BON[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'TIPE' => $TIPE[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SISA' => str_replace(',', '', $QTY[$i]),
                    'SISA_BELI' => str_replace(',', '', $QTY[$i]),
                    'BILANGAN' => strtoupper($BILANGAN[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'DEVISI' => $DEVISI[$i],
                    'KET' => $KET[$i],
                    'TGL_DIMINTA' => date("Y-m-d", strtotime($TGL_DIMINTA[$i])),
                    'SISABON' => str_replace(',', '', $SISABON[$i]),
                    'URGENT' => isset($URGENT[$URUT]) ? $URGENT[$URUT] : 0,
                    'FLAG' => 'PP',
                    // 'FLAG2' => 'SP',
                    'FLAG2' => 'NB',
                    'LOGISTIK' => '0',
                    'TYP' => 'PEMESANAN',
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
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pp WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_ppins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
    }

    public function delete($id)
    {
        $data = $this->db->query("SELECT NO_BUKTI FROM pp WHERE NO_ID='$id'")->result();
        $this->db->query("CALL spp_ppdel('" . $data[0]->NO_BUKTI . "')");
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
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp', 'ppd');
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
    }

    public function PostedTTD1_aksi($NO_ID)
    {
        $datah = array(
            'TTD1' => 1,
            'TTD1_USR' => $this->session->userdata['username'],
            'TGVAL1' => date("Y-m-d h:i:s")
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $datahd = array(
            'TTD1' => 1,
            'TTD1_USR' => $this->session->userdata['username'],
            'TGVAL1' => date("Y-m-d h:i:s")
        );
        $whered = array(
            'ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'ppd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Posted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>'
        );
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
    }

    public function getDataAjax_bond()
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
        $results = $this->db->query("SELECT bond.NO_ID, 
                bond.NO_BUKTI AS NO_BON,
                bond.KD_BHN, 
                bond.NA_BHN,
                bond.TYPE,
                bond.KET,
                bond.NOTES AS TIPE,
                (bond.QTY-bond.KIRIM) AS QTY,
                (bond.QTY-bond.KIRIM) AS SISABON,
                bond.SATUAN,

                bon.NM_BAG AS DEVISI,
                date_format(bon.TGL ,'%d-%m-%Y') AS TGL_DIMINTA,
                bon.SUB
            FROM bon, bond
            WHERE bon.NO_BUKTI=bond.NO_BUKTI AND bon.TTD2<>'' AND bon.TUJUAN='$sub' AND bon.DR='$dr' AND bond.OK=0 AND bond.QTY - bond.KIRIM <> 0 AND (bond.NO_BUKTI LIKE '%$search%' OR bond.NA_BHN LIKE '%$search%')
            ORDER BY bond.NO_BUKTI LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NO_BON'],
                'text' => $row['NO_BON'],
                'NO_BON' => $row['NO_BON'] . " - " . $row['NA_BHN'] . " - " . $row['TIPE'] . " - " . $row['QTY'] . " - " . $row['SATUAN'] . " - " . $row['SUB'] . " - " . $row['DEVISI'] . " - " . $row['KET'] . " - " . $row['TGL_DIMINTA'],
                'NA_BHN' => $row['NA_BHN'],
                'TIPE' => $row['TIPE'],
                'KET' => $row['KET'],
                'SUB' => $row['SUB'],
                'SATUAN' => $row['SATUAN'],
                'QTY' => $row['QTY'],
                'SISABON' => $row['SISABON'],
                'DEVISI' => $row['DEVISI'],
                'TGL_DIMINTA' => $row['TGL_DIMINTA'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function verifikasi_ttd1($NO_ID)
    {
        $datah = array(
            'TTD1' => 1,
            'TTD1_USR' => $this->session->userdata['username'],
            'TTD1_SMP' => date("Y-m-d h:i:s")
        );
        $where = array(
            'NO_ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        $datahd = array(
            'TTD1' => 1,
            'TTD1_USR' => $this->session->userdata['username'],
            'TTD1_SMP' => date("Y-m-d h:i:s")
        );
        $whered = array(
            'ID' => "$NO_ID"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'ppd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>'
        );
        redirect('admin/Transaksi_Pemesanan/index_Transaksi_Pemesanan');
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_Pemesanan_SP.jrxml");
        $no_id = $id;
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $query = "SELECT pp.NO_ID as ID,
            pp.NO_BUKTI AS NO_BUKTI,
            pp.NOTES AS NOTES,
            pp.TGL AS TGL,
            pp.TOTAL_QTY AS TOTAL_QTY,
            pp.TTD1 AS TTD1,
            pp.TTD2 AS TTD2,
            pp.TTD3 AS TTD3,
            pp.TTD4 AS TTD4,
            pp.TTD5 AS TTD5,

            CASE pp.SUB
					WHEN 'SP' THEN 'SPAREPART'
					WHEN 'INV' THEN 'INVENTARIS'
                    WHEN 'UM' THEN 'UMUM'
                    WHEN 'ATK' THEN 'ALAT TULIS KANTOR'
			END AS 'SUB',

            ppd.NO_ID AS NO_ID,
            ppd.REC AS REC,
            ppd.NO_BON AS NO_BON,
            ppd.KD_BHN AS KD_BHN,
            ppd.NA_BHN AS NA_BHN,
            ppd.TIPE AS JENIS,
            ppd.BILANGAN AS BILANGAN,
            ppd.QTY AS QTY,
            ppd.SATUAN AS SATUAN,
            ppd.DEVISI AS DEVISI,
            ppd.KET AS KET,
            ppd.TGL_DIMINTA AS TGL_DIMINTA,
            ppd.SISABON AS SISA,
            ppd.URGENT AS URGENT
        FROM pp,ppd
        WHERE pp.NO_ID=$id 
        AND pp.NO_ID=ppd.ID
        ORDER BY ppd.REC";
        $PHPJasperXML->transferDBtoArray($servername, $username, $password, $database);
        $PHPJasperXML->arraysqltable = array();
        $result1 = mysqli_query($conn, $query);
        while ($row1 = mysqli_fetch_assoc($result1)) {
            array_push($PHPJasperXML->arraysqltable, array(
                "NO_BUKTI" => $row1["NO_BUKTI"],
                "NOTES" => $row1["NOTES"],
                "TGL" => $row1["TGL"],
                "TOTAL_QTY" => $row1["TOTAL_QTY"],
                "TTD1" => $row1["TTD1"],
                "TTD2" => $row1["TTD2"],
                "TTD3" => $row1["TTD3"],
                "TTD4" => $row1["TTD4"],
                "TTD5" => $row1["TTD5"],
                "NO_ID" => $row1["NO_ID"],
                "REC" => $row1["REC"],
                "NO_BON" => $row1["NO_BON"],
                "KD_BHN" => $row1["KD_BHN"],
                "NA_BHN" => $row1["NA_BHN"],
                "JENIS" => $row1["JENIS"],
                "BILANGAN" => $row1["BILANGAN"],
                "QTY" => $row1["QTY"],
                "SATUAN" => $row1["SATUAN"],
                "DEVISI" => $row1["DEVISI"],
                "KET" => $row1["KET"],
                "TGL_DIMINTA" => $row1["TGL_DIMINTA"],
                "SISA" => $row1["SISA"],
                "URGENT" => $row1["URGENT"],
                "SUB" => $row1["SUB"],
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
        $q1 = "SELECT NO_ID FROM pp WHERE NO_ID<'$ID' AND PER='$per' AND DR='$dr' AND SUB='$sub' AND LOGISTIK='0' ORDER BY NO_BUKTI DESC LIMIT 1";
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
        $q1 = "SELECT NO_ID FROM pp WHERE NO_ID>'$ID' AND PER='$per' AND DR='$dr' AND SUB='$sub' AND LOGISTIK='0' ORDER BY NO_BUKTI ASC LIMIT 1";
        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }
}
