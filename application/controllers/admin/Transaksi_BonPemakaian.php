<?php

class Transaksi_BonPemakaian extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'pakai') {
            $this->session->set_userdata('menu_sparepart', 'pakai');
            $this->session->set_userdata('kode_menu', 'T0005');
            $this->session->set_userdata('keyword_pakai', '');
            $this->session->set_userdata('order_pakai', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'NOTES', 'FLAG', 'DR');
    var $column_search = array('NO_BUKTI', 'TGL', 'NOTES', 'FLAG', 'DR');
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
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'ATK' => '0'
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
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'ATK' => '0'
        );
        $this->db->from('pakai');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_pakai()
    {
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
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_BonPemakaian/update/' . $pakai->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_BonPemakaian/delete/' . $pakai->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
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

    public function index_Transaksi_BonPemakaian()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Bon Pemakaian');
        $where = array(
            'DR' => $dr,
            'PER' => $per,
            'SUB' => $sub,
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'ATK' => '0'
        );
        $data['pakai'] = $this->transaksi_model->tampil_data($where, 'pakai', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonPemakaian/Transaksi_BonPemakaian', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonPemakaian/Transaksi_BonPemakaian_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pakai WHERE FLAG='PK' AND FLAG2='SP' AND PER='$per' AND SUB='$sub' AND DR='$dr'")->result();
        $nom = array_column($nomer, 'NO_BUKTI');
        $value11 = substr($nom[0], 3, 7);
        $value22 = STRVAL($value11) + 1;
        $urut = str_pad($value22, 4, "0", STR_PAD_LEFT);
        $tahun = substr($this->session->userdata['periode'], -4);
        $bulan = substr($this->session->userdata['periode'], 0, 2);
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
        // $bukti = 'PK' . '/' . $urut . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $no = $this->input->post('NO_BUKTI', TRUE);
        $ht = strlen($no);
        if ($ht <= 3) {
            $bukti = $no . '/' . $bulan . '/' . $tahun . '/BB';
        } else {
            $bukti = $this->input->post('NO_BUKTI', TRUE);
        }
        // var_dump($bukti);
        // die;

        $datah = array(
            'NO_BUKTI' => $bukti,
            'NOTES' => $this->input->post('NOTES', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'ATK' => '0',
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pakai', $datah);
        $ID = $this->db->query("SELECT NO_ID AS no_id FROM pakai WHERE NO_BUKTI = '$bukti' AND SUB = '$sub' GROUP BY NO_BUKTI")->result();
        $no = $this->input->post('NO_BUKTI', TRUE);
        $ht = strlen($no);
        if ($ht <= 3) {
            $NO_BUKTI = $no . '/' . $bulan . '/' . $tahun . '/BB';
        } else {
            $NO_BUKTI = $this->input->post('NO_BUKTI', TRUE);
        }
        // $NO_BUKTI = $no . '/' . $bulan . '/' . $tahun . '/BB';
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $RAK = $this->input->post('RAK');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $KET2 = $this->input->post('KET2');
        $GRUP = $this->input->post('GRUP');
        $NA_GOL = $this->input->post('NA_GOL');
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->no_id,
                'NO_BUKTI' => $NO_BUKTI,
                'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                'REC' => $REC[$i],
                'KD_BHN' => $KD_BHN[$i],
                // 'KD_BHN' => '',
                'RAK' => $RAK[$i],
                'NA_BHN' => $NA_BHN[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'SATUAN' => $SATUAN[$i],
                'KET1' => $KET1[$i],
                'KET2' => $KET2[$i] ?? "-",
                'GRUP' => $GRUP[$i],
                // 'NA_GOL' => $NA_GOL[$i],
                'NA_GOL' => $KET2[$i],
                'FLAG' => 'PK',
                'FLAG2' => 'SP',
                'ATK' => '0',
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'DR' => $this->session->userdata['dr'],
                'SUB' => $this->session->userdata['sub'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('pakaid', $datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pakai WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_pakaiins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonPemakaian/index_Transaksi_BonPemakaian');
    }

    public function update($id)
    {
        $q1 = "SELECT pakai.NO_ID as ID,
                pakai.NO_BUKTI AS NO_BUKTI,
                pakai.NOTES AS NOTES,
                pakai.TGL AS TGL,
                pakai.TOTAL_QTY AS TOTAL_QTY,

                pakaid.NO_ID AS NO_ID,
                pakaid.REC AS REC,
                pakaid.KD_BHN AS KD_BHN,
                pakaid.RAK AS RAK,
                pakaid.NA_BHN AS NA_BHN,
                pakaid.QTY AS QTY,
                pakaid.SATUAN AS SATUAN,
                pakaid.KET1 AS KET1,
                pakaid.KET2 AS KET2,
                pakaid.GRUP AS GRUP,
                pakaid.NA_GOL AS NA_GOL
            FROM pakai,pakaid 
            WHERE pakai.NO_ID=$id 
            AND pakai.NO_ID=pakaid.ID 
            ORDER BY pakaid.REC";
        $data['bonpemakaian'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonPemakaian/Transaksi_BonPemakaian_update', $data);
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
            'FLAG' => 'PK',
            'FLAG2' => 'SP',
            'ATK' => '0',
            'PER' => $this->session->userdata['periode'],
            'SUB' => $this->session->userdata['sub'],
            'DR' => $this->session->userdata['dr'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pakai WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_pakaidel('" . $no_bukti . "')");
        $this->transaksi_model->update_data($where, $datah, 'pakai');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT pakai.NO_ID as ID,
                pakai.NO_BUKTI AS NO_BUKTI,
                pakai.NOTES AS NOTES,
                pakai.TGL AS TGL,
                pakai.TOTAL_QTY AS TOTAL_QTY,

                pakaid.NO_ID AS NO_ID,
                pakaid.REC AS REC,
                pakaid.KD_BHN AS KD_BHN,
                pakaid.RAK AS RAK,
                pakaid.NA_BHN AS NA_BHN,
                pakaid.QTY AS QTY,
                pakaid.SATUAN AS SATUAN,
                pakaid.KET1 AS KET1,
                pakaid.KET2 AS KET2,
                pakaid.GRUP AS GRUP,
                pakaid.NA_GOL AS NA_GOL
            FROM pakai,pakaid 
            WHERE pakai.NO_ID=$id 
            AND pakai.NO_ID=pakaid.ID 
            ORDER BY pakaid.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $RAK = $this->input->post('RAK');
        $NA_BHN = $this->input->post('NA_BHN');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
        $KET1 = $this->input->post('KET1');
        $KET2 = $this->input->post('KET2');
        $GRUP = $this->input->post('GRUP');
        $NA_GOL = $this->input->post('NA_GOL');
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
                    'RAK' => $RAK[$URUT],
                    'NA_BHN' => $NA_BHN[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'KET1' => $KET1[$URUT],
                    'KET2' => $KET2[$URUT],
                    'GRUP' => $GRUP[$URUT],
                    // 'NA_GOL' => $NA_GOL[$URUT],
                    'NA_GOL' => $KET2[$URUT],
                    'FLAG' => 'PK',
                    'FLAG2' => 'SP',
                    'ATK' => '0',
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SUB' => $this->session->userdata['sub'],
                    'USRNM' => $this->session->userdata['username'],
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
                    'ID' => $this->input->post('ID', TRUE),
                    'NO_BUKTI' => $this->input->post('NO_BUKTI'),
                    'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
                    'REC' => $REC[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'RAK' => $RAK[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'KET1' => $KET1[$i],
                    'KET2' => $KET2[$i],
                    'GRUP' => $GRUP[$i],
                    'NA_GOL' => $KET2[$i],
                    // 'NA_GOL' => $NA_GOL[$i],
                    'FLAG' => 'PK',
                    'FLAG2' => 'SP',
                    'ATK' => '0',
                    'PER' => $this->session->userdata['periode'],
                    'DR' => $this->session->userdata['dr'],
                    'SUB' => $this->session->userdata['sub'],
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('pakaid', $datad);
            }
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM pakai WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL spp_pakaiins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonPemakaian/index_Transaksi_BonPemakaian');
    }

    public function delete($id)
    {
        $data = $this->db->query("SELECT NO_BUKTI FROM pakai WHERE NO_ID='$id'")->result();
        $this->db->query("CALL spp_pakaidel('" . $data[0]->NO_BUKTI . "')");
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pakai');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pakaid');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_BonPemakaian/index_Transaksi_BonPemakaian');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pakai', 'pakaid');
        redirect('admin/Transaksi_BonPemakaian/index_Transaksi_BonPemakaian');
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
        $results = $this->db->query("SELECT bhn.NO_ID, bhn.NA_BHN, bhn.SATUAN, bhnd.RAK
            FROM bhn, bhnd
            WHERE bhn.KD_BHN=bhnd.KD_BHN AND bhnd.FLAG='SP' AND bhnd.DR='$dr' AND bhnd.SUB='$sub' AND bhnd.RAK <> '' AND (bhn.NA_BHN LIKE '%$search%' OR bhnd.RAK LIKE '%$search%')
            GROUP BY bhn.KD_BHN
            ORDER BY bhn.KD_BHN LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['RAK'],
                'text' => $row['RAK'],
                'RAK' => $row['RAK'] . " - " . $row['NA_BHN'] . " - " . $row['SATUAN'],
                'NA_BHN' => $row['NA_BHN'],
                'SATUAN' => $row['SATUAN'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }

    public function getDataAjax_sp_mesin()
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
        $results = $this->db->query("SELECT sp_mesin.NO_ID, sp_mesin.KD_GOL, sp_mesin.NA_GOL, sp_mesin.GRUP, sp_mesin.DR, sp_mesin.SUB
            FROM sp_mesin
            WHERE sp_mesin.KD_GOL LIKE '%$search%' OR sp_mesin.NA_GOL LIKE '%$search%' OR sp_mesin.GRUP LIKE '%$search%'
            ORDER BY sp_mesin.NO_ID LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['NA_GOL'],
                'text' => $row['NA_GOL'],
                'KD_GOL' => $row['KD_GOL'] . " - " . $row['NA_GOL'] . " - " . $row['GRUP'],
                // 'KD_GOL' => $row['KD_GOL'],
                'NA_GOL' => $row['NA_GOL'],
                'GRUP' => $row['GRUP'],
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
        $PHPJasperXML->load_xml_file("phpjasperxml/Transaksi_BonPemakaian.jrxml");
        $no_id = $id;
        $query = "SELECT pakai.no_id as ID,
                pakai.no_sp AS MODEL,
                pakai.perke AS PERKE,
                pakai.tgl_sp AS TGL_SP,
                pakai.nodo AS NODO,
                pakai.tgldo AS TGLDO,
                pakai.tlusin AS TLUSIN,
                pakai.tpair AS TPAIR,

                pakaid.no_id AS NO_ID,
                pakaid.rec AS REC,
                CONCAT(pakaid.article,' - ',pakaid.warna) AS ARTICLE,
                pakaid.size AS SIZE,
                pakaid.golong AS GOLONG,
                pakaid.sisa AS SISA,
                pakaid.lusin AS LUSIN,
                pakaid.pair AS PAIR,
                CONCAT(pakaid.kodecus,' - ',pakaid.nama) AS KODECUS,
                pakaid.kota AS KOTA
            FROM pakai,pakaid 
            WHERE pakai.no_id=$id 
            AND pakai.no_id=pakaid.id 
            ORDER BY pakaid.rec";
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

        $q1 = " SELECT NO_ID FROM pakai WHERE NO_ID<'$ID' AND FLAG = 'PK' AND FLAG2 = 'SP' AND PER='$per' AND ATK = '0' AND DR = '$dr' AND SUB = '$sub' ORDER BY NO_ID DESC LIMIT 1";

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

        $q1 = " SELECT NO_ID FROM pakai WHERE NO_ID>'$ID' AND FLAG = 'PK' AND FLAG2 = 'SP' AND PER='$per' AND ATK = '0' AND DR = '$dr' AND SUB = '$sub' ORDER BY NO_ID LIMIT 1";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }

    function isiRAK()
    {
        $VAL = $this->input->post('VAL');

        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];

        $q1 = "SELECT bhn.NA_BHN, bhn.SATUAN, bhnd.RAK, bhnd.KD_BHN
            FROM bhn, bhnd
            WHERE bhnd.RAK='$VAL' AND bhn.KD_BHN=bhnd.KD_BHN AND bhnd.FLAG='SP' AND bhnd.DR='$dr' AND bhnd.SUB='$sub' AND bhnd.RAK <> '' 
            GROUP BY bhn.KD_BHN
            ORDER BY bhn.KD_BHN";

        $q2 = $this->db->query($q1);
        if ($q2->num_rows() > 0) {
            foreach ($q2->result() as $row) {
                $hasil[] = $row;
            }
        };
        echo json_encode($hasil);
    }
}
