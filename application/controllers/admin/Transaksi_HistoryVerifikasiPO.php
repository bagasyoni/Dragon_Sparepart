<?php

class Transaksi_HistoryVerifikasiPO extends CI_Controller
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
            $this->session->set_userdata('kode_menu', 'T0020');
            $this->session->set_userdata('keyword_po', '');
            $this->session->set_userdata('order_beli', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI','NAMAS', 'DR', 'TGL', 'JTEMPO', 'NOTES');
    var $column_search = array( 'NO_BUKTI','NAMAS', 'DR', 'TGL', 'JTEMPO', 'NOTES');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata('periode');
        // $sub = $this->session->userdata('sub');
        $where = array(
            'DR' => $dr,
            // 'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '1',
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
        $dr = $this->session->userdata['dr'];
        $per = $this->session->userdata('periode');
        // $sub = $this->session->userdata('sub');
        $where = array(
            'DR' => $dr,
            // 'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '1',
        );
        $this->db->from('po');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function get_ajax_po()
    {
        $list = $this->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $po) {
            $JASPER = "window.open('JASPER/" . $po->NO_ID . "','', 'width=1000','height=900');";
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' class='singlechkbox' name='check[]' value='" . $po->NO_ID . "'>";
            $row[] = '<div class="dropdown">
                        <a style="background-color: #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_HistoryVerifikasiPO/update/' . $po->NO_ID) . '"> <i class="fa fa-edit"></i> Lihat</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_HistoryVerifikasiPO/delete/' . $po->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
                        </div>
                    </div>';
            $row[] = $no . ".";
            $row[] = $po->NO_BUKTI;
            $row[] = $po->KODES;
            $row[] = $po->NAMAS;
            $row[] = $po->DR;
            $row[] = date("d-m-Y", strtotime($po->TGL));
            $row[] = date("d-m-Y", strtotime($po->JTEMPO));
            $verifikasi = $po->VERIFIKASI_PO_SP;
            if($verifikasi == 1){
                $verifikasi = 'VERIFIKASI';
            } else {
                $verifikasi = 'BELUM TERVERIFIKASI';
            }
            $row[] = $verifikasi;
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

    public function index_Transaksi_HistoryVerifikasiPO()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata('sub');
        $per = $this->session->userdata('per');
        $this->session->set_userdata('judul', 'Transaksi Verifikasi PO');
        $where = array(
            'DR' => $dr,
            // 'PER' => $per,
            'KD_TTD1 !=' => '',
            'KD_TTD2 !=' => '',
            'FLAG2' => 'NB',
            'VERIFIKASI_PO_SP' => '1',
        );
        $data['historypo'] = $this->transaksi_model->tampil_data($where, 'po', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_HistoryVerifikasiPO/Transaksi_HistoryVerifikasiPO', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM po WHERE PER='$per' AND SUBDIV='$sub' AND FLAG='SP' AND FLAG2='$sub'")->result();
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
        $bukti = 'PO' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_HistoryVerifikasiPO/Transaksi_HistoryVerifikasiPO_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $per = $this->session->userdata['periode'];
        $sub = $this->session->userdata['sub'];
        $dr = $this->session->userdata['dr'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM po WHERE PER='$per' AND SUBDIV='$sub' AND FLAG='SP' AND FLAG2='$sub'")->result();
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
        $bukti = 'PO' . '/' . $urut . '/' . 'DR' . $dr . '/' . $romawi . '/' . $tahun . '/' . $sub;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'DR' => $this->input->post('DR', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
            'PROD' => $this->input->post('PROD', TRUE),
            'KURS' => $this->input->post('KURS', TRUE),
            'RATE' => $this->input->post('RATE', TRUE),
            'NOTESBL' => $this->input->post('NOTESBL', TRUE),
            'NOTESKRM' => $this->input->post('NOTESKRM', TRUE),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'KODES' => $this->input->post('KODES', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'AN' => $this->input->post('AN', TRUE),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'NO_PP' => $this->input->post('NO_PP', TRUE),
            'FLAG' => 'SP',
            'FLAG2' => $sub,
            'SUBDIV' => $sub,
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('po', $datah);
        $ID = $this->db->query("SELECT MAX(NO_ID) AS NO_ID FROM po WHERE NO_BUKTI = '$bukti' GROUP BY NO_BUKTI")->result();
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $KET = $this->input->post('KET');
        $SATUAN = $this->input->post('SATUAN');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        $i = 0;
        foreach ($REC as $a) {
            $datad = array(
                'ID' => $ID[0]->NO_ID,
                'NO_BUKTI' => $bukti,
                'REC' => $REC[$i],
                'KD_BHN' => $KD_BHN[$i],
                'NA_BHN' => $NA_BHN[$i],
                'KET' => $KET[$i],
                'SATUAN' => $SATUAN[$i],
                'QTY' => str_replace(',', '', $QTY[$i]),
                'FLAG' => 'SP',
                'FLAG2' => $sub,
                'PER' => $this->session->userdata['periode'],
                'USRNM' => $this->session->userdata['username'],
                'TG_SMP' => date("Y-m-d h:i a")
            );
            $this->transaksi_model->input_datad('pod', $datad);
            $i++;
        }
        $xx = $this->db->query("SELECT NO_BUKTI AS BUKTIX FROM po WHERE NO_BUKTI='$bukti'")->result();
        $no_bukti = $xx[0]->BUKTIX;
        $this->db->query("CALL pp_ins('" . $no_bukti . "')");
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_HistoryVerifikasiPO/index_Transaksi_HistoryVerifikasiPO');
    }

    public function update($id)
    {
        $q1 = "SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.DR AS DR,
                po.TGL AS TGL,
                po.JTEMPO AS JTEMPO,
                po.PROD AS PROD,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.NOTESBL AS NOTESBL,
                po.NOTESKRM AS NOTESKRM,
                po.NOTES AS NOTES,
                po.KODES AS KODES,
                po.NAMAS AS NAMAS,
                po.AN AS AN,
                po.TOTAL_QTY AS TOTAL_QTY,
                po.NO_PP AS NO_PP,
                po.VERIFIKASI_PO_SP,

                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.KET AS KET,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY
            FROM po, pod 
            WHERE po.NO_ID = $id 
            AND po.NO_ID = pod.ID 
            ORDER BY pod.REC";
        $data['history_po'] = $this->transaksi_model->edit_data($q1)->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_HistoryVerifikasiPO/Transaksi_HistoryVerifikasiPO_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'DR' => $this->input->post('DR', TRUE),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'JTEMPO' => date("Y-m-d", strtotime($this->input->post('JTEMPO', TRUE))),
            'PROD' => $this->input->post('PROD', TRUE),
            'KURS' => $this->input->post('KURS', TRUE),
            'RATE' => $this->input->post('RATE', TRUE),
            'NOTESBL' => $this->input->post('NOTESBL', TRUE),
            'NOTESKRM' => $this->input->post('NOTESKRM', TRUE),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'KODES' => $this->input->post('KODES', TRUE),
            'NAMAS' => $this->input->post('NAMAS', TRUE),
            'AN' => $this->input->post('AN', TRUE),
            'TOTAL_QTY' => str_replace(',', '', $this->input->post('TOTAL_QTY', TRUE)),
            'NO_PP' => $this->input->post('NO_PP', TRUE),
        );
        $where = array(
            'NO_ID' => $this->input->post('ID', TRUE)
        );
        $this->transaksi_model->update_data($where, $datah, 'po');
        $id = $this->input->post('ID', TRUE);
        $q1 = "SELECT po.NO_ID as ID,
                po.NO_BUKTI AS NO_BUKTI,
                po.DR AS DR,
                po.TGL AS TGL,
                po.JTEMPO AS JTEMPO,
                po.PROD AS PROD,
                po.KURS AS KURS,
                po.RATE AS RATE,
                po.NOTESBL AS NOTESBL,
                po.NOTESKRM AS NOTESKRM,
                po.NOTES AS NOTES,
                po.KODES AS KODES,
                po.NAMAS AS NAMAS,
                po.AN AS AN,
                po.TOTAL_QTY AS TOTAL_QTY,
                po.NO_PP AS NO_PP,

                pod.NO_ID AS NO_ID,
                pod.REC AS REC,
                pod.KD_BHN AS KD_BHN,
                pod.NA_BHN AS NA_BHN,
                pod.KET AS KET,
                pod.SATUAN AS SATUAN,
                pod.QTY AS QTY
            FROM po, pod 
            WHERE po.NO_ID = $id 
            AND po.NO_ID = pod.ID 
            ORDER BY pod.REC";
        $data = $this->transaksi_model->edit_data($q1)->result();
        $NO_ID = $this->input->post('NO_ID');
        $REC = $this->input->post('REC');
        $KD_BHN = $this->input->post('KD_BHN');
        $NA_BHN = $this->input->post('NA_BHN');
        $KET = $this->input->post('KET');
        $SATUAN = $this->input->post('SATUAN');
        $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
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
                    'KET' => $KET[$URUT],
                    'SATUAN' => $SATUAN[$URUT],
                    'QTY' => str_replace(',', '', $QTY[$URUT]),
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
                    'REC' => $REC[$i],
                    'KD_BHN' => $KD_BHN[$i],
                    'NA_BHN' => $NA_BHN[$i],
                    'KET' => $KET[$i],
                    'SATUAN' => $SATUAN[$i],
                    'QTY' => str_replace(',', '', $QTY[$i]),
                    'USRNM' => $this->session->userdata['username'],
                    'TG_SMP' => date("Y-m-d h:i a")
                );
                $this->transaksi_model->input_datad('pod', $datad);
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
        redirect('admin/Transaksi_HistoryVerifikasiPO/index_Transaksi_HistoryVerifikasiPO');
    }

    public function delete($id) {
        $where = array('NO_ID' => $id);
        $this->transaksi_model->hapus_data($where, 'po');
        $where = array('ID' => $id);
        $this->transaksi_model->hapus_data($where, 'pod');
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>');
        redirect('admin/Transaksi_HistoryPO/index_Transaksi_HistoryPO');
    }

    function delete_multiple() {
        $this->transaksi_model->remove_checked('po', 'pod');
        redirect('admin/Transaksi_HistoryVerifikasiPO/index_Transaksi_HistoryVerifikasiPO');
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
