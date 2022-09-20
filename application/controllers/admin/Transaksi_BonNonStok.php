<?php

class Transaksi_BonNonStok extends CI_Controller
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
        if ($this->session->userdata['menu_sparepart'] != 'bond') {
            $this->session->set_userdata('menu_sparepart', 'bond');
            $this->session->set_userdata('kode_menu', 'T0021');
            $this->session->set_userdata('keyword_bon', '');
            $this->session->set_userdata('order_bon', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGLD', 'NA_BHN', 'DR', 'NA_BHN', 'SLS');
    var $column_search = array('NO_BUKTI', 'TGLD', 'NA_BHN', 'DR', 'NA_BHN', 'SLS');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $where = array(
            
            'DR' => $dr,
            'SLS' => '0',
            'SPD' => $sub,
            // 'a.no_bukti' => 'b.no_bukti',
        );
        $this->db->select('*');
        $this->db->from('bond');
        // $this->db->join('bon', 'bon.no_bukti = bond.no_bukti');
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
        $sub = $this->session->userdata['sub'];
        $where = array(
            'DR' => $dr,
            'SLS' => '0',
            'SPD' => $sub,
        );
        $this->db->from('bond');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_bon()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $bon) {
            $JASPER = "window.open('JASPER/" . $bon->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $bon->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_BonNonStok/update/' . $bon->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $bon->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($bon->TGLD));
            // $row[] = $bon->NM_BAG;
            $row[] = $bon->DR;
            $row[] = $bon->NA_BHN;
            $status = $bon->SLS;
            if($status == '1'){
                $status = 'SELESAI';
            }
            else{
                $status = 'BELUM SELESAI';
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

    public function index_Transaksi_BonNonStok()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $this->session->set_userdata('judul', 'Transaksi Bon Non Stok');
        $where = array(
            'DR' => $dr,
            'SLS' => '0',
            'SPD' => $sub,
        );
        $data['bond'] = $this->transaksi_model->tampil_data($where, 'bond', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonNonStok/Transaksi_BonNonStok', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
    }

    public function input_aksi()
    {
    }

    public function update($id)
    {
        $q1 = "SELECT beli.NO_ID as ID,
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
        $data['bon_nonstok'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_BonNonStok/Transaksi_BonNonStok_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'KET' => $this->input->post('KET', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'TOTAL_QTYPP' => str_replace(',', '', $this->input->post('TOTAL_QTYPP', TRUE)),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'VAL' => '1',
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'beli');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT beli.NO_ID as ID,
                beli.NO_BUKTI AS NO_BUKTI,
                beli.KET AS KET,
                beli.TGL AS TGL,
                beli.PIN1 AS PIN1,
                beli.TOTAL_QTYPP AS TOTAL_QTYPP,
                beli.TOTAL_QTY AS TOTAL_QTY,
                beli.VAL AS VAL,
                beli.TTD1 AS TTD1,
                beli.TTD1 AS TTD2,
                beli.TTD1 AS TTD3,
                beli.TTD1 AS TTD4,
                beli.TTD1 AS TTD5,
                beli.TTD1 AS TTD6,

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
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $RAK = $this->input->post('RAK');
        $QTYPP = str_replace(',', '', $this->input->post('QTYPP', TRUE));
        $SATUANPP = $this->input->post('SATUANPP');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SISA = str_replace(',', '', $this->input->post('QTY', TRUE));
        $SATUAN = $this->input->post('SATUAN');
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
                    'QTYPP' => str_replace(',', '', $QTYPP[$URUT]),
                    'SATUANPP' => $SATUANPP[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
                    'SISA' => str_replace(',', '', $SISA[$URUT]),
                    'SATUAN' => $SATUAN[$URUT],
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
                    'VAL' => '1',
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
                    'QTYPP' => str_replace(',', '', $QTYPP[$i]),
                    'SATUANPP' => $SATUANPP[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'SISA' => str_replace(',', '', $SISA[$i]),
                    'SATUAN' => $SATUAN[$i],
                    'FLAG' => 'BL',
                    'FLAG2' => 'SP',
                    'VAL' => '1'
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
        redirect('admin/Transaksi_BonNonStok/index_Transaksi_BonNonStok');
    }

    public function delete($id)
    {
    }

    function delete_multiple()
    {
    }

    public function verifikasi_ttd1($NO_BUKTI)
    {
        $datah = array(
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'VAL' => '1',
            'TTD1' => 1,
            'TTD2' => 1,
            'TTD3' => 1,
            'TTD4' => 1,
            'TTD5' => 1,
            'TTD6' => 1,
            'PIN2' => $this->session->userdata['pin'],
            'TTD2_USR' => $this->session->userdata['username']
        );
        $where = array(
            'NO_BUKTI' => "$NO_BUKTI"
        );
        $this->transaksi_model->update_data($where, $datah, 'beli');
        $datahd = array(
            'FLAG' => 'BL',
            'FLAG2' => 'SP',
            'ATK' => '0',
            'VAL' => '1',
            'TTD1' => 1,
            'TTD2' => 1,
            'TTD3' => 1,
            'TTD4' => 1,
            'TTD5' => 1,
            'TTD6' => 1,
            'PIN2' => $this->session->userdata['pin'],
            'TTD2_USR' => $this->session->userdata['username']
        );
        $whered = array(
            'NO_BUKTI' => "$NO_BUKTI"
        );
        $this->transaksi_model->update_data($whered, $datahd, 'belid');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data succesfully Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
				</button> 
			</div>'
        );
        redirect('admin/Transaksi_Barang_Masuk/index_Transaksi_Barang_Masuk');
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
}
