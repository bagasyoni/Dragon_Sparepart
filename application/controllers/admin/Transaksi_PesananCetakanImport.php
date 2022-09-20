<?php

class Transaksi_PesananCetakanImport extends CI_Controller
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
            $this->session->set_userdata('kode_menu', 'T0031');
            $this->session->set_userdata('keyword_pp', '');
            $this->session->set_userdata('order_pp', 'NO_ID');
        }
    }

    var $column_order = array(null, null, null, 'NO_BUKTI', 'TGL', 'KET', 'NOTES', 'TIPE');
    var $column_search = array('NO_BUKTI', 'TGL', 'KET', 'NOTES', 'TIPE');
    var $order = array('NO_BUKTI' => 'asc');

    private function _get_datatables_query()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CI',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'FLAG3' => 'IMPORT',
            // 'TYP' => 'RND_CETAK',
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
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CI',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'FLAG3' => 'IMPORT',
            // 'TYP' => 'RND_CETAK',
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
                        <a style="background-color:  #00b386;" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars icon" style="font-size: 13px;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakanImport/update/' . $pp->NO_ID) . '"> <i class="fa fa-edit"></i> Edit</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakanImport/validasi/' . $pp->NO_ID) . '"> <i class="fa fa-check"></i> Validasi</a>
                            <a class="dropdown-item" href="' . site_url('admin/Transaksi_PesananCetakanImport/delete/' . $pp->NO_ID) . '" onclick="return confirm(&quot; Apakah Anda Yakin Ingin Menghapus? &quot;)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                            </div>';
                            // <a name="NO_ID" class="dropdown-item" href="#" onclick="' . $JASPER . '");"><i class="fa fa-print"></i> Print</a>
            $row[] = $no . ".";
            $row[] = $pp->NO_BUKTI;
            $row[] = date("d-m-Y", strtotime($pp->TGL));
            $row[] = date("d-m-Y", strtotime($pp->TGL_DIMINTA));
            $row[] = $pp->ARTICLE;
            $row[] = $pp->SIZE;
            $row[] = $pp->JUMLAH;
            $row[] = $pp->NOTES;
            $row[] = $pp->TIPE_CETAK;
            $row[] = $pp->M_LASTING;
            $row[] = $pp->PROSES;
            $row[] = $pp->FLAG;
            $row[] = "<img src='/Dragon_Sparepart/gambar/$pp->GAMBAR1' width='auto' height='120'>";
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

    public function index_Transaksi_PesananCetakanImport()
    {
        $per = $this->session->userdata['periode'];
        $dr= $this->session->userdata['dr'];
        $where = array(
            'PER' => $per,
            'DR' => $dr,
            'SUB' => 'CI',
            // 'FLAG' => 'PP',
            // 'FLAG2' => 'SP',
            // 'FLAG3' => 'IMPORT',
            // 'TYP' => 'RND_CETAK',
        );
        $data['pp'] = $this->transaksi_model->tampil_data($where, 'pp', 'NO_ID')->result();
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport', $data);
        $this->load->view('templates_admin/footer');
    }

    public function input()
    {
        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
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
        // PP / NOMER / DR / BULAN / TAHUN / CNC
        $bukti = 'PP' . '/' . $urut . '/' . "CI" . '/' . $dr . '/' . $romawi . '/' . $tahun;
        $this->session->set_userdata('bukti', $bukti);
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_form');
        $this->load->view('templates_admin/footer');
    }

    public function input_aksi()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $tgl = date("Y-m-d");
        $number = rand(0,100);

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        $na_gambar1                     = '-CTK-'; 
        $na_gambar2                     = '-SPT-';
        $gambar                         = $number;
        $config['file_name']            = $sub.$na_gambar1.$tgl.'-'.$gambar;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1') && ! $this->upload->do_upload('GAMBAR2') && ! $this->upload->do_upload('GAMBAR3')){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport', $data);
		}

        $per = $this->session->userdata['periode'];
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $nomer = $this->db->query("SELECT MAX(NO_BUKTI) as NO_BUKTI FROM pp WHERE PER='$per' AND SUB='CI' AND DR='$dr' AND FLAG='IMPORT' AND FLAG2='SP'")->result();
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
        // PP / NOMER / DR / BULAN / TAHUN / RND
        $bukti = 'PP' . '/' . $urut . '/' . "CI" . '/' . $dr . '/' . $romawi . '/' . $tahun;
        $datah = array(
            'NO_BUKTI' => $bukti,
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'JUMLAH' => str_replace(',', '', $this->input->post('JUMLAH', TRUE)),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'SIZE' => $this->input->post('SIZE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' => $this->upload->data('file_name'),
            'GAMBAR2' => $sub.$na_gambar2.$tgl.'-'.$this->upload->data('file_name'),
            'GAMBAR3' => $sub.'-'.$tgl.'-'.$this->upload->data('file_name'),
            'FLAG' => 'IMPORT',
            'FLAG2' => 'SP',
            'FLAG3' => '',
            'SUB' => 'CI',
            'TYP' => 'RND_CETAK',
            'DR' => $this->session->userdata['dr'],
            'PER' => $this->session->userdata['periode'],
            'USRNM' => $this->session->userdata['username'],
            'TG_SMP' => date("Y-m-d h:i a")
        );
        $this->transaksi_model->input_datah('pp', $datah);
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Inserted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakanImport/index_Transaksi_PesananCetakanImport');
    }

    public function update($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pp');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'ARTICLE' => $r['ARTICLE'],
            'JUMLAH' => $r['JUMLAH'],
            'NOTES' => $r['NOTES'],
            'M_LASTING' => $r['M_LASTING'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'TGL' => $r['TGL'],
            'DEVISI' => $r['DEVISI'],
            'SIZE' => $r['SIZE'],
            'TIPE_CETAK' => $r['TIPE_CETAK'],
            'PROSES' => $r['PROSES'],
            'GAMBAR1' => $r['GAMBAR1'],
            'GAMBAR2' => $r['GAMBAR2'],
            'GAMBAR3' => $r['GAMBAR3'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_update', $data);
        $this->load->view('templates_admin/footer');
    }

    public function update_aksi()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $tgl = date("Y-m-d");
        $number = rand(0,100);

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        $na_gambar1                     = '-CTK-'; 
        $na_gambar2                     = '-SPT-';
        $gambar                         = $number;
        $config['file_name']            = $sub.$na_gambar1.$tgl.'-'.$gambar;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1') && ! $this->upload->do_upload('GAMBAR2') && ! $this->upload->do_upload('GAMBAR3') ){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'JUMLAH' => str_replace(',', '', $this->input->post('JUMLAH', TRUE)),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'SIZE' => $this->input->post('SIZE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' => $this->upload->data('file_name'),
            // 'GAMBAR2' => $this->upload->data('file_name'),
            // 'GAMBAR3' => $this->upload->data('file_name'),
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakanImport/index_Transaksi_PesananCetakanImport');
    }

    public function validasi($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $ambildata = $this->master_model->edit_data($where, 'pp');
        $r = $ambildata->row_array();
        $data = [
            'NO_ID' => $r['NO_ID'],
            'NO_BUKTI' => $r['NO_BUKTI'],
            'ARTICLE' => $r['ARTICLE'],
            'JUMLAH' => $r['JUMLAH'],
            'NOTES' => $r['NOTES'],
            'M_LASTING' => $r['M_LASTING'],
            'TGL_DIMINTA' => $r['TGL_DIMINTA'],
            'TGL' => $r['TGL'],
            'DEVISI' => $r['DEVISI'],
            'SIZE' => $r['SIZE'],
            'TIPE_CETAK' => $r['TIPE_CETAK'],
            'PROSES' => $r['PROSES'],
            'GAMBAR1' => $r['GAMBAR1'],
            'GAMBAR2' => $r['GAMBAR2'],
            'GAMBAR3' => $r['GAMBAR3'],
            'VAL' => $r['VAL'],
        ];
        $this->load->view('templates_admin/header');
        $this->load->view('templates_admin/navbar');
        $this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_validasi', $data);
        $this->load->view('templates_admin/footer');
    }

    public function validasi_aksi()
    {
        $dr = $this->session->userdata['dr'];
        $sub = $this->session->userdata['sub'];
        $tgl = date("Y-m-d");
        $number = rand(0,100);

        $config['upload_path']          = './gambar/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg|bmp';
        $na_gambar1                     = '-CTK-'; 
        $na_gambar2                     = '-SPT-';
        $gambar                         = $number;
        $config['file_name']            = $sub.$na_gambar1.$tgl.'-'.$gambar;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('GAMBAR1') && ! $this->upload->do_upload('GAMBAR2') && ! $this->upload->do_upload('GAMBAR3') ){
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport_form', $error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			$this->load->view('admin/Transaksi_PesananCetakanImport/Transaksi_PesananCetakanImport', $data);
		}

        $NO_ID = $this->input->post('NO_ID');
        $datah = array(
            'NO_BUKTI' => $this->input->post('NO_BUKTI', TRUE),
            'ARTICLE' => $this->input->post('ARTICLE', TRUE),
            'JUMLAH' => str_replace(',', '', $this->input->post('JUMLAH', TRUE)),
            'NOTES' => $this->input->post('NOTES', TRUE),
            'M_LASTING' => $this->input->post('M_LASTING', TRUE),
            'TGL_DIMINTA' => date("Y-m-d", strtotime($this->input->post('TGL_DIMINTA', TRUE))),
            'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
            'DEVISI' => $this->input->post('DEVISI', TRUE),
            'SIZE' => $this->input->post('SIZE', TRUE),
            'TIPE_CETAK' => $this->input->post('TIPE_CETAK', TRUE),
            'PROSES' => $this->input->post('PROSES', TRUE),
            'GAMBAR1' => $this->upload->data('file_name'),
            'GAMBAR2' => $this->upload->data('file_name'),
            'GAMBAR3' => $this->upload->data('file_name'),
            'VAL' => '1',
        );
        $where = array(
            'NO_ID' => $NO_ID
        );
        $this->transaksi_model->update_data($where, $datah, 'pp');
        // $id = $this->input->post('ID', TRUE);
        // $q1 = "SELECT pp.NO_ID as ID,
        //         pp.NO_BUKTI AS NO_BUKTI,
        //         pp.TGL AS TGL,
        //         pp.NA_BRG AS NA_BRG,
        //         pp.PESAN AS PESAN,
        //         pp.M_LASTING AS M_LASTING,
        //         pp.JENIS AS JENIS,
        //         pp.KET AS KET,
        //         pp.TGL_DIMINTA AS TGL_DIMINTA,
        //         pp.TOTAL_QTY AS TOTAL_QTY,
        //         pp.TYP AS TYP,

        //         ppd.NO_ID AS NO_ID,
        //         ppd.REC AS REC,
        //         ppd.SIZE AS SIZE,
        //         ppd.QTY AS QTY,
        //         ppd.SATUAN AS SATUAN,
        //         ppd.TYP AS TYP
        //     FROM pp,ppd 
        //     WHERE pp.NO_ID=$id 
        //     AND pp.NO_ID=ppd.ID 
        //     ORDER BY ppd.REC";
        // $data = $this->transaksi_model->edit_data($q1)->result();
        // $NO_ID = $this->input->post('NO_ID');
        // $REC = $this->input->post('REC');
        // $SIZE = $this->input->post('SIZE');
        // $QTY = str_replace(',', '', $this->input->post('QTY', TRUE));
        // $SATUAN = $this->input->post('SATUAN');
        // $jum = count($data);
        // $ID = array_column($data, 'NO_ID');
        // $jumy = count($NO_ID);
        // $i = 0;
        // while ($i < $jum) {
        //     if (in_array($ID[$i], $NO_ID)) {
        //         $URUT = array_search($ID[$i], $NO_ID);
        //         $datad = array(
        //             'NO_BUKTI' => $this->input->post('NO_BUKTI'),
        //             'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
        //             'REC' => $REC[$URUT],
        //             'SIZE' => $SIZE[$URUT],
        //             'QTY' => str_replace(',', '', $QTY[$URUT]),
        //             'SATUAN' => $SATUAN[$URUT],
        //             'FLAG' => 'PP',
        //             'FLAG2' => 'SP',
        //             'TYP' => 'RND_IMPORT',
        //             'DR' => $this->session->userdata['dr'],
        //             'PER' => $this->session->userdata['periode'],
        //             'USRNM' => $this->session->userdata['username'],
        //             'TG_SMP' => date("Y-m-d h:i a")
        //         );
        //         $where = array(
        //             'NO_ID' => $NO_ID[$URUT]
        //         );
        //         $this->transaksi_model->update_data($where, $datad, 'ppd');
        //     } else {
        //         $where = array(
        //             'NO_ID' => $ID[$i]
        //         );
        //         $this->transaksi_model->hapus_data($where, 'ppd');
        //     }
        //     $i++;
        // }
        // $i = 0;
        // while ($i < $jumy) {
        //     if ($NO_ID[$i] == "0") {
        //         $datad = array(
        //             'ID' => $this->input->post('ID', TRUE),
        //             'REC' => $REC[$i],
        //             'NO_BUKTI' => $this->input->post('NO_BUKTI'),
        //             'TGL' => date("Y-m-d", strtotime($this->input->post('TGL', TRUE))),
        //             'SIZE' => $SIZE[$i],
        //             'QTY' => str_replace(',', '', $QTY[$i]),
        //             'SATUAN' => $SATUAN[$i],
        //             'FLAG' => 'PP',
        //             'FLAG2' => 'SP',
        //             'TYP' => 'RND_IMPORT',
        //             'DR' => $this->session->userdata['dr'],
        //             'PER' => $this->session->userdata['periode'],
        //             'USRNM' => $this->session->userdata['username'],
        //             'TG_SMP' => date("Y-m-d h:i a")
        //         );
        //         $this->transaksi_model->input_datad('ppd', $datad);
        //     }
        //     $i++;
        // }
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-success alert-dismissible fade show" role="alert"> 
                Data Berhasil Di Update.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakanImport/index_Transaksi_PesananCetakanImport');
    }

    public function delete($NO_ID)
    {
        $where = array('NO_ID' => $NO_ID);
        $this->transaksi_model->hapus_data($where, 'pp');
        // $where = array('ID' => $id);
        // $this->transaksi_model->hapus_data($where, 'ppd');
        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                Data succesfully Deleted.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>'
        );
        redirect('admin/Transaksi_PesananCetakanImport/index_Transaksi_PesananCetakanImport');
    }

    function delete_multiple()
    {
        $this->transaksi_model->remove_checked('pp');
        redirect('admin/Transaksi_PesananCetakanImport/index_Transaksi_PesananCetakanImport');
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
            WHERE bhn.KD_BHN=bhnd.KD_BHN AND bhnd.FLAG='SP' AND bhnd.DR = '$dr' AND bhnd.SUB='$sub' AND (bhn.KD_BHN LIKE '%$search%' OR bhn.NA_BHN LIKE '%$search%')
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

    public function getDataAjax_dragon()
    {
        $search = $this->input->post('search');
        $page = ((int)$this->input->post('page'));
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        $results = $this->db->query("SELECT KD_DEV, NM_DEV AS DEVISI, NAMA, AREA
            FROM rn_dev
            WHERE FLAG='CNC' AND (AREA LIKE '%$search%' OR NM_DEV LIKE '%$search%')
            GROUP BY AREA
            ORDER BY AREA LIMIT $xa,$perPage");
        $selectajax = array();
        foreach ($results->RESULT_ARRAY() as $row) {
            $selectajax[] = array(
                'id' => $row['AREA'],
                'text' => $row['AREA'],
                'DR' => $row['AREA'],
            );
        }
        $select['total_count'] =  $results->NUM_ROWS();
        $select['items'] = $selectajax;
        $this->output->set_content_type('application/json')->set_output(json_encode($select));
    }
}
