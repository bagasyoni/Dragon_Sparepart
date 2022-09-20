<?php

class Transaksi_PesananOnline extends CI_Controller
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
            $this->session->set_userdata('kode_menu', 'T0030');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'SUB', 'DR');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'PESAN_ONLINE',
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
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'PESAN_ONLINE',
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
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananOnline/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananOnline/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
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

    public function index_Transaksi_PesananOnline()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Pemesanan Online');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'PESAN_ONLINE',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananOnline/Transaksi_PesananOnline', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='PP' AND FLAG2='SP'")->result();
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
        // PP / NOMER / DR / BULAN / TAHUN / SP
        $bukti = 'PP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananOnline/Transaksi_PesananOnline_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='$sub' AND FLAG='PP' AND FLAG2='SP'")->result();
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
        // PP / NOMER / DR / BULAN / TAHUN / SP
        $bukti = 'PP' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'PESAN_ONLINE',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pp', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM pp WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $JENIS = $this->input->post('JENIS');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET = $this->input->post('KET');
        $DEVISI = $this->input->post('DEVISI');
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISABON = str_replace(',', '', $this->input->post('SISABON', TRUE));
        $URGENT = str_replace(',', '', $this->input->post('URGENT', TRUE));
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'REC' => $REC[$i],
                'NO_BON' => '-',
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'JENIS' => $JENIS[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SISA' => str_replace(',', '', $SISA[$i]),
                'SATUAN' => $SATUAN[$i],
                'DEVISI' => $DEVISI[$i],
                'KET' => $KET[$i],
                'SISABON' => str_replace(',', '', $SISABON[$i]),
                'URGENT' => isset($URGENT[$i]) ? $URGENT[$i] : 0,
                'FLAG' => 'PP',
                'FLAG2' => 'SP',
                'LOGISTIK' => '0',
                'TYP' => 'PESAN_ONLINE',
                'SUB' => $this->session->userdata['sub'],
                'DR' => $this->session->userdata['dr'],
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a"),
                'NO_TIKET' => $bukti."_".$KD_BHN[$i],
            );
            $this->transaksi_model->input_datad('ppd', $datad);
            $i++;
        }
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
    }

    public function update($id)
    {
        $q1 = "SELECT pp.NO_ID as ID,
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.TTD1 AS TTD1,
                pp.TTD2 AS TTD2,
                pp.TTD3 AS TTD3,
                pp.TTD4 AS TTD4,
                pp.TTD5 AS TTD5,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.JENIS AS JENIS,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.DEVISI AS DEVISI,
                ppd.KET AS KET,
                ppd.SISABON AS SISABON,
                ppd.URGENT AS URGENT
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data['ppstok'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananOnline/Transaksi_PesananOnline_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'PP',
            'FLAG2' => 'SP',
            'LOGISTIK' => '0',
            'TYP' => 'PESAN_ONLINE',
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
                pp.NO_BUKTI AS NO_BUKTI,
                pp.TGL AS TGL,
                pp.TOTAL_QTY AS TOTAL_QTY,
                pp.TTD1 AS TTD1,
                pp.TTD2 AS TTD2,
                pp.TTD3 AS TTD3,
                pp.TTD4 AS TTD4,
                pp.TTD5 AS TTD5,

                ppd.NO_ID AS NO_ID,
                ppd.REC AS REC,
                ppd.KD_BHN AS KD_BHN,
                ppd.NA_BHN AS NA_BHN,
                ppd.JENIS AS JENIS,
                ppd.QTY AS QTY,
                ppd.SATUAN AS SATUAN,
                ppd.DEVISI AS DEVISI,
                ppd.KET AS KET,
                ppd.SISABON AS SISABON,
                ppd.URGENT AS URGENT
            FROM pp,ppd 
            WHERE pp.NO_ID=$id 
            AND pp.NO_ID=ppd.ID 
            ORDER BY ppd.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $JENIS = $this->input->post('JENIS');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $DEVISI = $this->input->post('DEVISI');
        $KET = $this->input->post('KET');
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
                    'KD_BHN' => $KD_BHN[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'JENIS' => $JENIS[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SISA' => str_replace(',', '', $SISA[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'DEVISI' => $DEVISI[$URUT],
                    'KET' => $KET[$URUT],
                    'SISABON' => str_replace(',', '', $SISABON[$URUT]),
                    'URGENT' => isset($URGENT[$URUT]) ? $URGENT[$URUT] : 0,
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'LOGISTIK' => '0',
                    'TYP' => 'PESAN_ONLINE',
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
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'JENIS' => $JENIS[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SISA' => str_replace(',', '', $SISA[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'DEVISI' => $DEVISI[$i],
                    'KET' => $KET[$i],
                    'SISABON' => str_replace(',', '', $SISABON[$i]),
                    'URGENT' => isset($URGENT[$URUT]) ? $URGENT[$URUT] : 0,
                    'FLAG' => 'PP',
                    'FLAG2' => 'SP',
                    'LOGISTIK' => '0',
                    'TYP' => 'PESAN_ONLINE',
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
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
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
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp', 'ppd');
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
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
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
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
            WHERE bhn.KD_BHN=bhnd.KD_BHN AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%')
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
        redirect('admin/Transaksi_PesananOnline/index_Transaksi_PesananOnline');
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
            ppd.KD_BHN AS KD_BHN,
            ppd.NA_BHN AS NA_BHN,
            ppd.JENIS AS JENIS,
            ppd.BILANGAN AS BILANGAN,
            ppd.QTY AS QTY,
            ppd.SATUAN AS SATUAN,
            ppd.DEVISI AS DEVISI,
            ppd.KET AS KET,
            ppd.TGL_DIMINTA AS TGL_DIMINTA,
            ppd.SISABON AS SISABON,
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
                "SISABON" => $row1["SISABON"],
                "URGENT" => $row1["URGENT"],
                "SUB" => $row1["SUB"],
            ));
        }
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
